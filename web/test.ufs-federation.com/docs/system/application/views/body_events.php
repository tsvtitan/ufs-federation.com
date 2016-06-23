   
<? if ($this->last_page_name=='learning' || $this->last_page_name=='training-center' || $this->last_page_name=='trainingszentrum' || $this->last_page_name=='centre-dtudes') { ?>

<script> 
  
  function notify_learing(obj)  
  {  
    document.getElementById('text-hide').innerHTML = ' ';
    
    var failed = false;
    
    var city = document.getElementById("learning-city").value;
    if (city==null || city=='') { failed = "<? echo(dictionary('Город')); ?>"; }

    var phone = document.getElementById("learning-phone").value;
    if (phone==null || phone=='') { failed = "<? echo(dictionary('Телефон для связи')); ?>"; }
    
    var email = document.getElementById("learning-email").value;
    if (email==null || email=='') { failed = "<? echo(dictionary('Адрес электронной почты')); ?>"; }

    var name = document.getElementById("learning-name").value;
    if (name==null || name=='') { failed = "<? echo(dictionary('Представьтесь, пожалуйста')); ?>"; }
    

    if (!failed) {
      
      var url='<? echo($this->phpself) ?>notify.html?type=learning'+
              '&name='+name+
              '&email='+email+
              '&phone='+phone+
              '&city='+city+
              '&event='+document.getElementById("learning-event").value;

      $.getJSON(url,{format:'json'}).done(function(data) {    
       if (data) {
          var info = document.getElementById("div-info-p");
          if (data.success) {
            info.innerHTML = "<? echo(dictionary('Спасибо! Ваша заявка отправлена.')); ?>";
            document.getElementById("learning-subscribe").style.display = "none";
          } else {
            info.innerHTML = data.message;
          }
          document.getElementById("div-info").style.display = "inline";
        }
      });
      
    } else {
      var info = document.getElementById("div-info-p");
      info.innerHTML = failed;
      document.getElementById("div-info").style.display = "inline";
    }
    
    return false;
  }
  
  function info_close() {
    var o = document.getElementById("div-info");
    if (o) {
      o.style.display = "none";
    }
  }
  
</script>

<div class="section_extra">
  <h2><? echo(dictionary('Online заявка на семинар')); ?></h2>
  <div class="subscription-box" style="margin-left: 0; margin-bottom: 22px">
    <div id="mc_embed_signup">
      <form>
        <h5 style="margin-top:  5px;"><? echo(dictionary('Представьтесь, пожалуйста')); ?> <span style="color:red">*</span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="name required" id="learning-name" maxlength="100" />
        <h5 style="margin-top:  5px;"><? echo(dictionary('Адрес электронной почты')); ?> <span style="color:red">*</span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="email required" id="learning-email" maxlength="128" />
        <h5 style="margin-top:  5px;"><? echo(dictionary('Телефон для связи')); ?> <span style="color:red">*</span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="phone" id="learning-phone" maxlength="12" />
        <h5 style="margin-top:  5px;"><? echo(dictionary('Город')); ?> <span style="color:red">*</span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="city required" id="learning-city" maxlength="250" />
        <h5 style="margin-top:  5px;"><? echo(dictionary('Семинар')); ?> <span></span></h5>
        <span style="margin-left: 12px;">
          <select style="border: 1px solid #c2cdcf; box-shadow: inset 1px 1px 1px rgba(0,0,0,0.1); width: 160px; " id="learning-event">
            <option value="Искусство торговли акциями и еврооблигациями на торговых идеях без манипулирования и инсайда"><? echo(dictionary('Искусство торговли акциями и еврооблигациями на торговых идеях без манипулирования и инсайда')); ?></option>
            <option value="Курс Президента на Восток: структурирование евробондов на площадке Сингапур-ГонкКонг. Что нужно китайским большим инвестиционным концернам?"><? echo(dictionary('Курс Президента на Восток: структурирование евробондов на площадке Сингапур-ГонкКонг. Что нужно китайским большим инвестиционным концернам?')); ?></option>
          </select>
        </span>
      </form>
    </div>  
    <div id="div-info" style="display: none; float:none; width:100%" class="conference-inner">
      <p id="div-info-p">&#160;</p>
    </div>
    <div id="text-hide">
      <p>&#160;</p>
    </div>
    <div class="clear"><input type="button" style="border:0; display: block; margin: auto; margin-bottom: 7px;" onclick="notify_learing(this);" value="<? echo(dictionary('Отправить')); ?>" name="notify" id="learning-subscribe" class="button" /></div>
</div>
<? } ?>    