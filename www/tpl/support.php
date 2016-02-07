<?php
defined('ACCESS') or die();
?>
<div class="form-container support-form">
	<form action="/support/?action=submit" method="post">
		<div class="form-field">
			<label><span class="required">*</span> Ваш E-mail:</label>
			<input class="text-input" type="text" name="mail" size="50" maxlength="50" value="<?php if(!$mail) { print $user_mail; } else { print $mail; } ?>" />
		</div>
		<div class="form-field">
			<label><span class="required">*</span> <?php print $lang['subject']; ?>:</label>
			<input class="text-input" type="text" name="subj" size="50" maxlength="100" value="<?php print $subj; ?>" />
		</div>		
		<div class="form-field">
			<label><span class="required">*</span> <?php print $lang['textform']; ?>:</label>
			<textarea name="textform" cols="40" rows="10"><?php print $textform; ?></textarea>
		</div>		
		<div class="form-field captcha">
			<a class="pull-left" href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha/?'+Math.random(); return false;">
				<img src="/captcha/" width="70" height="40" border="0" alt="Captcha" title="<?php print $lang['captcha']; ?>" />
			</a>
			<input class="text-input" type="text" name="code" size="17" maxlength="5" />
		</div>		
		<div class="form-buttons">
			<input class="subm" type="submit" value=" <?php print $lang['send']; ?> " />
		</div>	
	</form>
</div>