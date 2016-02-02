<?php
defined('ACCESS') or die();
?>

<div class="form-container registration-form">
	<form action="/registration/?action=save" method="post">

		<div class="form-field">
			<label><span class="required"><b>*</b></span> Login:</label>
			<input class="text-input" name="ulogin" type="text" value="<?php print $ulogin; ?>" size="30" maxlength="30" />
		</div>
		<div class="form-field">
			<label><span class="required"><b>*</b></span> <?php print $lang['password']; ?>:</label>
			<input class="text-input" type="password" name="pass" size="30" maxlength="20" />
		</div>
		<div class="form-field">
			<label><span class="required"><b>*</b></span> <?php print $lang['password']; ?> <small>(<?php print $lang['povtor']; ?>)</small>:</label>
			<input class="text-input" type="password" name="repass" size="30" maxlength="20" />
		</div>
		<div class="form-field">
			<label><span class="required"><b>*</b></span> E-mail:</label>
			<input class="text-input" type="text" name="email" value="<?php print $email; ?>" size="30" maxlength="30" />
		</div>
		<div class="form-field">
			<label>Skype:</label>
			<input class="text-input" type="text" name="skype" value="<?php print $skype; ?>" size="30" maxlength="50" />
		</div>
		<div class="form-field">
			<label>ICQ UIN:</label>
			<input class="text-input" type="text" name="icq" value="<?php print $icq; ?>" size="30" maxlength="50" />
		</div>
		<?php
		if($cfgPerfect) {	
		?>
			<div class="form-field">
				<label>PerfectMoney: </label>
				<input class="text-input" type="text" name="pm" value="<?php print $pm; ?>" size="30" maxlength="10" />
			</div>
		<?php
		}
		if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {	
		?>
			<div class="form-field">
				<label>PAYEER.com</label>
				<input class="text-input" type="text" name="pe" value="<?php print $pe; ?>" size="30" maxlength="50" />
			</div>
		<?php
		}

		if(cfgSET('regcaptcha') == "on") {
		?>
			<div class="form-field captcha">
				<a class="pull-left" href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha/?'+Math.random(); return false;">
					<img src="/captcha/" width="70" height="40" border="0" alt="Captcha" title="<?php print $lang['captcha']; ?>" />
				</a>
				<input class="text-input" type="text" name="code" size="17" maxlength="5" />
			</div>
		<?php 
		}		
		?>
			<div class="form-field">
			
					<label>
						<input class="check" type="checkbox" name="yes" value="1" /> 
						<b>
							<a href="/rules/" target="_blank"><?php print $lang['rules']; ?></a> 
							<?php print $lang['rulesyes']; ?>
						</b>
						<span class="required"><b>*</b></font>
					</label> 
					
			</div>


		<div class="form-buttons">
			<input class="subm" type="submit" name="submit" value=" <?php print $lang['registration']; ?> " />
		</div>
	</form>
</div>

<?php

	if($referal) {
		print '<div class="sponsor">Пригласитель: <b>'.$referal.'</b></div>';
	}
?>
<p align="center"><?php print $lang['obyazalovka']; ?></p>