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

<script type="text/javascript" src="/js/NEWjquery.tablesorter.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.widgets.js"></script>
<script>
	$(document).ready(function() {
		$('#table1').tablesorter({
			theme : 'blue',
			cssInfoBlock : "tablesorter-no-sort",
			widgets: [ 'zebra', 'stickyHeaders' ],
			widgetZebra: { css: ['d0', 'd1']},
			stickyHeaders : 'tablesorter-stickyHeader',
			headers: {
				0: {sorter: "text"},
				1: {sorter: "text"},
				2: {sorter: "text"},
				3: {sorter: "text"},
				4: {sorter: "percent"},
				5: {sorter: "text"}
			}
		});
	});
</script>
<style>
  .r-hold, .r-buy, .r-sell {
    color: #fff !important;
  }
  .r-hold {
    background-color: #686868 !important;
  }
  .r-buy {
    background-color: #3daf2c !important;
  }
  .r-sell {
    background-color: #e1261c !important;
  }
</style>
<link rel="stylesheet" href="/css/theme.blue.css" type="text/css" media="all">
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>  
 <? if($data->euro){ ?>
    <div class="section-recommendations">
    <div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
      <table class="tablesorter-blue" id="table1">
        <thead>
          <tr>
            <th class="header"><? echo(dictionary('Наименование')); ?></th>
            <th class="header"><? echo(dictionary('Тикер')); ?></th>
            <th class="header" width="15%"><? echo(dictionary('Цена текущая')); ?></th>
            <th class="header" width="15%"><? echo(dictionary('Цена (справедливая)').'<sup>1</sup>'); ?></th>
            <th class="header" width="15%"><? echo(dictionary('Потенциал роста/снижения')); ?></th>
            <th class="header" width="10%"><? echo(dictionary('Рекомендация').'<sup>2</sup>'); ?></th>
          </tr>
        </thead>
        <? foreach($sectors as $s){ ?>
        <tbody class="tablesorter-no-sort">
            <tr class="sector">
                    <td colspan="6" style="text-align: center"><? echo($s->name); ?></td>
            </tr>
        </tbody>

        <tbody>
        <? foreach($s->items as $i){ ?>
            <tr>
              <td class="aleft"><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->ticker))); ?>"><? echo($i->ticker); ?></a></td>
              <td class="acenter"><? echo($i->name); ?></td>
              <td class="aright"><? echo($i->price_currency); ?></td>
              <td class="aright"><? echo($i->price_fair); ?> </td>
              <td class="aright"><? if ($i->potential!='') { echo($i->potential.' %'); } ?></td>
              <td class="acenter<? echo(($i->class!='')?' '.$i->class:''); ?>"><? echo($i->recommendation); ?></td>
            </tr>
        <? } ?>
        </tbody>
        <? } ?>
      </table>
      <p class="table-notice"><span class="info-sign">i</span>&nbsp;<i>Данные предоставляются Московской Биржей</i></p><br style="clear:both"/>
  <? if($this->site_lang=='ru') { ?>
    <p><span><i>1&nbsp;&mdash; Справедливый уровень цены бумаги в&nbsp;перспективе 12-и месяцев.</i></span></p>
    <p><span><i>2&nbsp;&mdash; За&nbsp;исключением отдельно оговоренных случаев (см. обзоры), рекомендация &laquo;<span style="color: green">Покупать</span>&raquo; соответствует потенциалу роста цены акции от&nbsp;текущего уровня более чем на&nbsp;20% в&nbsp;течение одного года; рекомендация &laquo;Держать&raquo; предполагает диапазон от &minus;5% до&nbsp;20%; рекомендация &laquo;<span style="color: red">Продавать</span>&raquo; соответствует потенциалу снижения с&nbsp;текущих уровней более чем на&nbsp;5%; &laquo;Пересмотр&raquo; рекомендации означает, что в&nbsp;данный момент, ввиду появления новых факторов, способных повлиять на&nbsp;цену компании, или корпоративных событий, связанных с&nbsp;эмитентом, мы&nbsp;проводим переоценку компании и&nbsp;значения справедливого уровня цены её&nbsp;акций.</i></span></p>
    <p><span><i>Представленные оценки основываются на&nbsp;данных, полученных из&nbsp;публичных источников информации, предположениях и&nbsp;прогнозах UFS IC&nbsp;и&nbsp;не&nbsp;содержат инсайдерской, и&nbsp;иной информации, полученной незаконным путем. Компания UFS IC&nbsp;и&nbsp;ее&nbsp;сотрудники не&nbsp;несут ответственности за&nbsp;инвестиционные решения, принятые на&nbsp;основе представленной информации.</i></span></p>
    <!-- <span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span> -->
  <? } ?>
  </div>
<? } ?>
        
      
  
