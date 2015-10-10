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

OSFactory::loadLibrary('modellist');

class HelpdeskproModelFields extends OSModelList {
	function __construct($config) {		
		$config['title_field'] = array('a.name', 'a.title');
		$config['state_vars'] = array(									  
					'category_id' => array(0, 'int', true)															
				);
				
		parent::__construct($config) ;
	}
	function _buildContentWhereArray() {
		$state = $this->getState() ;
		$where = parent::_buildContentWhereArray() ;
		if ($state->category_id >0)
			$where[] = ' (a.category_id=-1 OR a.id IN (SELECT field_id FROM #__helpdeskpro_field_categories WHERE category_id='.$state->category_id.'))';
			
		return $where ;
	}	
}