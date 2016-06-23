<script>
     function notify(obj)
  {
    var url='<? echo($this->phpself) ?>notify.html?type=send&email='+document.getElementById("mce-EMAIL").value;
    $.getJSON(url,{format: 'json'}).done(function(data) {
      if (data) {
        var o = document.getElementById("div-info-p");
        if (data.success) {
          o.innerText = "Спасибо за Вашу регистрацию. Специалисты компании отправят Вам на электронную почту место и время нашей следующей встречи на «Летней Веранде»";
        } else {
          o.innerText = "Ошибка. Попробуйте еще раз";
        }
        document.getElementById("div-info").style.display = "inline";
      }
    });
    return false;
  }
  
  function info_close() {
    var o = document.getElementById("div-info");
    if (o) {
      o.style.display = "none";
    }
  }
</script>
<div class="subscription-box" style="margin-left: 0; margin-bottom: 22px">
    <h2>Регистрация участников</h2>
<div id="div-info" style="display: none;">
        <p id="div-info-p">&#160;</p>
        
</div>
    <div id="text-hide">
        <h5>Зарегистрируйтесь на наши мероприятия</h5>
    </div>  
    
<div id="mc_embed_signup">
    <input type="text" name="email" class="email required" id="mce-EMAIL" placeholder="Ваша электронная почта" />
    <div class="clear">               <!-- <a href="javascript:void(0);"> -->
        <input type="button" onclick="document.getElementById('text-hide').innerHTML = ' '; notify(this);" value="Регистрация" name="notify" id="mc-embedded-subscribe" class="button" /> 
                <!-- </a> -->
</div>
</div>
</div>