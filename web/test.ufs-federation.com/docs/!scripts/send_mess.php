<?
$submit = (isset($_POST['submit'])) ? true : false;
 if ($submit)
 {
 if ($_POST['name'] != "" and $_POST['email'] != "")
 {$FIO = $_POST['name'];
  $Email = $_POST['email'];
  $Mess = $_POST['mess'];
  

$to = "paa@ufs-federation.com"; /*УКАЗАТЬ СВОЙ АДРЕС!*/
$headers = "Content-type: text/plain; charset = utf-8";
$subject = "Сообщение с ufs-federation.com. Раздел Услуги на фондовом рынке";
$message = "Имя: $FIO\nEmail: $Email\nСообщение: $Mess";
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
}
?>