<? if(!$finished) { ?>
<script src="/js/jquery.validate.js" type="text/javascript"></script>
<script src="/js/jquery.mask.min.js" type="text/javascript"></script>
<script>

$(document).ready(function(){
	$('select').change(function(){
		var select_id = $(this).attr('id');
		//alert(select_id);
		//if ($(this).is(':selected')){
		
		if ($(this).val() == 'other'){
			//alert('00');
			//var trade_idea_name = $('select').attr('name');
			//var select_id = $('select').attr('id');
			//alert(trade_idea_name);
			//$('#addinfo').show('fast');
			//alert('00');
			$('#inp_'+select_id).show(250);
			$('#inp_'+select_id).select();
		} else {
			$('#inp_'+select_id).hide(250);
			$('#inp_'+select_id).val('');
		}
		
	});
	
  $('#phone_num').mask("9 (999) 999-99-99");
  $('#r002').change(function(){
    if ($(this).is(':checked')){
      $('#addinfo').show('fast');
    }
  });

  $('#r001').change(function(){
    if ($(this).is(':checked')){
      $('#addinfo').hide('fast');
    }
  });
  
  $('#buyOptionsRadio4').change(function(){
    $('#buyOptionsHidden').removeClass('hidden').focus();
  });
  $('#sellOptionsRadio4').change(function(){
    $('#sellOptionsHidden').removeClass('hidden').focus();
  });
  $('#buyOptionsRadio1,#buyOptionsRadio2,#buyOptionsRadio3').change(function(){
    $('#buyOptionsHidden').addClass('hidden').focus();
  });
  $('#sellOptionsRadio1,#sellOptionsRadio2,#sellOptionsRadio3').change(function(){
    $('#sellOptionsHidden').addClass('hidden').focus();
  });
  
  $('#ufsclientoption2').change(function(){
    $('#notclient').slideDown(150);
    $('#brokername').focus();
  });
  $('#ufsclientoption1').change(function(){
    $('#notclient').slideUp(150);
  });

  $('.remove_item').click(function(){
    n = $(this).parent().parent().parent().find('tr').length;
    if (n<=2){
      //$('.errormsg').show();
      alert('<? echo(dictionary("Нельзя удалить последнюю позицию")) ?>.');
    } else {
      if (confirm('Удалить?')) {
        $(this).parent().parent().remove();
      }
    }
    //alert(r);
    //$(this).parent().parent().remove();
    return false;
  });

  $("#form_order_trade_ideas").validate({
    errorPlacement: function(error, element) {
      error.appendTo( element.parents("dd") ); /*.next("div") */
    },
    rules: {
      "data[last_name]": "required",
      "data[first_name]": "required",
      "data[email]": {
        required: true,
        email: true
      },
      "data[phone]": {
        required: true
      },
      "data[is_client]": "required",
      "data[broker]": {
        required: function(element) {
          return $('input[name="data[is_client]"]', '#form_order_trade_ideas').val() > 0;
        }
      },
      /*"data[leveridge]": {
        required: function(element) {
          return $('input[name="data[is_client]"]', '#form_order_trade_ideas').val() > 0;
        }
      },
      "data[leveridge_cond]": {
        required: function(element) {
          return $('input[name="data[is_client]"]', '#form_order_trade_ideas').val() > 0;
        }
      }
      */
    },
    messages: {
      "data[last_name]": "Пожалуйста, укажите вашу фамилию",
      "data[first_name]": "Пожалуйста, укажите ваше имя",
      "data[email]": {
        required: "Пожалуйста, укажите действующий email",
        email: "Пожалуйста, укажите действующий email"
      },
      "data[phone]": {
        required: "Пожалуйста, укажите ваш номер телефона"
      },
      "data[is_client]": "Пожалуйста, укажите являетесь ли вы клиентом UFS IC",
      "data[broker]": "Пожалуйста, укажите, клиентом какого брокера вы являетесь",
      "data[leveridge]": "Пожалуйста, выберите один из вариантов",
      "data[leveridge_cond]": "Пожалуйста, выберите один из вариантов"
    }
  });
  
});

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
  
  #addinfo {
    display:none;
  }
  .remove_item {
    display: inline-block;
    background: url(/img/close-sprite.gif) no-repeat 50% 0%;
    width: 20px;
    height: 20px;
    margin: auto;
  }
  .remove_item:hover, .remove_item:active {
    background-position: 50% -48px;
  }
  .errormsg, .ti_inp {
		display: none;
	}
	.ti_inp {
		margin-right: 4px;
	}
	.nowrap {
		white-space: nowrap;
	}
	table th {
		text-align: left;
		/* border-bottom: 1px solid #ccc; */
		vertical-align: top;
		background-color: #f0f0f0;
	}
</style>

<style>


table.trade-ideas,
table.trade-ideas thead.tableFloatingHeaderSticky {
  border: 1px solid #fff !important;
  border-collapse: separate;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -webkit-border-radius: 4px;
 -moz-border-radius: 4px;
  border-radius: 4px;
}
table.trade-ideas,
table.trade-ideas th,
table.trade-ideas td,
.buttonGrad {
  /* font-family: open sans; */
}
table.trade-ideas,
table.trade-ideas th,
table.trade-ideas td {
  background-color: #fffefa;
  border: 0;
  font-size: 11px !important; /* 95%; */
  font-weight: normal;
}
table.trade-ideas th,
table.trade-ideas td {
  text-align: left;
  vertical-align: middle;
  border-left: 0;
  border-right: 0;
  padding: 0.5em 0.75em;
  /* padding-left: 0.75em;
  padding-right: 0.75em; */
}
table.trade-ideas td.sub-header {
  border-left: 2px solid #fff;
  /* font-weight: bold; */
  /* font-style: italic; */
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  /* color: rgba(0,0,0,1); */
}
table.trade-ideas th {
  background-color: #e0e6e7;
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  text-align: center;
  font-weight: bold;
}
table.trade-ideas th.header {
  background-color: #004577; /* 26C196; #009abf; #004577; */
  /* background: #009abf;
  background: -moz-linear-gradient(top,  #009abf 0%, #0085a3 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#009abf), color-stop(100%,#0085a3));
  background: -webkit-linear-gradient(top,  #009abf 0%,#0085a3 100%);
  background: -o-linear-gradient(top,  #009abf 0%,#0085a3 100%);
  background: -ms-linear-gradient(top,  #009abf 0%,#0085a3 100%);
  background: linear-gradient(to bottom,  #009abf 0%,#0085a3 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#009abf', endColorstr='#0085a3',GradientType=0 ); */
  color: #fff;
  text-shadow: none; /* 0px 0px 6px #006680; */
  text-align: left;
  font-weight: normal;
}
table.trade-ideas-order th.header {
  padding: 0;
  border-top: 1px solid #fff;
}
/* table.trade-ideas-order td.sub-header {
  text-align: center;
} */
table.trade-ideas th.header .date,
table.trade-ideas th.header .target {
  float: right;
  overflow: hidden;
  margin-left: 3em;
}
span.date i,
span.target i {
  width: 16px;
  height: 16px;
  display: inline-block;
  background: transparent url(/img/sprite-ideas.png) no-repeat 0 0;
  margin-right: 0.5em;
  vertical-align: text-bottom; /* top; */
  margin-top: 0.05em;
}
span.date i {
  background-position: -16px 0;
}
table.trade-ideas th.header .vs {
  font-weight: bold;
  /* font-style: italic; */
  
  background-color: #fff;
  letter-spacing: -0.05em;
  display: inline-block;
  width: 14px;
  height: 14px;

 -webkit-border-radius: 10px;
 -moz-border-radius: 10px;
  border-radius: 10px;
  
  padding: 2px;
  margin: 0 0.5em;
  color: #004577; /* 009abf; */
  font-size: 10px;
  line-height: 14px;
  text-shadow: none;
  font-weight: bold;
  text-align: center;
}
table.trade-ideas thead tr:first-child th:first-child {
 -webkit-border-radius: 4px 0 0 0;
 -moz-border-radius: 4px 0 0 0;
  border-radius: 4px 0 0 0;
}
table.trade-ideas thead tr:first-child th:last-child {
 -webkit-border-radius: 0 4px 0 0;
 -moz-border-radius: 0 4px 0 0 ;
  border-radius: 0 4px 0 0;
}
table.trade-ideas tbody tr.odd:last-child td:first-child {
 -webkit-border-radius: 0 0 0 4px;
 -moz-border-radius: 0 0 0 4px;
  border-radius: 0 0 0 4px;
  /* background-color: red !important; */
}
table.trade-ideas tbody td.comment:last-child {
 -webkit-border-radius: 0 0 4px 0;
 -moz-border-radius: 0 0 4px 0;
  border-radius: 0 0 4px 0;
}
table.trade-ideas thead.tableFloatingHeaderSticky,
table.trade-ideas thead.tableFloatingHeaderSticky tr th {
 -webkit-border-radius: 0 0 0 0 !important;
 -moz-border-radius: 0 0 0 0 !important;
  border-radius: 0 0 0 0 !important;
  background-color: rgba(224, 230, 231,0.8) !important;
}
table.trade-ideas thead.tableFloatingHeaderSticky {
  border: 1px solid #fff !important;
}
table.trade-ideas thead.tableFloatingHeaderSticky {
 -moz-box-sizing: content-box;
 -webkit-box-sizing: content-box;
  box-sizing: content-box;
  
  border-top-width: 0 !important;
  border-bottom-width: 0 !important;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.21);
 -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.21);
 -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.21);
}
table.trade-ideas td.sell,
table.trade-ideas td.buy {
  color: #fff;
  text-transform: uppercase;
  text-align: center;
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  font-weight: bold;
}
table.trade-ideas-order td.sell,
table.trade-ideas-order td.buy {
  text-align: left;
}
table.trade-ideas td.buy,
ul.list-items li.list-items-buy,
ul.list-items li.list-items-buy h4 {
  background-color: #ebfae8; /* #3daf2c; */
  color: #318c23;
  /* border-bottom: 1px solid #c4d7c1;
  border-top: 1px solid #f3faf2; */
}
table.trade-ideas td.sell,
ul.list-items li.list-items-sell,
ul.list-items li.list-items-sell h4 {
  background-color: #fff3f2; /* #e1261c; */
  color: #e1261c;
  /* border-bottom: 1px solid #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
/* ul.list-items-buy li:first-child {
  border-bottom: 1px solid #318c23;
}
ul.list-items-sell li:first-child {
  border-bottom: 1px solid #e1261c;
} */
ul.list-items li,
ul.list-items li {
  font-size: inherit;
  color: inherit !important;
}
ul.list-items h4 {
  margin: 0 0 0.5em 0;
  font-size: inherit;
  font-weight: bold;
}

ul.list-items li input[type=radio],
ul.list-items-buy li input[type=radio],
ul.list-items-sell li input[type=radio],
ul.list-items li input[type=checkbox],
ul.list-items-buy li input[type=checkbox],
ul.list-items-sell li input[type=checkbox] {
  margin-top: 0.34em;
}
ul.list-items li.list-items-buy,
ul.list-items li.list-items-sell {
 -webkit-border-radius: 0;
 -moz-border-radius: 0;
  border-radius: 0;
  margin: 0 0 2px 0;
}
ul.list-items-header li:first-child {
 -webkit-border-radius: 4px 4px 0 0;
 -moz-border-radius: 4px 4px 0 0;
  border-radius: 4px 4px 0 0;
}
ul.list-items li.list-items-header  {
  margin-bottom: 0;
  margin-top: 2px;

}
/*
ul.list-items-header li:last-child {
  margin-top: 2px;
 -webkit-border-radius: 0 0 4px 4px;
 -moz-border-radius: 0 0 4px 4px;
  border-radius: 0 0 4px 4px;
}
*/
table.trade-ideas tr.buy td a,
table.trade-ideas tr.sell td a {
  font-weight: bold;
}
table.trade-ideas tr.buy.odd td {
  background-color: #dbf0d8; /* #3daf2c; */
}
table.trade-ideas tr.sell.odd td {
  background-color: #fae5e3; /* #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas tr.buy.even td /*,
ul.list-items-buy li:last-child */ {
  background-color: #ebfae8; /* #3daf2c; */
}
table.trade-ideas tr.sell.even td /*,
ul.list-items-sell li:last-child */ {
  background-color: #fff3f2; /* #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas tr.even td {
  background-color: #f2f7f7; /* #3daf2c; */
}
table.trade-ideas tr.odd td {
  background-color: #e3e8e8; /* #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas tr.bold td {
  font-weight: bold;
}
table.trade-ideas td.comment {
  text-align: justify;
  /* font-style: italic; */
  vertical-align: top;
  color: #000; /* rgba(68,71,77,0.7); */
  padding: 0 !important;
}
table.trade-ideas td.comment,
table.trade-ideas tr.pusher td {
  background: #fffaf0 !important;
}
table.trade-ideas td.comment div {
  padding: 0 0.75em; /* 0 18px 1.5em; */
}
table.trade-ideas tr.pusher td {
  padding: 0;
}
.buttonGrad {
  font-size: 100%;
  min-width: 125px;
  background-color: transparent;
  background-repeat: repeat-x;
  background-position: center left;
  cursor: pointer !important;
  white-space: nowrap;
  width: 100%;
  outline: none;
  display: inline-block;
  margin: 1.3em auto;
  color: black;
  text-shadow: 0px 1px rgba(255,255,255,0.8);
 -webkit-border-radius: 4px;
 -moz-border-radius: 4px;
  border-radius: 4px;
  border: 1px solid silver;
  box-shadow: 1px 2px 2px 0px rgba(0,0,0,0.08);
  -webkit-box-shadow: 1px 2px 2px 0px rgba(0,0,0,0.08);
  min-height: 25px;
  height: auto;
  padding: 0.4em 8px;

  background: -moz-linear-gradient(top, #FDFDFD, #F0F0F0); /* Firefox 3.6+ */
  /* Chrome 1-9, Safari 4-5 */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FDFDFD), color-stop(100%,#F0F0F0));
  /* Chrome 10+, Safari 5.1+ */
  background: -webkit-linear-gradient(top, #FDFDFD, #F0F0F0);
  background: -o-linear-gradient(top, #FDFDFD, #F0F0F0); /* Opera 11.10+ */
  background: -ms-linear-gradient(top, #FDFDFD, #F0F0F0); /* IE10 */
  background: linear-gradient(top, #FDFDFD, #F0F0F0); /* CSS3 */
  background: -webkit-gradient(linear, left bottom, left top, from(#F0F0F0), to(#FDFDFD), color-stop(14%,#E2E2E2)) !important;
}
.buttonGradPush,
.buttonGrad:active {
  background-color: #b5c6d4 !important;
  box-shadow: inset 1px 2px 5px 0.5px #79858e;
 -webkit-box-shadow: inset 1px 2px 5px 0.5px #79858e;
 -moz-box-shadow: inset 1px 2px 5px 0.5px #79858e;
  border-color: #909090;
  /* color: white; */
  text-shadow: 0px 1px 3px gray;
}
.buttonGradGold,
.buttonGrad:hover {
  background: -moz-linear-gradient(top, #ebcf3e, #dfb829); /* Firefox 3.6+ */
  /* Chrome 1-9, Safari 4-5 */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ebcf3e), color-stop(100%,#d5a81a));
  /* Chrome 10+, Safari 5.1+ */
  background: -webkit-linear-gradient(top, #ebcf3e, #dfb829);
  background: -o-linear-gradient(top, #ebcf3e, #dfb829); /* Opera 11.10+ */
  background: -ms-linear-gradient(top, #ebcf3e, #dfb829); /* IE10 */
  background: linear-gradient(top, #ebcf3e, #dfb829); /* CSS3 */
  background: -webkit-gradient(linear, left bottom, left top, from(#F0F0F0), to(#FDFDFD), color-stop(14%,#E2E2E2)) !important;

  background: -webkit-gradient(linear, left bottom, left top, from(#dfb829), to(#ebcf3e), color-stop(14%,#d5a81a)) !important;
  border-color: #bba351;
  text-shadow: 0px 1px rgba(248,233,0,0.8);
  padding-left: 1em;
  padding-right: 1em;
}
a.expand {
  white-space: nowrap;
  display: block;
  padding: 6px 0;
  font-weight: normal !important;
  
  /* display: none; */
}
.order-container {
  width: 540px;
}
/* .hidden-text {
  display: inline !important;
}
table.trade-ideas td.comment form {
  display: block;
  width: 180px;
  float: right;
  padding-left: 30px;
} */
ul.list-items input.flat-ui {
  margin-top: 0.5em;
  margin-bottom: 1.5em;
  width: 240px;
}
ul.list-items label input.flat-ui {
  margin-left: 16px;
}


</style>


<h3><? echo(dictionary('Выбранные торговые идеи')) ?></h3>
<form id="form_order_trade_ideas" method="post" novalidate="novalidate">
  <div class="order-container">
    <ul class="list-items list-items-header">
      <? $counter = 1; 
         $idea_oper = '';
         $item = $trade_ideas[0];
         foreach($trade_ideas as $item) { 
           $counter++; 
           if($item->operation == 'ПОКУПАТЬ' || $item->operation == 'ПОКУПКА' || $item->operation == 'BUY') $idea_oper='buy';
           else if($item->operation == 'ПРОДАВАТЬ' || $item->operation == 'ПРОДАЖА' || $item->operation == 'SELL') $idea_oper='sell';
           else if($item->operation == 'ДЕРЖАТЬ' || $item->operation == 'HOLD') $idea_oper='hold'; 
      ?>
      <li class="list-items-<? echo($idea_oper) ?>">
        <h4><? echo($item->operation);?></h4>
        <strong><? echo($item->name); ?></strong>&nbsp;— <span><? echo($item->group_name) ?></span>
      </li>
      <? } ?>
      <li>
        <span><? echo(dictionary('Ожидаемый объем сделки')) ?></span>
        <div class="radio">
          <label><input type="radio" name="trade_idea_<? echo($item->trade_idea_id) ?>" id="sellOptionsRadio1" value="100000"<? echo(($item->volume=='100000')?' selected':'') ?>>100 000 $</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="trade_idea_<? echo($item->trade_idea_id) ?>" id="sellOptionsRadio2" value="200000"<? echo(($item->volume=='200000')?' selected':'') ?>>200 000 $</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="trade_idea_<? echo($item->trade_idea_id) ?>" id="sellOptionsRadio3" value="300000"<? echo(($item->volume=='300000')?' selected':'') ?>>300 000 $</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="trade_idea_<? echo($item->trade_idea_id) ?>" id="sellOptionsRadio4" value="other"<? echo(($item->volume=='other')?' selected':'') ?>><? echo(dictionary('Другая сумма')); ?></label>
          <input class="flat-ui hidden" type="text" id="sellOptionsHidden" name="trade_idea2_<? echo($item->trade_idea_id) ?>" value="<?=$item->volume?>" size="6" placeholder="<? echo(dictionary('Укажите сумму')); ?>., $">
        </div>
      </li>

    </ul>
    <h3><? echo(dictionary('Персональная информация')) ?></h3>

    <ul class="list-items">
      <li>
          <label><? echo(dictionary('Ваш email')) ?><sup>*</sup>
            <input name="data[email]" class="flat-ui" type="text" placeholder="<? echo(dictionary('Ваш email')) ?>" value="<? echo(isset($email)?$email:'') ?>">
          </label>
          <span class="hr"></span>
          <label><? echo(dictionary('Фамилия')) ?><sup>*</sup>
            <input name="data[last_name]" class="flat-ui" type="text" placeholder="<? echo(dictionary('Фамилия')) ?>" value="<? echo(isset($last_name)?$last_name:'') ?>">
          </label>
          <span class="hr"></span>
          <label><? echo(dictionary('Имя')) ?><sup>*</sup>
            <input name="data[first_name]" class="flat-ui" type="text" placeholder="<? echo(dictionary('Имя')) ?>" value="<? echo(isset($first_name)?$first_name:'') ?>">
          </label>
          <span class="hr"></span>
          <label><? echo(dictionary('Отчество')) ?><input name="data[middle_name]" class="flat-ui" type="text" placeholder="<? echo(dictionary('Отчество')) ?>" value="<? echo(isset($phone)?$phone:'') ?>">
          </label>
          <span class="hr"></span>
          <label><? echo(dictionary('Телефон')) ?><sup>*</sup>
            <input name="data[phone]" class="flat-ui" type="text" placeholder="<? echo(dictionary('Например, +7 (495) 000-00-00')); ?>" value="<? echo(isset($phone)?$phone:'') ?>">
          </label>
      </li>
    </ul>

    <h3><? echo(dictionary('Дополнительно')) ?></h3>
    <ul class="list-items">
      <li>
        <span><? echo(dictionary('Вы уже являетесь клиентом UFS IC?')) ?><sup>*</sup></span>
        <!-- <span class="hr"></span> -->
        <div class="radio">
          <label>
            <input type="radio" name="data[is_client]" id="ufsclientoption1" value="1"><label for="ufsclientoption1"><? echo(dictionary('Да')) ?></label></label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="data[is_client]" id="ufsclientoption2" value="0"><label for="ufsclientoption2"><? echo(dictionary('Нет')) ?></label></label>
        </div>

        <div class="hidden" id="notclient">
          <span class="hr"></span>
          <label><? echo(dictionary('Клиентом какого брокера вы являетесь?')) ?><sup>*</sup>
            <input name="data[broker]" class="flat-ui" id="brokername" type="text" placeholder="<? echo(dictionary('Укажите брокера')) ?>" value="<? echo(isset($broker)?$broker:'') ?>">
          </label>
          <span class="hr"></span>
          <div class="radio">
            <label><input type="checkbox" name="data[leverage]" id="chk02" value="1"<? echo((isset($leverage)&&($leverage==1))?' checked':'') ?>><? echo(dictionary('Применить леверидж')) ?></label>
          </div>
          <span class="hr"></span>
          <div class="radio">
            <label><input type="checkbox" name="application_form" id="" value="1"<? echo((isset($leverage)&&($leverage==1))?' checked':'') ?>><? echo(dictionary('Подписаться на аналитику UFS IC с упоминанием данной торговой идеи и ежедневный утренний график для отслеживания ситуации')) ?></label>
          </div>
          <span class="hr"></span>
          <div class="radio">
            <label><input type="checkbox" name="data[subscription]" id="" value="1"><? echo(dictionary('После отправки заявки перейти к заполнению анкеты на открытие счета')) ?></label>
          </div>
        </div>
      </li>
      <li>
        <p><input class="flat-ui" type="submit" name="submit" value="<? echo(dictionary('Отправить')) ?>"></p>
      </li>
    </ul>
    <p class="description"><span class="required-sign">*</span>&nbsp;— <? echo(dictionary('Обязательно для заполнения')) ?>.</p>
    <input type="hidden" name="order">
    <input type="hidden" name="data[order_id]" value="69BC8DD097FFD34B8AC294076DAD0717">

  </div>
  <input type="hidden" name="order"/>
  <input type="hidden" name="data[order_id]" value="<? echo($order_id) ?>"/>
</form>
<? } else { ?>
<p><? echo(dictionary('Спасибо! В ближайшее время наш менеджер свяжется с вами по телефону или иным способом')) ?>.</p><hr>
<? echo((isset($info)&&(trim($info)!=''))?'<p>'.$info.'</p><hr>':'') ?>
<p><? echo(dictionary('Теперь у вас есть возможность подать заявку на брокерское обслуживание на специальных условиях')) ?>
<br><a href="<? echo($this->phpself.'application-form.html') ?>"><? echo(dictionary('Подать заявку на брокерское обслуживание')) ?></a></p>
<? } ?>