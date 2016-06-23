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
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_url').$this->lang->line('admin_tpl_url_text')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="url" value="<? echo(isset($data->url)?$data->url:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
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
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_category')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <select name="select[cat_id]" class="select">
                <? foreach($menu as $item){ ?>
                    <option <?if(isset($data->cat_id)):?><?if($data->cat_id==$item->id):?>selected="selected"<?endif;?><?endif;?> value="<? echo($item->id); ?>"><strong>&nbsp;&nbsp;&bull;&nbsp;<? echo($item->name); ?></strong></option>
                      <? if(isset($item->sub)){ ?>
                        <? foreach($item->sub as $val){ ?>
                            <option<? echo($val->sel); ?> value="<? echo($val->id); ?>">&nbsp;&nbsp;&bull;&nbsp;<? echo($val->name); ?></option>
                        <? } ?>
                      <? } ?>
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
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_file')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <input type="file" name="_file" value="" class="file" />
                <? if(isset($data->_file)){ ?>
                 <? if(!empty($data->_file)){ ?> 
                    - <a href="http://<? echo($this->site_lang.'.'.$this->host); ?>/disclosure-of-information-download-pdf/<? echo($data->url.$this->mail_urlsufix); ?>" target="_blank"><? echo($this->lang->line('admin_tpl_download')); ?></a>
                 <? } ?>
                    <input type="hidden" name="old_file" value="<? echo($data->_file); ?>">
                <? } ?>
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
                        <td><a href="<? echo($this->phpself.$this->page_name.'/cat/'.$this->cat_id); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$data->cat_id.'/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
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