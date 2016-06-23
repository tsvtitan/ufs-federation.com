<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_user')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_query')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[query]"><? echo(isset($data->query)?$data->query:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_answer')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[answer]"><? echo(isset($data->answer)?$data->answer:''); ?></textarea></td>
		  </tr>
          <tr>
			<td class="header_title"></td>
		  </tr>
          <tr>
            <td class="item">
                <input type="checkbox" class="checkbox" name="select[status]" value="1"<? echo(isset($data->status)?$data->status:''); ?> /> - <? echo($this->lang->line('admin_tpl_hide_box')); ?>
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
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del_comment/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
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