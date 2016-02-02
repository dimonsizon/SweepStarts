<?php
defined('ACCESS') or die();
?>
<div class="open-contribution">
<form method="post" action="/newdeposit/?act=open">

<?php
$result	= mysql_query("SELECT * FROM plans WHERE status = 0 ORDER BY id ASC");
$i = 0;
while($row = mysql_fetch_array($result)) {

	print "<div class=\"block-tile\"> 
		<label onclick=\"CalcSumDepo();\">
			<div class=\"header-info\">			
				<input id=\"plan\" type=\"radio\" name=\"plan\" value=\"".$row['id']."\"";
				if($i == 0) { print " checked "; }
				print "/>
				<span>";
					print "".$row['name']."
				</span>				
			</div>
			<div class=\"short-info\">
				<img class=\"depoico\" src=\"/img/depoico/".$row['img'].".png\" alt=\"\">
				<p>".$lang['sum']." ".$row['minsum']." ".$moneycurr." - ".$row['maxsum']." ".$moneycurr."</p>
				<p>".$row['percent']."% ";
					if($row['period'] == 1) { print $land['inday']; } elseif($row['period'] == 2) { print $land['week']; } elseif($row['period'] == 4) { print $land['inhour']; } else { print $land['inmon']; }
				print "</p>			
				<p class=\"days\"> ".$land['srokom']." ".$row['days']." ";
				if($row['period'] == 4) { print $land['hours']; } elseif($row['period'] == 1) { print $land['deys']; } elseif($row['period'] == 2) { print $land['weekly']; } elseif($row['period'] == 3) { print $land['mesyac']; }
				print "</p>
			</div>
		</label>
		<input type=\"hidden\" id=\"per".$row['id']."\" value=\"".$row['percent']."\" />
		<input type=\"hidden\" id=\"cou".$row['id']."\" value=\"".$row['days']."\" />
		<input type=\"hidden\" id=\"bac".$row['id']."\" value=\"".$row['back']."\" />";
	print "</div>";
	
$i++;
}
if(!$i) { print '<p class="warn">'.$lang['nodata'].'</p>'; }
?>
<div class="form-container">
	<div class="form-field">
		<label><?php print $lang['sum']; ?> (<?php print $moneycurr; ?>):</label>
		<input class="text-input" type="text" id="sum" name="sum" onkeyup="CalcSumDepo();" value="<?php print $balance; ?>" />
	</div>
	<?php
		if(cfgSET('cfgReInv') == "on") {
			print '<div class=\"form-field\">
				<label>'.$lang['reinvest'].' (%): </label>
				<input type="text" name="reinv" value="0" /></td>
			</div>';	
		}
	?>
	<?php if(cfgSET('cfgBonusBal') == "on") { ?>
		<div class="form-field">
			<input type="checkbox" name="bonus" value="1">
			<?php print $lang['ispbonus']." ".$bonusbalance." ".$moneycurr; ?>
		</div>
	<?php } ?>
	<div class="form-buttons">
		<input class="subm" type="submit" value=" <?php print $lang['opendep']; ?> " />
	</div>
	
	<div>
		<h3 class="text-center"><?php print $lang['yourmojdep']; ?> <span id="calcsumperc">0.00</span></h3>
	</div>
</div>
</form>
</div>

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
		var datadays = document.getElementById('cou'+selectedValue).value-52;
		var databack = document.getElementById('bac'+selectedValue).value;
		s = document.getElementById('sum').value / 100 * dataperc * datadays;

		perrr = roundPlus(s, 2);
		document.getElementById('calcsumperc').innerHTML = '&asymp;'+perrr;
		//document.getElementById('calp').innerHTML = dataperc+'%'; //$lang['percent']
		//document.getElementById('caln').innerHTML = datadays;		//$lang['countnac']	
		//if (databack == 1) {
		//	document.getElementById('calb').innerHTML = "100%";		//$lang['backvklad']
		//} else {
		//	document.getElementById('calb').innerHTML = "-";
		//}

	}
}
//-->
CalcSumDepo();
</script>