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
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
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
			}
		});
	});
</script>
<link rel="stylesheet" href="/css/theme.blue.css" type="text/css" media="all">
<style>
	.sector td {
		background: #cdcdcd;
		font-weight: bold;
	}
</style>
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>  
		<? if($sectors){ ?>
		  <div class="section-planned-placement">
		  <div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>

		<table class="tablesorter-blue" id="table1">
			<thead>
				<tr>
					<th class="header"><? echo(dictionary('Наименование')); ?></th>
					<th class="header" width="30%"><? echo(dictionary('Размещение')); ?></th>
					<th class="header" width="35%"><? echo(dictionary('Объем, млн')); ?></th>
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
					<td class="left"><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->name))); ?>"><? echo($i->name); ?></a></td>
					<td><? echo($i->placement); ?></td>
					<td><? echo($i->volume); ?></td>
				</tr>
				<? } ?>
			</tbody>
			<? } ?>
</table>
          <? if($this->site_lang=='ru') { ?>
            <span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span>
          <? } ?>
		  </div>
          <? } ?>
