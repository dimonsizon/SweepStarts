<?php
defined('ACCESS') or die();

// ������� �������� ����������� ��������
function sch_special_chars($str)
{
	$spch_check_result = 0;
	$special_chars = array("?",">","<","&","|","+",";",":","'","=","/","\"","$","!","@","#","%","^","*","(",")","-","�");
	$str_lenght = strlen($str);
	$i = 0;
	for($i = 0;$i <= $str_lenght;$i++)
	{
		$char_from_str = substr($str,$i,1);
		$check_spch = in_array($char_from_str,$special_chars);
		if($check_spch != false)
		{
 			$spch_check_result = 1;
			break;
		}
	}
if($spch_check_result != 0)
 return 1;
else
 return 0;
}
// ����� ������ �������

	$folder			= "";
	$title			= "";
	$body			= "";
	$keywords		= "";
	$description	= "";
	$lite			= "";

$action = $_GET['action'];
if ($action == "add") {
	$folder			= gs_html(strtolower($_POST['folder']));
	$title			= gs_html($_POST['title']);
	$keywords		= gs_html($_POST['keywords']);
	$description	= gs_html($_POST['description']);
	$type			= intval($_POST['type']);
	$part			= intval($_POST['part']);
	$nbsp			= "";

	if(!$folder || !$title) {
		print "<p class=\"er\">��������� ���� ������������ ��� ����������</p>";
	}
	elseif(preg_match("[�-��-�]",$folder)) {
		print "<p class=\"er\">� ������ ��������� ������� ������ ���������� ��������!</p>";
	}
	elseif(sch_special_chars($folder) != 0) {
		print "<p class=\"er\">� ������ ��������� �����������!</p>";
	} 
	elseif($folder == "admin" || $folder == "files" || $folder == "img" || $folder == "includes" || $folder == "tpl" || $folder == "modules" || $folder == "captcha") {
		print "<p class=\"er\">���������� ������� �������� � ������ ������!</p>";
	} elseif(mysql_num_rows(mysql_query("SELECT * FROM `pages` WHERE `path` = '".$folder."'"))) {
		print "<p class=\"er\">�������� � ����� ������ ��� ����������!</p>";
	} else {

			$sql = "INSERT INTO `pages` (`path`, `title`, `keywords`, `description`, `type`, `part`) VALUES ('".$folder."', '".$title."', '".$keywords."', '".$description."', 1, ".$part.")";

			mysql_query($sql);
			$lastid	= mysql_insert_id();

				print "<p class=\"erok\">�������� <a href=\"http://".$_SERVER['SERVER_NAME']."/".$folder."/\" target=\"_blank\">http://".$_SERVER['SERVER_NAME']."/".$folder."/</a> �������!</p>";

				$folder			= "";
				$title			= "";
				$body			= "";
				$keywords		= "";
				$description	= "";

	}
}

?>
<FIELDSET>
<LEGEND><b>�������� ��������� ��������</b></LEGEND>
<form action="?p=add_page&action=add" method="post">
<table width="650" bgcolor="#eeeeee" align="center" border="0" style="border: solid #cccccc 1px;">
	<tr>
		<td width="130"><font color="red"><b>*</b></font> ������:</td>
		<td><b>http://<?php print $_SERVER['SERVER_NAME']; ?>/</b><input type="text" name="folder" size="30" maxlength="20" value="<?php print $folder; ?>" />/</td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> ���������:</td>
		<td><input style="width: 500px;" type="text" name="title" size="80" maxlength="50" value="<?php print $title; ?>" /></td>
	</tr>
	<tr>
		<td>�������� �����:</td>
		<td><input style="width: 500px;" type="text" name="keywords" size="80" maxlength="250" value="<?php print $keywords; ?>" /></td>
	</tr>
	<tr>
		<td>��������&nbsp;��������:</td>
		<td><input style="width: 500px;" type="text" name="description" size="80" maxlength="250" value="<?php print $description; ?>" /></td>
	</tr>
	<tr>
		<td>�������:</td>
		<td>
			<select name="part" style="width: 500px;">
				<option value=0>��� ��������</option>
<?php
$result	= mysql_query("SELECT * FROM pages WHERE part = 0 ORDER BY title ASC");
while($row = mysql_fetch_array($result)) {
	$idpart		= $row['id'];
	$titlepart	= $row['title'];
	print "<option value=".$idpart.">".$titlepart."</option>";
}
?>
			</select>
		</td>
	</tr>
</table>
<table align="center" width="660" border="0">
	<tr>
		<td align="right"><input type="submit" value="�������!" /></td>
	</tr>
</table>
</form>
</FIELDSET>