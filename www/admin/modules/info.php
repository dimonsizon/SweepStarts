<?php
defined('ACCESS') or die();
?>
<script src="files/jquery.js"></script>
<script type="text/javascript">
<!--
	var h=<?php print intval(date('G')); ?>;
	var m=<?php print intval(date('i')); ?>;
	var s=<?php print intval(date('s')); ?>;
	setInterval("showtime2()",1000);

	function showtime2() {
		s++;
		if (s>=60) {
			s=0;
			m++;
			if (m>=60) {
				m=0;
				h++;
				if (h>=24) h=0;
			}
		}
		s = s+"";
		m = m+"";
		h = h+"";
		if (s.length<2) s = "0"+s;
		if (m.length<2) m = "0"+m;
		if (h.length<2) h = "0"+h;
		document.getElementById("time2").innerHTML = h+":"+m+":"+s;
	}

	$(document).ready(function(){
	$('#getContent').click(function(){
	$.ajax({
		url: "/admin/modules/serverip.php",
		cache: false,
		beforeSend: function() {
			$('#divContent').html('<center><img src="images/loader.gif" width="16" height="16" border="0" alt="" /> Определяем IP адрес...</center>');
		},
		success: function(html){
			$("#divContent").html(html);
		}
	});
	return false;
	});
	});
-->
</script>
<?php
print '<table width="100%">
<tr height="20">
	<td colspan="2">IP адрес сервера</td>
	<td colspan="2">phpinfo();</td>
	<td colspan="2">Дата / время сервера:</td>
</tr>
<tr height="3" bgcolor="#dddddd">
	<td colspan="2"></td>
	<td colspan="2"></td>
	<td colspan="2"></td>
</tr>
<tr>
	<td width="20" height="30"><img src="images/serverip_ico.png" width="16" height="16" border="0" alt="" /></td>
	<td width="33%"><a href="#" id="getContent">Показать</a></td>
	<td width="20"><img src="images/phpinfo_ico.png" width="16" height="16" border="0" alt="" /></td>
	<td width="33%"><a href="modules/phpinf.php" target="_blank">Открыть</a></td>
	<td width="20"><img src="images/clock_ico.png" width="16" height="16" border="0" alt="" /></td>
	<td><b style="float: left; padding-right: 7px;">'.date("d.m.Y").'</b> <div id="time2"></div></td>
</tr>
<tr height="3" bgcolor="#dddddd">
	<td colspan="2"></td>
	<td colspan="2"></td>
	<td colspan="2"></td>
</tr>
</table>
<div id="divContent"></div>';

if($_GET['action'] == "md5") {
	print '<p class="erok">Ваш ALTERNATE_PHRASE_HASH = <b><u>'.strtoupper(md5(trim($_POST['md']))).'</u></b></p>';
}

?>
<FIELDSET style="margin-top: 15px;">
<LEGEND><b>MD5-генератор альтернативной кодовой фразы в PerfectMoney:</b></LEGEND>
<form method="post" action="?p=info&action=md5">
<table bgcolor="#dddddd" align="center">
	<tr>
		<td>Альтернативная кодовая фраза в PerfectMoney:</td>
		<td><input type="text" name="md" size="50" /></td>
		<td><input style="margin-top: 0px; height: 26px;" type="submit" value=" ГЕНЕРИРОВАТЬ " /></td>
	</tr>
</table>
</form>
</FIELDSET>