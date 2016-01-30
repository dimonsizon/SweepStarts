<?php
defined('ACCESS') or die();
if($_GET['action'] == "edit") {

	$cfgOnOff				= htmlspecialchars($_POST['cfgOnOff'], ENT_QUOTES, '');
	if($cfgOnOff) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgOnOff.'" WHERE cfgname = "cfgOnOff" LIMIT 1');
	}

	$adminmail				= htmlspecialchars($_POST['adminmail'], ENT_QUOTES, '');
	if($adminmail) {
		mysql_query('UPDATE `settings` SET `data` = "'.$adminmail.'" WHERE cfgname = "adminmail" LIMIT 1');
	}

	$projectname			= htmlspecialchars($_POST['projectname'], ENT_QUOTES, '');
	if($projectname) {
		mysql_query('UPDATE `settings` SET `data` = "'.$projectname.'" WHERE cfgname = "projectname" LIMIT 1');
	}

	$cfgReInv				= htmlspecialchars($_POST['cfgReInv'], ENT_QUOTES, '');
	if($cfgReInv) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgReInv.'" WHERE cfgname = "cfgReInv" LIMIT 1');
	}

	$cfgSSL					= htmlspecialchars($_POST['cfgSSL'], ENT_QUOTES, '');
	if($cfgSSL) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgSSL.'" WHERE cfgname = "cfgSSL" LIMIT 1');
	}

	$cfgMailConf			= htmlspecialchars($_POST['cfgMailConf'], ENT_QUOTES, '');
	if($cfgMailConf) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgMailConf.'" WHERE cfgname = "cfgMailConf" LIMIT 1');
	}

	$cfgMinOut				= sprintf("%01.2f", $_POST['cfgMinOut']);
	if($cfgMinOut) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgMinOut.'" WHERE cfgname = "cfgMinOut" LIMIT 1');
	}

	$cfgMaxOut				= sprintf("%01.2f", $_POST['cfgMaxOut']);
	if($cfgMaxOut) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgMaxOut.'" WHERE cfgname = "cfgMaxOut" LIMIT 1');
	}

	$cfgCountOut			= intval($_POST['cfgCountOut']);
	if($cfgCountOut) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgCountOut.'" WHERE cfgname = "cfgCountOut" LIMIT 1');
	}

	$cfgPercentOut			= sprintf("%01.2f", $_POST['cfgPercentOut']);
	if($cfgPercentOut) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgPercentOut.'" WHERE cfgname = "cfgPercentOut" LIMIT 1');
	}

	$cfgLang				= htmlspecialchars($_POST['cfgLang'], ENT_QUOTES, '');
	if($cfgLang) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgLang.'" WHERE cfgname = "cfgLang" LIMIT 1');
	}

	$regcaptcha				= htmlspecialchars($_POST['regcaptcha'], ENT_QUOTES, '');
	if($regcaptcha) {
		mysql_query('UPDATE `settings` SET `data` = "'.$regcaptcha.'" WHERE cfgname = "regcaptcha" LIMIT 1');
	}

	$cfgMonCur				= htmlspecialchars($_POST['cfgMonCur'], ENT_QUOTES, '');
	if($cfgMonCur) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgMonCur.'" WHERE cfgname = "cfgMonCur" LIMIT 1');
	}

	$h = intval($_POST['h']);
	$i = intval($_POST['i']);
	$d = intval($_POST['d']);
	$m = intval($_POST['m']);
	$ye = intval($_POST['ye']);

	$datestart				= mktime($h,$i,0,$m,$d,$ye);
	if($datestart) {
		mysql_query('UPDATE `settings` SET `data` = "'.$datestart.'" WHERE cfgname = "datestart" LIMIT 1');
	}

	$autopercent			= htmlspecialchars($_POST['autopercent'], ENT_QUOTES, '');
	if($autopercent) {
		mysql_query('UPDATE `settings` SET `data` = "'.$autopercent.'" WHERE cfgname = "autopercent" LIMIT 1');
	}

	$cfgTrans			= htmlspecialchars($_POST['cfgTrans'], ENT_QUOTES, '');
	if($cfgTrans) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgTrans.'" WHERE cfgname = "cfgTrans" LIMIT 1');
	}

	$cfgTransPercent = sprintf("%01.2f", str_replace(',', '.', $_POST['cfgTransPercent']));

	if($cfgTransPercent >= 0 && $cfgTransPercent <= 100) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgTransPercent.'" WHERE cfgname = "cfgTransPercent" LIMIT 1');
	} else {
		print '<p class="er">Процент администратору от суммы перевода, должен быть установлен в диапазоне от 0 до 100</p>';
	}

	$cfgBonusBal			= htmlspecialchars($_POST['cfgBonusBal'], ENT_QUOTES, '');
	if($cfgBonusBal) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgBonusBal.'" WHERE cfgname = "cfgBonusBal" LIMIT 1');
	}

	$cfgBonusReg			= sprintf("%01.2f", $_POST['cfgBonusReg']);
	if($cfgBonusReg) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgBonusReg.'" WHERE cfgname = "cfgBonusReg" LIMIT 1');
	}

	$cfgBonusOnOff			= htmlspecialchars($_POST['cfgBonusOnOff'], ENT_QUOTES, '');
	if($cfgBonusOnOff) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgBonusOnOff.'" WHERE cfgname = "cfgBonusOnOff" LIMIT 1');
	}
	$cfgBonusMin			= intval($_POST['cfgBonusMin']);
	if($cfgBonusMin) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgBonusMin.'" WHERE cfgname = "cfgBonusMin" LIMIT 1');
	}
	$cfgBonusMax			= intval($_POST['cfgBonusMax']);
	if($cfgBonusMax) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgBonusMax.'" WHERE cfgname = "cfgBonusMax" LIMIT 1');
	}
	$cfgBonusPeriod			= intval($_POST['cfgBonusPeriod']);
	if($cfgBonusPeriod) {
		mysql_query('UPDATE `settings` SET `data` = "'.$cfgBonusPeriod.'" WHERE cfgname = "cfgBonusPeriod" LIMIT 1');
	}

	print '<p class="erok">Данные сохранены!</p>';

}

?>
<script language="javascript" type="text/javascript" src="files/alt.js"></script>
<FIELDSET style="border: solid #666666 1px; padding: 10px;">
<LEGEND><b>НАСТРОЙКИ ПРОЕКТА:</b></LEGEND>
<form action="?p=settings&action=edit" method="post">
<table width="700" align="center" border="0" cellpadding="3" cellspacing="0" style="border: solid #cccccc 1px; border-radius: 4px;">
<tr bgcolor="#dddddd" height="35">
	<td><b>Работа сайта</b>:</td>
	<td align="right"><label><input type="radio" name="cfgOnOff" value="on" <?php if(cfgSET('cfgOnOff') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="cfgOnOff" value="off" <?php if(cfgSET('cfgOnOff') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Вы можете данной опцией включить, или выключить сайт для пользователей. В то же время, для администратора сайт будет полностью доступен." /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Название проекта</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="projectname" size="70" maxlength="250" value="<?php print cfgSET('projectname'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Введите название вашего проекта." /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>E-mail администратора</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="adminmail" size="70" maxlength="250" value="<?php print cfgSET('adminmail'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="E-mail на который будут отправляться письма с контактной формы, а так же от этого имени будут отправляться все письма пользователям." /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Минимальная сумма на вывод</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgMinOut" size="70" maxlength="250" value="<?php print cfgSET('cfgMinOut'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Укажите минимальную сумму на вывод средств пользователем. Не рекомендуем устанавливать цену ниже 0.1" /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Максимальная сумма на вывод</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgMaxOut" size="70" maxlength="250" value="<?php print cfgSET('cfgMaxOut'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Укажите МАКСИМАЛЬНУЮ сумму на вывод средств пользователем." /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Кол-во раз вывода средств в сутки</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgCountOut" size="70" maxlength="250" value="<?php print cfgSET('cfgCountOut'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Укажите кол-во заявок на вывод средств в сутки пользователем. Если указать 0, тогда кол-во заявок будет неограничено." /></td>
</tr>

<tr bgcolor="#dddddd" height="35">
	<td><b>Процент при выводе</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgPercentOut" size="70" maxlength="250" value="<?php print cfgSET('cfgPercentOut'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Если вы хотите высчитывать от суммы вывода свой процент, укажите этот процент в этом поле. Диапазон процента от 0.00 до 99" /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Дата старта проекта</b>:</td>
	<td align="right">
		<select class="input" name="h" title="Часы">
			<option value="">ЧЧ</option>
		<?php
		$datestart = cfgSET('datestart');
		for($i=0; $i<=24; $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("H", $datestart)) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select> :
		<select class="input" name="i" title="Минуты">
			<option value="">MM</option>
		<?php
		for($i=0; $i<=60; $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("i", $datestart)) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select> - 
		<select class="input" name="d" title="День">
			<option value="">ДД</option>
		<?php
		for($i=0; $i<=31; $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("d", $datestart)) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select>.<select class="input" name="m" title="Месяц">
		<?php
			print '<option value="1"';
			if(intval(date("m", $datestart)) == 1) { print ' selected'; }
			print '>Январь</option>';

			print '<option value="2"';
			if(intval(date("m", $datestart)) == 2) { print ' selected'; }
			print '>Февраль</option>';

			print '<option value="3"';
			if(intval(date("m", $datestart)) == 3) { print ' selected'; }
			print '>Март</option>';

			print '<option value="4"';
			if(intval(date("m", $datestart)) == 4) { print ' selected'; }
			print '>Апрель</option>';

			print '<option value="5"';
			if(intval(date("m", $datestart)) == 5) { print ' selected'; }
			print '>Май</option>';

			print '<option value="6"';
			if(intval(date("m", $datestart)) == 6) { print ' selected'; }
			print '>Июнь</option>';

			print '<option value="7"';
			if(intval(date("m", $datestart)) == 7) { print ' selected'; }
			print '>Июль</option>';

			print '<option value="8"';
			if(intval(date("m", $datestart)) == 8) { print ' selected'; }
			print '>Август</option>';

			print '<option value="9"';
			if(intval(date("m", $datestart)) == 9) { print ' selected'; }
			print '>Сентябрь</option>';

			print '<option value="10"';
			if(intval(date("m", $datestart)) == 10) { print ' selected'; }
			print '>Октябрь</option>';

			print '<option value="11"';
			if(intval(date("m", $datestart)) == 11) { print ' selected'; }
			print '>Ноябрь</option>';

			print '<option value="12"';
			if(intval(date("m", $datestart)) == 12) { print ' selected'; }
			print '>Декабрь</option>';
		?>	
		</select>.<select class="input" name="ye" title="Год">
			<option value="">ГГГГ</option>
		<?php
		for($i=2012; $i<=date(Y); $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("Y", $datestart)) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select>
	</td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Укажите дату старта вашего проекта. Данная дата будет выводиться в статистике, а так же не будут начисляться проценты до начала даты (предстарт)" /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Язык по умолчанию</b>:</td>
	<td align="right"><label><input type="radio" name="cfgLang" value="ru" <?php if(cfgSET('cfgLang') == "ru") { print ' checked="checked"'; } ?> /> Русский</label> / <label><input type="radio" name="cfgLang" value="en" <?php if(cfgSET('cfgLang') == "en") { print ' checked="checked"'; } ?> /> Английский</label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Укажите ваш основной язык. С данного языка будет открываться сайт, пока пользователь его не сменит самостоятельно." /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Начисление процентов</b>:</td>
	<td align="right"><label><input type="radio" name="autopercent" value="on" <?php if(cfgSET('autopercent') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="autopercent" value="off" <?php if(cfgSET('autopercent') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Если вы начисляете проценты вручную и в свободном графике, вам необходимо отключить автоматичское начисление процентов. Если ваш проект уже имеет инвесторов и данная опция была выключена, включать ее крайне не рекомендуется." /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Реинвестирование</b>:</td>
	<td align="right"><label><input type="radio" name="cfgReInv" value="on" <?php if(cfgSET('cfgReInv') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="cfgReInv" value="off" <?php if(cfgSET('cfgReInv') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данная опция включает возможность инвестору реинвестировать полученные проценты от вклада" /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>SSL</b>:</td>
	<td align="right"><label><input type="radio" name="cfgSSL" value="on" <?php if(cfgSET('cfgSSL') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Установлен</font></label> / <label><input type="radio" name="cfgSSL" value="off" <?php if(cfgSET('cfgSSL') == "off") { print ' checked="checked"'; } ?> /> <font color="red">Не установлен</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Если у вас установлен SSL, то включая данную опцию, сайт будет перенаправлять пользователя по протоколу https://, а так же все платежи будут проходить по защищенному протоколу." /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Подтверждение регистрации по e-mail</b>:</td>
	<td align="right"><label><input type="radio" name="cfgMailConf" value="on" <?php if(cfgSET('cfgMailConf') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="cfgMailConf" value="off" <?php if(cfgSET('cfgMailConf') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данная опция ограничивает пользователей в авторизации до тех пор, пока не будет подтверждена регистрация по ссылке отправленной на e-mail" /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Валюта проекта</b>:</td>
	<td align="right"><label><input type="radio" name="cfgMonCur" value="USD" <?php if(cfgSET('cfgMonCur') == "USD") { print ' checked="checked"'; } ?> /> <b>Доллары США</b></label> / <label><input type="radio" name="cfgMonCur" value="RUB" <?php if(cfgSET('cfgMonCur') == "RUB") { print ' checked="checked"'; } ?> /> <b>Рубли</b></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Валюта с которой будет работать проект" /></td>
</tr>

<tr bgcolor="#dddddd" height="35">
	<td><b>Captcha при регистрации</b>:</td>
	<td align="right"><label><input type="radio" name="regcaptcha" value="on" <?php if(cfgSET('regcaptcha') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="regcaptcha" value="off" <?php if(cfgSET('regcaptcha') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данная опция включает и отключает каптчу (ввод цифер с картинки) в регистрационной форме" /></td>
</tr>

<tr bgcolor="#cccccc">
	<td height="25" colspan="3" align="center"><b style="color: #666666;">ПЕРЕВОД СРЕДСТВ МЕЖДУ ПОЛЬЗОВАТЕЛЯМИ</b></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Перевод средств</b>:</td>
	<td align="right"><label><input type="radio" name="cfgTrans" value="on" <?php if(cfgSET('cfgTrans') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="cfgTrans" value="off" <?php if(cfgSET('cfgTrans') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данная опция позволяет пользователям переводить деньги друг другу внутри системы." /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Процент от перевода</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgTransPercent" size="70" maxlength="250" value="<?php print cfgSET('cfgTransPercent'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Процент администратору от суммы перевода денег между пользователями. Значение от 0 до 99. Данный процент будет суммирован с суммой перевода." /></td>
</tr>

<tr bgcolor="#cccccc">
	<td height="25" colspan="3" align="center"><b style="color: #666666;">НАСТРОЙКИ БОНУСНОГО БАЛАНСА</b></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Бонусный баланс</b>:</td>
	<td align="right"><label><input type="radio" name="cfgBonusBal" value="on" <?php if(cfgSET('cfgBonusBal') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="cfgBonusBal" value="off" <?php if(cfgSET('cfgBonusBal') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данная опция включает и отключает бонусный баланс на сайте. С данного баланса можно только открыть депозит, но невозможно вывести." /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Бонус при регистрации</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgBonusReg" size="70" maxlength="250" value="<?php print cfgSET('cfgBonusReg'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Сумма на бонусный баланс, которую получит пользователь сразу после регистрации" /></td>
</tr>

<tr bgcolor="#eeeeee" height="35">
	<td><b>Переодичный бонус</b>:</td>
	<td align="right"><label><input type="radio" name="cfgBonusOnOff" value="on" <?php if(cfgSET('cfgBonusOnOff') == "on") { print ' checked="checked"'; } ?> /> <font color="green">Включить</font></label> / <label><input type="radio" name="cfgBonusOnOff" value="off" <?php if(cfgSET('cfgBonusOnOff') == "off") { print ' checked="checked"'; } ?> /> <font color="red">ВЫключить</font></label></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Данная опция включает и отключает возможность получения бонуса на бонусный баланс один раз в определенный промежуток времени" /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Минимальная сумма бонуса</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgBonusMin" size="70" maxlength="250" value="<?php print cfgSET('cfgBonusMin'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Минимальная сумма для переодичного бонуса в центах/копейках. (Например, если вы хотите установить минимальную сумму 1 доллар или рубль, вам необходимо указать 100)" /></td>
</tr>
<tr bgcolor="#eeeeee" height="35">
	<td><b>Максимальная сумма бонуса</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgBonusMax" size="70" maxlength="250" value="<?php print cfgSET('cfgBonusMax'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Максимальная сумма для переодичного бонуса в центах/копейках. (Например, если вы хотите установить максимальную сумму бонуса 11 долларов или 11 рублей (в зависимости от валюты, которая используется в настройках), вам необходимо указать 1100)" /></td>
</tr>
<tr bgcolor="#dddddd" height="35">
	<td><b>Переодичность бонусов</b>:</td>
	<td align="right"><input style="width: 361px;" type="text" name="cfgBonusPeriod" size="70" maxlength="250" value="<?php print cfgSET('cfgBonusPeriod'); ?>" /></td>
	<td width="18"><img style="cursor: help;" src="images/help_ico.png" width="16" height="16" border="0" alt="Переодичность выдачи бонусов в минутах. (Например: при выдаче бонуса 1 раз в сутки, необходимо указать 1440 минут)" /></td>
</tr>

</table>
<table align="center" width="708" border="0">
	<tr>
		<td align="right"><input type="submit" value="Сохранить!" /></td>
	</tr>
</table>
</form>
</FIELDSET>