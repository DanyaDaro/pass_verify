<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="ru">
<title>Система анализа надёжности многоразового пароля (ОТЗИ-42, В.Рудных)</title>
<head>
	<link href="bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script>
		$(function() {
			$( document ).tooltip();
		});
	</script>
	<style>
		label {
			display: inline-block;
			width: 5em;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
			
<b>Рекомендуемые требования</b><br>
Длина минимум 8 символов.<br> Содержит:<ul>
<li>Заглавные буквы
<li>Строчные буквы
<li>Числа
<li>Символы
</ul>

<form method="POST">
	<input type="text" name="password" maxlength="10">
	<input type="submit" value="Проверить">
</form>

<?php
$password = iconv("utf-8","cp1251",$_POST['password']);

function count_char($str) { // Количество знаков
	$len = strlen($str)*4;
	return $len;
}

function capital_letter($str) { // Заглавные буквы
	$cl = strlen(preg_replace('![^\A-Z]+!u', '', $str));
		if($cl<>0){$cl = (strlen($str)-$cl)*2;}
	return $cl;
}

function lowercase_letter($str) { // Строчные буквы
	$ll = strlen(preg_replace('![^a-z]+!u', '', $str));
		if($ll<>0){$ll = (strlen($str)-$ll)*2;}
	return $ll;
}

function number_count($str) { // Цифры
	$nc = (strlen(preg_replace('![^0-9]+!u','',$str)))*4;
	return $nc;
}

function symbol_count($str) { // Символы
	$sc = (strlen($str)-strlen(preg_replace('![^\w\d\s]*!','',$str)))*6;
	return $sc;
}

$count_char = count_char($password);
$capital_letter = capital_letter($password);
$lowercase_letter = lowercase_letter($password);
$number_count = number_count($password);
$symbol_count = symbol_count($password);
?>


<table border=1 width="600" cellspacing="0" cellpadding="2">
<tr>
<td><b>Повышение сложности</b></td>
<td width="80"><b>Расчет</b></td>
<td><b>Баллы</b></td>
<td><b>Функция в коде</b></td>
</tr>

<tr>
<td>Количество знаков</td>
<td>+(n*4)</td>
<td><?=$count_char?></td>
<td><a href="#" title="$len = strlen($str)*4; return $len;">function count_char()</a></td>
</tr>

<tr>
<td>Заглавные буквы</td>
<td>+((len-n)*2)</td>
<td><?=$capital_letter?></td>
<td><a href="#" title="$cl = strlen(preg_replace('![A-Z]+!u', '', $str)); if($cl<>0){$cl = (strlen($str)-$cl)*2;} return $cl;">function capital_letter()</a></td>
</tr>

<tr>
<td>Строчные буквы</td>
<td>+((len-n)*2)</td>
<td><?=$lowercase_letter?></td>
<td><a href="#" title="$ll = strlen(preg_replace('![a-z]+!u', '', $str)); if($ll<>0){$ll = (strlen($str)-$ll)*2;} return $ll;">function lowercase_letter()</a></td>
</tr>

<tr>
<td>Цифры</td>
<td>+(n*4)</td>
<td><?=$number_count?></td>
<td><a href="#" title="$nc = (strlen(preg_replace('![^0-9]+!u','',$str)))*4; return $nc;">function number_count()</a></td>
</tr>

<tr>
<td>Символы</td>
<td>+(n*6)</td>
<td><?=$symbol_count?></td>
<td><a href="#" title="$sc = (strlen($str)-strlen(preg_replace('![^\w\d\s]*!','',$str)))*6;	return $sc;">function symbol_count()</a></td>
</tr>
</table>
<br>
<?php
$score = $count_char+$capital_letter+$lowercase_letter+$number_count+$symbol_count;
if($score<>0){
if($score<=30){$result="<font color=\"red\">Очень плохо</font>";}
if($score>30 and $score<=60){$result="<font color=\"orange\">Плохо</font>";}
if($score>60 and $score<=80){$result="<font color=\"blue\">Нормально</font>";}
if($score>80 and $score<=100){$result="<font color=\"green\">Хорошо</font>";}
if($score>100){$result="<font color=\"lime\">Отлично</font>";}
}else{$result="---";}
?>
Итого баллов: <b><a href="#" title="$score = $count_char + $capital_letter + $lowercase_letter + $number_count + $symbol_count;"><?=$score?></a></b>
<br>
Результат: <b><?=$result?></b>

</div>
</body>
</html>