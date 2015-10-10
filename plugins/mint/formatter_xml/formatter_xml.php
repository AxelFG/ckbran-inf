<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgMintFormatter_xml extends JPlugin
{
	private $tmpl_path;
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$this->tmpl_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR;
	}

	function onListFormat($view)
	{
		$this->sendHeader();
		require $this->tmpl_path.'list'.DIRECTORY_SEPARATOR.'xml.php';
	}
	
	function onRecordFormat($view)
	{
		$this->sendHeader();
		require $this->tmpl_path.'record'.DIRECTORY_SEPARATOR.'xml.php';
	}
	
	function sendHeader()
	{
		header('Content-Type: text/xml');
	}

}