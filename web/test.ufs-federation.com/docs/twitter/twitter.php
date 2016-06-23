<?php
$title = "UFS Finance Twitter WEB Gate";
$user = "ufs_ic";
$pass = "ufsfederation060810";
 
// если имя с паролем заданы неправильно или не заданы вообще — запрашиваем их
if(@$_SERVER['PHP_AUTH_USER'] != $user || @$_SERVER['PHP_AUTH_PW'] != $pass)
{
  header('WWW-Authenticate: Basic realm="'.$title.'"');
  header('HTTP/1.0 401 Unauthorized');
  die("В доступе отказано.");
}
?>

<?php
//Укорачиваем линк
$link=$_POST['lnk'];
$surl = json_decode(file_get_contents("http://api.bit.ly/v3/shorten?login=ufsic&apiKey=R_633ce6ab637030878e5cc4c2813ee998&longUrl=".urlencode($link)."&format=json"))->data->url;

//Укорачиваем анонс
$anounce=$_POST['message'];
$anounce=substr($anounce,0,300);

//Создаем многоточие
$dotsL=str_replace("http","...",$surl);
$dotsS=substr($dotsL,0,3);

//Объединяем анонс и линк
$twit="".$anounce."".$dotsS." ".$surl."";

require_once "twitteroauth/twitteroauth.php";

define("CONSUMER_KEY", "AhZ7LyyMj9bPbCW6a2WLQ");
define("CONSUMER_SECRET", "svoCwMQBamKqS1Cnc6HvWB7ZpmfB6EQokURgrUcuwQ");
define("OAUTH_TOKEN", "240162732-oYP0KQPFf2MrhpFqaEDy2XodezKo4LmPaiPA6lDx");
define("OAUTH_SECRET", "Z25DXi2452taVAD61MxB5iBVGT5zFxE1FKFXaVG0s");

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
$content = $connection->get('account/verify_credentials');

$connection->post('statuses/update', array('status' => $twit));

?>

<html>

<SCRIPT LANGUAGE="JavaScript">
function textCounter( field, countfield, maxlimit ) {
  if ( field.value.length > maxlimit )
  {
    field.value = field.value.substring( 0, maxlimit );
    alert( 'Максимальный размер текста - 116 символов!' );
    return false;
  }
  else
  {
    $(countfield).update(maxlimit - field.value.length);
  }
}
</script>

  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1251">
  <title>Отправить в twitter</title>
  </head>
  <body><center>
    <img src=Twitter-Logo.jpg>
    <hr>
    <form action="twitter.php" method="post">
      <p><input name="lnk" type="text" size="30" value="Ссылка на страницу">		
      <p><textarea name="message" cols="30" rows="10" onkeyup="textCounter(this,'text-counter',116)" onpaste="textCounter(this,'text-counter',116)">Введите краткое описание, не более 116 символов</textarea><br>
      <p><input name="send" type="submit" value="Отправить в twitter">
    </form>
    <hr>
  </body>
</html>