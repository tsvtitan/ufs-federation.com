<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
      <form action="<? echo($this->uri->uri_string); ?>" method="post" enctype="multipart/form-data">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_priority')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" id="input_title" name="data[priority]" value="<? echo(isset($data->priority)?$data->priority:''); ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_key')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" id="input_title" name="data[key]" value="<? echo(isset($data->key)?$data->key:''); ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_text')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" id="input_title" name="data[text]" value="<? echo(isset($data->text)?$data->text:''); ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_description')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" id="input_title" name="data[description]" value="<? echo(isset($data->description)?$data->description:''); ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_group')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" id="input_title" name="data[group]" value="<? echo(isset($data->group)?$data->group:''); ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="submit">
              <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                <tr>
                  <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                  <td><a href="<? echo($this->phpself.$this->page_name.'/group/'.$this->group); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                  <td width="100%">&nbsp;</td>
                  <? if(isset($data->search_key_id)){ ?>
                  <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$group.'/'.$data->search_key_id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                  <? } ?>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <? if(isset($data->search_key_id)){ ?>
        <input type="hidden" name="search_key_id" value="<? echo($data->search_key_id); ?>" class="hidden" />
        <? } ?>
        <input type="hidden" name="submit" value="submit" class="hidden" />
      </form>
  	</td>
  </tr>	
</table>