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

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModInsertfieldsattach {
    
	public function getType($args){
		$db = &JFactory::getDBO();
		$item = ""; 
		$id = $args['id'];
                
		if($id > 0){ 
			$query  = "select type ";
			$query .= "FROM #__fieldsattach  WHERE id =".$id." AND published=1 " ;

			//echo $query;
			$db->setQuery($query);
			$item = $db->loadResult();
		}
		
		return $item;
	} 

}
