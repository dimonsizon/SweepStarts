<?php
defined('ACCESS') or die();

$action = $_GET['action'];
if($action == "addip" && $_POST['ip']) {
	$ip			= htmlspecialchars($_POST['ip'], ENT_QUOTES, '');
	$comments	= htmlspecialchars($_POST['comments'], ENT_QUOTES, '');
	mysql_query("INSERT INTO `blacklist_ip` (`ip`, `comment`) values ('".$ip."', '".$comments."')");
	print "<p class=\"erok\">IP <u>".$ip."</u> добавлен в чёрный список!</p>";

} elseif($action == "delip") {
	$id = intval($_GET['id']);
	mysql_query("DELETE FROM `blacklist_ip` WHERE id = ".$id." LIMIT 1");
	print "<p class=\"erok\">IP убран из чёрного списка!</p>";
}
?>
<form action="?p=blacklist&action=addip" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px;">
<LEGEND><b>Добавить IP в чёрный список:</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td width="90"><strong>IP:</strong></td>
		<td><input style="width: 99%;" type="text" name="ip" size="90" /></td>
		<td width="32" rowspan="2" valign="bottom"><input type="image" src="images/save.png" width="28" height="28" border="0" title="Сохранить!" /></td>
	</tr>
	<tr>
		<td><strong>Причина:</strong></td>
		<td><input style="width: 99%;" type="text" name="comments" size="90" /></td>
	</tr>
</table>
</FIELDSET>
</form>
<hr size="2" />
<table border="0" align="center" width="100%" cellpadding="1" cellspacing="1">
<?php
$result	= mysql_query("SELECT id, ip, comment FROM blacklist_ip GROUP BY id order by id DESC");
while($row = mysql_fetch_array($result)) {
$id		= $row['id'];
$ip		= $row['ip'];

print "
<tr>
	<td><b>".$ip."</b><br /><small>".$row['comment']."</small></td>
	<td width=\"20\"><a style=\"cursor: pointer;\" onclick=\"if(confirm('Вы действительно хотите удалить данный IP с чёрного списка?')) top.location.href='?p=blacklist&id=".$id."&action=delip';\"><img src=\"images/delite.png\" width=\"24\" height=\"24\" border=\"0\" alt=\"Удалить\" /></a></td>
</tr>
<tr>
	<td colspan=\"4\" height=\"1\" bgcolor=\"#cccccc\"></td>
</tr>";
}
?>
</table>