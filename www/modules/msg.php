<?php
defined('ACCESS') or die();
if(!$login) {
	print "<p class=\"er\">".$lang['noauth']."</p>";
} else {

	if($_GET[act] == "del") {
		$postvar = array_keys($_POST);
		$count	 = 0;
		while($count < count($postvar)) {
			$sid = $_POST[$postvar[$count]];

			mysql_query("DELETE FROM msgs WHERE id = ".$sid." AND to_id = ".$user_id);

		$count++;
		}
	print "<p class=\"erok\">".$lang['savedata']."</p>";
	}

?>
<form action="/msg/?act=del" method="post">
<p>
	<i class="fa fa-envelope text-blue"></i>
	<a style="font-size: 14px;" href="/newmsg/"><b><?php print $lang['new_ticcet']; ?></b></a>
<p>

<table class="table-content" cellpadding="1" cellspacing="1">
<tr>
	<th width="20"><b>+</b></th>
	<th width="470"><b><?php print $lang['subject']; ?></b></th>
	<th width="120"><b><?php print $lang['date']; ?></b></th>
	<th width="110"><b><?php print $lang['login']; ?></b></th>
	<th width="50"><b><?php print $lang['prioritet']; ?></b></th>
	<th width="50"><b><?php print $lang['status']; ?></b></th>
</tr>

<?php
function show_msg($id, $subj, $date, $from_id, $from_name, $to_id, $read, $pr, $st, $fid, $s, $uid, $lng) {

	if($read == 0) {
		$td_open	= "<font color=\"#333333\">";
		$td_clouse	= "</font>";
	} else {
		$td_open	= "<font color=\"#999999\">";
		$td_clouse	= "</font>";
	}

	if($s == 0) {
		$b_open	= "<b>";
		$b_clouse	= "</b>";
	} else {
		$b_open	= "";
		$b_clouse	= "";
	}

	$i = "";

	if($uid == $from_id && $s == 1) {
		$i = "<img src=\"/img/tomsg.gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"\" title=\"Полученное сообщение\" /> ";
	} elseif($uid == $to_id && $s == 1) {
		$i = "<img src=\"/img/frommsg.gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"\" title=\"Отправленное сообщение\" /> ";
	}

	if($s == 1) { $s = " style='padding-left: 15px;'"; $z = $fid; } else { $s = " style=\"font-size: 12px;\""; $z = $id; }

print '<tr bgcolor="#ffffff" height="19">
	<td align="center"><input class="check" type="checkbox" name="box'.$id.'" value="'.$id.'" /></td>
	<td'.$s.'>'.$i.'<a href="/readmsg/?id='.$z.'#'.$id.'">'.$b_open.$td_open.$subj.$td_clouse.$b_clouse.'</a></td>
	<td align="center">'.$b_open.$td_open.date("d.m.Y H:i", $date).$td_clouse.$b_clouse.'</td>
	<td align="center"><a href="/newmsg/?to='.$from_id.'">'.$b_open.$td_open.$from_name.$td_clouse.$b_clouse.'</a></td>
	<td align="center">';

	if($pr == "0" && $lng == "en") {
		print '<font color="green">lower</font>';
	} elseif($pr == 1 && $lng == "en") {
		print '<font color="orange">average</font>';
	} elseif($pr == 2 && $lng == "en") {
		print '<font color="red">high</font>';
	} elseif($pr == "0") {
		print '<font color="green">низкий</font>';
	} elseif($pr == 1) {
		print '<font color="orange">средний</font>';
	} elseif($pr == 2) {
		print '<font color="red">высокий</font>';
	} else {
		print '-';
	}

print '</td>
	<td class="status" align="center">';

if($st == "0") {
	print '<i class="fa fa-clock-o text-blue" title="Последнее сообщение от пользователя. Ожидает ответ от администрации."></i>';
} elseif($st == 1) {
	print '<i class="fa fa-hourglass-half text-success" title="Последнее сообщение от администрации. Ожидает ответ от пользователя."></i>';
} elseif($st == 3) {
	print '<i class="fa fa-lock" title="Вопрос решен. Тикет закрыт."></i>';
} else {
	print "-";
}

print '</td>
</tr>';

}

function msg_list($page, $num, $uid, $lng, $del, $pages) {

	$query	= "SELECT * FROM `msgs` WHERE (to_id = ".$uid." OR from_id = ".$uid.") AND `fid` = 0 ORDER by prioritet DESC, id ASC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);

	if($themes) {
		while ($row = mysql_fetch_array($result)) {
			show_msg($row['id'], $row['subject'], $row['date'], $row['from_id'], $row['from_name'], $row['to_id'], $row['read'], $row['prioritet'], $row['status'],  $row['fid'],  0, $uid, $lng);

				$r	= mysql_query("SELECT * FROM `msgs` WHERE (to_id = ".$uid." OR from_id = ".$uid.") AND `fid` = ".$row['id']." ORDER by id ASC");

				while ($row22 = mysql_fetch_array($r)) {
					show_msg($row22['id'], $row22['subject'], $row22['date'], $row22['from_id'], $row22['from_name'], $row22['to_id'], $row22['read'], '', '', $row22['fid'],  1, $uid, $lng);
				}
		}
	} else {
		print "<tr bgcolor=\"#ffffff\"><td colspan=\"6\" align=\"center\" style=\"padding: 5 5 5 5px;\"><b>У Вас нет созданных тикетов</b> [<a href=\"/newmsg/\"><b>создать</b></a>]</td></tr>";
	}

	if ($page) {
		if($page != 1) { $pervpage = "<a href=\"?p=1\"><i class=\"fa fa-angle-left\"></i></a>"; }
		if($page != $total) { $nextpage = " <a href=\"?p=".$total."\"><i class=\"fa fa-angle-right\"></i></a>"; }
		if($page - 2 > 0) { $page2left = " <a href=\"?p=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
		if($page - 1 > 0) { $page1left = " <a href=\"?p=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
		if($page + 2 <= $total) { $page2right = " <a href=\"?p=". ($page + 2) ."\">". ($page + 2) ."</a> "; }
		if($page + 1 <= $total) { $page1right = " <a href=\"?p=". ($page + 1) ."\">". ($page + 1) ."</a> "; }
	}

	
	print '<tr>
	<td colspan="3"><input type="submit" value="'.$del.'" /></td>
	<td colspan="3">';
		if ($total != 1) {
			print ' <div class="pages"> '.$pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage.'</div>';
		}		
	print '</td>
	</tr>';

}

$p = intval($_GET['p']);
msg_list($p, 50, $user_id, $lng, $lang['delete'], $lang['pages']);
?>
</table>
</form>
<?php
}
?>