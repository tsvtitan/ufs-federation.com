<link rel="stylesheet" href="/css/trade-ideas.css"/>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
        <td align="center" colspan="2"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center" class="left_sidebar">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="left_content">
          <? if(isset($is_update)){ ?>
          <tr>
                <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_status')); ?></h5></td>
          </tr>
          <tr>
                <td class="item"><? echo($is_update); ?></td>
          </tr>
          <? } ?>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_search')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <form action="<? echo($this->phpself.$this->page_name); ?>" method="post">
                    <input type="text" class="text" name="search" value="<? echo(isset($_SESSION['search'])?stripslashes($_SESSION['search']):''); ?>" />
                    <br />
                    <input type="image" src="<? echo($this->base_url); ?>images/btn_ok_admin.png" />
                    <input type="hidden" name="form_search" value="1">
                </form>
            </td>
          </tr>
          <tr>
              <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_menu')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <ul class="sub_menu">
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/import_xls"><? echo($this->lang->line('admin_tpl_import_xls')); ?></a></li>
                    <!-- <li><a href="<? echo($this->phpself.$this->page_name); ?>/export_xls"><? echo($this->lang->line('admin_tpl_export_xls')); ?></a></li>  -->
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/make_review"><? echo($this->lang->line('admin_tpl_make_review')); ?></a></li>
                    <!-- <li><a href="<? echo($this->phpself.$this->page_name); ?>/add"><? echo($this->lang->line('admin_tpl_add')); ?></a></li> -->
                </ul>
            </td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_pages_cat_menu')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <ul class="nav_menu">
                    <li><a<? echo(($_SESSION['trade_idea_type']=='евро')?' class="sel"':''); ?> 
                            href="<? echo($this->phpself.$this->page_name); ?>/set_type/euro">Еврооблигации</a></li> 
                    <li><a<? echo(($_SESSION['trade_idea_type']=='рубл')?' class="sel"':''); ?> 
                            href="<? echo($this->phpself.$this->page_name); ?>/set_type/local">Локальный рынок</a></li>
                </ul>
            </td>
          </tr>
        </table>
    </td>
    <td valign="top" align="center">
    <? $counter = 0;
       if($data) { ?>
      <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content trade-ideas" style="width: 800px">

        <colgroup>
          <col style="width: 24%;">
          <col style="width: 46%;">
          <col style="width: 12%;">
          <col style="width: 9%;">
          <col style="width: 9%;">
        </colgroup>
        <thead style="position: static; margin-top: 0px; left: 682px; z-index: 1; top: 0px; width: 808px;" class="tableFloatingHeaderOriginal">
          <tr>
            <th rowspan="2" style="min-width: 0px; max-width: none;"><? echo($this->lang->line('admin_trade_ideas_tool')); ?></th>
            <th rowspan="2" style="min-width: 0px; max-width: none;"><? echo($this->lang->line('admin_issuers_debt_market_comment')); ?></th>
            <th rowspan="2" style="min-width: 0px; max-width: none;">&nbsp;</th>
            <th colspan="2" style="min-width: 0px; max-width: none;"><? echo($this->lang->line('admin_trade_ideas_level')); ?></th>
          </tr>
          <tr>
            <th style="min-width: 0px; max-width: none;"><? echo($this->lang->line('admin_trade_ideas_level_open')); ?></th>
            <th style="min-width: 0px; max-width: none;"><? echo($this->lang->line('admin_trade_ideas_level_current')); ?></th>
          </tr>
        </thead>
        <tbody>

          <? foreach ($data as $item) {
                  $item_level_values = $item->level_value->get();
                  $item_level_value = null;
                  foreach ($item_level_values as $item_level_value){}
              ?>
            <tr>
              <th class="header acenter"><? 
                    $pos = strpos($item->caption, "VS");
                    if($pos) {
                      $caption = explode ("VS", $item->caption);
                      echo($caption[0]);  ?><span class="vs">VS</span><? echo($caption[1]);
                    }
                    else
                      echo($item->caption); 
                ?></th>
              <th class="header acenter">
                  <span class="target target2" title="<? echo($item_level_value->data_Type->get()->data_type_name); ?>"><i></i>
                      <? echo($item_level_value->data_Type->get()->data_type_name); ?>:&nbsp;<? echo($item_level_value->level_value); ?>
                  </span>
              </th>
              <th class="header" style="text-align: right" colspan="3">
                  <span class="date" title="<? echo($this->lang->line('admin_trade_ideas_recommend_date')); ?>"><i></i><? 
                      echo($this->lang->line('admin_trade_ideas_recommend_date')); ?>: <? 
                      $recommend_date = strtotime($item->recommend_date);
                      $recommend_date = date('d.m.Y', $recommend_date);/*date('j', $recommend_date).' '.global_model::months_ru_short($recommend_date).', '.date('Y',$recommend_date);*/
                      echo($recommend_date); ?>
                  </span>
              </th>
            </tr>
            <? switch($item->type_id ) {
                  case 1:
                    $is_first = true;
                    foreach ($item->tool->get() as $tool) {
                      $j = 1;
                      if($tool->operation == 'ПОКУПАТЬ' || $tool->operation == 'BUY') $idea_oper='buy';
                      if($tool->operation == 'ПРОДАВАТЬ' || $tool->operation == 'SELL') $idea_oper='sell';
                      if($tool->operation == 'ДЕРЖАТЬ' || $tool->operation == 'HOLD') $idea_oper='hold';
                      $tool = $item->tool->get(); ?>
            <tr class="<?  echo($idea_oper.' odd');  ?>" >
              <td class="<? echo($idea_oper); ?>"><? echo($tool->operation); ?></td>
                  <?  if ($is_first) {
                        $is_first = false;  ?>
              <td class="comment" rowspan="5"><? echo($item->comment); ?></td>
                  <? } ?>
              <td colspan="3"></td>
            </tr>      
                      <?  $j = 1;
                          $level_value_types = $tool->level_value->select('data_type_id')->distinct()->get();
                          foreach ($level_value_types as $level_value_type ) {
                            $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                            if($j % 2 <> 0) $idea_row = 'even';
                            else $idea_row = 'odd';
                            $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                      ?>
            <tr class="<? echo($idea_oper.' '.$idea_row);  ?>">
              <td class="acenter"><? switch($j) {
                              case 1: ?><? echo($tool->name); ?><?      break; 
                              case 2: ?><? echo($tool->isin); ?><?      break; 
                              case 3: ?><? echo($tool->rating); ?><?      break;
                      } ?></td>
              <td class="sub-header"><? echo($level_values->data_type->get()->data_type_name); ?></td>
                        <?  foreach ($level_values as $level_value ) {  ?>
              <td class="acenter"><? echo($level_value->level_value); ?></td>
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
                      if($tool->operation == 'ПОКУПКА' || $tool->operation == 'BUY') $idea_oper='buy';
                      if($tool->operation == 'ПРОДАЖА' || $tool->operation == 'SELL') $idea_oper='sell';

            ?>
            <tr class="<?  echo($idea_oper.' odd');  ?>" >
              <td class="<?  echo($idea_oper); ?>"><? echo($tool->operation); ?></td>
                  <?  if ($is_first) {
                        $is_first = false;  ?>
              <td class="comment" rowspan="10"><? echo($item->comment); ?></td>
                  <? } ?>
              <td colspan="3"></td>
            </tr>
                      <?  $level_value_types = $tool->level_value->select('data_type_id')->distinct()->get();
                          foreach ($level_value_types as $level_value_type ) {
                            if($j % 2 <> 0) $idea_row = 'even';
                            else $idea_row = 'odd';
                            $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                      ?>
            <tr class="<?  echo($idea_oper.' '.$idea_row);  ?>">
              <td class="acenter"><?  switch($j) {
                              case 1: ?><? echo($tool->name); ?><?      break; 
                              case 2: ?><? echo($tool->isin); ?><?      break; 
                              case 3: ?><? echo($tool->rating); ?><?      break;
                            } ?></td>
              <td class="sub-header"><? echo($level_values->data_type->get()->data_type_name); ?></td>
                        <?  foreach ($level_values as $level_value ) {  ?>
              <td class="acenter"><? echo($level_value->level_value); ?></td>
                        <?  } ?>
            </tr>
                        <?  $j++; 
                          }
                    } ?>
            <tr class="odd bold">
              <td></td>
              <?  reset($item_level_values); ?>
              <td class="sub-header"><? echo($item_level_values->data_type->get()->data_type_name); ?></td>
                        <?  foreach ($item_level_values as $item_level_value ) {  
                              if ($item_level_value->level_type_id <> 3) {
                          ?>
              <td class="acenter bold"><? echo($item_level_value->level_value); ?></td>        
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
       <? } echo(isset($pages)?$pages:''); ?>
    </td>
  </tr>	
</table>
﻿