<?php


//no direct access


defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$id = JRequest::getVar('id');
if (!is_numeric($id)) {
	preg_match_all('!\d+!', $id, $nums);
	$id = (int)implode(' ', $nums[0]);
}

require_once(dirname(__FILE__).DS.'helper.php');

$function = '<ul>';
$function .= getBacklink($id,$params->get('id'),1);
$function .= getBacklink($id,$params->get('id'),0);
$function .= '</ul>';
 
	

// Initialize module parameters.

$params->def('function', $function);


// include the template for display 

require JModuleHelper::getLayoutPath('mod_backlink', $params->get('layout', 'default'));

?>

