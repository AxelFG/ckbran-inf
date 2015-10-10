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
 * Helpdesk Pro controller
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
}