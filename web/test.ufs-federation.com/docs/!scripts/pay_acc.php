<?
   $uname = ($_POST['uname']);
   $uname = iconv('utf-8', 'windows-1251', $uname);
   $amount = "3000";
   //$amount = "1";
   $amountcurr = "RUR";
   $currency = "MBC";
   //$currency = "WMR";
   $number = "1337";
   $description = urlencode("$uname");
   $account = "ebd906766de13bce813468fc99a93b4b";
   $shoptype = "m";
   $signature = "$amount:$amountcurr:$number:$description";
   $signature = $signature.":$account:555:$shoptype";
   $signature = strtoupper(md5($signature));
   ?>

   <style type="text/css">
   input{
      margin-top:300px
      padding:3px;
      color:#FFFFFF;
 border:1px solid #96A6C5;
      height:100px;
      width:230px;
      font-size:30px;
	  background:#0088C8;
	  -webkit-border-radius: 10px;
	  -moz-border-radius: 10px;
	  border-radius: 10px;
	  -webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
      }
   </style>
<form action="http://www.e-pos.ru/ext/dopay.php" method=POST>
<input type="hidden" name="amount" value="<?print $amount?>">
<input type="hidden" name="amountcurr" value="<?print $amountcurr?>">
<input type="hidden" name="currency" value="<?print $currency?>">
<input type="hidden" name="number" value="<?print $number?>">
<input type="hidden" name="description" value="<?print $description?>"><br>
<input type="hidden" name="account" value="<?print $account?>">
<input type="hidden" name="shoptype" value="<?print $shoptype?>">
<input type="hidden" name="signature" value="<?print $signature?>">
<center>
<input type="submit" value="Оплатить">
</center>
</form><br>