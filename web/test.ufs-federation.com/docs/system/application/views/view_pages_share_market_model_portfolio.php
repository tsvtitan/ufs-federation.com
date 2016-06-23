<? if($data){ ?>
<? echo($data->content); ?>

<div class="column_content">
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

           <? if(count($data->graph[$i])>1){ ?>

                <div class="linechart">	 
                    <div id="graph<? echo(($i>1)?$i:''); ?>"><span class="no-graf">Loading graph...</span></div>
                </div> 

                <script type="text/javascript">
                var <? echo('myChart'.$i); ?> = new JSChart('graph<? echo(($i>1)?$i:''); ?>', 'line');

                /******************************/
                var <? echo('myData'.$i); ?> = new Array(<? 
                                                          $x=0;
                                                            foreach($data->graph[$i] as $item){
                                                               if($x==0){
                                                                   $x=1;     
                                                               }else{ 
                                                                   echo(',');                                                                              
                                                               }
                                                             echo('['.$item->timestamp.','.$item->cost_sum.']');
                                                            } 
                                                         ?>);

                <? $start_date=reset($data->graph[$i]); $end_date=end($data->graph[$i]); ?>

                <? echo('myChart'.$i); ?>.setTitle('<? echo(@$start_date->date_limit.' - '.@$end_date->date_limit); ?>');

                <? 
                   foreach($data->graph[$i] as $item){
                     echo('myChart'.$i.'.setTooltip(['.$item->timestamp.', \''.$item->date.' - '.$item->cost_sum.'\']);'."\n");
                   } 
                ?>

                <? 
                   foreach($data->graph[$i] as $item){
                     //echo('myChart'.$i.'.setLabelX(['.$item->timestamp.', \''.$item->date.'\']);'."\n");
                     echo('myChart'.$i.'.setLabelX(['.$item->timestamp.', \'\']);'."\n");
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

<? } ?>