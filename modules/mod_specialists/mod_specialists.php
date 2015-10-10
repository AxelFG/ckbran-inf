<?php

/**

* Contact Information Module 3.2
* Joomla Module

* Author: Edward Cupler

* Website: www.digitalgreys.com

* Contact: ecupler@digitalgreys.com

* @copyright Copyright (C) 2013 Digital Greys. All rights reserved.

* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php

* Contact Information Module is free software. This version may have been modified pursuant

* to the GNU General Public License, and as distributed it includes or

* is derivative of works licensed under the GNU General Public License or

* other free or open source software licenses.

*/



// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

if(!function_exists('contactImage')) {
function contactImage($imageInfo, $widthheight=35) {
	$database = &JFactory::getDBO();
	if ($imageInfo->image == '') {
		$imageInfo->image = '/modules/mod_specialists/default.jpg';
	}
	$timthumbPath=JURI::root() . $imageInfo->image;
	if (is_file( JPATH_BASE . '/'. $imageInfo->image )) {
		$iconimage = '<img src="'. $timthumbPath .'" title="' . $imageInfo->name . '" alt="' . $imageInfo->name . '" class="contactIcon"  />';
	} else {
		$iconimage = 'No image found';
	}
	return $iconimage ;
}
}

$db		=& JFactory::getDBO();


	global $mainframe, $database, $my, $Itemid;

/*************************************************/
	
// Random picking

	shuffle($contact_array);
	
	$contact_array = array_slice($contact_array, 0,4);
	
	$contact_id = implode(",",$contact_array);
	
// Random end

/*************************************************/

	$query = "SELECT * FROM #__contact_details WHERE featured = 1";

	$db->setQuery( $query );

	$contacts = $db->loadObjectList();
	
	shuffle($contacts);
	
	$contacts = array_slice($contacts, 0,4);



	if ($params->get( 'layout_style', '' )=="SeperateLines") {

		$linebreak="<br />";

		$newspace="";

	} else {

		$linebreak="";

		$newspace=" ";

	}



	if ($params->get( 'separate_code', '' )=="div") {

		$separate_code="<div class=\"contact_sep\"></div>";

	} else if ($params->get( 'separate_code', '' )=="br") {

		$separate_code="<br />";

	} else if ($params->get( 'separate_code', '' )=="hr") {

		$separate_code="<hr class=\"contact_sep\" />";

	} else {

		$separate_code="";

	}


include $sitepath.'components/com_contact/helpers/route.php';

	echo '<div class="custom module doctors">';

	echo '<h3 class="page-header"><a href="/specialist"><span>Наши специалисты</span></a></h3>';
	
	echo '<ul>';

	$separate_num=sizeof($contacts);

	for ( $i=0; $i<sizeof($contacts); $i++ ) {

	echo '<li>';
	
	$slug = $contacts[$i]->id.":".$contacts[$i]->alias;

	$url = ContactHelperRoute::getContactRoute($slug,$contacts[$i]->catid);
	
		$telephone_array=explode(",",$contacts[$i]->telephone);

		if ($params->get( 'show_image', '' ) == 1 && $contacts[$i]->name != '') {

			if ($params->get( 'link_image', '' ) == 1) {

				echo "<a href=\"". $url . "\" class=\"thumb\">" . contactImage( $contacts[$i], $params->get( 'thumb_size', 100 ) ) . "</a>$newspace\n";

			} else {

				echo "<span class=\"info_image\">" . contactImage( $contacts[$i], $params->get( 'thumb_size', 100 ) ) . "</span>$newspace\n";

			}

		}

		list($lastname,$firstname,$middlename) = split(' ',$contacts[$i]->name);

		if ($params->get( 'show_name', '' ) == 1 && $contacts[$i]->name != '') {

			if ($params->get( 'link_to', '' ) == 1) {

				echo "<h4><a href=\"". $url . "\"><strong>" . $lastname . "</strong><br/>".$firstname." ".$middlename."</a></h4>$newspace\n";

			} else {

				echo "<h4><strong>" . $lastname . "</strong><br/>".$firstname." ".$middlename."</h4>$newspace\n";

			}

		}

		if ($params->get( 'show_alias', '' ) == 1 && $contacts[$i]->alias != '') {

			if ($params->get( 'link_to', '' ) == 1 && $params->get( 'show_name', '' ) != 1) {

				echo "<span class=\"info_name\"><a href=\"index.php?option=com_contact&task=view&id=" . $contacts[$i]->id . "\">" . $contacts[$i]->alias . "</a></span>$linebreak$newspace\n";

			} else {

				echo "<span class=\"info_alias\">" . $contacts[$i]->alias . "</span>$linebreak$newspace\n";

			}

		}

		if ($params->get( 'con_position', '' ) == 1 && $contacts[$i]->con_position != '') {

			echo "<em>".$contacts[$i]->con_position . "</em>$linebreak$newspace\n";

		}

		if ($params->get( 'show_address', '' ) == 1 && $contacts[$i]->address != '') {

			echo "<span class=\"info_address\">".$contacts[$i]->address . "</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_suburb', '' ) == 1 && $contacts[$i]->suburb != '') {

			echo "<span class=\"info_suburb\">".$contacts[$i]->suburb . "</span>, \n";

		}

		if ($params->get( 'show_state', '' ) == 1 && $contacts[$i]->state != '') {

			echo "<span class=\"info_state\">".$contacts[$i]->state . "</span> \n";

		}

		if ($params->get( 'show_postcode', '' ) == 1 && $contacts[$i]->postcode != '') {

			echo "<span class=\"info_postcode\">".$contacts[$i]->postcode . "</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_country', '' ) == 1 && $contacts[$i]->country != '') {

			echo "<span class=\"info_country\">".$contacts[$i]->country . "</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_telephone', '' ) == 1 && $contacts[$i]->telephone != "") {

			if (sizeof($telephone_array) > 1) {

				$telNum=1;

				foreach ($telephone_array AS $telephone) {

					echo "<span class=\"info_telephone info_telephone".($telNum++)."\">".$telephone . "</span>$linebreak$newspace\n";

				}

			} else {

				echo "<span class=\"info_telephone\">".$telephone_array[0]. "</span>$linebreak$newspace\n";

			}

		}

		if ($params->get( 'show_mobile', '' ) == 1 && $contacts[$i]->mobile != "") {

			echo "<span class=\"info_mobile\">Mobile: " . $contacts[$i]->mobile ."</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_fax', '' ) == 1 && $contacts[$i]->fax != "") {

			echo "<span class=\"info_fax\">Fax: " . $contacts[$i]->fax ."</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_email_to', '' ) == 1 && $contacts[$i]->email_to != "") {

			if ($params->get( 'email_text' ) != '') {

				$displayed_email=JHTML::_('email.cloak', $contacts[$i]->email_to, true, $params->get( 'email_text'), false);// 

				//$displayed_email=emailcloaking( $contacts[$i]->email_to, true, $params->get( 'email_text' ), false );

			} else {

				$displayed_email=JHTML::_('email.cloak', $contacts[$i]->email_to, true );

				//$displayed_email=emailcloaking( $contacts[$i]->email_to, true );

			}

			echo "<span class=\"info_email\">" . $displayed_email . "</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_webpage', '' ) == 1 && $contacts[$i]->webpage != "") {

			$displayAddress=str_replace("http://", "", $contacts[$i]->webpage);

			$displayAddress=str_replace("https://", "", $displayAddress);

			if ($params->get( 'link_website', '' ) == 1) {

				if ($params->get( 'website_target', '' ) == 1) {

					$target=' target="new"';

				}


				if ( preg_match("/http:\/\//", $contacts[$i]->webpage ) || preg_match("/https:\/\//", $contacts[$i]->webpage ) ) {

					echo "<span class=\"info_webpage\"><a href=\"".$contacts[$i]->webpage ."\"". $target .">".$displayAddress ."</a></span>$linebreak$newspace\n";

				} else {

					echo "<span class=\"info_webpage\"><a href=\"http://".$contacts[$i]->webpage ."\"". $target .">".$displayAddress ."</a></span>$linebreak$newspace\n";

				}

			} else {

				echo "<span class=\"info_webpage\">".$contacts[$i]->webpage ."</span>$linebreak$newspace\n";

			}

		}

		if ($params->get( 'show_misc', 0 ) == 1 && $contacts[$i]->misc != "") {

			echo "<span class=\"info_misc\">".$contacts[$i]->misc ."</span>$linebreak$newspace\n";

		}

		if ($params->get( 'show_vcard', 0 ) == 1 ) {
			echo '<span class="info_vcard"><a href="' . JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$contact_id . '&amp;format=vcf') . '">' . JText::_('MOD_CONTENT_DGCONTACT_VCARD') . '</a></span>' . $linebreak . $newspace ."\n";
		}

		if ($separate_num > 1) {

			echo $separate_code."\n";

		}

		$separate_num=$separate_num-1;

	echo '</li>';

	}
	
	echo '<ul>';
	
	echo '</div>';

?>

