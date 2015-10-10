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
 * Helpdesk Pro Language controller
 *
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskProControllerLanguage extends LegacyController
{
	function save() {
		$model =  $this->getModel('language');
		$data = JRequest::get('post', JREQUEST_ALLOWHTML) ;
		$model->save($data);
		$lang = $data['lang'] ;
		$item = $data['item'];
		$url = JRoute::_('index.php?option=com_helpdeskpro&view=language&lang='.$lang.'&item='.$item, false);		
		$msg = JText::_('Traslation saved') ;
		$this->setRedirect($url, $msg);
	}
}