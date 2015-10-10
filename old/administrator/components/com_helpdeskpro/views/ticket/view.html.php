<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

OSFactory::loadLibrary('viewform') ;
/**
 * HTML View class for OS Membership component
 *
 * @static
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproViewTicket extends OSViewForm
{
	function _buildListArray(&$lists, $item) {	
		$layout = $this->getLayout() ;
		if ($layout == 'form') {
			$this->_buildListArrayForm($lists, $item);
			return ;
		}	
		$db =  JFactory::getDbo();				
		$config = HelpdeskProHelper::getConfig();							 
		$sql = 'SELECT a.*, b.name FROM #__helpdeskpro_messages AS a LEFT JOIN #__users AS b ON a.user_id=b.id WHERE ticket_id='.$item->id.' ORDER BY a.id DESC';
		$db->setQuery($sql);
		$messages = $db->loadObjectList() ;
		
		$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories";
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
		$categories = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		
		//Get all custom fields
		$sql = 'SELECT * FROM #__helpdeskpro_fields WHERE published=1 AND (category_id = -1 OR id IN (SELECT field_id FROM #__helpdeskpro_field_categories WHERE category_id='.$item->category_id.')) ORDER BY ordering ';
		$db->setQuery($sql);
		$fields = $db->loadObjectList() ;
							
		$sql = 'SELECT title FROM #__helpdeskpro_statuses WHERE id='.$item->status_id;
		$db->setQuery($sql);
		$item->status = $db->loadResult();
		
		$sql = 'SELECT title FROM #__helpdeskpro_priorities WHERE id='.$item->priority_id;
		$db->setQuery($sql);
		$item->priority = $db->loadResult();
		//Get all custom fields value belong to this support ticket
		
		$sql = 'SELECT field_id, field_value FROM #__helpdeskpro_field_value WHERE ticket_id='.$item->id ;
		$db->setQuery($sql);
		$rows = $db->loadObjectList() ;		
		$fieldValues = array();
		if (count($rows)) {
			foreach ($rows as $row) {
				$fieldValues[$row->field_id] = $row->field_value ; 
			}
		}
		
		$sql = 'SELECT id, title FROM #__helpdeskpro_statuses WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$rowStatuses = $db->loadObjectList();
		
		$sql = 'SELECT id, title FROM #__helpdeskpro_priorities WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$rowPriorities = $db->loadObjectList();
		
		$dateFormat = HelpdeskProHelper::getConfigValue('date_format');
				
		//Process plugin here
		
		JPluginHelper::importPlugin( 'helpdeskpro' );
		$dispatcher =& JDispatcher::getInstance();		
		//Trigger plugins
		$results = $dispatcher->trigger( 'onViewTicket', array($item));				
		$this->fields = $fields ;		
		$this->messages = $messages ;
		$this->fieldValues = $fieldValues ;
		$this->rowStatuses = $rowStatuses ;
		$this->rowPriorities = $rowPriorities ;
		$this->categories = $categories ;
		$this->dateFormat = $dateFormat ;
		$this->config = $config ;
		$this->results = $results ;
										
		return true ;
	}
	/**
	 * Generate form allows admin creating new ticket
	 * @param array $lists
	 * @param object $item
	 */
	function _buildListArrayForm(&$lists, $item) {
		$db = & JFactory::getDbo() ;
		$config = HelpdeskProHelper::getConfig();
		$db = & JFactory::getDBO();		
		
		$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories";
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
				'list.select' => $item->category_id
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
			$this->assign('fieldJs', $fieldJs) ;
			$fields = $jcFields->renderCustomFields($categoryId, $fieldArray);
			$validations = $jcFields->renderJSValidation();
			$this->assign('fields', $fields);
			$this->assign('validations', $validations) ;
		} else {
			$customField = false ;
		}
		$this->assign('customField', $customField);
		$user = JFactory::getUser() ;
	
		$this->item = $item;
												
		$this->config = $config ;		
	}
	
	/**
	 * Build the toolbar for view list
	 */
	function _buildToolbar() {
		$layout = $this->getLayout() ;
		$viewName = $this->getName() ;
		if ($viewName == 'status')
			$controller = $viewName ;
		else
			$controller = OSInflector::singularize($viewName) ;
		$edit = JRequest::getVar('edit') ;
		$text = $edit ? JText::_($this->lang_prefix.'_EDIT') : JText::_($this->lang_prefix.'_NEW');
		JToolBarHelper::title(   JText::_( $this->lang_prefix.'_'.$viewName).': <small><small>[ ' . $text.' ]</small></small>' );
		if ($layout == 'form') {					
			JToolBarHelper::save($controller.'.save');						
		}		
		JToolBarHelper::cancel($controller.'.cancel');
	}
	
}