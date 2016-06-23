<? if($this->site_lang=='ru') { ?>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,300&amp;subset=latin,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/css/anketa.css">
<script src="/js/popup.js" type="text/javascript"></script>
<script> 

$(document).ready(function() {
  $('#callback, .callback').click(function(e){
    var theInput = $('#callback-phone');
    theInput.focus();
    
    e.stopPropagation();
    e.preventDefault();
    
    Avgrund.show('#callback-popup');
    return false;
  });
  $('#callback-popup button').click(function(){
    var phone = $('#callback-phone').val();
    var length = phone.length;
    
    if (length == 11) {
      $('#callback-popup input').prop('disabled', true);
      $('#callback-popup input').addClass('.disabled');
      
      var url='http://ru.ufs-federation.com/callback.html?phone='+phone;
      $.getJSON(url,{format:'json'}).done(function(data) {
        if (data) {
          if (data.success) {
            $('#callback-popup button').text('Соединение...');
          } else {
            $('#callback-popup button').text(data.message);
          }
        }
        //$('#mc-embedded-callback').show();
      });
    } else {
      $('#callback-phone').focus();
    }
    return false;
  });
  $('.open-dialog').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    return false;
    
    Avgrund.show('#default-popup');
  });
  $('.close-dialog, .popup-cover').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    Avgrund.hide();
    return false;
  });
});
</script>
<div id="callback-popup" class="popup-popup popup-mode-call" style="z-index: 1200">
  <form action="action">
    <div class="popup-caption">
      <div class="badge"></div>
      Пожалуйста, укажите Ваш номер телефона
      <input type="text" id="callback-phone" placeholder="Например: 8**********" maxlength="11"/>
    </div>
    <div class="popup-footer">
      <button>Заказать звонок</button>
    </div>
  </form>
</div>
<div class="popup-cover" style="z-index: 11"><a class="close-dialog"><i></i></a></div>

<a href="#" class="btn-callback" id="callback"><i></i>Заказать звонок</a>
  <? /*
  <? echo(dictionary('Обратный звонок')); ?>
  <h5><? echo(dictionary('Оставьте ваш номер телефона и мы перезвоним вам')); ?></h5>
  <div id="mc_embed_signup">
    <form id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate">
      <input type=text name=phone class="email" id="mce-PHONE" placeholder="8XXXXXXXXXX" required>
    </form>
    <div class="clear"><input type="submit" value="<? echo(dictionary('Перезвонить мне')); ?>" name="callback" id="mc-embedded-callback" class="button" onclick="callback(this);"></div>
  </div>
  */ ?>
<? } ?>  