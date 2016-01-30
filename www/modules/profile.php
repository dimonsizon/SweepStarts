<?php
defined('ACCESS') or die();
if ($login) {
	if ($_GET['action'] == 'save') {
		$get_user_info = mysql_query("SELECT `pe` FROM `users` WHERE `id` = ".$user_id." LIMIT 1");
		$row = mysql_fetch_array($get_user_info);
		 $upe		= $row['pe'];

		$pass_1 = $_POST['pass_1'];
		$pass_2 = $_POST['pass_2'];
		$email	= gs_html($_POST['email']);
		$icq	= gs_html($_POST['icq']);
		$pm		= gs_html($_POST['pm']);
		$pe		= gs_html($_POST['pe']);
		$skype	= gs_html($_POST['skype']);

		if($upm) { $pm = $upm; } 
		if($upe) { $pe = $upe; } 

		if (!$email) {
			echo '<p class="er">'.$lang['er_09'].'</p>';
		} else {
			if ($pass_1 != $pass_2) {
				echo '<p class="er">'.$lang['er_11'].'</p>';
			} else {
				if (!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $email)) {
					print '<p class="er">'.$lang['er_09'].'!</p>';
				} elseif ($pm[0] != 'U' && $pm) {
					print '<p class="er">'.$lang['er_20'].'</p>';
				} elseif(mysql_num_rows(mysql_query("SELECT pm FROM users WHERE pm = '".$pm."' AND id != ".$user_id)) && $pm) {
					print "<p class=\"er\">".$lang['er_19']."</p>";
				} elseif(mysql_num_rows(mysql_query("SELECT mail FROM users WHERE mail = '".$email."' AND id != ".$user_id))) {
					print "<p class=\"er\">".$lang['er_13']."</p>";
				} else {
					$sql = 'UPDATE `users` SET ';
					if($pass_1) { $sql .= 'pass = "'.gs_md5($licKEY, $pass_1).'", '; }

					$sql .= 'mail = "'.$email.'", icq = "'.$icq.'", pm = "'.$pm.'", pe = "'.$pe.'", skype = "'.$skype.'" WHERE id = '.$user_id.' LIMIT 1';
					if (mysql_query($sql)) {
						print '<p class="erok">'.$lang['savedata'].'</p>';
					} else {
						print '<p class="er">'.$lang['erbd'].'</p>';
					}
			}
		}
	}
}

$sql	= 'SELECT * FROM users WHERE login = "'.$login.'" LIMIT 1';
$rs		= mysql_query($sql);
$a		= mysql_fetch_array($rs);

include "tpl/profile.php";

} else {
	print '<p class="er">'.$lang['noauth'].'</p>';
}
?>