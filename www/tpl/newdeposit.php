<?php
defined('ACCESS') or die();
?>
<form method="post" action="/newdeposit/?act=open">
<table width="100%" align="center">
<?php
$result	= mysql_query("SELECT * FROM plans WHERE status = 0 ORDER BY id ASC");
$i = 0;
while($row = mysql_fetch_array($result)) {

	print "<tr><td><label onclick=\"CalcSumDepo();\"><input style=\"float: left; margin: 5px;\" id=\"plan\" type=\"radio\" name=\"plan\" value=\"".$row['id']."\"";
	if($i == 0) { print " checked "; }
	print "/>";

	print "<div style=\"padding: 7px; background-color: #dddddd; border-radius: 4px; font-size: 14px;\"><b>".$row['name']."</b></div>";
	print "<div style=\"padding: 10px 30px; color: #666666;\"><img align=\"left\" class=\"depoico\" src=\"/img/depoico/".$row['img'].".png\" alt=\"\">".$lang['sum'].": ".$row['minsum']." ".$moneycurr." - ".$row['maxsum']." ".$moneycurr." <strong>".$row['percent']."%</strong> ";

	if($row['period'] == 1) { print $land['inday']; } elseif($row['period'] == 2) { print $land['week']; } elseif($row['period'] == 4) { print $land['inhour']; } else { print $land['inmon']; }
	print ", ".$land['srokom']." ".$row['days']." ";
	if($row['period'] == 4) { print $land['hours']; } elseif($row['period'] == 1) { print $land['deys']; } elseif($row['period'] == 2) { print $land['weekly']; } elseif($row['period'] == 3) { print $land['mesyac']; }

	print "</label></div></td>
</tr>
<tr>
	<td height=\"1\" bgcolor=\"#cccccc\"></td>
</tr>
<tr>
	<td height=\"15\">
		<input type=\"hidden\" id=\"per".$row['id']."\" value=\"".$row['percent']."\" />
		<input type=\"hidden\" id=\"cou".$row['id']."\" value=\"".$row['days']."\" />
		<input type=\"hidden\" id=\"bac".$row['id']."\" value=\"".$row['back']."\" />
	</td>
</tr>";

$i++;
}
if(!$i) { print '<p class="warn">'.$lang['nodata'].'</p>'; }
?>
</table>
<div style="margin-top: 15px;"></div>

<table width="100%">
<tr>
	<td align="right"><?php print $lang['sum']; ?> (<?php print $moneycurr; ?>): </td>
	<td width="240"><input style="width: 230px;" type="text" id="sum" name="sum" onkeyup="CalcSumDepo();" value="<?php print $balance; ?>" /></td>
</tr>

<?php
if(cfgSET('cfgReInv') == "on") {
	print '<tr>
	<td align="right">'.$lang['reinvest'].' (%): </td>
	<td><input style="width: 230px;" type="text" name="reinv" value="0" /></td>
	</tr>';	
}
?>

<tr>
	<td></td>
	<td><input style="width: 230px;" class="subm" type="submit" value=" <?php print $lang['opendep']; ?> " /></td>
</tr>
<?php if(cfgSET('cfgBonusBal') == "on") { ?>
<tr>
	<td align="right"><input type="checkbox" name="bonus" value="1"></td>
	<td><?php print $lang['ispbonus']." ".$bonusbalance." ".$moneycurr; ?></td>
</tr>
<?php } ?>
<tr>
	<td height="15"></td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td>
		<div class="left"><div class="left" style="width: 100px; height: 14px; border: 1px solid #e6433a; border-radius: 3px 0 0 3px; padding: 7px; color: #e6433a; margin-bottom: 15px; float: left;"><strong><?php print $lang['yourmojdep']; ?>:</strong></div><div class="right pricepart" id="calcsumperc">0.00</div></div>
	</td>
</tr>
<tr>
	<td></td>
	<td>
		<div class="left" style="width: 120px;"><?php print $lang['percent']; ?>:</div><div id="calp" style="font-weight: bold;">2</div><div class="clear"></div>
		<div class="left" style="width: 120px;"><?php print $lang['countnac']; ?>:</div><div id="caln" style="font-weight: bold;">2</div><div class="clear"></div>
		<div class="left" style="width: 120px;"><?php print $lang['backvklad']; ?>:</div><div id="calb" style="font-weight: bold;">2</div>
	</td>
</tr>
</table>
</form>
<script type="text/javascript">
<!--
function CalcSumDepo() {
var plan = document.getElementsByName("plan");
var selectedValue = -1;
var input_len = plan.length;
for (var i = 0; i < input_len; i++) {
    if (plan[i].checked) {
        selectedValue = plan[i].value;
        break;
    }
}
	if (selectedValue > 0) {
		var dataperc = document.getElementById('per'+selectedValue).value;
		var datadays = document.getElementById('cou'+selectedValue).value;
		var databack = document.getElementById('bac'+selectedValue).value;
		s = document.getElementById('sum').value / 100 * dataperc * datadays;

		perrr = roundPlus(s, 2);
		document.getElementById('calcsumperc').innerHTML = '&asymp;'+perrr;
		document.getElementById('calp').innerHTML = dataperc+'%';
		document.getElementById('caln').innerHTML = datadays;
		if (databack == 1) {
			document.getElementById('calb').innerHTML = "100%";
		} else {
			document.getElementById('calb').innerHTML = "-";
		}

	}
}
//-->
CalcSumDepo();
</script>