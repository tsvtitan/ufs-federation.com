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
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_fio')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_info')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[info]"><? echo(isset($data->info)?$data->info:''); ?></textarea></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_photo')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td class="item"><input type="file" name="img" value="" class="file" /></td>
                    <td class="item">	
                        <? if(isset($data->img)){ ?>
                            <? if(!empty($data->img)){ ?>
                              <img src="/upload/analytical_team/small/<? echo($data->img); ?>">
                            <? } ?>
                            <input type="hidden" name="img_old" value="<? echo($data->img); ?>" class="hidden" />
                        <? } ?>
                    </td>
                  </tr>	
                </table>
            </td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_display')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[display][]', $data->display_array, $data->display_multi_sel, 'class="multiple" multiple="multiple"', true)); ?>
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