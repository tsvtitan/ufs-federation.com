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
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>"><? echo($this->lang->line('admin_tpl_back')); ?></a></li>
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/transaction_add/<? echo($portfolio_id); ?>"><? echo($this->lang->line('admin_tpl_add')); ?></a></li>
                </ul>
            </td>
          </tr>
        </table>
	</td>
	<td valign="top" align="center">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <? if($data){ ?>
		  <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_tpl_price')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_tpl_quantity')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_transaction_amount')); ?></h5></td>
			<td class="header_title center"></td>
          </tr>
		  <? foreach($data as $item){ ?>
          <tr<? echo($item->css_class); ?>>
            <td class="item"><? echo($item->name); ?></td>
            <td class="item center"><? echo($item->date); ?></td>
            <td class="item center"><? echo($item->price); ?></td>
            <td class="item center"><? echo($item->quantity); ?></td>
            <td class="item center"><? echo($item->transaction_amount); ?></td>            
			<td class="item">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="item_sort">
                        <? if($item->end_el==1){ ?>
                            <img src="/images/empty.gif" alt="" width="12" height="16" />
                        <? }else{ ?>	
                            <a href="<? echo($this->phpself.$this->page_name.'/transaction_sort/'.$portfolio_id.'/down/'.$item->id.'/'.$item->sort_id); ?>" title="<? echo($this->lang->line('admin_tpl_down')); ?>"><img src="/images/arrow_down.gif" alt="<? echo($this->lang->line('admin_tpl_down')); ?>" width="12" height="16" /></a>
                        <? } ?>
                        </td>
                        <td class="item_sort">
                        <? if($item->first_el==1){ ?>
                            <img src="/images/empty.gif" alt="" width="12" height="16" />
                        <? }else{ ?>
                            <a href="<? echo($this->phpself.$this->page_name.'/transaction_sort/'.$portfolio_id.'/up/'.$item->id.'/'.$item->sort_id); ?>" title="<? echo($this->lang->line('admin_tpl_up')); ?>"><img src="/images/arrow_up.gif" alt="<? echo($this->lang->line('admin_tpl_up')); ?>" width="12" height="16" /></a>
                        <? } ?>
                        </td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/transaction_edit/'.$portfolio_id.'/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_edit')); ?>"><img src="<? echo($this->base_url); ?>images/pencil.png" alt="<? echo($this->lang->line('admin_tpl_edit')); ?>" width="16" height="16" /></a></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/transaction_del/'.$portfolio_id.'/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')); ?>" width="16" height="16" /></a></td>
                    </tr>
                </table>
			</td>
          </tr>
		  <? } ?>
		<? }else{ //if data ?>
          <tr>
			<td class="item"><h4><? echo($this->lang->line('admin_tpl_not_found')); ?></h4></td>
          </tr>
		<? } ?>
        </table>
        <? echo(isset($pages)?$pages:''); ?>
	</td>
  </tr>	
</table>