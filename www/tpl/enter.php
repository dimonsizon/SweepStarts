<?php
defined('ACCESS') or die();
?>
<div class="enter-balance">
	<form action="/enter/?action=save" method="post">
		<div class="form-container">
			<div class="form-field">
				<label><?php print $lang['sum']; ?>:</label>
				<input class="text-input" type='text' name='sum' value='' size="30" maxlength="7" />
			</div>
			<div class="form-field">
				<label><?php print $lang['paysystem']; ?>:</label>
				<select name="ps">
					<?php
						if($cfgPerfect) { 
							print '<option value="1">PerfectMoney</option>';
						} 
						if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) { 
							print '<option value="2">PAYEER.com</option>';
						} 

						$result	= mysql_query("SELECT * FROM `paysystems` WHERE id > 2 ORDER BY id ASC");
						while($row = mysql_fetch_array($result)) {
							print '<option value="'.$row['id'].'">'.$row['name'].'</option> ';
						}
					?>
				</select>
			</div>
			<div class="form-buttons">
				<input class="subm" type='submit' name='submit' value=' <?php print $lang['enterbalance']; ?> ' />
			</div>
		</div>
	</form>
	<h3><?php print $lang['history']; ?>:</h3>
<?php
	$num	= 20;
	$p		= intval($_GET['p']);
	$query	= "SELECT * FROM `enter` WHERE login = '".$login."'";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / 20) + 1;

	if(empty($p) or $p < 0) $p = 1;
	if($p > $total) $p = $total;
	$start = $p * $num - $num;
	$result = mysql_query($query." ORDER BY id DESC LIMIT ".$start.", 20");

	if(!$themes) {
		print "<p class=\"er\">".$lang['nodata']."</p>";
	} else {

		print "<table class=\"table-content\">
		<tr>
			<th>#</th>
			<th>".$lang['date']."</th>
			<th>".$lang['sum']."</th>
			<th>".$lang['schet']."</th>
			<th>".$lang['system']."</th>
			<th style=\"text-align: center\">".$lang['status']."</th>
		</tr>";

		$i = 1;
		$s = 0;
		while ($row = mysql_fetch_array($result)) {

		if($i % 2) { $bg = ""; } else { $bg = " bgcolor=\"#eeeeee\""; }

		print "<tr".$bg.">
			<td>".$row['id']."</td>
			<td>".date("d.m.Y H:i", $row['date'])."</td>
			<td>".$row['sum']." ".$moneycurr."</td>
			<td><b>".$row['purse']."</b></td>
			<td>".$row['paysys']."</td>
			<td class=\"status text-center\">";

				if($row['status'] == 0) {
					print '<span class="tool"><i class="fa fa-hourglass text-warning "><span class="tip">'.$lang['msg_07'].'</span></span>';
				} elseif($row['status'] == 1) {
					print '<span class="tool"><i class="fa fa-clock-o text-blue"></i><span class="tip">'.$lang['msg_03'].'</span></span>';
				} elseif($row['status'] == 2) {
					print '<span class="tool"><i class="fa fa-check text-success"></i><span class="tip">'.$lang['msg_04'].'</span></span>';
				} else {
					print '<span class="tool"><i class="fa fa-ban text-error"></i><span class="tip">'.$lang['msg_08'].'</span></span>';
				}

			print "</td>

		</tr>";

			$i++;
			$s = $s + $row['sum'];
		}

	print "</table>
	<p><b>".$lang['total']." внесено: <b>".$s."</b> ".$moneycurr."</b></p>";
	
	}

	if ($p) {
		if($p != 1) { $pervp = "<a href=\"/enter/?p=". ($p - 1) ."\"><i class=\"fa fa-angle-left\"></i></a>"; }
		if($p != $total) { $nextp = " <a href=\"/enter/?p=". ($p + 1) ."\"><i class=\"fa fa-angle-right\"></i></a>"; }
		if($p - 2 > 0) { $p2left = " <a href=\"/enter/?p=". ($p - 2) ."\">". ($p - 2) ."</a> "; }
		if($p - 1 > 0) { $p1left = " <a href=\"/enter/?p=". ($p - 1) ."\">". ($p - 1) ."</a> "; }
		if($p + 2 <= $total) { $p2right = " <a href=\"/enter/?p=". ($p + 2) ."\">". ($p + 2) ."</a> "; }
		if($p + 1 <= $total) { $p1right = " <a href=\"/enter/?p=". ($p + 1) ."\">". ($p + 1) ."</a> "; }
	}
	if ($total != 1) {
		print "<div class=\"pages\">".$pervp.$p2left.$p1left." <b>".$p."</b> ".$p1right.$p2right.$nextp."</div>";
	}
	

?>
</div>