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

class ModHCSwitch {
    
	function hcCheck($id)
	{
		$type = JRequest::getVar('view');
		if ($type == 'article')
			$id = ModHCSwitch::getCategory($id);
		$iter = 0;
		while (isset($id) && $id != 0 && $id != $previd)
		{
			if ($id == 9)
				return 'hospital';
			elseif ($id == 10)
				return 'clinic';
			error_log($id."-ID Test round ".$iter++."\n", 3, "/var/www/ckbran/data/www/ckbran.ru/logs/hcs.log");
			$previd = $id;
			$id = ModHCSwitch::getParent($id);
		}
		return 0;
	}
	
	function hcSwitch($id)
	{
		if (ModHCSwitch::hcCheck == 'hospital')
			ModHCSwitch::getType();
		elseif (ModHCSwitch::hcCheck == 'clinic')
			ModHCSwitch::getBacklink();
		return;
	}
	
	function getParent($id)
	{
		$db = &JFactory::getDBO(  );
		$query = "SELECT parent_id from #__categories WHERE id = ".$id." LIMIT 1";
		$db->setQuery($query);
		$result = $db->loadResult();
		if ($result > 1) return $result;
		else return $id;
	}
	
	function getCategory($id)
	{
		$db = &JFactory::getDBO(  );
		$query = "SELECT catid from #__content WHERE id = ".$id." LIMIT 1";
		$db->setQuery($query);
		return $result = $db->loadResult();
	}
	
	function getLink($id)
	{
		$items = array();
		
		$db = &JFactory::getDBO(  );
			
		$db->setQuery("SELECT value from #__fieldsattach_values WHERE articleid = ".$id." AND fieldsid = 33 LIMIT 1");
		
		if ($result = $db->loadResult())
			$items = array_merge($items,explode (",",$result));
	
		$db->setQuery("SELECT value from #__fieldsattach_categories_values WHERE catid = ".$id." AND fieldsid = 32 LIMIT 1");
		
		if ($result = $db->loadResult())
			$items = array_merge($items,explode (",",$result));
				   
			
		if (count($items)) 
		{
			
			$function = '<ul>';
			$function .= '<li class="active"><span>В Стационаре</span></li>';
			
			if (count($items) > 1)
			{
				$function .= '<li><span>В Поликлинике</span>';
				$function .= '<ul class="popup">';
					
					foreach ($items as $item) {
						
						$parent = ModHCSwitch::getParent(ModHCSwitch::getCategory($item));
					
						$function .= '<li><a href="'.ContentHelperRoute::getArticleRoute($item, $parent).'">'.ModHCSwitch::getTitle($item,0).'</a></li>';
							
					}    
					
				$function .= '</ul>';
				$function .= '</li>';
			}
			else 
				$function .= '<li><a href="'.ContentHelperRoute::getArticleRoute($items[0], $parent).'">В Поликлинике</a></li>';
	
			$function .= '</li>';
			$function .= '</ul>';
		}
		else return;
	
		return $function;
	}
	
	function getBacklink($id)
	{
		$db = &JFactory::getDBO(  );
			
		//For Articles
		
		$db->setQuery($query = "SELECT articleid from #__fieldsattach_values WHERE (value LIKE '%,".$id.",%' OR value LIKE '".$id.",%' OR value LIKE '%,".$id."' OR value LIKE '".$id."') AND fieldsid = 33");
			   
		$articles = $db->loadObjectList();
		
		// For Categories
		
		$db->setQuery("SELECT catid from #__fieldsattach_categories_values WHERE (value LIKE '%,".$id.",%' OR value LIKE '".$id.",%' OR value LIKE '%,".$id."' OR value LIKE '".$id."') AND fieldsid = 32");
				  
		$categories = $db->loadObjectList();
		
		if (count($articles)+count($categories)) 
		{
		
		$function = '<ul>';
		$function .= '<li class="active"><span>В Поликлинике</span></li>';

			if (count($articles)+count($categories) == 1) $single=true;
			else
			{
				$function .= '<li><span>В Стационаре</span>';
				$function .= '<ul class="popup">';
			}
				 
				foreach ($categories as $item) {
				
					$parent = ModHCSwitch::getParent($item->catid);
				
					if ($single)	
						$function .= '<li><a href="'.ContentHelperRoute::getCategoryRoute($item->catid, $parent).'">В Стационаре</a></li>';
					else
						$function .= '<li><a href="'.ContentHelperRoute::getCategoryRoute($item->catid, $parent).'">'.ModHCSwitch::getTitle($item->catid,1).'</a></li>';
						
				}   
				foreach ($articles as $item) {
				
					$parent = ModHCSwitch::getParent(ModHCSwitch::getCategory($item->articleid));
				
					if ($single)
						$function .= '<li><a href="'.ContentHelperRoute::getArticleRoute($item->articleid, $parent).'">В Стационаре</a></li>';		
					else
						$function .= '<li><a href="'.ContentHelperRoute::getArticleRoute($item->articleid, $parent).'">'.ModHCSwitch::getTitle($item->articleid,0).'</a></li>';		
				}     
			if (!$single)
			{
				$function .= '</ul>';
				$function .= '</li>';
			}
			$function .= '</ul>';
		}
		else return;
	
		return $function;

	}
	
	function getTitle($id,$category)
	{
		$db = &JFactory::getDBO(  );
		$query = "SELECT title from #__";
		if($category)
			$query .= "categories ";
		else
			$query .= "content ";
		$query .= "WHERE id = ".$id." LIMIT 1";
		$db->setQuery($query);
		return $result = $db->loadResult();
	}
	
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
