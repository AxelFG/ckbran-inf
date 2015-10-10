<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die ;

require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/view.php';
/**
 * HTML View class for OS Membership component
 *
 * @static
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproViewConfiguration extends LegacyView
{
	function display($tpl = null)
	{		
		$config = $this->get('Data');			
		$db = JFactory::getDbo() ;
		$lists['allow_public_user_submit_ticket'] = JHTML::_('select.booleanlist', 'allow_public_user_submit_ticket', '', $config->allow_public_user_submit_ticket);
		$lists['enable_attachment'] = JHTML::_('select.booleanlist', 'enable_attachment', '', $config->enable_attachment);
		$lists['send_ticket_attachments_to_email'] = JHTML::_('select.booleanlist', 'send_ticket_attachments_to_email', '', $config->send_ticket_attachments_to_email);
		$sql = 'SELECT id, title FROM #__helpdeskpro_statuses WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$rowStatuses = $db->loadObjectList() ;
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('HDP_SELECT'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList()) ;
		$lists['new_ticket_status_id'] = JHTML::_('select.genericlist', $options, 'new_ticket_status_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'title' ,
				'option.key' => 'id',
				'list.attr' => 'class="inputbox" ',
				'list.select' => $config->new_ticket_status_id
		));			
		
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('HDP_DONOT_CHANGE'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList()) ;
		
		$lists['ticket_status_when_customer_add_comment'] = JHTML::_('select.genericlist', $options, 'ticket_status_when_customer_add_comment', array(
				'option.text.toHtml' => false ,
				'option.text' => 'title' ,
				'option.key' => 'id',
				'list.attr' => 'class="inputbox" ',
				'list.select' => $config->ticket_status_when_customer_add_comment
		));
		
		$lists['ticket_status_when_admin_add_comment'] = JHTML::_('select.genericlist', $options, 'ticket_status_when_admin_add_comment', array(
				'option.text.toHtml' => false ,
				'option.text' => 'title' ,
				'option.key' => 'id',
				'list.attr' => 'class="inputbox" ',
				'list.select' => $config->ticket_status_when_admin_add_comment
		));
		
		$lists['closed_ticket_status'] = JHTML::_('select.genericlist', $options, 'closed_ticket_status', array(
				'option.text.toHtml' => false ,
				'option.text' => 'title' ,
				'option.key' => 'id',
				'list.attr' => 'class="inputbox" ',
				'list.select' => $config->closed_ticket_status
		));
		
		
		$options 	= array();
		$options[]= JHtml::_('select.option', 0, JText::_('HDP_SELECT'), 'id', 'title');
		$sql = 'SELECT id, title FROM #__helpdeskpro_priorities WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$rowPriorities = $db->loadObjectList() ;
		$options = array_merge($options, $rowPriorities) ;
		$lists['default_ticket_priority_id'] = JHTML::_('select.genericlist', $options, 'default_ticket_priority_id', array(
				'option.text.toHtml' => false ,
				'option.text' => 'title' ,
				'option.key' => 'id',
				'list.attr' => 'class="inputbox"" ',
				'list.select' => $config->default_ticket_priority_id
		));
		
					
		$this->assignRef('lists',		$lists);
		$this->assignRef('config',		$config);		
					
		parent::display($tpl);			
	}
}