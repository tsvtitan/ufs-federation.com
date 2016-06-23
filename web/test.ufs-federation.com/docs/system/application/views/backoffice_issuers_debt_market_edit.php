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
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_maturity_date')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                        <td>
                            <select name="mdate[day]" class="date">
                                <? foreach($mdate->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="mdate[month]" class="date">
                                <? foreach($mdate->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="mdate[year]" class="date">
                                <? foreach($mdate->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_isin')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[isin]" value="<? echo(isset($data->isin)?$data->isin:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_currency')); ?></h5></td>
		  </tr>
          <tr>
            <td class="item"><input type="text" name="text[currency]" value="<? echo(isset($data->currency)?$data->currency:''); ?>" class="text int" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_closing_price')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[closing_price]" value="<? echo(isset($data->closing_price)?$data->closing_price:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_income')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[income]" value="<? echo(isset($data->income)?$data->income:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_duration')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[duration][3]" value="<? echo(isset($data->duration)?$data->duration:'0.000'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_rate')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[rate]" value="<? echo(isset($data->rate)?$data->rate:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_next_coupon')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                        <td>
                            <select name="next_coupon[day]" class="date">
                                <? foreach($next_coupon->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="next_coupon[month]" class="date">
                                <? foreach($next_coupon->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="next_coupon[year]" class="date">
                                <? foreach($next_coupon->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_volume')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="int[volume]" value="<? echo(isset($data->volume)?$data->volume:'0'); ?>" class="text int" maxlength="15" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_payments_per_year')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="int[payments_per_year]" value="<? echo(isset($data->payments_per_year)?$data->payments_per_year:'0'); ?>" class="text int" maxlength="5" /></td>
		  </tr> 
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_rating_sp')); ?></h5></td>
		  </tr>
          <tr>
            <td class="item"><input type="text" name="text[rating_sp]" value="<? echo(isset($data->rating_sp)?$data->rating_sp:''); ?>" class="text" maxlength="250" /></td>
		  </tr> 
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_rating_moodys')); ?></h5></td>
		  </tr>
          <tr>
            <td class="item"><input type="text" name="text[rating_moodys]" value="<? echo(isset($data->rating_moodys)?$data->rating_moodys:''); ?>" class="text" maxlength="250" /></td>
		  </tr> 
          <tr>
	   <td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_rating_fitch')); ?></h5></td>
	  </tr>
          <tr>
            <td class="item"><input type="text" name="text[rating_fitch]" value="<? echo(isset($data->rating_fitch)?$data->rating_fitch:''); ?>" class="text" maxlength="250" /></td>
          </tr> 
          <tr>
	   <td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_industry')); ?></h5></td>
	  </tr>
          <tr>
            <td class="item"><input type="text" name="text[industry]" value="<? echo(isset($data->industry)?$data->industry:''); ?>" class="text" maxlength="250" /></td>
          </tr> 
          <tr>
	   <td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_country')); ?></h5></td>
	  </tr>
          <tr>
            <td class="item"><input type="text" name="text[country]" value="<? echo(isset($data->country)?$data->country:''); ?>" class="text" maxlength="250" /></td>
          </tr> 
          <tr>
			<td class="header_title"></td>
		  </tr>
		  <tr>
			<td class="submit">
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                    <tr>
                        <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                        <td><a href="<? echo($this->phpself.$this->page_name); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
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