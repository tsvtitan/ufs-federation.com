<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($header); ?></h2></td>
	</tr>
	<tr>
		<td align="left">
			<? if(isset($error)){ ?>
				<b><? echo($this->lang->line('admin_tpl_error')); ?>:</b><br />
				<ul>
					<? foreach($error as $item){ ?>
					<li><? echo($item); ?></li>
					<? } ?>
				</ul>
			<? }else{ ?>
				&nbsp;
			<? } ?>
		</td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Title</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[meta_title]"><? echo(isset($data->meta_title)?$data->meta_title:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Keywords</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[meta_keywords]"><? echo(isset($data->meta_keywords)?$data->meta_keywords:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Description</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[meta_description]"><? echo(isset($data->meta_description)?$data->meta_description:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"></td>
		  </tr>
		  <tr>
			<td class="submit">
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                    <tr>
                        <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3)); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/del/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
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