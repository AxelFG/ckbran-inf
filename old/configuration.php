<?php
class JConfig {
	public $offline = '0';
	public $offline_message = 'Сайт закрыт на техническое обслуживание.<br /> Пожалуйста, зайдите позже.';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $sitename = 'ЦКБ РАН';
	public $editor = 'jce';
	public $captcha = '0';
	public $list_limit = '20';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'ckbran';
	public $password = 'mkgnbDP0H7iqmE';
	public $db = 'ckbran';
	public $dbprefix = 'joom_';
	public $live_site = '';
	public $secret = '78An1KzlDxXmxqN1';
	public $gzip = '0';
	public $error_reporting = 'default';
	public $helpurl = 'http://help.joomla.org/proxy/index.php?option=com_help&keyref=Help{major}{minor}:{keyref}';
	public $ftp_host = '151.248.116.136';
	public $ftp_port = '21';
	public $ftp_user = 'ckbran';
	public $ftp_pass = 'mkgnbDP0H7iqmE';
	public $ftp_root = 'public_html';
	public $ftp_enable = '1';
	public $offset = 'UTC';
	public $mailer = 'mail';
	public $mailfrom = 'webmaster@ckbran.ru';
	public $fromname = 'ЦКБ РАН';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = '1';
	public $smtpuser = 'webmaster@ckbran.ru';
	public $smtppass = 'mkgnbDP0H7iqmE';
	public $smtphost = 'mail.ckbran.ru';
	public $smtpsecure = 'none';
	public $smtpport = '587';
	public $caching = '0';
	public $cache_handler = 'file';
	public $cachetime = '15';
	public $MetaDesc = '';
	public $MetaKeys = '';
	public $MetaTitle = '1';
	public $MetaAuthor = '1';
	public $MetaVersion = '0';
	public $robots = '';
	public $sef = '1';
	public $sef_rewrite = '1';
	public $sef_suffix = '0';
	public $unicodeslugs = '0';
	public $feed_limit = '10';
	public $log_path = '/var/www/ckbran/data/www/ckbran.ru/old/logs';
	public $tmp_path = '/var/www/ckbran/data/www/ckbran.ru/old/tmp';
	public $lifetime = '150';
	public $session_handler = 'database';
	public $MetaRights = '';
	public $sitename_pagetitles = '0';
	public $force_ssl = '0';
	public $feed_email = 'author';
	public $cookie_domain = '';
	public $cookie_path = '';
}