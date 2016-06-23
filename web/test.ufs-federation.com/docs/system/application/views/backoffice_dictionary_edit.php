<script language="javascript" type="text/javascript">
	function Valid_Form()
	{
        if(
         <? if(!isset($data->id)){ ?>
           document.getElementById('input_title_ru').value=='' ||
         <? } ?>
           document.getElementById('input_title_en').value=='' ||
           document.getElementById('input_title_de').value=='' ||
           document.getElementById('input_title_it').value=='' ||
           document.getElementById('input_title_fr').value=='' ||
           document.getElementById('input_title_el').value=='' ||
           document.getElementById('input_title_cn').value==''
          )
		{
			alert('<? echo($this->lang->line('admin_tpl_error')); ?>: <? echo($this->lang->line('admin_dictionary_error')); ?>');
			return false;
		} else {
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
			<td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_ru')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
              <? if(isset($data->id)){ ?>
                <? echo($data->ru); ?>
              <? }else{ ?>
                <input type="text" id="input_title_ru" name="text[ru]" value="<? echo(isset($data->ru)?$data->ru:''); ?>" class="text" maxlength="300" />
              <? } ?>
            </td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_en')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title_en" name="text[en]" value="<? echo(isset($data->en)?$data->en:''); ?>" class="text" maxlength="300" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_de')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title_de" name="text[de]" value="<? echo(isset($data->de)?$data->de:''); ?>" class="text" maxlength="300" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_it')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title_it" name="text[it]" value="<? echo(isset($data->it)?$data->it:''); ?>" class="text" maxlength="300" /></td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_fr')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title_fr" name="text[fr]" value="<? echo(isset($data->fr)?$data->fr:''); ?>" class="text" maxlength="300" /></td>
		  </tr>
      <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_el')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title_el" name="text[el]" value="<? echo(isset($data->el)?$data->el:''); ?>" class="text" maxlength="300" /></td>
		  </tr>
      <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_dictionary_cn')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title_cn" name="text[cn]" value="<? echo(isset($data->cn)?$data->cn:''); ?>" class="text" maxlength="300" /></td>
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