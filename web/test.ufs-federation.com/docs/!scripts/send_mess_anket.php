<?
$submit = (isset($_POST['subscribe'])) ? true : false;
 if ($submit)
 {
  $LNAME = $_POST['LNAME'];
  $FNAME = $_POST['FNAME'];
  $SNAME = $_POST['SNAME'];
  $day = $_POST['day'];
  $month = $_POST['month'];
  $year = $_POST['year'];
  $nation = $_POST['nation'];
  $yes = $_POST['yes'];
  $specialty = $_POST['specialty'];
  $vacancy = $_POST['vacancy'];
  $n1 = $_POST['n1'];
  $n2 = $_POST['n2'];
  $n3 = $_POST['n3'];
  $EMAIL = $_POST['EMAIL'];
  $education = $_POST['education'];
  $hschool = $_POST['hschool'];
  $kurs = $_POST['kurs'];
  $period1 = $_POST['period1'];
  $org1 = $_POST['org1'];
  $position1 = $_POST['position1'];
  $responce1 = $_POST['responce1'];
  $period2 = $_POST['period2'];
  $org2 = $_POST['org2'];
  $posotion2 = $_POST['posotion2'];
  $responce2 = $_POST['responce2'];
  $period3 = $_POST['period3'];
  $org3 = $_POST['org3'];
  $posotion3 = $_POST['posotion3'];
  $responce3 = $_POST['responce3'];
  $language = $_POST['language'];
  $level = $_POST['level'];
  $recommend = $_POST['recommend'];
  $skills = $_POST['skills'];
  $achieve = $_POST['achieve'];

$to = "hr@ufs-federation.com"; /*УКАЗАТЬ СВОЙ АДРЕС!*/
$headers = "Content-type: text/html; charset = utf-8\r\n";
$subject = "Сообщение с ufs-federation.com. Анкета на вакансию.";
$message = "
<html>
     <head>  
        <title>Анкета на вакансию</title>	 
	</head>
    <body>
         <table>
<tr><td>		 
<b>Анкета на вакансию</b>: $vacancy
</td></tr>
<tr><td><b>Ф.И.О.:</b> $LNAME $FNAME $SNAME</td></tr>
<tr><td><b>Дата рождения</b>: $day $month $year</td></tr>
<tr><td><b>Гражданство</b>: $nation</td></tr>
<tr><td><b>Готовность к перезду:</b> $yes</td></tr>
<tr><td><b>Специализация:</b> $specialty</td></tr>
<tr><td><b>Телефон:</b> $n1 $n2 $n3</td></tr>
<tr><td><b>Email:</b> $EMAIL</td></tr>
<tr><td></td></tr>
<tr><td><b>Образование:</b> $education</td></tr>
<tr><td><b>Информация об учебном заведении:</b> $hschool</td></tr>
<tr><td><b>Курсы повышения квалификации:</b> $kurs</td></tr>
<tr><td></td></tr>
<tr><td><b>Профессиональный опыт</b></td></tr>
<tr><td><b>Период:</b> $period1</td></tr>
<tr><td><b>Организация:</b> $org1</td></tr>
<tr><td><b>Должность:</b> $position1</td></tr>
<tr><td><b>Основные обязанности:</b> $responce1</td></tr>
<tr><td></td></tr>
<tr><td><b>Период:</b> $period2</td></tr>
<tr><td><b>Организация:</b> $org2</td></tr>
<tr><td><b>Должность:</b> $position2</td></tr>
<tr><td><b>Основные обязанности:</b> $responce2</td></tr>
<tr><td></td></tr>
<tr><td><b>Период:</b> $period3</td></tr>
<tr><td><b>Организация:</b> $org3</td></tr>
<tr><td><b>Должность:</b> $position3</td></tr>
<tr><td><b>Основные обязанности:</b> $responce3</td></tr>
<tr><td><b>Язык:</b> $language</td></tr>
<tr><td><b>Уровень владения:</b> $level</td></tr>
<tr><td></td></tr>
<tr><td><b>Рекомендации:</b> $recommend</td></tr> 
<tr><td></td></tr>
<tr><td><b>Ключевые навыки:</b> $skills</td></tr>
<tr><td></td></tr>
<tr><td><b>Достижения:</b> $achieve</td></tr></table></body></html>";
$send = mail ($to, $subject, $message, $headers);
}
if ($send == 'true')
{
echo "<b>Спасибо! Ваш вопрос отправлен!<p>";
}
else 
{
echo "<p><b>Ошибка. Сообщение не отправлено!";
}

?>