<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
    <form action="<? echo($this->uri->uri_string); ?>" method="post" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_import_xls_file')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="file" name="_file" value="" class="file" /></td>
		  </tr>
          <tr>
			<td class="header_title"></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="checkbox" name="del_all" value="1" class="checkbox" /> - <? echo($this->lang->line('admin_tpl_import_xls_del_all')); ?></td>
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
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>