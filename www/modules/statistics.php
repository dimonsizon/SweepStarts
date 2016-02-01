<?php
defined('ACCESS') or die();
if($login) {

	$dep	= 0.00;
	$sql	= mysql_query('SELECT `sum` FROM `stat` WHERE `user_id` = '.$user_id.' AND `type` = 3');
	while($a = mysql_fetch_array($sql)) {
		$dep = $dep + $a['sum'];
	}

	$part	= 0.00;
	$sql	= mysql_query('SELECT `sum` FROM `stat` WHERE `user_id` = '.$user_id.' AND `type` = 2');
	while($a = mysql_fetch_array($sql)) {
		$part = $part + $a['sum'];
	}
?>
<div class="left">
	<div class="left priceperctxt"><?php print $lang['zarabotano']; ?> %:</div><div class="right priceperc"><?php print $dep.' '.$moneycurr; ?></div>
</div>
<div class="right">
	<div class="left priceparttxt"><?php print $lang['partnerdoh']; ?>:</div><div class="right pricepart"><?php print $part.' '.$moneycurr; ?></div>
</div>

<table width="100%" class="table-content">
<tr align="center">
	<th height="25"><b><?php print $lang['date']; ?>:</b></th>
	<th><b><?php print $lang['sum']; ?>:</b></th>
	<th><b><?php print $lang['operation']; ?>:</b></th>
</tr>
<?php

	$query	= 'SELECT * FROM stat WHERE user_id = '.$user_id.' order by id DESC';
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	if($themes) {
		$i		= 1;
		$p		= intval($_GET['p']);
		$num	= 30;
		$total	= intval(($themes - 1) / $num) + 1;
		if(empty($p) or $p < 0) $p = 1;
		if($p > $total) $p = $total;
		$start = $p * $num - $num;
		$result = mysql_query($query." LIMIT ".$start.", ".$num);

		while ($a = mysql_fetch_array($result)) {

			if($i % 2) { $bg = ""; } else { $bg = " bgcolor=\"#eeeeee\""; }

				print "<tr ".$bg." align=\"center\">
				<td>".date("d.m.Y H:i", $a['date'])."</td>
				<td>".$a['sum']."</td>
				<td>";
				if($a['type'] == 1) {
					print '<span class="tool"><img src="/img/deposit_ico.png" width="16" height="16" alt="'.$lang['opendeposit'].'" /><span class="tip">'.$lang['opendeposit'].'</span></span>';
				} elseif($a['type'] == 2) {
					print '<span class="tool"><img src="/img/partner_ico.png" width="16" height="16" alt="'.$lang['partnerdoh'].'" /><span class="tip">'.$lang['partnerdoh'].'</span></span>';
				} else {
					print '<span class="tool"><img src="/img/percent_ico.png" width="16" height="16" alt="'.$lang['depositperc'].'" /><span class="tip">'.$lang['depositperc'].'</span></span>';
				}
				print "</td>
				</tr>";

			$i++;

		}

	} else {
		print "<tr bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\"><div class=\"warn\">".$lang['nodata']."!</div></td></tr>";
	}
	print "</table><hr />";

		if ($p) {
			if($p != 1) { $pervp = "<a href=\"/statistics/?p=1\">««</a>"; }
			if($p != $total) { $nextp = " <a href=\"/statistics/?p=".$total."\">»»</a>"; }
			if($p - 2 > 0) { $p2left = " <a href=\"/statistics/?p=". ($p - 2) ."\">". ($p - 2) ."</a> "; }
			if($p - 1 > 0) { $p1left = " <a href=\"/statistics/?p=". ($p - 1) ."\">". ($p - 1) ."</a> "; }
			if($p + 2 <= $total) { $p2right = " | <a href=\"/statistics/?p=". ($p + 2) ."\">". ($p + 2) ."</a> "; }
			if($p + 1 <= $total) { $p1right = " | <a href=\"/statistics/?p=". ($p + 1) ."\">". ($p + 1) ."</a> "; }
			print "<div class=\"pages\"><b>".$lang['pages'].":  </b>".$pervp.$p2left.$p1left." [<b>".$p."</b>] ".$p1right.$p2right.$nextp."</div>";
		}
	
} else {
	print "<p class=\"er\">".$lang['noauth']."</p>";
}
?>