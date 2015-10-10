<?php

/**

 * @version		1.1.1

 * @package		Joomla

 * @subpackage	Helpdesk Pro

 * @author  Tuan Pham Ngoc

 * @copyright	Copyright (C) 2011 Ossolution Team

 * @license		GNU/GPL, see LICENSE.php

 */



// Check to ensure this file is included in Joomla!

defined('_JEXEC') or die ;

require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/view.php';

/**

 * HTML View class for Helpdesk Pro component

 *

 * @static

 * @package		Joomla

 * @subpackage	Helpdesk Pro

 * @since 1.5

 */

class HelpdeskproViewTicket extends LegacyView

{

	function display($tpl = null)

	{					

		$layout = $this->getLayout();

		if ($layout == 'form') {

			$this->_displayForm($tpl) ;

			return ;

		}												

		$db =  JFactory::getDbo();

		$user = JFactory::getUser() ;

		$item = $this->get('Data') ;

		if (!$item->id) {

			$app = JFactory::getApplication() ;

			$app->redirect('index.php', JText::_('HDP_INVALID_TICKET'));

		}

			

		$canAccess = HelpdeskProHelper::checkTicketAccess($item);


		$app = JFactory::getApplication();

		$pathway = $app->getPathway();


		$state = $this->get('State');			

		$this->state = $state ;

		$lists = array() ;

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

				

		if ($user->id == $item->user_id || $item->is_ticket_code)

			$isCustomer = 1;

		else

			$isCustomer = 0 ;

		

		if ($isCustomer && ($item->status_id ==$config->closed_ticket_status)) {

			$canComment = false ;

		} else {

			$canComment = true ;

		}

		$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE published=1 ORDER BY ordering";

		$db->setQuery($sql);

		$list = $db->loadObjectList();

		$lists['cat'] = '<ul class="nav menu">';

		foreach ( $list as $listItem ) {

			$lists['cat'] .= '<li><a href="'.JRoute::_('index.php?option=com_helpdeskpro&view=tickets&category_id='.$listItem->id).'">'.$listItem->title.'</a></li>';

		}							

		$lists['cat'] .= '</ul>';

		$options 	= array();

		$options[]= JHtml::_('select.option', 0, JText::_('HDP_ALL_CATEGORIES'));

		foreach ( $list as $listItem ) {

			$options[] = JHTML::_('select.option',  $listItem->id, '&nbsp;&nbsp;&nbsp;'. $listItem->treename );

		}

		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id', array(

				'option.text.toHtml' => false ,

				'option.text' => 'text' ,

				'option.value' => 'value',

				'list.attr' => 'class="inputbox" ',

				'list.select' => $state->category_id

		));


		$Itemid = JRequest::getInt('Itemid') ;

				

		$this->fields = $fields ;		

		$this->messages = $messages ;

		$this->fieldValues = $fieldValues ;

		$this->rowStatuses = $rowStatuses ;

		$this->rowPriorities = $rowPriorities ;

		$this->categories = $categories ;

		$this->dateFormat = $dateFormat ;

		$this->config = $config ;

		$this->item = $item ;

		$this->lists['cat'] = $lists['cat'] ;

		$this->isCustomer = $isCustomer ;

		$this->canAccess = $canAccess ;		

		$this->canComment = $canComment ;

		$this->Itemid = $Itemid ;		

		
			
		$sql = 'SELECT title FROM #__helpdeskpro_categories WHERE id='.$item->category_id ;

		$db->setQuery($sql) ;

		$categoryTitle = $db->loadResult() ;

		$pathway->addItem($categoryTitle, 'index.php?option=com_helpdeskpro&category_id='.$item->category_id);
		$pathway->addItem($this->item->subject, '');


		/* FORM FUNCTION STARTS HERE */

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

		$options[]= JHtml::_('select.option', 0, JText::_('HDP_ALL_CATEGORIES'));

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
				

		$this->lists = $lists ;

		$this->Itemid = $Itemid ;

		$this->config = $config ;

		$this->userId = $userId ;

		$this->lists['category_id'] = $lists['category_id'];

		//	PARENT IS A PART OF BOTH FUNCTIONS


		parent::display($tpl);

	}


	function _displayForm($tpl) {

		$db = & JFactory::getDbo() ;

		$user = JFactory::getUser() ;		

		$config = HelpdeskProHelper::getConfig();

		$userId = $user->get('id');

		$Itemid = JRequest::getInt('Itemid', 0) ;

		if (!$userId && !$config->allow_public_user_submit_ticket) {

			//Redirect user to login page

			$return = JRoute::_('index.php?option=com_helpdeskpro&task=ticket.add&Itemid='.$Itemid);

			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return='.base64_encode($return), JText::_('HDP_LOGIN_TO_SUBMIT_TICKET'));

		}

		

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

		

		

		

		$options 	= array();

		$options[]= JHtml::_('select.option', 0, JText::_('HDP_CHOOSE_PRIORITY'), 'id', 'title');

		$sql = 'SELECT id, title FROM #__helpdeskpro_priorities WHERE published=1 ORDER BY ordering';

		$db->setQuery($sql);

		$rowPriorities = $db->loadObjectList() ;

		$options = array_merge($options, $rowPriorities) ;



		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id', array(

				'option.text.toHtml' => false ,

				'option.text' => 'text' ,

				'option.value' => 'value',

				'list.attr' => 'class="inputbox" onchange="showFields(this.form);" ',

				'list.select' => 0

		));
		

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
				

		$this->lists = $lists ;

		$this->Itemid = $Itemid ;

		$this->config = $config ;

		$this->userId = $userId ;

		$this->lists['category_id'] = $lists['category_id'];

		

		parent::display($tpl);

	}

}