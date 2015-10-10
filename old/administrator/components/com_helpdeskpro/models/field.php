<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die ;

OSFactory::loadLibrary('model') ;
/**
 * Helpdesk Pro Component Field Model
 *
 * @package		Joomla
* @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproModelField extends OSModel
{
	function store(&$data)
	{		
		$db = & JFactory::getDBO();	
		$row = & $this->getTable('Helpdeskpro', 'Field');	
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}					
		if (!$row->id) {
			$sql = ' SELECT MAX(ordering) + 1 AS ordering FROM #__helpdeskpro_fields ';
			$this->_db->setQuery($sql) ;
			$row->ordering = $this->_db->loadResult();	
			if ($row->ordering == 0)
				$row->ordering = 1 ;
		}
		if (!isset($data['category_id']) || $data['category_id'][0] == -1) {
			$row->category_id = -1 ;
		} else {
			$row->category_id = 1 ;
		}		
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$sql = 'DELETE FROM #__helpdeskpro_field_categories WHERE field_id='.$row->id;
		$db->setQuery($sql) ;
		$db->query();
		if ($row->category_id != -1) {
			if (isset($data['category_id'])) {
				$categoryIds = $data['category_id'] ;
				for ($i = 0 , $n = count($categoryIds); $i < $n ; $i++) {
					$categoryId = $categoryIds[$i] ;
					$sql = "INSERT INTO #__helpdeskpro_field_categories(field_id, category_id) VALUES($row->id, $categoryId);";
					$db->setQuery($sql) ;
					$db->query();
				}
			}
		}			
			
		return true;
	}	
}