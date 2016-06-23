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
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
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
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_short_content')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
			<?php
			include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('fck[short_content]') ;
				$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
				$oFCKeditor->Config['EnterMode'] = 'br';
				$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
				$oFCKeditor->ToolbarSet = 'Default';
				$oFCKeditor->Width = '100%';
				$oFCKeditor->Height = '300';
				$oFCKeditor->Value = isset($data->short_content) ? $data->short_content : ''; 
				$oFCKeditor->Create();
			?>
			</td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_content')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
			<?php
			include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('fck[content]') ;
				$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
				$oFCKeditor->Config['EnterMode'] = 'br';
				$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
				$oFCKeditor->ToolbarSet = 'Default';
				$oFCKeditor->Width = '100%';
				$oFCKeditor->Height = '500';
				$oFCKeditor->Value = isset($data->content) ? $data->content : ''; 
				$oFCKeditor->Create();
			?>
			</td>
		  </tr>
		  <tr>
		    <td style="padding: 0 0 0 0; " >
		       <table width=100% border=0 cellspacing=0 cellpadding=0>
		        <tr>
		          <td class="header_title" width=280px><h5><? echo($this->lang->line('admin_tpl_display')); ?></h5></td>
		          <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_group')); ?></h5></td>
		        <tr> 
              </table>
            </td>
          </tr>   		
          <tr>
            <td style="padding: 5px 0 0 0; " ><table width=100% border=0 cellspacing=0 cellpadding=0>        
		       <tr valign=top>
		  		 <td class="item">
                 <?if(!empty($sub_menu)):?>
                   <select style="height: 250px;width: 280px;" multiple="multiple" class="multiple" name="display_pg[]" >
                    <?foreach($sub_menu as $item):?>
                    <?if(isset($item->sub)):?>
                      <optgroup label="<?echo($item->name);?>">              
                      <?foreach($item->sub as $is):?>
                        <option <?if($is->select==1):?>selected="selected"<?endif;?> value="<?echo($is->id)?>"><?echo($is->name);?></option>
                      <?endforeach;?>
                      <optgroup>
                    <?endif;?>
                    <?endforeach;?>
                   </select>
                 <?endif;?>
                 </td>
                 <td class="item">
                 <?if(!empty($groups)):?>
                   <select style="width: 280px;" name="select[group_id]" >
                      <option value=""></option>
                    <?foreach($groups as $item) {?>
                      <option value="<? echo($item->group_id); ?>"<? echo($item->select); ?>><? echo($item->prefix.$item->name); ?></option>
                    <?} ?>
                   </select>
                 <?endif;?>
                 </td>
		       </tr>
		    </table></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_keywords')); ?></h5></td>
		  </tr>		  
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[keywords]"><? echo($keywords); ?></textarea></td>
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