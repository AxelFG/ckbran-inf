<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
	
JToolBarHelper::title(   JText::_( 'Configuration' ), 'generic.png' );
JToolBarHelper::save('configuration.save');	
JToolBarHelper::cancel('configuration.cancel');	

$editor = & JFactory::getEditor() ;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('HDP_GENERAL');?></a></li>
			<li><a href="#message-page" data-toggle="tab"><?php echo JText::_('HDP_MESSAGES');?></a></li>
		</ul>
		<div class="tab-content">			
			<div class="tab-pane active" id="general-page">
				<table class="admintable adminform" style="width:100%;">			
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_ALLOW_PUBLIC_USER_SUBMIT_TICKETS'); ?>
						</td>
						<td>
							<?php echo $this->lists['allow_public_user_submit_ticket']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>			
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_DATE_FORMAT'); ?>
						</td>
						<td>
							<input type="text" name="date_format" value="<?php echo $this->config->date_format; ?>" />
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_DEFAULT_TICKET_PRIORITY'); ?>
						</td>
						<td>
							<?php echo $this->lists['default_ticket_priority_id']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_NEW_TICKET_STATUS'); ?>
						</td>
						<td>
							<?php echo $this->lists['new_ticket_status_id']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_TICKET_STATUS_WHEN_CUSTOMER_ADD_COMMENT'); ?>
						</td>
						<td>
							<?php echo $this->lists['ticket_status_when_customer_add_comment']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_TICKET_STATUS_WHEN_ADMIN_ADD_COMMENT'); ?>
						</td>
						<td>
							<?php echo $this->lists['ticket_status_when_admin_add_comment']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_CLOSED_TICKET_STATUS'); ?>
						</td>
						<td>
							<?php echo $this->lists['closed_ticket_status']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_ENABLE_ATTACHMENT'); ?>
						</td>
						<td>
							<?php echo $this->lists['enable_attachment'] ; ?>
						</td>
						<td>
							<?php echo JText::_('HDP_ENABLE_ATTACHMENT_EXPLAIN'); ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_ALLOWED_FILE_TYPES'); ?>
						</td>
						<td>
							<input type="text" class="inputbox" name="allowed_file_types" size="30"  value="<?php echo $this->config->allowed_file_types; ?>" />
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_MAX_NUMBER_ATTACHMENS_PER_MESSAGE'); ?>
						</td>
						<td>
							<input type="text" class="inputbox" name="max_number_attachments" size="30"  value="<?php echo $this->config->max_number_attachments ? $this->config->max_number_attachments : 3 ; ?>" />
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_SEND_ATTACHMENTS_TO_EMAIL'); ?>
						</td>
						<td>
							<?php echo $this->lists['send_ticket_attachments_to_email']; ?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="key">
							Home Page Text
						</td>
						<td>
							<?php echo $editor->display( 'home_page_text',  $this->config->home_page_text , '100%', '250', '75', '8' ) ;?>					
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>																																	
				</table>
			</div>
			<div class="tab-pane" id="message-page">
				<table class="admintable adminform" style="width:100%;">
					<tr>
						<td class="key" width="25%">
							<?php echo JText::_('HDP_FROM_NAME'); ?>					
						</td>
						<td>
							<input type="text" name="from_name" class="inputbox" value="<?php echo $this->config->from_name; ?>" size="50" />
						</td>
						<td>
							<strong><?php echo JText::_('HDP_FROM_NAME_EXPLAIN'); ?></strong>
						</td>
					</tr>			
					<tr>
						<td class="key" width="25%">
							<?php echo JText::_('HDP_FROM_EMAIL'); ?>					
						</td>
						<td>
							<input type="text" name="from_email" class="inputbox" value="<?php echo $this->config->from_email; ?>" size="50" />
						</td>
						<td>
							<strong><?php echo JText::_('HDP_FROM_EMAIL_EXPLAIN'); ?></strong>
						</td>
					</tr>				
					<tr>
						<td class="key" width="25%">
							<?php echo JText::_('HDP_NOTIFICATION_EMAILS'); ?>					
						</td>
						<td>
							<input type="text" name="notification_emails" class="inputbox" value="<?php echo $this->config->notification_emails; ?>" size="50" />
						</td>
						<td>
							<strong><?php echo JText::_('HDP_NOTIFICATION_EMAILS_EXPLAIN'); ?></strong>
						</td>
					</tr>																							
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_NEW_TICKET_ADMIN_EMAIL_SUBJECT'); ?>
						</td>
						<td>
							<input type="text" name="new_ticket_admin_email_subject" class="inputbox" value="<?php echo $this->config->new_ticket_admin_email_subject; ?>" size="50" />
						</td>
						<td>
											
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_NEW_TICKET_ADMIN_EMAIL_BODY'); ?>
						</td>
						<td>
							<?php echo $editor->display( 'new_ticket_admin_email_body',  $this->config->new_ticket_admin_email_body , '100%', '250', '75', '8' ) ;?>					
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_NEW_TICKET_USER_EMAIL_SUBJECT'); ?>
						</td>
						<td>					
							<input type="text" name="new_ticket_user_email_subject" class="inputbox" value="<?php echo $this->config->new_ticket_user_email_subject; ?>" size="50" />
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_NEW_TICKET_USER_EMAIL_BODY'); ?>
						</td>
						<td>
							<?php echo $editor->display( 'new_ticket_user_email_body',  $this->config->new_ticket_user_email_body , '100%', '250', '75', '8' ) ;?>					
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>			
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_TICKET_UPDATED_ADMIN_EMAIL_SUBJECT'); ?>
						</td>
						<td>
							<input type="text" name="ticket_updated_admin_email_subject" class="inputbox" value="<?php echo $this->config->ticket_updated_admin_email_subject; ?>" size="50" />
						</td>
						<td>
											
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_TICKET_UPDATED_ADMIN_EMAIL_BODY'); ?>
						</td>
						<td>
							<?php echo $editor->display( 'ticket_updated_admin_email_body',  $this->config->ticket_updated_admin_email_body , '100%', '250', '75', '8' ) ;?>					
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_TICKET_UPDATED_USER_EMAIL_SUBJECT'); ?>
						</td>
						<td>					
							<input type="text" name="ticket_updated_user_email_subject" class="inputbox" value="<?php echo $this->config->ticket_updated_user_email_subject; ?>" size="50" />
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo JText::_('HDP_TICKET_UPDATED_USER_EMAIL_BODY'); ?>
						</td>
						<td>
							<?php echo $editor->display( 'ticket_updated_user_email_body',  $this->config->ticket_updated_user_email_body , '100%', '250', '75', '8' ) ;?>					
						</td>
						<td>
							<strong><?php echo JText::_('HDP_AVAILABLE_TAGS'); ?></strong>
						</td>
					</tr>		
				</table>
			</div>
		</div>		
	</div>	
	<input type="hidden" name="option" value="com_helpdeskpro" />	
	<input type="hidden" name="task" value="" />	
</form>