<?php
defined('ACCESS') or die();
?>
<form action="/admin/" method="get">
<FIELDSET style="border: solid #666666 1px; padding: 10px;">
<LEGEND><b>Показать лог авторизации</b></LEGEND>
<input type="hidden" name="p" value="logip" />
<table width="100%" border="0">
	<tr>
		<td width="60"><strong>USER ID:</strong></td>
		<td><input class="inp" style="width: 99%;" type="text" name="id" size="93" /></td>
		<td width="32" align="center"><input type="image" src="images/search.png" width="24" height="24" border="0" title="Поиск!" /></td>
	</tr>
</table>
</FIELDSET>
</form>

<hr />

<?php
if($_GET['id']) {
?>

<table class="tbl">
<tr>
	<th width="50%"><strong>Дата</strong></th>
	<th><strong>IP</strong></th>
	<th><strong>Страна</strong></th>
	<th><strong>Логин</strong></th>
</tr>
<?php
$sql	 = "SELECT * FROM logip WHERE user_id = ".intval($_GET['id'])." ORDER BY id DESC";
$rs		 = mysql_query($sql);
while($a = mysql_fetch_array($rs)) {

$sql2	= 'SELECT `login` FROM `users` WHERE id = '.$a['user_id'].' LIMIT 1';
$rs_u	= mysql_query($sql2);
$a2		= mysql_fetch_array($rs_u);

$country = getCOUNTRY($a['ip']);

print "<tr>
	<td>".date("d.m.Y H:i:s", $a['date'])."</td>
	<td>".$a['ip']."</td>
	<td><img src=\"/img/flags/".$country.".gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"".$country."\" title=\"".$country."\" /> ".$country."</td>
	<td><a href=\"?p=edit_user&id=".$a['user_id']."\">".$a2['login']."</a></td>
</tr>";
}

?>
</table>

<?php
} else {
?>
<table class="tbl">
<tr>
	<th width="50%"><strong>Дата</strong></th>
	<th><strong>IP</strong></th>
	<th><strong>Страна</strong></th>
	<th><strong>Логин</strong></th>
</tr>
<?php
$sql	 = "SELECT * FROM logip ORDER BY id DESC LIMIT 100";
$rs		 = mysql_query($sql);
while($a = mysql_fetch_array($rs)) {

$sql2	= 'SELECT `login` FROM `users` WHERE id = '.$a['user_id'].' LIMIT 1';
$rs_u	= mysql_query($sql2);
$a2		= mysql_fetch_array($rs_u);

$country = getCOUNTRY($a['ip']);

print "<tr>
	<td>".date("d.m.Y H:i:s", $a['date'])."</td>
	<td>".$a['ip']."</td>
	<td><img src=\"/img/flags/".$country.".gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"".$country."\" title=\"".$country."\" /> ".$country."</td>
	<td><a href=\"?p=edit_user&id=".$a['user_id']."\">".$a2['login']."</a></td>
</tr>";
}

?>
</table>
<?php } ?>