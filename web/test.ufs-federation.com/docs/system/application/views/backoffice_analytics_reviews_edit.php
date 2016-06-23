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
<table border="0" cellspacing="0" cellpadding="0" align="center" >
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit" style="width:800px;">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
          </tr>
          <tr>
            <td style="padding: 0 0 0 0;">
              <table width=100% border=0 cellspacing=0 cellpadding=0>
                <tr>
                  <td class="item" style="padding:3px 10px; width:200px;">
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
                  <td nowrap>
                    <h5><? echo($this->lang->line('admin_tpl_company')); ?>&nbsp;&nbsp;
                    <select id="reviewCompany" style="width: 150px;" name="select[company]">
                      <option value="UFS"<? echo(($data->company=='UFS')?' selected':''); ?>>UFS IC</option>
                      <option value="PREMIER"<? echo(($data->company=='PREMIER')?' selected':''); ?>>Premier</option>
                    </select></h5>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
      <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="clear[name]" value="<? echo(isset($data->name)?htmlspecialchars($data->name):''); ?>" class="text" style="width:780px;" /></td>
		  </tr>
      <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_subject')); ?></h5></td>
		  </tr>
		  <tr>
		    <td style="padding: 0 0 0 0;">
          <table width=100% border=0 cellspacing=0 cellpadding=0>
            <tr>
              <td style="padding:3px 10px; width:550px;"><input class="text" style="width:550px;" type="text" id="input_title" name="clear[subject]" value="<? echo(isset($data->subject)?htmlspecialchars($data->subject):''); ?>" /></td>
              <td nowrap><label><input class="checkbox" style="margin-right: 5px;" type="checkbox" <? echo(isset($data->only_mailing_checked) ? $data->only_mailing_checked:''); ?> name="string[only_mailing]"><? echo($this->lang->line('admin_tpl_only_mailing')) ?></label></td>
            </tr>
          </table>
		  	</td>		
		  </tr>
		  <tr>
				<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_review_short_content')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
				<?php
					include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
					$oFCKeditor = new FCKeditor('clear[short_content]') ;
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
				<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_extra_mailing_text')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
				<?php
					include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
					$oFCKeditor = new FCKeditor('clear[extra_mailing_text]') ;
					$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
					$oFCKeditor->Config['EnterMode'] = 'br';
					$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
					$oFCKeditor->ToolbarSet = 'Default';
					$oFCKeditor->Width = '100%';
					$oFCKeditor->Height = '300';
					$oFCKeditor->Value = isset($data->extra_mailing_text) ? $data->extra_mailing_text : ''; 
					$oFCKeditor->Create();
				?>
				</td>
		  </tr>
      <tr>
				<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_review_content')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
			<?php
			include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('clear[content]') ;
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
		  		 <td class="item" style="width:270px;">
                 <?if(!empty($sub_menu)):?>
                   <select style="height: 416px;width: 280px;" multiple="multiple" class="multiple" name="display_pg[]" >
                    <? foreach($sub_menu as $item):?>
                    <? if (isset($item->sub)) { ?>
                      <optgroup label="<?echo($item->name);?>">              
                      <?foreach($item->sub as $is):?>
                        <option <?if($is->select==1):?>selected="selected"<?endif;?> value="<?echo($is->id)?>"><?echo($is->name);?></option>
                      <?endforeach;?>
                      <optgroup>
                    <? } else { ?>
                      <option <?if($item->select==1):?>selected="selected"<?endif;?> value="<?echo($item->id)?>"><?echo($item->name);?></option>
                    <? } ?>
                    <?endforeach;?>
                   </select>
                 <?endif;?>
                 </td>
                 <td class="item">
                   <table width=100% border=0 cellspacing=0 cellpadding=0>
                     <tr><td>
                       <select style="width: 300px;" name="select[group_id]" >
                        <option value=""></option>
                        <?foreach($groups as $item) {?>
                        <option value="<? echo($item->group_id); ?>"<? echo($item->select); ?>><? echo($item->prefix.$item->name); ?></option>
                        <? } ?>
                       </select>
                     </td></tr>
                     <tr><td style="padding: 5px 0 0 0; "></td></tr>
                     <tr><td class="header_title"><h5><? echo($this->lang->line('admin_tpl_mailing_section')); ?></h5></td></tr>
                     <tr><td style="padding: 5px 0 0 0; "></td></tr>
                     <tr><td>
                     <? if(isset($sections)) { ?>
                       <select multiple style="height: 346px;width: 490px;" multiple="multiple" class="multiple" name="sections[]" >
                       <? foreach ($sections as $item) { ?>
                         <option value="<? echo($item->mailing_section_id); ?>"<? echo($item->select); ?>><? echo($item->prefix.$item->name); ?></option>
                       <? } ?>
                       </select>
                     <? } ?>
                     </td></tr>
                   </table> 
                 </td>
		       </tr>
		    </table></td>
		  </tr>
      <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_mailing_url')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="clear[mailing_url]" value="<? echo(isset($data->mailing_url)?$data->mailing_url:''); ?>" class="text" style="width:780px;" /></td>
		  </tr>      
      <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_keywords')); ?></h5></td>
		  </tr>		  
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px; width: 780px;" name="text[keywords]"><? echo($keywords); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Title</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px; width: 780px;" name="text[meta_title]"><? echo(isset($data->meta_title)?$data->meta_title:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Keywords</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px; width: 780px;" name="text[meta_keywords]"><? echo(isset($data->meta_keywords)?$data->meta_keywords:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Description</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px; width: 780px;" name="text[meta_description]"><? echo(isset($data->meta_description)?$data->meta_description:''); ?></textarea></td>
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
      <input id="reviewId" type="hidden" name="id" value="<? echo($data->id); ?>" class="hidden" />
      <? } ?>
      <input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>

<script type="text/javascript">
  
  $('#reviewCompany').change(function(e){
    var company = $('#reviewCompany').val();
    var url = $(window).attr('location').toString();
    var add = /\/add/.test(url);
    var edit = /\/edit/.test(url);
    var path = add?'/add':(edit?'/edit':'');
    var delim = add?'/':(edit?'/'+$('#reviewId').val()+'/':'/');
    var pos = url.length;
    pos = url.indexOf(path);
    url = url.substr(0,pos+path.length).concat(delim).concat(company);
    $(window).attr('location',url);
  });
  
</script>