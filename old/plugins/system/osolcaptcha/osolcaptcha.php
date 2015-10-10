<?php
/**
* OSOLCaptcha Plugin for Joomla 2.5
* @version $Id: osolcaptcha.php $
* @package: OSOLCaptcha 
* ===================================================
* @author
* Name: Sreekanth Dayanand, www.outsource-online.net
* Email: joomla@outsource-online.net
* Url: http://www.outsource-online.net
* ===================================================
* @copyright (C) 2012 Sreekanth Dayanand, Outsource Online (www.outsource-online.net). All rights reserved.
* @license see http://www.gnu.org/licenses/gpl-2.0.html  GNU/GPL.
* You can use, redistribute this file and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation.
*/

defined('_JEXEC') or die();


class plgSystemOSOLCaptcha extends JPlugin
{

		var $bgColor = "#000000";
		var $textColor = "#ff0000";
		
		//var $params;
		var $botScoutProtection  = '';
		var $botscoutAPIKey  = '';
		var $redirectURLforSuspectedIPs  = '';
		var $reportBotscoutNegativeMail='';
		var $enabledForms = array();
		var $addToForms =  array();
		var $ajaxChecksFor = array();
        function display()
		{
			$plugin 	=& JPluginHelper::getPlugin('system', 'osolcaptcha');
			//$this->params   	= new JParameter($plugin->params);
			$imageFunction = 'create_image'.$this->params->get('imageFunction');//JRequest::getVar('imageFunction','');
			$imageFunction = ((!method_exists($this,$imageFunction)))?'create_imageAdv':$imageFunction;
		    
			 
		   $this->$imageFunction();
		   exit;
		

		return true;
	}
	function setColors()
	{
			$plugin 	=& JPluginHelper::getPlugin('system', 'osolcaptcha');
			//$this->params   	= new JParameter($plugin->params);
			$this->bgColor  = $this->params->get('bgColor',$this->bgColor);
			$this->textColor  = $this->params->get('textColor',$this->textColor);
	}
	
	
	// generates distorted letters ,this is a revised version of a method used in kcaptcha
	#http://www.phpclasses.org/browse/package/3193.html
		
	# Copyright by Kruglov Sergei, 2006, 2007, 2008
	# www.captcha.ru, www.kruglov.ru
	
	# System requirements: PHP 4.0.6+ w/ GD
	
	# KCAPTCHA is a free software. You can freely use it for building own site or software.
	# If you use this software as a part of own sofware, you must leave copyright notices intact or add KCAPTCHA copyright notices to own.
	function create_imageAdv(){

		$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz";
		$allowed_symbols = "23456789abcdeghkmnpqsuvxyz";
		
		$length = 5;
		$width = 120;
		$height = 60;
		$fluctuation_amplitude = 5;
		$no_spaces = true;
		$this->setColors();
		$foreground_color = $this->HexToRGB($this->textColor) ;//array(255,255,255);//array(180, 180, 180);//array(255,255,255);//
		$background_color = $this->HexToRGB($this->bgColor) ;//array(44,127,7);//array(53,3,0);//array(246, 246, 246);//array(0,0,0);//
		$jpeg_quality = 90;
		
	
		$alphabet_length=strlen($alphabet);
		
		do{
			// generating random keystring
			while(true){
				$this->keystring='';
				for($i=0;$i<$length;$i++){
					$this->keystring.=$allowed_symbols{mt_rand(0,strlen($allowed_symbols)-1)};
				}
				if(!preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $this->keystring)) break;
			}
		
			$font_file=$font_file=dirname(__FILE__).DS.'osolCaptcha'.DS.'adlibBT.png';
			$font=imagecreatefrompng($font_file);
			imagealphablending($font, true);
			$fontfile_width=imagesx($font);
			$fontfile_height=imagesy($font)-1;
			$font_metrics=array();
			$symbol=0;
			$reading_symbol=false;

			// loading font
			for($i=0;$i<$fontfile_width && $symbol<$alphabet_length;$i++){
				$transparent = (imagecolorat($font, $i, 0) >> 24) == 127;

				if(!$reading_symbol && !$transparent){
					$font_metrics[$alphabet{$symbol}]=array('start'=>$i);
					$reading_symbol=true;
					continue;
				}

				if($reading_symbol && $transparent){
					$font_metrics[$alphabet{$symbol}]['end']=$i;
					$reading_symbol=false;
					$symbol++;
					continue;
				}
			}

			$img=imagecreatetruecolor($width, $height);
			imagealphablending($img, true);
			$white=imagecolorallocate($img, 255, 255, 255);
			$black=imagecolorallocate($img, 0, 0, 0);

			imagefilledrectangle($img, 0, 0, $width-1, $height-1, $white);

			// draw text
			$x=1;
			for($i=0;$i<$length;$i++){
				$m=$font_metrics[$this->keystring{$i}];

				$y=mt_rand(-$fluctuation_amplitude, $fluctuation_amplitude)+($height-$fontfile_height)/2+2;

				if($no_spaces){
					$shift=0;
					if($i>0){
						$shift=10000;
						for($sy=7;$sy<$fontfile_height-20;$sy+=1){
							for($sx=$m['start']-1;$sx<$m['end'];$sx+=1){
				        		$rgb=imagecolorat($font, $sx, $sy);
				        		$opacity=$rgb>>24;
								if($opacity<127){
									$left=$sx-$m['start']+$x;
									$py=$sy+$y;
									if($py>$height) break;
									for($px=min($left,$width-1);$px>$left-12 && $px>=0;$px-=1){
						        		$color=imagecolorat($img, $px, $py) & 0xff;
										if($color+$opacity<190){
											if($shift>$left-$px){
												$shift=$left-$px;
											}
											break;
										}
									}
									break;
								}
							}
						}
						if($shift==10000){
							$shift=mt_rand(4,6);
						}

					}
				}else{
					$shift=1;
				}
				imagecopy($img, $font, $x-$shift, $y, $m['start'], 1, $m['end']-$m['start'], $fontfile_height);
				$x+=$m['end']-$m['start']-$shift;
			}
		}while($x>=$width-10); // while not fit in canvas

		$center=$x/2;

		
		$img2=imagecreatetruecolor($width, $height);
		$foreground=imagecolorallocate($img2, $foreground_color[0], $foreground_color[1], $foreground_color[2]);
		$background=imagecolorallocate($img2, $background_color[0], $background_color[1], $background_color[2]);
		imagefilledrectangle($img2, 0, 0, $width-1, $height-1, $background);		
		imagefilledrectangle($img2, 0, $height, $width-1, $height+12, $foreground);
		

		// periods
		$rand1=mt_rand(750000,1200000)/10000000;
		$rand2=mt_rand(750000,1200000)/10000000;
		$rand3=mt_rand(750000,1200000)/10000000;
		$rand4=mt_rand(750000,1200000)/10000000;
		// phases
		$rand5=mt_rand(0,31415926)/10000000;
		$rand6=mt_rand(0,31415926)/10000000;
		$rand7=mt_rand(0,31415926)/10000000;
		$rand8=mt_rand(0,31415926)/10000000;
		// amplitudes
		$rand9=mt_rand(330,420)/110;
		$rand10=mt_rand(330,450)/110;

		//wave distortion

		for($x=0;$x<$width;$x++){
			for($y=0;$y<$height;$y++){
				$sx=$x+(sin($x*$rand1+$rand5)+sin($y*$rand3+$rand6))*$rand9-$width/2+$center+1;
				$sy=$y+(sin($x*$rand2+$rand7)+sin($y*$rand4+$rand8))*$rand10;

				if($sx<0 || $sy<0 || $sx>=$width-1 || $sy>=$height-1){
					continue;
				}else{
					$color=imagecolorat($img, $sx, $sy) & 0xFF;
					$color_x=imagecolorat($img, $sx+1, $sy) & 0xFF;
					$color_y=imagecolorat($img, $sx, $sy+1) & 0xFF;
					$color_xy=imagecolorat($img, $sx+1, $sy+1) & 0xFF;
				}

				if($color==255 && $color_x==255 && $color_y==255 && $color_xy==255){
					continue;
				}else if($color==0 && $color_x==0 && $color_y==0 && $color_xy==0){
					$newred=$foreground_color[0];
					$newgreen=$foreground_color[1];
					$newblue=$foreground_color[2];
				}else{
					$frsx=$sx-floor($sx);
					$frsy=$sy-floor($sy);
					$frsx1=1-$frsx;
					$frsy1=1-$frsy;

					$newcolor=(
						$color*$frsx1*$frsy1+
						$color_x*$frsx*$frsy1+
						$color_y*$frsx1*$frsy+
						$color_xy*$frsx*$frsy);

					if($newcolor>255) $newcolor=255;
					$newcolor=$newcolor/255;
					$newcolor0=1-$newcolor;

					$newred=$newcolor0*$foreground_color[0]+$newcolor*$background_color[0];
					$newgreen=$newcolor0*$foreground_color[1]+$newcolor*$background_color[1];
					$newblue=$newcolor0*$foreground_color[2]+$newcolor*$background_color[2];
				}

				imagesetpixel($img2, $x, $y, imagecolorallocate($img2, $newred, $newgreen, $newblue));
			}
		}
		
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		header('Cache-Control: post-check=0, pre-check=0', FALSE); 
		header('Pragma: no-cache');
		
		if(function_exists("imagejpeg")){
			header("Content-Type: image/jpeg");
			imagejpeg($img2, null, $jpeg_quality);
		}else if(function_exists("imagegif")){
			header("Content-Type: image/gif");
			imagegif($img2);



		}else if(function_exists("imagepng")){
			header("Content-Type: image/x-png");
			imagepng($img2);
		}
		$security_code = $this->keystring;
		//Set the session to store the security code
		//$_SESSION["security_code"] 
		$currentSession =  & JFactory::getSession() ;//&JSession::getInstance('none',array()); 
		$currentSession->set('securiy_code'.(JRequest::getVar('instanceNo')+0), $security_code);
		$width = 120;//100;
		$height = 40;//20;
		
		
	}
	// generates plain letters
	function create_imagePlane()
	{
		//Let's generate a totally random string using md5
		$md5_hash = md5(rand(0,999)); 
		//We don't need a 32 character long string so we trim it down to 5 
		$security_code = str_replace(array("0","O","o"), array("p"),substr($md5_hash, 15, 5)); 
		
		//Set the session to store the security code
		//$_SESSION["security_code"] 
		$currentSession =  & JFactory::getSession() ;//&JSession::getInstance('none',array()); 
		$currentSession->set('securiy_code'.(JRequest::getVar('instanceNo')+0), $security_code);
		$width = 120;//100;
		$height = 40;//20;
		$image = imagecreate($width, $height);  
		$this->setColors();
		$foreground_color = $this->HexToRGB($this->textColor) ;//array(255,255,255);//array(180, 180, 180);//array(255,255,255);//
		$background_color = $this->HexToRGB($this->bgColor) ;
		//We are making three colors, white, black and gray
		$white = imagecolorallocate ($image, $foreground_color[0],$foreground_color[1],$foreground_color[2]);//255, 255, 255);
		$black = imagecolorallocate ($image,$background_color[0],$background_color[1],$background_color[2]);//44,127,7);// imagecolorallocate ($image, 0, 0, 0);
		$grey = imagecolorallocate ($image, 204, 204, 204);
		
		//Make the background black 
		imagefill($image, 0, 0, $black); 
		//imagestring($image, 3, 30, 3, $security_code, $white);
		$size = 10;
		$this->ly = (int)(2.4 * $size);
		$x = 20;
		for($i=0;$i<strlen($security_code);$i++)
		{
			
			$angle = rand(-45,45);
			$y        = intval(rand((int)($size * 1.5), (int)($this->ly - ($size / 7))));
			
			@imagettftext($image, $size, $angle, $x + (int)($size / 15), $y, $white, dirname(__FILE__).DS.'osolCaptcha'.DS.'adlibBT.TTF', $security_code[$i]);
			$x += ($size *2);
		}
		//imageline($image, 0, $height/2, $width, $height/2, $grey); 
		//imageline($image, $width/2, 0, $width/2, $height, $grey); 
		header('Content-type: image/png');
		imagepng($image);
		//imagedestroy($image);
	}	

	function confirm( $word,$instanceNo='' )
	{
		
		$currentSession = & JFactory::getSession() ;

		$securiy_code = $currentSession->get('securiy_code'.$instanceNo);
		
		if ( $word == $securiy_code  &&  ($word != '')) 
		   return true;
		else
		   return false;  

	}

	// Function wrappers for TriggerEvent usage
	function onCaptcha_Display() {	
		return $this->display();	
	}

	function onCaptcha_confirm($word, &$return) {		
		$return = $this->confirm($word);
		return $return;
	}
	function addAJAXCheck($ajaxCheckFor,$index)
	{
		
		  if(isset($ajaxCheckFor['id']))
		  {
			  $formId = $ajaxCheckFor['id'];
			  $scriptToGetForm = "$('$formId')";
		  }
		  else //if(isset($ajaxCheckFor['name']))
		  {
			  
			  
			  list($k,$v)=each($ajaxCheckFor);
			  $scriptToGetForm = "$$( \"form[$k=\"+\"$v\" +\"]\")[0]";
			 
		  }
		  //alert($$( \"input[name=\"+\"jform[contact_name]\" +\"]\").map(function(e) { return e.value; }));
		  //alert($$( \"input[name=\"+\"jform[contact_name]\" +\"]\")[0].value);
		 
		  $captchaVerifyURL = JURI::base()."index.php";
		   $ajax = "
		   				
						window.addEvent( 'domready', function() {
								onsubmitFunction =  $scriptToGetForm.get('onsubmit');
									//alert($scriptToGetForm.option.value);
									$scriptToGetForm.addEvent( 'submit', function(e){
																				   
										var captchaResponse =  true;
										formInst = this;
										osolCatchaTxtInst = this.osolCatchaTxtInst.value;
										osolCatchaTxt = this.osolCatchaTxt.value;
										//alert($('osolCaptcha-ajax-container'));
										  $('osolCaptcha-ajax-container".$index."').addClass('osolCaptcha-ajax-loading');
								 			$('osolCaptcha-ajax-container".$index."').innerHTML =  \"Please wait while verifying captcha\";
										  /*var a = new Ajax('{$captchaVerifyURL}',{
											   method:'get',
											   onComplete:function(response){
													//var resp=Json.evaluate(response);
													alert('ajax complete ,result : ' + resp);
													// Other code to execute when the request completes.
													$('osolCaptcha-ajax-container".$index."').removeClass('osolCaptcha-ajax-loading').innerHTML = '';
											   }
										  }).request();*/
										  var request = new Request({
			
																	url: '{$captchaVerifyURL}',
											
																	method:'get',
											
																	data: 'verifyCaptcha=True&instanceNo='+osolCatchaTxtInst+'&osolCatchaTxtInst='+osolCatchaTxt,
											
																	onSuccess: function(responseText){
														
																					//alert( responseText);
																					$('osolCaptcha-ajax-container".$index."').removeClass('osolCaptcha-ajax-loading').innerHTML = '';
																					if(responseText == 'false')
																					{
																						alert('".JTEXT::_('OSOLCAPTCHA_ERROR_MESSAGE')."')
																						captchaResponse = false;
																					}
																					else
																					{
																						//alert( onsubmitFunction);
																						if( eval(onsubmitFunction) || onsubmitFunction == null )
																						{
																							formInst.submit();
																						}
																					}
																					
																					
														
																				}
											
																	}).send();
										  
										  
											return false;
											
										  
									 });
						
					} );
		   
					 
					";
			
		  return $ajax;//$document->addScriptDeclaration($ajax); doesnt work in onAfterRender so it must be inserted with str_replce
			//echo("FFFFF");
	}//addAJAXCheck($ajaxCheckFor)
	 function adminOptionsForm()
	   {
		  
		   $script1 = "
		   
		   				function reloadCapthcha(instanceNo)
						{
							var captchaSrc = \"".JURI::base()."index.php?showCaptcha=True&instanceNo=\"+instanceNo+\"&time=\"+ new Date().getTime();
							//alert(captachaSrc);
							//alert(document.getElementById('captchaCode'+instanceNo));
							document.getElementById('captchaCode'+instanceNo).src = captchaSrc ;
							//alert(document.getElementById('captchaCode'+instanceNo).src);
						} 
						";
			$script2 = "
		   window.addEvent('domready', function() {
														   
				/*var formitems = '[';
				$$( 'input.required').each(function(inputItem){
												 formitems = formitems + \"'\" +(inputItem.id)+\"',\";
												 }
														   );
			 	formitems = formitems.substring(0,formitems.length-1) + ']';
				alert(formitems);/**/
				vars = [];
				//alert( document.getElementById('member-registration') );
				switch(true)
				{
					case(document.getElementById('contact-form') != null ):
						vars = ['jform_contact_name','jform_contact_email','jform_contact_emailmsg','jform_contact_message'];
						break;
					case(document.getElementById('member-registration') != null ):
						vars = ['jform_name','jform_username','jform_password1','jform_password2','jform_email1','jform_email2']
						break;
					case(document.getElementById('username')!= null):
						vars = ['username','password'];
						
						break;
				}
				
				for(var i=0;i<vars.length;i++)
				{
					//alert(document.getElementById(vars[i]).value);
					//$$( \"input[id=\"+vars[i]).set('value','fff@sdsd.com') ;
					document.getElementById(vars[i]).value = 'fff@sdsd.com';
				}
				//alert($$( \"input[name=\"+\"jform[contact_name]\" +\"]\").map(function(e) { return e.value; }));
				//alert($$( \"input[name=\"+\"jform[contact_name]\" +\"]\")[0].value);
				//alert($$( \"form\")[0].action);
				
			 });";
			
		   $document =& JFactory::getDocument();
    	   $document->addScriptDeclaration($script1);
		   //$document->addScriptDeclaration($script2);
		   JHTML::stylesheet(JURI::base() . 'plugins/system/osolcaptcha/osolCaptcha/captchaStyle.css');//, $path);
		   //name=\"osolCatchaTxtInst\"
		   
		   
	   }
		//declare the system events
		/**
         * Do something onAfterInitialise 
         */
       function onAfterInitialise()
	   {
		   //echo "HI";
		  
		   $showCaptcha = JRequest::getVar('showCaptcha');
			if($showCaptcha == 'True')
			{
				return $this->display();
			}
		   if(JRequest::getVar('verifyCaptcha','')=='True')
		   {
			   if($this->confirm( JRequest::getVar('osolCatchaTxtInst',''),JRequest::getVar('instanceNo','') ))
			   {
				   die('true');
			   }
			   else
			   {
				   die('false');
			   }
		   }
		   $this->getEnabledForms();
	   }
	   // special function to check the  registration form submission
	   public function onUserBeforeSave($user, $isnew, $new)
		{
			//die('dsdsd');
			 $this->shouldCheckForOSOLCaptcha();
		}
	   function onAfterRoute()
        {
			$this->loadLanguage('plg_system_osolcaptcha', JPATH_ADMINISTRATOR);
			$this->shouldCheckForOSOLCaptcha();
			$this->adminOptionsForm();
			$this->shouldInsertOSOLCaptcha();
			global $mainframe;
			$this->botscoutCheck();
			
			
			$pathArray = preg_split("~/~",JURI::base());//fix provided by Gruz from ukraine on 5th september 2010

			
			$isAdmin = ($pathArray[(count($pathArray) - 2)] == "administrator");
			
			if($isAdmin  )
			{
				$currentSession =  & JFactory::getSession() ;//&JSession::getInstance('none',array()); 
				$sessOsolAdminPassPhrase = $currentSession->get('osolAdminPassPhrase','');
				$paramOsolPassPhrase =$this->params->get("adminPassPhrase");
				$osolPP = JRequest::getVar('osolPP','');
				/*echo  $sessOsolAdminPassPhrase . " | ". $osolPP . " | ".$paramOsolPassPhrase. " | <br />";
				echo "( $sessOsolAdminPassPhrase != $paramOsolPassPhrase)  = ".( $sessOsolAdminPassPhrase != $paramOsolPassPhrase) ."<br />";
				echo ($paramOsolPassPhrase !='')." && ".( $sessOsolAdminPassPhrase != $paramOsolPassPhrase) ." && ". ($osolPP!= $paramOsolPassPhrase )."<br />";
				echo "($paramOsolPassPhrase !='') && ( $sessOsolAdminPassPhrase != $paramOsolPassPhrase)  &&  ($osolPP!= $paramOsolPassPhrase ) :".($paramOsolPassPhrase !='') && ( $sessOsolAdminPassPhrase != $paramOsolPassPhrase)  &&  ($osolPP!= $paramOsolPassPhrase ) ."|";
				exit;*/
				if(($paramOsolPassPhrase !='') && ( $sessOsolAdminPassPhrase != $paramOsolPassPhrase)  &&  ($osolPP!= $paramOsolPassPhrase ) )
				{
					$liveSiteUserSide  = str_replace("/administrator/","/",JURI::base());
					$this->redirect($liveSiteUserSide);
					
				}
				elseif($osolPP == $paramOsolPassPhrase )
				{
					
					$currentSession->set('osolAdminPassPhrase',$osolPP);
				}
			}
        }
		// Redirect if spamcheck wasn't passed
    private function redirect($returnURI)
    {
        // PHP Redirection
        header('Location: '.$returnURI);

        // JS Redirection
        ?>
        <script type="text/javascript">window.location = '<?php echo $returnURI; ?>'</script>
        <?php
        // White page - if redirection doesn't work
        echo JText::_('Redirecting');
        jexit();
    }

        function GetCapthcaHTML($vertical = false)
		{
			JPlugin::loadLanguage( 'plg_system_osolcaptcha', JPATH_ADMINISTRATOR );
			if(!isset($GLOBALS['totalCaptchas']))
			{
				$GLOBALS['totalCaptchas'] = -1;
			}
			#JHTML::_('behavior.tooltip');

			$GLOBALS['totalCaptchas']++;
			$doc =& JFactory::getDocument();
			$cssFile = dirname(__FILE__).DS.'osolCaptcha'.DS.'captchaStyle.css';
			if(is_file($cssFile ) && !is_dir($cssFile))
			{
				$style = file_get_contents(dirname(__FILE__).DS.'osolCaptcha'.DS.'captchaStyle.css'); 
				$doc->addStyleDeclaration( $style );
			}

			return ("
					<div class=\"osolCaptchaBlock\">
					<div id=\"osolCaptcha-ajax-container".$GLOBALS['totalCaptchas']."\"></div>
					<label for=\"osolCatchaTxt{$GLOBALS['totalCaptchas']}\">".JText::_('OSOLCAPTCHA_ENTER_CAPTCHA_VALUE')."</label>".
					($vertical?"<br />":"")."


 			

			<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td width=\"120\" class=\"osol_captcha_td\"  >
	

	<a href=\"http://www.outsource-online.net/osol-captcha-for-joomla.html\" target=\"blank\" style=\"font-size:10px\" >
	<img id=\"captchaCode".$GLOBALS['totalCaptchas']."\" src=\"".JURI::base()."index.php?showCaptcha=True&amp;instanceNo=".$GLOBALS['totalCaptchas']."\" alt=\"Captcha plugin 2+ for Joomla from Outsource Online\" /> 
	</a>
  

	</td>
	".($vertical?"</tr><tr>":"")."
    <td valign=\"top\"  ".($vertical?"":"width=\"170\"")." class=\"osol_captcha_td\">
	
			<label>
       <a href=\"#\" onclick=\"reloadCapthcha(".$GLOBALS['totalCaptchas'].");return false;\" >".JText::_('OSOLCAPTCHA_REFRESH_CAPTCHA')."</a>
    </label></td>
	".($vertical?"</tr><tr>":"")."
    <td valign=\"top\" class=\"osol_captcha_td\"  >
		<input type=\"text\" name=\"osolCatchaTxt\" id=\"osolCatchaTxt{$GLOBALS['totalCaptchas']}\"  class=\"inputbox required validate-captcha\" />&nbsp;
		<input type=\"hidden\" name=\"osolCatchaTxtInst\" id=\"osolCatchaTxtInst\"  value=\"".$GLOBALS['totalCaptchas']."\"   /><br/>
	
	</td>
  </tr>
   
  
</table>
</div>
");
		}
		function createFormActionRegexp($regexp)
		{
			return preg_replace("/&/","(&amp;|&)",preg_quote(JRoute::_($regexp)));
		}
		function getEnabledForms()
		{
			
			$isEnabledForForms = $this->getIsEnabledForForms();
			if($isEnabledForForms['enableForContactUs'])
			{
				$this->enabledForms['Core Joomla Contact Us']  = array('requestVars' => 'option=com_contact&view=contact|contact.submit',
																		 'formId' => "contact-form",//if there is no name use formName
																		 'tagToPlaceCaptchaBefore' =>'<button class="button validate" type="submit">',
																		 'verifyOnVars' =>'option=com_contact&task=contact.submit',
																		 //'redirectOnfailure' =>JURI::base() ,//JFactory::getURI()->toString(),
																		 'skipAJAXVerification' => false,
																		 'onCaptchaFailSetVars' =>'view=contact&task='
																		);
			}
			if($isEnabledForForms['enableForRegistration'])
			{
				$this->enabledForms['Core Joomla Registration']  = array('requestVars' => 'option=com_users&view=registration',
																 'formId' => "member-registration",//if there is no name use formName 
																 'tagToPlaceCaptchaBefore' =>'<button type="submit" class="validate">',
																 'verifyOnVars' =>'option=com_users&task=registration.register',
																 //'redirectOnfailure' =>JFactory::getURI()->toString(),
																 'skipAJAXVerification' => false,
																 'onCaptchaFailSetVars' =>'view=registration&task='
																);
			}
			if($isEnabledForForms['enableForRemind'])
			{
				$this->enabledForms['Core Joomla remind username'] = array('requestVars' => 'option=com_users&view=remind',
																 'formId' => "user-registration",//if there is no name use formName 
																 'tagToPlaceCaptchaBefore' =>'<button type="submit" class="validate">',
																 'verifyOnVars' =>'option=com_users&task=remind.remind',
																 //'redirectOnfailure' =>JFactory::getURI()->toString(),
																 'skipAJAXVerification' => false,
																 'onCaptchaFailSetVars' =>'view=remind&task='
																);
			}
			if($isEnabledForForms['enableForReset'])
			{
				$this->enabledForms['Core Joomla reset password'] = array('requestVars' => 'option=com_users&view=reset',
																 'formId' => "user-registration",//if there is no name use formName 
																 'tagToPlaceCaptchaBefore' =>'<button type="submit" class="validate">',
																 'verifyOnVars' =>'option=com_users&task=reset.request',
																 //'redirectOnfailure' =>JFactory::getURI()->toString(),
																 'skipAJAXVerification' => false,
																 'onCaptchaFailSetVars' =>'view=reset&task='
																);
			}
			if($isEnabledForForms['enableForComLogin'] && 0)
			{
				$this->enabledForms['Core Joomla login Module'] = array('requestVars' => '*',
																		 'formId' => "login-form",//if there is no name use formName
																		 'ignore_condition' => 'task=user.logout',
																		 'tagToPlaceCaptchaBefore' =>'<input type="submit"',
																		 'verifyOnVars' =>'option=com_users&task=user.login',
																		 'redirectOnfailure' =>JURI::base()."index.php/login" ,//JFactory::getURI()->toString(),
																		 'skipAJAXVerification' => false,
																		 //'onCaptchaFailSetVars' =>'view=users&task='
																		 'isVertical' =>  true,
																		) ;
				$this->enabledForms['Core Joomla login Component Form'] = array('requestVars' => 'option=com_users&view=login',
																		 'formaction_regExp' => $this->createFormActionRegexp('task=user.login'),//since the form has no id or name use action regexp here
																		 //'no-id-form-ref-field' => 'task=user.login',//'task=search',//
																		 'ignore_condition' => 'task=user.logout',
																		 'tagToPlaceCaptchaBefore' =>'<button type="submit" class="button">',
																		 'verifyOnVars' =>'option=com_users&task=user.login',
																		 'redirectOnfailure' =>JURI::base()."index.php/login" ,//JFactory::getURI()->toString(),
																		 'skipAJAXVerification' => false,
																		 //'onCaptchaFailSetVars' =>'view=users&task='
																		 'isVertical' =>  false,
																		) ;
			}
										 
			
			foreach (glob((dirname(__FILE__).DS.'osolCaptcha'.DS.'nonCoreForms'.DS)."*.php") as $filename) {
				//echo "$filename size " . filesize($filename) . "\n";
				require_once($filename);
			}
			return $this->enabledForms;
		}
		function skipCaptchaVerification($details)
		{
			$skipForm = false;
			if(isset($details['ignore_condition']) && 
						preg_match_all("/([^=&]+)=([^=&]*)/",$details['ignore_condition'],$ignore_condition_matches)
						)
				{
					
					foreach($ignore_condition_matches[1] as $reqVarIndex=>$reqVar)
					{
						if(JRequest::getVar($reqVar) == $ignore_condition_matches[2][$reqVarIndex])
						{
							$skipForm = true;
							break;
						}
					}
				}
					return $skipForm;
				
		}
		function getNonRefererableForm($details)
		{
			
			$formRegExp = '<form[^>]+>.+</form>';
			$returnVar = array();
			
			$body = JResponse::getBody();
			if(preg_match_all('@'.$formRegExp.'@isU', $body, $match_forms))
			{
				foreach($match_forms[0] as $match_formIndex => $match_form)
				{
							if(isset($details['formaction_regExp']))
							{								
								$formActionRegExp =  "<form[^>]+action=\"([^\"]+)\"[^>]*>";
								//echo $match_form.htmlspecialchars($formActionRegExp)."<br />";
								if(preg_match("@".$formActionRegExp."@",$match_form,$match4) &&
											  preg_match("@".$details['formaction_regExp']."@",$match4[1],$match5)
											  )
								{
									
									$returnVar['js_form_access'] = array("action" => $match4[1]);
									return $returnVar;
								}
							}
							elseif(isset($details['no-id-form-ref-field']))
							{
					
								$formActionRegExp =  "<form[^>]+action=\"([^\"]+)\"[^>]*>";
								if(preg_match("@".$formActionRegExp."@",$match_form,$match4) &&
											  preg_match("@".$details['no-id-form-ref-field']."@",$match4[1],$match5)
											  )
								{
									//echo "@".$details['no-id-form-ref-field']."@";
									//echo htmlspecialchars($match4[1]).$match_form;
									$returnVar['js_form_access'] = array("action" => $match4[1]);
									return $returnVar;
								}
							}
					if(isset($details['no-id-form-ref-field']) && 
							preg_match_all("/([^=&]+)=([^=&]*)/",$details['no-id-form-ref-field'],$ignore_condition_matches)
							)
					{
						
						//echo $match_form;
						//echo "<pre>".print_r($ignore_condition_matches,true)."</pre>";
						foreach($ignore_condition_matches[1] as $reqVarIndex=>$reqVar)
						{
							//$regExp = "/name=\"$reqVar\"(.+)value=\"".$requestVar_matches[2][$reqVarIndex]."\"/";
							//<input type="hidden" name="task" value="user.logout" />
							//<input type="hidden" name="task" value="search" />
							$regExp1 = "<input[^/>]+name=\"$reqVar\"[^/>]+/>";
							$regExp2 = "<input[^/>]+value=\"".$ignore_condition_matches[2][$reqVarIndex]."\"[^/>]+/>";
							
							//echo $match_form."<br />";preg_match("@".$regExp2."@",$match_form,$match1);
							//echo "<pre>"."$regExp1<br />$regExp2<br />".print_r($match1,true)."</pre>";
							if(preg_match("@".$regExp1."@",$match_form,$match1) &&
										  preg_match("@".$regExp2."@",$match1[0],$match2))
							{
								//echo htmlspecialchars($match1[0]);
								/*$submitFieldRegExp =  "<[^/>]+type=\"submit\"[^>]+>";
								if(preg_match("@".$submitFieldRegExp."@",$match_form,$match3))
								{
									//echo htmlspecialchars($match3[0]);
									$returnVar['buttonfield'] = $match3[0];
								}*/
								$formActionRegExp =  "<form[^>]+action=\"([^\"]+)\"[^>]+>";
								if(preg_match("@".$formActionRegExp."@",$match_form,$match4))
								{
									//echo htmlspecialchars($match4[1]).$match_form;
									$returnVar['js_form_access'] = array("action" => $match4[1]);
								}
								
								return  $returnVar;
								//break;
							}
						}
					}//if(isset($details['no-id-form-ref-field'])
				}//foreach($match_forms as $match_form)
			}//if(preg_match_all('@'.$formRegExp.'@isU', $body, $match_forms))
					//return $skipForm;
				return false;
		}
		function skipCaptchaInsertion($details,$formHTML)
		{
			//echo $formHTML;
			//echo $details['ignore_condition'];
			$skipForm = false;
			if(isset($details['ignore_condition']) && 
						preg_match_all("/([^=&]+)=([^=&]*)/",$details['ignore_condition'],$ignore_condition_matches)
						)
				{
					
					foreach($ignore_condition_matches[1] as $reqVarIndex=>$reqVar)
					{
						//$regExp = "/name=\"$reqVar\"(.+)value=\"".$requestVar_matches[2][$reqVarIndex]."\"/";
						//<input type="hidden" name="task" value="user.logout" />
						$regExp1 = "<input[^/>]+name=\"$reqVar\"[^/>]+/>";
						$regExp2 = "<input[^/>]+value=\"".$ignore_condition_matches[2][$reqVarIndex]."\"[^/>]+/>";
						
						//preg_match("@".$regExp2."@",$formHTML,$match1);
						//echo "<pre>"."$regExp1<br />$regExp2<br />".print_r($match1,true)."</pre>";
						if(preg_match("@".$regExp1."@",$formHTML,$match1) &&
									  preg_match("@".$regExp2."@",$match1[0],$match2))
						{
							//echo $match1[0];
							$skipForm = true;
							break;
						}
					}
				}
					return $skipForm;
				
		}
		function shouldCheckForOSOLCaptcha()
		{
			/*$currentSession =  & JFactory::getSession() ;
			$currentSession->set('osolCaptchaIntance'.$GLOBALS['totalCaptchas'], $addToForm);*/
			$enabledForms = $this->enabledForms;//$this->getEnabledForms();
			
			$enableOnForm = true;
			//die( JFactory::getSession()->get('securiy_code'.(JRequest::getVar('instanceNo')+0)));
			foreach($enabledForms as $enabledForm => $details)
			
			{
				$enableOnForm = true;
				//'ignore_condition' => 'task=logout',
				
					if($this->skipCaptchaVerification($details)) break;
				
				if(preg_match_all("/([^=&]+)=([^=&]+)/",$details['verifyOnVars'],$requestVar_matches))
				{
					
					if(JRequest::getVar('option') != $requestVar_matches[2][0])
					{
						//echo $requestVar_matches[2][0]."<br />";
						continue;
					}
					//echo "<pre>".print_r($requestVar_matches,true)."</pre>";
					foreach($requestVar_matches[1] as $requestVarIndex => $requestVar)
					{
						$requestVarVals = preg_split("/\|/",$requestVar_matches[2][$requestVarIndex]);
						//echo $requestVar."<pre>".print_r($requestVarVals,true)."</pre>";
						if(!in_array(JRequest::getVar($requestVar),$requestVarVals))
						{
							$enableOnForm = false;
							//echo "shouldInsertOSOLCaptcha() for $enabledForm $requestVar = ".JRequest::getVar($requestVar)."<br />";
							break;
						}
						else
						{
							//echo "shouldInsertOSOLCaptcha() for $enabledForm <br />";
						}
						
						
					}
					if($enableOnForm)
					{
						//osolCatchaTxtInst
						//echo JRequest::getVar('osolCatchaTxt','')." : ".JRequest::getVar('osolCatchaTxtInst');
						//die( "<pre>".print_r($_REQUEST,true)."</pre>");
						if(JRequest::getVar('osolCatchaTxt','')=='' || (JRequest::getVar('osolCatchaTxtInst','') == ''))
						//if($_REQUEST['osolCatchaTxt'] == '' || ($_REQUEST['osolCatchaTxtInst'] == ''))
						{
							die("You haven't submitted captcha for  $enabledForm ");
						}
						else
						{
							//die('ddddd');
							//die("<pre>".print_r($_REQUEST,true)."</pre>");
							 if($this->confirm( JRequest::getVar('osolCatchaTxt',''),JRequest::getVar('osolCatchaTxtInst','') ))
						   {
							   //die('true');
						   }
						   else
						   {
							   JError::raiseWarning("666",JTEXT::_('OSOLCAPTCHA_ERROR_MESSAGE'));
							   if(isset($details['redirectOnfailure']))
							   {
								   $this->redirect($details['redirectOnfailure']);
								   exit;
							   }
							   //if(JRequest::getVar('task') == 'register_save')
								
									//JRequest::setVar('view','registration');
									//JRequest::setVar('task','');
									
									elseif(preg_match_all("/([^=&]+)=([^=&]*)/",$details['onCaptchaFailSetVars'],$onCaptchaFailSetVars_matches))
									{
										
										foreach($onCaptchaFailSetVars_matches[1] as $onCaptchaFailSetVarsIndex => $onCaptchaFailSetVar)
										{
											$onCaptchaFailSetVarVal = $onCaptchaFailSetVars_matches[2][$onCaptchaFailSetVarsIndex];//preg_split("/\|/",);
											JRequest::setVar($onCaptchaFailSetVar,$onCaptchaFailSetVarVal);
										}
										//die( "<pre>".print_r($onCaptchaFailSetVars_matches,true)."</pre>");
									}
								
						   }
						}
					}
					//JRequest::getVar('Itemid')
				}
			}
			
			//$addToForm =   & JFactory::getSession()->get('osolCaptchaIntance'.(JRequest::getVar('instanceNo')+0));
			//die($addToForm );
			$details = $enabledForms[$enabledForm];
		}
		function shouldInsertOSOLCaptcha()
		{
			$enabledForms = $this->enabledForms;//$this->getEnabledForms();
			
			$enableOnForm = true;
			
			foreach($enabledForms as $enabledForm => $details)
			{
				//echo $enabledForm ." : <br />";
				$enableOnForm = true;
				if($details['requestVars'] == '*')
				{
				
					$this->addToForms[] = $enabledForm;//add form for wild card,usually module forms
					continue;
				}

				else
				{
					if(is_array($details['requestVars']))
					{
						
						foreach($details['requestVars'] as $requestVar)
						{
							$continue = false;
							if( preg_match_all("/([^=&]+)=([^=&]+)/",$requestVar,$requestVar_matches)
																			  &&
																			 (JRequest::getVar('option') != $requestVar_matches[2][0]) 
																			  )
							{
								//echo JRequest::getVar('option')." != ".$requestVar_matches[2][0]."<br />";
								$continue = true;
								break;
							}
						}
						if($continue) continue;
					}
					
					elseif( preg_match_all("/([^=&]+)=([^=&]+)/",$details['requestVars'],$requestVar_matches)
																			  &&
																			 (JRequest::getVar('option') != $requestVar_matches[2][0])
																			 )
					{
						
						//echo( JRequest::getVar('option') ." != ". $requestVar_matches[2][0]."<br />") ;
							continue;
						
						
					}
					//echo "<pre>".print_r($requestVar_matches,true)."</pre>";
					$enableOnForm = $this->confirmInsertCaptcha($details);
					
					if($enableOnForm)
					{
						//echo "ADDING Captcha fro $enabledForm<br />";
						$this->addToForms[] = $enabledForm;
					}
					//JRequest::getVar('Itemid')
				}
			}
			
			
		}
		function confirmInsertCaptcha($details)
		{
			$enableOnForm = false;
			$reqVars = array();
			if(!is_array($details['requestVars']))
			{
				$reqVars[] = $details['requestVars'];
			}
			else
			{
				$reqVars = $details['requestVars'];
			}
			foreach($reqVars as $requestVarRegexp)
			{
				 //echo $requestVarRegexp."<br />";
			 	 preg_match_all("/([^=&]+)=([^=&]+)/",$requestVarRegexp,$requestVar_matches);
				 /*if($requestVarRegexp == 'option=com_users&view=login')
				 {
					 echo "<pre>".print_r($requestVar_matches,true)."</pre><br />";
				 }*/
					foreach($requestVar_matches[1] as $requestVarIndex => $requestVar)
					{
						$requestVarVals = preg_split("/\|/",$requestVar_matches[2][$requestVarIndex]);
						/*if($requestVarRegexp == 'option=com_users&view=login')
						{
							echo $requestVar." : ".JRequest::getVar($requestVar)."<br />";
							
							echo "<pre>".print_r($requestVarVals,true)."</pre><br />";
						}*/
						$enableOnForm = false;
						if(!in_array(trim(JRequest::getVar($requestVar)),$requestVarVals))
						{
							//echo "shouldnot InsertOSOLCaptcha $requestVar = ".JRequest::getVar($requestVar)."<br />";//
							//<pre>".print_r($requestVarVals,true)."</pre><br />";//
							//<pre>".print_r($_REQUEST,true)."</pre><br />";
							/*if($requestVarRegexp == 'option=com_users&view=login')
							{
								echo "shouldnot InsertOSOLCaptcha $requestVar = ".JRequest::getVar($requestVar)."<br />";
							}*/
							$enableOnForm = false;
							break;
						}
						else
						{
							$enableOnForm = true;
							//echo "shouldnot InsertOSOLCaptcha $requestVar = ".JRequest::getVar($requestVar)."<br />";
							//echo "shouldInsertOSOLCaptcha()<br />";
						}
						
						
					}
					if($enableOnForm) break;
			}
			//if($enableOnForm)echo "<pre>".print_r($requestVar_matches,true)."</pre><br />";
			return $enableOnForm;
		}
		function getIsEnabledForForms()
		{
			
									
			$plugin 	=& JPluginHelper::getPlugin('system', 'osolcaptcha');
			//$this->params   	= new JParameter($plugin->params);
			$enabledForms = array(
									"enableForContactUs" , 
									"enableForComLogin", 
									"enableForRegistration",
									"enableForReset",
									"enableForRemind");
			$isEnabledForForm = array();
			foreach($enabledForms as $paramName )
			{
				//echo $paramName." = ".$this->params->get($paramName)."<br />";
				$isEnabledForForm[$paramName] = ($this->params->get($paramName) == 'Yes');
			}
			return $isEnabledForForm;
		}
        /**
         * Do something onAfterDispatch 
         */
       // function onAfterDispatch()
	   public function onAfterRender()
        {
			$enabledForms = $this->enabledForms;//$this->getEnabledForms();
			$body = JResponse::getBody();
			
			//echo "shouldInsertOSOLCaptcha()<pre>".print_r($addToForms,true)."</pre>";
			foreach($this->addToForms as $addToForm)
			{
				//echo $addToForm."<br />";
				
				if(isset($enabledForms[$addToForm]['formId']))
				{
					$formId = $enabledForms[$addToForm]['formId'];
					$formRegExp = '<form[^>]+id="'.$formId.'".+</form>';
					$ajaxCheckFor = array('id' => $formId);
				}
				elseif(isset($enabledForms[$addToForm]['formName']))
				{
					$formId = $enabledForms[$addToForm]['formName'];
					$formRegExp = '<form[^>]+name="'.$formId.'".+</form>';
					$ajaxCheckFor = array('name' => $formId);
				}
				elseif(isset($enabledForms[$addToForm]['formaction_regExp']))
				{
					
					$returnVar = $this->getNonRefererableForm($enabledForms[$addToForm]);
					$formRegExp = '<form[^>]+action="[^\"]+'. $enabledForms[$addToForm]['formaction_regExp'].'.+".+</form>';
					$ajaxCheckFor = $returnVar['js_form_access'];//array('name' => $formId);
					//echo htmlspecialchars($formRegExp);
				}
				elseif(isset($enabledForms[$addToForm]['no-id-form-ref-field']))
				{
					/*$returnVar = array(
							   	'buttonfield' => '<button',//for inserting via php
								'js_form_access' => array("action"=>''),//for inserting  ajax check
							  );*/
					$returnVar = $this->getNonRefererableForm($enabledForms[$addToForm]);
					$formId = $enabledForms[$addToForm]['formName'];
					$formRegExp = '<form[^>]+action="'. preg_quote($returnVar['js_form_access']['action']).'".+</form>';
					$ajaxCheckFor = $returnVar['js_form_access'];//array('name' => $formId);
					//echo "<pre>".print_r($ajaxCheckFor,true)."</pre>";
					//$this->ajaxChecksFor[] = $ajaxCheckFor;
					//continue;
				}

				
				//echo "? creating problem onAfterRender()".htmlspecialchars($formRegExp) ;preg_match('@'.$formRegExp.'@isU', $body, $match_form); echo "<pre>".print_r($match_form,true)."</pre>";
				//echo htmlspecialchars($enabledForms[$addToForm]['formRegExp'])."<br />";
				if(preg_match('@'.$formRegExp.'@isU', $body, $match_form) &&
							  !$this->skipCaptchaInsertion($enabledForms[$addToForm],$match_form[0])
						)
				{
					//echo "<pre>".print_r($match_form,true)."</pre>";
					//echo "onAfterRender()".$match_form[0]."</br />";
					$isVerticalLayout = (isset($enabledForms[$addToForm]['isVertical']) && $enabledForms[$addToForm]['isVertical']);
					$captchaHTML = $this->GetCapthcaHTML($isVerticalLayout);
					$checkContent = $enabledForms[$addToForm]['tagToPlaceCaptchaBefore'];
					//echo htmlspecialchars( $checkContent);
					$enhancedForm = str_replace($checkContent,$captchaHTML.$checkContent,$match_form[0]);
					$body = str_replace($match_form[0],$enhancedForm,$body);
					$currentSession =  & JFactory::getSession() ;
					$currentSession->set('osolCaptchaIntance'.$GLOBALS['totalCaptchas'], $addToForm);
					//$this->addAJAXCheck($ajaxCheckFor);
					//echo "<pre>".print_r($ajaxCheckFor,true)."</pre>";
					if(!$enabledForms[$addToForm]['skipAJAXVerification'])
					{
						$this->ajaxChecksFor[] = $ajaxCheckFor;
					}
					
				}
				/*if(empty($match_form))
					{
						JError::raiseWarning(100, JText::_('Form not found'));
					}*/
				
			}
			$ajax = "<script type=\"text/javascript\">";
				foreach($this->ajaxChecksFor as $index => $ajaxCheckFor)
			   {
				  $ajax .= $this->addAJAXCheck($ajaxCheckFor,$index);
			   }
			$ajax .="</script>";
			 $body = str_replace("</head>",$ajax."\r\n</head>",$body);//$document->addScriptDeclaration($ajax); doesnt work in onAfterRender
			 // Set body
            JResponse::setBody($body);
			//echo $body;
			 
			return;
			
			
			
        }
		
		/*
			Usage
			<?php 
				//set the argument below to true if you need to show vertically( 3 cells one below the other)
				JFactory::getApplication()->triggerEvent('onShowOSOLCaptcha', array(false)); 
			?>
			*/
		function onShowOSOLCaptcha($isVertical)
		{
			
			echo $this->GetCapthcaHTML($isVertical);
		}
		
		function HexToRGB($hex) {
			$hex = preg_replace("/#/", "", $hex);
			$color = array();
			
			if(strlen($hex) == 3) {
				$color['r'] = hexdec(substr($hex, 0, 1) . $r);
				$color['g'] = hexdec(substr($hex, 1, 1) . $g);
				$color['b'] = hexdec(substr($hex, 2, 1) . $b);
			}
			else if(strlen($hex) == 6) {
				$color['r'] = hexdec(substr($hex, 0, 2));
				$color['g'] = hexdec(substr($hex, 2, 2));
				$color['b'] = hexdec(substr($hex, 4, 2));
			}
			
			return array_values($color);
		}
	
		function RGBToHex($r, $g, $b) {
			$hex = "#";
			$hex.= dechex($r);
			$hex.= dechex($g);
			$hex.= dechex($b);
			
			return $hex;
		}
	function botscoutCheck()
	{
		/////////////////////////////////////////////////////
		// sample API code for use with the BotScout.com API
		// code by MrMike / version 2.0 / LDM 2-2009 
		/////////////////////////////////////////////////////
		
		/////////////////// START CONFIGURATION ////////////////////////
		// use diagnostic output? ('1' to use, '0' to suppress)
		// (normally set to '0')
		global $mainframe;
		$currentSession =  & JFactory::getSession() ;//&JSession::getInstance('none',array()); 
		$botscoutCheckdone = $currentSession->get('botscoutCheckdone');
		$plugin 	=& JPluginHelper::getPlugin('system', 'osolcaptcha');
		//$this->params   	= new JParameter($plugin->params);
		$this->botScoutProtection  = $this->params->get('botScoutProtection',$this->botScoutProtection);
		$this->botscoutAPIKey  = $this->params->get('botscoutAPIKey',$this->botscoutAPIKey);
		$this->redirectURLforSuspectedIPs  = $this->params->get('redirectURLforSuspectedIPs',$this->redirectURLforSuspectedIPs);
		$this->reportBotscoutNegativeMail  = $this->params->get('reportBotscoutNegativeMail',$this->reportBotscoutNegativeMail);
		
		if((!isset($_REQUEST['email'])) || $this->botScoutProtection == 'Disable' || $this->botscoutAPIKey == '')
		{
			return;
		}
		
		$diag = '0';
		
		/////////////////// END CONFIGURATION ////////////////////////
		
		
		////////////////////////
		// test values 
		// an email value...a bot, perhaps?
		// these would normally come from your 
		// web form or registration form code 
			
		$XMAIL = $_REQUEST['email'];
		
		// an IP address
		$XIP = $_SERVER['REMOTE_ADDR'];
		
		// a name, maybe a bot?
		$XNAME = '';
		
		////////////////////////
		// your optional API key (don't have one? get one here: http://botscout.com/
		$APIKEY=$this->botscoutAPIKey;
		
		$USEXML = 0;
		
		////////////////////////
		
		// sample query strings - you'd dynamically construct this 
		// string and use it as in the example below - these examples use the optional API 'key' field 
		// for more information on using the API key, please visit http://botscout.com
		
		// in most cases the BEST test is to use the "MULTI" query and test for the IP and email
		//$multi_test = "http://botscout.com/test/?multi&mail=$XMAIL&ip=$XIP&key=$APIKEY";
		
		/* you can use these but they're much less efficient and (possibly) not as reliable
		$test_string = "http://botscout.com/test/?mail=$XMAIL&key=$APIKEY";	// test email - reliable
		$test_string = "http://botscout.com/test/?ip=$XIP&key=$APIKEY";		// test IP - reliable
		$test_string = "http://botscout.com/test/?name=$XNAME&key=$APIKEY";	// test name (unreliable!)
		$test_string = "http://botscout.com/test/?all=$XNAME&key=$APIKEY";	// test all (see docs)
		*/
		
		// make the url compliant with urlencode()
		$XMAIL = urlencode($XMAIL);
		
		// for this example we'll use the MULTI test 
		$test_string = "http://botscout.com/test/?multi&mail=$XMAIL&ip=$XIP";
		
		// are using an API key? If so, append it.
		if($APIKEY != ''){
			$test_string = "$test_string&key=$APIKEY";
		}
		
		// are using XML responses? If so, append the XML format key.
		if($USEXML == '1'){
			$test_string = "$test_string&format=xml";
		}
		
		////////////////////////
		if($diag=='1'){print "Test String: $test_string";}
		////////////////////////
		
		
		////////////////////////
		// use file_get_contents() or cURL? 
		// we'll user file_get_contents() unless it's not available 
		
		if(function_exists('file_get_contents')&& (ini_get('allow_url_fopen')=='On')){
			// Use file_get_contents
			$data = file_get_contents($test_string);
		}else{
			$ch = curl_init($test_string);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$returned_data = curl_exec($ch);
			curl_close($ch);
		}
		
		// diagnostic output 
		if($diag=='1'){
			print "RETURNED DATA: $returned_data";
			// sanity check 
			if($returned_data==''){ print 'Error: No return data from API query.'; exit; } 
		} 
		
		
		// take the returned value and parse it (standard API, not XML)
		$botdata = explode('|', $returned_data); 
		
		// sample 'MULTI' return string 
		// Y|MULTI|IP|4|MAIL|26|NAME|30
		
		// $botdata[0] - 'Y' if found in database, 'N' if not found, '!' if an error occurred 
		// $botdata[1] - type of test (will be 'MAIL', 'IP', 'NAME', or 'MULTI') 
		// $botdata[2] - descriptor field for item (IP)
		// $botdata[3] - how many times the IP was found in the database 
		// $botdata[4] - descriptor field for item (MAIL)
		// $botdata[5] - how many times the EMAIL was found in the database 
		// $botdata[6] - descriptor field for item (NAME)
		// $botdata[7] - how many times the NAME was found in the database 
		//$mainframe->redirect($this->redirectURLforSuspectedIPs);
		if($botdata[0] == 'Y'){
			
			//$this->botScoutProtection  = $this->params->get('botScoutProtection',$this->botScoutProtection);//Disable,Redirect,Stop
			//$this->redirectURLforSuspectedIPs  = $this->params->get('redirectURLforSuspectedIPs',$this->redirectURLforSuspectedIPs);
			if($this->reportBotscoutNegativeMail  !='')
			{
				$this->mailBotScoutResult();
			}
			if($this->botScoutProtection == 'Redirect')
			{
				$mainframe->redirect($this->redirectURLforSuspectedIPs);
				
			}
			else //Stop
			{
			}
			
			exit;
			
		}
		$currentSession->set('botscoutCheckdone', 1);
		if(($diag=='1') && substr($returned_data, 0,1) == '!'){
			// if the first character is an exclamation mark, an error has occurred  
			print "Error: $returned_data";
			exit;
		}
		
		
		// this example tests the email address and IP to see if either of them appear 
		// in the database at all. Either one is a fairly good indicator of bot identity. 
		if($botdata[3] > 0 || $botdata[5] > 0){ 
			if($diag=='1')print $data; 
		
			if($diag=='1'){ 
				print "Bot signature found."; 
				print "Type of test was: $botdata[1]"; 
				print "The {$botdata[2]} was found {$botdata[3]} times, the {$botdata[4]} was found {$botdata[5]} times"; 
			} 
		
			// your 'rejection' code would go here.... 
			// for example, print a fake error message and exit the process. 
			$errnum = round(rand(1100, 25000));
			if($diag=='1')print "Confabulation Error #$errnum, Halting.";
			exit;
		
		}
		////////////////////////
	}
	function mailBotScoutResult($isSecondLevel =  false)
	{
				global $mainframe;
				$mailFrom = $mainframe->getCfg('mailfrom');
				$fromName = $mainframe->getCfg('fromname');
				
				$plugin 	=& JPluginHelper::getPlugin('system', 'osolcaptcha');
				
				//$this->params   	= new JParameter($plugin->params);
				
				$this->reportBotscoutNegativeMail  = $this->params->get('reportBotscoutNegativeMail',$this->reportBotscoutNegativeMail);
				//JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null)
				$subject = $mainframe->getCfg('sitename')." : Suspected spam attack from ".$_SERVER['REMOTE_ADDR'];
				$verificationType = $isSecondLevel?"botscout":"second level security";
				$message = "Following request from IP:{$_SERVER['REMOTE_ADDR']} returned a -ve result on $verificationType verification in ".JURI::current()."\r\n Get vars =".var_export($_GET,true)."\r\n POST vars =".var_export($_POST,true)."\r\n REQUEST vars =".var_export($_REQUEST,true);
				//echo $subject."\r\n".$message ;exit;
				JUtility::sendMail($mailFrom,$fromName,$this->reportBotscoutNegativeMail ,$subject  ,$message,$mailFrom);
	}

}
?>