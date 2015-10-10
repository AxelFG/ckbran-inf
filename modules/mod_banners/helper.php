<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_banners
 *
 * @package     Joomla.Site
 * @subpackage  mod_banners
 * @since       1.5
 */
class modBannersHelper
{
	public static function &getList(&$params)
	{
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_banners/models', 'BannersModel');
		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$keywords	= explode(',', $document->getMetaData('keywords'));

		$model = JModelLegacy::getInstance('Banners', 'BannersModel', array('ignore_request' => true));
		$model->setState('filter.client_id', (int) $params->get('cid'));
		$model->setState('filter.category_id', $params->get('catid', array()));
		$model->setState('list.limit', (int) $params->get('count', 1));
		$model->setState('list.start', 0);
		$model->setState('filter.ordering', $params->get('ordering'));
		$model->setState('filter.tag_search', $params->get('tag_search'));
		$model->setState('filter.keywords', $keywords);
		$model->setState('filter.language', $app->getLanguageFilter());

		$db		=& JFactory::getDBO();
		$type = JRequest::getVar('view');
		$menuid = JRequest::getVar('Itemid');		
		$currentid = JRequest::getVar('id');
		if (JRequest::getVar('option') != 'com_search') {		
			if ($type != 'article')		
				$query = 'SELECT  value  FROM #__fieldsattach_categories_values WHERE  fieldsid = 26 AND catid = '.$currentid;		
			else		
				$query = 'SELECT  value  FROM #__fieldsattach_values WHERE  fieldsid = 27 AND articleid = '.$currentid;		
			$db->setQuery( $query );		
			$ids = $db->loadResult();

			if (!$ids && $menuid != 101) $ids = 9999;
		}

		$model->setState('filter.id', $ids);

		$banners = $model->getItems();
		$model->impress();

		return $banners;
	}
}
