<?php
defined('ACCESS') or die();
?>
<table align="center" border="0">
<form action="/reminder/?action=send" method="post">
	<tr>
		<td><strong>Login</strong>: </td>
	</tr>
	<tr>
		<td><input style="width: 300px;" type="text" name="ulogin" size="30" maxlength="30" /></td>
	</tr>
	<tr>
		<td><strong>E-mail</strong>: </td>
	</tr>
	<tr>
		<td><input style="width: 300px;" type="text" name="email" size="45" maxlength="30" /></td>
	</tr>
	<tr>
		<td>
			<table align="center" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td><a href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha/?'+Math.random(); return false;"><img src="/captcha/" width="70" height="40" border="0" alt="Captcha" title="<?php print $lang['captcha']; ?>" /></a></td>
					<td><input style="width: 80px; height: 40px; text-align: center; font-size: 16px; font-weight: bold;" type="text" name="code" size="5" maxlength="5" /></td>
					<td><input style="height: 40px;"  type="submit" value=" <?php print $lang['send']; ?> " /></td>
				</tr>
			</table>
		</td>
	</tr>
</form>
</table>