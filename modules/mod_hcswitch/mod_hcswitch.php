<?php
/*------------------------------------------------------------------------
# mod_insertfieldsattach
# ------------------------------------------------------------------------
# author    Cristian Grañó (percha.com)
# copyright Copyright (C) 2010 percha.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.percha.com
# Technical Support:  Forum - http://www.percha.com/
-------------------------------------------------------------------------*/
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
// include the helper file 
require_once(dirname(__FILE__).DS.'helper.php');

$sitepath = JPATH_BASE ;
JLoader::register('fieldattach',  $sitepath.'components/com_fieldsattach/helpers/fieldattach.php'); 
 

$args['id'] = $params->get('id');
$type = ModHCSwitch::getType($args);
$type = JRequest::getVar('view');
	$id = JRequest::getVar("id",0);	

if(!Is_int($id)){
    
    $tmp = explode(":", $id);
    $id = $tmp[0];
}


JPluginHelper::importPlugin('fieldsattachment'); // very important
$function  = "echo plgfieldsattachment_".$type."::getHTML(".$id.",". $args['id'].");"; 

 
// Initialize module parameters.
$params->def('function', $function);

//$item = ModInsertarticle::getArticles($args);

// include the template for display 
require JModuleHelper::getLayoutPath('mod_hcswitch', $params->get('layout', 'default'));
 

?>
