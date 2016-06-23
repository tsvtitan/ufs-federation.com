<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">

<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script src="/js/jquery.validate.js" type="text/javascript"></script>
<script src="/js/jquery.mask.min.js" type="text/javascript"></script>

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
	
	$('#f005').mask("9 (999) 999-99-99");
	$('#f006').mask("9 (999) 999-99-99");
	$('#f007').mask("99.99.9999");
	$('#f012').mask("99.99.9999");
	$('#f010').mask("99 99 999999");
	$('#f013').mask("999-999");

	﻿$( "#f007" ).datepicker({
		yearRange: "-90:+0", 
		changeMonth: true,
		changeYear: true,
		showOn: "button",
		buttonImage: "/img/icon-calendar.png",
		buttonImageOnly: true,
		onClose: function() {
			$(this).valid();
		}
	});
	$( "#f012" ).datepicker({
		yearRange: "-90:+0", 
		changeMonth: true,
		changeYear: true,
		showOn: "button",
		buttonImage: "/img/icon-calendar.png",
		buttonImageOnly: true,
		onClose: function() {
			$(this).valid();
		}
	});
		
	$.validator.addMethod("australianDate",
		function(value, element) {
			return value.match(/^\d\d?\.\d\d?\.\d\d\d\d$/);
		},"Пожалуйста, введите дату в формате дд.мм.гггг."
	);
	$.validator.addMethod("phoneSA",
		function (phone_number, element) {
			//phone_number = phone_number.replace(/\s+/g, "");
			return this.optional(element) || phone_number.length > 9 && phone_number.match(/^((\d{1,3})[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/); //(0-?) = 0, ([0-9]\d{2}\) = 3 digits from 0-9 , d{4} 4 didgets, $ end of number
		}, "Пожалуйста, укажите ваш номер телефона"
	);
	$.validator.addMethod("passCode",
		function (passport_code, element) {
			//phone_number = phone_number.replace(/\s+/g, "");
			return this.optional(element) || passport_code.match(/^\d{3,3}\-\d{3,3}$/);
		}, "Пожалуйста, укажите код подразделения"
	);
	$( "#show-policy" ).tooltip();
	// masked input will be here
	

/* validation rules */
	$("#application_form_1").validate({
		/*
		highlight: function(element, errorClass) {
			$(element).fadeOut(function() {
				$(element).fadeIn();
			});
		},
		*/
		/*
		groups: {
			resident: "fname lname"
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "fname" || element.attr("name") == "lname" )
				error.insertAfter("#lastname");
			else
				error.insertAfter(element);
			},
			debug:true
		},
		*/
		errorPlacement: function(error, element) {
			error.appendTo( element.parents("dd") ); /*.next("div") */
		},
		// debug:true,
		rules: {
			"data[last_name]": "required",
			"data[first_name]": "required",
			"data[email]": {
				required: true,
				email: true
			},
			"data[phone]": {
				required: true /*,
				phoneSA: true */
			},
			"data[birth_date]": {
				required: true,
				australianDate: true
			},
			"data[inn]": {
				required: function(element) {
					return $('input[name="data[inn]"]', '#step1form').val() > 0;
				},
				minlength: 12,
				number: true
			},
			"data[citizen]": "required",
			"data[passport_number]": {
				required: true,
				minlength: 12
			},
			"data[passport_authority]": "required",
			"data[passport_date]": {
				required: true,
				australianDate: true
			},
			"data[passport_code]": {
				required: true,
				passCode: true
			},
			"data[agree]": "required"
		},
		messages: {
			"data[last_name]": "<? echo(dictionary('Пожалуйста, укажите вашу фамилию')) ?>",
			"data[first_name]": "<? echo(dictionary('Пожалуйста, укажите ваше имя')) ?>",
			"data[email]": {
				required: "<? echo(dictionary('Пожалуйста, укажите действующий email')) ?>",
				email: "<? echo(dictionary('Пожалуйста, укажите действующий email')) ?>"
			},
			"data[phone]": {
				required: "<? echo(dictionary('Пожалуйста, укажите ваш номер телефона')) ?>" /*,
				phoneSA: "Пожалуйста, введите номер как в примере" */
			},
			"data[birth_date]": {
				required: "<? echo(dictionary('Пожалуйста, укажите дату вашего рождения')) ?>",
				australianDate: "<? echo(dictionary('Пожалуйста, введите дату как в примере')) ?>"
			},
			"data[inn]": {
				required: "<? echo(dictionary('Пожалуйста, укажите ваш ИНН, если есть')) ?>",
				number: "<? echo(dictionary('Пожалуйста, введите корректный ИНН')) ?>",
				minlength: "<? echo(dictionary('Пожалуйста, введите корректный ИНН')) ?>"
			},
			"data[citizen]": "<? echo(dictionary('Пожалуйста, укажите являетесь ли вы резидентом РФ')) ?>",
			"data[passport_number]": {
				required: "<? echo(dictionary('Пожалуйста, укажите серию и номера паспорта')) ?>",
				number: "<? echo(dictionary('Пожалуйста, введите корректный номер')) ?>",
				minlength: "<? echo(dictionary('Пожалуйста, введите корректный номер')) ?>"
			},
			"data[passport_authority]": "<? echo(dictionary('Пожалуйста, укажите кем выдан паспорт')) ?>",
			"data[passport_date]": {
				required: "<? echo(dictionary('Пожалуйста, укажите дату вашего рождения')) ?>",
				australianDate: "<? echo(dictionary('Пожалуйста, введите дату как в примере')) ?>"
			},
			"data[passport_code]": {
				required: "<? echo(dictionary('Пожалуйста, укажите код подразделения')) ?>",
				passCode: "<? echo(dictionary('Пожалуйста, укажите корректный код подразделения')) ?>"
			},
			"data[agree]": "<? echo(dictionary('Для продолжения вам необходимо дать согласие на обработку персональных данных')) ?>"
		}
	});
	$("#application_form_2").validate({
		errorPlacement: function(error, element) {
			error.appendTo( element.parents("dd") ); /*.next("div") */
		},
		rules: {
			"data[residence_zipcode]": {
				required: true,
				number: true,
				minlength: 6
			},
			"data[residence_region_id]": "required",
			"data[residence_locality]": "required",
			"data[residence_street]": "required",
			"data[residence_house]": "required",
			"data[residence_flat]": "required"
		},
		messages: {
			"data[residence_zipcode]": {
				required: "Пожалуйста, укажите индекс",
				number: "Пожалуйста, введите только цифры без пробелов",
				minlength: "Индекс должен содержать минимум 6 цифр"
			},
			"data[residence_region_id]": "Пожалуйста, укажите регион",
			"data[residence_locality]": "Пожалуйста, укажите город или населенный пункт",
			"data[residence_street]": "Пожалуйста, укажите улицу",
			"data[residence_house]": "Пожалуйста, укажите номер дома",
			"data[residence_flat]": "Пожалуйста, укажите номер квартиры"
		}
	});
	$("#application_form_3").validate({
		errorPlacement: function(error, element) {
			error.appendTo( element.parents("dd") ); /*.next("div") */
		},
		rules: {
			"data[bank_recipient]": "required",
			"data[bank_current_account]": { // Расчетный счет
				required: true,
				number: true,
				minlength: 20,
				maxlength: 20
			},
			"data[bank_personal_account]": { // Лицевой счет
				required: function(element) {
					return $('input[name="data[bank_personal_account]"]', '#application_form_3').val() > 0;
				},
				number: true,
				minlength: 20,
				maxlength: 20
			},
			"data[bank_name]": "required",
			"data[bank_region_id]": "required",
			"data[bank_town]": "required",
			/* "data[bank_inn]": { 
				required: false,
				number: true,
				minlength: 10,
				maxlength: 10
			}, */
			"data[bank_bic]": { // БИК банка
				required: true,
				number: true,
				minlength: 9,
				maxlength: 9,
				success: function(element){
				  var url='<? echo($this->phpself) ?>bank.html?bic='+element.value;
					$.getJSON(url,{
						format: 'json'
					})
					.done(function(data) {
						if (data) {
							$('input[name="data[bank_name]"]')[0].value=data['namep'];
							var rid=data['region_id'];
							if (rid) {
                var o=$('select[name="data[bank_region_id]"]');
                var n='option[value="'+rid+'"]';
                o.children(n).attr('selected',true);
							} 
							var s='';
							if (data['ind']) { s=s+data['ind']+', '; }
							if (data['nnp']) { s=s+data['nnp']; }
							if (data['adr']) { s=s+', '+data['adr']; }
							$('input[name="data[bank_town]"]')[0].value=s;
							$('input[name="data[bank_corr_account]"]')[0].value=data['ksnp'];
							
							$('input[name="data[bank_name]"]').valid();
							$('select[name="data[bank_region_id]"]').valid();
							$('input[name="data[bank_town]"]').valid();
							$('input[name="data[bank_corr_account]"]').valid();
						}
					})
				}
			},
			"data[bank_corr_account]": { // Коррсчет
				required: true,
				number: true,
				minlength: 20,
				maxlength: 20
			}
		},
	messages: {
			"data[bank_recipient]": "Пожалуйста, укажите ФИО получателя",
			"data[bank_current_account]": { // Расчетный счет
				required: "Пожалуйста, укажите номер расчетного счета",
				number: "Пожалуйста, введите только цифры без пробелов",
				minlength: "Пожалуйста, укажите корректный номер",
				maxlength: "Пожалуйста, укажите корректный номер"
			},
			"data[bank_personal_account]": { // Лицевой счет
				required: "Пожалуйста, укажите номер лицевого счета, если есть",
				number: "Пожалуйста, введите только цифры без пробелов",
				minlength: "Пожалуйста, укажите корректный номер",
				maxlength: "Пожалуйста, укажите корректный номер"
			},
			"data[bank_name]": "Пожалуйста, укажите наименование банка",
			"data[bank_region_id]": "Пожалуйста, укажите регион банка",
			"data[bank_town]": "Пожалуйста, укажите адрес банка",
			/* "data[bank_inn]": {
				required: "Пожалуйста, укажите ИНН банка",
				number: "Пожалуйста, введите только цифры без пробелов",
				minlength: "Пожалуйста, укажите корректный номер",
				maxlength: "Пожалуйста, укажите корректный номер"
			}, */
			"data[bank_bic]": { // БИК банка
				required: "Пожалуйста, укажите БИК банка",
				number: "Пожалуйста, введите только цифры без пробелов",
				minlength: "Пожалуйста, укажите корректный номер",
				maxlength: "Пожалуйста, укажите корректный номер"
			},
			"data[bank_corr_account]": { // Коррсчет
				required: "Пожалуйста, укажите номер корресподентского счета",
				number: "Пожалуйста, введите только цифры без пробелов",
				minlength: "Пожалуйста, укажите корректный номер",
				maxlength: "Пожалуйста, укажите корректный номер"
			}
		}
});
	$("#application_form_4").validate({
		errorPlacement: function(error, element) {
			error.appendTo( element.parents("dd") ); /*.next("div") */
		},
		rules: {
			"data[public_face]": "required",
			"data[public_country]": {
				required: function(element) {
					return $('input[name="data[public_face]"]:checked', '#application_form_4').val() == 1;
				}
			},
			"data[public_organization]": {
				required: function(element) {
					return $('input[name="data[public_face]"]:checked', '#application_form_4').val() == 1;
				}
			},
			"data[public_position]": {
				required: function(element) {
					return $('input[name="data[public_face]"]:checked', '#application_form_4').val() == 1;
				}
			},
			"data[official]": "required",
			"data[official_organization]": {
				required: function(element) {
					return $('input[name="data[official]"]:checked', '#application_form_4').val() == 1;
				}
			},
			"data[official_position]": {
				required: function(element) {
					return $('input[name="data[official]"]:checked', '#application_form_4').val() == 1;
				}
			},
			"data[laundering]": "required",
			"data[keyword]": {
				required: true,
				minlength: 6
			}
		},
		messages: {
			"data[public_face]": "Пожалуйста, выберите один из вариантов",
			"data[public_country]": "Пожалуйста, укажите государство",
			"data[public_organization]": "Пожалуйста, укажите организацию",
			"data[public_position]": "Пожалуйста, укажите должность",
			"data[official]": "Пожалуйста, выберите один из вариантов",
			"data[official_organization]": "Пожалуйста, укажите организацию",
			"data[official_position]": "Пожалуйста, укажите должность",
			"data[laundering]": "Пожалуйста, выберите один из вариантов",
			"data[keyword]": {
				required: "Пожалуйста, укажите кодовое слово от 6 до 10 символов",
				minlength: "Пожалуйста, укажите кодовое слово от 6 до 10 символов"
			}
		}
	});



});

function application_form_post(obj) {
	
}

function application_form_back(obj) {

	fm = document.getElementById('application_form_<? echo($step) ?>');
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
	
	.row { clear: both; padding: 0; margin: 0; min-height: 1em; //height: 1em; position: relative; padding-bottom: 0.4em; }
	
	.label { display: block; width: 13em; margin: 0; padding: 0.2em 0 0 1em; float: left; }
	.label a { cursor: pointer; }
	.for_select_checkbox, .for_select_radio, .for_textarea { padding-top: 0; }
	.button { margin-top: 0.2em; margin-bottom: 1em; }
	
	.input { margin: 0 0 0 14em; padding: 0; //margin-left: 0; //padding-left: 1em; //float: left; }
	
	.description { display: block; font-style: normal; font-size: 0.76em; }
	
	.selector .label .description { margin-bottom: 0.5em; }
	.input .description { margin: 0.5em 0 0 !important; color: #777; }
	
	.row .row { margin-left: 20px; padding: 0; clear: none; }
	.row .row .input { margin: 0px; padding: 0; }
	
	.input { padding-bottom: 0.5em; }
	.for-previous { position: relative; top: -0.5em; }
	
	.selector { //clear: left; //height: 1px; margin-bottom: 0 ! important; }
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
	
	form fieldset { margin: 2em 0 2em 0; //margin: 0 0 1em 0; padding: 1em 0; //padding-bottom: 0; clear: left; border-left: 0 hidden; border-right: 0 hidden; border-bottom: 0 hidden; }
	form fieldset legend { margin: 0; padding: 0 0.5em 0 0; font-size: 0.76em; font-weight: bold; //display: block; //float: left; //margin-bottom: 1em; }
	* html form fieldset legend { margin-left: -7px; margin-right: -7px; }
	form fieldset fieldset { border: 0 hidden; margin: 0; padding-bottom: 0; padding-top: 1em; //padding-top: 0; }
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
		/* width: 70%; */
		width: 343px;
	}
	.miniwidth {
		width: 5.5em;
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
  label.error {
	padding: 0.3em 0 0;
	display: block;
	font-size: 85%;
	/*
	background: none repeat scroll 0 0 #ffdddd;
	border: 1px solid #ffffff;
	box-shadow: 0 1px 3px #ccbbbb;
	margin-left: 354px;
	margin-top: -27px;
	min-width: 246px;
	padding: 0.4em 0.5em;
	position: absolute;
	text-shadow: 0 1px 0 #ffeeee;
	border-radius: 3px;
	*/
  }
  .selector label.error {
	margin-top: 0;
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
  .ui-datepicker-trigger {
  	  display: inline-block;
  	  margin-left: 0.7em;
  	  cursor: pointer;
  }
  span.plus-indent {
  	display: inline-block;
  	margin-right: -12px;
  	zoom: 1;
  	position: relative;
  	width: 6px;
  	padding-left: 6px;
  	color: #777;
  }
  input.plus-indent-input {
  	  text-indent: 12px;
  }
 
</style>

<? if (($step>=1) && ($step<=4)) { ?>
<form method="post" id="application_form_<? echo($step) ?>">
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
<? } ?>
 
<? if ($step==1) { ?>
<h2><? echo(dictionary('Персональные данные')) ?></h2>

<? if (isset($message)) { ?>
<div class="msgbox">
<p><span><? echo($message) ?></span></p><br/>
</div> 
<? } ?>

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
	<dd class="input"><span class="plus-indent">+</span><input class="plus-indent-input" id="f005" type="text" name="data[phone]" value="<? echo(isset($phone)?$phone:'') ?>"/><p class="description">Например, +7 (495) 000-00-00</p></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f006"><? echo(dictionary('Факс')) ?></label></dt>
	<dd class="input"><span class="plus-indent">+</span><input class="plus-indent-input" id="f006" type="text" name="data[fax]" value="<? echo(isset($fax)?$fax:'+') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f007"><? echo(dictionary('Дата рождения')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f007" type="text" name="data[birth_date]" value="<? echo(isset($birth_date)?$birth_date:'') ?>"/><p class="description">Например, 31.12.2012</p></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f008"><? echo(dictionary('Место рождения')) ?></label></dt>
	<dd class="input"><input id="f008" class="fullwidth" type="text" name="data[birth_place]" value="<? echo(isset($birth_place)?$birth_place:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f009"><? echo(dictionary('ИНН')) ?></label></dt>
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
	<dt class="label"><label for="f010"><? echo(dictionary('Серия и номер')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f010" type="text" name="data[passport_number]" value="<? echo(isset($passport_number)?$passport_number:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f011"><? echo(dictionary('Кем выдан')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f011" class="fullwidth" type="text" name="data[passport_authority]" value="<? echo(isset($passport_authority)?$passport_authority:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f012"><? echo(dictionary('Дата выдачи')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f012" type="text" name="data[passport_date]" value="<? echo(isset($passport_date)?$passport_date:'') ?>"/><p class="description">Например, 31.12.2012</p></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f013"><? echo(dictionary('Код подразделения')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f013" class="miniwidth" type="text" name="data[passport_code]" value="<? echo(isset($passport_code)?$passport_code:'') ?>"/><p class="description">Например, 555-777</p></dd>
</dl>
<br class="clear"/>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="checkbox" id="chk07" name="data[agree]" value="1"<? echo((isset($agree)&&($agree==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk07"><? echo(dictionary('Соглашаюсь на')) ?></label> <a id="show-policy" title="Настоящим, нажав кнопку «Продолжить», я выражаю и подтверждаю свое согласие на обработку, как это определено в ФЗ «О персональных данных» N 152-ФЗ от 27.07.2006 года, моих персональных данных любым способом, в том числе: хранение, запись на электронные носители, сбор, систематизация, уточнение (обновление, изменение), использование, обезличивание, блокирование, уничтожение. Настоящее согласие предоставляется компании ООО «ИК «Ю Эф Эс Финанс» (адрес места нахождения: Российская Федерация, 105082, г. Москва, Балакиревский пер., д.19, стр.1, оф.206) в целях заключения договора по инициативе субъекта персональных данных. Настоящее согласие выдано на неопределенный срок."><? echo(dictionary('обработку персональных данных')) ?></a>.<label><sup>*</sup></label>
			</span>
		</div>
	</dd>
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
	<dt class="label"><label for="f015"><? echo(dictionary('Регион')) ?><sup>*</sup></label></dt>
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
	<dt class="label"><label for="f016"><? echo(dictionary('Населенный пункт')) ?><sup>*</sup></label></dt>
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
	<dt class="label"><label for="f022"><? echo(dictionary('Регион')) ?></label></dt>
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
	<dt class="label"><label for="f035"><? echo(dictionary('БИК банка')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f035" class="fullwidth" type="text" name="data[bank_bic]" value="<? echo(isset($bank_bic)?$bank_bic:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f030"><? echo(dictionary('Лицевой счет')) ?></label></dt>
	<dd class="input"><input id="f030" class="fullwidth" type="text" name="data[bank_personal_account]" value="<? echo(isset($bank_personal_account)?$bank_personal_account:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f031"><? echo(dictionary('Наименование банка')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f031" class="fullwidth" type="text" name="data[bank_name]" value="<? echo(isset($bank_name)?$bank_name:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f032"><? echo(dictionary('Регион банка')) ?><sup>*</sup></label></dt>
	<dd class="input">
		<select id="f032" class="fullwidth" name="data[bank_region_id]">
		  <option value=""<? echo(!isset($bank_region_id)?' selected':'') ?>><? echo(dictionary('выберите')) ?></option>
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
	<dt class="label"><label for="f033"><? echo(dictionary('Адрес банка')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f033" class="fullwidth" type="text" name="data[bank_town]" value="<? echo(isset($bank_town)?$bank_town:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f034"><? echo(dictionary('ИНН банка')) ?></label></dt>
	<dd class="input"><input id="f034" class="fullwidth" type="text" name="data[bank_inn]" value="<? echo(isset($bank_inn)?$bank_inn:'') ?>"/></dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f036"><? echo(dictionary('Корреспондентский счет')) ?><sup>*</sup></label></dt>
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
	<dt class="label"><label for="f045"><? echo(dictionary('Адрес банка')) ?></label></dt>
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

<dl class="row wide">
	<dt class="label"><label for=""><? echo(dictionary('Сведения о&nbsp;счетах в&nbsp;банках, зарегистрированных в&nbsp;государстве (на&nbsp;территории), которое (которая) не&nbsp;участвует в&nbsp;международном сотрудничестве в&nbsp;сфере противодействия легализации (отмыванию) доходов, полученных преступным путем, и&nbsp;финансированию терроризма')) ?>?</label></dt>
</dl>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r11" name="data[laundering]" value="1"<? echo((isset($laundering)&&($laundering==1))?' checked':'') ?>/>
			<span class="label"><label for="r11"><? echo(dictionary('Присутствуют')) ?></label></span>
		</div>
		<div class="selector">
			<input type="radio" id="r12" name="data[laundering]" value="0"<? echo((isset($laundering)&&($laundering==0))?' checked':'') ?>/>
			<span class="label"><label for="r12"><? echo(dictionary('Отсутствуют')) ?></label></span>
		</div>
	</dd>
</dl>

<h3><? echo(dictionary('Условия обслуживания')) ?></h3>

<dl class="row">
	<dt class="label"><label for="f058"><? echo(dictionary('Кодовое слово при подаче брокеру устных поручений на&nbsp;сделки')) ?><sup>*</sup></label></dt>
	<dd class="input"><input id="f058" class="fullwidth" type="text" name="data[keyword]" value="<? echo(isset($keyword)?$keyword:'') ?>"/><p class="description"><? echo(dictionary('Придумайте комбинацию от&nbsp;6&nbsp;до&nbsp;10&nbsp;символов.<br/>Допускаются русские и&nbsp;латинские буквы, а&nbsp;так&nbsp;же цифры')) ?>.</p></dd>
</dl>

<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="checkbox" id="chk01" name="data[forts]" value="1"<? echo((isset($forts)&&($forts==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk01"><? echo(dictionary('Регистрация в&nbsp;торговой системе FORTS')) ?></label>
			</span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="checkbox" id="chk02" name="data[special_account]" value="1"<? echo((isset($special_account)&&($special_account==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk02"><? echo(dictionary('Открытие и&nbsp;ведение отдельного специального брокерского счёта')) ?></label><p class="description">(<? echo(dictionary('Взимается комиссия согласно')) ?> <a href="http://www.ufs-finance.com/uploads/pr4.pdf" target="_blank"><? echo(dictionary('Тарифам Брокера')) ?></a>)</p>
			</span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><? echo(dictionary('Предоставление Интернет-трейденга')) ?></dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r13" name="data[internet_trading]" value="1"<? echo((isset($internet_trading)&&($internet_trading==1))?' checked':'') ?>/>
			<span class="label">
				<label for="r13"><? echo(dictionary('Да')) ?></label>
			</span>
		</div>
		<div class="selector">
			<input type="radio" id="r14" name="data[internet_trading]" value="0"<? echo((isset($internet_trading)&&($internet_trading==0))?' checked':'') ?>/>
			<span class="label">
				<label for="r14"><? echo(dictionary('Нет')) ?></label>
			</span>
		</div>
	</dd>
</dl>

<dl class="row">
	<dt class="label"><label for="f059"><? echo(dictionary('Количество терминалов')) ?></label></dt>
	<dd class="input"><input id="f059" class="miniwidth" type="text" name="data[terminal_count]" value="<? echo(isset($terminal_count)?$terminal_count:'1') ?>"/></dd>
</dl>

<h3><? echo(dictionary('Способы подачи клиентом поручений и&nbsp;получение отчетной документации')) ?></h3>

<dl class="row wide">
	<dt class="label"><? echo(dictionary('Подача поручений и&nbsp;получение отчетов по&nbsp;брокерскому договору')) ?></dt>
</dl>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="checkbox" id="chk03" name="data[orders_in_office]" value="1"<? echo((isset($orders_in_office)&&($orders_in_office==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk03"><? echo(dictionary('В офисе брокера')) ?></label>
			</span>
		</div>
		<div class="selector">
			<input type="checkbox" id="chk04" name="data[orders_by_mail]" value="1"<? echo((isset($orders_by_mail)&&($orders_by_mail==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk04"><? echo(dictionary('По почте')) ?></label>
			</span>
		</div>
		<div class="selector">
			<input type="checkbox" id="chk05" name="data[orders_by_phone]" value="1"<? echo((isset($orders_by_phone)&&($orders_by_phone==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk05"><? echo(dictionary('По телефону')) ?></label>
			</span>
		</div>
		<div class="selector">
			<input type="checkbox" id="chk06" name="data[orders_by_email]" value="1"<? echo((isset($orders_by_email)&&($orders_by_email==1))?' checked':'') ?>/>
			<span class="label">
				<label for="chk06"><? echo(dictionary('По email')) ?></label>
			</span>
		</div>
	</dd>
</dl>

<dl class="row wide">
	<dt class="label"><? echo(dictionary('Получение оригиналов отчетов по&nbsp;депозитарному договору')) ?></dt>
</dl>
<dl class="row">
	<dt class="label">&nbsp;</dt>
	<dd class="input">
		<div class="selector">
			<input type="radio" id="r15" name="data[delivery]" value="1"<? echo((isset($delivery)&&($delivery==1))?' checked':'') ?>/>
			<span class="label">
				<label for="r15"><? echo(dictionary('Курьером')) ?></label>
			</span>
		</div>
		<div class="selector">
			<input type="radio" id="r16" name="data[delivery]" value="2"<? echo((isset($delivery)&&($delivery==2))?' checked':'') ?>/>
			<span class="label">
				<label for="r16"><? echo(dictionary('Через уполномоченного представителя')) ?></label>
			</span>
		</div>
		<div class="selector">
			<input type="radio" id="r17" name="data[delivery]" value="3"<? echo((isset($delivery)&&($delivery==3))?' checked':'') ?>/>
			<span class="label">
				<label for="r17"><? echo(dictionary('Заказным письмом')) ?></label>
			</span>
		</div>
	</dd>
</dl>

<? } elseif ($step==5) { ?>

<h2><? echo(dictionary('Спасибо')) ?>!</h2>
<p><? echo(dictionary('В&nbsp;ближайшее время, на&nbsp;основе заполненной Вами анкеты, будут подготовлены пакеты документов на&nbsp;брокерское и&nbsp;депозитарное обслуживанию в&nbsp;ООО &laquo;ИК&nbsp;&laquo;Ю&nbsp;Эф&nbsp;Эс&nbsp;Финанс&raquo;')) ?>.</p>
<p><? echo(dictionary('Клиентский менеджер ООО &laquo;ИК&nbsp;&laquo;Ю&nbsp;Эф&nbsp;Эс&nbsp;Финанс&raquo; свяжется с&nbsp;Вами для уточнения удобного времени для подписания готовых документов')) ?>.</p>


<p><? echo(dictionary('Вы&nbsp;так&nbsp;же можете подписаться на&nbsp;')) ?><a href="/subscribe.html"><? echo(dictionary('рассылку аналитических материалов')) ?></a>.</p>
                                                                                                                            
<? } ?>

<? if (($step>=1) && ($step<=4)) { ?>
<br class="clear"/>
<dl class="row">
  <? if ($step==1) { ?>
  <dt class="label">&nbsp;</dt>
	<? } else { ?>
	<dt class="label"><a onclick="application_form_back(this); return false;">‹&nbsp<? echo(dictionary('Вернуться')) ?></a></dt>
	<? } ?>
	<dd class="input">
	  <input type="hidden" name="data[application_form_id]" value="<? echo($application_form_id) ?>"/>
	  <input type="hidden" name="back" value="false"/>
	  <input type="hidden" name="step" value="<? echo($step) ?>"/>
	  <input class="flat-btn" type="submit" name="next" value="<? echo(($step!=4)?dictionary('Продолжить'):dictionary('Отправить анкету')) ?>"/>
	</dd>
</dl>
</form>
<? } ?>