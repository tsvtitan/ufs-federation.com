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
 <div class="content_analitics_sharemarket">
    <div class="inner_content clearfix">
        <div class="analitics-shar_bg">
            <div class="column_content clearfix">
                <div class="column-two">
                   <h2><? echo(dictionary('Ежедневные комментарии')); ?></h2>
                    <? foreach($daily_comments as $item){ ?>
                    <div class="text">
                      <var><? echo($item->date); ?></var>
                      <a class="shot" href="<? echo($this->phpself.$item->page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
                      <? echo($item->short_content); ?>
                    </div>
                    <? } ?>
                   <? if(isset($daily_comments[0]->page_url)){ ?>
                    <a class="all-news" href="<? echo($this->phpself.$daily_comments[0]->page_url.$this->urlsufix); ?>"><? echo(dictionary('Все комментарии')); ?></a>
                   <? } ?>
                </div>

                <div class="column-two">
                   <h2><? echo(dictionary('Обзоры по эмитентам')); ?></h2>
                    <? foreach($reviews_issuers as $item){ ?>
                    <div class="text">
                      <var><? echo($item->date); ?></var>
                      <a class="shot" href="<? echo($this->phpself.$item->page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
                      <? echo($item->short_content); ?>
                    </div>
                    <? } ?>
                   <? if(isset($reviews_issuers[0]->page_url)){ ?>
                    <a class="all-news" href="<? echo($this->phpself.$reviews_issuers[0]->page_url.$this->urlsufix); ?>"><? echo(dictionary('Все обзоры')); ?></a>
                   <? } ?>
                </div>
            </div>
        </div>
        <? if($portfolio){ ?>
        <div class="modelnii-portfel clearfix">
            <h2><? echo(dictionary('Модельный портфель')); ?></h2>
            <div class="text">
                <p><? echo($portfolio->content); ?></p>
              <div class="grafiki">					  
                <ul id="nav_na_grafikah">
                  <li><a href="#" rev="content_s" rel="content_tab_1"><? echo(dictionary('1 месяц')); ?></a></li>
                  <li><a href="#" rev="content_s" rel="content_tab_2"><? echo(dictionary('3 месяца')); ?></a></li>
                  <li><a href="#" rev="content_s" rel="content_tab_3"><? echo(dictionary('1 год')); ?></a></li>
                  <li><a href="#" rev="content_s" rel="content_tab_4"><? echo(dictionary('5 лет')); ?></a></li>
                  <li><a href="#" rev="content_s" rel="content_tab_5"><? echo(dictionary('с даты формирования')); ?></a></li>
                </ul>
                <div id="content_s">                    
                  <? for($i=1;$i<=5;$i++){ ?>
                  <div id="content_tab_<? echo($i); ?>" class="content-tab">

                   <? if(count($portfolio->graph[$i])>1){ ?>
                      
                        <div class="linechart">	 
                            <div id="graph<? echo(($i>1)?$i:''); ?>"><span class="no-graf">Loading graph...</span></div>
                        </div> 
                      
                        <script type="text/javascript">
                        var <? echo('myChart'.$i); ?> = new JSChart('graph<? echo(($i>1)?$i:''); ?>', 'line');

                        /******************************/
                        var <? echo('myData'.$i); ?> = new Array(<? 
                                                                  $x=0;
                                                                    foreach($portfolio->graph[$i] as $item){
                                                                       if($x==0){
                                                                           $x=1;     
                                                                       }else{ 
                                                                           echo(',');                                                                              
                                                                       }
                                                                     echo('['.$item->timestamp.','.$item->cost_sum.']');
                                                                    } 
                                                                 ?>);

                        <? $start_date=reset($portfolio->graph[$i]); $end_date=end($portfolio->graph[$i]); ?>
                         
                        <? echo('myChart'.$i); ?>.setTitle('<? echo(@$start_date->date_limit.' - '.@$end_date->date_limit); ?>');
                        
                        <? 
                           foreach($portfolio->graph[$i] as $item){
                             echo('myChart'.$i.'.setTooltip(['.$item->timestamp.', \''.$item->date.' - '.$item->cost_sum.'\']);'."\n");
                           } 
                        ?>
                            
                        <? 
                           foreach($portfolio->graph[$i] as $item){
                             echo('myChart'.$i.'.setLabelX(['.$item->timestamp.', \''.$item->date.'\']);'."\n");
                           } 
                        ?>  
                        /******************************/

                        <? echo('myChart'.$i); ?>.setDataArray(<? echo('myData'.$i); ?>);
                        <? echo('myChart'.$i); ?>.setTitleColor('#859B9F');
                        <? echo('myChart'.$i); ?>.setTitleFontSize(8);
                        <? echo('myChart'.$i); ?>.setTitlePosition('right');
                        <? echo('myChart'.$i); ?>.setAxisNameX('');
                        <? echo('myChart'.$i); ?>.setAxisNameY('');
                        <? echo('myChart'.$i); ?>.setAxisColor('#c4cdcc');
                        <? echo('myChart'.$i); ?>.setAxisValuesColor('#949494');
                        <? echo('myChart'.$i); ?>.setAxisPaddingLeft(100);
                        <? echo('myChart'.$i); ?>.setAxisPaddingRight(120);
                        <? echo('myChart'.$i); ?>.setAxisPaddingTop(40);
                        <? echo('myChart'.$i); ?>.setAxisPaddingBottom(40);
                        <? echo('myChart'.$i); ?>.setAxisValuesNumberX(10);
                        <? echo('myChart'.$i); ?>.setShowXValues(false);
                        <? echo('myChart'.$i); ?>.setGridColor('#F1F4F4');
                        <? echo('myChart'.$i); ?>.setLineColor('#ffcc4e');
                        <? echo('myChart'.$i); ?>.setLineWidth(1);
                        <? echo('myChart'.$i); ?>.setFlagColor('#ffcc4e');
                        <? echo('myChart'.$i); ?>.setFlagColor('#ffcc4e');
                        <? echo('myChart'.$i); ?>.setTooltipFontSize(10);
                        <? echo('myChart'.$i); ?>.setFlagRadius(2);
                        <? echo('myChart'.$i); ?>.setFlagFillColor('#ffcc4e');
                        <? echo('myChart'.$i); ?>.setTooltipBackground('#ffcc4e');
                        <? echo('myChart'.$i); ?>.setTooltipBorder(' 0px solid #ffcc4e');
                        <? echo('myChart'.$i); ?>.setSize(650, 280);
                        <? echo('myChart'.$i); ?>.draw();
                    </script>
                    
                <? }else{ ?>
                    <div class="linechart empty">	 
                         <? echo(dictionary('Не хватает данных для построения графика')); ?> 
                    </div> 
                <? } ?>
                    
                  </div>
                  <? } ?>
                </div>
              </div>
            </div>
               <div class="block_list_formirovania">
                <dl class="list-formirovania">
                  <dt><? echo(dictionary('Начало формировния')); ?></dt>
                  <dd><? echo($portfolio->start_building); ?></dd>
                  <dt><? echo(dictionary('Начальная сумма')); ?></dt>
                  <dd class="large">$ <? echo($portfolio->initial_amount); ?></dd>
                  <dt><? echo(dictionary('Текущая стоимость')); ?></dt>
                  <dd class="large">$ <? echo($portfolio->current_value); ?></dd>
                  <dt><? echo(dictionary('Доходность')); ?></dt>
                  <dd class="geen"><? echo($portfolio->yield); ?><? echo(dictionary('% годовых')); ?></dd>
                  <dt><? echo(dictionary('Дюрация портфеля')); ?></dt>
                  <dd><? echo($portfolio->duration_of_portfolio); ?></dd>
                </dl>
              </div>
      </div>
      <? } ?>

      <div class="column_content clearfix">
            <div class="column-two">
               <h2><? echo(dictionary('Месячные обзоры')); ?></h2>
               <? foreach($month_reviews as $item){ ?>
                <div class="text">
                  <var><? echo($item->date); ?></var>
                  <a class="shot" href="<? echo($this->phpself.$item->page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
                  <p><? echo($item->short_content); ?></p>
                </div>
                <? } ?>
               <? if(isset($month_reviews[0]->page_url)){ ?>
                <a class="all-news" href="<? echo($this->phpself.$month_reviews[0]->page_url.$this->urlsufix); ?>"><? echo(dictionary('Все обзоры')); ?></a>
               <? } ?>
            </div>

            <div class="column-two">
                <h2><? echo(dictionary('Специальные комментарии')); ?></h2>
                <? foreach($special_comments as $item){ ?>
                <div class="text">
                  <var><? echo($item->date); ?></var>
                  <a class="shot" href="<? echo($this->phpself.$item->page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
                  <p><? echo($item->short_content); ?></p>
                </div>
                <? } ?>
               <? if(isset($special_comments[0]->page_url)){ ?>
                <a class="all-news" href="<? echo($this->phpself.$special_comments[0]->page_url.$this->urlsufix); ?>"><? echo(dictionary('Все комментарии')); ?></a>
               <? } ?>
            </div>
        </div>
    </div>
</div>