<script language="javascript" type="text/javascript" src="files/alt.js"></script>
<?php
defined('ACCESS') or die();

// Задаём статус пользователю
if($_GET['pst'] || $_GET['id']) {
	if(isset($_GET['status'])) {
		if($_GET['status'] < 0 OR $_GET['status'] > 5) {
			print '<p class="er">Указанный статус не корректен!</p>';
		} elseif($status != 1) {
			print '<p class="er">У вас нет прав на эту функцию!</p>';
		} else {
			$sql = 'UPDATE `users` SET `status` = '.intval($_GET['status']).' WHERE `id` = '.intval($_GET['id']).' LIMIT 1';
			if (mysql_query($sql)) {
				print '<p class="erok">Статус был успешно установлен!</p>';
			} else {
				print '<p class="er">Ошибка записи в БД!</p>';
			}
		}
	} else {
		print '<p class="er">Не указан статус!</p>';
	}
}
// Закончили со статусом

// Создаём пользователя
if($_GET['action'] == "add") {

	$name = addslashes($_POST['name']);
	$pass1 = $_POST['pass'];
	$pass2 = $_POST['re_pass'];
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES, '');

	if(!$name OR !$pass1 OR !$pass2 OR !$email) {
		print '<p class="er">Корректно заполните все поля!</p>';
	} else {
		if($pass1 != $pass2) {
			print '<p class="er">Пароль и подтерждение не совпадают!</p>';
		} elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$email)) {
				print "<p class=\"er\">Введите правильно e-mail!</p>";
		} else {
			$sql = 'SELECT `login` FROM `users` WHERE `login` = "'.$name.'"';
			if(mysql_num_rows(mysql_query($sql))) {
				print '<p class="er">Пользователь с таким именем уже существует!</p>';
			} else {
				$sql = 'INSERT INTO `users` (`login`, `go_time`, `ip`, `pass`, `mail`, `reg_time`) VALUES ("'.$name.'", '.time().', "'.$userip.'", "'.gs_md5($licKEY, $pass1).'", "'.$email.'", '.time().')';
				if (mysql_query($sql)) {
					print '<p class="erok">Создание пользователя прошло успешно!</p>';
				} else {
					print '<p class="er">Ошибка записи в БД!</p>';
				}
			}
		}
	}

}
// Закончили создавать
$money = 0.00;
$query	= "SELECT `balance` FROM `users`";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$money = $money + $row['balance'];
}
?>
<center><b>Всего денег на балансе у пользователей: <?php print sprintf("%01.2f", $money); ?></b></center>
<hr />
<table class="tbl">
<colspan><div align="right" style="padding: 2px;">Сортировать по: <a href="?p=users">ID</a> | <a href="?p=users&sort=auth">Авторизации</a> | <a href="?p=users&sort=balance">Балансу</a> | <a href="?p=users&sort=status">Статусу</a> | <a href="?p=users&sort=login">Логину</a> | <a href="?p=users&sort=ip">IP</a></div></colspan>
	<tr>
		<th width="40"><b>ID</b></th>
  		<th><b>Логин</b></th>
		<th width="70"><b>Баланс</b></th>
		<th width="90"><b>Регистрация</b></th>
		<th width="90"><b>Входил</b></th>
		<th width="90"><b>Последний&nbsp;IP</b></th>
		<th width="55"><b>Страна</b></th>
		<th width="55"><b>Статус</b></th>
		<th width="180"><b>EDIT</b></th>
	</tr>
<?php
function users_list($pg, $num, $query) {

	$result = mysql_query($query);
	$themes = mysql_num_rows($result);

	if (!$themes) {
		print '<tr><td colspan="9" align="center"><font color="#ffffff"><b>Пользователей пока нет.</b></font></td></tr>';
	} else {

		$total = intval(($themes - 1) / $num) + 1;
		if (empty($pg) or $pg < 0) $pg = 1;
		if ($pg > $total) $pg = $total;
		$start = $pg * $num - $num;
		$result = mysql_query($query." LIMIT ".$start.", ".$num);
		while ($row = mysql_fetch_array($result)) {

		$country = getCOUNTRY($row['ip']);

	print "<tr>
		<td>".$row['id']."</td>
		<td class=\"tdleft\"><a href=\"mailto:".$row['mail']."\"><b>".$row['login']."</b></a></td>
		<td>".$row['balance']."</td>
		<td>".date("d.m.y H:i", $row['reg_time'])."</td>
		<td>".date("d.m.y H:i", $row['go_time'])."</td>
		<td>".$row['ip']."</td>
		<td><img src=\"/img/flags/".$country.".gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"".$country."\" title=\"".$country."\" /> ".substr($country, 0, 4)."</td>
	<td>";

			switch ($row['status'])
			{
			case 0:
				print "<img src=\"images/user.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"User\">";
				break;
			case 1:
				print "<img src=\"images/admin.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Админ\">";
				break;
			case 2:
				print "<img src=\"images/moder.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Модератор\">";
				break;
			case 3:
				print "<img src=\"images/ban.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Заблокированный\">";
				break;
			case 4:
				print "<img src=\"images/hranitel.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Хранитель\">";
				break;
			}

			print "</td>
			<td><nobr><a onClick=\"return confirm('Удалить этого пользователя?')\" href='del/user.php?pg=".$pg."&id=".$row[id]."'><img src=\"images/del_ico.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Удаление пользователя\"></a> ";

			switch ($row[status])
			{
			case 0:
				print '<a href="?p=users&pst=change_status&id='.$row[id].'&status=2"><img src="images/moder.png" width="16" height="16" border="0" alt="Сделать модером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=1"><img src="images/admin.png" width="16" height="16" border="0" alt="Сделать админом"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=4"><img src="images/hranitel.png" width="16" height="16" border="0" alt="Сделать хранителем"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=3"><img src="images/ban.png" width="16" height="16" border="0" alt="Закрыть доступ"></a>';
				break;
			case 1:
				print '<a href="?p=users&pst=change_status&id='.$row[id].'&status=0"><img src="images/user.png" width="16" height="16" border="0" alt="Сделать юзером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=2"><img src="images/moder.png" width="16" height="16" border="0" alt="Сделать модером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=4"><img src="images/hranitel.png" width="16" height="16" border="0" alt="Сделать хранителем"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=3"><img src="images/ban.png" width="16" height="16" border="0" alt="Закрыть доступ"></a>';
				break;
			case 2:
				print '<a href="?p=users&pst=change_status&id='.$row[id].'&status=0"><img src="images/user.png" width="16" height="16" border="0" alt="Сделать юзером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=1"><img src="images/admin.png" width="16" height="16" border="0" alt="Сделать админом"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=4"><img src="images/hranitel.png" width="16" height="16" border="0" alt="Сделать хранителем"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=3"><img src="images/ban.png" width="16" height="16" border="0" alt="Закрыть доступ"></a>';
				break;
			case 3:
				print '<a href="?p=users&pst=change_status&id='.$row[id].'&status=0"><img src="images/user.png" width="16" height="16" border="0" alt="Разблокировать"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=2"><img src="images/moder.png" width="16" height="16" border="0" alt="Сделать модером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=4"><img src="images/hranitel.png" width="16" height="16" border="0" alt="Сделать хранителем"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=1"><img src="images/admin.png" width="16" height="16" border="0" alt="Сделать админом"></a>';
				break;
			case 4:
				print '<a href="?p=users&pst=change_status&id='.$row[id].'&status=0"><img src="images/user.png" width="16" height="16" border="0" alt="Сделать юзером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=2"><img src="images/moder.png" width="16" height="16" border="0" alt="Сделать модером"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=1"><img src="images/admin.png" width="16" height="16" border="0" alt="Сделать админом"></a> <a href="?p=users&pst=change_status&id='.$row[id].'&status=3"><img src="images/ban.png" width="16" height="16" border="0" alt="Закрыть доступ"></a>';
				break;
			}

			print ' <a href="?p=edit_user&id='.$row[id].'"><img src="images/edit_ico.png" width="16" height="16" border="0" alt="Редактировать"></a> <a href="?p=referals&id='.$row[id].'"><img src="images/partners.png" width="16" height="16" border="0" alt="Привлечённые рефералы"></a> <a href="?p=logip&id='.$row[id].'"><img src="images/monip_ico.png" width="16" height="16" border="0" alt="Лог IP"></a> <a href="?p=logpages&id='.$row[id].'"><img src="images/logpage_ico.png" width="16" height="16" border="0" alt="Журнал переходов" /></a></nobr></td></tr>';
		}

		if ($pg != 1) $pervpg = "<a href=?p=users&sort=".$_GET['sort']."&pg=". ($pg - 1) .">««</a>";
		if ($pg != $total) $nextpg = " <a href=?p=users&sort=".$_GET['sort']."&pg=". ($pg + 1) .">»»</a>";
		if($pg - 2 > 0) $pg2left = " <a href=?p=users&sort=".$_GET['sort']."&pg=". ($pg - 2) .">". ($pg - 2) ."</a> | ";
		if($pg - 1 > 0) $pg1left = " <a href=?p=users&sort=".$_GET['sort']."&pg=". ($pg - 1) .">". ($pg - 1) ."</a> | ";
		if($pg + 2 <= $total) $pg2right = " | <a href=?p=users&sort=".$_GET['sort']."&pg=". ($pg + 2) .">". ($pg + 2) ."</a>";
		if($pg + 1 <= $total) $pg1right = " | <a href=?p=users&sort=".$_GET['sort']."&pg=". ($pg + 1) .">". ($pg + 1) ."</a>";
		print "<tr><td height=\"20\" colspan=\"9\" class=\"ftr\"><b>Страницы: </b>".$pervpg.$pg2left.$pg1left."[".$pg."]".$pg1right.$pg2right.$nextpg."</td></tr>";
	}
	print "</table>";
}

if($_GET['sort'] == "login") {
	$sort = "ORDER BY login ASC";
} elseif($_GET['sort'] == "status") {
	$sort = "order by status DESC";
} elseif($_GET[sort] == "balance") {
	$sort = "order by balance DESC";
} elseif($_GET[sort] == "ip") {
	$sort = "order by ip DESC";
} elseif($_GET[sort] == "auth") {
	$sort = "order by go_time DESC";
} else {
	$sort = "GROUP BY id order by id ASC";
}

if($_GET['action'] == "searchuser") {
	$su = " AND (login = '".$_POST['name']."' OR id = ".intval($_POST['name'])." OR mail = '".$_POST['name']."' OR pm = '".$_POST['name']."')";
}

$sql = "SELECT * FROM `users` WHERE bot = 0".$su." ".$sort;
users_list(intval($_GET['pg']), 50, $sql);
?>
<form action="?p=users&action=add" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 10px;">
<LEGEND><b>Создание нового пользователя</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td width="60"><strong>Login:</strong></td>
		<td><input style="width: 99%;" type="text" name="name" size="30" /></td>
		<td><strong>E-mail:</strong></td>
		<td><input style="width: 99%;" type="text" name="email" size="30" /></td>
		<td width="32" rowspan="2" valign="bottom" align="center"><input type="image" src="images/save.png" width="24" height="24" border="0" title="Сохранить!" /></td>
	</tr>
	<tr>
		<td><strong>Пароль:</strong></td>
		<td><input style="width: 99%;" type="password" name="pass" size="30" /></td>
		<td><strong>Пароль</strong> <small>[повторно]</small>:</td>
		<td><input style="width: 99%;" type="password" name="re_pass" size="30" /></td>
	</tr>
</table>
</FIELDSET>
</form>

<form action="?p=users&action=searchuser" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 10px;">
<LEGEND><b>Найти пользователя по Логину / ID / e-mail / кошельку</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td width="60"><strong>Поиск:</strong></td>
		<td><input style="width: 99%;" type="text" name="name" size="93" /></td>
		<td width="32" align="center"><input type="image" src="images/search.png" width="24" height="24" border="0" title="Поиск!" /></td>
	</tr>
</table>
</FIELDSET>
</form>