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
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/export_xls"><? echo($this->lang->line('admin_tpl_export_xls')); ?></a></li>
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/add"><? echo($this->lang->line('admin_tpl_add')); ?></a></li>
                    <li>
                      <a id="recalc" href="<? echo($this->phpself.$this->page_name); ?>/recalc"><? echo($this->lang->line('admin_tpl_recalc')); ?></a>
                      <div id="spinner" style="display: none;">
                        <a><? echo($this->lang->line('admin_tpl_recalc')); ?></a>
                        <div style="display: inline-block; min-width:16px; min-height:16px; background-image: url(/images/spinner.gif)">&nbsp;</div>
                      </div>  
                    </li>
                </ul>
            </td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_pages_cat_menu')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <ul class="nav_menu">
                    <li><a<? echo(($_SESSION['issuers_debt_market_type']=='euro')?' class="sel"':''); ?> href="<? echo($this->phpself.$this->page_name); ?>/set_type/euro"><? echo($this->lang->line('admin_issuers_debt_market_type_euro')); ?></a></li>
                    <li><a<? echo(($_SESSION['issuers_debt_market_type']=='rur')?' class="sel"':''); ?> href="<? echo($this->phpself.$this->page_name); ?>/set_type/rur"><? echo($this->lang->line('admin_issuers_debt_market_type_rur')); ?></a></li>
                    <li><a<? echo(($_SESSION['issuers_debt_market_type']=='int_euro')?' class="sel"':''); ?> href="<? echo($this->phpself.$this->page_name); ?>/set_type/int_euro"><? echo($this->lang->line('admin_issuers_debt_market_type_int_euro')); ?></a></li>
                </ul>
            </td>
          </tr>
        </table>
	</td>
	<td valign="top" align="center">
	  <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <? if($data){ ?>
	  <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_name')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_maturity_date')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_isin_isin')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_currency')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_closing_price')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_income')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_duration')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_rate')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_next_coupon')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_volume')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_payments_per_year')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_rating_sp')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_rating_moodys')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_rating_fitch')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_industry')); ?></h5></td>
            <td class="header_title center"><h5><? echo($this->lang->line('admin_issuers_debt_market_country')); ?></h5></td>
	    <td class="header_title center"></td>
          </tr>
	  <? foreach($data as $item){ ?>
          <tr<? echo($item->css_class); ?>>
            <td class="item"><? echo($item->name); ?></td>
            <td class="item center"><? echo($item->mdate); ?></td>
            <td class="item center"><? echo($item->isin); ?></td>
            <td class="item center"><? echo($item->currency); ?></td>
            <td class="item center"><? echo($item->closing_price); ?> %</td>
            <td class="item center"><? echo($item->income); ?> %</td>
            <td class="item center"><? echo($item->duration); ?></td>
            <td class="item center"><? echo($item->rate); ?> %</td>
            <td class="item center"><? echo($item->next_coupon); ?></td>
            <td class="item center"><? echo($item->volume); ?></td>
            <td class="item center"><? echo($item->payments_per_year); ?></td>
            <td class="item center"><? echo($item->rating_sp); ?></td>
            <td class="item center"><? echo($item->rating_moodys); ?></td>
            <td class="item center"><? echo($item->rating_fitch); ?></td>
            <td class="item center"><? echo($item->industry); ?></td>
            <td class="item center"><? echo($item->country); ?></td>
	    <td class="item">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/edit/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_edit')); ?>"><img src="<? echo($this->base_url); ?>images/pencil.png" alt="<? echo($this->lang->line('admin_tpl_edit')); ?>" width="16" height="16" /></a></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/del/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')); ?>" width="16" height="16" /></a></td>
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

<script type="text/javascript">
  
  $('#recalc').click(function(e){
    $('#recalc').hide();
    $('#spinner').show();
  });
  
</script>