<?php
defined('ACCESS') or die();
$cusers		= mysql_num_rows(mysql_query("SELECT `id` FROM `users`")) + cfgSET('fakeusers');
$cwm		= mysql_num_rows(mysql_query("SELECT `id` FROM `users` WHERE `balance` != 0")) + cfgSET('fakeactiveusers');

$money	= cfgSET('fakewithdraws');
$query	= "SELECT `sum` FROM `output` WHERE `status` = 2";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$money = $money + $row['sum'];
}

$depmoney	= cfgSET('fakedeposits');
$query	= "SELECT `sum` FROM `deposits` WHERE `status` = 0";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$depmoney = $depmoney + $row['sum'];
}
?>
<table width="100%">
	<tr height="25">
		<td><?php print $lang['workfrom']; ?>:</td>
		<td align="right"><font color="#2680bc"><?php print date("d.m.Y", cfgSET('datestart')); ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['users']; ?>:</td>
		<td align="right"><font color="#2680bc"><?php print $cusers; ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['activeusers']; ?>:</td>
		<td align="right"><font color="#2680bc"><?php print $cwm; ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['onlineusers']; ?>:</td>
		<td align="right"><font color="#2680bc"><?php print intval(mysql_num_rows(mysql_query("SELECT `id` FROM `users` WHERE go_time > ".intval(time() - 1200))) + cfgSET('fakeonline')); ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['deposits']; ?>:</td>
		<td align="right"><font color="#2680bc"><?php print $depmoney.' '.$moneycurr; ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['payouts']; ?>:</td>
		<td align="right"><font color="#2680bc"><?php print $money.' '.$moneycurr; ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['newuser']; ?>:</td>
<?php
	$nu	= mysql_fetch_array(mysql_query("SELECT login FROM users ORDER BY id DESC LIMIT 1"));
?>
		<td align="right"><font color="#2680bc">[<?php print $nu['login']; ?>]</font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['newdeposit']; ?>:</td>
<?php
	$nd	= mysql_fetch_array(mysql_query("SELECT * FROM deposits ORDER BY id DESC LIMIT 1"));
?>
		<td align="right"><font color="#2680bc"><?php print $nd['sum'].' '.$moneycurr; ?></font></td>
	</tr>
	<tr height="25">
		<td><?php print $lang['newoutput']; ?>:</td>
<?php
	$no	= mysql_fetch_array(mysql_query("SELECT * FROM output ORDER BY id DESC LIMIT 1"));
?>
		<td align="right"><font color="#2680bc"><?php print $no['sum'].' '.$moneycurr; ?></font></td>
	</tr>
</table>