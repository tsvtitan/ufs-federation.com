<? if(isset($data)&&isset($lang)) {
   /*if($lang=='ru')*/ { ?>
<body lang="ru-ru" style="padding: 0; margin: 0;">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,300&amp;subset=latin,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css">
  <div style="width: 100%; background-color: #e9eaed;">
    <div style="min-width: 640px; max-width: 800px; padding: 0; margin: auto; padding-top: 20px; font: normal 13px Helvetica, open sans, sans-serif, arial, tahoma; color: #464646;">
      <table style="width: 100%; font: normal 13px Helvetica, open sans, sans-serif, arial, tahoma; color: #464646; margin-bottom: 30px" cellspacing="0">
         <tbody>
                 <tr> 
                         <td style="width: 33%; vertical-align: middle;">
             <a target="_blank" href="http://ru.ufs-federation.com/"><img src="http://www.ufs-federation.com/img/email/logo.png" width="108" height="80" border="0"/></a>
           </td>
           <td style="width: 34%; text-align: center; vertical-align: middle;">
             <h3 style="margin: 0; font-size: 24px;">
               <a target="_blank" href="http://ru.ufs-federation.com/" style="text-decoration: none; color: #08509d"><nobr class="phone"><? echo(dictionary('8-800 234-0202')); ?></nobr></a>
             </h3>
             <p style="margin: 0; color: #859c9f"><? echo($this->lang->line('free_call_from_Russia')); ?></p>
           </td>
           <td style="width: 33%; text-align: right; vertical-align: middle; font-size: 14px; font-weight: normal; color: #859c9f">
             <nobr class="phone">+7 (495) 781-73-00</nobr><br/>
             <nobr class="phone">+7 (495) 781-73-02</nobr><br/>
             <nobr class="phone">+7 (495) 781-02-02</nobr>
           </td>
                 </tr>
         </tbody>
     </table>

     <div style="background-color: #fff; border: 1px solid; border-color: #e5e6e9 #dfe0e4 #d0d1d5; padding: 8px 20px 20px; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
       <h2 style="color: #859c9f; font-size: 22px; font-weight: normal; text-align: left; margin-bottom: 0"><? echo($date); ?> / <? echo($this->lang->line('trade_idea_full')) ?></h2>
  <? $group_name = '';
     foreach($data as $item) { ?>
        <? if($item->group_name != $group_name) {
           if($group_name != "") { ?>
            </tbody>
          </table>
        <? } ?>
        <h3 style="font-size: 22px; font-weight: normal; text-align: left;"><? echo($item->group_name) ?></h3> 
        <table border="0" cellspacing="0" cellpadding="4" style="padding: 0px; font-size: 11px; color: #44474d" class="trade-ideas trade-ideas2 ti-corner">
          <colgroup>
            <col style="width: 24%;">
            <col style="width: 46%;">
            <col style="width: 12%;">
            <col style="width: 9%;">
            <col style="width: 9%;">
          </colgroup>
          <thead style="margin-top: 0px;">
            <tr>
              <th rowspan="2" style="background-color: #e0e6e7; text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); text-align: center; font-weight: bold;"><? echo($this->lang->line('admin_trade_ideas_tool')); ?></th>
              <th rowspan="2" style="background-color: #e0e6e7; text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); text-align: center; font-weight: bold;"><? echo($this->lang->line('admin_issuers_debt_market_comment')); ?></th>
              <th rowspan="2" style="background-color: #e0e6e7; text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); text-align: center; font-weight: bold;">&nbsp;</th>
              <th colspan="2" style="background-color: #e0e6e7; text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); text-align: center; font-weight: bold;"><? echo($this->lang->line('admin_trade_ideas_level')); ?></th>
            </tr>
            <tr>
              <th style="background-color: #e0e6e7; text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); text-align: center; font-weight: bold; font-size: 11px;"><? echo($this->lang->line('admin_trade_ideas_level_open')); ?></th>
              <th style="background-color: #e0e6e7; text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); text-align: center; font-weight: bold; font-size: 11px;"><? echo($this->lang->line('admin_trade_ideas_level_current')); ?></th>
            </tr>
          </thead>
          <tbody>
        <?   $group_name = $item->group_name;       
           }
           $item_level_values = $item->level_value->get();
           $item_level_value = null;
           foreach ($item_level_values as $item_level_value) {}
        ?>
            <tr>
              <th class="header acenter" style="background-color: #004577; color: #fff; text-shadow: none; text-align: center; font-weight: normal; white-space: nowrap;"><? 
                    $pos = strpos($item->caption, "VS");
                    if($pos) {
                      $caption = explode ("VS", $item->caption);
                      echo($caption[0]);  ?><span class="vs">VS</span><? echo($caption[1]);
                    }
                    else
                      echo($item->caption); 
                ?></th>
              <th class="header acenter" style="background-color: #004577; color: #fff; text-shadow: none; text-align: center; font-weight: normal; white-space: nowrap;">
                  <span class="target target2" title=""<? echo($item_level_value->data_Type->get()->data_type_name); ?>">
                    <img src="http://www.ufs-federation.com/img/email/target_ico.gif" width="16" height="16" alt="Цель" valign="bottom"/><?
                      echo($item_level_value->data_Type->get()->data_type_name); ?>:&nbsp;<? echo($item_level_value->level_value); ?></span>
              </th>
              <th class="header" style="background-color: #004577; color: #fff; text-shadow: none; text-align: center; font-weight: normal; white-space: nowrap;" colspan="3">
                  <span class="date" title="<? echo($this->lang->line('admin_trade_ideas_recommend_date')); ?>">
                    <img src="http://www.ufs-federation.com/img/email/calendar_ico.gif" width="16" height="16" alt="Дата" valign="bottom"/><? 
                      echo($this->lang->line('admin_trade_ideas_recommend_date')); ?>: <? 
                      $recommend_date = strtotime($item->recommend_date);
                      $recommend_date = date('d.m.Y', $recommend_date);/*date('j', $recommend_date).' '.global_model::months_ru_short($recommend_date).', '.date('Y',$recommend_date);*/
                      echo($recommend_date); ?></span>
              </th>
            </tr>
            <? switch($item->type_id ) {
                  case 1:
                    $is_first = true;
                    foreach ($item->tool->get() as $tool) {
                      $j = 1;
                      if($tool->operation == 'ПОКУПАТЬ' || $tool->operation == 'BUY') {
                        $idea_oper='buy'; $bcolor_odd='#dbf0d8'; $color='#318c23'; $bcolor_even='#ebfae8';
                      }
                      if($tool->operation == 'ПРОДАВАТЬ' || $tool->operation == 'SELL') {
                        $idea_oper='sell'; $bcolor_odd='#fae5e3'; $color='#e1261c'; $bcolor_even='#fff3f2';
                      }
                      if($tool->operation == 'ДЕРЖАТЬ' || $tool->operation == 'HOLD') {
                        $idea_oper='hold'; $bcolor_odd='#e3e8e8'; $color='#44474d'; $bcolor_even='#f2f7f7';
                      }
                      $tool = $item->tool->get(); ?>
            <tr class="<?  echo($idea_oper.' odd');  ?>" >
              <td class="<? echo($idea_oper); ?>" style="background-color: <? echo($bcolor_odd) ?>; 
                  color: <? echo($color) ?>; text-transform: uppercase; text-align: center; 
                  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); 
                  font-weight: bold;"><? echo($tool->operation); ?></td>
                  <?  if ($is_first) {
                        $is_first = false;  ?>
              <td class="comment" rowspan="5" style="vertical-align: top; background-color: #fffaf0; padding: 10px"><? echo($item->comment); ?></td>
              <td colspan="3" style="background-color: <? echo($bcolor_odd); ?>; text-align: center; vertical-align: middle; border-left: 0; border-right: 0;">
                <!-- <form name="form_trade_ideas" id="form_trade_ideas"  style="margin: 0; padding: 0" method="post">
                  <input name="trade_idea_id" value="< echo($item->id); ?>" type="hidden">
                  <!-- <input class="placeOrder buttonGrad" value="< echo($this->lang->line('add_to_cart')); ?>" type="submit">
                  <a href="#" class="cartorder">< echo($this->lang->line('add_to_cart')); ?></a>
                  <input type="hidden" name="order"/>
                </form> -->
                <a href="<? echo($this->base_url.$url.$item->id.'&order'); ?>" type="submit"><? echo($this->lang->line('add_to_cart')); ?></a>
              </td>
                  <?  } else { ?>
              <td colspan="3" style="background-color: <? echo($bcolor_odd) ?>;"></td>
                  <? } ?>
            </tr>      
                      <?  $j = 1; $row_color = '';
                          $level_value_types = $tool->level_value->select('data_type_id')->distinct()->get();
                          foreach ($level_value_types as $level_value_type ) {
                            $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                            if($j % 2 <> 0) { $idea_row = 'even'; $row_color = $bcolor_even; }
                            else { $idea_row = 'odd'; $row_color = $bcolor_odd; }
                            $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                      ?>
            <tr class="<? echo($idea_oper.' '.$idea_row);  ?>">
              <td class="acenter" style="background-color: <? echo($row_color); ?>; text-align: center">
                        <? switch($j) {
                              case 1: ?><? echo($tool->name); ?><?      break; 
                              case 2: ?><? echo($tool->isin); ?><?      break; 
                              case 3: ?><? echo($tool->rating); ?><?      break;
                            } ?></td>
              <td class="sub-header" style="background-color: <? echo($row_color); ?>; text-align: center"><? echo($level_values->data_type->get()->data_type_name); ?></td>
                        <?  foreach ($level_values as $level_value ) {  ?>
              <td class="acenter" style="background-color: <? echo($row_color); ?>; text-align: center"><? echo($level_value->level_value); ?></td>
                        <?  } ?>
            </tr>
                        <?  $j++; 
                          }
                    } ?>
            <tr class="<? echo('pusher '.$idea_oper.' odd'); ?>">
              <td></td>
              <td colspan="3"></td>
            </tr>
              <?
                    break;?>
              <?  case 2:
                    $is_first = true;
                    foreach ($item->tool->get() as $tool) {  
                      $j = 1;
                      if($tool->operation == 'ПОКУПКА' || $tool->operation == 'BUY') {
                        $idea_oper='buy'; $bcolor_odd='#dbf0d8'; $color='#318c23'; $bcolor_even='#ebfae8';
                      }
                      if($tool->operation == 'ПРОДАЖА' || $tool->operation == 'SELL') {
                        $idea_oper='sell'; $bcolor_odd='#fae5e3'; $color='#e1261c'; $bcolor_even='#fff3f2';
                      }

            ?>
            <tr class="<?  echo($idea_oper.' odd');  ?>" >
              <td class="<?  echo($idea_oper); ?>" style="background-color: <? echo($bcolor_odd) ?>;
                  color: <? echo($color) ?>; text-transform: uppercase; text-align: center; 
                  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1); 
                  font-weight: bold;"><? echo($tool->operation); ?></td>
                  <?  if ($is_first) { 
                        $is_first = false;  ?>
              <td class="comment" rowspan="10" style="vertical-align: top; background-color: #fffaf0; padding: 10px"><? echo($item->comment); ?></td>
              <td colspan="3" style="background-color: <? echo($bcolor_odd) ?>; text-align: center; vertical-align: middle; border-left: 0; border-right: 0;">
                <!-- <form name="form_trade_ideas" id="form_trade_ideas"  style="margin: 0; padding: 0" method="post">
                  <input name="trade_idea_id" value="< echo($item->id); ?>" type="hidden">
                  <!-- <input class="placeOrder buttonGrad" value="< echo($this->lang->line('add_to_cart')); ?>" type="submit">
                  <a href="#" class="cartorder">< echo($this->lang->line('add_to_cart')); ?></a>
                  <input type="hidden" name="order"/>
                </form> -->
                <a href="<? echo($this->base_url.$url.$item->id.'&order'); ?>" type="submit"><? echo($this->lang->line('add_to_cart')); ?></a>
              </td>
                  <?  } else { ?>
              <td colspan="3" style="background-color: <? echo($bcolor_odd) ?>;"></td>
                  <? } ?>
            </tr>
                      <?  $level_value_types = $tool->level_value->select('data_type_id')->distinct()->get();
                          foreach ($level_value_types as $level_value_type ) {
                            if($j % 2 <> 0) { $idea_row = 'even'; $row_color = $bcolor_even; }
                            else { $idea_row = 'odd'; $row_color = $bcolor_odd; }
                            $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                      ?>
            <tr class="<?  echo($idea_oper.' '.$idea_row);  ?>">
              <td class="acenter" style="background-color: <? echo($row_color); ?>; text-align: center">
                        <?  switch($j) {
                              case 1: ?><? echo($tool->name); ?><?      break; 
                              case 2: ?><? echo($tool->isin); ?><?      break; 
                              case 3: ?><? echo($tool->rating); ?><?      break;
                            } ?>
              </td>
              <td class="sub-header" style="background-color: <? echo($row_color); ?>;"><? echo($level_values->data_type->get()->data_type_name); ?></td>
                        <?  foreach ($level_values as $level_value ) {  ?>
              <td class="acenter" style="background-color: <? echo($row_color); ?>; text-align: center"><? echo($level_value->level_value); ?></td>
                        <?  } ?>
            </tr>
                        <?  $j++; 
                          }
                    } ?>
            <tr class="odd bold">
              <td style="background-color: #e3e8e8; text-align: center;"></td>
              <?  reset($item_level_values); ?>
              <td class="sub-header" style="background-color: #e3e8e8;"><? echo($item_level_values->data_type->get()->data_type_name); ?></td>
                        <?  foreach ($item_level_values as $item_level_value ) {  
                              if ($item_level_value->level_type_id <> 3) {
                          ?>
              <td class="acenter bold" style="background-color: #e3e8e8; text-align: center;"><? echo($item_level_value->level_value); ?></td>        
                              <?  }
                            } ?>
            </tr>
            <tr class="<? echo('pusher '.$idea_oper.' odd'); ?>">
              <td></td>
              <td colspan="3"></td>
            </tr>
                <?  break;
               }
            ?>
                        <? 

        } ?>
          </tbody>
        </table>
      </div>
        <?  $unsubscribe = 'В случае, если подписка была оформлена не Вами или Вы не желаете получать письма по ней, перейдите по этой ссылке';
            if ($lang=='en') {
              $unsubscribe = 'If it were not you who made the subscription and you do not want to receive letters on it, click the link';
            } elseif ($lang=='de') {
              $unsubscribe = 'Falls jemand anderer die Suskription gemacht hat und Sie sie nicht erhalten wollen, klicken auf den Link';
            }
            $message='<a style="font-size:12px" href="http://'.$lang.'.ufs-federation.com/subscribe.html?unsubscribe">'.$unsubscribe.'</a><br>';//$this->base_url
        ?>
      <div style="border-top: 1px solid; border-color: #e5e6e9; padding: 8px 20px;">
        <table style="width: 100%; margin: 20px auto 0; color: #464646; font: normal 13px Helvetica, open sans, sans-serif, arial, tahoma;" cellspacing="0">
          <tbody>
            <tr>
              <td style="text-align: left; vertical-align: top; padding: 0 0 20px;">
                <p style="color: #859c9f; font-size: 14px;">
                  <span style="color: #3a3b3b;"><? echo($this->lang->line('regards')); ?>,<br/>UFS Investment Company</span><br/><br/>
                  <nobr class="phone"><? echo(dictionary('8-800 234-0202')); ?></nobr> (<? echo($this->lang->line('free_call_from_Russia')); ?>)<br/>
                  <nobr class="phone">+7 (495) 781-73-00</nobr><br/>
                  <nobr class="phone">+7 (495) 781-73-02</nobr><br/>
                  <nobr class="phone">+7 (495) 781-02-02</nobr>
                  <br/><br/>
                  <? echo($message); ?>
                </p>
              </td>
              <td style="text-align: right; vertical-align: top">
                <a target="_blank" href="https://facebook.com/UFS.IC" title="Facebook"><img src="http://www.ufs-federation.com/img/email/ico-facebook.gif" width="32"></a>&nbsp;
                <a target="_blank" href="http://twitter.com/@ufs_ic" title="Twitter"><img src="http://www.ufs-federation.com/img/email/ico-twitter.gif" width="32"></a>&nbsp;
                <a target="_blank" href="http://youtube.com/user/UFSInvestmentCompany" title="Youtube"><img src="http://www.ufs-federation.com/img/email/ico-youtube.gif" width="32"></a>&nbsp;
                <a target="_blank" href="http://www.linkedin.com/company/ufs-investment-company" title="LinkedIn"><img src="http://www.ufs-federation.com/img/email/ico-linkedin.gif" width="32"></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
<? }} ?>