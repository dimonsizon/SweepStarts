<?php
defined('ACCESS') or die();
print $body;
$action = htmlspecialchars(str_replace("'","",substr($_GET['action'],0,6)));

	if($action == "submit") {
		$mail		= htmlspecialchars(str_replace("'","",substr($_POST['mail'],0,50)), ENT_QUOTES, '');
		$subj		= htmlspecialchars(str_replace("'","",substr($_POST['subj'],0,100)), ENT_QUOTES, '');
		$textform	= htmlspecialchars(str_replace("'","",substr($_POST['textform'],0,10240)), ENT_QUOTES, '');
		$code		= md5(htmlspecialchars(str_replace("'","",substr($_POST['code'],0,5)), ENT_QUOTES, ''));
		$emailsupport = 'support@sweepstarts.com';

		if(!$mail) {
				print "<p class=\"er\">Введите пожалуйста Ваш e-mail!</p>";
		}
		elseif(!$subj) {
				print "<p class=\"er\">Введите пожалуйста тему Вашего сообщения!</p>";
		}
		elseif(!$textform) {
				print "<p class=\"er\">Введите пожалуйста текст Вашего сообщения!</p>";
		}
		elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)) {
				print "<p class=\"er\">Введите пожалуйста Ваш e-mail валидно!</p>";
		} elseif(!mysql_num_rows(mysql_query("SELECT * FROM captcha WHERE sid = '".$sid."' AND code = '".$code."'"))) {
			print "<p class=\"er\">Не правильно введён код!</b></font></center></p>";
		} else {

			$headers = "From: ".$mail."\n";
			$headers .= "Reply-to: ".$mail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			//$send = mail($adminmail, $subj, $textform, $headers);
			$send = mail($emailsupport, $subj, $textform, $headers);

			if(!$send) {
				print "<p class=\"er\">Ошибка почтового сервера!<br />Приносим извинения за предоставленные неудобства.</p>";
			} else {

				print "<p class=\"erok\">Ваше сообщение отправлено!</p>";

				$name		= "";
				$mail		= "";
				$subj		= "";
				$textform	= "";
			}
		}
	}

include "tpl/support.php";
?>