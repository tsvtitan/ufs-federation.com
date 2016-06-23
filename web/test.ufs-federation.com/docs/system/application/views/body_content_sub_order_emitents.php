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

  $("#form_order_emitents").validate({
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
          return $('input[name="data[is_client]"]', '#form_order_emitents').val() > 0;
        }
      },
      /*"data[leveridge]": {
        required: function(element) {
          return $('input[name="data[is_client]"]', '#form_order_emitents').val() > 0;
        }
      },
      "data[leveridge_cond]": {
        required: function(element) {
          return $('input[name="data[is_client]"]', '#form_order_emitents').val() > 0;
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
    display: block;
    background: url(/img/close-sprite.png) no-repeat 50% 11px;
    width: 20px;
    height: 20px;
    margin: auto;
    padding: 8px;
  }
  .remove_item:hover, .remove_item:active {
    background-position: 50% -37px;
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
  table.cart {
      border-collapse: separate !important;
      border-bottom: 1px solid #c0c0c0;
  }
  table.cart td, table.cart th {
        background-color: #f2f2f2;
        border-top: 1px solid #fff;
        border-bottom: 1px solid #d7d7d7;
        padding: 1em 0.75em 0.75em;
  }
  table.cart td, table.cart th, table.cart td input {
      font-size: 92%;
  }
  table.cart td input {
      text-align: right;
  }
  table.cart th {
      color: #444;
      border-bottom-color: #d0d0d0;
      text-shadow: #f9f9f9 0 1px 0;
      background-color: #dedede;
  }
</style>

<form id="form_order_emitents" method="post">
  <h2><? echo(dictionary('Выбранные акции')) ?></h2>
  <table class="cart" style="width:540px">
    <tr>
      <th class="aleft"><? echo(dictionary('Название')) ?></th>
      <th class="acenter" style="width: 230px"><? echo(dictionary('Ожидаемый объем сделки')) ?></th>
      <th class="aright" style="width: 44px;"><!-- <? echo(dictionary('Удалить')) ?> --></th>
    </tr>
    <? $counter = 0; foreach($emitents as $i) { $counter++; ?>
    <tr>
      <td><? echo($counter.'.&nbsp'.$i->name) ?></td>
      <td class="nowrap acenter">
        <input type="text" size="16" name="emitent_<? echo($i->emitent_id) ?>" id="inp_sel<?=$counter?>" value="<?=$i->volume?>">&nbsp;руб.
        <? /*
        <select id="sel<?=$counter?>" name="emitent_<? echo($i->emitent_id) ?>">
          <option value="100000"<? echo(($i->volume=='100000')?' selected':'') ?>>100 000</option>
          <option value="200000"<? echo(($i->volume=='200000')?' selected':'') ?>>200 000</option>
          <option value="300000"<? echo(($i->volume=='300000')?' selected':'') ?>>300 000</option>
          <option value="other"<? echo(($i->volume=='other')?' selected':'') ?>><? echo(dictionary('Другое')) ?></option>
        </select>
        */ ?>
        
      </td>
      <td class="aright" style="padding: 0;"><a href="#" class="remove_item" title="<? echo(dictionary('Удалить'))?>"></a></td>
    </tr>
    <? } ?>
  </table>
  <p class="error errormsg"><? echo(dictionary('Нельзя удалить последнюю позицию')) ?>. <a href="<? echo($this->phpself.(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'#')) ?>">‹&nbsp;<? echo(dictionary('Вернуться')) ?></a></p>
  <p class="description"><span class="required-sign">*</span>&nbsp;— <? echo(dictionary('поля, обязательные для заполнения')) ?>.</p>

  <dl class="row">
    <dt class="label"><label for=""><? echo(dictionary('Ваш email')) ?><sup>*</sup></label></dt>
    <dd class="input"><input type="text" name="data[email]" value="<? echo(isset($email)?$email:'') ?>"></dd>
  </dl>
  
  <dl class="row">
    <dt class="label"><label for=""><? echo(dictionary('Фамилия')) ?><sup>*</sup></label></dt>
    <dd class="input"><input type="text" name="data[last_name]" value="<? echo(isset($last_name)?$last_name:'') ?>"></dd>
  </dl>
  
  <dl class="row">
    <dt class="label"><label for=""><? echo(dictionary('Имя')) ?><sup>*</sup></label></dt>
    <dd class="input"><input type="text" name="data[first_name]" value="<? echo(isset($first_name)?$first_name:'') ?>"></dd>
  </dl>
  
  <dl class="row">
    <dt class="label"><label for=""><? echo(dictionary('Отчество')) ?></label></dt>
    <dd class="input"><input type="text" name="data[middle_name]" value="<? echo(isset($middle_name)?$middle_name:'') ?>"></dd>
  </dl>
  
  <dl class="row">
    <dt class="label"><label for=""><? echo(dictionary('Телефон')) ?><sup>*</sup></label></dt>
    <dd class="input"><span class="plus-indent">+</span><input id="phone_num" type="text" name="data[phone]" value="<? echo(isset($phone)?$phone:'') ?>" style="text-indent: 12px;"></dd>
  </dl>
<? /* <dl class="row wide">
      <dt class="label"><label for=""><? echo(dictionary('Клиентом какого брокера вы являетесь')) ?>?<sup>*</sup></label></dt>
    </dl>
    <dl class="row">
      <dt class="label">&nbsp;</dt>
      <dd class="input"><input class="fullwidth" type="text" name="data[broker]" value="<? echo(isset($broker)?$broker:'') ?>"></dd>
    </dl>
*/ ?>
    <br class="clear">
    <dl class="row">
      <dt class="label">&nbsp;</dt>
      <dd class="input">
        <div class="selector">
          <input type="checkbox" id="chk01" name="data[subscription]" value="1" checked />
          <span class="label">
            <label for="chk01"><? echo(dictionary('Подписаться на&nbsp;аналитику UFS IC с&nbsp;упоминанием данной акции и&nbsp;ежедневный утренний график для отслеживания ситуации')) ?></label>
          </span>
        </div>
      </dd>
    </dl>
    
    
  
  <br class="clear"/>
  <dl class="row">
    <dt class="label"><a href="<? echo($this->phpself.(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'#')) ?>">‹&nbsp;<? echo(dictionary('Вернуться')) ?></a></dt>
    <dd class="input">
      <input class="flat-btn" type="submit" name="submit" value="<? echo(dictionary('Отправить')) ?>">
    </dd>
  </dl>
  <input type="hidden" name="order"/>
  <input type="hidden" name="data[order_id]" value="<? echo($order_id) ?>"/>
</form>
<? } else { ?>
<p><? echo(dictionary('Спасибо! В ближайшее время наш менеджер свяжется с вами по телефону или иным способом')) ?>.</p><hr>
<? echo((isset($info)&&(trim($info)!=''))?'<p>'.$info.'</p><hr>':'') ?>
<p><? echo(dictionary('Теперь у вас есть возможность подать заявку на брокерское обслуживание на специальных условиях')) ?>
<br><a href="<? echo($this->phpself.'application-form.html') ?>"><? echo(dictionary('Подать заявку на брокерское обслуживание')) ?></a></p>
<? } ?>