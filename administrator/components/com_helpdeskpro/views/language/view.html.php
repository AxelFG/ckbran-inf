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
 * HTML View class for Helpdesk Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @since 1.5
 */
class HelpdeskproViewLanguage extends LegacyView
{
	function display($tpl = null)
	{	
		$mainframe = & JFactory::getApplication() ;
		$option    = 'com_helpdeskpro' ;			
		$lang 				= $mainframe->getUserStateFromRequest( $option.'language.lang',			'lang',			'en-GB',				'string' );
		if (!$lang)
			$lang = 'en-GB' ;		
		$search				= $mainframe->getUserStateFromRequest( $option.'language.search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );				
		$lists['search'] = $search;	
		$item = JRequest::getVar('item', '') ;
		if (!$item)
			$item = 'com_helpdeskpro' ;
		$model = & $this->getModel('language') ;	
		$trans = $model->getTrans($lang, $item);
		$languages = $model->getSiteLanguages();		
		$options = array() ;
		$options[] = JHTML::_('select.option', '', JText::_('Select Language'))	;
		foreach ($languages as $language) {
			$options[] = JHTML::_('select.option', $language, $language) ;		
		}
		$lists['lang'] = JHTML::_('select.genericlist', $options, 'lang', ' class="inputbox"  onchange="submit();" ', 'value', 'text', $lang) ;		
		$options = array() ;
		$options[] = JHTML::_('select.option', '', JText::_('--Select Item--')) ;						
		$options[] = JHTML::_('select.option', 'com_helpdeskpro', JText::_('Helpdesk Pro')) ;			
		$lists['item'] = JHTML::_('select.genericlist', $options, 'item', ' class="inputbox"  onchange="submit();" ', 'value', 'text', $item) ;
		$this->assignRef('trans', $trans) ;	
		$this->assignRef('lists', $lists) ;	
		$this->assignRef('lang', $lang) ;
		$this->assignRef('item', $item) ;				
		parent::display($tpl);				
	}
}