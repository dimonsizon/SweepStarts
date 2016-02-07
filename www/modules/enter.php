<?php
defined('ACCESS') or die();
if ($login) {

	if($_GET['pay'] == "no") {
		print '<p class="er">'.$lang['er_15'].'</p>';
	}

	if($_GET['conf']) {

		print '<p class="erok">'.$lang['er_16'].'</p>';

		$conf		= intval($_GET['conf']);
		$purse		= gs_html($_POST["purse"]);

		mysql_query("UPDATE enter SET status = 1, purse = '".$purse."' WHERE id = ".$conf." LIMIT 1");

	} elseif ($_GET['action'] == 'save') {
		$sum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));
		$ps		= intval($_POST['ps']);

		if ($sum < 0.10 || $sum > 1000000) {
			print '<p class="er">'.$lang['er_17'].'</p>';
		} elseif($ps < 1) {
			print '<p class="er">'.$lang['er_18'].'</p>';
		} else {

				// Форма пополнения
					if($ps == 1) {

					// PM

					$sql = 'INSERT INTO `enter` (`sum`, `date`, `login`, `paysys`) VALUES ('.$sum.', '.time().', "'.$login.'", "PerfectMoney")';
					mysql_query($sql);
					$lid = mysql_insert_id();

					if(cfgSET('cfgSSL') == "on") {
						$http = "https";
					} else {
						$http = "http";
					}

					$get_ps	= mysql_query("SELECT * FROM `paysystems` WHERE id = 1 LIMIT 1");
					$rowps	= mysql_fetch_array($get_ps);

					print '<FIELDSET style="border: solid #666666 1px; padding-top: 15px; margin-bottom: 50px;">
					<LEGEND><b>'.$lang['payconf'].'</b></LEGEND>
					<form action="https://perfectmoney.is/api/step1.asp" method="POST">
					<input type="hidden" name="PAYEE_ACCOUNT" value="'.cfgSET('cfgPerfect').'">
					<input type="hidden" name="PAYEE_NAME" value="'.cfgSET('cfgPAYEE_NAME').'">
					<input type="hidden" name="PAYMENT_ID" value="'.$lid.'">
					<input type="hidden" name="PAYMENT_AMOUNT" value="'.$sum.'">
					<input type="hidden" name="PAYMENT_UNITS" value="USD">
					<input type="hidden" name="STATUS_URL" value="'.$http.'://'.$cfgURL.'/pmresult.php">
					<input type="hidden" name="PAYMENT_URL" value="'.$http.'://'.$cfgURL.'/newdeposit/?pay=yes">
					<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="NOPAYMENT_URL" value="'.$http.'://'.$cfgURL.'/enter/?pay=no">
					<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="BAGGAGE_FIELDS" value="">
					<input type="hidden" name="SUGGESTED_MEMO" value="'.$cfgURL.'">';
					
					print '<p class="warn">'.$lang['msg_02'].' $'.$sum.' PerfectMoney. '.$lang['msg_15'].' '.sprintf("%01.2f", $sum * $rowps['percent']).' '.$moneycurr.'</p>';
					
					print '<p align="center"><input class="subm" name="PAYMENT_METHOD" type="submit" value=" '.$lang['enterbal'].' $'.$sum.' " /></p>
					</form>
					</FIELDSET>';

					} else {


					$get_ps	= mysql_query("SELECT * FROM `paysystems` WHERE id = ".intval($ps)." LIMIT 1");
					$rowps	= mysql_fetch_array($get_ps);

					$sum2 = sprintf("%01.2f", $sum * $rowps['percent']);

					$sql = 'INSERT INTO enter (sum, date, login, paysys, service) VALUES ('.$sum.', '.time().', "'.$login.'", "'.$rowps['name'].'", "bal")';

						if(mysql_query($sql)) {

						$m_orderid = mysql_insert_id();

							if($rowps['name'] == "PAYEER.com") {

								$desc = base64_encode($cfgURL);

								$cu = cfgSET('cfgMonCur');

								$cid	= cfgSET('cfgPEsid');
								$m_key	= cfgSET('cfgPEkey');

								$arHash = array(
									$cid,
									$m_orderid,
									$sum,
									$cu,
									$desc,
									$m_key
								);

								$sign = strtoupper(hash('sha256', implode(":", $arHash)));

								print '<FIELDSET style="border: solid #666666 1px; padding-top: 15px; margin-bottom: 50px;">
								<LEGEND><b>'.$lang['payconf'].'</b></LEGEND>
								<form method="GET" action="//payeer.com/api/merchant/m.php" accept-charset="utf-8">
								<input type="hidden" name="m_shop" value="'.$cid.'">
								<input type="hidden" name="m_orderid" value="'.$m_orderid.'">
								<input type="hidden" name="m_amount" value="'.$sum.'">
								<input type="hidden" name="m_curr" value="'.cfgSET('cfgMonCur').'">
								<input type="hidden" name="m_desc" value="'.$desc.'">
								<input type="hidden" name="m_sign" value="'.$sign.'">
								<p align="center"><input class="subm" type="submit" name="m_process" value=" '.$lang['enterbal'].' '.$sum.' '.$moneycurr.' " /></p>
								</form>
								</FIELDSET>';

							} else {

								print '<form method="POST" action="/enter/?conf='.$m_orderid.'">
								<center>'.$lang['msg_02'].' <b>'.$sum.'</b> '.$rowps['abr'].' '.$lang['naschet'].' <b>'.$rowps['purse'].'</b> '.$lang['msg_01'].'<br />'.$rowps['comment'].'<br />'.$lang['msg_15'].' '.sprintf("%01.2f", $sum * $rowps['percent']).' '.$moneycurr.'<br /><br />

								<input type="text" name="purse" size="20" />
								<br /><br />
								<p align="center"><input class="subm" type="submit" value="'.$lang['ipay'].'" /></p>
								</center>
								</form>';
							}

						} else {
							print '<p class="er">'.$lang['erbd'].'</p>';
						}

				
				
					}
		}
	} else {
		include "tpl/enter.php";
	}

} else {
	print "<p class=\"er\">".$lang['noauth']."</p>";
}
?>