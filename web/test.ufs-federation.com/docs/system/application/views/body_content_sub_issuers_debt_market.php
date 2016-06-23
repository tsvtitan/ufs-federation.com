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
<script type="text/javascript" src="/js/NEWjquery.tablesorter.widgets.js"></script>
<style>

/* tables */
table.tablesorter {
	background-color: #cdcdcd;
	margin: 0;
	font-size: 8pt;
	width: 100%;
	text-align: left;
  border-spacing: 0;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
	background-color: #e0e6e7;
	border: 0 /* 1px solid #fff */;
	font-size: 8pt;
	padding: 8px 0.5em /* 4px */;
} 
table.tablesorter thead tr .header {
	background-image: url(bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}
table.tablesorter tbody td {
	color: #3d3d3d;
	padding: 8px 0.5em /* 4px */;
	background-color: #FFF;
	vertical-align: top;
}
table.tablesorter tbody tr.odd td {
	background-color:#f0f0f6;
}
table.tablesorter thead tr .headerSortUp {
	background-image: url(asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
  background-color: #004577;
  color: #fff;
}
table.tablesorter tbody tr th {
  background-color: #cdcdcd;
  text-align: center;
  font-weight: bold;
  padding: 8px 0.5em;
}
/* tabs */
#content-text {
  min-height: 30px;
}
body.emitenti-dolgovogo-rinka div.section-kalendar-statistiki {
  float: left;
  clear: none;
  width: 810px;
  margin-left: 40px;
  overflow: hidden;
}
body.emitenti-dolgovogo-rinka div.section-kalendar-statistiki #tabs .updated_block var {
  padding-top: 14px;
  padding-bottom: 11px;
}
body.emitenti-dolgovogo-rinka .content-tab {
  padding-left: 0;
}
.table-calc th, .table-calc td, body.emitenti-dolgovogo-rinka div.section table.table-calc thead th, body.emitenti-dolgovogo-rinka div.section table.table-calc tbody td {
  padding-left: 5px;
  padding-right: 3px;
  font-size: 11px;
}
body.emitenti-dolgovogo-rinka div.section-kalendar-statistiki #nav_na_grafikah li a span {
  font-size: 18px;
}
.tablesorter-blue {
    margin: 0;
    border: 0;
    font-size: 12px;
}
</style>
<div class="section-kalendar-statistiki">
    <div id="content_s"> 
        <div class=" clearfix" id="tabs">
            <div class="updated_block"><var><? echo(dictionary('Обновление данных')); ?>:<br><? echo($last_update); ?></var></div>
            <ul class="kalendar-statistiki" id="nav_na_grafikah">
                <li><a href="#content_tab_1" rev="content_s" rel="content_tab_1"><span><? echo(dictionary('Российские еврооблигации')); ?></span></a></li>
                <? /* <li><a href="#content_tab_2" rev="content_s" rel="content_tab_2"><span><? echo(dictionary('Рублёвые облигации')); ?></span></a></li> */ ?>
                <li><a href="#content_tab_3" rev="content_s" rel="content_tab_3"><span><? echo(dictionary('Иностранные еврооблигации')); ?></span></a></li>
            </ul>
            <div id="content_tab_1" class="content-tab">
                <div class="section">
                    <span class="fict">
                        <?if(!empty($euro_names)):?>
                        <select name="name" id="n_bonds">
                            <option value=""><? echo(dictionary('Наименования')); ?></option>
                            <?foreach($euro_names as $item):?>
                            <option value="<?echo($item->name)?>"><?echo($item->name)?></option>
                            <?endforeach;?>
                        </select>
                        <input type="hidden" name="type" value="euro" />
                        <?endif;?>
                        
                        <?if(!empty($euro_currency)):?>
                            <select name="euro_currency" id="value">
                                <option value=""><? echo(dictionary('Валюта')); ?></option>
                                <?foreach($euro_currency as $item):?>
                                <option><?echo($item->currency)?></option>
                                <?endforeach;?>
                            </select>
                        <?endif;?>
                        
                        <?if(!empty($euro_rating_sp) or !empty($euro_rating_moodys) or !empty($euro_rating_fitch)):?>
                            <select name="euro_rating" id="value2">
                                <option value=""><? echo(dictionary('Рейтинг')); ?></option>
                                <?foreach($euro_rating_sp as $item):?>
                                <option>S&amp;P <?echo($item->rating_sp)?></option>
                                <?endforeach;?>

                                <?foreach($euro_rating_moodys as $item):?>
                                <option>Moody&#39;s <?echo($item->rating_moodys)?></option>
                                <?endforeach;?>

                                <?foreach($euro_rating_fitch as $item):?>
                                <option>Fitch <?echo($item->rating_fitch)?></option>
                                <?endforeach;?>
                            </select>
                        <?endif;?>
                        
                    </span>
                    <? if($data->euro){ ?>
                        <table class="tablesorter hasStickyHeaders table-calc" id="table1">
                            <thead>
                                <tr class="tablesorter-headerRow">
                <th class="header"><? echo(dictionary('Эмитент')); ?></th>
                <th class="header"><? echo(dictionary('Погашение')); ?></th>
                <th class="header"><? echo(dictionary('ISIN')); ?></th>
                <th class="header"><? echo(dictionary('Валюта')); ?></th>
                <th class="header"><? echo(dictionary('Цена закрытия')); ?></th>
                <th class="header"><? echo(dictionary('Доходность, (%)')); ?></th>
                <th class="header"><? echo(dictionary('Дюрация, (лет)')); ?></th>
                <th class="header"><? echo(dictionary('Купон, (%)')); ?></th>
                <!-- <th><? echo(dictionary('Следующий купон')); ?></th> -->
                <th class="header"><? echo(dictionary('Объем выпуска, млн.')); ?></th>
                <!-- <th><? echo(dictionary('Периодичность выплат, раз в год')); ?></th> -->
                <th class="header ratings">
                    <span><? echo(dictionary('Рейтинги')); ?></span>
                    <div class="type_ratings"><span>S&amp;P</span><span>Moody&#39;s</span><span>Fitch</span></div>
                </th>
              </tr>
            </thead>
                            
                            <? foreach ($data->euro_sectors as $sector) { ?>
                                <tbody class="tablesorter-infoOnly"><tr class="sector"><td colspan="10" style="text-align: center"><? echo($sector->name); ?></td></tr></tbody>
                                <tbody>
                                    <? foreach ($sector->items as $item) { ?>
                                    <tr>
                                        <td><a target="_blank" href="/search.html?text=<? echo($item->name); ?>"><? echo($item->name); ?></a></td>
                                        <td><? echo($item->maturity_date); ?></td>
                                        <td><? echo($item->isin); ?></td>
                                        <td><? echo($item->currency); ?></td>
                                        <td><? echo($item->closing_price); ?></td>
                                        <td class="revenue"><? echo($item->income); ?></td>
                                        <td><? echo($item->duration); ?></td>
                                        <td><? echo($item->rate); ?></td>
                                        <!-- <td><? echo($item->next_coupon); ?></td> -->
                                        <td><? echo($item->volume); ?></td>
                                        <!-- <td><? echo($item->payments_per_year); ?></td> -->
                                        <td><div class="type_ratings"><span class="rat-1st"><? echo($item->rating_sp); ?></span><span class="rat-2nd"><? echo($item->rating_moodys); ?></span><span class="rat-3rd"><? echo($item->rating_fitch); ?></span></div></td>
                                    </tr>
                                    <? } ?>
                                </tbody>
                            <? } ?>
                        </table>
                    <? } ?>
                    <div class="show_more"><? echo(dictionary('Смотреть еще')); ?></div>	 
                </div>
            </div>
            
            <? /* <div id="content_tab_2" class="content-tab" style="display:none">
                <div class="section">
                    <span class="fict">
                        <?if(!empty($rur_names)):?>
                        <select name="name" id="n_bonds_">
                            <option value=""><? echo(dictionary('Наименования')); ?></option>
                            <?foreach($rur_names as $item):?>
                            <option value="<?echo($item->name)?>"><?echo($item->name)?></option>
                            <?endforeach;?>
                        </select>
                        <input type="hidden" name="type" value="rur" />
                        <?endif;?>

                        <?if(!empty($rur_currency)):?>
                        <select name="rur_currency" id="value_">
                            <option value=""><? echo(dictionary('Валюта')); ?></option>
                            <?foreach($rur_currency as $item):?>
                            <option><?echo($item->currency)?></option>
                            <?endforeach;?>
                        </select>
                        <?endif;?> 

                        <?if(!empty($rur_rating_sp) or !empty($rur_rating_moodys) or !empty($rur_rating_fitch)):?>
                        <select name="rur_rating" id="value2_">
                            <option value=""><? echo(dictionary('Рейтинг')); ?></option>
                            <?foreach($rur_rating_sp as $item):?>
                            <option>S&amp;P <?echo($item->rating_sp)?></option>
                            <?endforeach;?>
                            
                            <?foreach($rur_rating_moodys as $item):?>
                            <option>Moody&#39;s <?echo($item->rating_moodys)?></option>
                            <?endforeach;?>

                            <?foreach($rur_rating_fitch as $item):?>
                            <option>Fitch <?echo($item->rating_fitch)?></option>
                            <?endforeach;?>
                        </select>
                        <?endif;?>
                    </span>
                    
                    <? if($data->rur){ ?>
                        <table class="tablesorter hasStickyHeaders table-calc" id="table2">
                            <thead>
                                <tr class="tablesorter-headerRow">
                                    <th class="header"><? echo(dictionary('Эмитент')); ?></th>
                                    <th class="header"><? echo(dictionary('Погашение')); ?></th>
                                    <th class="header"><? echo(dictionary('ISIN')); ?></th>
                                    <th class="header"><? echo(dictionary('Валюта выпуска')); ?></th>
                                    <th class="header"><? echo(dictionary('Цена закрытия, (% от номинала)')); ?></th>
                                    <th class="header"><? echo(dictionary('Доходность, (%)')); ?></th>
                                    <th class="header"><? echo(dictionary('Дюрация, (лет)')); ?></th>
                                    <th class="header"><? echo(dictionary('Ставка купона, (%)')); ?></th>
                                    <!-- <th><? echo(dictionary('Следующий купон')); ?></th> -->
                                    <th class="header"><? echo(dictionary('Объем размещения, млн.')); ?></th>
                                    <!-- <th><? echo(dictionary('Периодичность выплат, раз в год')); ?></th> -->
                                    <th class="header ratings">
                                        <span><? echo(dictionary('Рейтинги')); ?></span>
                                        <div class="type_ratings"><span>S&amp;P</span><span>Moody&#39;s</span><span>Fitch</span></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <? foreach($data->rur as $item){ ?>
                                <tr>
                                    <td><? echo($item->name); ?></td>
                                    <td><? echo($item->maturity_date); ?></td>
                                    <td><? echo($item->isin); ?></td>
                                    <td><? echo($item->currency); ?></td>
                                    <td><? echo($item->closing_price); ?></td>
                                    <td class="revenue"><? echo($item->income); ?></td>
                                    <td><? echo($item->duration); ?></td>
                                    <td><? echo($item->rate); ?></td>
                                    <!-- <td><? echo($item->next_coupon); ?></td> -->
                                    <td><? echo($item->volume); ?></td>
                                    <!-- <td><? echo($item->payments_per_year); ?></td> -->
                                    <td><div class="type_ratings"><span><? echo($item->rating_sp); ?></span><span><? echo($item->rating_moodys); ?></span><span><? echo($item->rating_fitch); ?></span></div></td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    <? } ?>  
                    <div class="show_more"><? echo(dictionary('Смотреть еще')); ?></div>
                </div>
            </div>
            */ ?>
               <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a> 
            <div id="content_tab_3" class="content-tab">
                <div class="section">
                    <span class="fict">
                        <?if(!empty($int_euro_names)):?>
                        <select onchange="document.getElementById('int_euro_from').submit();" name="int_euro['value']" id="n_bonds__">
                            <option value=""><? echo(dictionary('Наименования')); ?></option>
                            <?foreach($int_euro_names as $item):?>
                            <option value="<?echo($item->name)?>"><?echo($item->name)?></option>
                            <?endforeach;?>
                        </select>
                        <input type="hidden" name="int_euro['type']" value="int_euro" />
                        <input type="hidden" name="int_euro['field']" value="name" />
                        <?endif;?>
                        
                        <?if(!empty($int_euro_currency)):?>
                            <select name="int_euro_currency" id="value__">
                                <option value=""><? echo(dictionary('Валюта')); ?></option>
                                <?foreach($int_euro_currency as $item):?>
                                <option><?echo($item->currency)?></option>
                                <?endforeach;?>
                            </select>
                        <?endif;?>
                        
                        <?if(!empty($int_euro_rating_sp) or !empty($int_euro_rating_moodys) or !empty($int_euro_rating_fitch)):?>
                        <select name="int_euro_rating" id="value2__">
                            <option value=""><? echo(dictionary('Рейтинг')); ?></option>
                            <?foreach($int_euro_rating_sp as $item):?>
                            <option>S&amp;P <?echo($item->rating_sp)?></option>
                            <?endforeach;?>              
                            
                            <?foreach($int_euro_rating_moodys as $item):?>
                            <option>Moody&#39;s <?echo($item->rating_moodys)?></option>
                            <?endforeach;?>              
                            
                            <?foreach($int_euro_rating_fitch as $item):?>
                            <option>Fitch <?echo($item->rating_fitch)?></option>
                            <?endforeach;?>    
                        </select>
                        <?endif;?>
                    </span>
                    
                    <? if($data->int_euro){ ?>
                        <table class="tablesorter hasStickyHeaders table-calc" id="table3">
                            <thead>
                                <tr class="tablesorter-headerRow">
                <th class="header"><? echo(dictionary('Эмитент')); ?></th> 
                <th class="header"><? echo(dictionary('Погашение')); ?></th>
                <th class="header"><? echo(dictionary('ISIN')); ?></th>
                <th class="header"><? echo(dictionary('Валюта')); ?></th>
                <th class="header"><? echo(dictionary('Цена закрытия')); ?></th>
                <th class="header"><? echo(dictionary('Доходность, (%)')); ?></th>
                <th class="header"><? echo(dictionary('Дюрация, (лет)')); ?></th>
                <th class="header"><? echo(dictionary('Купон, (%)')); ?></th>
                <!-- <th><? echo(dictionary('Следующий купон')); ?></th> -->
                <th class="header"><? echo(dictionary('Объем выпуска, млн.')); ?></th>
                <!-- <th><? echo(dictionary('Периодичность выплат, раз в год')); ?></th> -->
                <th class="header ratings">
                    <span><? echo(dictionary('Рейтинги')); ?></span>
                    <div class="type_ratings"><span>S&amp;P</span><span>Moody&#39;s</span><span>Fitch</span></div>
                </th>
              </tr> 
            </thead>
                                </tr>
                            </thead>
                            
                            <? foreach ($data->int_euro_sectors as $sector) { ?>
                                <tbody class="tablesorter-no-sort"><tr class="sector"><td colspan="10" style="text-align: center"><? echo($sector->name); ?></td></tr></tbody>
                                <tbody>
                                    <? foreach ($sector->items as $item) { ?>
                                    <tr>
                                        <td><a target="_blank" href="/search.html?text=<? echo($item->name); ?>"><? echo($item->name); ?></a></td>
                                        <td><? echo($item->maturity_date); ?></td>
                                        <td><? echo($item->isin); ?></td>
                                        <td><? echo($item->currency); ?></td>
                                        <td><? echo($item->closing_price); ?></td>
                                        <td class="revenue"><? echo($item->income); ?></td>
                                        <td><? echo($item->duration); ?></td>
                                        <td><? echo($item->rate); ?></td> 
                                        <!-- <td><? echo($item->next_coupon); ?></td> -->
                                        <td><? echo($item->volume); ?></td>
                                        <!-- <td><? echo($item->payments_per_year); ?></td> -->
                                        <td><div class="type_ratings"><span><? echo($item->rating_sp); ?></span><span><? echo($item->rating_moodys); ?></span><span><? echo($item->rating_fitch); ?></span></div></td>
                                    </tr>
                                    <? } ?>
                                </tbody>
                            <? } ?>
                        </table>
                    <? } ?> 
                    <div class="show_more"><? echo(dictionary('Смотреть еще')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function() 
      { 
        $('#table1, #table3').tablesorter({
          widgets: ['zebra', 'stickyHeaders'],
          
          
          
          widgetOptions: {
          // extra css class name applied to the sticky header row (tr) - changed in v2.11
          stickyHeaders : '',
          // adding zebra striping, using content and default styles - the ui css removes the background from default
          // even and odd class names included for this demo to allow switching themes
          zebra   : ["ui-widget-content even", "ui-state-default odd"],
          //zebra : [ "normal-row", "alt-row" ],
          // use uitheme widget to apply defauly jquery ui (jui) class names
          // see the uitheme demo for more details on how to change the class names
          uitheme : 'jui'
          }
          
          //debug: true
        });
        
    } 
); 
</script>