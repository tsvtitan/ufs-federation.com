<script>

function subscribe_terms_click(obj)  {
	
  terms=document.getElementById('subscribe_terms');
  if (obj!=terms) {
    terms.checked=!terms.checked;
  }  
  submit=document.getElementById('subscribe_submit');
  submit.disabled=!terms.checked;
  if (terms.checked) {
    submit.style.fontWeight="bold";
    terms.value="on";      
  } else {
    submit.style.fontWeight="normal";
    terms.value="off";  
  }  
}

function subscribe_section_check_down(obj) {

  for (var i=0;i<=obj.form.length-1;i++) {
    o=obj.form[i];
    pid=o.attributes["pid"];
    if (pid) {
      pid=o.attributes["pid"].value;
      if (pid==obj.attributes["id"].value) {
        o.checked=obj.checked;
        o.value=o.checked?"on":"off";  
        subscribe_section_check_down(o);
      }	
    }	
  }	
}

function subscribe_section_check_up(obj,checked) {

  for (var i=obj.form.length-1;i>=0;i--) {
    o=obj.form[i];
    id=o.attributes["id"];
    if (id) {
      id=o.attributes["id"].value;
      if (id==obj.attributes["pid"].value) {
        o.checked=checked;
        o.value=o.checked?"on":"off";
        subscribe_section_check_up(o,checked);
      }	
    }	
  }	
}

function subscribe_section_click(obj) {

  name=obj.tagName;
  if (name=="A") {
    obj=obj.previousSibling;
    obj.checked=!obj.checked; 
  }
  obj.value=obj.checked?"on":"off";	
  subscribe_section_check_down(obj);
  if (obj.checked==true) { 
    subscribe_section_check_up(obj,true);
  }  	
}

function subscribe_lang_change(obj) {

  fm=document.getElementById('subscribe_form');
  if (fm.lang.value!="<? echo($this->site_lang) ?>") {
    temp=fm.action;
    temp=temp.replace("//<? echo($this->site_lang) ?>","//"+fm.lang.value);
    document.location.href=temp;
  }  	
}

function subscribe_image_click(obj) {

  if (obj.className=='image-off') {
    obj.className='image-on';
  }	else {
    obj.className='image-off';
  }	
}

<? if (isset($remove_link)) { ?>
function subscribe_remove_click(obj) {

  temp=document.location.href+"<? echo($remove_link); ?>";
  document.location.href=temp;
}
<? } ?>
</script>
<div class="subscribe">
  <? if ($completed) { ?>
    <? if (trim($error)!='') { ?>
      <p class="error"><? echo($error) ?></p>
    <? } else { ?>
      <p class="info"><? echo($info) ?></p>
    <? } ?>
      <? if (isset($remove_link) && (trim($remove_link)!='')) { ?>
      <form method="POST" id="unsubscribe_form">
        <p><? echo(dictionary('Пожалуйста, введите свой email, а затем нажмите кнопку «ОТПИСАТЬ»')); ?>:<br><br><input name="unsubscribe" type="text" placeholder="<? echo(dictionary('Укажите ваш адрес электронной почты')); ?>" /><br><br>
        <input id="subscribe_remove" name="submit" type="submit" value="<? echo(dictionary('ОТПИСАТЬ')); ?>" style="font-weight: bold;" /></p>
      </form>
      <? } ?>
      <? if( isset($feedback) && ($feedback)) { ?>
      <form method="POST" id="feedback_form">
        <p><? echo(dictionary('Ваш почтовый адрес отписан от рассылок аналитических обзоров UFS IC. Мы сожалеем о принятом Вами решении и будем рады, если Вы вновь вернетесь к нам.<br><br>'.
                              'Вы можете оставить отзыв о качестве нашей аналитики или указать причину Вашего решения ниже')); ?>
          <br><br><textarea name="comments" cols=67 rows=6></textarea><br><br>
        <? if (isset($email)) { ?>
        <input name="unsubscribe" type="text" hidden="true" value="<? echo($email) ?>"/>
        <? } ?>
          <input id="subscribe_remove" name="submit" type="submit" value="<? echo(dictionary('Отправить')); ?>" style="font-weight: bold;" /></p>
      </form>
      <? } ?>
    <? } else { ?>
      <? if (trim($error)!='') { ?>
        <p class="error"><? echo($error) ?></p> 
      <? } ?>
  <table>
    <form method="POST" id="subscribe_form" action="<? echo($action); ?>">
      <tr><td class="sections"><? echo($sections); ?></td></tr>      
      <tr>
        <td>
          <table class="fields">
            <tr>
              <td class="name"><? echo(dictionary('Логин')); ?>:&nbsp<input name="name" placeholder="<? echo(dictionary('Как нам к вам обращаться')); ?>" value="<? echo($name); ?>" /></td>
            </tr>
            <tr>
              <td class="email"><? echo(dictionary('E-Mail')); ?>:&nbsp<input name="email" placeholder="<? echo(dictionary('Укажите ваш адрес электронной почты')); ?>" value="<? echo($email); ?>" /></td>
            </tr>
                        <tr>
              <td><p><b><? echo(dictionary('Обратите внимание, чтобы e-mail был указан правильно, на него будет выслано письмо с подтверждением подписки.')); ?></b></p></td>
            </tr>
            <tr><td class="captcha">
              <table>
                <tr>
                  <td><? if(isset($captcha_image)) { echo($captcha_image); } ?></td>
                  <td><input name="word" placeholder="<? echo(dictionary('Число с картинки')); ?>" /></td>
                </tr>
              </table>
            </td></tr>
          </table>  
        </td>
      </tr>	
      <tr>
        <td class="agreement">
        <span>
          <? if ($this->site_lang=='en') { ?>
          UFS IC Research is provided for informational purpose only and cannot be considered as a proposal for carrying out transactions in the stock market, in particular, regarding securities purchase and sale. Valuations and opinions presented in this review are based solely on conclusions of company analysts in respect of analyzed issuers and securities. The Company and its employees are not responsible for any investment decisions based on the information provided in the Research.
          <? } elseif($this->site_lang=='de') {?>
          Die vorliegende Übersicht ist ausschließlich als ein Einblick geboten und ist kein Vorschlag, Transaktionen am Markt vorzunehmen, unter anderem Wertpapiere zu kaufen oder zu verkaufen. Einschätzungen und Meinungen basieren ausschließlich auf Schlussfolgerungen der Analytiker in Bezug auf analysierte Wertpapiere und Emittenten. Die Firma und  ihre Mitarbeiter tragen keine Verantwortung für Investitionsentscheidungen, die auf in Übersichten enthaltener Information basieren.
          <? } else { ?>
          Аналитические материалы UFS IC предоставляются исключительно в информационном порядке и не являются предложением о проведении операций на рынке ценных бумаг, и в частности предложением об их покупке или продаже. Оценки и мнения основаны на заключениях аналитиков компании в отношении анализируемых ценных бумаг и эмитентов. Компания и ее сотрудники не несут ответственности за принятые инвестиционные решения, основанные на информации, содержащейся в аналитических материалах. 
          <? } ?>
        </span>
        </td>
      </tr>
      <tr>
        <td class="terms"><input type="checkbox" id="subscribe_terms" name="terms" <? echo($terms_checked) ?> value="<? echo($terms) ?>" onclick="subscribe_terms_click(this);" />
          <a onclick="subscribe_terms_click(this); return false;"><? echo(dictionary('Я ознакомился и принимаю условия осуществляемой рассылки')); ?>
          </a>
        </td>
      </tr>
      <? if(isset($auto) && $auto) { ?><tr><td><input name="auto" type="hidden"/></td></tr><? } ?>
      <tr><td class="submit"><input id="subscribe_submit" name="submit" type="submit" <? echo($submit_disabled); ?> value="<? echo(dictionary('Подписаться')); ?>" style="font-weight: <? echo($submit_fontweight); ?>;" /></td></tr> 
    </form>
  </table>
  <? } ?>  
</div>    