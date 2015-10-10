<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



//Email Parameters

$recipient = $params->get('email_recipient', 'pol3@ckbran.ru');

$fromName = @$params->get('from_name', 'ЦКБ РАН');

$fromEmail = @$params->get('from_email', 'book@ckbranmail.ru');



// Text Parameters

$myEmailLabel = $params->get('email_label', 'Email:');

$mySubjectLabel = $params->get('subject_label', 'Subject:');

$myMessageLabel = $params->get('message_label', 'Message:');

$buttonText = $params->get('button_text', 'Send Message');

$pageText = $params->get('page_text', 'Спасибо за Ваше обращение. Наши консультанты свяжутся с Вами в ближайшее время.');

$errorText = $params->get('error_text', 'Ваша заявка не может быть отправлена. Пожалуйста, попробуйте позднее. ');

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

$myAntiSpamText = $params->get('anti_spam_t', 'Введите контрольное число, которое вы видите на картинке:');

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

$CORRECT_PHONE = '';

$CORRECT_SUBJECT = '';

$CORRECT_MESSAGE = '';

print '<style type="text/css">
		#ask_button, #book_button {
			float: right;
			color: #fff;
			background: #5577af url(/templates/protostar/images/question.png) 4px 50% no-repeat;
			margin: 0;
			padding: 6px 16px 8px 40px;
			border-right: 1px solid #fff;
			font-family: "PT Sans Narrow";
			font-size: 16px;
			font-weight: 700;
			text-transform: uppercase;
			text-decoration: none;
			text-align: right;
		}
		
		#book_button {
			background: #3a4a67  url(/templates/protostar/images/book.png) 4px 50% no-repeat;
		}
		#book_button:hover, #ask_button:hover {
			background-color: #2F6497;
		}
		#book_form, #book_message {
			width: 50%;
			max-width: 480px;
			padding: 12px 24px;
			background: #fff;
			position: absolute;
			top: 50px;
			left: 25%;
			margin: 0 auto;
			z-index: 999;
			box-shadow: 0 0 8px #404040;
			border-radius: 8px;
		}
		#book_form, #book_bg {
			display: none;	
		}
		#book_close {
			float: right;	
			margin-top: -16px;
			-moz-opacity: 0.25;	
			opacity: .25;
			filter: alpha(opacity=25);
		}
		#book_message #book_close {
			margin: 0;	
		}
		#book_form h2 {
			font-size: 24px;
			margin: 16px 0 8px;
			color: #2F6497;
		}
		#book_form table {
			padding: 12px;
		}
		#book_form input, #book_form select, #book_form textarea {
			margin: 0;
			background: #f5f5f5;
			border: 1px solid #ebebeb;
		}
		#book_form .button {
			background: #2F6497;
		}
		#book_bg {
			background: #000;
			position: fixed;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			-moz-opacity: 0.5;	
			opacity: .50;
			filter: alpha(opacity=50);
			z-index: 99;
		}

		</style>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
		
		';

if (isset($_POST["rp_send"])) {

  $CORRECT_NAME = htmlentities($_POST["rp_name"], ENT_COMPAT, "UTF-8");
  $CORRECT_LAST_NAME = htmlentities($_POST["rp_last_name"], ENT_COMPAT, "UTF-8");
  $CORRECT_FATHERS_NAME = htmlentities($_POST["rp_fathers_name"], ENT_COMPAT, "UTF-8");

print '<style type="text/css">#book_form, #book_bg { display: block; }</style>';

  $CORRECT_BOOK = htmlentities($_POST["rp_book"], ENT_COMPAT, "UTF-8");

  // check CAPTCHA
$options['name'] = 'captcha';

$app =& JFactory::getApplication('site',$options);
$app->initialise();

  $session =& JFactory::getSession();

  if($_POST['captcha'] != $session->get('digit-book')) $myError = '<span style="color: ' . $error_text_color . ';">'.$wrongantispamanswer . '</span>';

  // check email

  if ($_POST["rp_phone"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Пожалуйста, укажите Ваш номер телефона</span>';

  }
  
  if ($_POST["rp_name"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Пожалуйста, укажите Ваше имя</span>';

  }
  
  if ($_POST["rp_book"] === "") {

    $myError = '<span style="color: ' . $error_text_color . ';">Пожалуйста, укажите врача или исследование</span>';

  }

/*  if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($_POST["rp_phone"]))) {

    $myError = '<span style="color: ' . $error_text_color . ';">' . $invalidEmail . '</span>';

  }

  else { */

    $CORRECT_PHONE = htmlentities($_POST["rp_phone"], ENT_COMPAT, "UTF-8");
	
    $CORRECT_DATE = htmlentities($_POST["rp_date"], ENT_COMPAT, "UTF-8");
    $CORRECT_TIME_HR = htmlentities($_POST["rp_time_hr"], ENT_COMPAT, "UTF-8");
    $CORRECT_TIME_MIN = htmlentities($_POST["rp_time_min"], ENT_COMPAT, "UTF-8");
	
    $CORRECT_FIRST_TIME = htmlentities($_POST["rp_first_time"], ENT_COMPAT, "UTF-8");

//  }


  if ($myError == '') {

    $mySubject = "Запись на приём";

    $myMessage = "Пациент: " . $_POST["rp_name"]. "\nТел.: " . $_POST["rp_phone"] ."\nЗапись: ". $_POST["rp_book"]."\nКомментарий:".$_POST["rp_comment"]."\n Удобные дата и время: ". $_POST["rp_date"] ." в ". $_POST["rp_time_hr"] .':'. $_POST["rp_time_min"] . "\nПовторно:". $_POST["rp_first_time"];



    $mailSender = &JFactory::getMailer();

    $mailSender->addRecipient($recipient);



    $mailSender->setSender(array($fromEmail,$fromName));

    $mailSender->addReplyTo(array( $_POST["rp_phone"], '' ));



    $mailSender->setSubject($mySubject);

    $mailSender->setBody($myMessage);



    if ($mailSender->Send() !== true) {

      $myReplacement = '<span style="color: ' . $error_text_color . ';">' . $errorText . '</span>';

      print $myReplacement;

      return true;

    }

    else {

      $myReplacement = '<div id="book_message"><a id="book_close" href="#" onclick="location.href=window.location.pathname;"><img src="/images/close.png" alt="" /></a><span style="color: '.$thanksTextColor.';">' . $pageText . '</span></div><div id="book_bg" style="display:block" onclick="document.getElementById(\'book_message\').style.display = \'none\';document.getElementById(\'book_bg\').style.display = \'none\';"></div>';

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

print '<a id="book_button" href="#" onclick="document.getElementById(\'book_form\').style.display = \'block\';document.getElementById(\'book_bg\').style.display = \'block\';">Записаться на приём</a>';

print '<a id="ask_button" href="/forum?view=tickets">Задать вопрос</a>';

print '<div id="book_bg" onclick="document.getElementById(\'book_form\').style.display = \'none\';document.getElementById(\'book_bg\').style.display = \'none\';"></div><div id="book_form"><form action="' . $url . '" method="post" onsubmit="return checkForm(this);">' . "\n" .
	  '<a id="book_close" href="#" onclick="document.getElementById(\'book_form\').style.display = \'none\';document.getElementById(\'book_bg\').style.display = \'none\';"><img src="/images/close.png" alt="" /></a> '.
	  '<h2>Запись на приём</h2>'.
      '<p class="contact intro_text"><em>Обращаем Ваше внимание, что приведённая ниже форма — предварительная заявка записи на приём. С Вами свяжется сотрудник службы координации пациентов для подтверждения записи.<br />
Служба координации работает:<br />
Понедельник-пятница с 8.00 до 20.00;<br />
Суббота с 9.00 до 17.00.<br />
Заявки, поступившие в воскресенье, обрабатываются в понедельник</em></p>' . "\n";



if ($myError != '') {

  print $myError;

}



$separator = '</li><li>';

$emptycell = '<li></li>';

if ($label_pos == '1') {

  $separator = '<br/>';

  $emptycell = '';

}



print '<table cellpadding="4" cellspacing="0" border="0">';

print '<tr><td><strong>ФИО:*</strong> </td><td><input class="rapid_contact inputbox" type="text" name="rp_name" value="'.$CORRECT_NAME.'"/></td></tr>';

print '<tr><td><strong>Контактный телефон:*</strong> </td><td><input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" name="rp_phone" value="'.$CORRECT_PHONE.'"/></td></tr>';

print '<tr valign="top"><td><strong>Запись к врачу или на исследование:*</strong></td><td>
<select name="rp_book" class="rapid_contact inputbox">
				<option value="Нет данных">Выберите врача</option>
				<option value="Аллерголог">Аллерголог</option>
				<option value="Гастроэнтеролог">Гастроэнтеролог</option>
				<option value="Гинеколог">Гинеколог</option>							
				<option value="Дерматолог">Дерматолог</option>
				<option value="Кардиолог">Кардиолог</option>
				<option value="Невролог">Невролог</option>
				<option value="Онколог">Онколог</option>
				<option value="Оториноларинголог">Оториноларинголог</option>
				<option value="Офтальмолог">Офтальмолог</option>
				<option value="Проктолог">Проктолог</option>
				<option value="Психоневролог">Психоневролог</option>
				<option value="Сердечно-сосудистый хирург (флеболог)">Сердечно-сосудистый хирург (флеболог)</option>
				<option value="Терапевт">Терапевт</option>
				<option value="Травматолог–ортопед">Травматолог–ортопед</option>
				<option value="Уролог">Уролог</option>
				<option value="Хирург">Хирург</option>
				<option value="Эндокринолог">Эндокринолог</option>
				<option value="Рентген">Рентген</option>
				<option value=УЗИ"">УЗИ</option>
				<option value="МРТ">МРТ</option>
				<option value="МСКТ">МСКТ</option>
</select></td></tr>';

print '<tr valign="top"><td><strong>Комментарий:</strong></td><td><textarea class="rapid_contact textarea" name="rp_comment" rows="4">'.$CORRECT_BOOK.'</textarea></td></tr>';

print '<tr><td><strong>Удобная дата:</strong> </td><td><input type="text" name="rp_date" id="datepicker" /></td></tr>';

print '<tr><td><strong>Удобное время:</strong> </td>
<td><select name="rp_time_hr" style="width:64px;"  value="'.$CORRECT_TIME_HR.'">';

print '<option value="">час</option>';
for ($h=8; $h< 20; $h++)
print '<option value="'.$h.'">'.$h.'</option>';

print '</select>
:
<select name="rp_time_min" style="width:64px;" value="'.$CORRECT_TIME_MIN.'">';

	print '<option value="">мин</option>';
for ($m=0; $m< 60; $m++) {
	if ($m < 10) $mm = '0'; else $mm='';
	print '<option value="'.$mm.$m.'">'.$mm.$m.'</option>';
}
print '</select></td></tr>';

print '<tr><td colspan="2" style="text-align:center;"><input type="radio" name="rp_first_time" value="Да" />&nbsp;Уже был в ЦКБ РАН&nbsp;&nbsp;&nbsp;
<input type="radio" name="rp_first_time" value="Нет" checked="checked" />&nbsp;Обращаюсь в первый раз</td></tr>';


print	'<script type="text/javascript">
			function checkForm(form)
			{
				if(!form.captcha.value.match(/^\d{3}$/)) {
					alert("'.$wrongantispamanswer.'");
					form.captcha.focus();
					return false;
				}
				return true;
			}
		</script>';

print '<tr><td>Введите число, которое Вы видите на картинке:* </td><td><input class="rapid_contact inputbox ' . $mod_class_suffix . '" type="text" style="width:50px" maxlength="3" name="captcha" value="" />&nbsp;<img src="libraries/captcha-book.php" width="120" height="30" border="1" alt="CAPTCHA" style="margin-bottom: 6px" /></td></tr>';

print	'<script type="text/javascript">
			function checkForm(form)
			{
				if(!form.captcha.value.match(/^\d{3}$/)) {
					alert("'.$wrongantispamanswer.'");
					form.captcha.focus();
					return false;
				}
				if(form.rp_book.value == "") {
					alert("Пожалуйста, введите Ваш отзыв");
					form.rp_book.focus();
					return false;
				}
				return true;
			}
		</script>';
		
/*


				if(form.rp_name.value == "") {
					alert("Пожалуйста, введите Ваше имя");
					form.rp_name.focus();
					return false;
				}
				if(form.rp_phone.value == "") {
					alert("Пожалуйста, введите адрес эл. почты или телефон");
					form.rp_phone.focus();
					return false;
				}
				if(form.rp_phone.value.length < 7) {
					alert("Пожалуйста, укажите верный адрес эл. почты или телефон");
					form.rp_phone.focus();
					return false;
				}

*/



// print button

print '<tr><td><input type="hidden" name="rp_send" value="1" /></td><td><input class="btn btn-primary rapid_contact button ' . $mod_class_suffix . '" type="submit" value="Отправить" /></td></tr></table></form></div>' . "\n";

return true;

