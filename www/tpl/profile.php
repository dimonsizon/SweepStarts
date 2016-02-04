<?php defined('ACCESS') or die(); ?>
<form action="/profile/?action=save" method="post">
<table align="center" width="350" border="0" cellpadding="2" cellspacing="1">
<?php if($mycurrency == 1) { ?>
	<tr>
		<td>Ваша валюта: </td>
		<td align="right">					<select name="currency">
<?php
$sql	 = 'SELECT `id`, `style`, `name` FROM `currency`';
$rs		 = mysql_query($sql);
while($a2 = mysql_fetch_array($rs)) {
	print '<option value="'.$a2['id'].'"';
	if($mycurrency == $a2['id']) { print ' selected'; }
	print '>'.$a2['name'].'</option>';
}
?>
					</select></td>
	</tr>
<?php } ?>
	<tr>
		<td><?php print $lang['password']; ?>: </td>
		<td align="right"><input type='password' name='pass_1' size="30" /></td>
	</tr>
	<tr>
		<td><?php print $lang['password']; ?> <small>(<?php print $lang['povtor']; ?>)</small>: </td>
		<td align="right"><input type='password' name='pass_2' size="30" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> E-mail:</td>
		<td align="right"><input type='text' name='email' value='<?php print $a['mail']; ?>' size="30" maxlength="30" /></td>
	</tr>
	<tr>
		<td>Skype:</td>
		<td align="right"><input type='text' name='skype' value='<?php print $a['skype']; ?>' size="30" maxlength="50" /></td>
	</tr>
	<tr>
		<td>ICQ UIN:</td>
		<td align="right"><input type='text' name='icq' value='<?php print $a['icq']; ?>' size="30" maxlength="20" /></td>
	</tr>
	<tr>
		<td>Телефон:</td>
		<td align="right"><input type='text' name='phone' value='<?php print $a['phone']; ?>' size="30" maxlength="20" /></td>
	</tr>
	<tr>
		<td>Соц. сеть:</td>
		<td align="right"><input type='text' name='social' value='<?php print $a['social']; ?>' size="30" maxlength="250" /></td>
	</tr>
	<tr>
		<td>Название банка:</td>
		<td align="right"><input type='text' name='bankName' value='<?php print $a['bankName']; ?>' size="30" maxlength="250" /></td>
	</tr>
<?php
if($cfgPerfect) {	
?>
	<tr>
		<td>PerfectMoney: </td>
		<td align="right"><input type='text' name='pm' value='<?php print $a['pm']; ?>' size="30" maxlength="8" <?php if($a['pm']) { print 'disabled'; } ?> /></td>
	</tr>
<?php
}
if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {	
?>
	<tr>
		<td>PAYEER.com</td><td align="right"><input type="text" name="pe" value="<?php print $a['pe']; ?>" size="30" maxlength="50" <?php if($a['pe']) { print 'disabled'; } ?>  /></td>
	</tr>
<?php
}
?>
<tr>
	<td></td>
	<td align="right"><input class="subm" type="submit" name="submit" value=" <?php print $lang['save']; ?> " /></td>
</tr>
</table>
</form>