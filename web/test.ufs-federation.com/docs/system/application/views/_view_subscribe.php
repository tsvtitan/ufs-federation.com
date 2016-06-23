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
				subscribe_section_check_up(o);
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
	  subscribe_section_check_up(obj,false);
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
<div class="h_block">
  <div class="postion">
    <h1><a href="<? echo($this->phpself.$this->page_url.$this->urlsufix); ?>"><? echo(dictionary('Подписка на аналитику')); ?></a></h1>
  </div>
</div>
<div class="subscribe">
  <? if ($completed) { ?>
    <? if (trim($error)!='') { ?>
		<p class="error"><? echo($error) ?></p>
    <? } else { ?>
		<p class="info"><? echo($info) ?></p>
    <? } ?>
	<? if (isset($remove_link) && (trim($remove_link)!='')) { ?>
		<input id="subscribe_remove" name="submit" type="submit" value="<? echo(dictionary('Отменить рассылку')); ?>" onclick="subscribe_remove_click(this);" style="font-weight: bold;" />
	<? } ?>
  <? } else { ?>
	<? if (trim($error)!='') { ?>
		<p class="error"><? echo($error) ?></p> 
	<? } ?>
  <table>
    <form method="POST" id="subscribe_form" action="<? echo($this->phpself.$this->page_url.$this->urlsufix); ?>">
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
            UFS IC analytical materials are provided only for informational purposes and do not offer to apply securities transactions, in particular  their purchase or sale. Assessments and views are relied on the analysts’ opinions of the company regarding securities under review and issuers. The company and its employees bear no responsibility for the made investment resolutions based on the information from analytical materials.
            <? } elseif($this->site_lang=='de') {?>
            Analytischen Materialien von den Investmentgesellschaft UFS sind lediglich zur Information vorgelegt und sind keine Angebote für die Überweisung der Wertpapiergeschäfte, insbesondere ihre Käufe und Verkäufe. Beurteilungen und Aussichten werden auf die Ansichten der Analytiker bezüglich  beobachteter Wertpapiere und Emittenten gesetzt.Das Unternehmen und seine Mitarbeiter übernehmen keine Haftung für die gemachten Investitionen Resolutionen basierend auf den Informationen der analytischen Materialien.
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
      <tr><td class="submit"><input id="subscribe_submit" name="submit" type="submit" <? echo($submit_disabled); ?> value="<? echo(dictionary('Подписаться')); ?>" style="font-weight: <? echo($submit_fontweight); ?>;" /></td></tr> 
    </form>
  </table>
  <? } ?>  
</div>    