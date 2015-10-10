<?php

$type = JRequest::getVar('view');

if ($type == 'contact')

	include $sitepath.'components/com_content/helpers/route.php';

function getParent($id)
{
	$db = &JFactory::getDBO(  );
	$query = "SELECT parent_id from #__categories WHERE id = ".$id." LIMIT 1";
	$db->setQuery($query);
	$result = $db->loadResult();
	if ($result > 1) return getParent($result);
	else return $id;
}

function getCategory($id)
{
	$db = &JFactory::getDBO(  );
	$query = "SELECT catid from #__content WHERE id = ".$id." LIMIT 1";
	$db->setQuery($query);
	return $result = $db->loadResult();
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

function getBacklink($id, $type, $category = true)
{
	$db = &JFactory::getDBO(  );
		
	if(!$category)
		$query = "SELECT articleid from #__fieldsattach_values WHERE (value LIKE '%,".$id.",%' OR value LIKE '".$id.",%' OR value LIKE '%,".$id."' OR value LIKE '".$id."') AND fieldsid = 23";

	else
		$query = "SELECT catid from #__fieldsattach_categories_values WHERE (value LIKE '%,".$id.",%' OR value LIKE '".$id.",%' OR value LIKE '%,".$id."' OR value LIKE '".$id."') AND fieldsid = 22";
	           
	$db->setQuery($query);
	$result = $db->loadObjectList();
	

	$function = '';	
	
	foreach ($result as $item) {
	
		if(!$category)
			$parent = getParent(getCategory($item->articleid));
		else
			$parent = getParent($item->catid);
	
		if($parent == $type) 
			if(!$category)
				$function .= '<li><a href="'.ContentHelperRoute::getArticleRoute($item->articleid, $parent).'">'.getTitle($item->articleid,$category).'</a></li>';
			else
				$function .= '<li><a href="'.ContentHelperRoute::getCategoryRoute($item->catid, $parent).'">'.getTitle($item->catid,$category).'</a></li>';
			
	}	            

	return $function;

}
?>