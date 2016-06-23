<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date_create')); ?></h5></td>
          </tr> 
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                   <tr>
                        <td>
                            <select name="date_create[day]" class="date">
                                <? foreach($date->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="date_create[month]" class="date">
                                <? foreach($date->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="date_create[year]" class="date">
                                <? foreach($date->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <select name="date_create[hour]" class="date">
                                <? foreach($date->hour as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>:</td>
                        <td>
                            <select name="date_create[minute]" class="date">
                                <? foreach($date->minute as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        <td>:</td>
                        <td>
                            <select name="date_create[seconds]" class="date">
                                <? foreach($date->seconds as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_login')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[login]" value="<? echo(isset($data->login)?$data->login:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_pass')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="password" id="input_title" name="text[pass]" value="<? echo(isset($data->pass)?$data->pass:''); ?>" class="text" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_role')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[role][]', $data->role_array, $data->role_multi_sel, 'class="multiple" multiple="multiple"', false)); ?>
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