<?php
defined('ACCESS') or die();

// ������� �������
if($_GET['act'] == "addreflevel") {
	$level		= intval($_POST['level']);
	$percent	= sprintf ("%01.2f", str_replace(',', '.', $_POST['percent']));
	$minsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['minsum']));
	$nominsum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['nominsum']));

	if($level < 1) {
		print '<p class="er">������� ������� ����������� �������</p>';
	} elseif($percent < 0.01 || $percent > 100) {
		print '<p class="er">������� ������ ���� �� 0.01 �� 100</p>';
	} else {
		mysql_query('INSERT INTO reflevels (id, sum, minsum, nominsum) VALUES ('.$level.', '.$percent.', '.$minsum.', '.$nominsum.')');
		print '<p class="erok">����� ����������� ������� - ��������!</p>';
	}
}

// ������� ������� ������
if($_GET['act'] == "addbonus") {
	$name		= gs_html($_POST['name']);
	$refsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['refsum']));
	$sum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));

	if(!$name) {
		print '<p class="er">������� ��������</p>';
	} else {
		mysql_query('INSERT INTO bonus (name, refsum, sum) VALUES ("'.$name.'", '.$refsum.', '.$sum.')');
		print '<p class="erok">����� �������� ������� - ��������!</p>';
	}
}

// ������� �������
if($_GET['act'] == "dellevel") {
	mysql_query("DELETE FROM reflevels WHERE id = ".intval($_GET['id'])." LIMIT 1");
	print '<p class="erok">����������� ������� ������!</p>';
}

// ������� ������� ������
if($_GET['act'] == "delbonus") {
	mysql_query("DELETE FROM bonus WHERE id = ".intval($_GET['id'])." LIMIT 1");
	print '<p class="erok">�������� ������� ������!</p>';
}

// ������� ������
if($_GET['act'] == "delbanner") {
	mysql_query("DELETE FROM `promo` WHERE id = ".intval($_GET['id'])." LIMIT 1");
	print '<p class="erok">������ ������!</p>';
}

// ����������� �������
if($_GET['act'] == "editlevel") {
	$level		= intval($_POST['level']);
	$percent	= sprintf ("%01.2f", str_replace(',', '.', $_POST['percent']));
	$minsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['minsum']));
	$nominsum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['nominsum']));

	if($level < 1) {
		print '<p class="er">������� ������� ����������� �������</p>';
	} elseif($percent < 0.01 || $percent > 100) {
		print '<p class="er">������� ������ ���� �� 0.01 �� 100</p>';
	} else {
		mysql_query("UPDATE reflevels SET id = ".$level.", sum = ".$percent.", minsum = ".$minsum.", nominsum = ".$nominsum." WHERE id = ".intval($_GET['id'])." LIMIT 1");
		print '<p class="erok">��������� ���������!</p>';
	}
}

// ����������� ������� ������
if($_GET['act'] == "editbonus") {
	$name		= gs_html($_POST['name']);
	$refsum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['refsum']));
	$sum		= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));

	if(!$name) {
		print '<p class="er">������� ��������</p>';
	} else {
		mysql_query("UPDATE bonus SET name = '".$name."', sum = ".$sum.", refsum = ".$refsum." WHERE id = ".intval($_GET['id'])." LIMIT 1");
		print '<p class="erok">��������� ���������!</p>';
	}
}

// ��������� ���������
if($_GET['act'] == "seveset") {
	if($_POST['cfgRefPerc']) {
		mysql_query("UPDATE `settings` SET `data` = '".intval($_POST['cfgRefPerc'])."' WHERE `cfgname` = 'cfgRefPerc' LIMIT 1");
	}
	if($_POST['refname']) {
		mysql_query("UPDATE `settings` SET `data` = '".gs_html($_POST['refname'])."' WHERE `cfgname` = 'refname' LIMIT 1");
	}

	$file		= addslashes(gs_html($_FILES['banner']['name']));
	$type		= substr(strrchr($file,"."),1);

	if($file) {
		$sql = mysql_query("INSERT INTO `promo` (`type`) values ('".$type."')");

		$bname	= mysql_insert_id().".".$type;
		copy($_FILES['banner']['tmp_name'], "../img/promo/".$bname);

		print "<p class=\"erok\">�����-�������� ��������!</p>";
	} else {
		print "<p class=\"er\">�� �� ������� ����� ��� ���� ��� ��������!</p>";
	}

	print '<p class="erok">��������� ���������!</p>';

}

?>
<script language="javascript" type="text/javascript" src="files/alt.js"></script>

	<FIELDSET>
	<LEGEND>��������� ����������� ���������</LEGEND>
		<form action="?p=ref&act=seveset" method="post" enctype="multipart/form-data">
		<table width="600" align="center" border="0" cellpadding="3" cellspacing="0" style="border: solid #cccccc 1px; border-radius: 4px;">
		<tr bgcolor="#dddddd">
			<td height="30"><b>����������</b>:</td>
			<td align="right"><label><input type="radio" name="cfgRefPerc" value="1" <?php if(cfgSET('cfgRefPerc') == "1") { print ' checked="checked"'; } ?> /> �� ����� ������</label> / <label><input type="radio" name="cfgRefPerc" value="2" <?php if(cfgSET('cfgRefPerc') == "2") { print ' checked="checked"'; } ?> /> �� ���������� ���������</label></td>
			<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="�������� �� ���� ������ ����� ��������� �������� ���������. �� ����� ������ ��� �� ����� ���������� ��������� ���������." /></td>
		</tr>
		<tr bgcolor="#eeeeee">
			<td height="30"><b>����������� ������</b>:</td>
			<td align="right">
			http://<?php print $cfgURL; ?>/?<input style="width: 100px;" type="text" name="refname" value="<?php print cfgSET('refname'); ?>">=login
			</td>
			<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������� ������ ���� ����� ����������� ������. ��������, ����� ������� ������� ������ ����� �� ������, �.�. ������ �� �������� ������ ������ ���������." /></td>
		</tr>
		<tr bgcolor="#dddddd">
			<td height="30"><b>������</b>:</td>
			<td align="right"><input type="file" name="banner"></td>
			<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������ ������ ����� �������� ������������� � ������� ����������� ��������� � ����� ��� ���������. � ��������� ������� ���������� ���� ����� �� ������ ����� /img/promo/" /></td>
		</tr>
		</table>
		<table width="600" align="center" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td align="right"><input type="submit" value="���������!" /></td>
		</tr>
		</table>
		</form>
	</FIELDSET>
<hr>

<table width="98%" align="center">
<tr valign="top">
	<td width="50%">

	<FIELDSET>
	<LEGEND>�������� ������ ������������ ������</LEGEND>
		<form action="?p=ref&act=addreflevel" method="post">
		<table align="center">
		<tr>
			<td><b>�������</b>:</td>
			<td align="right"><input type="text" size="35" name="level" /></td>
		</tr>
		<tr>
			<td><b>�������</b>:</td>
			<td align="right"><input type="text" size="35" name="percent" /></td>
		</tr>
		<tr>
			<td><b>����������� �����</b>:</td>
			<td align="right"><input type="text" size="35" name="minsum" /></td>
		</tr>
		<tr>
			<td><b>������� ��� ��������</b>:</td>
			<td align="right"><input type="text" size="35" name="nominsum" /></td>
		</tr>
		<tr>
			<td></td>
			<td align="right"><input type="submit" value=" ��������! " /></td>
		</tr>
		</table>
		</form>
	</FIELDSET>
	</td><td>
	<FIELDSET>
	<LEGEND>����������� ������:</LEGEND>

<table align="center">
<tr bgcolor="#dddddd" align="center">
	<td height="22"><b>�������</b></td>
	<td height="22"><b>Min �����</b></td>
	<td><b>�������</b></td>
	<td><b>% �������</b></td>
	<td width="32"></td>
	<td width="32"></td>
</tr>
<?php
$query	= "SELECT * FROM reflevels ORDER BY id ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {

print '<form action="?p=ref&act=editlevel&id='.$row['id'].'" method="post"><tr>
	<td><input type="text" size="3" name="level" value="'.$row['id'].'" /></td>
	<td><input type="text" size="10" name="minsum" value="'.$row['minsum'].'" /></td>
	<td><input type="text" size="10" name="percent" value="'.$row['sum'].'" /></td>
	<td><input type="text" size="10" name="nominsum" value="'.$row['nominsum'].'" /></td>
	<td align="center"><input type="image" src="images/save.png" width="24" height="24" border="0" title="���������!" /></td>
	<td align="center"><img style="cursor: pointer;" onclick="if(confirm(\'�� ������������� ������ ������� ������ �������?\')) top.location.href=\'?p=ref&act=dellevel&id='.$row['id'].'\';" src="images/delite.png" width="24" height="24" border="0" alt="�������" title="�������" /></td>
</tr><tr><td colspan="6" height="2" bgcolor="#dddddd"></td></tr></form>';

}
?>
</table>

	</FIELDSET>
	</td>
</tr>
</table>
<hr>

<table width="98%" align="center">
<tr valign="top">
	<td width="50%">

	<FIELDSET>
	<LEGEND>�������� ��������� ������</LEGEND>
		<form action="?p=ref&act=addbonus" method="post">
		<table align="center">
		<tr>
			<td><b>��������</b>:</td>
			<td align="right"><input type="text" size="35" name="name" /></td>
		</tr>
		<tr>
			<td><b>����� �����������</b>:</td>
			<td align="right"><input type="text" size="35" name="refsum" /></td>
		</tr>
		<tr>
			<td><b>�����</b>:</td>
			<td align="right"><input type="text" size="35" name="sum" /></td>
		</tr>
		<tr>
			<td></td>
			<td align="right"><input type="submit" value=" ��������! " /></td>
		</tr>
		</table>
		</form>
	</FIELDSET>
	</td><td>
	<FIELDSET>
	<LEGEND>�������� ������:</LEGEND>

<table align="center">
<tr bgcolor="#dddddd" align="center">
	<td height="22"><b>#</b></td>
	<td><b>��������</b></td>
	<td height="22"><b>���. �����</b></td>
	<td height="22"><b>�����</b></td>
	<td width="32"></td>
	<td width="32"></td>
</tr>
<?php
$query	= "SELECT * FROM bonus ORDER BY id ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {

print '<form action="?p=ref&act=editbonus&id='.$row['id'].'" method="post"><tr>
	<td>'.$row['id'].'</td>
	<td><input type="text" size="15" name="name" value="'.$row['name'].'" /></td>
	<td><input type="text" size="8" name="refsum" value="'.$row['refsum'].'" /></td>
	<td><input type="text" size="8" name="sum" value="'.$row['sum'].'" /></td>
	<td align="center"><input type="image" src="images/save.png" width="24" height="24" border="0" title="���������!" /></td>
	<td align="center"><img style="cursor: pointer;" onclick="if(confirm(\'�� ������������� ������ ������� ������ �������?\')) top.location.href=\'?p=ref&act=delbonus&id='.$row['id'].'\';" src="images/delite.png" width="24" height="24" border="0" alt="�������" title="�������" /></td>
</tr><tr><td colspan="6" height="2" bgcolor="#dddddd"></td></tr></form>';

}
?>
</table>

	</FIELDSET>
	</td>
</tr>
</table>
<hr>

	<FIELDSET>
	<LEGEND>���������� �������:</LEGEND>
	<table width="100%">
<?php
$query	= "SELECT * FROM promo ORDER BY id ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {

print '<tr><td><img style="max-width: 800px;" src="/img/promo/'.$row['id'].'.'.$row['type'].'" border="0" alt=""></td>
<td align="center" valign="top" width="30"><img style="cursor: pointer;" onclick="if(confirm(\'�� ������������� ������ ������� ������ ������?\')) top.location.href=\'?p=ref&act=delbanner&id='.$row['id'].'\';" src="images/delite.png" width="24" height="24" border="0" alt="�������" title="�������" /></td></tr>
<tr><td><textarea style="width: 100%;" name="banner" rows="3" cols="50"><a href="http://'.$cfgURL.'/" target="_blank"><img src="http://'.$cfgURL.'/img/promo/'.$row['id'].'.'.$row['type'].'" border="0" alt="" /></a></textarea></td><td></td></tr>
<tr height="3" bgcolor="#eeeeee"><td></td><td></td></tr>';

}
?>
	</table>
	</FIELDSET>