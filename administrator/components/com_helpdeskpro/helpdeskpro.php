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

//Require the controller
error_reporting(0);
require_once JPATH_ROOT.'/components/com_helpdeskpro/helper/helper.php';
require_once JPATH_ROOT.'/components/com_helpdeskpro/helper/fields.php';


//OS Framework
require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/libraries/defines.php';
require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/libraries/inflector.php';
require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/libraries/factory.php';

OSInflector::addWord('status', 'statuses');

$command = JRequest::getVar('task', 'display');
// Check for a controller.task command.
if (strpos($command, '.') !== false)
{
	list ($controller, $task) = explode('.', $command);	
	$path = JPATH_COMPONENT . '/controllers/' . $controller.'.php';
	if (file_exists($path)) {		
		require_once $path;
		$className =  'HelpdeskproController'.ucfirst($controller) ;
		$controller	= new $className() ;
	} else {
		//Fallback to default controller
		OSFactory::loadLibrary('controller');
		$controller = new OSController( array('entity_name' => $controller, 'name' => 'Helpdeskpro'));	
	}			
	JRequest::setVar('task', $task);
}
else
{			
	$path =  JPATH_COMPONENT.'/controller.php' ;	
	require_once $path;
	$className =  'HelpdeskproController' ;	
	$controller	= new $className() ;
}

//Ass css files

$document = & JFactory::getDocument() ;
$document->addStyleSheet(JURI::base(true).'/components/com_helpdeskpro/assets/css/style.css') ;
if (version_compare(JVERSION, '3.0', 'lt')) {
	HelpdeskProHelper::loadBootstrap() ;
}
// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>