<?php
if($login) {
defined('ACCESS') or die();
	print $body;

	$get_user_info = mysql_query("SELECT ref, ref_money, clx FROM users WHERE id = ".$user_id." LIMIT 1");
	$row = mysql_fetch_array($get_user_info);
	 $ref			= $row['ref'];
	 $ref_money		= $row['ref_money'];	

	if($ref) {

		$get_user_info2	= mysql_query("SELECT login FROM users WHERE id = ".$ref." LIMIT 1");
		$row2 			= mysql_fetch_array($get_user_info2);
		 $uplogin	= $row2['login'];

		print "<p>Upline: <b>".$uplogin."</b>; ".$lang['dohref'].": <b>$".$ref_money."</b></p>";

	}
?>
<p>
	<b><?php print $lang['yourpartnlink']; ?></b>
	(<?php print $lang['clx']; ?>: <?php print $row['clx']; ?>)
</p>
<div class="referal-link">
	<span>http://<?php print $cfgURL; ?>/?<?php print cfgSET('refname'); ?>=<?php print $login; ?></span>
</div>
<!--<input type="text" name="refurl" style="width: 100%;" value="http://<?php print $cfgURL; ?>/?<?php print cfgSET('refname'); ?>=<?php print $login; ?>" readonly />-->




	<table width="100%">
		<?php
		$query	= "SELECT * FROM promo ORDER BY id ASC";
		$result	= mysql_query($query);
		while($row = mysql_fetch_array($result)) {

		print '<tr>
			<td><img style="max-width: 800px;" src="/img/promo/'.$row['id'].'.'.$row['type'].'" border="0" alt=""></td>
		</tr>
		<tr>
			<td><textarea style="width: 100%;" name="banner" rows="3" cols="50"><a href="http://'.$cfgURL.'/?'.cfgSET('refname').'='.$login.'" target="_blank"><img src="http://'.$cfgURL.'/img/promo/'.$row['id'].'.'.$row['type'].'" border="0" alt="" /></a></textarea></td>
		</tr>';

		}
		?>
	</table>

<div class="partner-list">
	&nbsp;<b><?php print $lang['youref']; ?>:</b>
	<table class="table-content">

		<tr>
			<th width="45"><b>#</b></th>
			<th>Login:</th>
			<th width="140"><?php print $lang['dohod']; ?> (<?php print $moneycurr; ?>):</th>
		</tr>
		<?php

		function PrintRef($refid, $i, $c) {

			$sql	= 'SELECT id, login, ref_money FROM users WHERE ref = '.$refid;
			$rs		= mysql_query($sql);
				$n 	= 1;
				while($a = mysql_fetch_array($rs)) {

					$dep = mysql_num_rows(mysql_query("SELECT id FROM deposits WHERE user_id = ".$a['id']." AND status = 0 LIMIT 1"));

					if($i == 1) {

						print "<tr bgcolor=\"#ffffff\" align=\"center\">
							<td>".$n."</td>
							<td align=\"left\">".$a['login']."</font></td>
							<td>".$a['ref_money']; 
								if($dep) { print " <font color=\"green\">активен</font>"; } else { print " <font color=\"orange\">не активен</font>"; }
							print "</td>
						</tr>";

						if($i <= $c) {
							PrintRef($a['id'], intval($i + 1), $c);
						}

					} elseif($c >= $i) {

						print "<tr bgcolor=\"#ffffff\" align=\"center\">
							<td></td>
							<td align=\"left\" style=\"padding-left: ".$i."0px;\">
								<font color=\"#999999\">» ".$a['login']."</font>
							</td>
							<td>-"; 
								if($dep) { print " <font color=\"green\">активен</font>"; } else { print " <font color=\"orange\">не активен</font>"; }
							print "</td>
						</tr>";

						if($i <= $c) {
							PrintRef($a['id'], intval($i + 1), $c);
						}

					}
				$n++;
				}
				
		}

			$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

			PrintRef($user_id, 1, $countlvl);

			$sql	= 'SELECT login, ref_money FROM users WHERE ref = '.$user_id;
			$rs		= mysql_query($sql);

			if(mysql_num_rows($rs)) {

				$m = 0;
				while($a = mysql_fetch_array($rs)) {
					$m = $m + $a['ref_money'];
				}

				print "<tr align=\"center\">
					<td align=\"right\" colspan=\"2\"><b>".$lang['total'].":</b></td>
					<td><b>".sprintf("%01.2f", $m)."</b></td>
				</tr>";

			} else {
				print "<tr bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\"><div class=\"warn\">".$lang['nodata']."!</div></td></tr>";
			}

		print '</table>';

		} else {
			print '<p class="er">'.$lang['noauth'].'</p>';
		}
		?>
</div>