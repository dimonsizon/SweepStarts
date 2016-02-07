<?php
defined('ACCESS') or die();

if($login) {

	$get_user_info = mysql_query("SELECT `bonus` FROM `users` WHERE id = ".$user_id." LIMIT 1");
	$row123 = mysql_fetch_array($get_user_info);
	$bonusbalance	= $row123['bonus'];

if($_GET['act'] == "open") {

	$plan	= intval($_POST['plan']);
	$sum	= sprintf("%01.2f", $_POST['sum']);
	$reinv	= sprintf("%01.2f", $_POST['reinv']);
	$paysys	= intval($_POST['paysys']);
	$bonus	= intval($_POST['bonus']);

	if($plan && $sum) {

	$result	= mysql_query("SELECT * FROM plans WHERE id = ".$plan." LIMIT 1");
	$row	= mysql_fetch_array($result);

		if(!$row['id']) {
			print '<p class="er">Выберите тарифный план</p>';
		} elseif($sum < $row['minsum'] || ($sum > $row['maxsum'] && $row['maxsum'] != 0)) {
			print '<p class="er">Сумма не соответствует тарифному плану</p>';
		} elseif($sum > $balance && !$bonus) {
			print '<p class="er">У вас недостаточно средств на счету, рекомендуем <a href="/enter/">пополнить</a> его.</p>';
		} elseif($bonus == 1 && $bonusbalance < $sum) {
			print '<p class="er">У вас недостаточно средств на бонусном счету.</p>';
		} elseif($reinv < 0 && $reinv > 100) {
			print '<p class="er">Процент реинвестиций должет быть от 0 до 100</p>';
		} else {

			if($row['bonusdeposit']) {
				$depo	= sprintf("%01.2f", $sum + $sum / 100 * $row['bonusdeposit']);
			} else {
				$depo	= $sum;
			}

			// Вычисляем даты
			if(cfgSET('datestart') <= time()) {
				$lastdate	= time();
				$weekend	= $row['weekend'];
				$day		= date("w");

				if($day == 0 && $weekend == 1) {
					$nad = intval((24 - date("H")) * 3600);
				} elseif($day == 6 && $weekend == 1) {
					$nad = intval((24 - date("H")) * 3600 + 86400);
				} else {
					$nad = 0;
				}

				if($row['period'] == 1) {
					$nextdate = $lastdate + 86400 + $nad;
				} elseif($row['period'] == 2) {
					$nextdate = $lastdate + 604800 + $nad;
				} elseif($row['period'] == 3) {
					$nextdate = $lastdate + 2592000 + $nad;
				} elseif($row['period'] == 4) {
					$nextdate = $lastdate + 3600 + $nad;
				}
			} else {
				$lastdate = time();
				if($row['period'] == 1) {
					$nextdate = cfgSET('datestart') + 86400;
				} elseif($row['period'] == 2) {
					$nextdate = cfgSET('datestart') + 604800;
				} elseif($row['period'] == 3) {
					$nextdate = cfgSET('datestart') + 2592000;
				} elseif($row['period'] == 4) {
					$nextdate = cfgSET('datestart') + 3600;
				}
			}

			$sql = "INSERT INTO `deposits` (username, user_id, date, plan, sum, paysys, lastdate, nextdate, reinvest) VALUES ('".$login."', ".$user_id.", ".time().", ".$plan.", ".$depo.", ".$paysys.", ".$lastdate.", ".$nextdate.", ".$reinv.")";
			mysql_query($sql);

			if($bonus == 1) {
				mysql_query('UPDATE users SET bonus = bonus - '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
			} else {
				mysql_query('UPDATE users SET balance = balance - '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
			}
			mysql_query('INSERT INTO `stat` (`user_id`, `date`, `plan`, `sum`, `paysys`, `type`) VALUES ('.$user_id.', '.time().', 0, '.$sum.', 0, 1)');

			// Начисляем бонус

			if($row['bonusbalance']) {
				$bonus	= sprintf("%01.2f", $sum / 100 * $row['bonusbalance']);
				mysql_query('UPDATE users SET balance = balance + '.$bonus.' WHERE id = '.$user_id.' LIMIT 1');
			}

			// Начисляем нашим "любимым" рефералам
			if($uref && cfgSET('cfgRefPerc') == 1) {

				// Подсчитываем кол-во уровней
				$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

				// Считаем сумму открытых депозитов
				$money	= 0;
				$query	= "SELECT `sum` FROM `deposits` WHERE `status` = 0 AND user_id = ".$uref;
				$result	= mysql_query($query);
				while($row = mysql_fetch_array($result)) {
					$money = $money + $row['sum'];
				}

				if($countlvl) {
					$i		= 0;
					$uid	= $user_id;
					$query	= "SELECT * FROM reflevels ORDER BY id ASC";
					$result	= mysql_query($query);
					while($row = mysql_fetch_array($result)) {
						if($i < $countlvl && $money >= $row['minsum']) {
							$lvlperc = $row['sum'];		// Процент уровня
							$ps		 = sprintf("%01.2f", $sum / 100 * $lvlperc); // Сумма рефских

							if($uref) {

								// Смотрим есть ли индивидуальный процент у данного реферала
								$get_refp	= mysql_query("SELECT reftop, ref_percent, bonuslevel FROM users WHERE id = ".intval($uref)." LIMIT 1");
								$rowrefp	= mysql_fetch_array($get_refp);
								$urefp		= $rowrefp['ref_percent'];
								$reftop		= $rowrefp['reftop'];
								$bonuslevel	= $rowrefp['bonuslevel'];

								$query_bonus	= "SELECT `id`, `refsum`, `sum` FROM `bonus` ORDER BY id DESC";
								$result_bonus	= mysql_query($query_bonus);
								while($row_bonus = mysql_fetch_array($result_bonus)) {

									if($row_bonus['refsum'] <= $reftop && $bonuslevel < $row_bonus['id']) {
										mysql_query('UPDATE users SET bonuslevel = '.$row_bonus['id'].', bonus = bonus + '.$row_bonus['sum'].' WHERE id = '.intval($uref).' LIMIT 1');
									}

								}

								if($i == 0 && $urefp >= 0.01) {
									$ps = sprintf("%01.2f", $sum / 100 * $urefp); // Сумма рефских
								}

								mysql_query('UPDATE users SET balance = balance + '.$ps.', reftop = reftop + '.$ps.' WHERE id = '.$uref.' LIMIT 1');
								mysql_query('UPDATE users SET ref_money = ref_money + '.$ps.' WHERE id = '.$uid.' LIMIT 1');
								mysql_query('INSERT INTO `stat` (`user_id`, `date`, `plan`, `sum`, `paysys`, `type`) VALUES ('.$uref.', '.time().', 0, '.$ps.', 0, 2)');

								
								// Получаем данные следующего пользователя

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
			// Закончили с рефералами

			print '<p class="erok">Вклад открыт! <a href="/deposits/">К списку ваших вкладов</a></p>';
		}

	} else {
		print '<p class="er">Выберите тарифный план, платежную систему и введите сумму депозита</p>';
	}
	
}

include "tpl/newdeposit.php";

} else {
	print "<p class=\"er\">Для доступа к данной странице вам необходимо авторизироваться</p>";
}
?>