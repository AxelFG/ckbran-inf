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

class OSFactory {
	public static $loadedLibs = null ;
	/**
	 * Load a library into registry
	 * @param string $name Name of the lib which we want to load
	 */
	public static function loadLibrary($name = 'controller') {
		if (!isset(self::$loadedLibs[$name])) {
			$option = JRequest::getCmd('option') ;
			$path = JPATH_ROOT.'/administrator/components/'.$option.'/libraries/'.$name.'.php' ;
			require_once $path;
			self::$loadedLibs[$name] = true ;						
		}
	}
}
