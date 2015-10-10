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

require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/view.php';

/**

 * HTML View class for Helpdesk Pro component

 *

 * @static

 * @package		Joomla

* @subpackage	Helpdesk Pro

 * @since 1.5

 */

class HelpdeskproViewTickets extends LegacyView

{	

	

	function display($tpl = null)

	{		

		$user = JFactory::getUser() ;


		$app = JFactory::getApplication();

		$pathway = $app->getPathway();

		$state = $this->get('State');

		$items		= & $this->get( 'Data');		

		$pagination = & $this->get( 'Pagination' );

				

			

		$this->state = $state ;

	

		$lists = array() ;

		$lists['order_Dir'] = $state->filter_order_Dir;

		$lists['order'] = $state->filter_order ;

		$lists['filter_state'] = JHTML::_('grid.state', $state->filter_state);

		$this->_buildListArray($lists, $state) ;

		$this->assignRef('lists',		$lists);

		$this->assignRef('items',		$items);

		$this->assignRef('pagination',	$pagination);

			


		$db = & JFactory::getDbo() ;
		
		$sql = 'SELECT title FROM #__helpdeskpro_categories WHERE id='.$state->category_id ;

		$db->setQuery($sql) ;

		$categoryTitle = $db->loadResult() ;

		if (isset($_GET['category_id'])) $pathway->addItem($categoryTitle, '');
			

		parent::display($tpl);

	}

	

	function _buildListArray(&$lists, $state) {

		$user = JFactory::getUser() ;

		$db = & JFactory::getDbo() ;

		$dateFormat = HelpdeskProHelper::getConfigValue('date_format');

		if (!$user->authorise('core.admin')) {

			$managedCategoryIds = HelpdeskProHelper::getTicketCategoryIds($user->get('username'));

			if (count($managedCategoryIds)) {

				$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE id IN (".implode(',', $managedCategoryIds).") AND published=1 ORDER BY ordering";

			} elseif (!$user->get('id')) {
	
				$userId = 0;	
	
				$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE published=1 ORDER BY ordering";		
	
			} else {				

				$userId = $user->get('id');

				$email = $user->get('email');

				$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE published=1 AND id IN (SELECT DISTINCT category_id FROM #__helpdeskpro_tickets AS t WHERE t.user_id=$userId OR t.email='$email') ORDER BY ordering";

			}	
		} else {

			$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE published=1 ORDER BY ordering";

		}	

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


		$lists['cat'] = '<ul class="nav menu">';

		foreach ( $list as $listItem ) {


			if ($listItem->id == $state->category_id && isset($_GET['category_id'])) $active = ' class="active"';

			else $active = '';

			$lists['cat'] .= '<li'.$active.'><a href="'.JRoute::_('index.php?option=com_helpdeskpro&view=tickets&category_id='.$listItem->id).'">'.$listItem->treename.'</a></li>';

		}							

		$lists['cat'] .= '</ul>';
								

		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id', array(

				'option.text.toHtml' => false ,

				'option.text' => 'text' ,

				'option.value' => 'value',

				'list.attr' => 'class="inputbox" ',

				'list.select' => $state->category_id

		));

		

		$options 	= array();

		$options[]= JHtml::_('select.option', -1, JText::_('HDP_SELECT'), 'id', 'title');

		$options[]= JHtml::_('select.option', 0, JText::_('HDP_ALL_STATUSES'), 'id', 'title');

		$sql = 'SELECT id, title FROM #__helpdeskpro_statuses WHERE published=1 ORDER BY ordering';

		$db->setQuery($sql);

		$rowStatuses = $db->loadObjectList();

		$options = array_merge($options, $rowStatuses) ;

		

		$lists['status_id'] = JHTML::_('select.genericlist', $options, 'status_id', array(

				'option.text.toHtml' => false ,

				'option.text' => 'title' ,

				'option.key' => 'id',

				'list.attr' => 'class="inputbox" onchange="submit();"',

				'list.select' => $state->status_id

		));

		

		$options 	= array();

		$options[]= JHtml::_('select.option', 0, JText::_('HDP_ALL_PRIORITIES'), 'id', 'title');

		$sql = 'SELECT id, title FROM #__helpdeskpro_priorities WHERE published=1 ORDER BY ordering';

		$db->setQuery($sql);

		$rowPriorities = $db->loadObjectList() ;

		$options = array_merge($options, $rowPriorities) ;			

		

		$lists['priority_id'] = JHTML::_('select.genericlist', $options, 'priority_id', array(

				'option.text.toHtml' => false ,

				'option.text' => 'title' ,

				'option.key' => 'id',

				'list.attr' => 'class="inputbox" ',

				'list.select' => $state->priority_id

		));

				

		$statusList = array();

		foreach ($rowStatuses as $status) {

			$statusList[$status->id] = $status->title ;

		}

				

		$priorityList = array();

		foreach ($rowPriorities as $priority) {

			$priorityList[$priority->id] = $priority->title ;	

		}

		$sql = 'SELECT title FROM #__helpdeskpro_categories WHERE id='.$state->category_id ;

		$db->setQuery($sql) ;

		$categoryTitle = $db->loadResult() ;

		$sql = "SELECT config_value FROM #__helpdeskpro_configs WHERE config_key='home_page_text'";

		$db->setQuery($sql);

		$home_page_text = $db->loadResult() ;
	
		$this->home_page_text = $home_page_text;
								

		$this->dateFormat = $dateFormat ;

		$this->statusList = $statusList	;

		$this->priorityList = $priorityList ;
		
		if(isset($_GET['category_id']))

			$this->category_id = $state->category_id ;

		else

			$this->category_id = 0 ;

		$this->categoryTitle = $categoryTitle ;

				

		return true ;

	}

}