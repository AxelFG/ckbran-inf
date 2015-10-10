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

		$imageInfo->image = '/modules/mod_contactinfo/default.jpg';

	}

	$timthumbPath=JURI::base() . 'modules/mod_contactinfo/includes/timthumb.php?src=' . JURI::root() . $imageInfo->image . '&w='.$widthheight ;

	if (is_file( JPATH_BASE . '/'. $imageInfo->image )) {

		$iconimage = '<img src="'. $timthumbPath .'" title="' . $imageInfo->name . '" alt="' . $imageInfo->name . '" class="contactIcon"  />';

	} else {

		$iconimage = '';

	}

	return $iconimage ;

}

}

// !IMPORTANT. OBTAINING CONTACT IDs FROM DB

$db		=& JFactory::getDBO();


$type = JRequest::getVar('view');

$currentid = JRequest::getVar('id');

if (JRequest::getVar('option') == 'com_content' && $type != 'search') {	

	if ($type != 'article')
	
		$query = 'SELECT  value  FROM #__fieldsattach_categories_values WHERE  fieldsid = 22 AND catid = '.$currentid;
	
	elseif ($type == 'article')
	
		$query = 'SELECT  value  FROM #__fieldsattach_values WHERE  fieldsid = 23 AND articleid = '.$currentid;
	
	$db->setQuery( $query );
	
	$contact_id = $db->loadResult();

}

if (!$contact_id) $contact_id = 0;



	global $mainframe, $database, $my, $Itemid;


/*************************************************/


//Set up ordering


	$contact_array = explode(",", $contact_id);


	$contactordering = 'ORDER BY ';


	for ( $i=0; $i<sizeof($contact_array); $i++ ) {


		$contactordering .= "id=" . $contact_array[$i] . " DESC, ";  


	}  


	$contactordering = rtrim($contactordering, ', ');


/*************************************************/


	$query = "SELECT * FROM #__contact_details WHERE id IN ( $contact_id ) ORDER BY ordering ASC";


	$db->setQuery( $query );


	$contacts = $db->loadObjectList();





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


if (sizeof($contacts)) {
	
	echo '<div class="contactsblock"><h3>'.$module->title.'</h3><ul>';

	$separate_num=sizeof($contacts);


	for ( $i=0; $i<sizeof($contacts); $i++ ) {

		echo '<li>';

		echo '<div class="contact-cell">';

		$telephone_array=explode(",",$contacts[$i]->telephone);


		$slug = $contacts[$i]->id.":".$contacts[$i]->alias;

		$url = ContactHelperRoute::getContactRoute($slug,$contacts[$i]->catid);


		if ($params->get( 'show_image', '' ) == 1 && $contacts[$i]->name != '') {


			if ($params->get( 'link_image', '' ) == 1) {


				echo "<a class=\"info_image\" href=\"". $url . "\">" . contactImage( $contacts[$i], $params->get( 'thumb_size', 100 ) ) . "</a>";


			} else {


				echo contactImage( $contacts[$i], $params->get( 'thumb_size', 100 ) ) . "$linebreak$newspace\n";


			}


		}

		echo '</div>';
		echo '<div class="contact-cell">';

		if ($params->get( 'show_name', '' ) == 1 && $contacts[$i]->name != '') {


			if ($params->get( 'link_to', '' ) == 1) {


				echo "<span class=\"info_name\"><a href=\"". $url . "\">" . $contacts[$i]->name . "</a></span>";


			} else {


				echo "<span class=\"info_name\">" . $contacts[$i]->name . "</span>";


			}


		}


		echo '<br />';


		if ($params->get( 'show_alias', '' ) == 1 && $contacts[$i]->alias != '') {


			if ($params->get( 'link_to', '' ) == 1 && $params->get( 'show_name', '' ) != 1) {


				echo "<span class=\"info_name\"><a href=\"index.php?option=com_contact&task=view&id=" . $contacts[$i]->id . "\">" . $contacts[$i]->alias . "</a></span>$linebreak$newspace\n";


			} else {


				echo "<span class=\"info_alias\">" . $contacts[$i]->alias . "</span>$linebreak$newspace\n";


			}


		}


		if ($params->get( 'con_position', '' ) == 1 && $contacts[$i]->con_position != '') {


			echo "<span class=\"info_position\"><a href=\"". $url . "\">".$contacts[$i]->con_position . "</a></span>";


		}

		echo '</div>';

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



		if ($separate_num > 0) {


			echo $separate_code."\n";


		}


		echo '</li>';


		$separate_num=$separate_num-1;


	}


	echo '</ul></div>';

}


?>


