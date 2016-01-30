<?php
defined('ACCESS') or die();

// Создаем уровень
if($_GET['act'] == "addreflevel") {
	$level		= intval($_POST['level']);
	$percent	= sprintf ("%01.2f", str_replace(',', '.', $_POST['percent']));
	$minsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['minsum']));
	$nominsum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['nominsum']));

	if($level < 1) {
		print '<p class="er">Введите уровень реферальной системы</p>';
	} elseif($percent < 0.01 || $percent > 100) {
		print '<p class="er">Процент должен быть от 0.01 до 100</p>';
	} else {
		mysql_query('INSERT INTO reflevels (id, sum, minsum, nominsum) VALUES ('.$level.', '.$percent.', '.$minsum.', '.$nominsum.')');
		print '<p class="erok">Новый реферальный уровень - добавлен!</p>';
	}
}

// Создаем уровень бонуса
if($_GET['act'] == "addbonus") {
	$name		= gs_html($_POST['name']);
	$refsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['refsum']));
	$sum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));

	if(!$name) {
		print '<p class="er">Введите название</p>';
	} else {
		mysql_query('INSERT INTO bonus (name, refsum, sum) VALUES ("'.$name.'", '.$refsum.', '.$sum.')');
		print '<p class="erok">Новый бонусный уровень - добавлен!</p>';
	}
}

// Удаляем уровень
if($_GET['act'] == "dellevel") {
	mysql_query("DELETE FROM reflevels WHERE id = ".intval($_GET['id'])." LIMIT 1");
	print '<p class="erok">Реферальный уровень удален!</p>';
}

// Удаляем уровень бонуса
if($_GET['act'] == "delbonus") {
	mysql_query("DELETE FROM bonus WHERE id = ".intval($_GET['id'])." LIMIT 1");
	print '<p class="erok">Бонусный уровень удален!</p>';
}

// Удаляем баннер
if($_GET['act'] == "delbanner") {
	mysql_query("DELETE FROM `promo` WHERE id = ".intval($_GET['id'])." LIMIT 1");
	print '<p class="erok">Баннер удален!</p>';
}

// Редактируем уровень
if($_GET['act'] == "editlevel") {
	$level		= intval($_POST['level']);
	$percent	= sprintf ("%01.2f", str_replace(',', '.', $_POST['percent']));
	$minsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['minsum']));
	$nominsum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['nominsum']));

	if($level < 1) {
		print '<p class="er">Введите уровень реферальной системы</p>';
	} elseif($percent < 0.01 || $percent > 100) {
		print '<p class="er">Процент должен быть от 0.01 до 100</p>';
	} else {
		mysql_query("UPDATE reflevels SET id = ".$level.", sum = ".$percent.", minsum = ".$minsum.", nominsum = ".$nominsum." WHERE id = ".intval($_GET['id'])." LIMIT 1");
		print '<p class="erok">Изменения сохранены!</p>';
	}
}

// Редактируем уровень бонуса
if($_GET['act'] == "editbonus") {
	$name		= gs_html($_POST['name']);
	$refsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['refsum']));
	$sum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));

	if(!$name) {
		print '<p class="er">Введите название</p>';
	} else {
		mysql_query("UPDATE bonus SET name = '".$name."', sum = ".$sum.", refsum = ".$refsum." WHERE id = ".intval($_GET['id'])." LIMIT 1");
		print '<p class="erok">Изменения сохранены!</p>';
	}
}

// Сохраняем настройки
if($_GET['act'] == "seveset") {
	if($_POST['cfgRefPerc']) {
		mysql_query("UPDATE `settings` SET `data` = '".intval($_POST['cfgRefPerc'])."' WHERE `cfgname` = 'cfgRefPerc' LIMIT 1");
	}
	if($_POST['refname']) {
		mysql_query("UPDATE `settings` SET `data` = '".gs_html($_POST['refname'])."' WHERE `cfgname` = 'refname' LIMIT 1");
	}

	$file		= addslashes(gs_html($_FILES['banner']['name']));
	$type		= substr(strrchr($file,"."),1);

	if($file) {
		$sql = mysql_query("INSERT INTO `promo` (`type`) values ('".$type."')");

		$bname	= mysql_insert_id().".".$type;
		copy($_FILES['banner']['tmp_name'], "../img/promo/".$bname);

		print "<p class=\"erok\">ПРОМО-материал добавлен!</p>";
	} else {
		print "<p class=\"er\">Вы не указали архив или файл для загрузки!</p>";
	}

	print '<p class="erok">Изменения сохранены!</p>';

}

?>
<script language="javascript" type="text/javascript" src="files/alt.js"></script>

	<FIELDSET>
	<LEGEND>Настройки партнерской программы</LEGEND>
		<form action="?p=ref&act=seveset" method="post" enctype="multipart/form-data">
		<table width="600" align="center" border="0" cellpadding="3" cellspacing="0" style="border: solid #cccccc 1px; border-radius: 4px;">
		<tr bgcolor="#dddddd">
			<td height="30"><b>Начисление</b>:</td>
			<td align="right"><label><input type="radio" name="cfgRefPerc" value="1" <?php if(cfgSET('cfgRefPerc') == "1") { print ' checked="checked"'; } ?> /> От суммы вклада</label> / <label><input type="radio" name="cfgRefPerc" value="2" <?php if(cfgSET('cfgRefPerc') == "2") { print ' checked="checked"'; } ?> /> От начисления процентов</label></td>
			<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Выберите от чего именно будут начислять проценты партнерам. От суммы вклада или от суммы начисления процентов инвестору." /></td>
		</tr>
		<tr bgcolor="#eeeeee">
			<td height="30"><b>Партнерская ссылка</b>:</td>
			<td align="right">
			http://<?php print $cfgURL; ?>/?<input style="width: 100px;" type="text" name="refname" value="<?php print cfgSET('refname'); ?>">=login
			</td>
			<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Укажите какого вида будет партнерская ссылка. Внимание, после запуска проекта данную опцию не менять, т.к. станут не рабочими старые ссылки партнеров." /></td>
		</tr>
		<tr bgcolor="#dddddd">
			<td height="30"><b>Баннер</b>:</td>
			<td align="right"><input type="file" name="banner"></td>
			<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данный баннер будет доступен пользователям в разделе партнерская программа с кодом для установки. В некоторых случаях необходимо дать права на запись папке /img/promo/" /></td>
		</tr>
		</table>
		<table width="600" align="center" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td align="right"><input type="submit" value="Сохранить!" /></td>
		</tr>
		</table>
		</form>
	</FIELDSET>
<hr>

<table width="98%" align="center">
<tr valign="top">
	<td width="50%">

	<FIELDSET>
	<LEGEND>Создание нового реферального уровня</LEGEND>
		<form action="?p=ref&act=addreflevel" method="post">
		<table align="center">
		<tr>
			<td><b>Уровень</b>:</td>
			<td align="right"><input type="text" size="35" name="level" /></td>
		</tr>
		<tr>
			<td><b>Процент</b>:</td>
			<td align="right"><input type="text" size="35" name="percent" /></td>
		</tr>
		<tr>
			<td><b>Минимальная сумма</b>:</td>
			<td align="right"><input type="text" size="35" name="minsum" /></td>
		</tr>
		<tr>
			<td><b>Процент при недоборе</b>:</td>
			<td align="right"><input type="text" size="35" name="nominsum" /></td>
		</tr>
		<tr>
			<td></td>
			<td align="right"><input type="submit" value=" Добавить! " /></td>
		</tr>
		</table>
		</form>
	</FIELDSET>
	</td><td>
	<FIELDSET>
	<LEGEND>Реферальные уровни:</LEGEND>

<table align="center">
<tr bgcolor="#dddddd" align="center">
	<td height="22"><b>Уровень</b></td>
	<td height="22"><b>Min сумма</b></td>
	<td><b>Процент</b></td>
	<td><b>% недобор</b></td>
	<td width="32"></td>
	<td width="32"></td>
</tr>
<?php
$query	= "SELECT * FROM reflevels ORDER BY id ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {

print '<form action="?p=ref&act=editlevel&id='.$row['id'].'" method="post"><tr>
	<td><input type="text" size="3" name="level" value="'.$row['id'].'" /></td>
	<td><input type="text" size="10" name="minsum" value="'.$row['minsum'].'" /></td>
	<td><input type="text" size="10" name="percent" value="'.$row['sum'].'" /></td>
	<td><input type="text" size="10" name="nominsum" value="'.$row['nominsum'].'" /></td>
	<td align="center"><input type="image" src="images/save.png" width="24" height="24" border="0" title="Сохранить!" /></td>
	<td align="center"><img style="cursor: pointer;" onclick="if(confirm(\'Вы действительно хотите удалить данный уровень?\')) top.location.href=\'?p=ref&act=dellevel&id='.$row['id'].'\';" src="images/delite.png" width="24" height="24" border="0" alt="Удалить" title="Удалить" /></td>
</tr><tr><td colspan="6" height="2" bgcolor="#dddddd"></td></tr></form>';

}
?>
</table>

	</FIELDSET>
	</td>
</tr>
</table>
<hr>

<table width="98%" align="center">
<tr valign="top">
	<td width="50%">

	<FIELDSET>
	<LEGEND>Создание бонусного уровня</LEGEND>
		<form action="?p=ref&act=addbonus" method="post">
		<table align="center">
		<tr>
			<td><b>Название</b>:</td>
			<td align="right"><input type="text" size="35" name="name" /></td>
		</tr>
		<tr>
			<td><b>Сумма реферальных</b>:</td>
			<td align="right"><input type="text" size="35" name="refsum" /></td>
		</tr>
		<tr>
			<td><b>Бонус</b>:</td>
			<td align="right"><input type="text" size="35" name="sum" /></td>
		</tr>
		<tr>
			<td></td>
			<td align="right"><input type="submit" value=" Добавить! " /></td>
		</tr>
		</table>
		</form>
	</FIELDSET>
	</td><td>
	<FIELDSET>
	<LEGEND>Бонусные уровни:</LEGEND>

<table align="center">
<tr bgcolor="#dddddd" align="center">
	<td height="22"><b>#</b></td>
	<td><b>Название</b></td>
	<td height="22"><b>Реф. сумма</b></td>
	<td height="22"><b>Бонус</b></td>
	<td width="32"></td>
	<td width="32"></td>
</tr>
<?php
$query	= "SELECT * FROM bonus ORDER BY id ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {

print '<form action="?p=ref&act=editbonus&id='.$row['id'].'" method="post"><tr>
	<td>'.$row['id'].'</td>
	<td><input type="text" size="15" name="name" value="'.$row['name'].'" /></td>
	<td><input type="text" size="8" name="refsum" value="'.$row['refsum'].'" /></td>
	<td><input type="text" size="8" name="sum" value="'.$row['sum'].'" /></td>
	<td align="center"><input type="image" src="images/save.png" width="24" height="24" border="0" title="Сохранить!" /></td>
	<td align="center"><img style="cursor: pointer;" onclick="if(confirm(\'Вы действительно хотите удалить данный уровень?\')) top.location.href=\'?p=ref&act=delbonus&id='.$row['id'].'\';" src="images/delite.png" width="24" height="24" border="0" alt="Удалить" title="Удалить" /></td>
</tr><tr><td colspan="6" height="2" bgcolor="#dddddd"></td></tr></form>';

}
?>
</table>

	</FIELDSET>
	</td>
</tr>
</table>
<hr>

	<FIELDSET>
	<LEGEND>Загруженые баннера:</LEGEND>
	<table width="100%">
<?php
$query	= "SELECT * FROM promo ORDER BY id ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {

print '<tr><td><img style="max-width: 800px;" src="/img/promo/'.$row['id'].'.'.$row['type'].'" border="0" alt=""></td>
<td align="center" valign="top" width="30"><img style="cursor: pointer;" onclick="if(confirm(\'Вы действительно хотите удалить данный баннер?\')) top.location.href=\'?p=ref&act=delbanner&id='.$row['id'].'\';" src="images/delite.png" width="24" height="24" border="0" alt="Удалить" title="Удалить" /></td></tr>
<tr><td><textarea style="width: 100%;" name="banner" rows="3" cols="50"><a href="http://'.$cfgURL.'/" target="_blank"><img src="http://'.$cfgURL.'/img/promo/'.$row['id'].'.'.$row['type'].'" border="0" alt="" /></a></textarea></td><td></td></tr>
<tr height="3" bgcolor="#eeeeee"><td></td><td></td></tr>';

}
?>
	</table>
	</FIELDSET>