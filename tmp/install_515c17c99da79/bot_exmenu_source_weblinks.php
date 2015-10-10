<?php
/**
* @version 1.0.0
* @author Daniel Ecer
* @package bot_exmenu_source_weblinks_1.0.0
* @copyright (C) 2005-2006 Daniel Ecer (de.siteof.de)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$_MAMBOTS->registerFunction('onLoadMenu', 'botExtendedMenuSourceWeblinks_onLoadMenu');

function botExtendedMenuSourceWeblinks_onLoadMenu(&$menuLoader, $name = '') {
	global $database;

	$botName		= 'bot_exmenu_source_weblinks';
	// load parameters
	$database->setQuery('SELECT m.params FROM #__mambots AS m WHERE element = \''.$botName.'\' AND folder = \'exmenu\'');
	$params	= new mosParameters($database->loadResult());
	$params->def('source_name', 'weblinks');
	
	if ($name != $params->get('source_name')) {
		return FALSE;
	}

	$rootMenuNode	=& $menuLoader->getRootMenuNode();
	$database->setQuery('SELECT * FROM #__weblinks WHERE published = 1 ORDER BY hits DESC, title LIMIT 10');
	$rows	= $database->loadObjectList();
	foreach(array_keys($rows) as $key) {
		$row			=& $rows[$key];
		$menuNode	=& $menuLoader->getEmptyMenuNode();
		$menuNode->type		= 'url';
		$menuNode->link		= $row->url;
		$menuNode->name		= $row->title;
		$menuLoader->addMenuNode($rootMenuNode, $menuNode);
	}
	return TRUE;
}

?>