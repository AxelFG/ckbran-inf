<?php
/* -------------------------------------------
Component: plg_KAZAAM!
Author: Barnaby Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2012 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.plugin.plugin');
jimport( 'joomla.html.parameter' );

class plgSystemKazaam extends JPlugin {

    function __construct(&$subject, $params) {
        parent::__construct($subject, $params);
        $this->checkMenuExists();
    }
    
    function onAfterRoute() {
        if(isset($_POST['task'])) $this->checkPost();
    }
    
    function onAfterRender () {
        if(isset($_SESSION['kz'])) {
            $this->data = (object) $_SESSION['kz'];
            unset($_SESSION['kz']);
            //INCREASE AVAILABLE MEMORY JUST IN CASE
            $this->setMemory();
            //REGENERATE MENU IF REQUESTED
            if(isset($this->data->regen)) {
                if((int)$this->params->get('regenerate', 1) == 1) {
                    $this->doAllCats();
                    $this->doAllArts();
                }
            }
            //OR UPDATE MULTI-SELECTED CATS / ARTICLES
            else if(count($this->data->cid)>0) foreach($this->data->cid as $a) {
                $this->data->id = $a;
                $this->updateMenu();

            //OR UPDATE INDIVIDUAL ITEMS
            } else if(isset($this->data->id)) {
                $this->updateMenu();
            }
            //REBUILD THE MENU
            $row = JTable::getInstance('menu');
            $row->rebuild();
        }
		$db = JFactory::getDBO();
		$query = "UPDATE `#__menu` SET `published`= 1 WHERE `published` = '-2' AND `menutype` = 'kazaam'";
        $db->setQuery($query);
		$db->query();
    }
    
    private function doAllCats() {
        $db = JFactory::getDBO();
        
        //REMOVE ALL KAZAAM DATA
        $query = "DELETE FROM #__menu WHERE `menutype` = 'kazaam'";
        $db->setQuery($query);
        $db->query();
        $query = "DELETE FROM #__kazaam_menu";
        $db->setQuery($query);
        $db->query();
        
        //GET ALL THE CATEGORY DATA
        $query = "SELECT `id`,`parent_id`,`title`,`alias`,`published`,`access`,`language` FROM `#__categories` WHERE `extension` = 'com_content' ORDER BY `lft` ASC";
        $db->setQuery($query);
        $categories = $db->loadAssocList();

        foreach($categories as $n=>$c) {

            //SET THE MENU ID & SAVE IN ASSETS TABLE
            $menu_id = $this->getMenuID(0, $c['id']);
            if($menu_id == 0) $menu_id = $this->insertMenu(0, $c['id']);

            //GET THE PARENT CATEGORY DATA
            $cat = $this->getCatData($c['parent_id']);
            
            //SET THE CATEGORY PARAMETERS
            $c['level'] = $cat['level']+1;
            $c['link'] = 'index.php?option=com_content&view=category&id='.$c['id'];
            
            //GET THE PARENT CATEGORY MENU ID
            $c['parent'] = $this->getMenuID(0,$c['parent_id']);
            if($c['parent'] == '0') $c['parent'] = $this->getRoot();

            //FORCE A PUBLISH AND SET TO BASE ROOT IF THIS CATEGORY IS INCLUDED, BUT PARENT IS UNPUBLISHED(!)
            $cpub = (in_array($c['id'], $this->params->get('cats', array()))) ? '1' : '0';
            $c['published'] = ($cpub == '1' && $c['published'] == '1') ? '1' : '-2';
            if($cpub == '1' && $cat['published'] != '1') {
                $c['parent'] = $this->getRoot();
                $c['level'] = '1';
            }
            
            //CHECK ALIAS
            $c['alias'] = $this->checkAlias($c['alias'], $menu_id);
            
            //BUILD THE DATA ARRAY
            $array = array(
                'menutype' => 'kazaam',
                'title' => $c['title'],
                'alias' => $c['alias'],
                'link' => $c['link'],
                'type' => 'component',
                'published' => $c['published'],
                'parent_id' => $c['parent'],
                'level' => $c['level'],
                'component_id' => 22,
                'access' => $c['access'],
                'language' => $c['language']
            );
            
            //UPDATE THE MENU TABLE
            $query = "UPDATE `#__menu` SET ";
            foreach($array as $a=>$b) {
                $query .= $db->quoteName($a).' = '.$db->quote($b);
                if($a !== 'language') $query .= ',';
            } 
            $query .= ' WHERE '.$db->quoteName('id').' = '.$db->quote($menu_id).' LIMIT 1';
            $db->setQuery($query);
            $db->query();
        }
    }
    
    private function doAllArts() {
        
        //DON'T BOTHER RUNNING IF NO ARTICLES ARE TO BE INCLUDED
        if((int)$this->params->get('arts',0) == '0') return false;

        //GET ALL THE ARTICLES
        $db = JFactory::getDBO();
        $query = "SELECT `id`,`title`,`alias`, `access`, `state`, `catid`, `language` FROM `#__content`";
        $db->setQuery($query);
        $articles = $db->loadAssocList();
        
        foreach($articles as $n=>$a) {
            
            //SET THE MENU ID & SAVE IN ASSETS TABLE
            $menu_id = $this->getMenuID(1, $a['id']);
            if($menu_id == 0) $menu_id = $this->insertMenu(1, $a['id']);

            //GET THE PARENT CATEGORY DATA
            $cat = $this->getCatData($a['catid']);

            //GET THE PARENT CATEGORY MENU ID
            $a['parent'] = $this->getMenuID(0,$a['catid']);
            if($a['parent'] == '0') $a['parent'] = $this->getRoot();
    
            //SET THE ARTICLE PARAMETERS
            $a['published'] = ($cat['published'] == '1') ? $a['state'] : '-2';
            if((int)$this->params->get('arts',0) === 0) $a['published'] = '-2';
            $a['level'] = $cat['level']+1;
            $a['link'] = 'index.php?option=com_content&view=article&id='.$a['id'];
            
            //CHECK ALIAS
            $a['alias'] = $this->checkAlias($a['alias'], $menu_id);
            
            //BUILD THE DATA ARRAY
            $array = array(
                'menutype' => 'kazaam',
                'title' => $a['title'],
                'alias' => $a['alias'],
                'link' => $a['link'],
                'type' => 'component',
                'published' => $a['published'],
                'parent_id' => $a['parent'],
                'level' => $a['level'],
                'component_id' => 22,
                'access' => $a['access'],
                'language' => $a['language']
            );
            
            //UPDATE THE MENU TABLE
            $query = "UPDATE `#__menu` SET ";
            foreach($array as $x=>$y) {
                $query .= $db->quoteName($x).' = '.$db->quote($y);
                if($x !== 'language') $query .= ',';
            } 
            $query .= ' WHERE '.$db->quoteName('id').' = '.$db->quote($menu_id).' LIMIT 1';
            $db->setQuery($query);
            $db->query();
        }
    }

    private function checkMenuExists() {
        $db = JFactory::getDBO();
        $query = "SELECT `id` FROM `#__menu_types` WHERE `menutype` = 'kazaam' LIMIT 1";
        $db->setQuery($query);
        $res = $db->loadResult();
        if(!isset($res['id'])) {
            $query = "INSERT INTO ".$db->quoteName('#__menu_types')
                ." SET "
                .$db->quoteName('menutype')
                ." = ".$db->quote('kazaam')
                .",".$db->quoteName('title')
                ."=".$db->quote('KAZAAM!');
            $db->setQuery($query);
            $db->query();
            $_SESSION['kz']['regen'] = '1';
        }
        return true;
    }

    private function checkPost() {
        
        //CHECK IF KAZAAM PLUGIN WAS JUST SAVED
        if(strpos($_POST['task'], 'plugin.save') !== FALSE || strpos($_POST['task'], 'plugin.apply') !== FALSE) {
            if(isset($_POST['jform']['element']) && $_POST['jform']['element'] == 'kazaam' && isset($_POST['jform']['folder']) && $_POST['jform']['folder'] == 'system') {
                $_SESSION['kz']['regen'] = '1';
            }
            return;
        }

        //OTHERWISE CHECK THE TYPE OF POST
        $types = array('categories', 'category', 'articles', 'article');
        $actions = array('save', 'apply', 'publish', 'unpublish', 'archive', 'featured', 'trash', 'batch', 'rebuild');
        
        //CHECK THE ACTION
        foreach($types as $t) {
            foreach($actions as $a) {
                if(strpos($_POST['task'], $t.'.'.$a) !== FALSE) {
                    $data['type'] = (strpos($t, 'categor')!==FALSE) ? 0 : 1;
                    $data['id'] = (isset($_POST['jform']['id'])) ? (int) $_POST['jform']['id'] : 0;
                    $data['cid'] = (isset($_POST['cid'])) ? $_POST['cid'] : array();
                    $_SESSION['kz'] = $data;
                    break;
                }
            }
        }
    }
    
    private function getMenuID($type = 0, $id = 0) {
        $db = JFactory::getDBO();
        
        if((int)$id === 0) return 0;
        
        //GET THE MENU ID FOR THE UPDATED ITEM
        $query = "SELECT ".$db->quoteName('menu_id')." FROM ".$db->quoteName('#__kazaam_menu')." WHERE ";
        $query .= $db->quoteName('type')." = ".$db->quote($type);
        if($id > 0) $query .= " AND ".$db->quoteName('item_id')." = ".$db->quote($id);
        $query .= " LIMIT 1";
        $db->setQuery($query);
        $res = $db->loadAssoc();
        $menu_id = (int) $res['menu_id'];
        return $menu_id;
    }
    
    private function getRoot() {
        $db = JFactory::getDBO();
        //RETURN THE BASE ROOT MENU ITEM ID
        $query = "SELECT `id` FROM `#__menu` WHERE `alias` = 'root' ORDER BY `id` ASC LIMIT 1";
        $db->setQuery($query);
        $res = $db->loadAssoc();
        return $res['id'];
    }
    
    private function insertMenu($type=0, $id=0) {
        $id = (int)$id;
        $type = (int)$type;
        
        //CREATE TEMPORARY MENU ITEM PLACEHOLDER
        $data = array('id' => '', 'menutype' => 'kazaam');
        $row = JTable::getInstance('menu');
        $row->save($data);
        $tempid = (int) $row->id;
        
        //SAVE TO THE KAZAAM MENU REL TABLE
        $db = JFactory::getDBO();
        $query = "INSERT INTO ".$db->quoteName('#__kazaam_menu')." SET ".$db->quoteName('menu_id')." = ".$db->quote($tempid).", ".$db->quoteName('item_id')." = ".$db->quote($id).", ".$db->quoteName('type')." = ".$db->quote($type);
        $db->setquery($query);
        $db->query();
        
        //RETURN THE NEW ID 
        return $tempid;
    }
    
    private function updateMenu() {
        
        $db = JFactory::getDBO();
                
        //CHECK THE MENU ID FOR UPDATED ITEM - ADD NEW MENU ITEM IF REQUIRED
        $menu_id = $this->getMenuID($this->data->type, $this->data->id);
        if($menu_id == 0) $menu_id = $this->insertMenu($this->data->type, $this->data->id);
        
        //GET RELEVANT CONTENT
        if($this->data->type === 0) $data = $this->getCategory($this->data->id);
        else $data = $this->getArticle($this->data->id);
        
        //CHECK ALIAS
        $data['alias'] = $this->checkAlias($data['alias'], $menu_id);
        
        //SAVE THE DATA
        $array = array(
            'id' => $menu_id,
            'menutype' => 'kazaam',
            'title' => $data['title'],
            'alias' => $data['alias'],
            'link' => $data['link'],
            'type' => 'component',
            'published' => $data['published'],
            'parent_id' => $data['parent'],
            'level' => $data['level'],
            'component_id' => 22,
            'access' => $data['access'],
            'language' => $data['language']
        );
        
        //UPDATE THE MENU TABLE
        $query = "UPDATE `#__menu` SET ";
        foreach($array as $x=>$y) {
            $query .= $db->quoteName($x).' = '.$db->quote($y);
            if($x !== 'language') $query .= ',';
        } 
        $query .= ' WHERE '.$db->quoteName('id').' = '.$db->quote($menu_id).' LIMIT 1';
        $db->setQuery($query);
        $db->query();
    }
    
    private function checkAlias($alias, $id) {

        $db = JFactory::getDBO();
        
        //QUICK LOOP TO CHECK IF ALIAS EXISTS - MAYBE REPLACE WITH DO...WHILE
        $origalias = $alias;
        for($i=1; $i<=9999; $i++) {
            $query = "SELECT ".$db->quoteName('id')." FROM #__menu WHERE ".$db->quoteName('alias')." = ".$db->quote($alias)." AND ".$db->quoteName('id')." != ".$db->quote($id)." LIMIT 1";
            $db->setQuery($query);
            $row = $db->loadColumn();
            if (!isset($row[0])) return $alias;
            else $alias = $origalias.'-'.$i;
        }
    }
    
    private function getArticle() {
        
        //GET THE ARTICLE DATA
        $db = JFactory::getDBO();
        $query = "SELECT `id`,`title`,`alias`, `access`, `state`, `catid`, `language` FROM `#__content`";
        if($this->data->id != '0') $query .= " WHERE `id` = '{$this->data->id}'";
        else $query .= " ORDER BY `id` DESC ";
        $query .= " LIMIT 1";
        $db->setQuery($query);
        $article = $db->loadAssoc();

        //GET THE PARENT CATEGORY DATA
        $cat = $this->getCatData($article['catid']);
        
        //GET THE PARENT CATEGORY MENU ID
        $article['parent'] = $this->getMenuID(0,$article['catid']);
        if($article['parent'] == '0') $article['parent'] = $this->getRoot();

        //SET THE ARTICLE PARAMETERS
        $article['published'] = ($cat['published'] == '1') ? $article['state'] : '-2';
        if((int)$this->params->get('arts',0) === 0) $article['published'] = '-2';
        $article['level'] = $cat['level']+1;
        $article['link'] = 'index.php?option=com_content&view=article&id='.$article['id'];
        return $article;
    }
    
    private function getCategory($id) {
        
        //GET THE CATEGORY DATA
        $db = JFactory::getDBO();

        $query = "SELECT `id`,`parent_id`,`title`,`alias`,`published`,`access`,`language` FROM `#__categories`";
        if($this->data->id != '0') $query .= " WHERE `id` = '{$this->data->id}'";
        else $query .= " ORDER BY `id` DESC ";
        $query .= " LIMIT 1";
        $db->setQuery($query);
        $category = $db->loadAssoc();
		
		if ($category['parent_id'] == 12) {
	       	$query = "INSERT IGNORE INTO `#__helpdeskpro_categories` (`id`,`title`,`description`,`access`,`published`) SELECT `id`,`title`,`description`,`access`,`published` FROM `#__categories` WHERE `parent_id` = 12 ORDER BY `title` ASC";	
	        $db->setQuery($query);
			$result = $db->execute();
		}

        //GET THE PARENT CATEGORY DATA
        $cat = $this->getCatData($category['parent_id']);
        
        //GET THE PARENT CATEGORY MENU ID
        $category['parent'] = $this->getMenuID(0,$category['parent_id']);
        if($category['parent'] == '0') $category['parent'] = $this->getRoot();

        //SET THE ARTICLE PARAMETERS
        $category['published'] = ($cat['published'] == '1') ? $category['published'] : 0;
        $category['published'] = (in_array($this->data->id, $this->params->get('cats', array()))) ? $category['published'] : 0;
        $category['level'] = $cat['level']+1;
        $category['link'] = 'index.php?option=com_content&view=category&id='.$category['id'];
        
        //FORCE A PUBLISH AND SET TO BASE ROOT IF THIS CATEGORY IS INCLUDED, BUT PARENT IS UNPUBLISHED(!)
        $cpub = (in_array($category['id'], $this->params->get('cats', array()))) ? '1' : '0';
        $category['published'] = ($cpub == '1' && $category['published'] == '1') ? '1' : '-2';
        if($cpub == '1' && $cat['published'] != '1') {
            $category['parent'] = $this->getRoot();
            $category['level'] = '1';
        }
        return $category;
    }

    private function getCatData($id) {
        $id = (int)$id;
        $data = array();
        $db = JFactory::getDBO();
        
        //GET ROOT MENU STATE
        if($id === 1) {
            $data['published'] = '1';
            $data['level'] = '0';
        } else {
            //GET CATEGORY STATE
            $query = "SELECT m.published, m.level FROM  `#__menu` AS m LEFT JOIN #__kazaam_menu AS k ON ( k.menu_id = m.id ) WHERE k.`type` =  '0' AND k.item_id =  '{$id}' LIMIT 1";
            $db->setQuery($query);
            $data = $db->loadAssoc();  
        }
        return $data;
    }

    private function setMemory() {
        if(!isset($this->mem)) {
            //WE'RE GOING TO RUN - SET A HIGHER TIME LIMIT & MEMORY LIMIT JUST IN CASE
            if(!ini_get('safe_mode')){ 
                set_time_limit(60*30); //30 MINUTES
                ini_set('memory_limit', '128M');
            }
            $this->mem = 1;
        }
    }
}