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

require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/model.php';
/**
 * Helpdesk Pro Component Configuration Model
 *
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproModelConfiguration extends LegacyModel
{
	/**
	 * Containing all config data,  store in an object with key, value
	 *
	 * @var object
	 */
	var $_data = null;
	
	function __construct() {
		parent::__construct();
	}	
	/**
	 * Get configuration data
	 *
	 */
	function getData() {
		if (empty($this->_data)) {
			$config = new stdClass ;
			$sql = 'SELECT config_key, config_value FROM #__helpdeskpro_configs';
			$this->_db->setQuery($sql);
			$rows = $this->_db->loadObjectList();
			if (count($rows)) {
				for ($i = 0, $n = count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$key = $row->config_key;
					$value = $row->config_value;
					$config->$key = stripslashes($value);						
				}	
			} else {
				$config = new stdClass() ;				 																																
			}
			$this->_data = $config;		
		}	
				
		return $this->_data ;
	}
	/**
	 * Store the configuration data
	 *
	 * @param array $post
	 */
	function store($data) {
		jimport('joomla.filesystem.folder') ;
		$row = & $this->getTable('Helpdeskpro', 'Config');		
		$sql = 'TRUNCATE TABLE #__helpdeskpro_configs';
		$this->_db->setQuery($sql);
		$this->_db->query();
		foreach ($data as $key=>$value) {
			$row->id = 0 ;
			if (is_array($value))
				$value = implode(',', $value);	
			$row->config_key = $key ;
			$row->config_value = $value ;
			$row->store();			
		}
										
		return true;
	}
}