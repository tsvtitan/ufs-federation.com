<? $content=$this->site_lang; ?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->lang->line('admin_language_versions')); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
		  <?if(!empty($data) and is_array($data)):?>
			  <?foreach($data as $item):?>
		          <tr>
					<td class="header_title"><h5><?echo($item->name)?></h5></td>
				  </tr>
				  <tr>
				  	<td class="item"><input type="checkbox" name="is_display[<?echo($item->id)?>]" <?if(isset($item->is_display)):?><?if($item->is_display==1):?>checked<?endif;?><?endif;?> value="<?echo(isset($item->is_display)?$item->is_display:0)?>" /></td>
				  </tr>
			  <?endforeach;?>
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
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>