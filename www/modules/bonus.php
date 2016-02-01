<?php
defined('ACCESS') or die();
if(cfgSET('cfgBonusOnOff') == "on" && cfgSET('cfgBonusBal') == "on" && $login) {

	if($_GET['act'] == "bon") {

		$sql	= 'SELECT * FROM bonuses WHERE user_id = '.$user_id.' order by id DESC LIMIT 1';
		$rs		= mysql_query($sql);

		$a = mysql_fetch_array($rs);

			if($a['date'] < (time() - (cfgSET('cfgBonusPeriod') * 60))) {

				$bonus = rand(cfgSET('cfgBonusMin'),cfgSET('cfgBonusMax')) / 100;

				$sql = "INSERT INTO bonuses (login, user_id, sum, date) VALUES ('".$login."', ".$user_id.", ".$bonus.", ".time().")";
				mysql_query($sql);
	
				mysql_query("UPDATE users SET bonus = bonus + ".$bonus." WHERE id = ".$user_id." LIMIT 1");

				print '<p class="erok">'.$lang['msg_17'].' '.$bonus.' '.$moneycurr.'</p>';


			} elseif($a['date'] == "") {
				$bonus = rand(cfgSET('cfgBonusMin'),cfgSET('cfgBonusMax')) / 100;

				$sql = "INSERT INTO bonusese (login, user_id, sum, date) VALUES ('".$login."', ".$user_id.", ".$bonus.", ".time().")";
				mysql_query($sql);
	
				mysql_query("UPDATE users SET bonus = bonus + ".$bonus." WHERE id = ".$user_id." LIMIT 1");

				print '<p class="erok">'.$lang['msg_17'].' '.$bonus.' '.$moneycurr.'</p>';

			} else {
				print '<p class="er">'.$lang['msg_18'].' '.date("d.m.Y â H:i:s", intval($a['date'] + (cfgSET('cfgBonusPeriod') * 60))).'</p>';
			}

	}

	$sql	= 'SELECT * FROM bonuses WHERE user_id = '.$user_id.' order by id DESC LIMIT 1';
	$rs		= mysql_query($sql);
	if(mysql_num_rows($rs)) {

		while($a = mysql_fetch_array($rs)) {
			if($a['date'] < (time() - intval(cfgSET('cfgBonusPeriod') * 60))) {
				print '<center><input style="width: 98%;" type="button" value="'.$lang['getbonus'].'" onclick="top.location.href=\'/bonus/?act=bon\'" /></center>';
			} else {
				print '<p class="warn">'.$lang['msg_18'].' '.date("d.m.Y â H:i:s", intval($a['date'] + (cfgSET('cfgBonusPeriod') * 60))).'</p>';
			}
		}

	} else {

		print '<center><input style="width: 98%;" type="button" value="'.$lang['getbonus'].'" onclick="top.location.href=\'/bonus/?act=bon\'" /></center>';

	}

print '<hr>';

print '<h3>'.$lang['lastbonuses'].':</h3>
<table class="table-content" width="100%" border="0">
<tr align="center">
	<th><b>'.$lang['date'].':</b></th>
	<th><b>'.$lang['login'].':</b></th>
	<th><b>'.$lang['sum'].':</b></th>
</tr>';

	$sql	= 'SELECT * FROM bonuses order by id DESC LIMIT 50';
	$rs		= mysql_query($sql);
	if(mysql_num_rows($rs)) {

		while($a = mysql_fetch_array($rs)) {
				print "<tr bgcolor=\"#ffffff\" align=\"center\">
				<td align=\"left\">".date("d.m.Y H:i", $a['date'])."</td>
				<td>".$a['login']."</td>
				<td>".$a['sum']." ".$moneycurr."</td>
				</tr>";
		}

	} else {
		print "<tr bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\">".$lang['nodata']."</td></tr>";
	}
print "</table>";

} else {
	print '<p class="er">'.$lang['noauth'].'</p>';
}
?>