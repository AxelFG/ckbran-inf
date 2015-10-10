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
 * HTML View class for OS Membership Component
 *
 * @static
 * @package		Joomla
* @subpackage	OS Membership
 * @since 1.5
 */
class HelpdeskproViewFields extends OSViewList
{	
	function _buildListArray(&$lists, $state) {
		$db = & JFactory::getDbo() ;
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
		$options[]= JHtml::_('select.option', 0, JText::_('HDP_ALL_CATEGORIES'));
		foreach ( $list as $listItem ) {
			$options[] = JHTML::_('select.option',  $listItem->id, '&nbsp;&nbsp;&nbsp;'. $listItem->treename );
		}
		
		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'text' ,
				'option.value' => 'value',
				'list.attr' => 'class="inputbox" onchange="submit();" ',
				'list.select' => $state->category_id
		));
				
		return true ;
	}		
}