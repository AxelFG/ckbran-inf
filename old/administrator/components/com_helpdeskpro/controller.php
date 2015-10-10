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

require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/controller.php';
/**
 * OS Membership controller
 *
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproController extends LegacyController
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
	/**
	 * Display information
	 *
	 */
	function display( )
	{				    	
		$task = $this->getTask();				
		$view = JRequest::getVar('view', '');
		if (!$view) {
			JRequest::setVar('view', 'tickets') ;	
		}						
		parent::display();			
		HelpdeskProHelper::addSubmenus(JRequest::getVar('view', 'plans')); 		
		HelpdeskProHelper::displayCopyRight();
	}	
		
	/**
	 * Upgrade database schema
	 */
	function upgrade() {
		require_once JPATH_COMPONENT.'/install.helpdeskpro.php' ;
		com_helpdeskproInstallerScript::updateDatabaseSchema();
	}
	/**
	 * Export support tickets into CSV format
	 */	
	function csv_export() {
		$db = JFactory::getDbo() ;
		$dateFormat = HelpdeskProHelper::getConfigValue('date_format') ;
		$categoryId = JRequest::getInt('category_id') ;
		$statusId = JRequest::getInt('status_id') ;
		$priorityId = JRequest::getInt('priority_id') ;
		//Get list statues and id
		$sql = 'SElECT id, title FROM #__helpdeskpro_statuses ';
		$db->setQuery($sql);
		$rowStatuses = $db->loadObjectList() ;
		$statusList = array();
		foreach ($rowStatuses as $status) {
			$statusList[$status->id] = $status->title ;
		}
				
		$sql = 'SElECT id, title FROM #__helpdeskpro_priorities ';
		$db->setQuery($sql);
		$rowPriorities = $db->loadObjectList() ;
		
		$priorityList = array();
		foreach ($rowPriorities as $priority) {
			$priorityList[$priority->id] = $priority->title ;
		}
		
		$where = array();
		if ($categoryId > 0) {
			$where[] = ' a.category_id = '.$categoryId ;
		}			
		if ($statusId > 0) {
			$where[] = ' a.status_id = '.$statusId ;
		}		
		if ($priorityId > 0) {
			$where[] = ' a.priority_id = '.$priorityId ;
		}
				
		$sql = 'SELECT a.*, b.title AS category_title, c.username AS username FROM #__helpdeskpro_tickets  AS a '
		.' LEFT JOIN #__helpdeskpro_categories AS b '
		.' ON a.category_id= b.id '
		.' LEFT JOIN #__users AS c '
		.' ON a.user_id = c.id '
		. (count($where) >0 ? ' WHERE '.implode(' AND ', $where) : '')		
		.' ORDER BY id ' 	
		;							
		$db->setQuery($sql) ;
		$rows = $db->loadObjectList() ;		
		if(count($rows)){
			$results_arr = array();
			$results_arr[] = JText::_('HDP_TITLE'); 
			$results_arr[] = JText::_('HDP_MESSAGE');
			$results_arr[] = JText::_('HDP_CATEGORY');
			$results_arr[] = JText::_('HDP_USER');
			$results_arr[] = JText::_('HDP_CREATED_DATE');
			$results_arr[] = JText::_('HDP_MODIFIED_DATE');
			$results_arr[] = JText::_('HDP_STATUS');
			$results_arr[] = JText::_('HDP_PRIORITY');
			$results_arr[] = JText::_('HDP_ID');
			$csv_output = implode (",", $results_arr) ;			
			foreach($rows as $row) {
				$results_arr=array();
				$results_arr[] = $row->subject ;				
				$results_arr[] = $row->message ;
				$results_arr[] = $row->subject ;
				$results_arr[] = $row->category_title ;
				if ($row->username) {
					$results_arr[] = $row->name.'('.$row->username.')' ;					
				} else {
					$results_arr[] = $row->name ;
				}
				$results_arr[] = $row->email ;
				$results_arr[] = JHtml::_('date', $row->created_date, $dateFormat);
				$results_arr[] = JHtml::_('date', $row->modified_date, $dateFormat);
				$results_arr[] = @$statusList[$row->status_id] ;
				$results_arr[] = @$priorityList[$row->priority_id] ;
				$results_arr[] = $row->id ;				
				$csv_output .= "\n\"".implode ("\",\"", $results_arr)."\"";
			}
			$csv_output .= "\n";
			if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
				$UserBrowser = "Opera";
			}
			elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
				$UserBrowser = "IE";
			} else {
				$UserBrowser = '';
			}
			$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
			$filename = "tickets";
			@ob_end_clean();
			ob_start();
			header('Content-Type: ' . $mime_type);
			header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			if ($UserBrowser == 'IE') {
				header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
			}
			else {
				header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
				header('Pragma: no-cache');
			}
			echo $csv_output;
			exit();
		}
	}
} 