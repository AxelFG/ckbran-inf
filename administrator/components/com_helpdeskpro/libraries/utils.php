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
class OSUtils {
	/**
	 * Get version of GD library installed on this server
	 * @param string
	 * @return number|NULL
	 */
	public static function getGDVersion() {
		if (!extension_loaded('gd')) {
			return 0;
		}
		static $gd_ver	= 0;
		// use the gd_info() function if posible.
		if (function_exists('gd_info')) {
			$ver_info = gd_info();
			$match = null;
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];
	
			return $match[0];
		}
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, 'gd version');
		$match = null;
		preg_match('/\d/', $info, $match);
		$gd_ver = $match[0];
	
		return $match[0];
	}
	/**
	 *
	 * Resize image to a pre-defined size
	 * @param string $srcFile
	 * @param string $desFile
	 * @param int $thumbWidth
	 * @param int $thumbHeight
	 * @param int $quality
	 */
	public static function resizeImage($srcFile, $desFile, $thumbWidth, $thumbHeight, $quality) {
		$app = JFactory::getApplication();
		$imgTypes = array( 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP', 7 => 'TIFF', 8 => 'TIFF', 9 => 'JPC', 10 => 'JP2', 11 => 'JPX', 12 => 'JB2', 13 => 'SWC', 14 => 'IFF');
		$imgInfo = getimagesize($srcFile);
		if ($imgInfo == null) {
			$app->enqueueMessage(JText::_('QG_IMAGE_NOT_FOUND', 'error')) ;
			return false ;
		}
		$type = strtoupper($imgTypes[$imgInfo[2]]) ;
		$gdSupportedTypes = array('JPG', 'PNG', 'GIF');
		if (!in_array($type, $gdSupportedTypes)) {
			$app->enqueueMessage(JText::_('QG_ONLY_SUPPORT_TYPES'), 'error');
			return false;
		}
		$srcWidth = $imgInfo[0];
		$srcHeight = $imgInfo[1];
		//Should canculate the ration
		$ratio =  max($srcWidth/$thumbWidth, $srcHeight/$thumbHeight , 1.0);
		$desWidth = (int) $srcWidth / $ratio ;
		$desHeight = (int) $srcHeight / $ratio ;
		$gdVersion = QuickGalleryHelper::getGDVersion() ;
		if ($gdVersion <= 0) {
			//Simply copy the source to target folder
			jimport('joomla.filesystem.file') ;
			$app->enqueueMessage(JText::_('QG_NOT_RESIZE_COPY', 'notice')) ;
			return false ;
		} else {
			if ($gdVersion == 1) {
				$method = 'gd1' ;
			}  else {
				$method = 'gd2' ;
			}
		}
		switch ($method) {
			case 'gd1':
				if ($type == 'JPG')
					$srcImage =  imagecreatefromjpeg($srcFile);
				elseif ($type == 'PNG')
				$srcImage = imagecreatefrompng($srcFile);
				else
					$srcImage = imagecreatefromgif($srcFile);
				$desImage = imagecreate($desWidth, $desHeight);
				imagecopyresized($desImage, $srcImage, 0, 0, 0, 0, $desWidth, $desHeight, $srcWidth, $srcHeight);
				imagejpeg($desImage, $desFile, $quality);
				imagedestroy($srcImage);
				imagedestroy($desImage);
				break ;
			case 'gd2':
				if (!function_exists('imagecreatefromjpeg')) {
					echo JText::_('GD_LIB_NOT_INSTALLED');
					return false;
				}
				if (!function_exists('imagecreatetruecolor')) {
					echo JText::_('GD2_LIB_NOT_INSTALLED');
					return false ;
				}
				if ($type == 'JPG')
					$srcImage =  imagecreatefromjpeg($srcFile);
				elseif ($type == 'PNG')
				$srcImage = imagecreatefrompng($srcFile);
				else
					$srcImage = imagecreatefromgif($srcFile);
				if (!$srcImage) {
					echo JText::_('QG_INVALID_IMAGE');
					return false;
				}
				$desImage = imagecreatetruecolor($desWidth, $desHeight);
				imagecopyresampled($desImage, $srcImage, 0, 0, 0, 0, $desWidth, $desHeight, $srcWidth, $srcHeight);
				imagejpeg($desImage, $desFile, $quality);
				imagedestroy($srcImage);
				imagedestroy($desImage);
				break ;
		}
		return true ;
	}
}