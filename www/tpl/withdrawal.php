<?php
defined('ACCESS') or die();
?>
<script language="JavaScript">
<!--
	function CheBal() {

		document.getElementById("sum").value = "<?php print $r['balance']; ?>"

		if(document.getElementById('ps').value == 1) {
			document.getElementById("purse").value = '<?php if($r['pm']) { print $r['pm']; } else { print $lang['msg_16']; } ?>';
			document.getElementById("purse").disabled = true;
		} else if(document.getElementById('ps').value == 2) {
			document.getElementById("purse").value = '<?php if($r['pe']) { print $r['pe']; } else { print $lang['msg_16']; } ?>';
			document.getElementById("purse").disabled = true;
		} else if(document.getElementById('ps').value == 3) { //bank card
			document.getElementById("purse").value = '���������� �����';
			document.getElementById("purse").disabled = true;
		}else {
			document.getElementById("purse").value = '';
			document.getElementById("purse").disabled = false;
		}
	}
//-->
</script>

<form action="/withdrawal/?action=save" method="post">
	<div class="form-container">
		<div class="form-field">
			<label><?php print $lang['sum']; ?>:</label>
			<input id="sum" class="text-input" type='text' name='sum' value='<?php print $r['balance']; ?>' size="30" maxlength="7" />
		</div>
		<div class="form-field">
			<label><?php print $lang['paysystem']; ?>:</label>
			<select id="ps" onChange="CheBal();" name="ps">
				<?php
				if($r['pm']) {
					print '<option value="1">PerfectMoney</option>';
				}

				$result	= mysql_query("SELECT * FROM `paysystems` WHERE id != 1 ORDER BY id ASC");
				while($row = mysql_fetch_array($result)) {
					if($row['id'] == 3) { //only bank card
						print '<option value="'.$row['id'].'">'.$row['name'].'</option> ';
					}
				}
				?>
			</select>
		</div>
		<div class="form-field">
			<label><?php print $lang['schet']; ?>:</label>
			<input id="purse" class="text-input" type='text' name='purse' value='' size="30" maxlength="30" />
		</div>
		<div class="form-buttons">
			<input class="subm" type='submit' name='submit' value=' <?php print $lang['send']; ?> ' />
		</div>
	</div>
</form>

<script language="JavaScript">
<!--
	CheBal();
//-->
</script>
<h3><?php print $lang['logoutput']; ?>:</h3>
<?php
	$p		= intval($_GET['p']);
	$num	= 20;
	$query	= "SELECT * FROM `output` WHERE login = '".$login."'";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;

	if(empty($p) or $p < 0) $p = 1;
	if($p > $total) $p = $total;
	$start = $p * $num - $num;
	$result = mysql_query($query." ORDER BY id DESC LIMIT ".$start.", ".$num);

	if(!$themes) {
		print "<p class=\"er\">".$lang['nodata']."</p>";
	} else {

		print "<table width=\"100%\" class=\"table-content\"><tr align=\"center\"><th style=\"padding: 3px;\"><b>#</b></th><th width=\"100\"><b>".$lang['date']."</b></th><th><b>".$lang['sum']."</b></th><th><b>".$lang['schet']."</b></th><th><b>".$lang['system']."</b></th><th><b>".$lang['status']."</b></th></tr>";

		$i = 1;
		$s = 0;
		while ($row = mysql_fetch_array($result)) {

		if($i % 2) { $bg = ""; } else { $bg = " bgcolor=\"#eeeeee\""; }

		$get_ps	= mysql_query("SELECT `name` FROM `paysystems` WHERE `id` = ".intval($row['paysys'])." LIMIT 1");
		$rowps	= mysql_fetch_array($get_ps);

		print "<tr".$bg." align=\"center\">
		<td style=\"padding: 3px;\">".$row['id']."</td>
		<td>".date("d.m.Y H:i", $row['date'])."</td>
		<td>".$row['sum']." ".$moneycurr."</td>
		<td><b>".$row['purse']."</b></td>
		<td>".$rowps['name']."</td>
		<td>";

		if($row['status'] == 0) {
			print '<span class="tool"><img src="/img/wait_ico.png" width="16" height="16" alt="" /><span class="tip">'.$lang['msg_03'].'</span></span> <span class="tool"><a href="/withdrawal/?cancel='.$row['id'].'"><img src="/img/no_ico.png" width="16" height="16" border="0" alt="" /></a><span class="tip">'.$lang['cancel'].'</span></span>';
		} elseif($row['status'] == 2) {
			print '<span class="tool"><img src="/img/yes_ico.png" width="16" height="16" alt="" /><span class="tip">'.$lang['msg_04'].'</span></span>';
		} elseif($row['status'] == 6) {
			print '<span class="tool"><img src="/img/cancel_ico.png" width="16" height="16" alt="" /><span class="tip">'.$lang['msg_05'].'</span></span>';
		} else {
			print '<span class="tool"><img src="/img/cancel_ico.png" width="16" height="16" alt="" /><span class="tip">'.$lang['msg_06'].'</span></span>';
		}

		print "</td>

		</tr>";

			$i++;
			$s = $s + $row['sum'];
		}

		print "<tr><td class=\"tblbord\"></td><td align=\"right\" class=\"tblbord\"><b>".$lang['total'].":</b></td><td align=\"center\" class=\"tblbord\"><b>".$s."</b> ".$moneycurr."</td><td class=\"tblbord\"></td><td class=\"tblbord\"></td><td class=\"tblbord\"></td></tr></table>";

	}

	if ($p) {
		if($p != 1) { $pervp = "<a href=\"/withdrawal/?p=". ($p - 1) ."\">��</a>"; }
		if($p != $total) { $nextp = " <a href=\"/withdrawal/?p=". ($p + 1) ."\">��</a>"; }
		if($p - 2 > 0) { $p2left = " <a href=\"/withdrawal/?p=". ($p - 2) ."\">". ($p - 2) ."</a> "; }
		if($p - 1 > 0) { $p1left = " <a href=\"/withdrawal/?p=". ($p - 1) ."\">". ($p - 1) ."</a> "; }
		if($p + 2 <= $total) { $p2right = " <a href=\"/withdrawal/?p=". ($p + 2) ."\">". ($p + 2) ."</a> "; }
		if($p + 1 <= $total) { $p1right = " <a href=\"/withdrawal/?p=". ($p + 1) ."\">". ($p + 1) ."</a> "; }
	}
	print "<div class=\"pages\"><b>".$lang['pages'].":  </b>".$pervp.$p2left.$p1left." <b>".$p."</b> ".$p1right.$p2right.$nextp."</div>";
?>