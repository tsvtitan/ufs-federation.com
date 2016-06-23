<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">

<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>


<script>


$(function() {
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
		'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
		'Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		weekHeader: 'Нед',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['ru']);

	﻿$( "#f007" ).datepicker({
	    yearRange: "-90:+0", 
	    changeMonth: true,
	    changeYear: true
	   });

	$( "#f012" ).datepicker({
	    yearRange: "-90:+0", 
	    changeMonth: true,
	    changeYear: true
	   });
});

function application_form_post(obj) {
	
}

function application_form_back(obj) {

	fm = document.getElementById('application_form');
	if (fm) {
		fm.back.value = 'true';
		fm.submit();
	}		
}

</script>

<style>
	.steps-bar {
		border-bottom: 1px solid #004577;
		height: 56px;
		overflow: hidden;
	}
	.steps-bar .step {
		width: 97px;
		height: 51px;
		padding: 0 8px 0 30px;
		float: left;
		font-size: 12px;
	}
	.steps-bar .step .step-num {
		display: block;
		width: 17px;
		margin-left: -25px;
		float: left;
		font-size: 30px;
		overflow: hidden;
		color: #ccc;
		line-height: 30px;
		font-weight: bold;
		text-align: right;
		padding-right: 8px;
		
		/* display: none; */
	}
	.current-step span, .steps-bar .current-step .step-num {
		/* background-color: #004577; */
		color: #004577;
		font-weight: bold;
	}
	.steps-bar .progress-bar {
		height: 5px;
		background-color: #004577;
	}
	.clear {
		clear: both;
	}
	.current1 {
		width: 127px;
	}
	.current2 {
		width: 262px;
	}
	.current3 {
		width: 397px;
	}
	.current4 {
		width: 540px;
	}
	/* forms */
	input {
		font-size: 0.8em;
	}
	form {
		margin: 0;
		padding: 0;
		/* width: 33em !important; */
		position: relative;
	}
	label.required, .warning, input.invalid { color: #990000; }
	input.valid { color: green; }
	
	label[disabled="true"] { opacity: 0.4; color: black; }
	
	.warning { display: none; position: absolute; left: 100%; margin-left: 0.5em; top: 0; text-decoration: none; background: white; border: 1px solid #999999; width: 13em; padding: 0.25em; }
	.required .warning, .invalid .warning { display: block; font-size: 0.89em; }
	
	.text { width: 100%; }
	.presized,
	input.button { width: auto; }
	
	.row { clear: both; padding: 0; margin: 0; min-height: 1em;  }
	
	.label { display: block; width: 13em; margin: 0; padding: 0.2em 0 0 1em; float: left; }
	.label a { cursor: pointer; }
	.for_select_checkbox, .for_select_radio, .for_textarea { padding-top: 0; }
	.button { margin-top: 0.2em; margin-bottom: 1em; }
	
	.input { margin: 0 0 0 14em; padding: 0;  }
	
	.description { display: block; font-style: normal; font-size: 0.76em; }
	
	.selector .label .description { margin-bottom: 0.5em; }
	
	.row .row { margin-left: 20px; padding: 0; clear: none; }
	.row .row .input { margin: 0px; padding: 0; }
	
	.input { padding-bottom: 1em; }
	.for-previous { position: relative; top: -0.5em; }
	
	.selector { margin-bottom: 0 ! important; }
	.selector, .selector span.label { min-height: 20px; display: block; }
	.selector .label, .input .label { width: auto; float: none; margin: 0; padding: 0; }
	.selector .label { margin-left: 20px; }
	.selector input { float: left; margin-top: 0.4em; }
	
	.wide .label { float: none; width: auto; }
	.wide .for_select_checkbox,
	.wide .for_select_radio,
	.wide .for_select_select,
	.wide .for_select,
	.wide .for_textarea
	{ padding-bottom: 0; }
	.wide .input { margin-left: 0; padding-left: 0; margin-bottom: 1em; }
	
	.horizontal .selector { float: left; clear: none; margin-right: 1em; }
	.horizontal .selector .label { height: auto; }
	.horizontal .description { clear: left; }
	
	form fieldset { margin: 2em 0 2em 0;  padding: 1em 0; clear: left; border-left: 0 hidden; border-right: 0 hidden; border-bottom: 0 hidden; }
	form fieldset legend { margin: 0; padding: 0 0.5em 0 0; font-size: 0.76em; font-weight: bold;  }
	* html form fieldset legend { margin-left: -7px; margin-right: -7px; }
	form fieldset fieldset { border: 0 hidden; margin: 0; padding-bottom: 0; padding-top: 1em; }
	form fieldset fieldset legend { padding-top: 1em; }
	
	@media handheld {
		form,
		.label
		{
		width: auto ! important;
		}
		.label,
		.input
		{
		float: none;
		padding-bottom: 0;
		}
		.input,
		.submit input
		{
		margin-left: 0 ! important;
		}
		.row { margin-bottom: 1em; }
		.row .row { margin-bottom: 0; }
		.warning { position: static; float: right; }
	}
	
	/*** votes ***/
	#vote dt.label { font-weight: bold; padding-bottom: 1em; }
	#vote .button { margin-top: 0; }
	#vote #row_action dt.label { display: none; }
	
	.invisible, .invisible th, .invisible td {
		border: 0;
		margin: 0;
		padding: 0;
	}
	
	.flat-btn {
		min-width: 160px;
		margin: 0 0 3px;
		padding: 0.4em;
		border: none;
		background: #859c9f;
		color: #fff;
	}
	.input select {
		border: 1px solid #ccc;
	}
	.fullwidth {
		width: 98%;
	}
	table.fullwidth {
		width: 100%;
	}
	select.fullwidth {
		width: 70%;
	}
	.miniwidth {
		width: 6em;
	}
	.wide .label {
		text-align: justify;
	}
	label sup, .required-sign {
		color: #df0300;
		font-size: 100%;
		display: inline-block;
		margin-left: 2px;
		font-weight: bold;
	}
	td.align-right {
		text-align: right;
	}
	label sup, .required-sign, .error {
    color: #DD4B39;
  }

  input.error {
    border-color: #DD4B39;
  }
  .error {
    display: block;
    font-size: 85%;
    margin-top: 2px;
  }
  
  ﻿.msgbox {
    clear: both;
    padding: 0.5em 1em 0;
  }

  .msgbox p {
    margin: 0 0 1em !important;
    padding: 0 1px;
    background-color: #bd302c;
    display: inline-block;
  }

  .msgbox p span {
    display: inline-block;
    margin: -1px 0;
    padding: 2px 0.5em;
    background: #bd302c;
    color: #fff;
    position: relative;
    font-size: 95%;
  }
 
</style>

<? if (($step>=1) && ($step<=4)) { ?>
<form method="post" id="application_form">
<div class="steps-bar">
	<div class="step<? echo(($step==1)?' current-step':'') ?>">
		<span class="step-num">1</span>
		<span><? echo(dictionary('Персональные данные')) ?></span>
	</div>
	<div class="step<? echo(($step==2)?' current-step':'') ?>">
		<span class="step-num">2</span>
		<span><? echo(dictionary('Адресная информация')) ?></span>
	</div>
	<div class="step<? echo(($step==3)?' current-step':'') ?>">
		<span class="step-num">3</span>
		<span><? echo(dictionary('Банковские счета')) ?></span>
	</div>
	<div class="step<? echo(($step==4)?' current-step':'') ?>">
		<span class="step-num">4</span>
		<span><? echo(dictionary('Дополнительные сведения')) ?></span>
	</div>
	<br class="clear"/>
	<div class="progress-bar <? echo('current'.$step) ?>"></div>
</div>
<? if (isset($message)) { ?>
<div class="msgbox">
<p><span><? echo($message) ?></span></p><br/>
</div> 
<? } ?>
<? } ?>
 
<? if ($step==1) { ?>
<h2><? echo(dictionary('Персональные данные')) ?></h2>
<h3 class="anketa"><? echo(dictionary('Общие сведения')) ?></h3>
<p class="description"><span class="required-sign">*</span>&nbsp;&mdash; <? echo(dictionary('поля, обязательные для заполнения')) ?>.</p>

<dl class="row">
	<dt class="label"><label for="f001"><? echo(dictionary('Фамилия')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f001" class="fullwidth" type="text" name="data[last_name]" value="<? echo(isset($last_name)?$last_name:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f002"><? echo(dictionary('Имя')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f002" class="fullwidth" type="text" name="data[first_name]" value="<? echo(isset($first_name)?$first_name:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f003"><? echo(dictionary('Отчество')) ?></label></dt>
	<dd class="input"><input id="f003" class="fullwidth" type="text" name="data[middle_name]" value="<? echo(isset($middle_name)?$middle_name:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f004"><? echo(dictionary('Email')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f004" class="fullwidth" type="text" name="data[email]" value="<? echo(isset($email)?$email:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f005"><? echo(dictionary('Телефон')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f005" type="text" name="data[phone]" value="<? echo(isset($phone)?$phone:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f006"><? echo(dictionary('Факс')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f006" type="text" name="data[fax]" value="<? echo(isset($fax)?$fax:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f007"><? echo(dictionary('Дата рождения')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f007" type="text" name="data[birth_date]" value="<? echo(isset($birth_date)?$birth_date:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f008"><? echo(dictionary('Место рождения')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f008" class="fullwidth" type="text" name="data[birth_place]" value="<? echo(isset($birth_place)?$birth_place:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f009"><? echo(dictionary('ИНН')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f009" type="text" name="data[inn]" value="<? echo(isset($inn)?$inn:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for=""><? echo(dictionary('Резидент РФ')) ?><sup>*</sup></label></dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r001" name="data[citizen]" value="1" <? echo((isset($citizen)&&($citizen==1))?' checked':'') ?>/>
			<span class="label"><label for="r001"><? echo(dictionary('Да')) ?></label></span>
		</div>
		<div class="selector">
			<input type="radio" id="r002" name="data[citizen]" value="0" <? echo((isset($citizen)&&($citizen==0))?' checked':'') ?>/>
			<span class="label"><label for="r002"><? echo(dictionary('Нет')) ?></label></span>
		</div>
	</dd>
</dl>

<h3><? echo(dictionary('Паспортные данные')) ?></h3>

<dl class="row">
	<dt class="label"><label for="f010"><? echo(dictionary('Серия и номер')) ?></label></dt>
	<dd class="input"><input id="f010" type="text" name="data[passport_number]" value="<? echo(isset($passport_number)?$passport_number:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f011"><? echo(dictionary('Кем выдан')) ?></label></dt>
	<dd class="input"><input id="f011" class="fullwidth" type="text" name="data[passport_authority]" value="<? echo(isset($passport_authority)?$passport_authority:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f012"><? echo(dictionary('Дата выдачи')) ?></label></dt>
	<dd class="input"><input id="f012" type="text" name="data[passport_date]" value="<? echo(isset($passport_date)?$passport_date:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f013"><? echo(dictionary('Код подразделения')) ?></label></dt>
	<dd class="input"><input id="f013" class="miniwidth" type="text" name="data[passport_code]" value="<? echo(isset($passport_code)?$passport_code:'') ?>"/></dd>
</dl>

<? } elseif ($step==2) { ?>

<h2><? echo(dictionary('Адресная информация')) ?></h2>
<p class="description"><span class="required-sign">*</span>&nbsp;&mdash; <? echo(dictionary('поля, обязательные для заполнения')) ?>.</p>

<h3><? echo(dictionary('Адрес места жительства или места пребывания')) ?></h3>

<dl class="row">
	<dt class="label"><label for="f014"><? echo(dictionary('Индекс')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f014" class="miniwidth" type="text" name="data[residence_zipcode]" value="<? echo(isset($residence_zipcode)?$residence_zipcode:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f015"><? echo(dictionary('Область/Край')) ?></label></dt>
	<dd class="input">
		<select id="f015" class="fullwidth" name="data[residence_region_id]">
		  <option value=""<? echo(!isset($residence_region_id)?' selected':'') ?>><? echo(dictionary('выберите')) ?></option>
		  <? if(isset($residence_regions)) { 
		       foreach ($residence_regions as $r) {
		         echo(sprintf('<option value="%s"%s>%s</option>',$r->region_id,($residence_region_id==$r->region_id)?' selected':'',$r->name));	
		       }		 
		     }
		  ?>
		</select>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f016"><? echo(dictionary('Населенный пункт')) ?></label></dt>
	<dd class="input"><input id="f016" class="fullwidth" type="text" name="data[residence_locality]" value="<? echo(isset($residence_locality)?$residence_locality:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f017"><? echo(dictionary('Улица')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f017" class="fullwidth" type="text" name="data[residence_street]" value="<? echo(isset($residence_street)?$residence_street:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f018"><? echo(dictionary('Дом')) ?><sup>*</sup></label></dt>
	<dd class="input">
		<table class="invisible fullwidth">
			<tr>
				<td><input id="f018" class="miniwidth" type="text" name="data[residence_house]" value="<? echo(isset($residence_house)?$residence_house:'') ?>"/></td>
				<td><label for="f019">&nbsp;<? echo(dictionary('Стр.')) ?>&nbsp;</label><input id="f019" class="miniwidth" type="text" name="data[residence_building]" value="<? echo(isset($residence_building)?$residence_building:'') ?>"/></td>
				<td class="align-right"><label for="f020">&nbsp;<? echo(dictionary('Кв.')) ?>&nbsp;</label><input id="f020" class="miniwidth" type="text" name="data[residence_flat]" value="<? echo(isset($residence_flat)?$residence_flat:'') ?>"/></td>
			</tr>
		</table>
	</dd>
</dl>

<h3><? echo(dictionary('Почтовый адрес')) ?></h3>

<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r03" name="data[post_as_residence]" value="1"<? echo((isset($post_as_residence)&&($post_as_residence==1))?' checked':'') ?>/>
			<span class="label"><label for="r03"><? echo(dictionary('Почтовый адрес совпадает с&nbsp;местом жительства')) ?></label></span>
		</div>
		<div class="selector">
			<input type="radio" id="r04" name="data[post_as_residence]" value="0"<? echo((isset($post_as_residence)&&($post_as_residence==0))?' checked':'') ?>/>
			<span class="label"><label for="r04"><? echo(dictionary('Указать другой адрес')) ?></label></span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f021"><? echo(dictionary('Индекс')) ?></label></dt>
	<dd class="input"><input id="f021" class="miniwidth" type="text" name="data[post_zipcode]" value="<? echo(isset($post_zipcode)?$post_zipcode:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f022"><? echo(dictionary('Область/Край')) ?></label></dt>
	<dd class="input">
		<select id="f022" class="fullwidth" name="data[post_region_id]">
		  <option value=""<? echo(!isset($post_region_id)?' selected':'') ?>><? echo(dictionary('выберите')) ?></option>
		  <? if(isset($post_regions)) { 
		       foreach ($post_regions as $p) {
		         echo(sprintf('<option value="%s"%s>%s</option>',$p->region_id,($post_region_id==$p->region_id)?' selected':'',$p->name));	
		       }		 
		     }
		  ?>
		</select>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f023"><? echo(dictionary('Населенный пункт')) ?></label></dt>
	<dd class="input"><input id="f023" class="fullwidth" type="text" name="data[post_locality]" value="<? echo(isset($post_locality)?$post_locality:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f024"><? echo(dictionary('Улица')) ?></label></dt>
	<dd class="input"><input id="f024" class="fullwidth" type="text" name="data[post_street]" value="<? echo(isset($post_street)?$post_street:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f025"><? echo(dictionary('Дом')) ?></label></dt>
	<dd class="input">
		<table class="invisible fullwidth">
			<tr>
				<td><input id="f025" class="miniwidth" type="text" name="data[post_house]" value="<? echo(isset($post_house)?$post_house:'') ?>"/></td>
				<td><label for="f026">&nbsp;<? echo(dictionary('Стр.')) ?>&nbsp;</label><input id="f026" class="miniwidth" type="text" name="data[post_building]" value="<? echo(isset($post_building)?$post_building:'') ?>"/></td>
				<td class="align-right"><label for="f027">&nbsp;<? echo(dictionary('Кв.')) ?>&nbsp;</label><input id="f027" class="miniwidth" type="text" name="data[post_flat]" value="<? echo(isset($post_flat)?$post_flat:'') ?>"/></td>
			</tr>
		</table>
	</dd>
</dl>

<? } elseif ($step==3) { ?>

<h2><? echo(dictionary('Банковские счета')) ?></h2>
<p class="description"><span class="required-sign">*</span>&nbsp;&mdash; <? echo(dictionary('поля, обязательные для заполнения')) ?>.</p>

<h3><? echo(dictionary('Реквизиты банковского счета для перечисления денежных средств денежных средств в&nbsp;рублях&nbsp;РФ')) ?></h3>

<dl class="row">
	<dt class="label"><label for="f028"><? echo(dictionary('Получатель')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f028" class="fullwidth" type="text" name="data[bank_recipient]" value="<? echo(isset($bank_recipient)?$bank_recipient:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f029"><? echo(dictionary('Расчетный счет')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f029" class="fullwidth" type="text" name="data[bank_current_account]" value="<? echo(isset($bank_current_account)?$bank_current_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f030"><? echo(dictionary('Лицевой счет')) ?></label></dt>
	<dd class="input"><input id="f030" class="fullwidth" type="text" name="data[bank_personal_account]" value="<? echo(isset($bank_personal_account)?$bank_personal_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f031"><? echo(dictionary('Наименование банка')) ?></label></dt>
	<dd class="input"><input id="f031" class="fullwidth" type="text" name="data[bank_name]" value="<? echo(isset($bank_name)?$bank_name:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f032"><? echo(dictionary('Регион банка')) ?></label></dt>
	<dd class="input">
		<select id="f032" class="fullwidth" name="data[bank_region_id]">
		  <option<? echo(!isset($bank_region_id)?' selected':'') ?>><? echo(dictionary('выберите')) ?></option>
		  <? if(isset($bank_regions)) { 
		       foreach ($bank_regions as $b) {
		         echo(sprintf('<option value="%s"%s>%s</option>',$b->region_id,($bank_region_id==$b->region_id)?' selected':'',$b->name));	
		       }		 
		     }
		  ?>
		</select>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f033"><? echo(dictionary('Город банка')) ?></label></dt>
	<dd class="input"><input id="f033" class="fullwidth" type="text" name="data[bank_town]" value="<? echo(isset($bank_town)?$bank_town:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f034"><? echo(dictionary('ИНН банка')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f034" class="fullwidth" type="text" name="data[bank_inn]" value="<? echo(isset($bank_inn)?$bank_inn:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f035"><? echo(dictionary('БИК банка')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f035" class="fullwidth" type="text" name="data[bank_bic]" value="<? echo(isset($bank_bic)?$bank_bic:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f036"><? echo(dictionary('Корреспондентский счет')) ?></label></dt>
	<dd class="input"><input id="f036" class="fullwidth" type="text" name="data[bank_corr_account]" value="<? echo(isset($bank_corr_account)?$bank_corr_account:'') ?>"/></dd>
</dl>

<h3><? echo(dictionary('Реквизиты банковского счета для перечисления денежных средств денежных средств в&nbsp;иностранной валюте')) ?></h3>

<dl class="row wide">
	<dt class="label"><label for=""><? echo(dictionary('Имеется&nbsp;ли у&nbsp;вас счет для перечисления денежных средств в&nbsp;иностранной валюте')) ?>?</label></dt>
</dl>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r05" name="data[out_bank]" value="1"<? echo((isset($out_bank)&&($out_bank==1))?' checked':'') ?>/>
			<span class="label"><label for="r05"><? echo(dictionary('Да')) ?></label></span>
		</div>
		<div class="selector">
			<input type="radio" id="r06" name="data[out_bank]" value="0"<? echo((isset($out_bank)&&($out_bank==0))?' checked':'') ?>/>
			<span class="label"><label for="r06"><? echo(dictionary('Нет')) ?></label></span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f037"><? echo(dictionary('Получатель')) ?></label></dt>
	<dd class="input"><input id="f037" class="fullwidth" type="text" name="data[out_bank_recipient]" value="<? echo(isset($out_bank_recipient)?$out_bank_recipient:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f038"><? echo(dictionary('Расчетный счет')) ?></label></dt>
	<dd class="input"><input id="f038" class="fullwidth" type="text" name="data[out_bank_current_account]" value="<? echo(isset($out_bank_current_account)?$out_bank_current_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f039"><? echo(dictionary('Лицевой счет')) ?></label></dt>
	<dd class="input"><input id="f039" class="fullwidth" type="text" name="data[out_bank_personal_account]" value="<? echo(isset($out_bank_personal_account)?$out_bank_personal_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f040"><? echo(dictionary('Карточный счет')) ?></label></dt>
	<dd class="input"><input id="f040" class="fullwidth" type="text" name="data[out_bank_card_account]" value="<? echo(isset($out_bank_card_account)?$out_bank_card_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f041"><? echo(dictionary('IBAN Получателя')) ?></label></dt>
	<dd class="input"><input id="f041" class="fullwidth" type="text" name="data[out_bank_iban]" value="<? echo(isset($out_bank_iban)?$out_bank_iban:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f042"><? echo(dictionary('Наименование банка')) ?></label></dt>
	<dd class="input"><input id="f042" class="fullwidth" type="text" name="data[out_bank_name]" value="<? echo(isset($out_bank_name)?$out_bank_name:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f043"><? echo(dictionary('Страна банка')) ?></label></dt>
	<dd class="input"><input id="f043" class="fullwidth" type="text" name="data[out_bank_country]" value="<? echo(isset($out_bank_country)?$out_bank_country:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f044"><? echo(dictionary('Регион банка')) ?></label></dt>
	<dd class="input"><input id="f044" class="fullwidth" type="text" name="data[out_bank_region]" value="<? echo(isset($out_bank_region)?$out_bank_region:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f045"><? echo(dictionary('Город банка')) ?></label></dt>
	<dd class="input"><input id="f045" class="fullwidth" type="text" name="data[out_bank_town]" value="<? echo(isset($out_bank_town)?$out_bank_town:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f046"><? echo(dictionary('ИНН банка')) ?></label></dt>
	<dd class="input"><input id="f046" class="fullwidth" type="text" name="data[out_bank_inn]" value="<? echo(isset($out_bank_inn)?$out_bank_inn:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f047"><? echo(dictionary('БИК банка')) ?></label></dt>
	<dd class="input"><input id="f047" class="fullwidth" type="text" name="data[out_bank_bic]" value="<? echo(isset($out_bank_bic)?$out_bank_bic:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f048"><? echo(dictionary('Корреспондентский счет')) ?></label></dt>
	<dd class="input"><input id="f048" class="fullwidth" type="text" name="data[out_bank_corr_account]" value="<? echo(isset($out_bank_corr_account)?$out_bank_corr_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f049"><? echo(dictionary('Наименование банка-корреспондента')) ?></label></dt>
	<dd class="input"><input id="f049" class="fullwidth" type="text" name="data[out_bank_corr_bank_name]" value="<? echo(isset($out_bank_corr_bank_name)?$out_bank_corr_bank_name:'') ?>"/></dd>
</dl>

<br/>
<dl class="row">
	<dt class="label"><label for="f050"><? echo(dictionary('Местонахождение банка-корреспондента')) ?></label></dt>
	<dd class="input"><input id="f050" class="fullwidth" type="text" name="data[out_bank_corr_bank_location]" value="<? echo(isset($out_bank_corr_bank_location)?$out_bank_corr_bank_location:'') ?>"/></dd>
</dl>

<br/>
<dl class="row">
	<dt class="label"><label for="f051"><? echo(dictionary('SWIFT-код банка-корреспондента')) ?></label></dt>
	<dd class="input"><input id="f051" class="fullwidth" type="text" name="data[out_bank_corr_bank_swift]" value="<? echo(isset($out_bank_corr_bank_swift)?$out_bank_corr_bank_swift:'') ?>"/></dd>
</dl>

<br/>
<dl class="row">
	<dt class="label"><label for="f052"><? echo(dictionary('SWIFT-код банка Получателя')) ?></label></dt>
	<dd class="input"><input id="f052" class="fullwidth" type="text" name="data[out_bank_swift]" value="<? echo(isset($out_bank_swift)?$out_bank_swift:'') ?>"/></dd>
</dl>

<? } elseif ($step==4) { ?>

<h2><? echo(dictionary('Дополнительные сведения')) ?></h2>
<p class="description"><span class="required-sign">*</span>&nbsp;&mdash; <? echo(dictionary('поля, обязательные для заполнения')) ?>.</p>

<dl class="row wide">
	<dt class="label"><label for=""><? echo(dictionary('Являетесь&nbsp;ли Вы&nbsp;иностранным публичным должностным лицом, должностным лицом публичных международных организаций, его аффилированным лицом или действуете от&nbsp;его имени')) ?>?</label></dt>
</dl>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r07" name="data[public_face]" value="1"<? echo((isset($public_face)&&($public_face==1))?' checked':'') ?>/>
			<span class="label"><label for="r07"><? echo(dictionary('Да, являюсь')) ?></label></span>
		</div>
		<div class="selector">
			<input type="radio" id="r08" name="data[public_face]" value="0"<? echo((isset($public_face)&&($public_face==0))?' checked':'') ?>/>
			<span class="label"><label for="r08"><? echo(dictionary('Нет')) ?></label></span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f053"><? echo(dictionary('Государство')) ?></label></dt>
	<dd class="input"><input id="f053" class="fullwidth" type="text" name="data[public_country]" value="<? echo(isset($public_country)?$public_country:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f054"><? echo(dictionary('Организация')) ?></label></dt>
	<dd class="input"><input id="f054" class="fullwidth" type="text" name="data[public_organization]" value="<? echo(isset($public_organization)?$public_organization:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f055"><? echo(dictionary('Должность')) ?></label></dt>
	<dd class="input"><input id="f055" class="fullwidth" type="text" name="data[public_position]" value="<? echo(isset($public_position)?$public_position:'') ?>"/></dd>
</dl>

<dl class="row wide">
	<dt class="label"><label for=""><? echo(dictionary('Являетесь&nbsp;ли Вы&nbsp;лицом, замещающим (занимающим) государственные должности Российской Федерации, должности членов Совета директоров Центрального банка Российской Федерации, должности федеральной государственной службы, назначение на&nbsp;которые и&nbsp;освобождение от&nbsp;которых осуществляются Президентом Российской Федерации или Правительством Российской Федерации, должности в&nbsp;Центральном банке Российской Федерации, государственных корпорациях и&nbsp;иных организациях, созданных Российской Федерацией на&nbsp;основании федеральных законов, включенные в&nbsp;перечни должностей, определяемые Президентом Российской Федерации, его аффилированным лицом (супругом(ой), близким родственником, братом, сестрой, усыновителем, усыновленным) или действуете от&nbsp;его имени')) ?>?</label></dt>
</dl>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r09" name="data[official]" value="1"<? echo((isset($official)&&($official==1))?' checked':'') ?>/>
			<span class="label"><label for="r09"><? echo(dictionary('Да, являюсь')) ?></label></span>
		</div>
		<div class="selector">
			<input type="radio" id="r10" name="data[official]" value="0"<? echo((isset($official)&&($official==0))?' checked':'') ?>/>
			<span class="label"><label for="r10"><? echo(dictionary('Нет')) ?></label></span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f056"><? echo(dictionary('Организация')) ?></label></dt>
	<dd class="input"><input id="f056" class="fullwidth" type="text" name="data[official_organization]" value="<? echo(isset($official_organization)?$official_organization:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f057"><? echo(dictionary('Должность')) ?></label></dt>
	<dd class="input"><input id="f057" class="fullwidth" type="text" name="data[official_position]" value="<? echo(isset($official_position)?$official_position:'') ?>"/></dd>
</dl>

<? } ?>