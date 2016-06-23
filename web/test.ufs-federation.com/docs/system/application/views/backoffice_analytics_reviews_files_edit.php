<script language="javascript" type="text/javascript">
	function Valid_Form()
	{
        if(document.getElementById('name').value=='')
		{
			alert('<? echo($this->lang->line('admin_tpl_error')); ?>: <? echo($this->lang->line('admin_pages_popup_error_name')); ?>');
			return false;
		}
		else if(document.getElementById('file').value.length==0 || document.getElementById('file').value=='')
		{
			if(!document.getElementById('present_file')){
				alert('<? echo($this->lang->line('admin_tpl_error')); ?>: <? echo($this->lang->line('admin_pages_popup_error_file')); ?>');
				return false;				
			}

		}
		else{
			return true;
		}
	}
</script>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2>
		
		<h4> <?echo($header)?></h4><br />
		</td>
	</tr>
  <tr>
    <td valign="top" align="center">
      <form action="<? echo($this->uri->uri_string.(isset($data->id)?('/'.$data->id):'')); ?>" method="post" onsubmit="return Valid_Form();" enctype="multipart/form-data">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" id="name" name="name" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_file')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
              <input type="file" name="_file" id="file" value="" class="file" />
              <? if(isset($data->_file)){ ?>
               <? if(!empty($data->_file)){ ?>                 
                  - <a id="present_file_link" href="<? echo($this->phpself.$this->page_name.'/download/'.$data->url); ?>" target="_blank"><? echo($this->lang->line('admin_tpl_download')); ?></a>
               <? } ?>
                  <input id="present_file" type="hidden" name="old_file" value="<? echo($data->_file); ?>">
              <? } ?>
            </td>
          </tr>
          <!--tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_companies')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
              <input type="checkbox" name="ufs" value="1"<? echo(($data->ufs)?' checked':'') ?>>&nbsp;UFS
              &nbsp;&nbsp;<input type="checkbox" name="premier" value="1"<? echo(($data->premier)?' checked':'') ?>>&nbsp;Премьер
            </td>
          </tr-->
          <tr>
            <td class="header_title"></td>
          </tr>
          <tr>
            <td class="submit">
              <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                <tr>
                  <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                  <td><a href="<? echo($this->phpself.$this->page_name.'/files/'.$the_debt->id); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
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