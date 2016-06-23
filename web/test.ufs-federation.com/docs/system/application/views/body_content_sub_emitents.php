<? if (isset($items)) { ?>
<script>
$(document).ready(function() {
    $('#close-disclamer').click(function(){
        $('.trade-ideas-disclamer').hide('fast');
        return false;
	});
    $('.placeOrder').click(function() {
        $(this).toggleClass('buttonGradPush');
        
        if ($(this).val() == '<? echo(dictionary('Выбрать')) ?>') {
            $(this).val('<? echo(dictionary('Выбрано')) ?>');
            $(this).parent().nextAll().has(':checkbox').first().find(':checkbox').attr('checked', 'checked');
        }
        else {
            $(this).val("<? echo(dictionary('Выбрать')) ?>");
            $(this).parent().nextAll().has(':checkbox').first().find(':checkbox').removeAttr('checked');
        }
        var numberOfChecked = $('.listOffers').find('input:checked').length;
        if (numberOfChecked > 0) {
            
            $('#submitOrder').addClass('buttonGradGold');
            $('#submitOrder').removeAttr('disabled');
        } else {
            $('#submitOrder').removeClass('buttonGradGold');
            $('#submitOrder').addAttr('disabled');
        }
        return false;
    });
    $('.clientOrNot').click(function() {
        $('.clientOrNot').removeClass('buttonGradPush');
        $(this).toggleClass('buttonGradPush');
        if ($(this).val() == '<? echo(dictionary('Да')) ?>') {
            $('#isClient').attr('checked', 'checked');
            
            $('.listOffers').removeClass('hidden');
            $('.centered').addClass('hidden');
            $('html, body').animate({
                scrollTop: $("#anchor").offset().top
            }, 'slow');
        } else if($(this).val() == '<? echo(dictionary('Нет')) ?>') {
            $('#isClient').removeAttr('checked');
            $('.listOffers').addClass('hidden');
            window.location = '/application-form.html';
        }
        return false;
    });
    $('.submitOrder').click(function(){
        $('#form_emitents').submit();
        return false;
    });
});
</script>

<style>
    .listOffers ul {
      list-style-type: none;
      width: 100%;
      clear: both;
      min-height: 36px;
      padding: 0 0 4px 0;
      border-bottom: 1px solid #e0e0e0;
    }
    .listOffers ul li {
      list-style-type: none;
      float: left;
      background: none;
      padding: 4px 0 0 1em;
      
    }
    .listOffers ul li .buttonGrad {
      margin-top: -5px;
    }
    .listOffers .date {
      color: #999;
    }
    sup.newLabel {
      background-color: #CC0000;
      color: #fff;
      font: normal 9px tahoma, verdana;
      text-transform: uppercase;
      display: inline-block;
      padding: 1px 2px;
      margin: 0 0.5em;
    }
    .hidden {
      display: none;
    }
    .buttonGrad {
      min-width: 125px;
      background-color: transparent;
      background-repeat: repeat-x;
      background-position: center left;
      cursor: pointer !important;
      white-space: nowrap;
      width: auto;
      outline: none;
      display: inline-block;
      margin: 0px;
      color: black;
      text-shadow: 0px 1px white;
      border-radius: 3px;
      border: 1px solid silver;
      box-shadow: 1px 2px 2px 0px rgba(0,0,0,0.08);
      -webkit-box-shadow: 1px 2px 2px 0px rgba(0,0,0,0.08);
      min-height: 25px;
      height: auto;
      padding: 3px 8px 3px 8px;
      
      
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
    .buttonGradPush {
      background: #b5c6d4 !important;
      box-shadow: inset 1px 2px 5px 0.5px #79858e;
      -webkit-box-shadow: inset 1px 2px 5px 0.5px #79858e;
      border-color: #909090;
      color: white;
      text-shadow: 0px 1px 3px gray;
    }
    .buttonGradGold {
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
        text-shadow: 0px 1px yellow;
        padding-left: 1em;
        padding-right: 1em;
    }
    .floatRight {
      float: right !important;
    }
    .offerItem input {
      
    }
    .nopadding {
        padding-left: 0 !important;
    }
    .centered {
        text-align: center !important;
    }
    .trade-ideas-disclamer {
        background: -moz-linear-gradient(top, #fff, #e0e0e0); /* Firefox 3.6+ */
        /* Chrome 1-9, Safari 4-5 */
        background: -webkit-gradient(linear, left top, left bottom, 
                    color-stop(0%,#fff), color-stop(100%,#e0e0e0));
        /* Chrome 10+, Safari 5.1+ */
        background: -webkit-linear-gradient(top, #fff, #e0e0e0);
        background: -o-linear-gradient(top, #fff, #e0e0e0); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #fff, #e0e0e0); /* IE10 */
        background: linear-gradient(top, #fff, #e0e0e0); /* CSS3 */
        text-shadow: #fff 0 1px 0;
        position: relative;
        z-index: 10;
    }
    .remove_item {
        display: inline-block;
        background: url(/img/close-sprite.png) no-repeat 50% 0%;
        width: 20px;
        height: 20px;
        margin: auto;
    }
    .remove_item:hover, .remove_item:active {
        background-position: 50% -48px;
    }
</style>
<!-- <h2>Headline sample text</h2>
<h3>Subtitle</h3> -->
<div class="trade-ideas-disclamer" style="border-radius: 4px; border: 1px solid #f7f7f7; box-shadow: #999 0 0 6px; padding: 10px; margin-bottom: 0;">
	<a href="#" class="remove_item" id="close-disclamer" style="position: relative; float: right"></a>
	<table style="border: 0; margin: 0; width: 95%;">
		<tbody><tr>
			<td><h3><? echo(dictionary('Уважаемый инвестор')) ?>!</h3><? echo(dictionary('Предлагаем выбрать бумаги из списка ниже, а затем нажать кнопку Отправить заявку брокеру')) ?>.<br/><? echo(dictionary('В том случае, если вы еще не успели стать клиентом, предлагаем вам пройти процедуру')) ?> <a href="/application-form.html"><? echo(dictionary('открытия счета online')) ?></a>.</td>
		</tr>
	</tbody></table>
</div>
<form id="form_emitents" method="post">
  <? /* <p class="centered<? echo(isset($order_id)?' hidden':'') ?>">
    <strong><? echo(dictionary('Вы уже являетесь клиентом UFS IC')) ?>?</strong><br/><br/>
    <input class="clientOrNot buttonGrad" type="button" value="<? echo(dictionary('Да')) ?>"/>
    <input class="clientOrNot buttonGrad" type="button" value="<? echo(dictionary('Нет')) ?>"/>
    <input class="hidden" type="checkbox" value="1" name="is_client" id="isClient"/>
    <br/>
  </p> */ ?>
  <div class="listOffers<? /* echo(isset($order_id)?'':' hidden') */ ?>">
    <a id="anchor">&nbsp;</a>
    <ul class="offerItem">
      <li class="floatRight" style="visibility: hidden;"><input type="button" class="buttonGrad" value="<? echo(dictionary('Выбрать')) ?>"></li>
      <li class="floatRight" style="width: 100px; text-align: center; padding-right: 100px;"><strong><? echo(dictionary('Тикер')) ?></strong></li>
      <li><strong><? echo(dictionary('Наименование')) ?></strong></li>
    </ul>
    <? $checked = false; foreach($items as $i) { if (!$checked) { $checked = $i->checked; } ?>
    <ul class="offerItem">
      <li class="floatRight"><input type="button" class="placeOrder buttonGrad<? echo(($i->checked)?' buttonGradPush':'') ?>" value="<? echo((!$i->checked)?dictionary('Выбрать'):dictionary('Выбрано')) ?>"></li>
      <li class="floatRight" style="width: 100px; text-align: center; padding-right: 100px;"><? echo($i->ticker) ?></li>
      <li><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->name))); ?>"><? echo($i->name) ?></a></li>
      <li class="hidden"><input type="checkbox"<? echo(($i->checked)?' checked':'') ?> value="<? echo($i->emitent_id) ?>" name="emitents[]"></li>
    </ul>
    <? } ?>
    <input id="submitOrder" type="submit" class="floatRight buttonGrad<? echo(($checked)?' buttonGradGold':'') ?>"<? echo((!$checked)?' disabled="disabled"':'') ?> value="<? echo(dictionary('Перейти к оформлению')) ?>">
    <br class="clear"/>  
  </div>
  <input type="hidden" name="order"/>
</form>
<h3><? echo(dictionary('Смотрите также')) ?></h3>
<ul>
    <li><a href="/pages/analitika/runok-aktsiyi/advice.html"><? echo(dictionary('Посмотреть торговые идеи по акциям')) ?></a></li>
    <li><a href="/pages/analitika/dolgovoyi-runok/torgovue-idei.html"><? echo(dictionary('Посмотреть торговые идеи облигации')) ?></a></li>
</ul>
<? } ?>