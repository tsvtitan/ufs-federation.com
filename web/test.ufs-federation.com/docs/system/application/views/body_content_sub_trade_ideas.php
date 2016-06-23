<script>
    (function($){  
      $(function(){  
        var e = $(".scrollTop"),  
        speed = 500;  
  
        e.click(function(){  
            $("html:not(:animated)" +( !$.browser.opera ? ",body:not(:animated)" : "")).animate({ scrollTop: 0}, 500 );  
            return false; //важно!  
        });  
      
    function show_scrollTop(){  
            ( $(window).scrollTop()>300 ) ? e.fadeIn(600) : e.hide();  
        }  
        $(window).scroll( function(){show_scrollTop()} ); show_scrollTop();  
    });  
  
})(jQuery)  
</script>
    
    <? if (isset($groups)) { ?>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.widgets.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/css/theme.blue.css" type="text/css" media="all">

<style>
	.sector td {
		background: #cdcdcd;
		font-weight: bold;
	}
</style>
<script>
  $(document).ready(function() {
  	  
  	  $('#close-disclamer').click(function(){
			$('.trade-ideas-disclamer').toggle('fast');
			return false;
		});

    $('.addtocartlink').click(function(){
      var linkid = 'x' + $(this).attr("id");
      if ($(this).is(':checked')){
        $(this).next('span').text('<? echo(dictionary("Удалить")) ?>');
        $('#'+linkid).show();
      } else {
        $(this).next('span').text('<? echo(dictionary("Оформить заявку")) ?>');
        $('#'+linkid).hide();
      };
    });
    
    $('.cartorder').click(function(){
      $('#form_trade_ideas').submit();
      return false;
    });

    jQuery.tablesorter.addParser({
      id: "fancyNumber",
      is: function(s) {
        return /^[0-9]?[0-9,\.]*$/.test(s);
      },
      format: function(s) {
          return jQuery.tablesorter.formatFloat( s.replace(/,/g,'') );
      },
      type: "numeric"
    });

    var names = '<?
      $names = "";
      if (is_array($groups)) {
        $n = array();
        foreach($groups as $g) {
          $n[] = "#table".$g->counter;
        }
        $names = implode(",",$n);
      }
      echo($names); 
    ?>';
    
    $('#table1').tablesorter({
		debug:true,
		theme : 'blue',
		cssInfoBlock : "tablesorter-no-sort",
		widgets: [ 'zebra', 'stickyHeaders' ],
		widgetZebra: { css: ['d0', 'd1']},
		stickyHeaders : 'tablesorter-stickyHeader',
		headers: {
        0: {sorter: "text"},
        1: {sorter: "text"},
        2: {sorter: "text"},
        3: {sorter: "percent"},
        4: {sorter: "percent"},
        5: {sorter: "text"}
      }
    });
  });

</script>



<style>

  table.tablesorter-blue tbody tr td.bold  {
    font-weight: bold;
    color: #000;
  }
  table.tablesorter-blue tbody tr td.close  {
    color: #ef181e;
  }
  table.tablesorter-blue tbody tr td.acenter  {
    text-align: center;
  }
  table.tablesorter-blue tbody tr td.ajustify  {
    text-align: justify;
  }
  table.tablesorter-blue tbody tr td.aright  {
    text-align: right;
  }
  div.chkbx {
    display: block;
    padding-top: 4px;
    padding-left: 6px;
    text-align: bottom;
  }
  div.chkbx a {
    display: none;
    vertical-align: middle;
    text-decoration: underline;
  }
  div.chkbx input {
    margin-right: 4px;
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
  }
</style>
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>  
<?
$lang_prefix = 'en';
if($this->site_lang=='ru' || $this->site_lang=='en' || $this->site_lang=='de') {
	$lang_prefix = $this->site_lang;
}
?>
<!-- div class="section-recommendations" -->
<div class="trade-ideas-disclamer" style="border-radius: 4px; border: 1px solid #f7f7f7; box-shadow: #999 0 0 6px; padding: 10px; margin-bottom: 2em;">
	<a href="#" class="remove_item" id="close-disclamer" style="position: relative; float: right"></a>
	<table style="border: 0; margin: 0; width: 97%;">
		<tr>
			<td style="width: 35%; height: 1px; padding: 0;"></td>
			<td style="width: 35%; height: 1px; padding: 0;"></td>
			<td style="width: 30%; height: 1px; padding: 0;"></td>
		</tr>
		<tr>
			<td colspan="2"><h3><? echo(dictionary('Уважаемый инвестор')); ?>!</h3><? echo(dictionary('Если вы уже являетесь клиентом брокера UFS Finance, то выбрав одну из торговых идей, вы можете отправить заявку брокеру')); ?>.</td>
			<td rowspan="2"><? if($this->site_lang=='ru') { ?><h3>&nbsp;</h3><p style="margin: 0; paddding: 0; border-left: 2px solid #777; text-align: left; padding-left: 12px;">Если вы&nbsp;не&nbsp;являетесь клиентом брокера UFS&nbsp;Finance, вы&nbsp;можете <a href="/application-form.html"><nobr>открыть счет онлайн</nobr></a>.<? } ?></td>
		</tr>
		<tr>
			<td><div class="pic" style="padding-left: 22px; background: transparent url(/img/trade-ideas-disclamer2_<?=$lang_prefix?>.gif) no-repeat 22px 0; width: 166px; height: 61px;"><span style="display: block; position: relative; margin-left: -22px; font-size: 22px; font-weight: bold; font-style: italic">1.</span></div></td>
			<td><div class="pic" style="padding-left: 22px; background: transparent url(/img/trade-ideas-disclamer2_<?=$lang_prefix?>.gif) no-repeat 22px -61px; width: 166px; height: 61px;"><span style="display: block; position: relative; margin-left: -22px; font-size: 22px; font-weight: bold; font-style: italic">2.</span></div></td>
		</tr>
	</table>
</div>
	
	
  <div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
  <? if (is_array($groups)) { ?>
  <form name="form_trade_ideas" id="form_trade_ideas" method="post">
  <table class="tablesorter-blue" id="table1">
  <colgroup>
    <col style="width: 20%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 30%;">
  </colgroup>
    <thead>
      <tr>
        <th class="header"><? echo(dictionary('Выпуск')) ?></th>
        <th class="header"><? echo(dictionary('ISIN')) ?></th>
        <th class="header"><? echo(dictionary("Рейтинг S&P/Moody's/Fitch")) ?></th>
        <th class="header"><? echo(dictionary('Доходность, %')) ?></th>
        <th class="header"><? echo(dictionary('Цена, (% от номинала)')) ?></th>
        <th class="header"><? echo(dictionary('Комментарий')) ?></th>
      </tr>
    </thead>
	<? $counter = 0; foreach($groups as $g) { ?>
	<tbody class="tablesorter-no-sort">
		<tr class="sector">
			<td colspan="6" style="text-align: center"><? echo($g->name) ?></td>
		</tr>
	</tbody>
	
	<tbody>
	<? foreach($g->items as $i) { $counter++; ?>
		<tr>
			<td class="aleft big">
				<a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->name))); ?>"><? echo($i->name) ?></a>
				<div class="chkbx"><label for="chk<? echo($counter) ?>" class="chkbx"><input name="trade_ideas[]" <? echo((isset($i->checked)&&$i->checked)?'checked ':'') ?> value="<? echo($i->trade_idea_id) ?>" type="checkbox" class="addtocartlink" id="chk<? echo($counter) ?>"/>
				<span><? echo((isset($i->checked)&&$i->checked)?dictionary('Удалить'):dictionary('Оформить заявку')) ?></span></label>&nbsp;<a id="xchk<? echo($counter) ?>" href="#" class="cartorder"<? echo((isset($i->checked)&&$i->checked)?' style="display:inline"':'') ?>><? echo(dictionary('Перейти к оформлению')) ?></a></div>
			</td>
			<td class="acenter"><? echo($i->isin) ?></td>
			<td class="acenter"><? echo($i->rating) ?></td>
			<td class="aright"><? echo($i->yield) ?></td>
			<td class="aright"><? echo($i->price) ?></td>
			<td class="ajustify"><? echo($i->description) ?></td>
		</tr>
	<? } ?>
	</tbody>
	<? } ?>
</table>

  <input type="hidden" name="order"/>
  </form>
  <? } ?>
  <? if($this->site_lang=='ru') { ?>
  <span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span>
  <? } else { ?>
  <span><i><? echo(dictionary('The present assessments are based on the data from public sources, assumptions and forecasts of UFS IC and do not include insider or other information derived illegally. UFS IC and its employees are not responsible for the decisions based on the provided information.')); ?></i></span>
  <? } ?>
<!-- /div -->
<? } ?>