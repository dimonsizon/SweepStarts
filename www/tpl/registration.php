<?php
defined('ACCESS') or die();
?>
<center>
<form action="/registration/?action=save" method="post">
<table align="center" width="400" border="0" cellpadding="3" cellspacing="1">
	<tr>
		<td><font color="red"><b>*</b></font> Login:</td>
		<td align="center"><input style="width: 200px;" type="text" name="ulogin" value="<?php print $ulogin; ?>" size="30" maxlength="30" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> <?php print $lang['password']; ?>:</td><td align="center"><input style="width: 200px;" type="password" name="pass" size="30" maxlength="20" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> <?php print $lang['password']; ?> <small>(<?php print $lang['povtor']; ?>)</small>:</td>
		<td align="center"><input style="width: 200px;" type="password" name="repass" size="30" maxlength="20" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> E-mail:</td>
		<td align="center"><input style="width: 200px;" type="text" name="email" value="<?php print $email; ?>" size="30" maxlength="30" /></td>
	</tr>
	<tr>
		<td>Skype:</td>
		<td align="center"><input style="width: 200px;" type="text" name="skype" value="<?php print $skype; ?>" size="30" maxlength="50" /></td>
	</tr>
	<tr>
		<td>ICQ UIN:</td>
		<td align="center"><input style="width: 200px;" type="text" name="icq" value="<?php print $icq; ?>" size="30" maxlength="50" /></td>
	</tr>
<?php
if($cfgPerfect) {	
?>
	<tr>
		<td>PerfectMoney: </td><td align="center"><input style="width: 200px;" type="text" name="pm" value="<?php print $pm; ?>" size="30" maxlength="10" /></td>
	</tr>
<?php
}
if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {	
?>
	<tr>
		<td>PAYEER.com</td><td align="center"><input style="width: 200px;" type="text" name="pe" value="<?php print $pe; ?>" size="30" maxlength="50" /></td>
	</tr>
<?php
}

if(cfgSET('regcaptcha') == "on") {
?>
	<tr>
		<td align="right"><a href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha/?'+Math.random(); return false;"><img src="/captcha/" width="70" height="40" border="0" alt="Captcha" title="<?php print $lang['captcha']; ?>" /></a></td><td align="center"><input style="width: 200px; height: 40px; text-align: center; font-size: 16px; font-weight: bold;" type="text" name="code" size="17" maxlength="5" /></td>
	</tr>
<?php 
}		
?>
	<tr>
		<td colspan="2" align="center"><label><input class="check" type="checkbox" name="yes" value="1" /> <b><a href="/rules/" target="_blank"><?php print $lang['rules']; ?></a> <?php print $lang['rulesyes']; ?></b></label> <font color="red"><b>*</b></font></td>
	</tr>
</table>
</center>
<div align="center" style="padding-top: 10px; padding-bottom: 15px;"><input class="subm" type="submit" name="submit" value=" <?php print $lang['registration']; ?> " /></div>
</form>
<?php

	if($referal) {
		print '<p class="warn">UPLINE: <b>'.$referal.'</b></p>';
	}
?>
<p align="center"><?php print $lang['obyazalovka']; ?></p>