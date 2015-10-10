<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
/**
 * Helpdesk Pro ticket from contact form
 */
class plgSystemTicketFromContactForm extends JPlugin
{     
	/**
	 * Contructor function
	 * @param string $subject
	 * @param array $config
	 */	
	function plgSystemTicketFromContactForm( &$subject, $config )
    {
		parent::__construct( $subject, $config );      
    }  
	
    function onAfterRoute() {
    	$mainframe = JFactory::getApplication() ;      	
      	if ($mainframe->isAdmin())
      		return ;
      	if (!file_exists(JPATH_ROOT.'/components/com_helpdeskpro/helper/helper.php')) {
      		return ;
      	}
      	$option = JRequest::getCmd('option', '');
      	$task  = JRequest::getCmd('task', '') ;
      	if ($option == 'com_contact' && $task == 'contact.submit') {
      		require_once JPATH_ROOT.'/components/com_helpdeskpro/helper/helper.php';
      		$config = HelpdeskProHelper::getConfig() ;
      		$contactData = JRequest::getVar('jform');
      		$data = array();
      		$data['name'] = $contactData['contact_name'];
      		$data['email'] = $contactData['contact_email'] ;
      		$data['subject'] = $contactData['contact_subject'];
      		$data['message'] = nl2br($contactData['contact_message']);
      		$data['category_id'] = $this->params->get('category_id') ;
      		$data['priority_id'] = $config->default_ticket_priority_id ;      		
      		HelpdeskProHelper::storeTicket($data) ;      		
      	}      	      	      	      	  
    }       	 	         
}