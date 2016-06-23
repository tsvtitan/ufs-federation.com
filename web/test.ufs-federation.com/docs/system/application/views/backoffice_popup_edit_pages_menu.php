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
          <? if(@$data->is_home=='yes'){ ?>
             <input type="hidden" name="select[is_hide]" value="1" class="hidden" />
          <? }else{ ?>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_link_to_page')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[link_to_page]" value="<? echo(isset($data->link_to_page)?$data->link_to_page:''); ?>" class="text" /></td>
		  </tr>
          <tr>
			<td class="header_title"></td>
		  </tr>
          <tr>
		  	<td class="item">
                <input type="checkbox" class="checkbox" name="select[is_hide]" value="1"<? echo(isset($data->is_hide)?$data->is_hide:''); ?> /> - <? echo($this->lang->line('admin_tpl_hide_in_menu')); ?>
            </td>
		  </tr>
          <? } ?>
          <? if(isset($data->sub_page_type)){ ?>
		  <tr>
            <td class="header_title"><h5>Тип меню <small>(предназначено для разработчиков)</small></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[sub_page_type]', $data->sub_page_type_array, $data->sub_page_type, 'class="select"', true)); ?>
            </td>
		  </tr>
          <? } ?>
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
                        <? if($data->is_delete=='yes'){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/del/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                        <? } ?>
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