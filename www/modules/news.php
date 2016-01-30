<?php
defined('ACCESS') or die();

function show_topics ($id, $subj, $msg, $date, $status) {
	print "<h4 style=\"margin-top: 30px;\"><font color=\"#999999\">".$date."</font> | ".$subj."</h4>";

	if ($status == 1 || $status == 2)
	{
		print " <a href=\"/admin/?p=edit_news&id=".$id."\"><img src=\"/admin/images/edit_ico.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"Редактировать новость\" /></a> ";
		print "<img style=\"cursor: hand;\" onclick=\"if(confirm('Вы уверены?')) top.location.href='/admin/del/news.php?id=".$id."'\";  width=\"12\" height=\"12\" border=\"0\" src=\"/admin/images/delite.png\" alt=\"Удалить новость\" />";
	}
	print "</p><p align=\"justify\">".$msg."</p><hr size=\"1\" color=\"#cccccc\" />";
}

function topics_list($p, $num, $status, $pages, $lang)
{
	$query	= "SELECT * FROM news ORDER BY id DESC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($p) or $p < 0) $p = 1;
	if($p > $total) $p = $total;
	$start = $p * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);
	if($lang == "en") {
		$where = "_en";
	} else {
		$where = "";
	}

	while ($row = mysql_fetch_array($result))
	{
		show_topics($row['id'], $row['subject'.$where], $row['msg'.$where], $row['date'], $status);
	}

	if ($p) {
		if($p != 1) { $pervp = "<a href=\"/news/?p=". ($p - 1) ."\">««</a>"; }
		if($p != $total) { $nextp = " <a href=\"/news/?p=".$total."\">»»</a>"; }
		if($p - 2 > 0) { $p2left = " <a href=\"/news/?p=". ($p - 2) ."\">". ($p - 2) ."</a> "; }
		if($p - 1 > 0) { $p1left = " <a href=\"/news/?p=". ($p - 1) ."\">". ($p - 1) ."</a> "; }
		if($p + 2 <= $total) { $p2right = " | <a href=\"/news/?p=". ($p + 2) ."\">". ($p + 2) ."</a> "; }
		if($p + 1 <= $total) { $p1right = " | <a href=\"/news/?p=". ($p + 1) ."\">". ($p + 1) ."</a> "; }
	}
	print "<div class=\"pages\"><b>".$pages.":  </b>".$pervp.$p2left.$p1left." [<b>".$p."</b>] ".$p1right.$p2right.$nextp."</div>";
}

$p = intval($_GET['p']);
topics_list($p, 10, $status, $lang['pages'], $lng);
?>