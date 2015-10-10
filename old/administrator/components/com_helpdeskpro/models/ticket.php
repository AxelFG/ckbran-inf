<?php
/**
* @version		1.0.0
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

OSFactory::loadLibrary('model') ;
/**
 * Helpdesk Pro Component Ticket Model
 *
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproModelTicket extends OSModel
{	
	var $ticket_code = null ;
	function __construct($config) {
		parent::__construct($config) ;
		
		$id = JRequest::getInt('id');
		$ticketCode = JRequest::getVar('ticket_code') ;
		if ($id > 0) {
			$this->setId($id) ;
		}
		if ($ticketCode) {
			$this->setTicketCode($ticketCode);	
		}
	}
			
	function setTicketCode($ticketCode) {
		$this->ticket_code = $ticketCode ;	
	}
	
	function &getData()
	{
		if (empty($this->_data)) {
			if ($this->_id || $this->ticket_code)
				$this->_loadData();
			else
				$this->_initData();
		}
		return $this->_data;
	}
	
	function _loadData() {
		if ($this->_id) {
			$sql = 'SELECT a.*, b.username, c.title AS category_title FROM #__helpdeskpro_tickets AS a '
			.' LEFT JOIN #__users AS b'
			.' ON a.user_id=b.id '
			.' LEFT JOIN #__helpdeskpro_categories AS c '
			.' ON a.category_id=c.id '
			.' WHERE a.id='.$this->_id
					
			;
		} else {
			$sql = 'SELECT a.*, b.username, c.title AS category_title FROM #__helpdeskpro_tickets AS a '
			.' LEFT JOIN #__users AS b'
			.' ON a.user_id=b.id '
			.' LEFT JOIN #__helpdeskpro_categories AS c '
			.' ON a.category_id=c.id '
			.' WHERE a.ticket_code="'.$this->ticket_code.'"'				
			;
		}
					
		$this->_db->setQuery($sql);		
		$this->_data = $this->_db->loadObject();
		if ($this->ticket_code)
			$this->_data->is_ticket_code = 1 ;
		else
			$this->_data->is_ticket_code = 0 ;		
	}
		
	function store(&$data) {
		if (isset($data['id'])) {
			$row = JTable::getInstance('helpdeskpro', 'ticket');
			$row->load($data['id']);
			$row->name = $data['name'];
			$row->email = $data['email'];
			$row->subject = $data['subject'];
			$row->message = $data['message'];
			$row->store();
		
			return true ;
		}
		jimport('joomla.user.helper');
		$config = HelpdeskProHelper::getConfig();
		$allowedFileTypes = explode('|', $config->allowed_file_types);
		for ($i = 0 , $n = count($allowedFileTypes) ; $i < $n ; $i++) {
			$allowedFileTypes[$i] = trim(strtoupper($allowedFileTypes[$i]));
		}
		$user = JFactory::getUser() ;
		$row = JTable::getInstance('helpdeskpro', 'ticket');
		$row->bind($data);
		if ($user->get('id') && !isset($data['name'])) {
			$row->name = $user->get('name');
			$row->email = $user->get('email');
			$row->user_id = $user->get('id');
		} else {
			$sql = 'SELECT id FROM #__users WHERE email="'.$data['email'].'"';
			$this->_db->setQuery($sql);
			$row->user_id = $this->_db->loadResult();
		} 
		$uploadedFiles = $this->_storeAttachment($allowedFileTypes);
		if (count($uploadedFiles['names'])) {
			$row->attachments = implode('|', $uploadedFiles['names']);
			$row->original_filenames = implode('|', $uploadedFiles['original_names']);
		}
		$row->status_id = $config->new_ticket_status_id ;
		while(true) {
			$ticketCode = strtolower(JUserHelper::genRandomPassword(10)) ;
			$sql = 'SELECT COUNT(*) FROM #__helpdeskpro_tickets WHERE ticket_code="'.$ticketCode.'"';
			$this->_db->setQuery($sql) ;
			$total = $this->_db->loadResult();
			if (!$total)
				break ;
		}
		$row->ticket_code = $ticketCode ;
		$row->created_date = $row->modified_date = gmdate('Y-m-d H:i:s') ;		
		$row->store();				
		//Store custom fields information for this ticket
		JCFields::saveFieldValues($row);
		HelpdeskProHelper::sendNewTicketNotificationEmails($row, $config) ;
		
		if (!$user->id) {
			$data['ticket_code'] = $ticketCode ;
		}
		
		return true ;
	}
	
	/**
	 * Update ticket category
	 * @param array $data
	 * @return boolean
	 */
	function updateCategory($data) {
		$row = JTable::getInstance('helpdeskpro', 'ticket');
		$row->load($data['id']);
		$row->category_id = $data['new_value'] ;
		$row->store();
		
		return true ;
	}

	function updateTicket($data) {
		$row = JTable::getInstance('helpdeskpro', 'ticket');
		$row->load($data['id']);
		$row->name = $data['name'];
		$row->email = $data['email'];
		$row->subject = $data['subject'];
		$row->message = $data['message'];
		$row->store();
	
		return true ;
	}
	
	/**
	 * Update ticket Status
	 * @param array $data
	 * @return boolean
	 */
	function updateStatus($data) {
		$row = JTable::getInstance('helpdeskpro', 'ticket');
		$row->load($data['id']);
		$row->status_id = $data['new_value'] ;
		$row->store();
	
		return true ;
	}
	
	/**
	 * Update ticket Status
	 * @param array $data
	 * @return boolean
	 */
	function updatePriority($data) {
		$row = JTable::getInstance('helpdeskpro', 'ticket');
		$row->load($data['id']);
		$row->priority_id = $data['new_value'] ;
		$row->store();
	
		return true ;
	}
	
	function saveRating($data) {
		$closedStatusId = HelpdeskProHelper::getConfigValue('closed_ticket_status');
		$row = JTable::getInstance('helpdeskpro', 'ticket');
		$row->load($data['id']);
		$row->rating = $data['new_value'] ;
		$row->status_id = $closedStatusId ;				
		$row->store();
		
		return true ;
	}
	
	function addComment($data) {
		$user = JFactory::getUser() ;
		$config = HelpdeskProHelper::getConfig();
		if (isset($data['message_id'])) {
			$row = JTable::getInstance('helpdeskpro', 'message');
			if ($data['comment'.$data['message_id']] == '') {
				$row->delete($data['message_id']); return true;
			}
			$row->load($data['message_id']);
			$row->message = $data['comment'.$data['message_id']];
			$row->store();
		
			return true ;
			
		}
		$ticket = JTable::getInstance('helpdeskpro', 'ticket');
		$row = JTable::getInstance('helpdeskpro', 'message');
		
		$allowedFileTypes = explode('|', $config->allowed_file_types);
		for ($i = 0 , $n = count($allowedFileTypes) ; $i < $n ; $i++) {
			$allowedFileTypes[$i] = trim(strtoupper($allowedFileTypes[$i]));
		}		
		$row->bind($data);				
		$row->message = $data['comment'];
		$ticket->load($row->ticket_id);
		if($user->id) {
			$row->user_id = $user->get('id') ;
		} else {
			if (isset($data['ticket_code'])) {
				$row->user_id = $ticket->user_id ;
			} 
		}					
		$row->date_added = gmdate('Y-m-d H:i:s') ;				
		$uploadedFiles = $this->_storeAttachment($allowedFileTypes);		
		if (count($uploadedFiles['names'])) {
			$row->attachments = implode('|', $uploadedFiles['names']);
			$row->original_filenames = implode('|', $uploadedFiles['original_names']);
		}					
		$row->store();							
		if ($row->user_id == $ticket->user_id || isset($data['ticket_code']))
			$isCustomerAddComment = true ;
		else
			$isCustomerAddComment = false ;
		//Update ticket status		
		if ($isCustomerAddComment) {
			if ($config->ticket_status_when_customer_add_comment)
				$ticket->status_id = $config->ticket_status_when_customer_add_comment ;
		} else {
			if ($config->ticket_status_when_admin_add_comment)	
				$ticket->status_id = $config->ticket_status_when_admin_add_comment ;
		}		
		$ticket->modified_date = gmdate('Y-m-d H:i:s');
		$ticket->store();		
		//Need to send email to users
		if ($isCustomerAddComment)
			HelpdeskProHelper::sendTicketUpdatedEmailToManagers($row, $ticket, $config);
		else							
			HelpdeskProHelper::sendTicketUpdatedEmailToCustomer($row, $ticket, $config);
								
		return true ;
	}		
	/**
	 * Override delete function to delete necessary data
	 * @see OSModel::delete()
	 */
	function delete($cid = array())
	{
		if (count( $cid ))
		{
			jimport('joomla.filesystem.file');
			$attachmentPath = JPATH_ROOT.'/media/com_helpdeskpro/attachments/' ;
			$row = JTable::getInstance('helpdeskpro', 'ticket');
			foreach ($cid as $id) {
				$row->load($id);
				if ($row->attachments) {
					$files = explode('|', $row->attachments);
					if (count($files)) {
						foreach ($files as $file) {
							if ($file && JFile::exists($attachmentPath.$file)) {
								JFile::delete($file) ;
							}
						}
					}															
				}
				//Need to remove messages and associated attachments
				$sql = 'SELECT attachments FROM #__helpdeskpro_messages WHERE ticket_id='.$row->id ;
				$this->_db->setQuery($sql) ;
				$messages = $this->_db->loadObjectList();
				if (count($messages)) {
					foreach($messages as $message) {
						$files = explode('|', $message->attachments);
						if (count($files)) {
							foreach ($files as $file) {
								if ($file && JFile::exists($attachmentPath.$file)) {
									JFile::delete($file) ;
								}
							}
						}
					}										
				}
			}			
			//Delete related messages
			$sql = 'DELETE FROM #__helpdeskpro_messages WHERE ticket_id IN ('.implode(',', $cid).')';
			$this->_db->setQuery($sql);
			$this->_db->query();
			
			$sql = 'DELETE FROM #__helpdeskpro_tickets WHERE id IN ('.implode(',', $cid).')';
			$this->_db->setQuery($sql);
			$this->_db->query();			
		}
		
		
		return true;
	}
		
	function _storeAttachment($allowedFileTypes) {
		jimport ( 'joomla.filesystem.file' );
		$attachmentPath = JPATH_ROOT . '/media/com_helpdeskpro/attachments';
		$files = array(
				'names' => array() ,
				'original_names' => array()
		) ;
		$attachments = JRequest::getVar('attachment', null, 'files');		
		if (count ($attachments ['name'])) {
			$names = $attachments ['name'];
			$tmpNames = $attachments ['tmp_name'];
			$errors = $attachments ['error'];
			$sizes = $attachments['size'] ;
			for($i = 0, $n = count($attachments['name']); $i < $n; $i ++) {
				$name = $names [$i];
				$tmpName=$tmpNames [$i];
				$error = $errors [$i];
				if ($error == 0) {
					$fileExt = strtoupper(JFile::getExt($name));
					if (in_array($fileExt, $allowedFileTypes)) {
						if (JFile::exists($attachmentPath.'/'.$name)) {
							$fileName = JFile::stripExt ( $name ) . '_' . uniqid () . '.' . $fileExt;
						} else {
							$fileName = $name ;
						}
						JFile::upload ( $tmpName, $attachmentPath . '/' . $fileName );
						//Resize the image
						$files['names'][] = $fileName ;
						$files['original_names'][] = $name ;
					}
				}
			}
		}
		return $files ;
	}	
}