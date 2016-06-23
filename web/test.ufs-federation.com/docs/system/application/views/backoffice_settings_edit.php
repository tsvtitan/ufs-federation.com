<? $content=$this->site_lang; ?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><? echo($this->lang->line('admin_settings_'.$data->name)); ?></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_content')); ?></h5></td>
		  </tr>
          <? if($data->type=='text'){ ?>
		  <tr>
		  	<td class="item"><textarea class="textarea" name="text[<? echo($content); ?>]"><? echo($data->$content); ?></textarea></td>
		  </tr>
          <? }else{ ?>
          <tr>
		  	 <td class="item">
			 <?php
			 include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('fck['.$content.']') ;
				$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
				$oFCKeditor->Config['EnterMode'] = 'br';
				$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
				$oFCKeditor->ToolbarSet = 'Default';
				$oFCKeditor->Width = '100%';
				$oFCKeditor->Height = '400';
				$oFCKeditor->Value = $data->$content; 
				$oFCKeditor->Create();
			 ?>
			 </td>
		   </tr>
          <? } ?>
          
          <?if($data->id==2):?>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_active')); ?></h5></td>
		  </tr>    
		  <tr>
		  	<td class="item"><input type="checkbox" name="is_active" <?if(isset($data->is_active)):?><?if($data->is_active==1):?>checked<?endif;?><?endif;?> value="<?echo(isset($data->is_active)?$data->is_active:0)?>" /></td>
		  </tr>		  
		  <?endif;?>      
          
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
                    </tr>
                </table>
            </td>
		  </tr>
        </table>
		<input type="hidden" name="id" value="<? echo($data->id); ?>" class="hidden" />
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>