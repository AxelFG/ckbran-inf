<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php 

if (JRequest::getVar('view') == 'test')
	JFactory::getDocument()->setTitle(JText::_('TESTIMONIALS'));

if (!$this->parser)
{
	$this->parser = new FSTParser();
	$this->parser->Load($this->template,$this->template_type);
}

$this->parser->Clear();

$modcolor = "";
if ($this->_permissions['mod_kb'])
{
	if ($this->comment['published'] == 0)
		$modcolor = "style='background-color: #eeeeff'";
	if ($this->comment['published'] == 2)
		$modcolor = "style='background-color: #ffeeee'";
}

$moderation = "";
if ($this->_permissions['mod_kb'] && array_key_exists('id',$this->comment)) {
	$moderation .= '<div class="fst_kb_mod_this">';
	$show_tick = ""; 
	$show_cross = ""; 
	$show_delete = "";
	$show_edit = "";
	if ($this->comment['published'] == 1) 
	{
		$show_tick = "style='display: none'";
		$show_delete = "style='display: none'";
	} else if ($this->comment['published'] == 2) 
	{
		$show_cross = "style='display: none'";
	} else if ($this->comment['published'] == 0) 
	{
		$show_delete = "style='display: none'";
	}	
	if (!$this->opt_no_edit)	
		$moderation .= "<img id='fst_comment_{$this->uid}_{$this->comment['id']}_edit' {$show_edit} src='". JURI::root( true )."/components/com_fst/assets/images/edit_16.png' width='16' height='16' onclick='fst_edit_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('EDIT_COMMENT')."'>";
	
	$moderation .= "<img id='fst_comment_{$this->uid}_{$this->comment['id']}_tick' {$show_tick} src='". JURI::root( true )."/components/com_fst/assets/images/save_16.png' width='16' height='16' onclick='fst_approve_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('APPROVE_COMMENT')."' >";
	$moderation .= "<img id='fst_comment_{$this->uid}_{$this->comment['id']}_cross' {$show_cross} src='". JURI::root( true )."/components/com_fst/assets/images/cancel_16.png' width='16' height='16' onclick='fst_remove_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('REMOVE_COMMENT')."'>";
	$moderation .= "<img id='fst_comment_{$this->uid}_{$this->comment['id']}_delete' {$show_delete} src='". JURI::root( true )."/components/com_fst/assets/images/delete_16.png' width='16' height='16' onclick='fst_delete_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('DELETE_COMMENT')."'>";
	$moderation .= "</div>";
}

if (!$this->use_website)
	$this->comment['website'] = "";

$custom = array();
if ($this->customfields)
{
	foreach($this->customfields as &$field)
	{
		if (array_key_exists('custom_' . $field['id'],$this->comment))
		{
			$val = $this->comment['custom_' . $field['id']];
			$this->parser->SetVar('custom_' . $field['id'], trim($val));
			if (strlen(trim($val)) > 0)
			{
				$custom[] =	$val;
			}
		}
	}
}

$this->parser->SetVar('divid', "fst_comment_{$this->uid}_{$this->comment['id']}");

if (count($custom) > 0)
{
	$this->parser->SetVar('custom', implode(", ", $custom));	
} else {
	$this->parser->SetVar('custom', "");	
}


$this->comment['body'] = str_replace("\n","<br />",$this->comment['body']);

$this->parser->SetVar('created_nice', FST_Helper::Date($this->comment['created'], FST_DATETIME_SHORT));
$this->parser->SetVar('date', FST_Helper::Date($this->comment['created'], FST_DATE_SHORT));
$this->parser->SetVar('modcolor',$modcolor);
$this->parser->SetVar('moderation',$moderation);
$this->parser->AddVars($this->comment);
if ($this->opt_max_length > 0)
	if (strlen($this->comment['body']) > $this->opt_max_length)
		$this->parser->SetVar('body', substr($this->comment['body'],0,$this->opt_max_length) . "...");
	
//print_p($this->parser);

echo $this->parser->Parse();
