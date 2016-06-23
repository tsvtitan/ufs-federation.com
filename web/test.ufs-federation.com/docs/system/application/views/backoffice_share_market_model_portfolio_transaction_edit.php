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
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                        <td>
                            <select name="date[day]" class="date">
                                <? foreach($date->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="date[month]" class="date">
                                <? foreach($date->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="date[year]" class="date">
                                <? foreach($date->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>         
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_price')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[price]" value="<? echo(isset($data->price)?$data->price:'0.00'); ?>" class="text" maxlength="50" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_quantity')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="int[quantity]" value="<? echo(isset($data->quantity)?$data->quantity:'0'); ?>" class="text int" maxlength="5" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_share_market_model_portfolio_transaction_amount')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="float[transaction_amount]" value="<? echo(isset($data->transaction_amount)?$data->transaction_amount:'0.00'); ?>" class="text" maxlength="50" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_content')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
			<?php
			 include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('fck[content]') ;
				$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
				$oFCKeditor->Config['EnterMode'] = 'br';
				$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
				$oFCKeditor->ToolbarSet = 'Default';
				$oFCKeditor->Width = '100%';
				$oFCKeditor->Height = '300';
				$oFCKeditor->Value = isset($data->content) ? $data->content : ''; 
				$oFCKeditor->Create();
			?>
			</td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_type')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[type]', $data->type_array, $data->type, 'class="select"', true)); ?>
            </td>
		  </tr>
          <tr>
			<td class="header_title"></td>
		  </tr>
          <tr>
		  	<td class="item">
                <input type="checkbox" class="checkbox" name="select[is_hide]" value="1"<? echo(isset($data->is_hide)?$data->is_hide:''); ?> /> - <? echo($this->lang->line('admin_tpl_hide')); ?>
            </td>
		  </tr>
          <tr>
			<td class="header_title"></td>
		  </tr>
		  <tr>
			<td class="submit">
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                    <tr>
                        <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/transaction_view/'.$portfolio_id); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/transaction_del/'.$portfolio_id.'/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
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