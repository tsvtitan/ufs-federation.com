<!-- <a class="poplight" rel="popup_application_form" href="#?w=581" ><? echo(dictionary('Подать заявку (тест)')) ?></a> -->
<div id="popup_application_form" class="popup_block">
	<div id="note">
		<h3><? echo(dictionary('Уважаемый инвестор! Предлагаем Вам отправить онлайн заявку на открытие счета у российского брокера ООО «ИК «Ю Эф Эс Финанс». После заполнения персональных данных с Вами свяжется наш менеджер и пояснит процедуру открытия счета.')) ?>.</h3>
								
	</div>
<form id="form2" action="" method="post" OnSubmit="return checkform2(this)"><input type="hidden" name="theme" value="Non-resident"/><input type="hidden" name="time" value="<?=date('Y-m-d H:i:s')?>"/><input type="hidden" name="application_form" value="1"/>
	<table cellpadding="5" cellspacing="5">
		<tr>
			<td><label for="surname"><? echo(dictionary('Фамилия')) ?> *</label><input tabindex="1" id="surname" name="surname" size="30" type="text" placeholder="<? echo(dictionary('Фамилия')) ?>" required/></td>
			<td><label for="birthday"><? echo(dictionary('Дата рождения')) ?></label><input tabindex="5" id="birthday" name="birthday" size="30" type="text" placeholder="<? echo(dictionary('Дата рождения')) ?>" required/></td>
		</tr>
		<tr>
			<td><label for="name"><? echo(dictionary('Имя')) ?> *</label><input tabindex="2" id="name" name="name" size="30" type="text" placeholder="<? echo(dictionary('Имя')) ?>" required/></td>
			<td rowspan="4"><label for="location"><? echo(dictionary('Место проживания')) ?> *</label><textarea tabindex="6" id="location" cols="25" rows="15" name="location"></textarea></td>
		</tr>
		<tr>
			<td><label for="email"><? echo(dictionary('Email')) ?> *</label><input tabindex="3" id="email" name="email" size="30" type="text" placeholder="<? echo(dictionary('Email')) ?>" required/></td>
		</tr>
		<tr>
			<td><label for="tel"><? echo(dictionary('Телефон')) ?> *</label><input tabindex="4" id="tel" name="tel" size="30" type="text" placeholder="<? echo(dictionary('Контактный телефон')) ?>" required/></td>
		</tr>
		<tr>
			<td colspan="2"><p class="submit"><input type="submit" value="<? echo(dictionary('Отправить')) ?>" name="submit" style="width:150px; height:37px; line-height:37px;	border:none; background:url(/img/form_button.gif) no-repeat 0 0 #004C81; color:#fff; cursor:pointer; text-align:center;	margin-top:10px;" ></p></td>
		</tr>
	</table>
	
<script type="text/javascript">
$(document).ready(function(){
if (!$.browser.webkit) {
$('INPUT[placeholder], TEXTAREA[placeholder]').blur(function(){
if ($(this).val()=='') {
$(this).val($(this).attr('placeholder'));
$(this).addClass('m-placeholder');
}
}).focus(function(){
$(this).removeClass('m-placeholder');
if ($(this).val()==$(this).attr('placeholder'))
$(this).val('');
}).each(function(){
if ( ($(this).val()=='') || ($(this).val()==$(this).attr('placeholder')) ) {
$(this).val( $(this).attr('placeholder') );
$(this).addClass('m-placeholder');
}
var form = $('#form2');
//alert(form);
if (form.length)
form.submit(function(){
if ($(this).val()==$(this).attr('placeholder'))
$(this).val('');
});
});
}
});
</script>
</form>
<div id="error">&nbsp;</div>
</div>



<?
 $submit = (isset($_POST['submit']) && isset($_POST['application_form'])) ? true : false;
 $send = false;
 if ($submit) {
  
   if (isset($_POST['name']) && $_POST['name']!= "" and $_POST['surname'] != "" and $_POST['email'] != "" and $_POST['birthday'] != "" and $_POST['location'] != "" and $_POST['tel'] != "" and $_POST['time'] != "") {
     
     $theme = $_POST['theme'];
     $FIO = $_POST['name'] . ' ' . $_POST['surname'];
     $Tel = $_POST['tel'];
     $Time = $_POST['time'];
     $Mess = $_POST['location'];
     
     $Birth = $_POST['birthday'];
     $Email = $_POST['email'];
  
     $to = "info@ufs-federation.com"; 
     $headers = "Content-type: text/plain; charset = utf-8";
     $subject = "Сообщение с вашего сайта";
     $message = "Тема: $theme\nИмя: $FIO\nТелефон: $Tel\nВремя: $Time\nМесто проживания: $Mess\nEmail: $Email\nДата рождения: $Birth";
     //$send = @mail ($to, $subject, $message, $headers);
     
     $send = $this->global_model->send_emails($to,$subject,$message);
     
     if ($send == true) {
       echo "<b><font color=08509D><? echo(dictionary('Спасибо! Ваша заявка отправлена!')) ?></font><p>";
     } else {
       echo "<p><b><font color=8B0000><? echo(dictionary('Ошибка. Заявка не отправлена!')) ?></font>";
     }
   }
 }
 ?>

<script type="text/javascript">
function checkform2(f) {
  var errMSG = ""; 
  // цикл ниже перебирает все элементы в объекте f, 
  // переданном в качестве параметра
  // функции, в данном случае - наша форма.            
  for (var i = 0; i<f.elements.length; i++) 
    // если текущий элемент имеет атрибут required
    // т.е. обязательный для заполнения
    if (null!=f.elements[i].getAttribute("required")) 
       // проверяем, заполнен ли он в форме
        if (isEmpty(f.elements[i].value)) // пустой
            errMSG += "  " + f.elements[i].name + "\\n"; // формируем сообщение
                                                       // об ошибке, перечисляя 
                                                       // незаполненные поля
        // если сообщение об ошибке не пусто,
        // выводим его, и возвращаем false     
        if ("" != errMSG) {
            alert("Не заполнены обязательные поля:\\n" + errMSG);
            return false;
        }
}
function isEmpty(str) {
   for (var i = 0; i < str.length; i++)
      if (" " != str.charAt(i))
          return false;
      return true;
}
</script>

<script type="text/javascript">
$(document).ready(function(){
//Когда вы нажмете на ссылку с классом poplight и HREF начинается с a # 
$('a.poplight[href^=#]').click(function() {
    var popID = $(this).attr('rel'); //Get Popup Name
    var popURL = $(this).attr('href'); //Получить Popup HREF и определить размер
 
    //Запрос и  Переменные от HREF URL
    var query= popURL.split('?');
    var dim= query[1].split('&');
    var popWidth = dim[0].split('=')[1]; //Возвращает первое значение строки запроса
 
    // Добавить кнопку "Закрыть" в наше окно, прописываете прямой путь к картинке
    //$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="/img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
 
    //Определяет запас на выравнивание по центру (по вертикали по горизонтали)мы добавим 80px к высоте / ширине, значение полей вокруг содержимого (padding) и ширину границы устанавливаем в CSS
    var popMargTop = ($('#' + popID).height() + 80) / 2;
    var popMargLeft = ($('#' + popID).width() + 80) / 2;
 
    //Устанавливает величину отступа на Popup
    $('#' + popID).css({
        'margin-top' : -popMargTop,
        'margin-left' : -popMargLeft
    });
 
   //Fade in Background
    $('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Постепенное исчезание слоя - .css({'filter' : 'alpha(opacity=80)'}) используется для фиксации в IE , фильтр для устранения бага тупого IE 
 
    return false;
});
 
//Закрыть всплывающие окна и Fade слой
$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
    $('#fade , .popup_block').fadeOut(function() {
        $('#fade, a.close').remove();  //fade them both out
    });
    return false;
});

});
</script>