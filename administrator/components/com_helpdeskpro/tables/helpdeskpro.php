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
/**
 * Category Table Class
 *
 */
class CategoryHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_categories', 'id', $db);
	}		
}
class TicketHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_tickets', 'id', $db);
	}
}
/**
 * Message table class
 *
 */
class MessageHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_messages', 'id', $db);
	}
}
/**
 * Custom Field Table Class
 *
 */
class FieldHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_fields', 'id', $db);
	}
}
/**
 * Custom Field Value Table Class
 *
 */
class FieldValueHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_field_value', 'id', $db);
	}
}
/**
 * Config Table Class
 *
 */
class ConfigHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_configs', 'id', $db);
	}
}
/**
 * Ticket Status table class
 *
 */
class StatusHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_statuses', 'id', $db);
	}
}
/**
 * Ticket Priorities table class
 *
 */
class PriorityHelpdeskpro extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.5
	 */
	function __construct(& $db) {
		parent::__construct('#__helpdeskpro_priorities', 'id', $db);
	}
}