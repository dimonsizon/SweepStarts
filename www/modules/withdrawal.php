<?php
defined('ACCESS') or die();
if ($login) {

	$sql	= 'SELECT `pe`, `pm`, `balance`, `ref` FROM `users` WHERE `id` = '.$user_id.' LIMIT 1';
	$rs		= mysql_query($sql);
	$r		= mysql_fetch_array($rs);

	// ������ ������
	if($_GET['cancel']) {
			$sql2	= 'SELECT * FROM `output` WHERE `id` = '.intval($_GET['cancel']).' AND status = 0 AND login = "'.$login.'" LIMIT 1';
			$rs2	= mysql_query($sql2);
			$r2		= mysql_fetch_array($rs2);

			if($r2['sum']) {
				$cfgPercentOut = cfgSET('cfgPercentOut');
				if(cfgSET('cfgPercentOut')) {
					$sum = sprintf("%01.2f", $r2['sum'] + ($r2['sum'] / (100 - $cfgPercentOut) * $cfgPercentOut));
				} else {
					$sum = $r2['sum'];
				}

				mysql_query('UPDATE `users` SET balance = balance + '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
				mysql_query('UPDATE `output` SET status = 6 WHERE id = '.intval($_GET['cancel']).' LIMIT 1');
				print '<p class="erok">������ ��������, �������� ���������� �� ������</p>';
			} else {
				print '<p class="er">���������� �������� ������</p>';
			}
	}

	if ($_GET['action'] == 'save') {
		$sum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));
		$ps		= intval($_POST['ps']);
		$purse	= gs_html($_POST['purse']);

		if(!$purse && $ps == 1) {
			$purse = $r['pm'];
		} elseif(!$purse && $ps == 2) {
			$purse = $r['pe'];
		}

		if ($sum <= 0) {
			print '<p class="er">������� ���������� ����� (�� '.cfgSET('cfgMinOut').' �� '.cfgSET('cfgMaxOut').' '.$moneycurr.')!</p>';
		} elseif ($sum < cfgSET('cfgMinOut') || $sum > cfgSET('cfgMaxOut')) {
			print '<p class="er">�� ���� ��� ��������� �������� �� '.cfgSET('cfgMinOut').' �� '.cfgSET('cfgMaxOut').' '.$moneycurr.'!</p>';
		} elseif ($r['balance'] < $sum) {
			print '<p class="er">� ��� ��� ������� ����� �� �����!</p>';
		} elseif(cfgSET('cfgCountOut') != 0 && cfgSET('cfgCountOut') <= mysql_num_rows(mysql_query("SELECT * FROM output WHERE login = '".$login."' AND (status = 2 OR status = 0) AND date > ".(time() - 86400)))) {
			print '<p class="er">�� �� ������� ��������� ���� ����� ������ �� ����� �������. ���������� ���������� ������.</p>';	
		} elseif($ps < 1) {
			print '<p class="er">������� ��������� �������! ����� ����� ������� � ����� �������.</p>';
		} elseif(!$purse) {
			print '<p class="er">������� ����� �����</p>';
		} else {

			$minus = $sum;

			if(cfgSET('cfgPercentOut')) {
				$sum = sprintf("%01.2f", $sum - $sum / 100 * cfgSET('cfgPercentOut'));
			}

			$sql	= 'UPDATE `users` SET balance = balance - '.$minus.' WHERE id = '.$user_id.' LIMIT 1';
			mysql_query($sql);

			if((cfgSET('cfgAutoPay') == "on" && $ps == 1) || (cfgSET('cfgAutoPayPE') == "on" && $ps == 2)) { 
				$st	= 2; 
			} else { 
				$st = 0; 

				$text = "<p>������������! � <a href=\"http://".$cfgURL."\">����� �������</a> ������ ������ �� ����� �������. ����������� � ����������.</p>";

				$subject	= "������ �� ����� �������";
				$headers 	= "From: ".$adminmail."\n";
				$headers 	.= "Reply-to: ".$adminmail."\n";
				$headers 	.= "X-Sender: < http://".$cfgURL." >\n";
				$headers 	.= "Content-Type: text/html; charset=windows-1251\n";

				mail($adminmail,$subject,$text,$headers);
			}

			if($ps == 1) { $purse = $r['pm']; }
			if($ps == 2) { $purse = $r['pe']; }

			$sql = 'INSERT INTO `output` (`sum`, `date`, `login`, `paysys`, `purse`, `status`) VALUES("'.$sum.'", "'.time().'", "'.$login.'", '.$ps.', "'.$purse.'", '.$st.')';

			if (mysql_query($sql)) {

					$lid = mysql_insert_id();

					// �����������
						if($ps == 1 && cfgSET('cfgAutoPay') == "on") {

							$get_ps	= mysql_query("SELECT * FROM `paysystems` WHERE id = 1 LIMIT 1");
							$rowps	= mysql_fetch_array($get_ps);

							$sumout = sprintf("%01.2f", $sum / $rowps['percent']);

							$f = fopen('https://perfectmoney.is/acct/confirm.asp?AccountID='.cfgSET('cfgPMID').'&PassPhrase='.cfgSET('cfgPMpass').'&Payer_Account='.cfgSET('cfgPerfect').'&Payee_Account='.$purse.'&Amount='.$sumout.'&PAY_IN=1&PAYMENT_ID='.$lid.'&Memo='.$cfgURL, 'rb');

							if($f===false){
								mysql_query('UPDATE `users` SET balance = balance + '.$minus.' WHERE id = '.$user_id.' LIMIT 1');
								mysql_query('UPDATE `output` SET status = 6 WHERE id = '.$lid.' LIMIT 1');

								print '<p class="er">�������� ���������� API PerfectMoney. ���������� ���������� �����.</p>';
							} else {
								// getting data
								$out=array(); $out="";
								while(!feof($f)) $out.=fgets($f);

								fclose($f);

								// searching for hidden fields
								if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){

									mysql_query('UPDATE `users` SET balance = balance + '.$minus.' WHERE id = '.$user_id.' LIMIT 1');
									mysql_query('UPDATE `output` SET status = 6 WHERE id = '.$lid.' LIMIT 1');

									print '<p class="er">PerfectMoney �� ��� ���������� �� ���������� ������ ��������</p>';

								} else {
									mysql_query('UPDATE `output` SET status = 2 WHERE id = '.$lid.' LIMIT 1');
								}
							}

						} elseif($ps == 2 && cfgSET('cfgAutoPayPE') == "on") {

							require_once('includes/cpayeer.php');
							$accountNumber	= cfgSET('cfgPEAcc');
							$apiId			= cfgSET('cfgPEidAPI');
							$apiKey			= cfgSET('cfgPEapiKey');
							$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($payeer->isAuth()) {
								$arTransfer = $payeer->transfer(array(
								'curIn' => cfgSET('cfgMonCur'),	// ���� �������� 
								'sum' => $sum,		// ����� ��������� 
								'curOut' => cfgSET('cfgMonCur'),	// ������ ���������  
								'to' => $purse,		// ����������
								'comment' => 'API '.$cfgURL,
							));

								if(!empty($arTransfer["historyId"])) {
									mysql_query('UPDATE `output` SET status = 2 WHERE id = '.$lid.' LIMIT 1');
									print "<p class=\"erok\">������� �".$arTransfer["historyId"]." ������� ��������</p>";
								} else {
									mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$lid.' LIMIT 1');
									print '<p class=\"er\">������! ������ ����� ��������� � ������ ������</p>';		
								}
							} else {
								mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$lid.' LIMIT 1');
								print "<p class=\"er\">������ ����������� � API Payeer. ������ ����� ��������� � ������ ������.</p>";
							}

						}

					print '<p class="erok">���� ������ ���������� � ���������!</p>';

			} else {
				print '<p class="er">�� ������ ��������� ������ �� ������ �����!</p>';
			}
		}
	}

include "tpl/withdrawal.php";

} else {
	print "<p class=\"er\">�� ������ ���������������� ��� ������� � ���� ��������!</p>";
}
?>