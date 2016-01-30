<script type="text/javascript" src="files/jquery.js"></script>
<script type="text/javascript">
    $(document).ready( function() {
       $("#example_maincb").click( function() { // при клике по главному чекбоксу
            if($('#example_maincb').attr('checked')){ // проверяем его значение
                $('.example_check:enabled').attr('checked', true); // если чекбокс отмечен, отмечаем все чекбоксы
            } else {
                $('.example_check:enabled').attr('checked', false); // если чекбокс не отмечен, снимаем отметку со всех чекбоксов
            }
       });
    });
 
</script>
<?php
defined('ACCESS') or die();
$s = intval($_GET['s']);

if($_GET['conf']) {

	$query	= "SELECT * FROM enter WHERE id = ".intval($_GET['conf'])." AND status = 1 LIMIT 1";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);

	if($row['sum']) {

		$get_ps	= mysql_query("SELECT * FROM `paysystems` WHERE name = '".$row['paysys']."' LIMIT 1");
		$rowps	= mysql_fetch_array($get_ps);

		$sum	= sprintf("%01.2f", $row['sum'] * $rowps['percent']);

		mysql_query('UPDATE users SET balance = balance + '.$sum.' WHERE login = "'.$row['login'].'" LIMIT 1');
		mysql_query("UPDATE enter SET status = 2 WHERE id = ".intval($_GET['conf'])." LIMIT 1");

		print '<p class="erok">Деньги зачислены на баланс пользователю.</p>';

	} else {
		print '<p class="er">Данная заявка уже выполнена!</p>';
	}
}
?>
<script language="javascript" type="text/javascript" src="files/alt.js"></script>
<form action="?p=enter&s=<?php print $s; ?>&action=search" method="post">
<FIELDSET>
<LEGEND>Поиск платежа:</LEGEND>
<table width="100%" border="0">
	<tr>
		<td width="150"><strong>Login или номер счета:</strong></td>
		<td><input style="width: 99%;" type="text" name="name" size="93" /></td>
		<td width="30" align="center"><input type="image" src="images/search.png" width="24" height="24" border="0" title="Поиск!" /></td>
	</tr>
		<tr>
		<td align="right"><b>Сортировка:</b></td>
		<td colspan="2">
		<select class="input" name="sh" title="Часы">
		<?php
		$datestart = time();
		for($i=0; $i<=24; $i++) {
			print '<option value="'.$i.'">'.$i.'</option>';
		}
		?>
		</select> :
		<select class="input" name="si" title="Минуты">
		<?php
		for($i=0; $i<=60; $i++) {
			print '<option value="'.$i.'">'.$i.'</option>';
		}
		?>
		</select> - 
		<select class="input" name="sd" title="День">
		<?php
		for($i=1; $i<=31; $i++) {
			print '<option value="'.$i.'">'.$i.'</option>';
		}
		?>
		</select>.<select class="input" name="sm" title="Месяц">
		<?php
		for($i=1; $i<=12; $i++) {
			print '<option value="'.$i.'">'.$i.'</option>';
		}
		?>	
		</select>.<select class="input" name="sy" title="Год">
		<?php
		for($i=2012; $i<=date(Y); $i++) {
			$datestart = cfgSET('datestart');
			print '<option value="'.$i.'"';
			if(intval(date("Y", $datestart)) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select> &macr; 		<select class="input" name="fh" title="Часы">
		<?php
		for($i=0; $i<=24; $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("H")) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select> :
		<select class="input" name="fi" title="Минуты">
		<?php
		for($i=0; $i<=60; $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("i")) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select> - 
		<select class="input" name="fd" title="День">
		<?php
		for($i=0; $i<=31; $i++) {
			print '<option value="'.$i.'"';
			if(date("d") == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select>.<select class="input" name="fm" title="Месяц">
		<?php
		for($i=0; $i<=12; $i++) {
			print '<option value="'.$i.'"';
			if(intval(date("m")) == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>	
		</select>.<select class="input" name="fy" title="Год">
		<?php
		for($i=2012; $i<=date(Y); $i++) {
			print '<option value="'.$i.'"';
			if(date("Y") == $i) { print ' selected'; }
			print '>'.$i.'</option>';
		}
		?>
		</select> <b>ПС:</b>
		<select class="input" name="ps" title="Платежная система">
		<?php
		print '<option value="0">Все</option>';
		$result	= mysql_query("SELECT * FROM `paysystems` ORDER BY id ASC");
		while($row = mysql_fetch_array($result)) {
			print '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}
		?>
		</select></td>
	</tr>
</table>
</FIELDSET>
</form>
<hr />

<div align="right">
	<a href="?p=enter&s=2&sort=1">В процессе</a> | <a href="?p=enter&s=2">Выполненые</a> | <a href="?p=enter&s=2&sort=3">В ожидании [ <?php print mysql_num_rows(mysql_query("SELECT `id` FROM `enter` WHERE `status` = 1")); ?> ]</a>
</div>
<?php
if ($_GET['action'] == 'clean') {
	$postvar = array_keys($_POST);
	$count	 = 0;
	while($count < count($postvar)) {
		$sid = $_POST[$postvar[$count]];

		$query		= 'UPDATE `enter` SET `status` = 5 WHERE `id` = '.$sid.' LIMIT 1';
		$query_res	= mysql_query($query);

	$count++;
	}
}

if (isset($_GET['sort'])) {
	$sort = intval($_GET['sort']);
} else {
	$sort = 0;
}

function topics_list($page, $num, $status, $query)
{
?>
<table class="tbl">
<tr>
	<th width="24"><input type="checkbox" name="box[]" value="0" id="example_maincb" /></th>
	<th width="150">Дата</th>
	<th><b>Логин</b></th>
	<th width="100"><b>Сумма</b></th>
	<th width="100"><b>К начислению</b></th>
	<th width="120"><b>Счет</b></th>
	<th width="70"><b>Система</b></th>
	<th width="70"><b>Статус</b></th>
</tr>
<form action='/admin/?p=enter&s=2&sort=<?php print $_GET[sort]; ?>&action=clean' method='post' id="example_group2">
<?php
	$result = mysql_query($query);
	$themes = mysql_num_rows($result);
	$total = intval(($themes - 1) / $num) + 1;
	if (empty($page) or $page < 0) $page = 1;
	if ($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query.' LIMIT '.$start.', '.$num);
	$esum	= 0.00;
	$osum	= 0.00;
	while ($topics = mysql_fetch_array($result))
	{
		$esum	= sprintf ("%01.2f", $esum + $topics['sum']);

			$get_ps	= mysql_query("SELECT * FROM `paysystems` WHERE name = '".$topics['paysys']."' LIMIT 1");
			$rowps	= mysql_fetch_array($get_ps);

		print '<tr>
		<td><input class="example_check" type="checkbox" name="box'.$topics['id'].'" value="'.$topics['id'].'" /></td>
		<td> '.date("d.m.Y H:i:s", $topics['date']).'</td>
		<td class="tdleft"><a href="?p=edit_user&l='.$topics['login'].'" target="_blank"><b>'.$topics['login'].'</b></a></td>
		<td>'.$topics['sum'].'</td>
		<td><strong>'.sprintf("%01.2f", $topics['sum'] * $rowps['percent']).'</strong></td>
		<td>'.$topics['purse'].'</td>
		<td>'.$topics['paysys'].'</td>
		<td>'.$topics['status'].' ';
		
		if ($topics['status'] == 1) {
			print '<a href="javascript: if(confirm(\'Вы уверены?\')) top.location.href=\'/admin/?p=enter&s=2&sort=3&conf='.$topics['id'].'&l='.$topics['login'].'\';"><img border="0" src="images/status.png" width="16" height="16" alt="Подтвердить платеж и зачислить деньги на баланс" /></a> ';
		}

		print '</td></tr>';
	}
?>
<tr>
	<td class="ftr" colspan="2"><input class="sbm" type="submit" name="submit" value="Очистить" /></td>
	<td class="ftr"></td>
	<td class="ftr" style="color: #ffffff; text-align: center;"><b><?php print $esum; ?></b></td>
	<td class="ftr"></td>
	<td class="ftr"></td>
	<td class="ftr"></td>
	<td class="ftr"></td>
</tr>
</form>
</table>
<?php
	if ($page != 1) $pervpage = "<a href=?p=edit&s=2&sort=".$_GET['sort']."&page=1>«</a>";
	if ($page != $total) $nextpage = " <a href=?p=edit&sort=".$_GET['sort']."&s=2&page=".$total.">»</a>";
	if ($page - 2 > 0) $page2left = " <a href=?p=edit&s=2&sort=".$_GET['sort']."&page=". ($page - 2) .">". ($page - 2) ."</a> ";
	if ($page - 1 > 0) $page1left = " <a href=?p=edit&s=2&sort=".$_GET['sort']."&page=". ($page - 1) .">". ($page - 1) ."</a> ";
	if ($page + 2 <= $total) $page2right = " <a href=?p=edit&s=2&sort=".$_GET['sort']."&page=". ($page + 2) .">". ($page + 2) ."</a>";
	if ($page + 1 <= $total) $page1right = " <a href=?p=edit&s=2&sort=".$_GET['sort']."&page=". ($page + 1) .">". ($page + 1) ."</a>";
	print "<div class=\"pages\"><b>Страницы: </b>".$pervpage.$page2left.$page1left." [".$page."] ".$page1right.$page2right.$nextpage."</div>";
}

$sql  = 'SELECT * FROM enter';
$sql .= ' WHERE purse != "X000000" AND status != 5';

if($_GET['action'] == "search") {
	$name = htmlspecialchars($_POST['name'], ENT_QUOTES, '');
	
	if($name) {
		$sql .= ' AND (login = "'.$name.'" OR purse = "'.$name.'")';
	}

	$sh = intval($_POST['sh']);
	$si = intval($_POST['si']);
	$sd = intval($_POST['sd']);
	$sm = intval($_POST['sm']);
	$sy = intval($_POST['sy']);

	$fh = intval($_POST['fh']);
	$fi = intval($_POST['fi']);
	$fd = intval($_POST['fd']);
	$fm = intval($_POST['fm']);
	$fy = intval($_POST['fy']);

	$datestart				= mktime($sh,$si,0,$sm,$sd,$sy);
	$datefinish				= mktime($fh,$fi,0,$fm,$fd,$fy);

	$sql .= " AND (`date` >= ".$datestart." AND `date` <= ".$datefinish.")";

	if(intval($_POST['ps'])) {
		$sql .= " AND `paysys` = ".intval($_POST['ps']);
	}

}

switch ($sort)
{
case 0:
	$sql .= ' AND status = 2';
	break;
case 1:
	$sql .= ' AND status = 0';
	break;
case 3:
	$sql .= ' AND status = 1';
	break;
}

$sql .= " ORDER BY id DESC";

$page = intval($_GET['page']);
topics_list($page, 50, $status, $sql);
?>