<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
    <form action="<? echo($this->uri->uri_string); ?>" method="post" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_role')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <select name="select[role_id]" class="select">
                <? foreach($roles as $val){ ?>
                  <option <? echo(($val->sel!='')?$val->sel:''); ?> value="<? echo($val->id); ?>"><? echo($val->role); ?></option>                
                <? } ?>
				</select>
			</td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_path')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[path]" value="<? echo(isset($data->path)?$data->path:''); ?>" class="text" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_access')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <select name="select[access]" class="select">
                <? foreach($access_list as $val){ ?>
                  <option <? echo(($val->sel!='')?$val->sel:''); ?> value="<? echo($val->value); ?>"><? echo($val->value); ?></option>                
                <? } ?>
				</select>
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
                        <td><a href="<? echo($this->phpself.$this->page_name.'/role/'.$this->role_id); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$data->role_id.'/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
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