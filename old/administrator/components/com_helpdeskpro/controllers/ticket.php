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

OSFactory::loadLibrary('controller') ;
/**
 * OS Membership controller
 *
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproControllerTicket extends OSController
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{					
		parent::__construct($config);				
	}	

	function add() {
		JRequest::setVar('layout', 'form') ;
		parent::add() ;
	}	
	/**
	 * Save the category
	 *
	 */
	function save() {
		$app = JFactory::getApplication() ;
		jimport('joomla.filesystem.file') ;
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		unset($post['option']) ;
		unset($post['task']) ;
		$model =  $this->getModel('ticket') ;

		// check CAPTCHA
		$options['name'] = 'captcha';
		
		$app =& JFactory::getApplication('site',$options);
		$app->initialise();
		
		$session =& JFactory::getSession();

		if ($post['captcha'] == $session->get('digit'))
		{
			$ret =  $model->store($post);
	
			if ($ret) {
				$msg = JText::_('HDP_TICKET_SUBMITTED') ;
			} else {
				$msg = JText::_('HDP_ERROR_SAVING_TICKET');
			}
			if ($app->isAdmin())
				$this->setRedirect('index.php?option=com_helpdeskpro&view=tickets', $msg);
			else {
				$user = JFactory::getUser() ;
				if (!$user->id) {				
					$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$post['ticket_code'].'&Itemid='.JRequest::getInt('Itemid'), $msg);
				} else {
					$this->setRedirect('index.php?option=com_helpdeskpro&view=tickets&Itemid='.JRequest::getInt('Itemid'), $msg);
				}			
			}
		}	
		else {			
				$msg = JText::_('HDP_CAPTCHA_ERROR');
				$this->setRedirect('index.php?option=com_helpdeskpro&view=tickets', $msg);
		}
	}
	
	function add_comment() {	
		$app = JFactory::getApplication() ;	
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		$post['ticket_id'] = (int) $post['cid'][0] ;		
		$model =  $this->getModel('ticket') ;				
		$ret =  $model->addComment($post);
		if ($ret) {
			$msg = JText::_('HDP_COMMENT_ADDED') ;
		} else {
			$msg = JText::_('HDP_ERROR_ADDING_COMMENT');
		}				
		if (isset($post['ticket_code'])) {			
			$url = 'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$post['ticket_code'].'&Itemid='.JRequest::getInt('Itemid');
			$this->setRedirect($url, $msg);
		} else {
			if ($app->isAdmin()) {
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['ticket_id'], $msg);
			} else {
				$Itemid = JRequest::getInt('Itemid') ;
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['ticket_id'].'&Itemid='.$Itemid, $msg);
			}			
		}	
	}
		
	function update_category() {		
		$app = JFactory::getApplication() ;
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		$post['id'] = (int) $post['cid'][0] ;
		$model =  $this->getModel('ticket') ;
		$ret =  $model->updateCategory($post);
		if ($ret) {
			$msg = JText::_('HDP_TICKET_CATEGORY_UPDATED') ;
		} else {
			$msg = JText::_('HDP_ERROR_UPDATE_TICKET_CATEGORY');
		}
		if (isset($post['ticket_code'])) {			
			$url = 'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$post['ticket_code'].'&Itemid='.JRequest::getInt('Itemid');
			$this->setRedirect($url);
		} else {
			if ($app->isAdmin())
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'], $msg);
			else 
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'].'&Itemid='.JRequest::getInt('Itemid'), $msg);
		}		
	}
	
	function update_status() {		
		$app = JFactory::getApplication() ;
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		$post['id'] = (int) $post['cid'][0] ;
		$model =  $this->getModel('ticket') ;
		$ret =  $model->updateStatus($post);
		if ($ret) {
			$msg = JText::_('HDP_TICKET_STATUS_UPDATED') ;
		} else {
			$msg = JText::_('HDP_ERROR_UPDATING_TICKET_STATUS');
		}
		if (isset($post['ticket_code'])) {			
			$url = 'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$post['ticket_code'].'&Itemid='.JRequest::getInt('Itemid');
			$this->setRedirect($url, $msg);
		} else {
			if ($app->isAdmin())
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'], $msg);
			else
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'].'&Itemid='.JRequest::getInt('Itemid'), $msg);
		}	
	}
		
	function update_priority() {		
		$app = JFactory::getApplication() ;
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		$post['id'] = (int) $post['cid'][0] ;
		$model =  $this->getModel('ticket') ;
		$ret =  $model->updatePriority($post);
		if ($ret) {
			$msg = JText::_('HDP_TICKET_PRIORITY_UPDATED') ;
		} else {
			$msg = JText::_('HDP_ERROR_UPDATING_PRIORITY');
		}
		if (isset($post['ticket_code'])) {			
			$url = 'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$post['ticket_code'].'&Itemid='.JRequest::getInt('Itemid');
			$this->setRedirect($url);
		} else {
			if ($app->isAdmin()) {
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'], $msg);
			} else {
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'].'&Itemid='.JRequest::getInt('Itemid'), $msg);
			}			
		}			
	}	
	/**
	 * Save rating and close the ticket
	 */
	function save_rating() {
		$app = JFactory::getApplication() ;
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		$post['id'] = (int) $post['cid'][0] ;
		$model =  $this->getModel('ticket') ;
		$ret =  $model->saveRating($post);
		if ($ret) {
			$msg = JText::_('HDP_RATING_SAVED') ;
		} else {
			$msg = JText::_('HDP_ERROR_SAVING_RATING');
		}
		if (isset($post['ticket_code'])) {			
			$url = 'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$post['ticket_code'].'&Itemid='.JRequest::getInt('Itemid');
			$this->setRedirect($url);
		} else {
			if ($app->isAdmin()) {
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'], $msg);
			} else {
				$this->setRedirect('index.php?option=com_helpdeskpro&view=ticket&id='.$post['id'].'&Itemid='.JRequest::getInt('Itemid'), $msg);
			}			
		}	
	}
	
	function download_attachment() {
		$fileName = JRequest::getVar('filename', '') ;
		$originalFilename = JRequest::getVar('original_filename', '') ;		
		if (file_exists(JPATH_ROOT.'/media/com_helpdeskpro/attachments/'.$fileName)) {
			while (@ob_end_clean());
			HelpdeskProHelper::processDownload(JPATH_ROOT.'/media/com_helpdeskpro/attachments/'.$fileName, $originalFilename);
			exit() ;
		} else {
			$mainframe = & JFactory::getApplication() ;			
			$mainframe->redirect('index.php', JText::_('HDP_FILE_NOT_EXIST'));
		}
	}
}