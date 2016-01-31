<?php
defined('ACCESS') or die();
$s = 0;
$result	= mysql_query("SELECT * FROM `deposits` WHERE `user_id` = ".$user_id." ORDER BY id ASC");
while($row = mysql_fetch_array($result)) {

	$result2	= mysql_query("SELECT * FROM `plans` WHERE `id` = ".$row['plan']." LIMIT 1");
	$row2		= mysql_fetch_array($result2);
	
	

/*print "<table width=\"100%\" align=\"center\" style=\"border: 1px solid #dddddd; border-radius: 6px; margin-bottom: 35px;\"><tr>
	<td><div style=\"padding: 7px; background-color: #dddddd; border-radius: 3px;\"><b>".$row2['name']."</b> - ".$lang['sum'].": <b>".$row['sum']."</b> ".$moneycurr;
	
	if($row2['back'] == 1 && $row2['close'] == 1) {
		print " [ <a href=\"javascript: if(confirm('".$lang['msg_09']." ".$row2['close_percent']."%')) top.location.href='/deposits/?close=".$row['id']."';\">".$lang['clousedepo']."</a> ]";
	}
	
	print "</div><div style=\"padding: 10px;\"><img align=\"left\" class=\"depoico\" src=\"/img/depoico/".$row2['img'].".png\" alt=\"\"> ".$row2['percent']."% ";
	if($row2['period'] == 1) { print $land['inday']; } elseif($row2['period'] == 2) { print $land['week']; }  elseif($row2['period'] == 4) { print $land['inhour']; } else { print $land['inmon']; }
	print ", ".$land['srokom']." ".$row2['days']." ";
	if($row2['period'] == 4) { print $land['hours']; } elseif($row2['period'] == 1) { print $land['deys']; } elseif($row2['period'] == 2) { print $land['weekly']; } elseif($row2['period'] == 3) { print $land['mesyac']; }
	print "<br />	
	".$lang['bilopen'].": ".date("d.m.Y H:i", $row['date'])."</div>
	</td>
</tr>
<tr>
	<td height=\"1\" bgcolor=\"#cccccc\"></td>
</tr>";

if(cfgSET('autopercent') == "on") {
print "<tr>
	<td align=\"center\"><b>".$lang['msg_10'].": <span id=\"deptimer".$row['id']."\"></span></b> [ ".date("H:i d.m.Y", $row['nextdate'])." ]</td>
</tr>
<tr>
	<td class=\"lineclock\" style=\"padding-left: 1px; padding-right: 1px;\">
		<div id=\"percentline".$row['id']."\" class=\"percentline\">&nbsp;</div>
		<script language=\"JavaScript\">
		<!--
			CalcTimePercent(".$row['id'].", ".$row['lastdate'].", ".$row['nextdate'].", ".time().", ".$row2['period'].");
		//-->
		</script>
	</td>
</tr>
<tr>
	<td height=\"1\" bgcolor=\"#cccccc\"></td>
</tr>";
}
print "</table>";
*/

print "<table class=\"contribution-table\">
	<thead>
		<tr>
			<th>Дата</th>
			<th>Сумма</th>
			<th>Валюта</th>
			<th>%</th>
			<th>Осталось дней</th>
			<th>Начислений в день</th>
			<th>Начислено</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>".date("d.m.Y H:i", $row['date'])."</td>
			<td>".$row['sum']." ".$moneycurr;
				if($row2['back'] == 1 && $row2['close'] == 1) {
					print " [ <a href=\"javascript: if(confirm('".$lang['msg_09']." ".$row2['close_percent']."%')) top.location.href='/deposits/?close=".$row['id']."';\">".$lang['clousedepo']."</a> ]";
				}			
			print "</td>			
			<td>".$moneycurr."</td>
			<td>1% "; 
				if($row2['period'] == 1) { print $land['inday']; } 
					elseif($row2['period'] == 2) { print $land['week']; }  
						elseif($row2['period'] == 4) { print $land['inhour']; } 
							else { print $land['inmon']; }			
			print "</td>
			<td>Всего ".$row2['days']." ";
				if($row2['period'] == 4) { print $land['hours']; } 
					elseif($row2['period'] == 1) { print $land['deys']; } 
						elseif($row2['period'] == 2) { print $land['weekly']; } 
							elseif($row2['period'] == 3) { print $land['mesyac']; }
			print "</td>
			<td>Начислений в день</td>
			<td>Начислено</td>
		</tr>
	</tbody>
</table>";

/*Ниже прегресс бар сколько осталось до следующей выплаты
if(cfgSET('autopercent') == "on") {
print "<tr>
	<td align=\"center\"><b>".$lang['msg_10'].": <span id=\"deptimer".$row['id']."\"></span></b> [ ".date("H:i d.m.Y", $row['nextdate'])." ]</td>
</tr>
<tr>
	<td class=\"lineclock\" style=\"padding-left: 1px; padding-right: 1px;\">
		<div id=\"percentline".$row['id']."\" class=\"percentline\">&nbsp;</div>
		<script language=\"JavaScript\">
		<!--
			CalcTimePercent(".$row['id'].", ".$row['lastdate'].", ".$row['nextdate'].", ".time().", ".$row2['period'].");
		//-->
		</script>
	</td>
</tr>
<tr>
	<td height=\"1\" bgcolor=\"#cccccc\"></td>
</tr>";
}*/

$s = $s + $row['sum'];
}

if($s == 0) {
	print '<p class="er">'.$lang['msg_11'].'</p>';
} else {
		print $lang['msg_12'].': <b>'.$s.'</b> '.$moneycurr;
}
?>