<?php defined('ACCESS') or die(); ?>
<div class="form-container profile-form">
	<form action="/profile/?action=save" method="post">
		
		<?php if($mycurrency == 1) { ?>
			<div class="form-field">
				<label>Ваша валюта:</label>
				<select name="currency">
					<?php
					$sql	 = 'SELECT `id`, `style`, `name` FROM `currency`';
					$rs		 = mysql_query($sql);
					while($a2 = mysql_fetch_array($rs)) {
						print '<option value="'.$a2['id'].'"';
						if($mycurrency == $a2['id']) { print ' selected'; }
						print '>'.$a2['name'].'</option>';
					}
					?>					
				</select>
			</div>
		<?php } ?>
		<div class="form-field">
			<label><?php print $lang['password']; ?>:</label>
			<input class="text-input" type='password' name='pass_1' size="30" />
		</div>
		<div class="form-field">
			<label><?php print $lang['password']; ?> <small>(<?php print $lang['povtor']; ?>)</small>: </label>
			<input class="text-input" type='password' name='pass_2' size="30" />
		</div>
		<div class="form-field">
			<label><font color="red"><b>*</b></font> E-mail:</label>
			<input class="text-input" type='text' name='email' value='<?php print $a['mail']; ?>' size="30" maxlength="30" />
		</div>
		<div class="form-field">
			<label>Skype:</label>
			<input class="text-input" type='text' name='skype' value='<?php print $a['skype']; ?>' size="30" maxlength="50" />
		</div>
		<div class="form-field">
			<label>ICQ UIN:</label>
			<input class="text-input" type='text' name='icq' value='<?php print $a['icq']; ?>' size="30" maxlength="20" />
		</div>
		<div class="form-field">
			<label>Телефон:</label>
			<input class="text-input" type='text' name='phone' value='<?php print $a['phone']; ?>' size="30" maxlength="20" />
		</div>
		<h4 class="top20">Банковские данные</h4>
		<div class="form-field">
			<label>Соц. сеть:</label>
			<input class="text-input" type='text' name='social' value='<?php print $a['social']; ?>' size="30" maxlength="250" />
		</div>
		<div class="form-field">
			<label>Название банка:</label>
			<input class="text-input" type='text' name='bankName' value='<?php print $a['bankName']; ?>' size="30" maxlength="250" />
		</div>
		<div class="form-field">
			<label>Номер карты:</label>
			<input class="text-input" type='text' name='bankCardNumber' value='<?php print $a['bankCardNumber']; ?>' size="30" maxlength="250" />
		</div>
		<div class="form-field">
			<label>Получатель:</label>
			<input class="text-input" type='text' name='bankUserName' value='<?php print $a['bankUserName']; ?>' size="30" maxlength="250" />
		</div>
		<?php
			if($cfgPerfect) {	
			?>
				<div class="form-field">
					<label>PerfectMoney: </label>
					<input class="text-input" type='text' name='pm' value='<?php print $a['pm']; ?>' size="30" maxlength="8" <?php if($a['pm']) { print 'disabled'; } ?> />
				</div>
			<?php
			}
			if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {	
			?>
				<div class="form-field">
					<label>PAYEER.com</label>
					<input class="text-input" type="text" name="pe" value="<?php print $a['pe']; ?>" size="30" maxlength="50" <?php if($a['pe']) { print 'disabled'; } ?>  />
				</div>
			<?php
			}
		?>
		<div class="form-buttons">
			<input class="subm" type="submit" name="submit" value=" <?php print $lang['save']; ?> " />
		</div>
		
	</form>
</div>