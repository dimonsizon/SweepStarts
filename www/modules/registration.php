<?php
defined('ACCESS') or die();
print $body;
if($_GET['action'] == "save") {
		$ulogin	= gs_html($_POST['ulogin']);
		$pass	= $_POST['pass'];
		$repass	= $_POST['repass'];
		$email	= gs_html($_POST['email']);
		$code	= gs_html($_POST["code"]);
		$skype	= gs_html($_POST["skype"]);
		$icq	= gs_html($_POST["icq"]);
		$pm		= gs_html($_POST["pm"]);
		$pe		= gs_html($_POST["pe"]);
		$yes	= intval($_POST['yes']);

		if(!$ulogin || !$pass || !$repass || !$email || !$yes) {
			$error = "<p class=\"er\">".$lang['obyazalovka']."</p>";
		} elseif(strlen($ulogin) > 20 || strlen($ulogin) < 3) {
			$error = "<p class=\"er\">".$lang['er_10']."</p>";
		} elseif($pass != $repass) {
			$error = "<p class=\"er\">".$lang['er_11']."</p>";
		} elseif(!mysql_num_rows(mysql_query("SELECT * FROM `captcha` WHERE sid = '".$sid."' AND code = '".md5($code)."'")) && cfgSET('regcaptcha') == "on") {
			$error = "<p class=\"er\">".$lang['er_05']."</p>";
		} elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $email)) {
			$error = "<p class=\"er\">".$lang['er_09']."</p>";
		} elseif(mysql_num_rows(mysql_query("SELECT login FROM users WHERE login = '".$ulogin."'"))) {
			$error = "<p class=\"er\">".$lang['er_12']."</p>";
		} elseif(mysql_num_rows(mysql_query("SELECT mail FROM users WHERE mail = '".$email."'"))) {
			$error = "<p class=\"er\">".$lang['er_13']."!</p>";
		} else {
			$time	 = time();
			$ip		 = $userip;
			$pass	 = gs_md5($licKEY, $pass);
			if($referal) { 
				$get_user_info	= mysql_query("SELECT * FROM `users` WHERE `login` = '".$referal."' LIMIT 1");
				$row			= mysql_fetch_array($get_user_info);
				$ref_id			= intval($row['id']);
			} else { 
				$ref_id = 1; //admin id
			}

			if(cfgSET('cfgMailConf') == "on") {

				$active		= 1;
				$actlink	= "���� ������ ��� ��������� ��������: http://".$cfgURL."/activate.php?m=".$email."&h=".gs_md5($licKEY, $ulogin.$email);
				$enactlink	= "Your link to activate your account: http://".$cfgURL."/activate.php?m=".$email."&h=".gs_md5($licKEY, $ulogin.$email);

			} else {
				$active		= 0;
				$actlink	= "";
				$enactlink	= "";
			}

			$sql = "INSERT INTO `users` (`login`, `pass`, `mail`, `go_time`, `ip`, `reg_time`, `ref`, `pm`, `active`, `skype`, `icq`, `pe`, `bonus`) VALUES ('".$ulogin."', '".$pass."', '".$email."', ".$time.", '".$ip."', ".$time.", ".$ref_id.", '".$pm."', ".$active.", '".$skype."', '".$icq."', '".$pe."', ".cfgSET('cfgBonusReg').")";
			mysql_query($sql);

			$subject = "Registration Info / ��������������� ����������";

			$headers = "From: ".$adminmail."\n";
			$headers .= "Reply-to: ".$adminmail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			$text = "������������ <b>".$ulogin."!</b><br />����������� ��� � �������� ������������ � ������� <a href=\"http://".$cfgURL."/\" target=\"_blank\">http://".$cfgURL."</a><br />��� Login: <b>".$ulogin."</b><br />��� ������: <b>".$repass."</b><br />".$actlink."<br /><br />� ���������, ������������� ������� ".$cfgURL."<hr />Hello <b>".$ulogin."!</b><br />Congratulations on your successful registration for project <a href=\"http://".$cfgURL."/\" target=\"_blank\">http://".$cfgURL."</a><br />Your Login: <b>".$ulogin."</b><br />Your password: <b>".$repass."</b><br />".$enactlink."<br /><br />Sincerely, administration of the project ".$cfgURL;

			mail($email, $subject, $text, $headers);

			$ulogin	= "";
			$pass	= "";
			$repass	= "";
			$email	= "";
			$skype	= "";
			$pm		= "";
			$pe		= "";

			$error = 1;
		}
}

if($error == 1) {

	print "<p class=\"erok\">".$lang['er_14']."</p>";
	include "tpl/login.php";

} else {
	print $error;
	include "tpl/registration.php";
} 
?>