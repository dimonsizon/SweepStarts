<script language="javascript" type="text/javascript" src="files/alt.js"></script>
<?php
defined('ACCESS') or die();

if($_GET['action'] == "add_depo") {

	$name	= htmlspecialchars($_POST['name'], ENT_QUOTES, '');
	$sum	= sprintf("%01.2f", $_POST['sum']);
	$plan	= intval($_POST['plan']);
	$paysys	= intval($_POST['paysys']);

	if(!$name || !$sum || !$plan || !$paysys) {
		print '<p class="er">��������� ��� ����</p>';
	} elseif(!mysql_num_rows(mysql_query("SELECT * FROM users WHERE login = '".$name."' LIMIT 1"))) {
		print '<p class="er">������������ � ����� ������� �� ������</p>';
	} else {
		$query	 = "SELECT id FROM users WHERE login = '".$name."' LIMIT 1";
		$result	 = mysql_query($query);
		$row	 = mysql_fetch_array($result);
		$name_id = $row['id'];


			$result	= mysql_query("SELECT * FROM plans WHERE id = ".$plan." LIMIT 1");
			$row2	= mysql_fetch_array($result);

			if(cfgSET('datestart') <= time()) {
				$lastdate = time();
				if($row2['period'] == 1) {
					$nextdate = $lastdate + 86400;
				} elseif($row2['period'] == 2) {
					$nextdate = $lastdate + 604800;
				} elseif($row2['period'] == 3) {
					$nextdate = $lastdate + 2592000;
				} elseif($row2['period'] == 4) {
					$nextdate = $lastdate + 3600;
				}
			} else {
				$lastdate = time();
				if($row2['period'] == 1) {
					$nextdate = cfgSET('datestart') + 86400;
				} elseif($row2['period'] == 2) {
					$nextdate = cfgSET('datestart') + 604800;
				} elseif($row2['period'] == 3) {
					$nextdate = cfgSET('datestart') + 2592000;
				} elseif($row2['period'] == 4) {
					$nextdate = cfgSET('datestart') + 3600;
				}
			}

			$sql = "INSERT INTO `deposits` (username, user_id, date, plan, sum, paysys, lastdate, nextdate) VALUES ('".$name."', ".$name_id.", ".time().", ".$plan.", ".$sum.", ".$paysys.", ".$lastdate.", ".$nextdate.")";
			mysql_query($sql);

		print '<p class="erok">������� �������� ������������</p>';
	}

}

if($_GET['action'] == "addpercent") {

	$percent	= sprintf("%01.2f", $_POST['percent']);
	$plan		= intval($_POST['plan']);

	if($percent) {

		if($plan == 999) {

			$query	= "SELECT * FROM `users` WHERE balance > 0";
			$result	= mysql_query($query);
			while($row = mysql_fetch_array($result)) {

				$p = sprintf("%01.2f", $row['balance'] / 100 * $percent);			// ����������
				// ��������� �� ������
				mysql_query('UPDATE users SET balance = balance + '.$p.' WHERE id = '.$row['id'].' LIMIT 1');

				// ������ � ����������
				mysql_query('INSERT INTO `stat` (`user_id`, `date`, `plan`, `sum`, `paysys`, `type`) VALUES ('.$row['id'].', '.time().', 0, '.$p.', 0, 3)');

				// ��������� ����� "�������" ���������
				$uid	= $row['id'];
				$sqlss	= mysql_query("SELECT `ref` FROM `users` WHERE id = ".$row['id']." LIMIT 1");
				$rowss	= mysql_fetch_array($sqlss);
				$uref	= $rowss['ref'];

				if($uref && cfgSET('cfgRefPerc') == 2) {

					// ������������ ���-�� �������
					$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

					if($countlvl) {
						$i		= 0;
						$query21	= "SELECT * FROM reflevels ORDER BY id ASC";
						$result21	= mysql_query($query21);
						while($row21 = mysql_fetch_array($result21)) {
							if($i < $countlvl) {
								$lvlperc = $row21['sum'];		// ������� ������
								$ps		 = sprintf("%01.2f", $p / 100 * $lvlperc); // ����� �������

								if($uref) {

									// ������� ���� �� �������������� ������� � ������� ��������
									$get_refp	= mysql_query("SELECT ref_percent FROM users WHERE id = ".intval($uref)." LIMIT 1");
									$rowrefp	= mysql_fetch_array($get_refp);
									$urefp		= $rowrefp['ref_percent'];

									if($i == 0 && $urefp >= 0.01) {
										$ps = sprintf("%01.2f", $p / 100 * $urefp); // ����� �������
									}

									mysql_query('UPDATE users SET balance = balance + '.$ps.', reftop = reftop + '.$ps.' WHERE id = '.$uref.' LIMIT 1');
									mysql_query('UPDATE users SET ref_money = ref_money + '.$ps.' WHERE id = '.$uid.' LIMIT 1');
									mysql_query('INSERT INTO `stat` (`user_id`, `date`, `plan`, `sum`, `paysys`, `type`) VALUES ('.$uref.', '.time().', 0, '.$ps.', 0, 2)');

								
									// �������� ������ ���������� ������������

									$get_ref	= mysql_query("SELECT id, ref FROM users WHERE id = ".intval($uref)." LIMIT 1");
									$rowref		= mysql_fetch_array($get_ref);
									$uref		= $rowref['ref'];
									$uid		= $rowref['id'];

								}

							}
							$i++;
						}
					}

				}
				// ��������� � ����������

			}

		} else {

			if($plan == 0) {
				$wh = "status = 0";
			} else {
				$wh = "status = 0 AND plan = ".$plan;
			}

			$query	= "SELECT * FROM deposits WHERE ".$wh;
			$result	= mysql_query($query);
			while($row = mysql_fetch_array($result)) {

				if(cfgSET('cfgReInv') == "on") {
					$p			= sprintf("%01.2f", $row['sum'] / 100 * $percent);			// ����������
					$re			= sprintf("%01.2f", $p / 100 * $row['reinvest']);			// ����� ���������
					$p			= $p - $re;
					mysql_query('UPDATE `deposits` SET sum = sum + '.$re.' WHERE id = '.$row['id'].' LIMIT 1');
				} else {
					$p			= sprintf("%01.2f", $row['sum'] / 100 * $percent);			// ����������
				}

				// ��������� �� ������
				mysql_query('UPDATE users SET balance = balance + '.$p.' WHERE id = '.$row['user_id'].' LIMIT 1');

				// ������ � ����������
				mysql_query('INSERT INTO `stat` (`user_id`, `date`, `plan`, `sum`, `paysys`, `type`) VALUES ('.$row['user_id'].', '.time().', '.$row['plan'].', '.$p.', '.$row['paysys'].', 3)');

				// ��������� ����� "�������" ���������
				$uid	= $row['user_id'];
				$sqlss	= mysql_query("SELECT `ref` FROM `users` WHERE id = ".$row['user_id']." LIMIT 1");
				$rowss	= mysql_fetch_array($sqlss);
				$uref	= $rowss['ref'];

				if($uref && cfgSET('cfgRefPerc') == 2) {

					// ������������ ���-�� �������
					$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

					if($countlvl) {
						$i		= 0;
						$query21	= "SELECT * FROM reflevels ORDER BY id ASC";
						$result21	= mysql_query($query21);
						while($row21 = mysql_fetch_array($result21)) {
							if($i < $countlvl) {
								$lvlperc = $row21['sum'];		// ������� ������
								$ps		 = sprintf("%01.2f", $p / 100 * $lvlperc); // ����� �������

								if($uref) {

									// ������� ���� �� �������������� ������� � ������� ��������
									$get_refp	= mysql_query("SELECT ref_percent FROM users WHERE id = ".intval($uref)." LIMIT 1");
									$rowrefp	= mysql_fetch_array($get_refp);
									$urefp		= $rowrefp['ref_percent'];

									if($i == 0 && $urefp >= 0.01) {
										$ps = sprintf("%01.2f", $p / 100 * $urefp); // ����� �������
									}

									mysql_query('UPDATE users SET balance = balance + '.$ps.', reftop = reftop + '.$ps.' WHERE id = '.$uref.' LIMIT 1');
									mysql_query('UPDATE users SET ref_money = ref_money + '.$ps.' WHERE id = '.$uid.' LIMIT 1');
									mysql_query('INSERT INTO `stat` (`user_id`, `date`, `plan`, `sum`, `paysys`, `type`) VALUES ('.$uref.', '.time().', 0, '.$ps.', 0, 2)');

								
									// �������� ������ ���������� ������������

									$get_ref	= mysql_query("SELECT id, ref FROM users WHERE id = ".intval($uref)." LIMIT 1");
									$rowref		= mysql_fetch_array($get_ref);
									$uref		= $rowref['ref'];
									$uid		= $rowref['id'];

								}

							}
							$i++;
						}
					}

				}
				// ��������� � ����������

			}
		}
		print '<p class="erok">�������� ���� ���������! <b>�� ���������� ��������</b>!</p>';
	} else {
		print '<p class="er">������� ������� ����������</p>';
	}
}

$money = 0.00;
$query	= "SELECT `sum` FROM `deposits` WHERE status = 0";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$money = $money + $row['sum'];
}
?>
<center><b>����� �������� ��������� �� �����: <?php print sprintf("%01.2f", $money); ?></b></center>
<hr />
<table border="0" align="center" width="100%" cellpadding="1" cellspacing="1" class="tbl">
<colspan><div align="right" style="padding: 2px;">����������� ��: <a href="?p=deposits&sort=id">ID (����)</a> | <a href="?p=deposits&sort=sum">�����</a> | <a href="?p=deposits&sort=username">������</a></div></colspan>
	<tr>
		<th width="40"><b>ID</b></th>
  		<th><b>����</b></th>
		<th><b>�����</b></th>
		<th><b>�����</b></th>
		<th><b>�������� ����</b></th>
	</tr>
<?php
function users_list($pg, $num, $query) {

	$result = mysql_query($query);
	$themes = mysql_num_rows($result);

	if (!$themes) {
		print '<tr><td colspan="5" align="center"><div class="warn"><b>��������� ���� ���.</div></td></tr>';
	} else {

		$total = intval(($themes - 1) / $num) + 1;
		if (empty($pg) or $pg < 0) $pg = 1;
		if ($pg > $total) $pg = $total;
		$start = $pg * $num - $num;
		$result = mysql_query($query." LIMIT ".$start.", ".$num);
		while ($row = mysql_fetch_array($result)) {

		$result2	= mysql_query("SELECT name FROM plans WHERE id = ".$row['plan']." LIMIT 1");
		$row2		= mysql_fetch_array($result2);

			print "<tr bgcolor=\"#eeeeee\" align=\"center\">
			<td>".$row['id']."</td>
			<td>".date("d.m.y H:i", $row['date'])."</td>
			<td align=\"left\"><a href=\"?p=edit_user&id=".$row['user_id']."\"><b>".$row['username']."</b></a></td>
			<td>".$row['sum']."</td>
			<td>".$row2['name']."</td>
		</tr>";
		}

		if ($pg != 1) $pervpg = "<a href=?p=deposits&sort=".$_GET['sort']."&pg=". ($pg - 1) .">��</a>";
		if ($pg != $total) $nextpg = " <a href=?p=deposits&sort=".$_GET['sort']."&pg=". ($pg + 1) .">��</a>";
		if($pg - 2 > 0) $pg2left = " <a href=?p=deposits&sort=".$_GET['sort']."&pg=". ($pg - 2) .">". ($pg - 2) ."</a> | ";
		if($pg - 1 > 0) $pg1left = " <a href=?p=deposits&sort=".$_GET['sort']."&pg=". ($pg - 1) .">". ($pg - 1) ."</a> | ";
		if($pg + 2 <= $total) $pg2right = " | <a href=?p=deposits&sort=".$_GET['sort']."&pg=". ($pg + 2) .">". ($pg + 2) ."</a>";
		if($pg + 1 <= $total) $pg1right = " | <a href=?p=deposits&sort=".$_GET['sort']."&pg=". ($pg + 1) .">". ($pg + 1) ."</a>";
		print "<tr height=\"19\"><td colspan=\"5\" class=\"ftr\"><b>��������: </b>".$pervpg.$pg2left.$pg1left."[".$pg."]".$pg1right.$pg2right.$nextpg."</td></tr>";
	}
	print "</table>";
}

if($_GET['sort'] == "id") {
	$sort = "ORDER BY id DESC";
} elseif($_GET['sort'] == "sum") {
	$sort = "order by sum DESC";
} elseif($_GET[sort] == "username") {
	$sort = "order by username ASC";
} else {
	$sort = "order by id ASC";
}

if($_GET['action'] == "search") {
	$su = " AND username = '".htmlspecialchars($_POST['name'], ENT_QUOTES, '')."'";
}

$sql = "SELECT * FROM deposits WHERE status = 0 AND id != 999 ".$su." ".$sort;
users_list(intval($_GET['pg']), 50, $sql);
?>
<form action="?p=deposits&action=add_depo" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 20px;">
<LEGEND><b>������� ������� ������������</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td><strong>�����:</strong></td>
		<td align="right"><input style="width: 750px;" type="text" name="name" size="93" /></td>
	</tr>
	<tr>
		<td><strong>�����:</strong></td>
		<td align="right"><input style="width: 750px;" type="text" name="sum" size="93" value="100.00" /></td>
	</tr>
	<tr>
		<td><strong>�������� ����:</strong></td>
		<td align="right"><select name="plan" style="width: 750px;">
<?php
$result	= mysql_query("SELECT * FROM plans ORDER BY id ASC");
while($row = mysql_fetch_array($result)) {
	print '<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?></select>
	</td>
	</tr>
	<tr>
		<td><strong>��������� �������:</strong></td>
		<td align="right"><select name="paysys" style="width: 750px;">
<?php
$result	= mysql_query("SELECT * FROM `paysystems` ORDER BY id ASC");
while($row = mysql_fetch_array($result)) {
	print '<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?>
		</select>
	</td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><input type="submit" value="��������!" /></td>
	</tr>
</table>
</FIELDSET>
</form>

<form action="?p=deposits&action=search" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 20px;">
<LEGEND><b>����� �������� �� ������</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td width="60"><strong>�����:</strong></td>
		<td><input style="width: 825px;" type="text" name="name" size="93" /></td>
		<td align="center"><input type="image" src="images/search.png" width="24" height="24" border="0" title="�����!" /></td>
	</tr>
</table>
</FIELDSET>
</form>

<form action="?p=deposits&action=addpercent" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 20px;">
<LEGEND><b>��������� �������� �� ��������� �������</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td><strong>������� �� ����� ������:</strong></td>
		<td><input style="width: 700px;" type="text" name="percent" size="93" /></td>
		<td></td>
	</tr>
	<tr>
		<td><strong>�������� ����:</strong></td>
		<td><select name="plan" style="width: 700px;">
		<option value="0" style="font-weight: bold;">��������� �� ���� �������� ������</option>
		<option value="999" style="color: green;">��������� ������� �� ����� �������</option>
<?php
$result	= mysql_query("SELECT * FROM plans ORDER BY id ASC");
while($row = mysql_fetch_array($result)) {
	print '<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?></select></td>
		<td align="center"><input type="image" src="images/save.png" width="24" height="24" border="0" title="���������!" /></td>
	</tr>
</table>
</FIELDSET>
</form>