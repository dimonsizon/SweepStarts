<?php
defined('ACCESS') or die();
if($_GET['action'] == "edit") {

	$cfgPerfect				= htmlspecialchars($_POST['cfgPerfect'], ENT_QUOTES, '');
	if($cfgPerfect) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPerfect.'" WHERE cfgname = "cfgPerfect" LIMIT 1');
		mysql_query('UPDATE `paysystems` SET `purse` = "'.$cfgPerfect.'" WHERE id = 1 LIMIT 1');
	}

	$cfgPEsid				= htmlspecialchars($_POST['cfgPEsid'], ENT_QUOTES, '');
	if($cfgPEsid) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPEsid.'" WHERE cfgname = "cfgPEsid" LIMIT 1');
	}

	$cfgPEkey				= htmlspecialchars($_POST['cfgPEkey'], ENT_QUOTES, '');
	if($cfgPEkey) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPEkey.'" WHERE cfgname = "cfgPEkey" LIMIT 1');
	}

	$cfgPAYEE_NAME			= htmlspecialchars($_POST['cfgPAYEE_NAME'], ENT_QUOTES, '');
	if($cfgPAYEE_NAME) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPAYEE_NAME.'" WHERE cfgname = "cfgPAYEE_NAME" LIMIT 1');
	}

	$ALTERNATE_PHRASE_HASH	= trim(htmlspecialchars($_POST['ALTERNATE_PHRASE_HASH'], ENT_QUOTES, ''));
	if($ALTERNATE_PHRASE_HASH) {
		mysql_query('UPDATE `settings` SET `data` = "'.$ALTERNATE_PHRASE_HASH.'" WHERE cfgname = "ALTERNATE_PHRASE_HASH" LIMIT 1');
	}

	$cfgAutoPay				= htmlspecialchars($_POST['cfgAutoPay'], ENT_QUOTES, '');
	if($cfgAutoPay) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgAutoPay.'" WHERE cfgname = "cfgAutoPay" LIMIT 1');
	}

	$cfgPMID				= htmlspecialchars($_POST['cfgPMID'], ENT_QUOTES, '');
	if($cfgPMID) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPMID.'" WHERE cfgname = "cfgPMID" LIMIT 1');
	}

	$cfgPMpass				= htmlspecialchars($_POST['cfgPMpass'], ENT_QUOTES, '');
	if($cfgPMpass) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPMpass.'" WHERE cfgname = "cfgPMpass" LIMIT 1');
	}

	$cfgAutoPayPE			= htmlspecialchars($_POST['cfgAutoPayPE'], ENT_QUOTES, '');
	if($cfgAutoPayPE) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgAutoPayPE.'" WHERE cfgname = "cfgAutoPayPE" LIMIT 1');
	}

	$cfgPEAcc				= htmlspecialchars($_POST['cfgPEAcc'], ENT_QUOTES, '');
	if($cfgPEAcc) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPEAcc.'" WHERE cfgname = "cfgPEAcc" LIMIT 1');
	}

	$cfgPEidAPI				= htmlspecialchars($_POST['cfgPEidAPI'], ENT_QUOTES, '');
	if($cfgPEidAPI) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPEidAPI.'" WHERE cfgname = "cfgPEidAPI" LIMIT 1');
	}

	$cfgPEapiKey			= htmlspecialchars($_POST['cfgPEapiKey'], ENT_QUOTES, '');
	if($cfgPEapiKey) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPEapiKey.'" WHERE cfgname = "cfgPEapiKey" LIMIT 1');
	}

	$autopercent			= htmlspecialchars($_POST['autopercent'], ENT_QUOTES, '');
	if($autopercent) {
		mysql_query('UPDATE `settings` SET `data` = "'.$autopercent.'" WHERE cfgname = "autopercent" LIMIT 1');
	}


	$cfgOutAdminPercent = sprintf("%01.2f", str_replace(',', '.', $_POST['cfgOutAdminPercent']));

	if($cfgOutAdminPercent >= 0 && $cfgOutAdminPercent <= 100) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgOutAdminPercent.'" WHERE cfgname = "cfgOutAdminPercent" LIMIT 1');
	} else {
		print '<p class="er">������� �������������� ������ ���� ���������� � ��������� �� 0 �� 100</p>';
	}

	$cfgInstant			= htmlspecialchars($_POST['cfgInstant'], ENT_QUOTES, '');
	if($cfgInstant) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgInstant.'" WHERE cfgname = "cfgInstant" LIMIT 1');
	}

	$AdminPMpurse			= htmlspecialchars($_POST['AdminPMpurse'], ENT_QUOTES, '');
	if($AdminPMpurse != $cfgPerfect) {
		mysql_query('UPDATE `settings` SET `data` = "'.$AdminPMpurse.'" WHERE cfgname = "AdminPMpurse" LIMIT 1');
	} elseif($AdminPMpurse) {
		print '<p class="er">��������� PerfectMoney �������, ������ ���������� �� �������� ������ �������</p>';
	}

	print '<p class="erok">������ ���������!</p>';

}
?>
<script language="javascript" type="text/javascript" src="files/alt.js"></script>
<FIELDSET style="border: solid #666666 1px; padding: 10px;">
<LEGEND><b>��������� ��������� ������:</b></LEGEND>
<form action="?p=merchant&action=edit" method="post">
<table align="center" width="700" border="0" cellpadding="3" cellspacing="0" style="border: solid #cccccc 1px;">
<tr bgcolor="#999999">
	<td height="30" colspan="3" align="center"><b>��������� �������������� ���������� ( SCI ):</b></td>
</tr>
<tr bgcolor="#cccccc">
	<td height="25" colspan="3" align="center"><b style="color: #666666;">��������� PERFECTMONEY</b></td>
</tr>

<tr bgcolor="#dddddd">
	<td><b>PerfectMoney ����</b>:</td>
	<td align="right"><input class="input" style="width: 295px;" type="text" name="cfgPerfect" size="70" maxlength="250" value="<?php print cfgSET('cfgPerfect'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="����� ����� � ������� PerfectMoney. ���������� � ������� U (�� ������ � ID). ���� ������ ���� �������� ������, ������ ������� �� ����� �������������� � �������." /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>ALTERNATE_PHRASE_HASH</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="ALTERNATE_PHRASE_HASH" size="70" maxlength="250" value="<?php print cfgSET('ALTERNATE_PHRASE_HASH'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="MD5 ���, ��������� ����� ��� ��������� �������� ����� ������� PerfectMoney. � ���������� �������� PerfectMoney ���������� �������� ����: �������������� ������� ����� (Alternate Merchant Passphrase Hash) � ��������� ���. MD5 ��� �� �������� �����, ������ ������������� � ������� ����������� � �������� ��� � ������ ���� (��������! �� ������� ��������� MD5 � ����������� ������, ������� ���� �� ����� PerfectMoney)" /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>Store name</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPAYEE_NAME" size="70" maxlength="250" value="<?php print cfgSET('cfgPAYEE_NAME'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������ �������� ������������ ��� ��� �������� � ������� PerfectMoney." /></td>
</tr>
<tr bgcolor="#cccccc">
	<td height="25" colspan="3" align="center"><b style="color: #666666;">��������� PAYEER</b></td>
</tr>

<tr bgcolor="#dddddd">
	<td><b>ID ��������</b>:</td>
	<td align="right"><input class="input" style="width: 295px;" type="text" name="cfgPEsid" size="70" maxlength="250" value="<?php print cfgSET('cfgPEsid'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������� ��� ID �������� � ������� payeer.com" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>��������� ���</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPEkey" size="70" maxlength="250" value="<?php print cfgSET('cfgPEkey'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="��������� ���� � ������� payeer.com (��� ��������. �� ������� � master key)" /></td>
</tr>

<tr bgcolor="#999999">
	<td height="30" colspan="3" align="center"><b>��������� �������������� ������ ( API ):</b></td>
</tr>
<tr bgcolor="#cccccc">
	<td height="25" colspan="3" align="center"><b style="color: #666666;">��������� PERFECTMONEY</b></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>����������� PerfectMoney</b>:</td>
	<td align="right"><label><input type="radio" name="cfgAutoPay" value="on" <?php if(cfgSET('cfgAutoPay') == "on") { print ' checked="checked"'; } ?> /> <font color="green">��������</font></label> / <label><input type="radio" name="cfgAutoPay" value="off" <?php if(cfgSET('cfgAutoPay') == "off") { print ' checked="checked"'; } ?> /> <font color="red">���������</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="���� �� �������� �����������, �������� ����� ������������ ������������� ������������� ����� ������ ������. ���� ���������, �� ��� ������ ����� ���������� �� �������� ������������� � ����� �������� � �������: ����� �������" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>��� ID � PerfectMoney</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPMID" size="70" maxlength="250" value="<?php print cfgSET('cfgPMID'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="��� ID � ��������� ������� PerfectMoney. ��������� ��� ����������� �� API." /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>��� ������ � PerfectMoney</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPMpass" size="70" maxlength="250" value="<?php print cfgSET('cfgPMpass'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="��� ������ ����� � ��������� ������� PerfectMoney. ��������� ��� ����������� �� API. ���� �� �� ����������� ����������, ����������� �� ��������� ������ ����" /></td>
</tr>

<tr bgcolor="#cccccc">
	<td height="25" colspan="3" align="center"><b style="color: #666666;">��������� PAYEER.com</b></td>
</tr>

<tr bgcolor="#dddddd">
	<td><b>����������� PAYEER.com</b>:</td>
	<td align="right"><label><input type="radio" name="cfgAutoPayPE" value="on" <?php if(cfgSET('cfgAutoPayPE') == "on") { print ' checked="checked"'; } ?> /> <font color="green">��������</font></label> / <label><input type="radio" name="cfgAutoPayPE" value="off" <?php if(cfgSET('cfgAutoPayPE') == "off") { print ' checked="checked"'; } ?> /> <font color="red">���������</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="���� �� �������� �����������, �������� ����� ������������ ������������� ������������� ����� ������ ������. ���� ���������, �� ��� ������ ����� ���������� �� �������� ������������� � ����� �������� � �������: ����� �������" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>��� ����� ����� � Payeer</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPEAcc" size="70" maxlength="250" value="<?php print cfgSET('cfgPEAcc'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������� ��� ����� ����� � ������� PAYEER.com. ��������� ��� ����������� �� API." /></td>
</tr>
<tr bgcolor="#dddddd">
	<td><b>��� ID ��������</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPEidAPI" size="70" maxlength="250" value="<?php print cfgSET('cfgPEidAPI'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������� ��� ID �������� � API payeer.com" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>��������� API ����</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgPEapiKey" size="70" maxlength="250" value="<?php print cfgSET('cfgPEapiKey'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������� ��� ��������� ����, �������� �� ������� � ���������� API � ��������" /></td>
</tr>

<tr bgcolor="#999999">
	<td height="30" colspan="3" align="center"><b>��������� ������������ �� ��������� ������� PerfectMoney:</b></td>
</tr>

<tr bgcolor="#dddddd">
	<td><b>������� �� ����� ���������� - ������</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="cfgOutAdminPercent" size="70" maxlength="6" value="<?php print cfgSET('cfgOutAdminPercent'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="�������, ����� ������� �� ����� ���������� ���������� �� ����� ����� ��������������. ���� �� �� ������ ���������� �������, �������� �������� 0.00. ��������! ��� ��������������� �������� �� ���� ��������������, � ��� ������ ���� ������� ��������� API" /></td>
</tr>
<tr bgcolor="#eeeeee">
	<td><b>��������� PerfectMoney ����</b>:</td>
	<td align="right"><input style="width: 295px;" type="text" name="AdminPMpurse" size="70" maxlength="8" value="<?php print cfgSET('AdminPMpurse'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="������� ����� ����� PerfectMoney ���� �������������� �������. ��������! ����� ����� ������ ���������� �� ������ ����� ������ ������� � ���������� � ������� U" /></td>
</tr>
</table>
<table align="center" width="708" border="0">
	<tr>
		<td align="right"><input type="submit" value=" ���������! " /></td>
	</tr>
</table>
</form>
</FIELDSET>