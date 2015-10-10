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
 
$option = JRequest::getVar("option");

if ($option == 'com_content') {

	// include the helper file 
	require_once(dirname(__FILE__).DS.'helper.php');
	
	$sitepath = JPATH_BASE ;
	JLoader::register('fieldattach',  $sitepath.'components/com_fieldsattach/helpers/fieldattach.php'); 
	 
	
	$args['id'] = $params->get('id');
	$type = ModInsertfieldsattach::getType($args);
	$id = JRequest::getVar("id",0);
	$view = JRequest::getVar("view");
	
	if(!Is_int($id)){
	    
	    $tmp = explode(":", $id);
	    $id = $tmp[0];
	}
	
	$db = &JFactory::getDBO(  );
	
		$query = 'SELECT  positionarticle  FROM #__fieldsattach WHERE id ='.$args['id'];
	
	    $db->setQuery( $query );
	
	$cattrue = $db->loadResult(); // if 1 then category else - article
	
	
	$fff = "plgfieldsattachment_".$type."::getHTML(".$id.",". $args['id'].");";
	eval("\$fattachout = \"$fff\";");
	
	JPluginHelper::importPlugin('fieldsattachment'); // very important
	
	eval("\$fattach = ".$fattachout.";");
	
	if ($fattach) {
		if (($view == 'article' && !$cattrue) || ($view == 'category' && $cattrue))
			$function  = "echo plgfieldsattachment_".$type."::getHTML(".$id.",". $args['id'].");"; 
	}
	else
		$function = '';
	
	
	// Initialize module parameters.
	$params->def('function', $function);
	$params->def('fattachout', $fattachout);
	
	//$item = ModInsertarticle::getArticles($args);
	
	// include the template for display 
	require JModuleHelper::getLayoutPath('mod_insertafieldsattach', $params->get('layout', 'default'));
}

else
	$function = '';

?>
