<?php
$result2	= mysql_query("SELECT `currency` FROM `users` WHERE `id` = ".$user_id." LIMIT 1");
$row		= mysql_fetch_array($result2);
$mycurrency = $row['currency'];

function mycurrency($id) {

	$get_conf	= mysql_query("SELECT `style` FROM `currency` WHERE `id` = ".$id." LIMIT 1");
	$row		= mysql_fetch_array($get_conf);
	$currency	= $row['style'];

	return $currency;
}

$mycur = mycurrency($mycurrency);

if(!$login) {
?>
	<!--<table align="center" cellpadding="1" cellspacing="0">
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
	</table>-->
	<div class="form-container">
		<form action="/login" method="post">
			<div class="form-field">
				<label><?php print $lang['login']; ?></label>
				<input type="text" name="user">
			</div>
			<div class="form-field">
				<label><?php print $lang['password']; ?></label>
				<input type="password" name="pass">
			</div>
			<div class="form-buttons">
				<input type="submit" value="<?php print $lang['enter']; ?>" />
				<a class="button" href="/registration"><?php print $lang['registration']; ?></a>
				<a class="btn-link" href="/reminder"><?php print $lang['reminder']; ?></a>
			</div>
		</form>
	</div>
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
	
	//это вывод логина и баланса http://grab.by/NMqo
    //print "<p align=\"center\"><b>".$lang['welcome']."</b> <b style=\"color: #2680bc;\">".$login."</b>!<br />";
	//
	//if(cfgSET('cfgBonusBal') == "on") {
	//	print "<div class=\"left\">".$lang['balance'].":</div><div class=\"right\"><b>".$balance."</b> ".$moneycurr."</div><div class=\"clear\"></div>
	//	<div class=\"left\"><font color=\"#666666\">".$lang['balance']." BONUS:</div><div class=\"right\"><b>".$bonusbalance."</b> ".$moneycurr."</font></div><div class=\"clear\"></div></p>";
	//} else {
	//	print $lang['balance'].": <b>".$balance."</b> ".$mycur."</p>";
	//}

	//это странный автоматический счетчик, который был слева http://grab.by/NKJ0
	//if($percent) {
	//print '<div style="text-align: center;"><div style="width: 90px; border: 1px solid #e6433a; border-radius: 3px 0 0 3px; padding: 7px; color: #e6433a; margin-bottom: 15px; float: left;">'.$lang['yourdohod'].'</div><div id="dohodno"></div></div><script language="JavaScript">
	//<!--
	//	CalcDohod('.$percent.', '.$zar.');
	////-->
	//</script>';
	//}
	// конец счетчика дохода

	print '<div class="clear"></div>';

	print '<nav class="user-menu"><ul>';
	
		if($status == 1) {
			print '<li class="menu-item"><a href="/admin"><i class="fa fa-user-secret"></i><u>'.$lang['adminpanel'].'</u></a></li>';
		}
		print '<li class="menu-item"><a href="/enter"><i class="fa fa-arrow-down"></i>'.$lang['enterbalance'].'</a></li>';
		print '<li class="menu-item"><a href="/newdeposit"><i class="fa fa-percent"></i>'.$lang['opendeposit'].'</a></li>';
		print '<li class="menu-item"><a href="/deposits"><i class="fa fa-pie-chart"></i>'.$lang['yourdeposits'].'</a></li>';
		if(cfgSET('cfgBonusOnOff') == "on" && cfgSET('cfgBonusBal') == "on") {
			print '<li class="menu-item"><a href="/bonus">'.$lang['enterbonus'].'</a></li>';
		}
		if(cfgSET('cfgTrans') == "on") {
			print '<li class="menu-item"><a href="/transfer">'.$lang['transfer'].'</a></li>';
		}
		print '<li class="menu-item"><a href="/withdrawal"><i class="fa fa-arrow-up"></i>'.$lang['withdrawal'].'</a></li>';
		print '<li class="menu-item"><a href="/msg"><i class="fa fa-comments-o"></i>'.$lang['msgs'].'</a> <b>'; 
			if($newmsgs!=0) { 
				print '+'.$newmsgs;
			} print '</b></li>';
		print '<li class="menu-item"><a href="/affiliate"><i class="fa fa-users"></i>'.$lang['affiliate'].'</a></li>';
		print '<li class="menu-item"><a href="/statistics"><i class="fa fa-bar-chart"></i>'.$lang['stat'].'</a></li>';
		print '<li class="menu-item"><a href="/profile"><i class="fa fa-user"></i>'.$lang['profile'].'</a></li>';
		print '<li class="menu-item"><a href="/logout.php"><i class="fa fa-sign-out"></i>'.$lang['exit'].'</a></li>';
	print '</ul></nav>';
}
?>