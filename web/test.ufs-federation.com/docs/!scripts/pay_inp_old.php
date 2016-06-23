<style type="text/css">
input{
      padding:3px;
      color:#333333;
 border:1px solid #96A6C5;
      height:25px;
      width:230px;
      font-size:12px;
	  -webkit-border-radius: 10px;
	  -moz-border-radius: 10px;
	  border-radius: 10px;
	  -webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
      }
#div-regForm,.registered{
      border:3px solid #eeeeee;
      padding:15px;
      background: #002644 url(logo1.png) no-repeat top;
	  
      color:#F3D44D;
 margin:50px auto 40px auto;
      width:300px;
	  height:270px;
	  	  -webkit-border-radius: 10px;
	  -moz-border-radius: 10px;
	  border-radius: 10px;
	  }
	  .form-title{
	  margin-top:80px;
      font-size:14px;
 font-family:"Lucida Grande",Tahoma,Verdana,Arial,sans-serif;
      font-weight:bold;
	  color:#FCFCFC;
      }
	  .addButton{
      width:150px;
	  height:30px;
      padding:3px 4px 3px 4px;
      color:#FFFFFF;
      background-color:#0088C8;
      outline:none;
      font-weight:bold;
      }
.addButton:active{
      background-color:#006600;
      padding:4px 3px 2px 5px;
	  -webkit-border-radius: 10px;
	  -moz-border-radius: 10px;
	  border-radius: 10px;
      }
</style>
<center>
<div id="div-regForm">
<div class="form-title">Пожалуйста, укажите ФИО лица,<br>за которого производится оплата</div><br><br>

<form action="pay_acc.php" method=POST>
<div class="input-container"><input type="text" name="uname" value="<?print "Укажите фамилию, имя, отчество"?>"></div><br><br>
<input type="submit" value="Перейти к оплате" class="addbutton">
</form></div><br></center>

