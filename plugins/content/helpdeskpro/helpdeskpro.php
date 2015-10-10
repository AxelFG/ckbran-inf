<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;
/**
 * Heldesk Pro content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		2.5
 */
class plgContentHelpdeskpro extends JPlugin {
	function onContentPrepare($context, &$article, &$params, $limitstart) {		
		$app = & JFactory::getApplication() ;
		if ($app->getName() != 'site') {
			return ;
		}
		if ( strpos( $article->text, 'helpdeskpro' ) === false ) {
			return true;
		}
		$regex = "#{helpdeskpro}#s";
		$article->text = preg_replace_callback( $regex, array( &$this, '_displayForm') , $article->text );
		return true;
	}
	/**
	 * Replace callback function
	 * 
	 * @param array $matches
	 */
	function _displayForm($matches) {
		$document = JFactory::getDocument() ;					
		require_once JPATH_ROOT.'/components/com_helpdeskpro/helper/helper.php';
		require_once JPATH_ROOT.'/components/com_helpdeskpro/helper/fields.php';		
		$db = & JFactory::getDbo() ;
		$user = JFactory::getUser() ;
		HelpdeskProHelper::loadLanguage();
		$document->addStyleSheet(JURI::base(true).'/components/com_helpdeskpro/assets/css/style.css') ;
		if (version_compare(JVERSION, '3.0', 'lt')) {
			HelpdeskProHelper::loadBootstrap() ;
		}				
		$config = HelpdeskProHelper::getConfig();		
		$userId = $user->get('id');
		$Itemid = JRequest::getInt('Itemid', 0) ;
		if (!$userId && !$config->allow_public_user_submit_ticket) {
			//Redirect user to login page
			$return = JRoute::_('index.php?option=com_helpdeskpro&task=ticket.add&Itemid='.$Itemid);
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return='.base64_encode($return), JText::_('HDP_LOGIN_TO_SUBMIT_TICKET'));
		}		
		//Initialize the view object
		$viewConfig = array() ;
		$viewConfig['name'] = 'form' ;
		$viewConfig['base_path'] = JPATH_ROOT.'/plugins/content/helpdeskpro' ;
		$viewConfig['template_path'] = JPATH_ROOT.'/plugins/content/helpdeskpro/tmpl' ;
		$viewConfig['layout'] = 'form' ;
		require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/view.php';
		$jView =  new LegacyView($viewConfig) ;
		
		
		$db = & JFactory::getDBO();
		$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE published=1 AND access IN (".implode(',', $user->getAuthorisedViewLevels()).')';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		$children = array();
		if ($rows)
		{
			// first pass - collect children
			foreach ( $rows as $v )
			{
				$pt 	= $v->parent_id ;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$options 	= array();
		$options[]= JHtml::_('select.option', 0, JText::_('HDP_CHOOSE_CATEGORY'));
		foreach ( $list as $listItem ) {
			$options[] = JHTML::_('select.option',  $listItem->id, '&nbsp;&nbsp;&nbsp;'. $listItem->treename );
		}
		
		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'text' ,
				'option.value' => 'value',
				'list.attr' => 'class="inputbox" onchange="showFields(this.form);" ',
				'list.select' => 0
		));
		
		
		$options 	= array();
		$options[]= JHtml::_('select.option', 0, JText::_('HDP_CHOOSE_PRIORITY'), 'id', 'title');
		$sql = 'SELECT id, title FROM #__helpdeskpro_priorities WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$rowPriorities = $db->loadObjectList() ;
		$options = array_merge($options, $rowPriorities) ;
		
		$lists['priority_id'] = JHTML::_('select.genericlist', $options, 'priority_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'title' ,
				'option.key' => 'id',
				'list.attr' => 'class="inputbox" ',
				'list.select' => $config->default_ticket_priority_id
		));
		$categoryId = 0 ;
		$jcFields = new JCFields();
		if ($jcFields->getTotal()) {
			$customField = true ;
			$fieldArray = JCFields::getAssoc();
			$fieldJs = "fields = new Array();\n" ;
			foreach ($fieldArray  as  $catId => $fieldList) {
				$fieldJs .= ' fields['.$catId.'] = new Array("'.implode('","', $fieldList).'");'."\n";
			}
			$jView->fieldJs = $fieldJs ;
			$fields = $jcFields->renderCustomFields($categoryId, $fieldArray);
			$validations = $jcFields->renderJSValidation();
			$jView->fields = $fields ;
			$jView->validations = $validations ;
		} else {
			$customField = false ;
		}
		$jView->assign('customField', $customField);		
		
		$jView->lists = $lists ;
		$jView->Itemid = $Itemid ;
		$jView->config = $config ;
		$jView->userId = $userId ;
																	
		ob_start();		
		$jView->display() ;	
		$text = ob_get_contents() ;
		ob_end_clean();
		return $text ;				
	}	
}