<?php
/**
 * @subpackage	Content.pdfembed
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
class plgContentPdf_embed extends JPlugin
{
	/** @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	 // Function for Joomla version 2.5.0 
	public function onContentPrepare($context, $row, $params, $page = 0)
	{
		$regex = "#{pdf[\=|\s]?(.+)}#s";
		$regex1 = '/{(pdf=)\s*(.*?)}/i';
		// find all instances of mambot and put in $matches
		preg_match_all( $regex1, $row->text, $matches );
		// Number of mambots
		$count = count( $matches[0] );
		for ($i=0; $i<$count; $i++) 
		{	
			$r	=	str_replace( '{pdf=', '', $matches[0][$i]);
			$r	=	str_replace( '}', '', $r); 
			$ex	=	explode('|',$r);
			$ploc	=	$ex[0];
			$w	=	$ex[1];
			$h	=	$ex[2];
			$replace = plg_pdfembed_replacer($ploc , $w, $h );
			$row->text = str_replace( '{pdf='.$ex[0].'|'.$ex[1].'|'.$ex[2].'}', $replace, $row->text);
		} 
		return true;
	}
}

function plg_pdfembed_replacer($ploc , $w, $h ) {
		return '<embed src="'.$ploc.'" width="'.$w.'" height="'.$h.'"/>';
	} 
?>
