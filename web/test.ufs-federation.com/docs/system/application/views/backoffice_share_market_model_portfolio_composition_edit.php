<script language="javascript" type="text/javascript">
	function Valid_Form()
	{
        if(document.getElementById('input_title').value=='')
		{
			alert('<? echo($this->lang->line('admin_tpl_error')); ?>: <? echo($this->lang->line('admin_pages_popup_error_name')); ?>');
			return false;
		}else{
			return true;
		}
	}
</script>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_maturity_date')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                        <td>
                            <select name="maturity_date[day]" class="date">
                                <? foreach($maturity_date->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="maturity_date[month]" class="date">
                                <? foreach($maturity_date->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="maturity_date[year]" class="date">
                                <? foreach($maturity_date->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>         
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_price_starting')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[price_starting]" value="<? echo(isset($data->price_starting)?$data->price_starting:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_price_current')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[price_current]" value="<? echo(isset($data->price_current)?$data->price_current:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_nominal_volume')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[nominal_volume]" value="<? echo(isset($data->nominal_volume)?$data->nominal_volume:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_cost')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[cost]" value="<? echo(isset($data->cost)?$data->cost:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"></td>
		  </tr>
		  <tr>
			<td class="submit">
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                    <tr>
                        <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/composition_view/'.$portfolio_id); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/composition_del/'.$portfolio_id.'/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                       <? } ?>
                    </tr>
                </table>
            </td>
		  </tr>
        </table>
        <? if(isset($data->id)){ ?>
		<input type="hidden" name="id" value="<? echo($data->id); ?>" class="hidden" />
        <? } ?>
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>