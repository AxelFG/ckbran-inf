<?php
 
require_once ('/var/www/ckbran/data/www/ckbran.ru/includes/defines.php');
require_once ('/var/www/ckbran/data/www/ckbran.ru/includes/framework.php');

// Adapted for The Art of Web: www.the-art-of-web.com
// Please acknowledge use of this code by including this header.

// initialise image with dimensions of 120 x 30 pixels
$image = @imagecreatetruecolor(120, 30) or die("Cannot Initialize new GD image stream");

// set background to white and allocate drawing colours
$background = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
imagefill($image, 0, 0, $background);
$linecolor = imagecolorallocate($image, 0x33, 0x99, 0xCC);
$textcolor1 = imagecolorallocate($image, 0x00, 0x00, 0x00);
$textcolor2 = imagecolorallocate($image, 0x2F, 0x6D, 0xAA);

// draw random lines on canvas
 for($i=0; $i < 8; $i++) {
	 imagesetthickness($image, rand(1,2));
	 imageline($image, rand(0,120), 0, rand(0,120), 30, $linecolor);
}


// using a mixture of TTF fonts
$fonts = array();
$fonts[] = "/var/www/ckbran/data/www/ckbran.ru/libraries/fonts/DejaVuSerif-Bold.ttf";
$fonts[] = "/var/www/ckbran/data/www/ckbran.ru/libraries/fonts/DejaVuSans-Bold.ttf";
$fonts[] = "/var/www/ckbran/data/www/ckbran.ru/libraries/fonts/DejaVuSansMono-Bold.ttf"; 

// add random digits to canvas
$digit = '';
for($x = 10; $x <=70; $x += 30) {
	$textcolor = (rand() % 2) ? $textcolor1 : $textcolor2;
	$digit .= ($num = rand(0, 9));
	imagettftext($image, 14, rand(-30,30), $x, rand(16, 32), $textcolor, $fonts[array_rand($fonts)], $num);
}

// record digits in session variable (Joomla! customized)

$options['name'] = 'captcha';

$app =& JFactory::getApplication('site',$options);
$app->initialise();

$session =& JFactory::getSession();
$session->set('digit', $digit);

// display image and clean up

// echo $digit; - Testin'

imagepng($image, 'libraries/captcha.png');
imagedestroy($image);


?>