<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OSFramework
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

require_once JPATH_ROOT.'/administrator/components/com_helpdeskpro/legacy/view.php';
class OSViewForm extends LegacyView {
	/**
	 * Language prefix
	 * @var string
	 */
	var $lang_prefix = OSF_LANG_PREFIX ;
	
	function display($tpl = null)
	{		
		$db = & JFactory::getDbo() ;
		$item = $this->get('Data');		
		$lists = array();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', ' class="inputbox" ', $item->published);
		
		if (isset($item->access)) {
			if (version_compare(JVERSION, '1.6.0', 'ge')) {
				$lists['access'] = JHtml::_('access.level', 'access', $item->access, 'class="inputbox"', false) ;
			} else {
				$sql = 'SELECT id AS value, name AS text'
				. ' FROM #__groups'
				. ' ORDER BY id'
				;
				$db->setQuery($sql) ;
				$groups = $db->loadObjectList();
				$lists['access'] = JHTML::_('select.genericlist',   $groups, 'access', 'class="inputbox" ', 'value', 'text', $item->access) ;
			}	
		}		
		
		$this->_buildListArray($lists, $item) ;
		$this->assignRef('item', $item);
		$this->assignRef('lists', $lists);
		
		$this->_buildToolbar() ;
				
		parent::display($tpl);
	}
	
	/**
	 * Build all the lists items used in the form and store it into the array
	 * @param  $lists
	 * @return boolean
	 */
	function _buildListArray(&$lists, $item) {
		
		return true ;
	}	
		
	/**
	 * Build the toolbar for view list
	 */
	function _buildToolbar() {
		$viewName = $this->getName() ;		
		if ($viewName == 'status')
			$controller = $viewName ;
		else 
			$controller = OSInflector::singularize($viewName) ;
		$edit = JRequest::getVar('edit') ;
		$text = $edit ? JText::_($this->lang_prefix.'_EDIT') : JText::_($this->lang_prefix.'_NEW');	
		JToolBarHelper::title(   JText::_( $this->lang_prefix.'_'.$viewName).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save($controller.'.save');	
		JToolBarHelper::apply($controller.'.apply');
		JToolBarHelper::cancel($controller.'.cancel');				
	}
}