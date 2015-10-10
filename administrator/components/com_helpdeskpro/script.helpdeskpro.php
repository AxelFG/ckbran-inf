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
class com_helpdeskproInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		$this->updateDatabaseSchema() ;		
	}
	
	function update($parent)
	{
		$this->updateDatabaseSchema() ;
	}
	
	function updateDatabaseSchema() {
		error_reporting(0);
		jimport('joomla.filesystem.file') ;		
		$db = & JFactory::getDBO();
		$sql = 'SELECT COUNT(*) FROM #__helpdeskpro_configs';
		$db->setQuery($sql) ;
		$total = $db->loadResult();
		if (!$total) {
			$configSql = JPATH_ADMINISTRATOR.'/components/com_helpdeskpro/sql/config.helpdeskpro.sql' ;
			$sql = JFile::read($configSql) ;
			$queries = $db->splitSql($sql);
			if (count($queries)) {
				foreach ($queries as $query) {
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}
	}	
}

