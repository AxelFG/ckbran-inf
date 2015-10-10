<?php

defined('_JEXEC') or die('Restricted access');

$cobalt = JPATH_ROOT. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_cobalt'. DIRECTORY_SEPARATOR .'cobalt.php';
if(!file_exists($cobalt)) return;

// Include the syndicate functions only once
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helper.php');
$Itemid = JRequest::getInt('Itemid');

include_once JPATH_ROOT. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_cobalt'. DIRECTORY_SEPARATOR .'library'. DIRECTORY_SEPARATOR .'php'. DIRECTORY_SEPARATOR .'html'. DIRECTORY_SEPARATOR .'tags.php';

$lang = JFactory::getLanguage();
$tag = $lang->getTag();
$res = $lang->load('com_cobalt', JPATH_ROOT. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_cobalt');

		
$section->id = $params->get('depends_on_cat', 0) ? JRequest::getInt('section_id') : $params->get('section_id');
$category->id = JRequest::getInt('cat_id');
if ($params->get('show_section_name'))
{
	JModelLegacy::addIncludePath(JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_cobalt' . DIRECTORY_SEPARATOR . 'models');
	JTable::addIncludePath(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_cobalt' . DIRECTORY_SEPARATOR . 'tables');
	$section = modCobaltTagcloudHelper::getSection($section->id);
	$category = modCobaltTagcloudHelper::getCategory($category->id);
}

$list = modCobaltTagcloudHelper::getTags($section, $params, $category->id);

if (! $list)
	return FALSE;

$html = $params->get('html_tags', 'H1, H2, H3, H4, H5, H6, strong, b, em, big, small');


$path = dirname(JModuleHelper::getLayoutPath('mod_cobalt_tagcloud'));
$layout = $path . DIRECTORY_SEPARATOR . $params->get('tmpl', 'default.php');

require ($layout);

?>