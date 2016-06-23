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
    <link rel="stylesheet" href="/css/theme.blue.css" type="text/css" media="all">
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
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
    table.tablesorter-blue tbody tr td.red  {
    	color: red;
    }
    table.tablesorter-blue tbody tr td.green  {
    	color: green;
    }
    
    table.past tr td, table.past tr td a {
        color: #f0f0f0 !important;
    }
    table.past tr td {
            background-color: #acacac !important;
    }
    table.past tr.sector td {
            background-color: #9c9c9c !important;
    }
    
    .all-to-right td, .all-to-right th {
        text-align: right;
        white-space: nowrap;
    }
    .all-to-right th {
        text-align: center;
        vertical-align: middle;
    }
    .sector td {
        color: #000;
        border-bottom: 1px solid #ebebeb;
    }
    table.tablesorter-blue {
        margin-top: 0;
    }
    div.tablesorter-header-inner {
        white-space: nowrap;
    }
    #table1 {
        height: auto;
    }
    .tablesorter-blue .header, .tablesorter-blue .tablesorter-header {
        padding-left: 3px;
    }
    p#date-status {
        padding: 0.3em 0;
        margin:0;
        font-size: 13px;
        color: #fff;
        /* text-shadow:#222 0 -1px 0; */
        text-align: center;
        background-color: #464646;
    }
    #containergraph {
        min-width: 540px;
        height: 400px;
        margin: 0 auto;
        background: transparent url(/img/ajax-loader.gif) no-repeat 50% 40%;
    }
    #date-note {
        display: none;
    }
    #loading {
        color: #ccc;
    }
    #point-note {
        position: absolute;
        right: 0;
        width: 185px;
        height: 30px;
        margin-top: -39px;
        z-index:199;
        padding: 4px 10px;
        font-size: 11px;
        float: right;
        color: black;
        background-color: #fdfdfd;
        border: 1px solid #cdcdcd;
    }
     
    
    
    
</style>
<script type='text/javascript'>//<![CDATA[
$(document).ready(function() {
    var lastPointDate;
    function renderPieChart(date) {
        var DDDate = (typeof date !== 'undefined') ? date : getFormattedDate();
       // $.getJSON('/pages/analitika/modelnuyi-portfel.html?type=pie&date='+DDDate+'&callback=?', function(data) {
        $.getJSON('<? echo($this->full_page_url) ?>?type=pie&date='+DDDate+'&callback=?', function(data) {
            $('#containerpie').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: false,
                tooltip: {
                    formatter: function () {
                        return this.point.name + ': <b>' + Highcharts.numberFormat(this.percentage, 2) + '%</b>';
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 2) +' %';
                            }
                        },
                        point: {
                            events: {
                                legendItemClick: function () {
                                    return false;
                                }
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    type: 'pie',
                    name: "<? echo(dictionary('Структура портфеля')); ?>",
                    data: data
                }]
            });
        });
    }
    function getFormattedDate(format,date) {
        // months
        var monthNames_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var monthNames_ru = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        
        // current date
        //var ms = new Date().getTime()-(1000*60*60*24); // -1 day
        var ms = new Date();
        var DD = new Date(ms);
        
        // check for argument
        if (typeof date !== 'undefined') {
            var dateParts = date.split('-');
        }
        var DDMonth = (typeof date !== 'undefined') ? trimNumber(dateParts[1]) : DD.getMonth()+1;
        var DDDay = (typeof date !== 'undefined') ? trimNumber(dateParts[2]) : DD.getDate();
        var DDYear = (typeof date !== 'undefined') ? trimNumber(dateParts[0]) : DD.getFullYear();
        
        /*var DDNum = DD.getFullYear() + '-' + ((''+DDMonth).length<2 ? '0' : '') + DDMonth + '-' + ((''+DDDay).length<2 ? '0' : '') + DDDay;
        var DDText = DDDay + ' ' + monthNames_<?=($this->site_lang=='ru' ? 'ru' : 'en')?>[DDMonth-1] + ' ' + DD.getFullYear();*/
        
        var DDNum = DDYear + '-' + ((''+DDMonth).length<2 ? '0' : '') + DDMonth + '-' + ((''+DDDay).length<2 ? '0' : '') + DDDay;
        var DDText = DDDay + ' ' + monthNames_<?=($this->site_lang=='ru' ? 'ru' : 'en')?>[DDMonth-1] + ' ' + DDYear;
        var uri = window.location.href + '#' + DDNum;
        
        return (typeof format !== 'undefined' ? (format == 'text' ? DDText : DDNum) : DDNum);
    }
    
    function trimNumber(s) {
        while (s.substr(0,1) == '0' && s.length>1) { s = s.substr(1,9999); }
        return s;
    }
    
    function appendTable(date, lastpoint) {

        var DDDate = getFormattedDate('number',date);
        var DDText = getFormattedDate('text',date);
        // url with date
        //var url = '/pages/analitika/modelnuyi-portfel.html?type=table&date='+DDDate+'&callback=?';
        var url = '<? echo($this->full_page_url) ?>?type=table&date='+DDDate+'&callback=?';
        var xtable = '';
        var xtable2 = '';

    if (!date || (date != lastPointDate)) {
        lastPointDate = date;
        
        $("#date-note").fadeOut('fast',function(){
            $("#loading").fadeIn('fast');
            $("#date-selected").html(DDText);
        });

        $("#table-container").slideUp(300,function(){
            if (!date || (date && lastpoint)) {
                $('#table1').removeClass('past');
                $('.archive-note').hide();
            } else {
                $('#table1').addClass('past');
                $('.archive-note').show();
            }
            
            $.getJSON(url, function(data) {

                $("#loading").fadeOut('fast',function(){
                    $("#date-note").fadeIn('fast');
                });
                
                jQuery.each(data, function(index, itemData) {
                    if (itemData.operation_value == null) {
                        xtable2 += '<tr class="sector"><td colspan="4" class="aleft">' + (itemData.name == null ? '-' : itemData.name) + '</td><td>' + (itemData.total_first == null ? '-' : itemData.total_first + ' <? echo(dictionary("р.")); ?>') + '</td><td></td><td>'+(itemData.revenue_loss == null ? '' : itemData.revenue_loss + ' <? echo(dictionary("р.")); ?>')+'</td><td>'+(itemData.shift == null ? '' : itemData.shift + '%')+'</td></tr>';
                        console.log('added footer row');
                    } else {
                        td_class = ((itemData.revenue_loss!=null) && (parseFloat(itemData.revenue_loss)>=0))?'green':'red';
                        xtable += '<tr><td class="aleft">' + itemData.name + '</td><td class="acenter">' + itemData.position + '</td><td>' + itemData.amount + '</td><td>' + (itemData.operation_value == null ? '-' : itemData.operation_value + ' <? echo(dictionary("р.")); ?>') + '</td><td>' + (itemData.total_first == null ? '-' : itemData.total_first + ' <? echo(dictionary("р.")); ?>') + '</td><td>' + (itemData.value == null ? '-' : itemData.value + ' <? echo(dictionary("р.")); ?>') + '</td><td class="'+td_class+'">' + (itemData.revenue_loss == null ? '-' : itemData.revenue_loss + ' <? echo(dictionary("р.")); ?>') + '</td><td>' + (itemData.shift == null ? '-' : itemData.shift + '%') + '</td><!-- <td>' + (itemData.isin == null ? '-' : itemData.isin) + '</td> --></tr>';
                        console.log('added table row');
                    }
                });
                $("#table1 tbody").html(xtable);
                $("#table1 tfoot").html(xtable2);
                // сообщаем плагину, что нужно обновить отображение
                $("#table1").trigger("update");
                $("#table-container").slideDown(600);
                
                // устанавливаем колонки и направление для сортировки. в этом случае сортируем по третьей и первой колонкам
                //var sorting = [[4,1][0,0]];
                //var sorting = [[4,1]];
                // сортируем
                //$("#table1").trigger("sorton",[sorting]);
            });
        });
    }
    }
    function renderSeriesChart(micex,portfolio) {
    
            $('#containergraph').highcharts('StockChart', {  
                chart: {
                    events: {
                        click: function(event) {
                           // var fetchDate = Highcharts.dateFormat('%Y-%m-%d', event.xAxis[0].value);
                           // appendTable(fetchDate);
                           // renderPieChart(fetchDate);
                        }
                    },
                    borderColor: '#cdcdcd',
                    borderWidth: 1,
                    borderRadius: 0
                },
                plotOptions: {
                    series: {
                        events: {
                            click: function(event) {
                                //appendTable(fetchDate);
                                //var fetchDate = Highcharts.dateFormat('%Y-%m-%d', event.xAxis[0].value);
                                //alert('value');
                            }
                        },
                        cursor: 'pointer',
                        allowPointSelect: true,
                        point: {
                            events: {
                                click: function(event) {
                                  var fetchDate = Highcharts.dateFormat('%Y-%m-%d', event.point.x);
                                  appendTable(fetchDate, event.point.last);
                                  renderPieChart(fetchDate);
                                }
                            }
                        }
                    },
                    area: {
                        point: {
                            events: {
                                click: function(event) {
                                    //appendTable(Highcharts.dateFormat('%Y-%m-%d', event.xAxis[0].value));
                                }
                            }
                        }
                    }
                },
                rangeSelector: {
                    selected: 2
                },
                title: {
                    text: ''
                },
                yAxis: [{
                    title: {
                        text: "<? echo(dictionary('Стоимость портфеля').' %'); ?>",
                        style: {
                            color: 'green'
                        },
                        margin: 65
                    },
                    lineWidth: null,
                    labels: {
                        formatter: function() {
                            if (this.value > 1000) {
                                return (this.value / 1000).toFixed(0) + ' <? echo("%"); ?>';
                            } else {
                                return this.value + ' <? echo("%"); ?>';
                            }
                        },
                        x: -55
                    }
                },{
                    title: {
                        text: "<? echo(dictionary('Индекс ММВБ').' %'); ?>",
                        style: {
                            color: 'red'
                        },
                        margin: 30
                    },
                    lineWidth: null,
                    opposite: true,
                    labels: {
                        x: 40
                    }
                }],
                series: [{
                    name: "<? echo(dictionary('Стоимость портфеля')); ?>",
                    index: 1,
                    data: portfolio,
                    type: 'spline',
                    color: 'green',
                    marker : {
                        enabled : true,
                        radius : 5,
                        states: {
                          select: {
                            fillColor: "red",
                            lineColor: "green"        
                          }
                        }
                    }/*,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' %'
                    }*/
                },{
                    name: "<? echo(dictionary('Индекс ММВБ')); ?>",
                    index: 0,
                    data: micex,
                    type: 'spline',
                    color: 'red',
                    yAxis: 0,
                    marker : {
                        enabled : true,
                        radius : 5,
                        states: {
                          select: {
                            enabled: false
                          }
                        }
                    }/*,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' %'
                    }*/
                }],
                tooltip: {
                  useHTML: true,
                  valueDecimals: 2,
                  headerFormat: '<small>{point.key}</small>',
                  pointFormat: '<br><font style="color: {series.color}">{series.name}: </font><b>{point.value} </b> ({point.y} %)'
                }
           });
    }
    
    /*$('#table1').tablesorter({
        widgets: ['zebra'],
        widgetZebra: { css: ['d0', 'd1']},
        headers: {
            0: {sorter: "text"},
            1: {sorter: "text"},
            2: {sorter: "digit"},
            3: {sorter: "digit"},
            4: {sorter: "digit"},
            5: {sorter: "digit"},
            6: {sorter: "digit"},
            7: {sorter: "percent"},
            8: {sorter: "text"}
        }
    });*/ 
    Highcharts.setOptions({
        <? if ($this->site_lang=='ru') { ?>
        lang: {
            rangeSelectorZoom: 'Маcштаб',
            rangeSelectorFrom: 'От',
            rangeSelectorTo: 'До',
            thousandsSep: ' ',
            downloadJPEG: 'Сохранить график в JPG',
            downloadPDF: 'Сохранить график в PDF',
            downloadPNG: 'Сохранить график в PNG',
            downloadSVG: 'Сохранить график в SVG (вектор)',
            printChart: 'Версия для печати',
            weekdays: ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            shortMonths: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек']
        
        },
        <? } ?>
        global: {
            useUTC: false
        },
        rangeSelector: {
            buttons: [
                { // Настраиваем свои кнопки для временных интервалов
                type: 'day', // Тип интервала: 'millisecond', 'second', 'minute', 'day', 'week', 'month', 'ytd' (текущий год), 'year' and 'all'
                count: 7, // Количество единиц указанных в type
                text: <?=($this->site_lang=='ru') ? "'Неделя'" : "'Week'"?>
                },{
                type: 'month',
                count: 1,
                text: <?=($this->site_lang=='ru') ? "'Месяц'" : "'Month'"?>
                },{
                type: 'month',
                count: 3,
                text: '3' + <?=($this->site_lang=='ru') ? "' мес'" : "' months'"?>
                },{
                type: 'month',
                count: 6,
                text: '6' + <?=($this->site_lang=='ru') ? "' мес'" : "' months'"?>
                },{
                type: 'ytd',
                count: 1,
                text: <?=($this->site_lang=='ru') ? "'Год'" : "'Year'"?>
                },{
                type: 'all',
                text: <?=($this->site_lang=='ru') ? "'Всё'" : "'All'"?>
                }], 
            inputEnabled: false,
            inputDateFormat: '%d.%m.%Y',
            inputEditDateFormat: '%d.%m.%Y',
            buttonTheme: {
                width: 66
            }
        }
    });
    var seriesOptions = [],
    yAxisOptions = [],
    seriesCounter = 0,
    colors = Highcharts.getOptions().colors;
    var DDDate = getFormattedDate();
    //var DDDate = getFormattedDate('number','2013-11-11');
    //var micex_url = '/pages/analitika/modelnuyi-portfel.html?type=chart&series=micex&date='+DDDate+'&callback=?';
    var micex_url = '<? echo($this->full_page_url) ?>?type=chart&series=micex&date='+DDDate+'&callback=?';
    $.getJSON(micex_url, function(micex) {
    
        //var portfolio_url = '/pages/analitika/modelnuyi-portfel.html?type=chart&series=portfolio&date='+DDDate+'&callback=?';
        var portfolio_url = '<? echo($this->full_page_url) ?>?type=chart&series=portfolio&date='+DDDate+'&callback=?';
        
        $.getJSON(portfolio_url, function(portfolio) {

          //var lastSeriesElement = portfolio[ Object.keys(portfolio).sort().pop() ];
          //var lastSeriesElementDate = new Date(lastSeriesElement[0]);

          if (portfolio && portfolio.lenght>0) {
            var lastSeriesElementDate = new Date(portfolio[portfolio.length-1].x);
            $("#date-selected").html(getFormattedDate('text',lastSeriesElementDate.getFullYear() + '-' + (lastSeriesElementDate.getMonth()+1) + '-' + lastSeriesElementDate.getDate()));
          }
          // Create the chart
          renderSeriesChart(micex,portfolio);
            
        });
    });
    appendTable();
    renderPieChart();
});
//]]>
</script>
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>  
<h3><? echo(dictionary('Динамика портфеля и сравнительный индекс')); ?></h3>

<script src="/js/highstock/highstock.js"></script>
<!--<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>-->
<script src="/js/highstock/modules/exporting.js"></script>
<div id="point-note">При выборе точки на графике вам будет доступна история портфеля.</div>
<div id="containergraph"></div>

<p id="date-status"><span id="loading"><? echo(dictionary('Загрузка')); ?>&hellip;</span><span id="date-note"><span class="archive-note">Внимание!&nbsp;</span><? echo(dictionary('Указаны данные на')); ?> <span id="date-selected">&hellip;</span></span></p>
<div id="table-container">
    <table class="tablesorter-blue tablesorter all-to-right" id="table1">
    <thead>
        <tr class="tablesorter-headerRow">
            <th class="aleft header tablesorter-header"><? echo(dictionary('Финансовый инструмент')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Позиция')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Количество')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Цена сделки')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Сумма')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Текущая цена')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Прибыль/убыток')); ?></th>
            <th class="header tablesorter-header"><? echo(dictionary('Изменение')); ?></th>
            <!-- <th class="header tablesorter-header"><? echo(dictionary('ISIN')); ?></th> -->
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot class="tablesorter-no-sort">
    </tfoot>
    </table>
    <p class="table-notice"><span class="info-sign">i</span>&nbsp;<i>Данные предоставляются Московской Биржей</i></p><br style="clear:both"/>
    <p><span><i>Отрицательное значение в&nbsp;поле &laquo;денежные средства&raquo; отражает инвестиции за&nbsp;счет привлеченных средств.</i></span></p>
</div>
<h3><? echo(dictionary('Структура портфеля')); ?></h3>
<div id="containerpie" style="min-width: 540px; height: 400px; margin: 0 auto"></div>