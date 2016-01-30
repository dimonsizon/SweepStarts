<?php
if($er == 1) {
	print '<div class="er">'.$lang['er_01'].'</div>';
} elseif($er == 2) {
	print '<div class="er">'.$lang['er_02'].'</div>';
}  elseif($er == 10) {
	print '<div class="er">Запрещена повторная авторизация</div>';
} elseif($_GET['activate'] == "yes") {
	print '<div class="warn">'.$lang['er_03'].'</div>';
} elseif($_GET['activate'] == "yes") {
	print '<div class="warn">'.$lang['er_03'].'</div>';
}
?>
<div align="center">
<form method="post" action="/login/">
	<p><input type="text" name="user" style="width: 160px;" onblur="if (value == '') {value='Login'}" onfocus="if (value == 'Login') {value =''}" value="Login" /></p>
	<p><input type="password" name="pass" style="width: 160px;" onblur="if (value == '') {value='password'}" onfocus="if (value == 'password') {value =''}" value="password" /></p>
	<p><input style="width: 160px;" class="subm" type="submit" value=" <?php print $lang['enter']; ?>! " /></p>
	<p><a href="/registration/"><?php print $lang['registration']; ?></a> | <a href="/reminder/"><?php print $lang['reminder']; ?>?</a></p>
</form>
</div>