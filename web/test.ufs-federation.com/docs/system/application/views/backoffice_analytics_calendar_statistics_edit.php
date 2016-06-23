<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_analytics_calendar_date')); ?></h5></td>
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
                        <td>&nbsp;</td>
                        <td>
                            <select name="date[hour]" class="date">
                                <? foreach($date->hour as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>:</td>
                        <td>
                            <select name="date[minute]" class="date">
                                <? foreach($date->minute as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_country')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[country]" value="<? echo(isset($data->country)?$data->country:''); ?>" class="text" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_analytics_calendar_month')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[month]', $data->month_array, $data->month, 'class="select"', true)); ?>
            </td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_analytics_calendar_rate')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[rate]" value="<? echo(isset($data->rate)?$data->rate:''); ?>" class="text" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_analytics_calendar_forecast')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="text[forecast]" value="<? echo(isset($data->forecast)?$data->forecast:'0'); ?>" class="text" maxlength="10" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_analytics_calendar_previous_value')); ?></h5></td>
		  </tr>
		  <tr>
            <td class="item"><input type="text" name="text[previous_value]" value="<? echo(isset($data->previous_value)?$data->previous_value:'0'); ?>" class="text" maxlength="10" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_analytics_calendar_influence_factor')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[influence_factor]', $data->influence_factor_array, $data->influence_factor, 'class="select"', true)); ?>
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