<?php
defined('ACCESS') or die();
if($login) {

	if($_GET['close']) {

		$result	= mysql_query("SELECT * FROM deposits WHERE id = ".intval($_GET['close'])." AND user_id = ".$user_id." AND status = 0 LIMIT 1");
		$row	= mysql_fetch_array($result);

		$result2	= mysql_query("SELECT * FROM `plans` WHERE id = ".$row['plan']." LIMIT 1");
		$row2		= mysql_fetch_array($result2);

		if(!$row['id'] || !$row2['id']) {
			print '<p class="er">'.$lang['error'].'</p>';
		} elseif($row2['back'] != 1 || $row2['close'] != 1) {
			print '<p class="er">'.$lang['er_22'].'</p>';
		} else {
			$sum = sprintf("%01.2f", $row['sum'] - $row['sum'] / 100 * $row2['close_percent']);
			mysql_query('UPDATE `users` SET balance = balance + '.$sum.' WHERE id = '.$row['user_id'].' LIMIT 1');
			mysql_query("DELETE FROM `deposits` WHERE `id` = ".$row['id']." LIMIT 1");
			print '<p class="erok">'.$lang['er_21'].'</p>';
		}

	}

include "tpl/deposits.php";

} else {
	print '<p class="er">'.$lang['noauth'].'</p>';
}
?>