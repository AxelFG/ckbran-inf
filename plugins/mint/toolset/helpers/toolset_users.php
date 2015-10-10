<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class METoolSetUserHelper
{
	function execute($params)
	{
		global $mainframe;

		$db =& JFactory::getDBO();
		
		$from = (int)$params->get('user_from');
		
		if(!$from)
		{
			JError::raiseWarning(400, 'From what user ID? Please set.');
			return;
		}
		
		$action = $params->get('action');
		$to = (int)$params->get('user_to');
		
		if($action == 2 && !$to)
		{
			JError::raiseWarning(400, 'To what user ID? Please set.');
			return;
		}

		if($action == 1)
		{
			self::cleanFiles($from);
		}
		
		self::cleanTable($action, $from, $to, 'community_notifications');

		self::cleanTable($action, $from, $to, 'jcs_user_subscr');
		self::cleanTable($action, $from, $to, 'jcs_coupons_history');
		self::cleanTable($action, $from, $to, 'jcs_history');

		self::cleanTable($action, $from, $to, 'js_res_category_user');
		self::cleanTable($action, $from, $to, 'js_res_hits');
		self::cleanTable($action, $from, $to, 'js_res_favorite');
		self::cleanTable($action, $from, $to, 'js_res_comments');
		self::cleanTable($action, $from, $to, 'js_res_files');
		self::cleanTable($action, $from, $to, 'js_res_polls');
		self::cleanTable($action, $from, $to, 'js_res_record');
		self::cleanTable($action, $from, $to, 'js_res_tags_history');
		self::cleanTable($action, $from, $to, 'js_res_vote');
		self::cleanTable($action, $from, $to, 'js_res_moderators');
		self::cleanTable($action, $from, $to, 'js_res_subscription_params');
		self::cleanTable($action, $from, $to, 'js_res_subscriptions');
		
		if($params->get('user_delete'))
		{
			$sql = "DELETE FROM #__users WHERE id = {$from}";
			$db->setQuery($sql);
			$db->query();
		}
		JFactory::getApplication()->enqueueMessage(JText::_('Successfully'));
	}

	function cleanFiles($from)
	{
		$db =& JFactory::getDBO();
		
		$sql = "SELECT * FROM #__js_res_files WHERE user_id = ".$from;
		$db->setQuery($sql);
		$files = $db->loadObjectList();
		
		
		foreach ($files AS $file)
		{
			$part = explode("_", $file->filename);
			unlink(JPATH_ROOT. DIRECTORY_SEPARATOR .JComponentHelper::getParams('com_mightyresources')->get('general_upload'). DIRECTORY_SEPARATOR .$file->ext. DIRECTORY_SEPARATOR .date('Y-m', $part[0]). DIRECTORY_SEPARATOR .$file->filename);
		}
	}
	function cleanTable($action, $from, $to, $table, $tag = 'user_id')
	{

		if(!self::isTableExists($table)) return;
		if($action == 0) return;

		$db =& JFactory::getDBO();

		if($action == 1)
		{
			$sql = "DELETE FROM #__{$table} WHERE {$tag} = {$from}";
		}
		else
		{
			$sql = "UPDATE #__{$table} SET {$tag} = {$to} WHERE {$tag} = {$from}";
		}
		$db->setQuery($sql);
		$db->query();
	}

	function isTableExists($table_name)
	{
		$db	=& JFactory::getDBO();
		$config =& JFactory::getConfig();
		$pref = $config->getValue( 'config.dbprefix' );
		$sql = "SHOW TABLES LIKE '{$pref}{$table_name}'";
		$db->setQuery($sql);
		$table = $db->loadResult();
		return $table;
	}
}
?>