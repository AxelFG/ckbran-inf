<?php
/**
* @version $Id: list.menuview.class.php 517 2011-06-12 12:47:20Z  $
* @author Daniel Ecer
* @package exmenu
* @copyright (C) 2005-2011 Daniel Ecer (de.siteof.de)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
if (!defined('EXTENDED_MENU_HOME')) {
	die('Restricted access');
}

/**
 * This Menu View is used for menu style "Flat List" and "Tree List"
 */
class ListExtendedMenuView extends AbstractExtendedMenuView {

	function renderAsString($menuNodeList, $level = 0) {
		return $this->_renderMenuNodeList($menuNodeList, $level, $this->menuHierarchy);
	}

	function _renderMenuNodeList($menuNodeList, $level = 0, $hierarchy = array()) {
		$result	= '';
		if (!$level)
			$result .= '<li ';
		else {
			$result .= '<ul ';
			if ($level==1)
				$result .= 'class="popup" ';
		}
		if ($this->hierarchyBasedIds) {
			$result	.= ' id="menulist_'.$this->getHierarchyString($hierarchy).$this->idSuffix.'"';
			$levelAttribute	= 'class';
		}
		$levelAttribute	= (($level == 0) && (!$this->hierarchyBasedIds) ? 'id' : 'class');	// for compatibility use id if possible
		$levelValue = '';
		if ($level == 0) {
			$levelValue	= 'mainlevel';
		} else {
			if ($this->sublevelClasses) {
				$levelValue	= 'sublevel';
			}
		}
		if ($levelValue != '') {
			$result	.= ' '.$levelAttribute.'="'.$levelValue.($levelAttribute == 'id' ? $this->idSuffix : $this->classSuffix).'"';
		}
		$result	.= '>';
		$index	= 0;
		foreach(array_keys($menuNodeList) as $id) {
			$menuNode = $menuNodeList[$id];
			$itemHierarchy = $hierarchy;
			$itemHierarchy[] = (1 + $index);
			if ($level) {
				$result	.= '<li';
				if ($this->activeMenuClassContainer) {
					$result	.= ' class="'.$this->getContainerMenuClassName($menuNode, $level).'"';
				}
				if ($this->hierarchyBasedIds) {
					$result	.= ' id="menuitem_'.$this->getHierarchyString($itemHierarchy).$this->idSuffix.'"';
				}
				$result	.= '>';
			}
			$linkOutput	= $this->mosGetMenuLink($menuNode, $level, $this->params, $itemHierarchy);
			$result	.= $linkOutput;
			if (($level < $this->maxDepth) && ($menuNode->isExpanded())) {
				$subMenuNodeList = $menuNode->getChildNodeList();
				if (count($subMenuNodeList) > 0) {
					$result	.= $this->_renderMenuNodeList($subMenuNodeList, $level+1, $itemHierarchy);
				}
			}
			if ($level) $result	.= '</li>';
			$index++;
		}
		if (!$level)
			$result .= '</li>';
		else
			$result	.= '</ul>';
		return $result;
	}
}
?>