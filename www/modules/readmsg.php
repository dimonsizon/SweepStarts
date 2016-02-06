<?php
defined('ACCESS') or die();

if($login) {

	if($_GET['act'] == "del") {

		mysql_query("DELETE FROM msgs WHERE id = ".intval($_GET['did'])." AND (to_id = ".$user_id." OR from_id = ".$user_id.") LIMIT 1");
		print "<p class=\"erok\">Сообщение удалено</p>";

	}

		$get_msg	= mysql_query("SELECT * FROM msgs WHERE id = ".intval($_GET[id])." AND (to_id = ".$user_id." OR from_id = ".$user_id.") LIMIT 1");
		$row		= mysql_fetch_array($get_msg);

		if($row) {

			if($_GET['ticket'] == "off") {
				mysql_query("UPDATE msgs SET `status` = 3 WHERE id = ".intval($_GET['id'])." AND (to_id = ".$user_id." OR from_id = ".$user_id.") LIMIT 1");
				print "<p class=\"erok\">Тикет закрыт! Благодарим за обращение в службу поддержки.</p>";
			}

			mysql_query("UPDATE msgs SET `read` = 1 WHERE id = ".intval($_GET[id])." AND to_id = ".$user_id." LIMIT 1");
			$id			= $row['id'];
			$subject	= $row['subject'];
			$text		= $row['text'];
			$fromname	= $row['from_name'];
			$fromname2	= $row['from_name'];
			$from_id	= $row['from_id'];
			$to_id		= $row['to_id'];
			$fid		= $row['fid'];
			$date		= $row['date'];
			$com		= $row['comment'];
			$st			= $row['status'];
			$pr			= $row['prioritet'];

			if(!$fid) {
				$fid = $id;
			}

			$get_us	= mysql_query("SELECT login FROM users WHERE id = ".$to_id." LIMIT 1");
			$row		= mysql_fetch_array($get_us);
			$ulogin		= $row['login'];


			print "<table bgcolor=\"#cccccc\" width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\">
			<tr bgcolor=\"#f0f0f0\">
				<td width=\"100\">От кого:</td>
				<td>".$fromname."<div style=\"float: right; font-size: 14px;\" valign=\"middle\">";

	if($pr == "0") {
		print '<font color="green">низкий</font> ';
	} elseif($pr == 1) {
		print '<font color="orange">средний</font> ';
	} elseif($pr == 2) {
		print '<font color="red">высокий</font> ';
	} else {
		print '-';
	}

	if($st != 3) {
		print ' <a href="?id='.$id.'&ticket=off"><i class="fa fa-lock" title="Вопрос решен. ЗАКРЫТЬ ТИКЕТ!"></i></a> ';
	}

	if($st == "0") {
		print '<i class="fa fa-clock-o text-blue" title="Последнее сообщение от пользователя. Ожидает ответ от администрации."></i>';
	} elseif($st == 1) {
		print '<i class="fa fa-hourglass-half text-success" title="Последнее сообщение от администрации. Ожидает ответ от пользователя."></i>';
	} elseif($st == 3) {
		print '<i class="fa fa-lock" title="Вопрос решен. Тикет закрыт."></i>';
	} else {
		print " - ";
	}

			print " <a href=\"?id=".$id."\"><i class=\"fa fa-refresh\" title=\"Обновить\"></i></a></div></td>
			</tr>
			<tr bgcolor=\"#eee\">
				<td>Кому:</td>
				<td>".$ulogin."</td>
			</tr>
			<tr bgcolor=\"#f0f0f0\">
				<td>Дата:</td>
				<td>".date("d.m.Y H:i", $date)."</td>
			</tr>
			<tr bgcolor=\"#eee\">
				<td>Тема:</td>
				<td><b>".$subject."</b></td>
			</tr>
			<tr height=\"3\" bgcolor=\"#f0f0f0\">
				<td colspan=\"2\">";

			if($com) { print 'Дополнительная информация: <font color="#333">'.$com.'</font>'; }

			print "</td>
			</tr>
			</table>";

			print "<p>".nl2br($text)."</p><p><hr size=\"3\" color=\"#ffcc99\" /></p>";



			// ВЫВОДИМ ОСТАЛЬНЫЕ СООБЩЕНИЯ

	$page 	= intval($_GET['page']);
	$num	= 50;

	$query	= "SELECT * FROM msgs WHERE fid = ".intval($_GET['id'])." ORDER BY id ASC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);
	while ($row = mysql_fetch_array($result))
	{
	$subject = $row['subject'];
	mysql_query("UPDATE msgs SET `read` = 1 WHERE id = ".$row['id']." AND to_id = ".$user_id." LIMIT 1");
	print '
	<table width="100%" border="0" style="border: 1px solid #99ff99; background-color: #eee; padding: 4 4 4 4px; margin-bottom: 10px;">
		<tr>
			<td><div style="float: left;" id="'.$row['id'].'">'.date("d.m.Y H:i", $row['date']).' - <b>'.$row['from_name'].'</b> [ '.$subject.' ]</div>
			<div style="float: right;"><img style="cursor: pointer;" onclick="if(confirm(\'Вы действительно хотите удалить сообщение?\')) top.location.href=\'?id='.intval($_GET['id']).'&did='.$row['id'].'&act=del\';" src="/img/cancel.png" width="16" height="16" border="0" alt="" title="Удалить!" /></div>
			<div style="clear: both;"></div>
			<div class="hline"></div>
			<p align="justify">'.nl2br($row['text']).'</p></td></table><div class="hline" style="margin-bottom: 3px;"></div>';

	}


	if($page != 1) { $pervpage = "<a href=\"?id=".intval($_GET['id'])."&page=1\">««</a> "; }
	if($page != $total) { $nextpage = " <a href=\"?id=".intval($_GET['id'])."&page=".$total."\">»»</a>"; }
	if($page - 2 > 0) { $page2left = " <a href=\"?id=".intval($_GET['id'])."&page=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
	if($page - 1 > 0) { $page1left = " <a href=\"?id=".intval($_GET['id'])."&page=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
	if($page + 2 <= $total) { $page2right = " <a href=\"?id=".intval($_GET['id'])."&page=". ($page + 2) ."\">". ($page + 2) ."</a>"; }
	if($page + 1 <= $total) { $page1right = " <a href=\"?id=".intval($_GET['id'])."&page=". ($page + 1) ."\">". ($page + 1) ."</a>"; }

	print "<div class=\"pages\"><b>Страницы: </b>".$pervpage.$page2left.$page1left.$page." ".$page1right.$page2right.$nextpage."</div>";

// ЗАКОНЧИЛИ С ВЫВОДОМ СООБЩЕНИЙ

	if($st == 3) {
		print "<p class=\"er\">Тикет закрыт.</p>";
	} else {

$subj	= substr($subject, 0,3);

if($subj == "Re:") {
	$subject	= str_replace("Re:", "", $subject);
	$subj		= "Re[2]: ".$subject;
} elseif($subj == "Re[") {
	$subject	= $subject."]";
	$subject1	= preg_replace('/(Re\[)(.+?)(\]:)(.+?)(\])/', '\\2', $subject);
	$subject2	= preg_replace('/(Re\[)(.+?)(\]:)(.+?)(\])/', '\\4', $subject);
	$subj		= "Re[".intval($subject1 + 1)."]:".$subject2;
} else {
	$subj		= "Re: ".$subject;
}

$textform	= "";

if($fromname2 == $login) {
	$fromname2 = $ulogin;
}


?>

<p><b>Быстрый ответ на сообщение:</b></p>
<form action="/newmsg/?action=send" method="post">
<table width="100%" align="center" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td>
		<input type="hidden" name="to" size="50" maxlength="20" value="<?php print $fromname2; ?>" />
		<input type="hidden" name="fid" size="50" maxlength="20" value="<?php print $fid; ?>" />
		<input type="hidden" name="tid" size="50" maxlength="20" value="<?php print intval($_GET['id']); ?>" />
		</td>
	</tr>
	<tr>
		<td>Тема тикета:</td>
	</tr>
	<tr>
		<td><input style="width: 99%;" type="text" name="subj" size="50" maxlength="50" value="<?php print $subj; ?>" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>!</b></font> Текст сообщения:</td>
	</tr>
	<tr>
		<td><textarea style="width: 99%;" name="textform" cols="40" rows="12"><?php print $textform; ?></textarea></td>
	</tr>
	<tr>
		<td align="right"><input type="submit" value=" Отправить! " /></td>
	</tr>
</table>
</form>

<?php
	}
		} else {
			print "<p class=\"er\">Сообщение не найдено!</p>";
		}
	
} else {
	print "<p>Для доступа к данной странице, Вам необходимо авторизироваться!</p>";	
}
?>