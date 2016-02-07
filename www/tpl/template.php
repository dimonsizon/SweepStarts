<?php
defined('ACCESS') or die();
if(cfgSET('cfgOnOff') == "off" && $status != 1) {
	include "includes/errors/tehwork.php";
	exit();
} elseif(cfgSET('cfgOnOff') == "off" && $status == 1) {
	print '<p class="warn">Сайт отключен и недоступен для остальных пользователей!</p>';
}

$cusers		= mysql_num_rows(mysql_query("SELECT `id` FROM `users` WHERE `balance` != 0")) + cfgSET('fakeactiveusers');

$money	= cfgSET('fakewithdraws');
$query	= "SELECT `sum` FROM `output` WHERE `status` = 2";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$money = $money + $row['sum'];
}

$depmoney	= cfgSET('fakedeposits');
$query	= "SELECT `sum` FROM `deposits` WHERE `status` = 0";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$depmoney = $depmoney + $row['sum'];
}

$ref = gs_html($_GET[cfgSET('refname')]);
if($ref) {
	mysql_query("UPDATE users SET clx = clx + 1 WHERE login = '".$ref."' LIMIT 1");
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<link href="/files/favicon.ico" type="image/x-icon" rel="shortcut icon" />
	<title><?php print $title; ?></title>
	<meta name="keywords" content="<?php print $keywords; ?>" />
	<meta name="description" content="<?php print $description; ?>" />
	<link href="/files/css/styles.css" type="text/css" rel="stylesheet" />
	<link href="/files/css/menu.css" type="text/css" rel="stylesheet" />
	<link href="/files/css/contributions.css" type="text/css" rel="stylesheet" />
	<link href="/files/css/general.css" type="text/css" rel="stylesheet" />
	<link href="/files/css/left-panel.css" type="text/css" rel="stylesheet" />
	<link href="/fonts/font-awesome-4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<script language="javascript" src="/files/scripts.js"></script>
</head>
<body>
<header id="header">
	<div class="container">		
		<div class="logo">
			<a href="/"><img src="/img/tpl/logo.png" border="0" alt="SWEEPSTARTS"></a>
		</div>
	
		<div class="pull-right">
			<div class="user-block text-right">
			<?php if($login) { ?>
				<span class=""><i class="fa fa-user"></i> <span><?php print $login;?></span></span>
				<span class="balans">
					<i class="fa fa-usd"></i> 
					<?php
						if(cfgSET('cfgBonusBal') == "on") {
							print "<span> ".$balance." ".$mycur."</span>
							<span class=\"bonus-bal\">BONUS: ".$bonusbalance." ".$mycur."</span>";
						} else {
							print "<span>".$balance."</span> ".$mycur."</p>";
						}				
					?>
				</span>
			<?php }?>
			</div>
			
			<nav id="menu" class="nav-bar">
				<ul>
					<li><a href="/"><?php print $lang['homepage']; ?></a></li>					
					<li><a href="/news"><?php print $lang['news']; ?></a></li>
					<li><a href="/reviews"><?php print $lang['reviews']; ?></a></li>
					<li><a href="/support"><?php print $lang['contacts']; ?></a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<div class="clear"></div>
<div class="main-container">
	<div class="clear"></div>
	<div class="container">
		<div class="left-panel pull-left">
			<div class="">
				<!--<h3><?php print $lang['auth']; ?></h3>-->
				<?php include "tpl/loginform.php"; ?>
			</div>
			<!--<div class="br"></div>
			<div class="card">
				
				
			</div>-->
		</div>
		<div class="center-panel">
			<h3 class="center-title"><?php print $title; ?></h3>
			<?php
				if(!$page) {
					include "includes/index.php";
				} elseif(file_exists("modules/".$page.".php")) {
					include "modules/".$page.".php";
				} else {
					include "modules/page.php";
				}
			?>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div id="footer">
	<div class="container">
		<div class="pull-left">
		&copy; <?php print date("Y"); ?> <a href="#" target="_blank">SWEEPSTARTS</a> Краудфандинговая платформа 
		<nav class="nav-footer">
			<ul>
				<li><a href="/rules"><?php print $lang['rules']; ?></a></li>					
				<li><a href="/news"><?php print $lang['news']; ?></a></li>
				<li><a href="/reviews"><?php print $lang['reviews']; ?></a></li>
				<li><a href="/support"><?php print $lang['contacts']; ?></a></li>
			</ul>
		</nav>
		</div>	
		<div class="pull-right">		
			<div class="languages-block">
				<a href="<?php if($page) { print "/".$page; } ?>/?lang=en"><img src="/img/us_ico.gif" width="18" height="12" border="0" alt="English" title="English"></a>
				<a href="<?php if($page) { print "/".$page; } ?>/?lang=ru"><img src="/img/ru_ico.gif" width="18" height="12" border="0" alt="Русский" title="Русский"></a>
			</div>
		</div>
	</div>	
</div>
<div class="br"></div>
</body>
</html>