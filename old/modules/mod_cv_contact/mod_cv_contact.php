<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



//Email Parameters

$recipient = $params->get('email_recipient', '');

$fromName = @$params->get('from_name', 'Rapid Contact');

$fromEmail = @$params->get('from_email', 'rapid_contact@yoursite.com');



// Text Parameters

$myEmailLabel = $params->get('email_label', 'Email:');

$mySubjectLabel = $params->get('subject_label', 'Subject:');

$myMessageLabel = $params->get('message_label', 'Message:');

$buttonText = $params->get('button_text', 'Send Message');

$pageText = $params->get('page_text', 'Thank you for your contact.');

$errorText = $params->get('error_text', 'Your message could not be sent. Please try again.');

$noEmail = $params->get('no_email', 'Please write your email');

$invalidEmail = $params->get('invalid_email', 'Please write a valid email');

$wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer');

$pre_text = $params->get('pre_text', '');



// Size and Color Parameters

$thanksTextColor = $params->get('thank_text_color', '#FF0000');

$error_text_color = $params->get('error_text_color', '#FF0000');

$emailWidth = $params->get('email_width', '15');

$subjectWidth = $params->get('subject_width', '15');

$messageWidth = $params->get('message_width', '13');

$buttonWidth = $params->get('button_width', '100');

$label_pos = $params->get('label_pos', '0');

$addcss = $params->get('addcss', 'div.rapid_contact tr, div.rapid_contact td { border: none; padding: 3px; }');



// URL Parameters

$exact_url = $params->get('exact_url', true);

$disable_https = $params->get('disable_https', true);

$fixed_url = $params->get('fixed_url', true);

$myFixedURL = $params->get('fixed_url_address', '');



// Anti-spam Parameters

$enable_anti_spam = $params->get('enable_anti_spam', true);
$myAntiSpamText = $params->get('anti_spam_t', 'Введите контрольное число:');

$myAntiSpamQuestion = $params->get('anti_spam_q', 'How many eyes has a typical person?');

$myAntiSpamAnswer = $params->get('anti_spam_a', '2');

$anti_spam_position = $params->get('anti_spam_position', 0);



// Module Class Suffix Parameter

$mod_class_suffix = $params->get('moduleclass_sfx', '');





if ($fixed_url) {

  $url = $myFixedURL;

}

else {

  if (!$exact_url) {

    $url = JURI::current();

  }

  else {

    if (!$disable_https) {

      $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    }

    else {

      $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    }

  }

}



$url = htmlentities($url, ENT_COMPAT, "UTF-8");



$myError = '';

$CORRECT_ANTISPAM_ANSWER = '';

$CORRECT_EMAIL = '';

$CORRECT_SUBJECT = '';

$CORRECT_MESSAGE = '';



if (isset($_POST["rp_email"])) {

  $SUBJECT = htmlentities($_POST["rp_subject"], ENT_COMPAT, "UTF-8");

  $MESSAGE = htmlentities($_POST["rp_message"], ENT_COMPAT, "UTF-8");
  
  $SPEC = htmlentities($_POST["rp_spec"], ENT_COMPAT, "UTF-8");

  $BIRTHDATE = htmlentities($_POST["rp_birthdate"], ENT_COMPAT, "UTF-8");

  $STATUS = htmlentities($_POST["rp_status"], ENT_COMPAT, "UTF-8");

  $PHONE = htmlentities($_POST["rp_phone"], ENT_COMPAT, "UTF-8");

  $EDU = htmlentities($_POST["rp_education_add"], ENT_COMPAT, "UTF-8");

  $EXP = htmlentities($_POST["rp_exp"], ENT_COMPAT, "UTF-8");

  $SKILLS = htmlentities($_POST["rp_skills"], ENT_COMPAT, "UTF-8");

  $SALARY = htmlentities($_POST["rp_salary"], ENT_COMPAT, "UTF-8");

  // check anti-spam

  if ($enable_anti_spam) {

    if ($_POST["rp_anti_spam_answer"] != $myAntiSpamAnswer) {

      $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';

    }

    else {

      $CORRECT_ANTISPAM_ANSWER = htmlentities($_POST["rp_anti_spam_answer"], ENT_COMPAT, "UTF-8");

    }

  }

  // check CAPTCHA
$options['name'] = 'captcha';

$app =& JFactory::getApplication('site',$options);
$app->initialise();

  $session =& JFactory::getSession();

  if($_POST['captcha'] != $session->get('digit')) $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';

  // check email

  if ($_POST["rp_email"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Пожалуйста, введите Ваши контактные данные</span>';

  }
  if ($_POST["rp_subject"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Пожалуйста, введите Ваше имя</span>';

  }
  if ($_POST["rp_spec"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Пожалуйста, введите Вашу специализацию</span>';

  }
/*
  if ($_POST["rp_birthdate"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Не указана дата рождения</span>';

  }
  if ($_POST["rp_phone"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Не указан номер телефона</span>';

  }
  if ($_POST["rp_education_add"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Не указано основное образование</span>';

  }
  if ($_POST["rp_exp"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Не указан опыт работы</span>';

  }
  if ($_POST["rp_email"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">' . $noEmail . '</span>';

  }
*/
/*  if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($_POST["rp_email"]))) {

    $myError = '<span style="color: ' . $error_text_color . ';">' . $invalidEmail . '</span>';

  }

  else { */

    $EMAIL = htmlentities($_POST["rp_email"], ENT_COMPAT, "UTF-8");

//  }



  if ($myError == '') {
	  
    $mySubject = $SUBJECT;
  
  
	$myMessage = "Специализация: ".$SPEC."\n";
	$myMessage .= "ФИО: ".$SUBJECT."\n";
	$myMessage .= "Дата рождения: ".$BIRTHDATE."\n";
	$myMessage .= "Семейное положение: ".$STATUS."\n";
	$myMessage .= "Контактные данные: ".$_POST["rp_email"]."\n";
	$myMessage .= "Образование основное: ".$_POST["rp_message"]."\n";
	$myMessage .= "Образование дополнительное: ".$EDU."\n";
	$myMessage .= "Опыт работы: ".$EXP."\n";
	$myMessage .= "Профессиональные навыки: ".$SKILLS."\n";
	$myMessage .= "Ожидаемый уровень дохода: ".$SALARY."\n";

    $mailSender = &JFactory::getMailer();

    $mailSender->addRecipient($recipient);



    $mailSender->setSender(array($fromEmail,$fromName));

    $mailSender->addReplyTo(array( $_POST["rp_email"], '' ));



    $mailSender->setSubject($mySubject);

    $mailSender->setBody($myMessage);



    if ($mailSender->Send() !== true) {

      $myReplacement = '<span style="color: ' . $error_text_color . ';">' . $errorText . '</span>';

      print $myReplacement;

      return true;

    }

    else {

      $myReplacement = '<div class="contact"><span style="color: '.$thanksTextColor.';">' . $pageText . '</span></div>';

      print $myReplacement;

      return true;

    }



  }

} // end if posted



// check recipient

if ($recipient === "") {

  $myReplacement = '<span style="color: ' . $error_text_color . ';">No recipient specified</span>';

  print $myReplacement;

  return true;

}



print '<style type="text/css"><!--' . $addcss . '--></style>';

print '<div class="contact ' . $mod_class_suffix . '"><form action="' . $url . '" method="post" onsubmit="return checkForm(this);">' . "\n" .

      '<div class="contact intro_text ' . $mod_class_suffix . '">'.$pre_text.'</div>' . "\n";



if ($myError != '') {

  print $myError;

}



$separator = '</li><li>';

$emptycell = '<li></li>';

if ($label_pos == '1') {

  $separator = '<br/>';

  $emptycell = '';

}

print '<h3>Вы можете отправить резюме на заинтересовавшую вас вакансию</h3>';

print '<ul>';

// print subject input

print '<li>ФИО: *'. $separator . '<input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_subject" style="width:' . $subjectWidth . '" value="'.$SUBJECT.'"/></li>' . "\n";

print '<li>Контактные данные: *' . $separator . '<input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_email" style="width:' . $emailWidth . '" value="'.$EMAIL.'"/></li>' . "\n";

print '<li>Специализация: *' . $separator . '<input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_spec" style="width:' . $subjectWidth . '" value="'.$SPEC.'"/></li>' . "\n";

// print email input

print '<li>Дата рождения:' . $separator . '<input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_birthdate" style="width:' . $subjectWidth . '" value="'.$BIRTHDATE.'"/></li>' . "\n";

print '<li>Семейное положение:' . $separator . '<input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_status" style="width:' . $subjectWidth . '" value="'.$STATUS.'"/></li>' . "\n";

// print message input

print '<li>Образование основное:'. $separator . '<textarea class="rapid_contact textarea ' . $mod_class_suffix . '" name="rp_message" style="width:' . $messageWidth . '; max-width:' . $messageWidth . '; min-width:' . $messageWidth . ';" rows="4">'.$MESSAGE.'</textarea></li>' . "\n";

print '<li>Образование дополнительное:'. $separator . '<textarea class="rapid_contact textarea ' . $mod_class_suffix . '" name="rp_education_add" style="width:' . $messageWidth . '; max-width:' . $messageWidth . '; min-width:' . $messageWidth . ';" rows="4">'.$EDU.'</textarea></li>' . "\n";

print '<li>Опыт работы:'. $separator . '<textarea class="rapid_contact textarea ' . $mod_class_suffix . '" name="rp_exp" style="width:' . $messageWidth . '; max-width:' . $messageWidth . '; min-width:' . $messageWidth . ';" rows="4">'.$EXP.'</textarea></li>' . "\n";

print '<li>Профессиональные навыки:'. $separator . '<textarea class="rapid_contact textarea ' . $mod_class_suffix . '" name="rp_skills" style="width:' . $messageWidth . '; max-width:' . $messageWidth . '; min-width:' . $messageWidth . ';" rows="4">'.$SKILLS.'</textarea></li>' . "\n";

print '<li>Ожидаемый уровень дохода:' . $separator . '<input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_salary" style="width:' . $subjectWidth . '" value="'.$SALARY.'"/></li>' . "\n";


//print anti-spam

if ($enable_anti_spam) {

    print '<li>' . $myAntiSpamText . '</li><li>' . $myAntiSpamQuestion . '&nbsp; <input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_anti_spam_answer" style="width:' . $buttonWidth . '" value="'.$CORRECT_ANTISPAM_ANSWER.'"/></li>' . "\n";

}

print '<script type="text/javascript">
		function checkForm(form) {
			if(!form.captcha.value.match(/^\d{3}$/)) {
				alert("Пожалуйста, введите Ваше имя"); form.captcha.focus(); return false;
			} 
			if(!form.rp_subject) {
				alert("'.$wrongantispamanswer.'"); form.rp_subject.focus(); return false;
			} 
			if(!form.rp_email) {
				alert("Пожалуйста, введите Ваши контактные"); form.rp_email.focus(); return false;
			} 
			return true;
		} </script>';

/*


			if(!form.rp_exp) {
				alert("Пожалуйста, укажите Ваш опыт работы"); form.rp_exp.focus(); return false;
			} 
			if(!form.rp_message) {
				alert("Пожалуйста, укажите Ваше образование") form.rp_message.focus(); return false;
			} 
			if(!form.rp_birhdate) {
				alert("Пожалуйста, введите дату Вашего рождения"); form.rp_birhdate.focus(); return false;
			} 

*/
include 'libraries/captcha.php';

print '<li>' . $myAntiSpamText . '*</li><li><input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" style="width:50px" maxlength="3" name="captcha" value="" />&nbsp;<img src="/libraries/captcha.png" width="120" height="30" border="1" alt="CAPTCHA" style="margin-bottom: 6px" /></li>';

print "<script>
var RecaptchaOptions = {
   lang : 'ru',
   theme : 'clean',
   tabindex : 2
};
</script>";

// print button

print '<li><input class="btn btn-primary rapid_contact button ' . $mod_class_suffix . '" type="submit" value="' . $buttonText . '" style="width: ' . $buttonWidth . '%"/></li></ul></form></div>' . "\n";

return true;

