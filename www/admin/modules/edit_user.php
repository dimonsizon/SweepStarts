<?php
defined('ACCESS') or die();

if($_GET[action] == "add") {
	$pass		= $_POST['pass'];
	$repass		= $_POST['re_pass'];
	$mail		= htmlspecialchars($_POST['mail'], ENT_QUOTES, '');
	$ul			= htmlspecialchars($_POST['ul'], ENT_QUOTES, '');
	$com		= htmlspecialchars($_POST['com'], ENT_QUOTES, '');
	$pm			= htmlspecialchars($_POST['pm'], ENT_QUOTES, '');
	$pe			= htmlspecialchars($_POST['pe'], ENT_QUOTES, '');
	$skype		= htmlspecialchars($_POST['skype'], ENT_QUOTES, '');
	$icq		= htmlspecialchars($_POST['icq'], ENT_QUOTES, '');
	$currency	= intval($_POST['currency']);
	$phone		= gs_html($_POST['phone']);
	$social		= gs_html($_POST['social']);
	$bank		= gs_html($_POST['bank']);

	if($pass && $repass) {

		if($pass == $repass) {
			mysql_query('UPDATE users SET pass = "'.gs_md5($licKEY, $pass).'" WHERE id = '.intval($_GET['id']).' LIMIT 1');
		} else {
			print "<p class=\"er\">Пароль не изменён, из-за несовпадения введённых паролей!</p>";
		}

	}

	if($mail) {
		if(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)) {
			print "<p class=\"er\">Введите правильный e-mail!</p>";
		} else {
			mysql_query('UPDATE users SET currency = '.$currency.', phone = "'.$phone.'", social = "'.$social.'", bank = "'.$bank.'", mail = "'.$mail.'", comment = "'.$com.'", balance = balance + '.sprintf("%01.2f", $_POST['pmbal']).', bonus = bonus + '.sprintf("%01.2f", $_POST['bonus']).', pm = "'.$pm.'", pe = "'.$pe.'", skype = "'.$skype.'", icq = "'.$icq.'", ref_percent = '.sprintf("%01.2f", $_POST['ref_percent']).' WHERE id = '.intval($_GET['id']).' LIMIT 1');
			print "<p class=\"erok\">Данные сохранены!</p>";

			if($_POST['pmbal'] != 0.00) {
				mysql_query('INSERT INTO enter (sum, date, login, status, purse, paysys) VALUES ("'.sprintf("%01.2f", $_POST['pmbal']).'", "'.time().'", "'.$ul.'", 2, "ADMINISTRATOR", "PerfectMoney")');
			}	

		}
	} else {
		print "<p class=\"er\">Не заполнены все поля!</p>";
	}
}

if($_GET['action'] == "mailto") {

$subject	= $_POST['subject'];
$msg		= $_POST['msg'];
$tick		= intval($_POST['tick']);

	$query	= "SELECT `id`, `mail` FROM `users` WHERE `id` = ".intval($_GET['id'])." LIMIT 1";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);
	$mail	= $row['mail'];

	$headers = "From: ".$adminmail."\n";
	$headers .= "Reply-to: ".$adminmail."\n";
	$headers .= "X-Sender: < http://".$cfgURL." >\n";
	$headers .= "Content-Type: text/html; charset=windows-1251\n";

			if(!$tick) {
				mail($mail, $subject, $msg, $headers);
			} else {
				mysql_query("INSERT INTO `msgs` (`from_id`, `from_name`, `to_id`, `date`, `subject`, `text`) VALUES (".$user_id.", '".$login."', ".$row['id'].", ".time().", '".$subject."', '".$msg."')");
			}

	print "<p class=\"erok\">Сообщение отправлено</p>";

}

$get_user = mysql_query("SELECT * FROM `users` WHERE id = ".intval($_GET['id'])." OR login = '".htmlspecialchars($_GET['l'], ENT_QUOTES, '')."' LIMIT 1");
$rows = mysql_fetch_array($get_user);
 $uid			= $rows['id'];
 $email			= $rows['mail'];
 $pmbal			= $rows['balance'];
 $bonus			= $rows['bonus'];
 $com			= $rows['comment'];
 $pm			= $rows['pm'];
 $pe			= $rows['pe'];
 $skype			= $rows['skype'];
 $currency		= $rows['currency'];
 $phone			= $rows['phone'];
 $social		= $rows['social'];
 $bank			= $rows['bank'];
 $icq			= $rows['icq'];
 $ref_percent	= $rows['ref_percent'];

$country = getCOUNTRY($rows['ip']);
?>
<FIELDSET>
<LEGEND><b>Редактирование данных пользователя: <?php print $rows['login']; ?></b> [ <?php print "<img src=\"/img/flags/".$country.".gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"".$country."\" title=\"".$country."\" /> ".$rows['ip']; ?> ]</LEGEND>
<form action="?p=edit_user&id=<?php print intval($uid); ?>&action=add" method="post">
<input type="hidden" name="ul" value="<?php print $rows['login']; ?>" />
<table align="center" width="630" border="0" cellpadding="3" cellspacing="0" style="border: solid #cccccc 1px;">
<tr bgcolor="#dddddd">
	<td><b>Пароль</b>:</td>
	<td align="right"><input style="width: 480px;" type="password" name="pass" size="70" maxlength="50" value="" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>Пароль</b> <small>[повторно]</small>:</td>
	<td align="right"><input style="width: 480px;" type="password" name="re_pass" size="70" maxlength="50" value="" /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><font color="red"><b>*</b></font> <b>E-mail</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="mail" size="70" maxlength="30" value="<?php print $email; ?>" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>Skype</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="skype" size="70" maxlength="50" value="<?php print $skype; ?>" /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>ICQ UIN</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="icq" size="70" maxlength="20" value="<?php print $icq; ?>" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>Валюта</b>:</td>
	<td align="right"><select name="currency">
<?php
$sql	 = 'SELECT `id`, `style`, `name` FROM `currency`';
$rs		 = mysql_query($sql);
while($a2 = mysql_fetch_array($rs)) {
	print '<option value="'.$a2['id'].'"';
	if($currency == $a2['id']) { print ' selected'; }
	print '>'.$a2['name'].'</option>';
}
?>
					</select></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>Телефон</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="phone" size="70" maxlength="50" value="<?php print $phone; ?>" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>Соц. сеть</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="social" size="70" maxlength="250" value="<?php print $social; ?>" /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>Реквизиты</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="bank" size="70" maxlength="250" value="<?php print $bank; ?>" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>Баланс</b> [<?php print $pmbal; ?>]:</td>
	<td align="right"><input style="width: 480px;" type="text" name="pmbal" size="70" maxlength="30" value="" /></td>
</tr>

<tr bgcolor="#dddddd">
	<td><b>Бонус</b> [<?php print $bonus; ?>]:</td>
	<td align="right"><input style="width: 480px;" type="text" name="bonus" size="70" maxlength="30" value="" /></td>
</tr>

<tr bgcolor="#eeeeee">
	<td><b>Счет PM</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="pm" size="70" maxlength="30" value="<?php print $pm; ?>" /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>Счет Payeer</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="pe" size="70" maxlength="30" value="<?php print $pe; ?>" /></td>
</tr>

<tr bgcolor="#eeeeee">
	<td><b>Реферальный %</b> <span class="tool"><img src="images/help_ico.png" width="16" height="16" alt="" /><span class="tip">Вы можете этому пользователю установить уникальный процент реферальных отчислений. Если установить 0, тогда его процент будет как и для остальных пользователей.</span></span></td>
	<td align="right"><input style="width: 480px;" type="text" name="ref_percent" size="70" maxlength="10" value="<?php print $ref_percent; ?>" /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>Комментарий</b>:</td>
	<td align="right"><input style="width: 480px;" type="text" name="com" size="70" maxlength="150" value="<?php print $com; ?>" /></td>
</tr>
</table>
<table align="center" width="640" border="0">
	<tr>
		<td align="right"><input type="submit" value=" Сохранить! " /></td>
	</tr>
</table>
</form>
</FIELDSET>
<hr />
<FIELDSET style="border: solid #666666 1px; margin-top: 15px;">
<LEGEND><b>Депозиты пользователя:</b></LEGEND>
<table class="tbl">
	<tr>
		<th width="40"><b>ID</b></th>
  		<th><b>Дата</b></th>
		<th><b>Сумма</b></th>
		<th><b>Тарифный план</b></th>
	</tr>
<?php

		$result = mysql_query("SELECT * FROM deposits WHERE status = 0 AND user_id = ".intval($uid)." ORDER BY id ASC");
		while ($row = mysql_fetch_array($result)) {

		$result2	= mysql_query("SELECT name FROM plans WHERE id = ".$row['plan']." LIMIT 1");
		$row2		= mysql_fetch_array($result2);

			print "<tr>
			<td>".$row['id']."</td>
			<td>".date("d.m.y H:i", $row['date'])."</td>
			<td>".$row['sum']."</td>
			<td>".$row2['name']."</td>
		</tr>";
		}

	print "</table>";

?>
</FIELDSET>

<hr />

<FIELDSET style="border: solid #666666 1px; margin-top: 15px;">
<LEGEND><b>Пополнения:</b></LEGEND>

<table class="tbl">
	<tr>
		<th width="40"><b>ID</b></th>
  		<th><b>Дата</b></th>
		<th><b>Сумма</b></th>
		<th width="120"><b>Счет</b></th>
		<th width="100"><b>Система</b></th>
	</tr>
<?php

		$result = mysql_query("SELECT * FROM enter WHERE status = 2 AND login = '".$rows['login']."' ORDER BY id ASC");
		while ($row = mysql_fetch_array($result)) {

			print "<tr>
			<td>".$row['id']."</td>
			<td>".date("d.m.y H:i", $row['date'])."</td>
			<td>".$row['sum']."</td>
			<td>".$row['purse']."</td>
			<td>".$row['paysys']."</td>
		</tr>";
		}

	print "</table>";

?>

</FIELDSET>
<hr />
<FIELDSET style="margin-top: 15px;">
<LEGEND><b>Вывод средств:</b></LEGEND>

<table class="tbl">
	<tr>
		<th width="40"><b>ID</b></th>
  		<th><b>Дата</b></th>
		<th><b>Сумма</b></th>
		<th width="120"><b>Счет</b></th>
		<th width="100"><b>Система</b></th>
	</tr>
<?php

		$result = mysql_query("SELECT * FROM output WHERE status = 2 AND login = '".$rows['login']."' ORDER BY id ASC");
		while ($row = mysql_fetch_array($result)) {

		$get_ps	= mysql_query("SELECT name FROM paysystems WHERE id = ".intval($row['paysys'])." LIMIT 1");
		$rowps	= mysql_fetch_array($get_ps);

			print "<tr>
			<td>".$row['id']."</td>
			<td>".date("d.m.y H:i", $row['date'])."</td>
			<td>".$row['sum']."</td>
			<td>".$row['purse']."</td>
			<td>".$rowps['name']."</td>
		</tr>";
		}

	print "</table>";

?>

</FIELDSET>
<?php
	$get_user_info = mysql_query("SELECT ref FROM users WHERE id = ".intval($uid)." LIMIT 1");
	$row = mysql_fetch_array($get_user_info);
	 $ref			= $row['ref'];	

	 if($ref) {

		$get_user_info2	= mysql_query("SELECT id, login FROM users WHERE id = ".$ref." LIMIT 1");
		$row2 			= mysql_fetch_array($get_user_info2);
		$upl			= "<a href=\"?p=edit_user&id=".$row2['id']."\" target=\"_blank\">".$row2['login']."</a>";

	 } else {
		$upl			= "-";
	 }
?>
<hr />
<FIELDSET style="margin-top: 15px;">
<LEGEND><b>Рефералы</b> [ <?php print 'Upline: '.$upl; ?> ]</LEGEND>

<table class="tbl" width="100%">
<tr>
	<th width="50"><b>#</b></th>
	<th class="tdleft"><b>Login:</b></th>
	<th width="150"><b>Доход $:</b></th>
</tr>
<?php

function PrintRef($refid, $i, $c) {

	$sql	= 'SELECT id, login, ref_money FROM users WHERE ref = '.$refid;
	$rs		= mysql_query($sql);
		$n 	= 1;
		while($a = mysql_fetch_array($rs)) {

			if($i == 1) {

				print "<tr><td>".$n."</td><td class=\"tdleft\"><a href=\"?p=edit_user&id=".$a['id']."\" target=\"_blank\">".$a['login']."</a></font></td><td>".$a['ref_money']."</td></tr>";

				if($i <= $c) {
					PrintRef($a['id'], intval($i + 1), $c);
				}

			} else {

				print "<tr><td></td><td class=\"tdleft\" style=\"padding-left: ".$i."0px;\"><font color=\"#999999\">» ".$a['login']."</font></td><td>-</td></tr>";

				if($i <= $c) {
					PrintRef($a['id'], intval($i + 1), $c);
				}

			}
		$n++;
		}
		
}

	$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

	PrintRef(intval($_GET['id']), 1, $countlvl);

	$sql	= 'SELECT login, ref_money FROM users WHERE ref = '.intval($uid);
	$rs		= mysql_query($sql);

	if(mysql_num_rows($rs)) {

		$m = 0;
		while($a = mysql_fetch_array($rs)) {
			$m = $m + $a['ref_money'];
		}

		print "<tr align=\"center\" bgcolor=\"#dddddd\"><td align=\"right\" colspan=\"2\" style=\"padding: 3px;\"><b>Всего:</b></td><td><b>".sprintf("%01.2f", $m)."</b></td></tr>";

	} else {
		print "<tr bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\">Пользователь пока никого не пригласил!</td></tr>";
	}

print '</table>';
?>

</FIELDSET>

<hr />
<FIELDSET style="border: solid #666666 1px; margin-top: 15px;">
<LEGEND><b>Авторизации за последние 30 дней</b></LEGEND>

<table class="tbl">
<tr>
	<th width="50%"><strong>Дата</strong></th>
	<th><strong>IP</strong></th>
	<th><strong>Страна</strong></th>
</tr>
<?php
$sql	 = "SELECT * FROM logip WHERE user_id = ".intval($uid)." AND date > ".intval(time() - 2592000)." ORDER BY id DESC";
$rs		 = mysql_query($sql);
while($a = mysql_fetch_array($rs)) {

$country = getCOUNTRY($a['ip']);

print "<tr>
	<td>".date("d.m.Y H:i:s", $a['date'])."</td>
	<td>".$a['ip']."</td>
	<td><img src=\"/img/flags/".$country.".gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"".$country."\" title=\"".$country."\" /> ".$country."</td>
</tr>";
}

?>
</table>

</FIELDSET>
<hr />

<script type="text/javascript" src="editor/tiny_mce_src.js"></script>
<script type="text/javascript">
	tinyMCE.init({

		mode : "exact",
		elements : "elm1",
		theme : "advanced",
		plugins : "cyberfm,safari, inlinepopups,advlink,advimage,advhr,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
		language: "ru",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,hr,|,forecolor,backcolor,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "pasteword,|,bullist,numlist,|,link,image,media,|,tablecontrols,|,replace,charmap,cleanup,fullscreen,preview,code",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		content_css : "/files/css/styles.css",

		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>

<FIELDSET style="border: solid #666666 1px; margin-top: 15px;">
<LEGEND><b>Отправка сообщения пользователю</b></LEGEND>
<form action="?p=edit_user&id=<?php print intval($_GET['id']); ?>&action=mailto" method="post" name="mainForm">
<table bgcolor="#eeeeee" width="612" align="center" border="0" style="border: solid #cccccc 1px; width: 612px;">
<tr><td align="center"><input style=" width: 606px;" size="97" name="subject" value="Сообщение от администратора проекта <?php print $cfgURL; ?>" type="text" maxlength="100"></td></tr>
<tr><td align="center" style="padding-bottom: 10px;"><textarea id="elm1" style="width: 605px;" name="msg" cols="103" rows="20"></textarea>
</td></tr>
</table>
<table align="center" width="624" border="0">
	<tr>
		<td><label><input type="checkbox" name="tick" value="1"> <b>Отправить в тикеты</b></label></td>
		<td align="right"><input type="submit" value=" Отправить сообщение! " /></td>
	</tr>
</table>
</form>
</FIELDSET>