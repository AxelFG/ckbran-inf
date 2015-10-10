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
class HelpdeskProViewField extends OSViewForm
{
	function _buildListArray(&$lists, $item) {		
		$db = & JFactory::getDbo() ;
		$fieldTypes = array(
				0 => 'Textbox' ,
				1 => 'Textarea' ,
				2 => 'Dropdown' ,
				3 => 'Checkbox List' ,
				4 => 'Radio List' ,
				5 => 'Date Time',
				6 => 'Heading',
				7 => 'Message',
				8 => 'MultiSelect'
		);
		$options = array() ;
		$options[] = JHTML::_('select.option', -1, JText::_('HDP_FIELD_TYPE')) ;
		foreach ($fieldTypes As $value => $text) {
			$options[] = JHTML::_('select.option', $value, $text ) ;
		}		
				
		$lists['field_type'] = JHTML::_('select.genericlist', $options, 'field_type',' class="inputbox" ', 'value', 'text', $item->field_type);
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
		$options[]= JHtml::_('select.option', -1, JText::_('HDP_ALL_CATEGORIES'));
		foreach ( $list as $listItem ) {
			$options[] = JHTML::_('select.option',  $listItem->id, '&nbsp;&nbsp;&nbsp;'. $listItem->treename );
		}
		
		if ($item->id) {
			if ($item->category_id == -1) {
				$selecteds[] = JHTML::_('select.option', -1, -1);
			} else {
				$sql = 'SELECT category_id FROM #__helpdeskpro_field_categories WHERE field_id='.$item->id ;
				$db->setQuery($sql) ;
				$rowFields = $db->loadObjectList();
				for ($i = 0 , $n = count($rowFields) ; $i < $n ; $i++) {
					$rowField = $rowFields[$i] ;
					$selecteds[] = JHTML::_('select.option', $rowField->category_id, $rowField->category_id);
				}
			}
		} else {
			$selecteds = -1 ;
		}
		
		$lists['category_id'] = JHTML::_('select.genericlist', $options, 'category_id[]', array(
				'option.text.toHtml' => false ,
				'option.text' => 'text' ,
				'option.value' => 'value',
				'list.attr' => 'class="inputbox" multiple="multiple" size="6" ',
				'list.select' => $selecteds
		));
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('Yes'));
		$options[] = JHTML::_('select.option', 2, JText::_('No'));		
		$lists['required'] = JHTML::_('select.booleanlist', 'required', ' class="inputbox" ', $item->required) ;				
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('None')) ;
		$options[] = JHTML::_('select.option', 1, JText::_('Integer Number')) ;
		$options[] = JHTML::_('select.option', 2, JText::_('Number')) ;
		$options[] = JHTML::_('select.option', 3, JText::_('Email')) ;
		$lists['datatype_validation'] = JHTML::_('select.genericlist', $options, 'datatype_validation', 'class="inputbox"', 'value', 'text', $item->datatype_validation) ;		
				
		return true ;
	}
}