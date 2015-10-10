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
 * HTML View class for EventBooking component
 *
 * @static
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproViewCategory extends OSViewForm
{
	function _buildListArray(&$lists, $item) {				
		$db = JFactory::getDbo() ;
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
		$options[]= JHtml::_('select.option', 0, JText::_('HDP_SELECT_PARENT_CATEGORY'));
		foreach ( $list as $listItem ) {
			$options[] = JHTML::_('select.option',  $listItem->id, '&nbsp;&nbsp;&nbsp;'. $listItem->treename );
		}		
		
		$lists['parent_id'] = JHTML::_('select.genericlist', $options, 'parent_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'text' ,
				'option.value' => 'value',
				'list.attr' => 'class="inputbox" ',
				'list.select' => $item->parent_id
		));
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published);
		if (version_compare(JVERSION, '1.6.0', 'ge')) {
			$lists['access'] = JHtml::_('access.level', 'access', $item->access, ' class="inputbox" ', false);
		} else {
			$sql = 'SELECT id AS value, name AS text'
			. ' FROM #__groups'
			. ' ORDER BY id'
			;
			$db->setQuery($sql) ;
			$groups = $db->loadObjectList();
			$lists['access'] = JHTML::_('select.genericlist',   $groups, 'access', 'class="inputbox" ', 'value', 'text', $item->access) ;
		}
			
			
		
		return true ;
	}	
}