<?php

/**

 * @version		1.1.1

 * @package		Joomla

 * @subpackage	Helpdesk Pro

 * @author  Tuan Pham Ngoc

 * @copyright	Copyright (C) 2012 Ossolution Team

 * @license		GNU/GPL, see LICENSE.php

 */

// no direct access

defined( '_JEXEC' ) or die ;



abstract class HelpdeskProHelper {

	/**

	 * Get configuration data and store in config object

	 *

	 * @return object

	 */

	public static function getConfig() {

		static $config ;

		if (!$config) {

			$db = & JFactory::getDBO();

			$config = new stdClass ;

			$sql = 'SELECT * FROM #__helpdeskpro_configs';

			$db->setQuery($sql);

			$rows = $db->loadObjectList();

			for ($i = 0 , $n = count($rows); $i < $n; $i++) {

				$row = $rows[$i];

				$key = $row->config_key;

				$value = stripslashes($row->config_value);				

				$config->$key = $value;	

			}

		}		

		return $config;

	}					

	/**

	 * Get specify config value

	 *

	 * @param string $key

	 */

	public static function getConfigValue($key) {

		$db = & JFactory::getDBO() ;

		$sql = 'SELECT config_value FROM #__helpdeskpro_configs WHERE config_key="'.$key.'"';

		$db->setQuery($sql) ;		

				

		return $db->loadResult();

	}	

	/**

	 * Get Itemid of OS Membership Componnent

	 *

	 * @return int

	 */

	public static function getItemid() {

		$db = & JFactory::getDBO();				   

		$sql = "SELECT id FROM #__menu WHERE link LIKE '%index.php?option=com_helpdeskpro%' AND published=1";		 

		$db->setQuery($sql) ;

		$itemId = $db->loadResult();		

		if (!$itemId) {

			$Itemid = JRequest::getInt('Itemid');

			if ($Itemid == 1)

				$itemId = 999999 ;

			else 

				$itemId = $Itemid ;	

		}

					

		return $itemId ;	

	}		

	/**

	 * Load bootstrap lib

	 */

	public static function loadBootstrap($loadJs = true) {

		$document = JFactory::getDocument();

		if ($loadJs) {

			$document->addScript(JUri::root().'administrator/components/com_helpdeskpro/assets/bootstrap/js/jquery.min.js') ;

			$document->addScript(JUri::root().'administrator/components/com_helpdeskpro/assets/bootstrap/js/jquery-noconflict.js') ;

			$document->addScript(JUri::root().'administrator/components/com_helpdeskpro/assets/bootstrap/js/bootstrap.min.js') ;

		}

		$document->addStyleSheet(JURI::root().'administrator/components/com_helpdeskpro/assets/bootstrap/css/bootstrap.css');

	}	

	/**

	 * Load language from main component

	 *

	 */

	public static function loadLanguage() {

		static $loaded ;

		if (!$loaded) {

			$lang = & JFactory::getLanguage() ;

			$tag = $lang->getTag();

			if (!$tag)

				$tag = 'en-GB' ;			

			$lang->load('com_helpdeskpro', JPATH_ROOT, $tag);

			$loaded = true ;	

		}		

	}		

	/**

	 * Display copy right information

	 *

	 */

	public static function displayCopyRight() {		

		echo '<div class="copyright" style="text-align:center;margin-top: 5px;"><a href="http://joomdonation.com/components/helpdesk-pro.html" target="_blank"><strong>Helpdesk Pro</strong></a> version 1.1.1, Copyright (C) 2010-2011 <a href="http://joomdonation.com" target="_blank"><strong>Ossolution Team</strong></a></div>' ;

	}	

	/**

	 * 

	 */

	public static function getTicketCategoryIds($username) {		

		$db =  JFactory::getDbo() ;		

		$sql = "SELECT id FROM #__helpdeskpro_categories WHERE managers='$username' OR managers LIKE '$username,%' OR managers LIKE '%,$username,%' OR managers LIKE '%,$username'" ;

		$db->setQuery($sql);		

		return $db->loadResultArray();			

	}	

	/**

	 * Check ticket access

	 * @param Ticket object $item

	 */	

	public static function checkTicketAccess($item) {

		$user = JFactory::getUser() ;

		if (!$item->id)

			return false ;

		if ($item->is_ticket_code)

			return true ;

		if (!$user->id)

			return false ;

		if ($user->id == $item->user_id)

			return true ;

		if ($user->authorise('core.admin'))

			return true ;

		$managedCategoryIds = HelpdeskProHelper::getTicketCategoryIds($user->get('username')) ;

		if (in_array($item->category_id, $managedCategoryIds))

			return true ;

				

		return false ;

	}

	/**

	 * Send email to super administrator and user

	 *

	 * @param object $row The message object

	 * @param object $ticket The ticket object

	 * @param object $config

	 */

	public static function sendTicketUpdatedEmailToCustomer($row, $ticket, $config) {

		$app = JFactory::getApplication() ;

		if ($app->isAdmin()) {

			$Itemid = HelpdeskProHelper::getItemid();

		} else {

			$Itemid = JRequest::getInt('Itemid') ;

		}		

		$jconfig = new JConfig();

		$siteUrl = JURI::root() ;		

		$db = & JFactory::getDBO();

		$sql = 'SELECT name FROM #__users WHERE id='.$row->user_id ;

		$db->setQuery($sql);

		$manageName = $db->loadResult() ;



				

//		$sql = 'SELECT title FROM #__helpdeskpro_categories WHERE id='.$row->category_id ;

//		$db->setQuery($sql);

		$categoryTitle = '';

		

		if ($config->from_email)

			$fromEmail = $config->from_email ;

		else 										

			$fromEmail =  $jconfig->mailfrom;

		if ($config->from_name)

			$fromName = $config->from_name ;

		else		

			$fromName = $jconfig->fromname;	
				

		$replaces = array() ;

		$replaces['ticket_id'] = $ticket->id ;

		$replaces['name'] = $ticket->name ;

		$replaces['ticket_comment'] = $row->message ;

		$replaces['manager_name'] = $manageName ;

		$replaces['frontend_link'] = $siteUrl.'index.php?option=com_helpdeskpro&view=ticket&cid[]='.$ticket->id.'&Itemid='.$Itemid ;		

		$replaces['frontend_link_without_login'] = $siteUrl.'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$ticket->ticket_code.'&Itemid='.$Itemid ;

		$replaces['category_title'] = $categoryTitle ;								

		$subject = $config->ticket_updated_user_email_subject ;		

		$body = $config->ticket_updated_user_email_body ;				

		foreach ($replaces as $key=>$value) {

			$key = strtoupper($key) ;

			$body = str_replace("[$key]", $value, $body) ;

			$subject = str_replace("[$key]", $value, $subject) ;

		}		

		if (version_compare(JVERSION, '3.0', 'ge')) {

			$mailer = new JMail();

			$mailer->sendMail($fromEmail, $fromName, $ticket->email, $subject, $body, 1);

		} else {

			JUtility::sendMail($fromEmail, $fromName, $ticket->email, $subject, $body, 1);

		}						

	}

	/**

	 * Send email to super administrator and user

	 *

	 * @param object $row The message object

	 * @param object $ticket The ticket object

	 * @param object $config

	 */

	public static function sendTicketUpdatedEmailToManagers($row, $ticket, $config) {

		$app = JFactory::getApplication() ;

		if ($app->isAdmin()) {

			$Itemid = HelpdeskProHelper::getItemid();

		} else {

			$Itemid = JRequest::getInt('Itemid') ;

		}		

		$jconfig = new JConfig();

		$db = JFactory::getDbo() ;

		$siteUrl = JURI::root() ;

		if ($config->from_email)

			$fromEmail = $config->from_email ;

		else 										

			$fromEmail =  $jconfig->mailfrom;

		if ($config->from_name)

			$fromName = $config->from_name ;

		else		

			$fromName = $jconfig->fromname;

		

//		$sql = 'SELECT title FROM #__helpdeskpro_categories WHERE id='.$row->category_id ;

//		$db->setQuery($sql);

		$categoryTitle = '';

		

		$replaces = array() ;		

		$replaces['ticket_id'] = $ticket->id ;

		$replaces['name'] = $ticket->name ;

		$replaces['ticket_comment'] = $row->message ;		

		$replaces['frontend_link'] = $siteUrl.'index.php?option=com_helpdeskpro&view=ticket&cid[]='.$ticket->id.'&Itemid='.$Itemid ;

		$replaces['backend_link'] = $siteUrl.'administrator/index.php?option=com_helpdeskpro&view=ticket&cid[]='.$ticket->id.'&Itemid='.$Itemid ;

		$replaces['category_title'] = $categoryTitle ;		

							

		$sql = 'SELECT managers FROM #__helpdeskpro_categories WHERE id='.$ticket->category_id ;

		$db->setQuery($sql);

		$managers = $db->loadResult() ;			

		$managers = trim($managers) ;

		if ($managers) {

			$managers = explode(',', $managers);

			for ($i = 0 , $n = count($managers) ; $i < $n ; $i++) {

				$managers[$i] = trim($managers[$i]);

			}

			$sql = 'SELECT email FROM #__users WHERE username IN ("'.implode('","', $managers).'")';

			$db->setQuery($sql) ;

			$emails = $db->loadResultArray() ;

		} else {

			//Send email to general notification emails

			$emails = explode(',', $config->notification_emails);

			for($i = 0 , $n = count($emails) ; $i < $n; $i++) {

				$emails[$i] = trim($emails[$i]);				

			}			

		}		

		//Over-ridde email message

		$subject = $config->ticket_updated_admin_email_subject ;

		$body = $config->ticket_updated_admin_email_body ;

		foreach ($replaces as $key=>$value) {

			$key = strtoupper($key) ;

			$body = str_replace("[$key]", $value, $body) ;

			$subject = str_replace("[$key]", $value, $subject) ;

		}

		if (version_compare(JVERSION, '3.0', 'ge')) {

			$mailer = new JMail() ;

			$j3 = true ;

		} else {

			$j3 = false ;

		}

		foreach($emails as $email ){

			if ($email) {

				if ($j3) {

					$mailer->sendMail($fromEmail, $fromName, $email, $subject, $body, 1);

				} else {

					JUtility::sendMail($fromEmail, $fromName, $email, $subject, $body, 1);

				}				

			}				

		}						

	}

	/**

	 * Send email to super administrator and user

	 *

	 * @param object $row The message object

	 * @param object $ticket The ticket object

	 * @param object $config

	 */

	public static function sendNewTicketNotificationEmails($row, $config) {

		$app = JFactory::getApplication() ;

		if ($app->isAdmin()) {

			$Itemid = HelpdeskProHelper::getItemid();

		} else {

			$Itemid = JRequest::getInt('Itemid') ;

		}

		$jconfig = new JConfig();

		$db = JFactory::getDbo() ;

		$siteUrl = JURI::root() ;

		if ($config->from_email)

			$fromEmail = $config->from_email ;

		else 										

			$fromEmail =  $jconfig->mailfrom;

		if ($config->from_name)

			$fromName = $config->from_name ;

		else		

			$fromName = $jconfig->fromname;

		$sql = 'SELECT title FROM #__helpdeskpro_categories WHERE id='.$row->category_id ;

		$db->setQuery($sql);

		$categoryTitle = $db->loadResult() ;

		$replaces = array() ;

		$replaces['ticket_id'] = $row->id ;

		$replaces['ticket_subject'] = $row->subject ;

		$replaces['name'] = $row->name ;

		$replaces['ticket_message'] = $row->message ;

		$replaces['frontend_link'] = $siteUrl.'index.php?option=com_helpdeskpro&view=ticket&cid[]='.$row->id.'&Itemid='.$Itemid ;

		$replaces['backend_link'] = $siteUrl.'administrator/index.php?option=com_helpdeskpro&view=ticket&cid[]='.$row->id.'&Itemid='.$Itemid ;

		$replaces['frontend_link_without_login'] = $siteUrl.'index.php?option=com_helpdeskpro&view=ticket&ticket_code='.$row->ticket_code.'&Itemid='.$Itemid ;

		$replaces['category_title'] = $categoryTitle ;

					

		$sql = 'SELECT managers FROM #__helpdeskpro_categories WHERE id='.$row->category_id ;

		$db->setQuery($sql);

		$managers = $db->loadResult() ;

		$managers = trim($managers) ;

		if ($managers) {

			$managers = explode(',', $managers);

			for ($i = 0 , $n = count($managers) ; $i < $n ; $i++) {

				$managers[$i] = trim($managers[$i]);

			}

			$sql = 'SELECT email FROM #__users WHERE username IN ("'.implode('","', $managers).'")';

			$db->setQuery($sql) ;

			$emails = $db->loadResultArray() ;

		} else {

			//Send email to general notification emails

			$emails = explode(',', $config->notification_emails);

			for($i = 0 , $n = count($emails) ; $i < $n; $i++) {

				$emails[$i] = trim($emails[$i]);

			}

		}

		//Send message to administrators/managers

		

		$subject = $config->new_ticket_admin_email_subject	 ;

		$body = $config->new_ticket_admin_email_body ;

		foreach ($replaces as $key=>$value) {

			$key = strtoupper($key) ;

			$body = str_replace("[$key]", $value, $body) ;

			$subject = str_replace("[$key]", $value, $subject) ;

		}

		if (version_compare(JVERSION, '3.0', 'ge')) {

			$j3 = true ;

			$mailer = new JMail() ;

		} else {

			$j3 = false ;

		}

		foreach($emails as $email ){

			if ($email) {

				if ($j3) {

					$mailer->sendMail($fromEmail, $fromName, $email, $subject, $body, 1);

				} else {

					JUtility::sendMail($fromEmail, $fromName, $email, $subject, $body, 1);

				}				

			}				

		}

		

		//Send email to user

		$subject = $config->new_ticket_user_email_subject	 ;

		$body = $config->new_ticket_user_email_body ;

		foreach ($replaces as $key=>$value) {

			$key = strtoupper($key) ;

			$body = str_replace("[$key]", $value, $body) ;

			$subject = str_replace("[$key]", $value, $subject) ;

		}



		if ($j3) {

			$mailer->sendMail($fromEmail, $fromName, $row->email, $subject, $body, 1);

		} else {

			JUtility::sendMail($fromEmail, $fromName, $row->email, $subject, $body, 1);

		}		

	}	

	/**

	 * Process download a file

	 *

	 * @param string $file : Full path to the file which will be downloaded

	 */

	public static function processDownload($filePath, $filename) {

		jimport('joomla.filesystem.file') ;			

		$fsize = @filesize($filePath);

		$mod_date = date('r', filemtime($filePath) );

		$cont_dis ='attachment';

		$ext = JFile::getExt($filename) ;

		$mime = HelpdeskProHelper::getMimeType($ext);

		// required for IE, otherwise Content-disposition is ignored

		if(ini_get('zlib.output_compression'))  {

			ini_set('zlib.output_compression', 'Off');

		}

		header("Pragma: public");

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		header("Expires: 0");

		header("Content-Transfer-Encoding: binary");

		header('Content-Disposition:' . $cont_dis .';'

		. ' filename="' .$filename . '";'

		. ' modification-date="' . $mod_date . '";'

		. ' size=' . $fsize .';'

		); //RFC2183

		header("Content-Type: "    . $mime );			// MIME type

		header("Content-Length: "  . $fsize);

	

		if( ! ini_get('safe_mode') ) { // set_time_limit doesn't work in safe mode

			@set_time_limit(0);

		}

		

		HelpdeskProHelper::readfile_chunked($filePath);

	}

	/**

	 * Get mimetype of a file

	 *

	 * @return string

	 */

	public static function getMimeType($ext) {

		require_once JPATH_ROOT."/components/com_helpdeskpro/helper/mime.mapping.php";

		foreach ($mime_extension_map as $key=>$value)

		{

			if($key==$ext)

			{

				return $value;

			}

		}

		

		return "";

	}	

	/**

	 * Get formatted file size of a file

	 * @param string $filePath

	 */

	public static function getSize($filePath)

	{

		$kb = 1024;

		$mb = 1024 * $kb;

		$gb = 1024 * $mb;

		$tb = 1024 * $gb;	

		$size = @filesize($filePath);	

		if ($size) {

			if ($size < $kb) {

				$final = round($size,2);

				$file_size = $final .' '.'Byte';

			}

			elseif ($size < $mb) {

				$final = round($size/$kb,2);

				$file_size = $final .' '. 'KB';

			}

			elseif ($size < $gb) {

				$final = round($size/$mb,2);

				$file_size = $final .' '. 'MB';

			}

			elseif($size < $tb) {

				$final = round($size/$gb,2);

				$file_size = $final .' '. 'GB';

			} else {

				$final = round($size/$tb,2);

				$file_size = $final .' '. 'TB';

			}

		} else {

			if( $size == 0 ) {

				$file_size = 'EMPTY';

			} else {

				$file_size = 'ERROR';

			}

		}

		return $file_size;

	}

	/**

	 * Read file

	 *

	 * @param string $filename

	 * @param  $retbytes

	 * @return unknown

	 */

	public static function readfile_chunked($filename,$retbytes=true)

	{

		$chunksize = 1*(1024*1024); // how many bytes per chunk

		$buffer = '';

		$cnt =0;

		$handle = fopen($filename, 'rb');

		if ($handle === false) {

			return false;

		}

		while (!feof($handle)) {

			$buffer = fread($handle, $chunksize);

			echo $buffer;

			@ob_flush();

			flush();

			if ($retbytes) {

				$cnt += strlen($buffer);

			}

		}

		$status = fclose($handle);

		if ($retbytes && $status) {

			return $cnt; // return num. bytes delivered like readfile() does.

		}

		return $status;

	}	

	/**

	 * Store ticket from input data

	 * @param array $data

	 */	

	public static function storeTicket($data) {

		$db = JFactory::getDbo() ;

		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_helpdeskpro/tables');

		jimport('joomla.user.helper');

		$config = HelpdeskProHelper::getConfig();		

		$user = JFactory::getUser() ;

		$row = JTable::getInstance('helpdeskpro', 'ticket');

		$row->bind($data);

		if ($user->get('id')) {			

			$row->user_id = $user->get('id');

		} else {

			$sql = 'SELECT id FROM #__users WHERE email="'.$data['email'].'"';

			$db->setQuery($sql);

			$row->user_id = $db->loadResult();

		}		

		$row->status_id = $config->new_ticket_status_id ;

		while(true) {

			$ticketCode = strtolower(JUserHelper::genRandomPassword(10)) ;

			$sql = 'SELECT COUNT(*) FROM #__helpdeskpro_tickets WHERE ticket_code="'.$ticketCode.'"';

			$db->setQuery($sql) ;

			$total = $db->loadResult();

			if (!$total)

				break ;

		}

		$row->ticket_code = $ticketCode ;

		$row->created_date = $row->modified_date = gmdate('Y-m-d H:i:s') ;

		$row->store();

		//Store custom fields information for this ticket	

		HelpdeskProHelper::sendNewTicketNotificationEmails($row, $config) ;

									

		return true ;

								

	}	

    /**

     * Add submenus, only used for Joomla 1.6 and newer version

     * 

     * @param string $vName

     */

    public static function addSubMenus($vName = 'plans') {			

		JSubMenuHelper::addEntry(

			JText::_('HDP_CONFIGURATION'),

			'index.php?option=com_helpdeskpro&view=configuration',

			$vName == 'configuration'

		);

		JSubMenuHelper::addEntry(

			JText::_('HDP_CATEGORIES'),

			'index.php?option=com_helpdeskpro&view=categories',

			$vName == 'categories'

		);

		JSubMenuHelper::addEntry(

			JText::_('HDP_TICKETS'),

			'index.php?option=com_helpdeskpro&view=tickets',

			$vName == 'tickets'

		);

						

		JSubMenuHelper::addEntry(

				JText::_('HDP_CUSTOM_FIELDS'),

				'index.php?option=com_helpdeskpro&view=fields',

				$vName == 'fields'

		);

		

		JSubMenuHelper::addEntry(

				JText::_('HDP_TICKET_STATUSES'),

				'index.php?option=com_helpdeskpro&view=statuses',

				$vName == 'statuses'

		);

							

		JSubMenuHelper::addEntry(

				JText::_('HDP_TICKET_PRIORITIES'),

				'index.php?option=com_helpdeskpro&view=priorities',

				$vName == 'priorities'

		);

											

		JSubMenuHelper::addEntry(

				JText::_('HDP_TRANSLATION'),

				'index.php?option=com_helpdeskpro&view=language',

				$vName == 'language'

		);											

	}   	

}

?>