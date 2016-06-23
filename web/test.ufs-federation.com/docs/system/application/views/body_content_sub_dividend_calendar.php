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
<a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a> 
    
<script type="text/javascript" src="/js/NEWjquery.tablesorter.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/js/jquery.metadata.js"></script>
<script>
	$(document).ready(function() {
		$('#table1').tablesorter({
			theme : 'blue',
			cssInfoBlock : "tablesorter-no-sort",
			widgets: [ 'zebra', 'stickyHeaders' ],
			widgetZebra: { css: ['d0', 'd1']},
			stickyHeaders : 'tablesorter-stickyHeader',
      dateFormat : "mmddyyyy", // set the default date format
			headers: {
				  0: {sorter: "text"},
					1: {sorter: "text"},
					2: {sorter: "text"},
					3: {sorter: "digit"},
					4: {sorter: "percent"},
					5: {sorter: "shortDate", dateFormat: "ddmmyyyy"},
					6: {sorter: "digit"}
			}
		});
	});
</script>
<link rel="stylesheet" href="<? echo($this->phpself) ?>css/theme.blue.css" type="text/css" media="all">
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
	table.tablesorter-blue tbody tr td.aright  {
		text-align: right;
	}

</style>

<div class="section-recommendations">
<? if($this->site_lang=='ru') { ?>

<div style="float: right" class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
<h3><a style="background: #dcb439 url(/img/bg_conference.png) 100% 50%; margin: 18px 18px 18px 0px; padding: 10px 27px 10px 27px; color: black;" href="/pages/investoram/526-depozitariyi/dividendu.html" target="_blank">Что должен знать инвестор, чтобы получить дивиденды?</a></h3>
   
<? } else if($this->site_lang=='en') { ?>

<div style="float: right" class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
<h3><a style="background: #dcb439 url(/img/bg_conference.png) 100% 50%; margin: 18px 18px 18px 0px; padding: 10px 27px 10px 27px; color: black;" href="/pages/for-investors/546-depository-services/dividends.html" target="_blank"><? echo(dictionary('Что должен знать инвестор, чтобы получить дивиденды?')); ?></a></h3>
   
<? } else { ?>
    
    

<div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>

<? } ?>

  <? if($sectors){ ?>
      <table class="tablesorter-blue" id="table1">
        <thead>
          <tr>
            <th class="header"><? echo(dictionary('Наименование')); ?></th>
            <th class="header"><? echo(dictionary('Тикер')); ?></th>
            <th class="header" width="15%"><? echo(dictionary('Тип дивиденда')); ?></th>
            <th class="header" width="15%"><? echo(dictionary('Дивиденды, руб. на акцию')); ?></th>
            <th class="header" width="15%"><? echo(dictionary('Дивидендная дох-ть, %')); ?></th>
            <th class="header" width="10%"><? echo(dictionary('Дата закрытия реестра')); ?></th>
            <th class="header" width="10%"><? echo(dictionary('Цена бумаги')); ?></th>
          </tr>
        </thead>

        <? foreach($sectors as $s){ ?>
        <tbody class="tablesorter-no-sort">
          <tr class="sector">
            <td colspan="7" style="text-align: center"><? echo($s->name); ?></td>
          </tr>
        </tbody>

        <tbody>
          <? foreach($s->items as $i){ ?>
            <tr<? echo(isset($i->past)?' class="past" title="More Info"':'') ?> >
              <td<? echo((isset($i->classes) && isset($i->classes['name']))?' class="'.$i->classes['name'].'"':'') ?>><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->name))); ?>"><? echo($i->name) ?></a></td>
              <td<? echo((isset($i->classes) && isset($i->classes['ticker']))?' class="'.$i->classes['ticker'].' acenter"':' class="acenter"') ?>><? echo(isset($i->ticker)?$i->ticker:'') ?></td>
              <td<? echo((isset($i->classes) && isset($i->classes['dividend_type']))?' class="'.$i->classes['dividend_type'].' acenter"':' class="acenter"') ?>><? echo(isset($i->dividend_type)?$i->dividend_type:'') ?></td>
              <td<? echo((isset($i->classes) && isset($i->classes['dividends']))?' class="'.$i->classes['dividends'].' aright"':' class="aright"') ?>><? echo(isset($i->dividends)?$i->dividends:'') ?></td>
              <td<? echo((isset($i->classes) && isset($i->classes['dividend_yield']))?' class="'.$i->classes['dividend_yield'].' aright"':' class="aright"') ?>><? echo(isset($i->dividend_yield)?$i->dividend_yield:'') ?></td>
              <td<? echo((isset($i->classes) && isset($i->classes['close_date']))?' class="'.$i->classes['close_date'].' acenter"':' class="acenter"') ?>><? echo(isset($i->close_date)?date('d.m.Y',strtotime($i->close_date)):'') ?></td>
              <td<? echo((isset($i->classes) && isset($i->classes['price']))?' class="'.$i->classes['price'].' aright"':' class="aright"') ?>><? echo(isset($i->price)?$i->price:'') ?></td>
            </tr>
            <? } ?>
        </tbody>
        <? } ?>
      </table>
      <p class="table-notice"><span class="info-sign">i</span>&nbsp;<i>Данные предоставляются Московской Биржей</i></p><br style="clear:both"/>
  <? } ?>

<!--   <table class="tablesorter-blue" id="table1">
	<colgroup>
		<col style="width: 22%;">
		<col style="width: 16%;">
		<col style="width: 16%;">
		<col style="width: 16%;">
		<col style="width: 15%;">
		<col style="width: 15%;">
		<col style="width: 16%;">
	</colgroup>
    <thead>
	    <tr>
		    <th class="header"><? echo(dictionary('Наименование')) ?></th>
		    <th class="header"><? echo(dictionary('Тикер')) ?></th>
        <th class="header"><? echo(dictionary('Отрасль')) ?></th>
		    <th class="header"><? echo(dictionary('Тип дивиденда')) ?></th>
		    <th class="header"><? echo(dictionary('Дивиденды, руб. на акцию')) ?></th>
		    <th class="header"><? echo(dictionary('Дивидендная дох-ть, %')) ?></th>
		    <th class="header"><? echo(dictionary('Дата закрытия реестра')) ?></th>
		    <th class="header"><? echo(dictionary('Цена бумаги')) ?></th>
		  </tr>
    </thead>
    <tbody>
    <? if (isset($data) && is_array($data)) { ?>
      <? foreach($data as $d) { ?>
	    <tr<? echo(isset($d->past)?' class="past" title="More Info"':'') ?> >
	      <td<? echo((isset($d->classes) && isset($d->classes['name']))?' class="'.$d->classes['name'].'"':'') ?>><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($d->name))); ?>"><? echo($d->name) ?></a></td>
	      <td<? echo((isset($d->classes) && isset($d->classes['ticker']))?' class="'.$d->classes['ticker'].' acenter"':' class="acenter"') ?>><? echo(isset($d->ticker)?$d->ticker:'') ?></td>
        <td<? echo((isset($d->classes) && isset($d->classes['sector']))?' class="'.$d->classes['sector'].' acenter"':' class="acenter"') ?>><? echo(isset($d->sector)?$d->sector:'') ?></td>
	      <td<? echo((isset($d->classes) && isset($d->classes['dividend_type']))?' class="'.$d->classes['dividend_type'].' acenter"':' class="acenter"') ?>><? echo(isset($d->dividend_type)?$d->dividend_type:'') ?></td>
	      <td<? echo((isset($d->classes) && isset($d->classes['dividends']))?' class="'.$d->classes['dividends'].' aright"':' class="aright"') ?>><? echo(isset($d->dividends)?$d->dividends:'') ?></td>
	      <td<? echo((isset($d->classes) && isset($d->classes['dividend_yield']))?' class="'.$d->classes['dividend_yield'].' aright"':' class="aright"') ?>><? echo(isset($d->dividend_yield)?$d->dividend_yield:'') ?></td>
	      <td<? echo((isset($d->classes) && isset($d->classes['close_date']))?' class="'.$d->classes['close_date'].' acenter"':' class="acenter"') ?>><? echo(isset($d->close_date)?date('d.m.Y',strtotime($d->close_date)):'') ?></td>
	      <td<? echo((isset($d->classes) && isset($d->classes['price']))?' class="'.$d->classes['price'].' aright"':' class="aright"') ?>><? echo(isset($d->price)?$d->price:'') ?></td>
	    </tr>
      <? } ?>
    <? } ?>  
    </tbody>
  </table>-->
  <p><strong><? echo(dictionary('Примечание')) ?>:</strong></p>
	<ul>
		<li><? echo(dictionary('Жирным шрифтом выделены данные, утвержденные эмитентом')) ?>.</li>
		<li><? echo(dictionary('Серым цветом отмечены бумаги, реестр акционеров по которым уже закрыт. Цена бумаги в этом случае указывается на дату закрытия реестра')) ?>.</li>
		<li><? echo(dictionary('Ожидаемый средний срок поступления дивидендных выплат — 60 календарных дней после закрытия реестра акционеров')) ?>.</li>
	</ul>
	<p><? echo(dictionary('Обращаем ваше внимание, что приведенные выше данные о дате закрытия реестра и размере дивидендных выплат являются прогнозными и основываются на расчетах UFS IC')) ?>.
	<br><? echo(dictionary('Календарь обновляется в зависимости от появления информации от эмитентов, поэтому фактические данные могут в какой-то момент времени отличаться от приведенных в нашем календаре')) ?>.</p>
</div>