<?php
defined('ACCESS') or die();

$action = $_GET['action'];

// ���������� ������
if ($action == "send") {
	if ($login) {
		if ($_POST['radio'] < 1 OR $_POST['radio'] > 2) {
			print '<p class="er">�� ���������!</p>';
		} else {
			$text = nl2br(gs_html(substr($_POST['text'], 0, 10000)));
			$view = 1;
			$temp = strtok($text, " ");


			if (!$text || $text == " ") {
				print "<p class=\"er\">������� ����� ���������</p>";
			} elseif (strlen($temp) > 100) {
				print "<p class=\"er\">����� ������ ��������� �������� ������� ����� �������� ��� ��������!</p>";
			} elseif (mysql_num_rows(mysql_query("SELECT id FROM answers WHERE date > ".(time() - 1800)." AND username = '".$login."' LIMIT 1"))) {
				print "<p class=\"er\">����� ������ ��������� ���� ������ ���� � 30 �����.</p>";
			} else {

				if ($_POST['radio'] == 1) {
					$radi = 1;
				} else {
					$radi = 2;
				}

				$sql = "INSERT INTO answers (`username`, `text`, `date`, `yes`, `view`, `ip`, `poll`) VALUES ('".$login."', '".$text."', ".time().", '".$radi."', ".$view.", '".$userip."', ".intval($_POST['poll']).")";

				if (mysql_query($sql)) {
					print "<p class=\"erok\">��������� ���������!</p>";
				} else {
					print "<p class=\"er\">��������� ������ ��� ������ ������ � ��</p>";
				}

				$text = "";
			}
		}
	} else {
		print '<p class="er">�� ������ ���������������� ��� ������� �� ��� ��������!</p>';
	}
} elseif($status == 1 && $_GET['v']) {
	mysql_query("UPDATE answers SET view = 0 WHERE id = ".intval($_GET['v'])." LIMIT 1");
	print "<p class=\"erok\">��������� ������</p>";
}


// ����� �������
function topics_list($page, $num, $status)
{
	$query	= "SELECT * FROM answers WHERE view = 1 AND part = 0 ORDER BY id DESC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);
	while ($row = mysql_fetch_array($result))
	{
  		if ($status == 1) {
			$admin_but  = "<a href=\"/admin/?p=admin_answers&id=".$row['id']."\"><img src=\"/admin/images/comment.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"��������������\" /></a> ";
			$admin_but .= "<a href=\"/admin/?p=edit_answers&id=".$row['id']."\"><img src=\"/admin/images/edit_ico.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"������������� ���������\" /></a> ";
			$admin_but .= "<img style=\"cursor: pointer;\" onclick=\"if(confirm('�� �������?')) top.location.href='/admin/del/answers.php?id=".$row['id']."'\"; src=\"/admin/images/delite.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"������� ���������\" />";
			$admin_but .= " IP: ".$row['ip'];
		} else {
			$admin_but	= "";
		}

		if ($row['yes'] == 1) {
			$smile = '<img src="/img/yes.png" width="16" height="16" border="0" alt="������������� �����" title="������������� �����" />';
			$style = "border: 1px solid #99ff99; background-color: #e4ffe4; padding: 10px; margin-bottom: 10px; border-radius: 0 15px 0 15px;";
		} else {
			$smile = '<img src="/img/no.png" width="16" height="16" border="0" alt="������������� �����" title="������������� �����" />';
			$style = "border: 1px solid #ff9999; background-color: #ffe4e4; padding: 10px; margin-bottom: 10px; border-radius: 0 15px 0 15px;";
		}



print '
	<table width="100%" border="0" style="'.$style.'">
		<tr>
			<td><h4>'.$smile.' <font color="#999999">'.date("d.m.Y H:i", $row['date']).'</font> - <b>'.$row['username'].'</b>';

print ' '.$admin_but.'</h4>
			<p align="justify">'.$row['text'].'</p>';

		if($row['answer']) { print "<div style='border: 1px solid #ff9900; background-color: #feffee; padding: 5px; border-radius: 3px;'><i>����������� �� �������������:</i><br /><font color=\"#660000\">".$row['answer']."</font></div>"; }

print '
			</td>
		</tr>
	</table><div class="hline" style="margin-bottom: 3px;"></div>
';

	}


	if($page != 1) { $pervpage = "<a href=\"/reviews/?p=". ($page - 1) ."\">��</a> "; }
	if($page != $total) { $nextpage = " <a href=\"/reviews/?p=".$total."\">��</a>"; }
	if($page - 2 > 0) { $page2left = " <a href=\"/reviews/?p=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
	if($page - 1 > 0) { $page1left = " <a href=\"/reviews/?p=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
	if($page + 2 <= $total) { $page2right = " <a href=\"/reviews/?p=". ($page + 2) ."\">". ($page + 2) ."</a> "; }
	if($page + 1 <= $total) { $page1right = " <a href=\"/reviews/?p=". ($page + 1) ."\">". ($page + 1) ."</a> "; }

	print "<div class=\"pages\"><b>��������: </b>".$pervpage.$page2left.$page1left." [".$page."] ".$page1right.$page2right.$nextpage."</div>";
}

$p = intval($_GET['p']);
topics_list($p, 10, $status);

if ($login) {
// ����� ���������� ������������
?>
<div class="hline"></div>
<center>
<table width="380" border="0" align="center">
	<form action="/reviews/?action=send" method="post" name="msg_form">
	<tr>
		<td colspan="3">����� ���������:<br /><textarea style="width: 500px;" name="text" rows="7" cols="48"><?php print gs_html(substr($_POST['text'], 0, 10000)); ?></textarea></td>
	</tr>
	<tr>
		<td><nobr style="line-height: 16px;">
		<label><input class="check" type="radio" name="radio" value="1" checked /><img src="/img/yes.png" width="16" height="16" border="0" alt="������������� �����" title="������������� �����" /> <font color="green">������������</font></label> -
		<label><input class="check" type="radio" name="radio" value="2" /> <img src="/img/no.png" width="16" height="16" border="0" alt="������������� �����" title="������������� �����" /> <font color="red">������������</font></label>
		</nobr></td>
		<td>
		</td>
		<td align="right"><input class="subm" type="submit" value="���������!" /></td>
	</tr>
	</form>
</table>
</center>
<?php
} else {
	print '<p class="er">��� ���������� ������� ��� ���������� ����������������!</p>';
}
?>