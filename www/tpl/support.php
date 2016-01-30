<?php
defined('ACCESS') or die();
?>
<form action="/support/?action=submit" method="post">
<center>
<table width="400" align="center" cellpadding="1" cellspacing="1" border="0" style="margin-top: 15px;">
	<tr>
		<td><font color="red"><b>*</b></font> E-mail:</td>
	</tr>
	<tr>
		<td><input style="width: 500px;" type="text" name="mail" size="50" maxlength="50" value="<?php if(!$mail) { print $user_mail; } else { print $mail; } ?>" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> <?php print $lang['subject']; ?>:</td>
	</tr>
	<tr>
		<td><input style="width: 500px;" type="text" name="subj" size="50" maxlength="100" value="<?php print $subj; ?>" /></td>
	</tr>
	<tr>
		<td><font color="red"><b>*</b></font> <?php print $lang['textform']; ?>:</td>
	</tr>
	<tr>
		<td><textarea style="width: 500px; margin-left: 0px;" name="textform" cols="40" rows="10"><?php print $textform; ?></textarea></td>
	</tr>
	<tr>
		<td>
			<table align="right" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td><a href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha/?'+Math.random(); return false;"><img src="/captcha/" width="70" height="40" border="0" alt="Captcha" title="<?php print $lang['captcha']; ?>" /></a></td>
					<td><input style="width: 111px; height: 40px; font-size: 16px; font-weight: bold; text-align: center;" type="text" name="code" size="17" maxlength="5" /></td>
					<td><input class="subm" style="height: 40px;" type="submit" value=" <?php print $lang['send']; ?> " /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>
</form>