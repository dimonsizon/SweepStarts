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
<div>
	<p>
		<?php print $lang['zarabotano']; ?> %:
		<b><?php print $dep.' '.$moneycurr; ?></b>
	</p>
</div>
<div>
	<p>
		<?php print $lang['partnerdoh']; ?>:
		<b><?php print $part.' '.$moneycurr; ?></b>
	</p>
</div>

<table class="table-content statistic">
<tr>
	<th><?php print $lang['date']; ?>:</th>
	<th><?php print $lang['sum']; ?>:</th>
	<th class="text-center" width="60"><?php print $lang['operation']; ?>:</th>
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

				print "<tr ".$bg.">
				<td>".date("d.m.Y H:i", $a['date'])."</td>
				<td>".$a['sum']."</td>
				<td class=\"status text-center\">";
				if($a['type'] == 1) {
					print '<span class="tool"><i class="fa fa-plus text-success"></i><span class="tip">'.$lang['opendeposit'].'</span></span>';
				} elseif($a['type'] == 2) {
					print '<span class="tool"><i class="fa fa-users text-blue"></i><span class="tip">'.$lang['partnerdoh'].'</span></span>';
				} else {
					print '<span class="tool"><i class="fa fa-percent text-black"></i><span class="tip">'.$lang['depositperc'].'</span></span>';
				}
				print "</td>
				</tr>";

			$i++;

		}

	} else {
		print "<tr bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\"><div class=\"warn\">".$lang['nodata']."!</div></td></tr>";
	}
	print "</table>";

		if ($p) {
			if($p != 1) { $pervp = "<a href=\"/statistics/?p=1\"><i class=\"fa fa-angle-left\"></i></a>"; }
			if($p != $total) { $nextp = " <a href=\"/statistics/?p=".$total."\"><i class=\"fa fa-angle-right\"></i></a>"; }
			if($p - 2 > 0) { $p2left = " <a href=\"/statistics/?p=". ($p - 2) ."\">". ($p - 2) ."</a> "; }
			if($p - 1 > 0) { $p1left = " <a href=\"/statistics/?p=". ($p - 1) ."\">". ($p - 1) ."</a> "; }
			if($p + 2 <= $total) { $p2right = " <a href=\"/statistics/?p=". ($p + 2) ."\">". ($p + 2) ."</a> "; }
			if($p + 1 <= $total) { $p1right = " <a href=\"/statistics/?p=". ($p + 1) ."\">". ($p + 1) ."</a> "; }
			if ($total != 1) {
				print "<div class=\"pages\">".$pervp.$p2left.$p1left." <b>".$p."</b> ".$p1right.$p2right.$nextp."</div>";			
			}
		}	
	
} else {
	print "<p class=\"er\">".$lang['noauth']."</p>";
}
?>