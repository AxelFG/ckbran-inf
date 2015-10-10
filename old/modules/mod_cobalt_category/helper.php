<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class modCobaltCategoriesHelper
{

	static public function getList($params, $cat_id = null)
	{
		$model = JModelLegacy::getInstance('Categories', 'cobaltModel');
		$parent_id = ($params->get('mode', 2) == 1 && $cat_id && JRequest::getWord('option') == 'com_cobalt') ? $cat_id : 1;
					$parent_id = 1;
		switch($params->get('cat_style', 'default'))
		{
			case 'moomenu':
				$level = '1000';
			break;
			case 'default':
				$level = 1;
				if($cat_id)
				{
					$model_cat = JModelLegacy::getInstance('Category', 'CobaltModel');
					$category = $model_cat->getItem($cat_id);
					$level = $category->level + 1;
				}
			break;
			default:
				$level = 100;
		}
		$model->section = $params->get('section_id');
		$model->parent_id = $parent_id;
		$model->order = $params->get('order') == 1 ? 'c.lft ASC' : 'c.title ASC';
		$model->levels = $level;
		$model->all = ($params->get('mode', 0) == 1 ? 0 : 1);
		$model->nums = $params->get('cat_nums', 'current');
		$list = $model->getItems();
		
		return $list;
	}

	static public function getSection($params)
	{
		return ItemsStore::getSection($params->get('section_id'));
	}

	static public function getParentsList($cat_id)
	{
		if(is_null($cat_id)) return array();
		$model = JModelLegacy::getInstance('Categories', 'CobaltModel');
		$parents = $model->getParentsByChild($cat_id);
		return $parents;
	}

	static public function getRecordsNum($section, $cat_id)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		
		$sql = $db->getQuery(true);
		$sql->select('count(*)');
		$sql->from('#__js_res_record');
		$sql->where('published = 1');
		$sql->where('hidden = 0');
		$sql->where('section_id = ' . $section->id);
		$sql->where("categories LIKE '%" . $cat_id . "%' ");
		
		if(! in_array($section->params->get('general.show_future_records'), $user->getAuthorisedViewLevels()))
		{
			$sql->where("ctime < " . $db->quote(JFactory::getDate()->toSql()));
		}
		
		if(! in_array($section->params->get('general.show_past_records'), $user->getAuthorisedViewLevels()))
		{
			$sql->where("(extime = '0000-00-00 00:00:00' OR extime > '" . JFactory::getDate()->toSql() . "')");
		}
		$db->setQuery($sql);
		return $db->loadResult();
	}

	static public function getCatRecords($cat_id, $params)
	{
		$db = JFactory::getDbo();
		
		$sql = $db->getQuery(true);
		
		$sql->select('*');
		$sql->from('#__js_res_record');
		$sql->where('published = 1');
		$sql->where('hidden = 0');
		$sql->where("ctime < " . $db->quote(JFactory::getDate()->toSql()));
		$sql->where("(extime = '0000-00-00 00:00:00' OR extime > '" . JFactory::getDate()->toSql() . "')");
		$sql->where("id IN (SELECT record_id FROM #__js_res_record_category WHERE catid = '{$cat_id}')");
		
		/*$sql->select('record_id');
			$sql->from('#__js_res_record_category');
			$sql->where('catid = '.$cat_id);*/
		$db->setQuery($sql, 0, ($params->get('records_limit') ? $params->get('records_limit') + 1 : 0));
		$items = array();
		if($recs = $db->loadColumn())
		{
			foreach($recs as $rid)
			{
				$record = ItemsStore::getRecord($rid);
				$record->url = Url::record($record);
				$items[] = $record;
			}			
		}
		
		return $items;
	}

	static public function getSectionRecords($params)
	{
		$db = JFactory::getDbo();
		$items = null;
		$sql = $db->getQuery(true);
		$sql->select('id');
		$sql->from('#__js_res_record');
		$sql->where('published = 1');
		$sql->where('hidden = 0');
		$sql->where('section_id = ' . $params->get('section_id'));
		$sql->where("ctime < " . $db->quote(JFactory::getDate()->toSql()));
		$sql->where("(extime = '0000-00-00 00:00:00' OR extime > '" . JFactory::getDate()->toSql() . "')");
		$sql->where("id NOT IN (SELECT record_id FROM #__js_res_record_category WHERE section_id = '" . $params->get('section_id') . "')");
		$db->setQuery($sql, 0, ($params->get('records_limit') ? $params->get('records_limit') + 1 : 0));
		if($recs = $db->loadColumn())
		{
			$items = array();
			foreach($recs as $rid)
			{
				$record = ItemsStore::getRecord($rid);
				$record->url = Url::record($record);
				$items[] = $record;
			}
		}
		return $items;
	}
}