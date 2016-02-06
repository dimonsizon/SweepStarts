<?php
defined('ACCESS') or die();

if(!$login) {
	print "<p class=\"er\">Для обращения в Службу технической поддержки, Вам необходимо авторизироваться</p>";	
} else {

	if($_GET[action] == "send") {

		$to			= gs_html(str_replace("'","",substr($_POST['to'],0,30)));
		$subj		= gs_html(str_replace("'","",substr($_POST['subj'],0,50)));
		$textform	= gs_html(str_replace("'","",substr($_POST['textform'],0,102400)));
		$type 		= intval($_POST['type']);
		$fid		= intval($_POST['fid']);
		$prioritet	= intval($_POST['prioritet']);

		if(!$to || !$textform) {
			print "<p class=\"er\">Заполните все поля!</p>";
		} else {

			if(!$subj) {
				$subj = "Без темы";
			}

				$row	= mysql_fetch_array(mysql_query("SELECT id, login, mail FROM users WHERE login = '".$to."' LIMIT 1"));
				$toid	= intval($row[id]);
				$toname	= $row['login'];


				if($toid) {

					if($user_id == 1) {
						$st = 1;
					} else {
						$st = 0;
					}

					if($fid) {
						mysql_query("UPDATE msgs SET status = ".$st." WHERE id = ".$fid." LIMIT 1");
					}

					mysql_query("INSERT INTO `msgs` (`from_id`, `from_name`, `to_id`, `date`, `subject`, `text`, `fid`, `type`, `prioritet`) VALUES (".$user_id.", '".$login."', ".$toid.", ".time().", '".$subj."', '".$textform."', ".$fid.", ".$type.", ".$prioritet.")");
					$nmsg = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `msgs` WHERE `read` = 0 AND `to_id` = 1")) + 3);
					print "<p class=\"erok\">Ваше сообщение отправлено! Номер вашего обращения: ".$nmsg."</p>";

					$headers = "From: ".$adminmail."\n";
					$headers .= "Reply-to: ".$adminmail."\n";
					$headers .= "X-Sender: < http://".$cfgURL." >\n";
					$headers .= "Content-Type: text/html; charset=windows-1251\n";

					$subject = "Получено сообщение от тех. поддержки";

					$textform = "Здравствуйте, ".$row[login]."!<br />Вы получили сообщение Службы технической поддерки";

					mail($row[mail],$subject,$textform,$headers);
				
					$subj		= "";
					$textform	= "";
				} else {
					print "<p class=\"er\">Не указан получатель</p>";
				}


		}

	}





if($toname) {
	$toname = $toname;
} elseif($_GET['to']) {
	$row		= mysql_fetch_array(mysql_query("SELECT login FROM users WHERE id = ".intval($_GET['to'])." LIMIT 1"));
	$toname		= $row['login'];
} else {
	$row		= mysql_fetch_array(mysql_query("SELECT login FROM users WHERE status = 1 ORDER BY id DESC LIMIT 1"));
	$toname		= $row['login'];
}

?>


<p> <b>Форма создания нового тикета:</b> </p>
<form action="?action=send" method="post">
<table align="center" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td><input style="width: 500px;" <?php if($status == 1) { print 'type="text"'; } else { print 'type="hidden"'; } ?> name="to" size="50" maxlength="30" value="<?php print $toname; ?>" /></td>
	</tr>
	<tr>
		<td>Тема тикета:</td>
	</tr>
	<tr>
		<td><input style="width: 500px;" type="text" name="subj" size="50" maxlength="50" value="<?php print $subj; ?>" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> Текст сообщения:</td>
	</tr>
	<tr>
		<td><textarea style="width: 500px;" name="textform" cols="40" rows="12"><?php print $textform; ?></textarea></td>
	</tr>
	<tr>
		<td><div style="float: left;"><select name="prioritet"><option value="0">Приоритет:</option><option value="0">НИЗКИЙ</option><option value="1">СРЕДНИЙ</option><option value="2">ВЫСОКИЙ</option></select></div><div style="float: right; width: 50%;" align="right"><input class="subm" type="submit" value=" Создать тикет! " /></div></td>
	</tr>
</table>
</form>
<div id="msginfo"></div>

<?php
}
?>