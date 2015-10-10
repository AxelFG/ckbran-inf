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
OSFactory::loadLibrary('viewlist') ;
/**
 * HTML View class for Helpdesk Pro component
 *
 * @static
 * @package		Joomla
* @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproViewTickets extends OSViewList
{	
	function _buildListArray(&$lists, $state) {
		$db = & JFactory::getDbo() ;
		$user = JFactory::getUser();
		$dateFormat = HelpdeskProHelper::getConfigValue('date_format');		
		if (!$user->authorise('core.admin')) {
			$managedCategoryIds = HelpdeskProHelper::getTicketCategoryIds($user->get('username'));
			if (count($managedCategoryIds)) {
				$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE id IN (".implode(',', $managedCategoryIds).") AND published=1 ORDER BY ordering";
			} else {
				$sql = "SELECT id, parent_id, title FROM #__helpdeskpro_categories WHERE published=1 ORDER BY ordering";
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
								
		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'text' ,
				'option.value' => 'value',
				'list.attr' => 'class="inputbox" onchange="submit();"',
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
				'list.attr' => 'class="inputbox" onchange="submit();" ',
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
				'list.attr' => 'class="inputbox" onchange="submit();" ',
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
		
		$this->dateFormat = $dateFormat ;
		$this->statusList = $statusList	;
		$this->priorityList = $priorityList ;
		
		return true ;
	}
	
	/**
	 * Custom toolbar
	 * @see OSViewList::_buildToolbar()
	 */
	function _buildToolbar() {
		$viewName = $this->getName() ;
		$controller = OSInflector::singularize($this->getName()) ;
		JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName).'_MANAGEMENT')) ;
		JToolBarHelper::deleteList(JText::_($this->lang_prefix.'_DELETE_'.strtoupper($this->getName()).'_CONFIRM') , $controller.'.remove');		
		JToolBarHelper::addNew($controller.'.add');	
		JToolBarHelper::custom('csv_export', 'export', 'export', 'Export Tickets', false);
	}
}