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
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_issue')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[ticker]" value="<? echo(isset($data->ticker)?$data->ticker:''); ?>" class="text" maxlength="250" /></td>
		  </tr> 
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_type')); ?></h5></td>
		  </tr>		  
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_isin')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[isin]" value="<? echo(isset($data->isin)?$data->isin:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_income')); ?></h5></td>
		  </tr>
          <tr>
            <td class="item"><input type="text" name="text[price_currency]" value="<? echo(isset($data->price_currency)?$data->price_currency:'0.00'); ?>" class="text int" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_price_fair')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="text[price_fair]" value="<? echo(isset($data->price_fair)?$data->price_fair:'0.00'); ?>" class="text int" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_issuers_debt_market_recommendation')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="text[potential]" value="<? echo(isset($data->potential)?$data->potential:''); ?>" class="text" maxlength="255" /></td>
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