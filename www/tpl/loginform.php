<?php
if(!$login) {
?>
	<table align="center" cellpadding="1" cellspacing="0">
	<form action="/login" method="post">
	<tr>
		<td style="padding-left: 5px;"><?php print $lang['login']; ?></td>
		<td style="padding-left: 5px;"><?php print $lang['password']; ?></td>
	</tr>
	<tr>
		<td><input type="text" name="user" size="11"></td>
		<td><input type="password" name="pass" size="11"></td>
	</tr>
	<tr>
		<td style="padding-left: 2px;"><a href="/registration"><?php print $lang['registration']; ?></a><br /><a href="/reminder"><?php print $lang['reminder']; ?></a></td>
		<td align="right"><input type="submit" value="<?php print $lang['enter']; ?>" /></td>
	</tr>
	</form>
	</table>

<?php
} else {
	$newmsgs = mysql_num_rows(mysql_query("SELECT `id` FROM `msgs` WHERE `to_id` = ".$user_id." AND `read` = 0"));

	// —четчик дохода
	$percent = 0;
	$money	 = 0;
	$i		 = 0;
	$zar	 = 0;
	$result	= mysql_query("SELECT `sum`, `plan`, `date` FROM `deposits` WHERE `user_id` = ".$user_id." AND status = 0 ORDER BY id ASC");
	while($row = mysql_fetch_array($result)) {

		$result2	= mysql_query("SELECT `percent` FROM `plans` WHERE `id` = ".$row['plan']." LIMIT 1");
		$row2		= mysql_fetch_array($result2);

		$percent  = $percent + $row2['percent'];
		$money	  = $money + $row['sum'];
		$lastdate = $row['date'];
		$i++;

	}

	if($i > 0) {

		$result	= mysql_query("SELECT `sum`, `date` FROM `stat` WHERE `user_id` = ".$user_id." AND `type` = 0 ORDER by id ASC");
		while($row = mysql_fetch_array($result)) {
			$zar = $zar + $row['sum'];
			$lastdate	= $row['date'];
		}

	$percent = $percent / $i / 86400;
	$zar = $zar + (time() - $lastdate) * $percent; 

	}

    print "<p align=\"center\"><b>".$lang['welcome']."</b> <b style=\"color: #2680bc;\">".$login."</b>!<br />";
	
	if(cfgSET('cfgBonusBal') == "on") {
		print "<div class=\"left\">".$lang['balance'].":</div><div class=\"right\"><b>".$balance."</b> ".$moneycurr."</div><div class=\"clear\"></div>
		<div class=\"left\"><font color=\"#666666\">".$lang['balance']." BONUS:</div><div class=\"right\"><b>".$bonusbalance."</b> ".$moneycurr."</font></div><div class=\"clear\"></div></p>";
	} else {
		print $lang['balance'].": <b>".$balance."</b> ".$moneycurr."</p>";
	}

	if($percent) {
	print '<div style="text-align: center;"><div style="width: 90px; border: 1px solid #e6433a; border-radius: 3px 0 0 3px; padding: 7px; color: #e6433a; margin-bottom: 15px; float: left;">'.$lang['yourdohod'].'</div><div id="dohodno"></div></div><script language="JavaScript">
	<!--
		CalcDohod('.$percent.', '.$zar.');
	//-->
	</script>';
	}
	// конец счетчика дохода

	print '<div class="clear"></div>';

	if($status == 1) {
		print '<div class="authmenu"><a href="/admin"><u>'.$lang['adminpanel'].'</u></a></div>';
	}
	print '<div class="authmenu"><a href="/enter">'.$lang['enterbalance'].'</a></div>';
	print '<div class="authmenu"><a href="/newdeposit">'.$lang['opendeposit'].'</a></div>';
	print '<div class="authmenu"><a href="/deposits">'.$lang['yourdeposits'].'</a></div>';
	if(cfgSET('cfgBonusOnOff') == "on" && cfgSET('cfgBonusBal') == "on") {
		print '<div class="authmenu"><a href="/bonus">'.$lang['enterbonus'].'</a></div>';
	}
	if(cfgSET('cfgTrans') == "on") {
		print '<div class="authmenu"><a href="/transfer">'.$lang['transfer'].'</a></div>';
	}
	print '<div class="authmenu"><a href="/withdrawal">'.$lang['withdrawal'].'</a></div>';
	print '<div class="authmenu"><a href="/msg">'.$lang['msgs'].'</a> <b>['.$newmsgs.']</b></div>';
	print '<div class="authmenu"><a href="/affiliate">'.$lang['affiliate'].'</a></div>';
	print '<div class="authmenu"><a href="/statistics">'.$lang['stat'].'</a></div>';
	print '<div class="authmenu"><a href="/profile">'.$lang['profile'].'</a></div>';
	print '<div class="authmenu"><a href="/logout.php">'.$lang['exit'].'</a></div>';

}
?>