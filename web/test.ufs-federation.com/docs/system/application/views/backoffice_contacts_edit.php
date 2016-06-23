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
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_address')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[address]"><? echo(isset($data->address)?$data->address:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_phones')); ?></h5></td>
		  </tr>
  	      <tr>
		  	 <td class="item">
			 <?php
			 include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('fck[phones]') ;
				$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
				$oFCKeditor->Config['EnterMode'] = 'br';
				$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
				$oFCKeditor->ToolbarSet = 'Default';
				$oFCKeditor->Width = '100%';
				$oFCKeditor->Height = '300';
				$oFCKeditor->Value = isset($data->phones) ? $data->phones : ''; 
				$oFCKeditor->Create();
			 ?>
			 </td>
   	      </tr>
		  </tr>
  		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_phone_all')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[phone_all]" value="<? echo(isset($data->phone_all)?$data->phone_all:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_phone_front')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[phone_front]" value="<? echo(isset($data->phone_front)?$data->phone_front:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_phone_back')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[phone_back]" value="<? echo(isset($data->phone_back)?$data->phone_back:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_fax')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[fax]" value="<? echo(isset($data->fax)?$data->fax:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_eurobonds')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[eurobonds]" value="<? echo(isset($data->eurobonds)?$data->eurobonds:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_stock')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[stock]" value="<? echo(isset($data->stock)?$data->stock:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_internet_trading')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[internet_trading]" value="<? echo(isset($data->internet_trading)?$data->internet_trading:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
		  <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_display')); ?></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[display]', $data->display_array, $data->display, 'class="select"', true)); ?>
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