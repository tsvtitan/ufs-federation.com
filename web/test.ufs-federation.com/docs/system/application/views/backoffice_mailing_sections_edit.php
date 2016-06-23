<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_parent_section')); ?></h5></td>
          </tr> 
          <tr>
            <td class="item">
              <select name="select[parent_id]" class="select">
                <option value=""></option>
                <? foreach($parents as $item){ ?>
                <option value="<? echo($item->mailing_section_id); ?>"<? echo($item->select); ?>><? echo($item->prefix.$item->name); ?></option>
                <? } ?>
              </select>
            </td>
          </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
		  </tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_group')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[group]" value="<? echo(isset($data->group)?$data->group:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_description')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea type="textarea" id="input_title" name="text[description]" class="textarea" /><? echo(isset($data->description)?$data->description:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_priority')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[priority]" value="<? echo(isset($data->priority)?$data->priority:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
  			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_type')); ?></h5></td>
		  </tr>
		  <tr>
		    <td class="item">
          <select name="select[type]" class="select">
            <option value=""></option>
            <option value="analytics"<? if($data->type=='analytics'){ echo('selected'); }?>>Аналитика</option>
          </select>
        </td>  
		  </tr>
		  <tr>
  			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_company')); ?></h5></td>
		  </tr>
		  <tr>
		    <td class="item">
          <select style="width: 150px;" name="select[company]" >
            <option value="UFS"<? echo(($data->company=='UFS')?' selected':''); ?>>UFS IC</option>
            <option value="PREMIER"<? echo(($data->company=='PREMIER')?' selected':''); ?>>Premier</option>
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
                        <td><a href="<? echo($this->phpself.$this->page_name); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->mailing_section_id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$data->mailing_section_id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                       <? } ?>
                    </tr>
                </table>
            </td>
		  </tr> 
        </table>
        <? if(isset($data->mailing_section_id)){ ?>
		<input type="hidden" name="mailing_section_id" value="<? echo($data->mailing_section_id); ?>" class="hidden" />
        <? } ?>
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>