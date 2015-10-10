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



OSFactory::loadLibrary('modellist');



class HelpdeskproModelTickets extends OSModelList {

	function __construct($config) {

		$config['title_field'] = array('a.name', 'a.email','c.username' ,'a.subject',  'a.message') ;

		$config['default_ordering'] = ' a.created_date' ;

		$config['state_vars'] = array('category_id' => array(0, 'int', true),

									  'manager_id' => array(0, 'int', true),

									  'status_id' => array(0, 'int', true),

									  'priority_id' => array(0, 'int', true),

									  'filter_order' => array('a.modified_date', 'string', true),

									  'filter_order_Dir' => array('DESC', 'cmd', true),									   

									  'published' => array(-1, 'int', true)										 														

		);

						

		parent::__construct($config) ;

	}	

	

	

	function _buildQuery() {

		$where		= $this->_buildContentWhere();		

		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT a.*, b.title AS category_title, c.username AS username FROM #__helpdeskpro_tickets  AS a '

		.' LEFT JOIN #__helpdeskpro_categories AS b '

		.' ON a.category_id= b.id '

		.' LEFT JOIN #__users AS c '

		.' ON a.user_id = c.id '		

		. $where

		. $orderby

		;

						

		return $query ;				

	}

	

	

	function _buildContentWhereArray() {		

		$app = JFactory::getApplication() ;

		$user = JFactory::getUser() ;

		$config = HelpdeskProHelper::getConfig();

		$managedCategoryIds = HelpdeskProHelper::getTicketCategoryIds($user->get('username'));

		$where = parent::_buildContentWhereArray() ;

		$state = $this->getState() ;

		if ($state->category_id) {

			$where[] = ' a.category_id = '.$state->category_id ;	

		}		

				

		if ($state->status_id) {

			if ($state->status_id == -1) {

				if (!$user->authorise('core.admin')) {

					if (count($managedCategoryIds)) {

						//Show open and pending tickets to managers by default

						$where[] = ' (a.status_id='.$config->new_ticket_status_id.' OR a.status_id='.$config->ticket_status_when_customer_add_comment.') ';

					} else {

						//Show open tickets and require feedback tickets to customers

						//$where[] = ' (a.status_id='.$config->new_ticket_status_id.' OR a.status_id='.$config->ticket_status_when_admin_add_comment.') ';

						$where[] = " a.status_id != $config->closed_ticket_status " ;

					}

				} else {

					//Show open and pending tickets to managers by default

					$where[] = ' (a.status_id='.$config->new_ticket_status_id.' OR a.status_id='.$config->ticket_status_when_customer_add_comment.') ';

				}

			} else {

				$where[] = ' a.status_id = '.$state->status_id ;

			}

									

		}

		

		if ($state->priority_id) {

			$where[] = ' a.priority_id = '.$state->priority_id ;

		}



		if (!$user->authorise('core.admin')) {//Super administrator can view all tickets			

			if ($managedCategoryIds) {//He is ticket managers, so all tickets belong to him

				$where[] = ' a.category_id IN ('.implode(',', $managedCategoryIds).')' ;

			} else {

				//Registered user, only show tickets submitted by himself

				$userId = $user->get('id');

				$email = $user->get('email');

//				$where[] = "(a.user_id=$userId OR a.email='$email') " ;
				$where[] = ' a.status_id = 4';

			}																

		}

				

		return $where ;

	}

	

}