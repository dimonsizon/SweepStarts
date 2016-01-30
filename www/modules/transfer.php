<?php
defined('ACCESS') or die();
print $body;
	
if($login) {

	if(cfgSET('cfgTrans') == "off") {
		print '<p class="er">'.$lang['er_23'].'</p>';	
	} else {

		if($_GET['action'] == "yes") { 

			$name		= gs_html($_POST['name']);
			$sum		= sprintf("%01.2f", str_replace(',', '.', $_POST['sum']));
			$percent	= intval($_POST['percent']);

			if(!$name) {
				print '<p class="er">'.$lang['er_24'].'</p>';
			} elseif(!$sum) {
				print '<p class="er">'.$lang['er_25'].'</p>';
			} elseif($sum < 0.01) {
				print '<p class="er">'.$lang['er_17'].' '.$moneycurr.'</p>';
			} elseif(!mysql_num_rows(mysql_query("SELECT login, mail FROM users WHERE login = '".$name."' OR mail = '".$name."'"))) {
				print '<p class="er">'.$lang['er_26'].'!</p>';
			} else {

				if(cfgSET('cfgTransPercent') > 0 && $percent == 2) {
					$sum_in		= sprintf("%01.2f", $sum - $sum / 100 * cfgSET('cfgTransPercent'));
					$sum_out	= $sum;
				} elseif(cfgSET('cfgTransPercent') > 0 && $percent == 3) {
					$sum_in		= sprintf("%01.2f", $sum - $sum / 100 * cfgSET('cfgTransPercent') / 2);
					$sum_out	= sprintf("%01.2f", $sum + $sum / 100 * cfgSET('cfgTransPercent') / 2);
				} else {
					$sum_in		= $sum;
					$sum_out	= sprintf("%01.2f", $sum + $sum / 100 * cfgSET('cfgTransPercent'));
				}

				if($balance < $sum_out) {
					print '<p class="er">'.$lang['er_27'].'</p>';
				} else {

					$get_user_info	= mysql_query("SELECT login, mail FROM users WHERE login = '".$name."' OR mail = '".$name."' LIMIT 1");
					$row			= mysql_fetch_array($get_user_info);

					mysql_query('UPDATE users SET balance = balance + '.$sum_in.' WHERE login = "'.$row['login'].'" LIMIT 1');
					mysql_query('UPDATE users SET balance = balance - '.$sum_out.' WHERE login = "'.$login.'" LIMIT 1');

					mysql_query('INSERT INTO `transfer` (`sum`, `date`, `to`, `from`) VALUES ('.$sum.', '.time().', "'.$row['login'].'", "'.$login.'")');

					$mail	= $row['mail'];

					$headers = "From: ".$adminmail."\n";
					$headers .= "Reply-to: ".$adminmail."\n";
					$headers .= "X-Sender: < http://".$cfgURL." >\n";
					$headers .= "Content-Type: text/html; charset=windows-1251\n";

					$subject	= "Вам поступил денежный перевод";
					$msg		= "Здравствуйте! Вам поступил денежный перевод в сумме ".$sum_in." ".$moneycurr." от пользователя ".$login;

					mail($mail, $subject, $msg, $headers);

					print '<p class="erok">Перевод успешно выполнен</p>';
				}

			}

		}

?>
	<table align="center">
	<form action="/transfer/?action=yes" method="post">
	<tr><td><b><?php print $lang['letrans']; ?></b>: </td><td align="right"><input style="width: 195px;" type='text' name='name' value='' size="30" maxlength="30" /></td></tr>
	<tr><td><b><?php print $lang['sum']; ?> *</b>: </td><td align="right"><input style="width: 195px;" type='text' name='sum' value='' size="30" maxlength="7" /></td></tr>
<?php if(cfgSET('cfgTransPercent') > 0) { ?>
	<tr><td><b><?php print $lang['comoplata']; ?></b>: </td><td align="right">
	<select style="width: 195px; margin-right: 0px;" name="percent">
		<option value="1"><?php print $lang['i']; ?></option> 
		<option value="2"><?php print $lang['toto']; ?></option>
		<option value="3"><?php print $lang['popolam']; ?></option>
	</select></td></tr>
<?php } ?>
	<tr><td></td><td align="right"><input style="width: 195px;" class="subm" type='submit' name='submit' value=' <?php print $lang['send']; ?> ' /></td></tr>
	</form>
	</table><hr />
	<p>* <?php print $lang['msg_13']; ?> <b><?php print cfgSET('cfgTransPercent'); ?>%</b></p>

<h3><?php print $lang['history']; ?>:</h3>
<?php


	$page	= intval($_GET['p']);
	$num	= 20;
	$query	= "SELECT * FROM `transfer` WHERE `to` = '".$login."' OR `from` = '".$login."'";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;

	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." ORDER BY id DESC LIMIT ".$start.", ".$num);

	if(!$themes) {
		print "<div class=\"warn\">".$lang['nodata']."!</div>";
	} else {

		print "<table width=\"100%\" class=\"tbl\"><tr align=\"center\"><th style=\"padding: 3px;\"><b>#</b></th><th width=\"100\"><b>".$lang['date']."</b></th><th><b>".$lang['sum']."</b></th><th><b>".$lang['toto']."</b></th><th><b>".$lang['sender']."</b></th><th><b>".$lang['status']."</b></th></tr>";

		$i = 1;
		$s = 0;
		while ($row = mysql_fetch_array($result)) {

		if($i % 2) { $bg = ""; } else { $bg = " bgcolor=\"#eeeeee\""; }

		print "<tr".$bg." align=\"center\">
		<td style=\"padding: 3px;\">".$row['id']."</td>
		<td>".date("d.m.Y H:i", $row['date'])."</td>
		<td>".$row['sum']." ".$moneycurr."</td>
		<td><b>".$row['to']."</b></td>
		<td>".$row['from']."</td>
		<td>";

		if($row['to'] == $login) {
			print '<span class="tool"><img src="/img/to_ico.png" width="16" height="16" alt="" /><span class="tip">'.$lang['totransfer'].'</span></span>';
		} else {
			print '<span class="tool"><img src="/img/from_ico.png" width="16" height="16" alt="" /><span class="tip">'.$lang['sendtransfer'].'</span></span>';
		}

		print "</td>

		</tr>";

			$i++;
			$s = $s + $row['sum'];
		}

		print "<tr><td class=\"tblbord\"></td><td align=\"right\" class=\"tblbord\"><b>".$lang['total'].":</b></td><td align=\"center\" class=\"tblbord\"><b>".$s."</b> ".$moneycurr."</td><td class=\"tblbord\"></td><td class=\"tblbord\"></td><td class=\"tblbord\"></td></tr></table>";

	}

	if ($page) {
		if($page != 1) { $pervpage = "<a href=\"/transfer/?p=1\">««</a>"; }
		if($page != $total) { $nextpage = " <a href=\"/transfer/?p=".$total."\">»»</a>"; }
		if($page - 2 > 0) { $page2left = " <a href=\"/transfer/?p=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
		if($page - 1 > 0) { $page1left = " <a href=\"/transfer/?p=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
		if($page + 2 <= $total) { $page2right = " <a href=\"/transfer/?p=". ($page + 2) ."\">". ($page + 2) ."</a> "; }
		if($page + 1 <= $total) { $page1right = " <a href=\"/transfer/?p=". ($page + 1) ."\">". ($page + 1) ."</a> "; }
	}
	print "<div class=\"pages\"><b>".$lang['pages'].":  </b>".$pervpage.$page2left.$page1left." [<b>".$page."</b>] ".$page1right.$page2right.$nextpage."</div>";

	}

} else {
	print '<p class="er">'.$lang['noauth'].'!</p>';
}
?>