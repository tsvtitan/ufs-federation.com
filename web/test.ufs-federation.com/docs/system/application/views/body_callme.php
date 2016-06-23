<script type="text/javascript">
function checkform(f) {
  var isError = false;
  $.each(f, function() {
    var req = $(this).attr('req');
    if (typeof req !== 'undefined') {
        if ($(this).val() == '' || $(this).val() == $(this).attr('placeholder') || ($(this).attr('name')=='theme' && $(this).val() == '<? echo(dictionary("Общие вопросы")) ?>')) {
            isError++;
            $(this).css('border-color', '#bb0000');
            var err = $(this).next('p.error');
            if (err.length==0) {
                $(this).after('<p class="error">' + $(this).attr('error') + '</p>');
            }
        } else {
            $(this).css('border-color', '#ddd');
            $(this).next('p.error').remove();
        }
    }
  });
  if(isError) return false;
}

function isEmpty(str) {
   for (var i = 0; i < str.length; i++)
      if (" " != str.charAt(i))
          return false;
      return true;
}

function openPopUp(popID, popURL) {
    //Запрос и  Переменные от HREF URL
    var query= popURL.split('?');
    var dim= query[1].split('&');
    var popWidth = dim[0].split('=')[1]; //Возвращает первое значение строки запроса

    // Добавить кнопку "Закрыть" в наше окно, прописываете прямой путь к картинке
    $('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="/img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');

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
}
$(document).ready(function(){
    //Когда вы нажмете на ссылку с классом poplight и HREF начинается с a # 
    var wlink = $('a.poplight[href^=#]');
    var popID = $(wlink).attr('rel'); //Get Popup Name
    var popURL = $(wlink).attr('href'); //Получить Popup HREF и определить размер

    $(wlink).click(function(){
        openPopUp(popID, popURL)
    });

    //Закрыть всплывающие окна и Fade слой
    $('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
        $('#fade , .popup_block').fadeOut(function() {
            $('#fade, a.close').remove();  //fade them both out
        });
        return false;
    });

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
var form = $(this).closest('FORM');
if (form.length)
form.submit(function(){
if ($(this).val()==$(this).attr('placeholder'))
$(this).val('');
});
});
}

});
</script>

<?
$submit = (isset($_POST['submit']) && isset($_POST['callme'])) ? true : false;
$send = false;
 if ($submit) {
   if (isset($_POST['name']) && $_POST['name'] != "" and isset($_POST['word']) && $_POST['word'] != "" and $_POST['tel'] != "") {
        if ($_POST['word']!=$_SESSION['captcha_word2']) {
            $error = dictionary('Неправильно указано число с картинки.');
            $completed = false;
        } else {
             $theme = $_POST['theme'];
             $FIO = $_POST['name'];
             $Tel = $_POST['tel'];
             $Time = $_POST['time'];
             $Mess = $_POST['message'];

             $to = "info@ufs-federation.com"; // 
             $headers = "Content-type: text/plain; charset = utf-8";
             $subject = "Сообщение с вашего сайта";
             $message = "Тема: $theme\nИмя: $FIO\nТелефон: $Tel\nВремя: $Time\nСообщение: $Mess";
             //$send = @mail ($to, $subject, $message, $headers);
             $send = $this->global_model->send_emails($to,$subject,$message);
             
        }
     if ($send == true) {
       echo "<span style=\"color: #08509D; font-weight: bold;\">Спасибо! Вам обязательно перезвонят!</span>";
     } else {
       echo "<span style=\"color: #8B0000; font-weight: bold;\">Ошибка. Сообщение не&nbsp;отправлено!</span>";
       ?><script type="text/javascript">
$(document).ready(function() {
    var wlink = $('a.poplight[href^=#]');
    var popID = $(wlink).attr('rel'); //Get Popup Name
    var popURL = $(wlink).attr('href'); //Получить Popup HREF и определить размер
    $(wlink).trigger('click');
    $('#form').trigger('submit');
});
</script><?
     }
   }
 }
?>


<?
require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/strutils.php');

if (!isset($_POST['callme']) || $_POST['word']!=$_SESSION['captcha_word2'] || $send==true) {
 //&& !isset($_POST['captcha_word2'])
    $this->load->helper('captcha');
    $captcha_word2 = random_number(5);
    $params = array(
        'word'=>$captcha_word2,
        'img_path'=>$_SERVER['DOCUMENT_ROOT'].'/images/captcha/',
        'img_url'=>$this->phpself.'/images/captcha/',
        'img_width'=>100,
        'img_height'=>30,
        'expiration'=>3600);
    $captcha = create_captcha($params);
    if ($captcha) {
        $_SESSION['captcha_word2'] = $captcha['word'];
        $_SESSION['captcha_image'] = $data['captcha_image'] = $captcha['image'];
    }
}
?>
<style>#form p img {margin: 0;}</style>
<a class="poplight" rel="popup_contact" href="#?w=580" ><? echo(dictionary('Заказать звонок')) ?></a>
<div id="popup_contact" class="popup_block">
<div id="note">
<h3><? echo(dictionary('Нет возможности дозвониться до менеджера')) ?>?<br><? echo(dictionary('Оставьте заявку, и Вам обязательно перезвонят')) ?>.</h3>
</div>
<form id="form" action="" method="post" OnSubmit="return checkform(this)"><input type="hidden" name="callme" value="1"/>
<table cellpadding="5" cellspacing="5">
  <tr>
    <td>
        <label for="theme"><? echo(dictionary('Тема письма')) ?> *</label>
        <select tabindex="1" id="theme" name="theme" size="" type="text" req="required" error="<? echo(dictionary('Пожалуйста, укажите тему письма')); ?>.">

            <option value="<? echo(dictionary('Общие вопросы')) ?>" <?=!isset($_POST['theme']) && !$send || $_POST['theme'] == dictionary('Общие вопросы') ? 'selected="selected"' : ''?>><? echo(dictionary('Общие вопросы')) ?></option>
            <option value="<? echo(dictionary('Услуги на фондовом рынке')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Услуги на фондовом рынке')) ? 'selected="selected"' : ''?>><? echo(dictionary('Услуги на фондовом рынке')) ?></option>
            <option value="<? echo(dictionary('Услуги на рынках долгового капитала')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Услуги на рынках долгового капитала')) ? 'selected="selected"' : ''?>><? echo(dictionary('Услуги на рынках долгового капитала')) ?></option>
            <option value="<? echo(dictionary('Услуги на рынках акционерного капитала')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Услуги на рынках акционерного капитала')) ? 'selected="selected"' : ''?>><? echo(dictionary('Услуги на рынках акционерного капитала')) ?></option>
            <option value="<? echo(dictionary('Золото')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Золото')) ? 'selected="selected"' : ''?>><? echo(dictionary('Золото')) ?></option>
            <option value="<? echo(dictionary('Wealth Management')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Wealth Management')) ? 'selected="selected"' : ''?>><? echo(dictionary('Wealth Management')) ?></option>
            <option value="<? echo(dictionary('Фонды совместных инвестиций')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Фонды совместных инвестиций')) ? 'selected="selected"' : ''?>><? echo(dictionary('Фонды совместных инвестиций')) ?></option>
            <option value="<? echo(dictionary('Финансовый консалтинг')) ?>" <?=(isset($_POST['theme']) && !$send && $_POST['theme'] == dictionary('Финансовый консалтинг')) ? 'selected="selected"' : ''?>><? echo(dictionary('Финансовый консалтинг')) ?></option>
        </select>
    </td>
    <td><p><label for="time"><? echo(dictionary('Удобное время для звонка')) ?></label>
        <select tabindex="4" id="time" name="time" size="" type="text"/>
        <option value="<? echo(dictionary('Время не указано')) ?>" <?=!isset($_POST['time']) && !$send || $_POST['time'] == dictionary('Время не указано') ? 'selected="selected"' : ''?>><? echo(dictionary('Время не указано')) ?></option>
        <option <?=(isset($_POST['time']) && !$send && $_POST['time'] == '10:00 - 13:00') ? 'selected="selected"' : ''?>>10:00 - 13:00</option>
        <option <?=(isset($_POST['time']) && !$send && $_POST['time'] == '13:00 - 16:00') ? 'selected="selected"' : ''?>>13:00 - 16:00</option>
        <option <?=(isset($_POST['time']) && !$send && $_POST['time'] == '16:00 - 19:00') ? 'selected="selected"' : ''?>>16:00 - 19:00</option>
        </select></p>
    </td>
  </tr>
  <tr>
    <td><p class="first"><label for="name"><? echo(dictionary('Ваше имя')) ?> *</label>
        <input tabindex="2" id="name" name="name" value="<?=isset($_POST['name']) && !$send ? $_POST['name'] : ''?>" size="30" type="text" req="required" placeholder="<? echo(dictionary('Как к Вам обращаться')) ?>?" error="<? echo(dictionary('Пожалуйста, укажите ваше имя')); ?>."/></p>
    </td>
    <td rowspan="4"><p><label for="message"><? echo(dictionary('Сообщение')) ?></label>
        <textarea tabindex="5" id="message" cols="25" rows="15" name="message"><?=isset($_POST['message']) && !$send ? $_POST['message'] : ''?></textarea></p>
    </td>
  </tr>
  <tr>
    <td><p><label for="tel"><? echo(dictionary('Ваш телефон')) ?> *</label>
        <input tabindex="3" id="tel" name="tel" value="<?=isset($_POST['tel']) && !$send ? $_POST['tel'] : ''?>" size="30" type="text" req="required" placeholder="<? echo(dictionary('Контактный телефон')) ?>" error="<? echo(dictionary('Пожалуйста, укажите ваш номер телефона')); ?>."/></p>
    </td>
  </tr>
  <tr>
    <td><p><label for="word"><? echo(dictionary('Число с картинки')) ?> *</label><br/>
        <?=isset($data['captcha_image']) ? $data['captcha_image'] : $_SESSION['captcha_image']?>
        <input tabindex="6" name="word" id="word" value="" req="required" placeholder="<? echo(dictionary('Число с картинки')); ?>" error="<? echo(dictionary('Неправильно указано число с картинки')); ?>."/></p>
    </td>
  </tr>
  <tr>
    <td><p class="submit"><input type="submit" value="<? echo(dictionary('Отправить')) ?>" name="submit" style="width:150px; height:37px; line-height:37px;	border:none; background:url(/img/form_button.gif) no-repeat 0 0 #004C81; color:#fff; cursor:pointer; text-align:center;	margin-top:10px;" ></p></td>
  </tr>
</table>
</form>
<div id="error">&nbsp;</div>
</div>