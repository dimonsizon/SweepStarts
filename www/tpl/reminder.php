<?php
defined('ACCESS') or die();
?>
<div class="form-container remember-form">
	<form action="/reminder/?action=send" method="post">
		<div class="form-field">
			<label>Login: </label>
			<input class="text-input" type="text" name="ulogin" size="30" maxlength="30" />
		</div>
		<div class="form-field">
			<label>E-mail: </label>
			<input class="text-input" type="text" name="email" size="45" maxlength="30" />
		</div>
		<div class="form-field captcha">
			<a class="pull-left" href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha/?'+Math.random(); return false;">
				<img src="/captcha/" width="70" height="40" border="0" alt="Captcha" title="<?php print $lang['captcha']; ?>" />
			</a>
			<input class="text-input" type="text" name="code" size="5" maxlength="5" />
		</div>
		<div class="form-buttons">			
			<input type="submit" value=" <?php print $lang['send']; ?> " />			
		</tr>
	</form>
</div>