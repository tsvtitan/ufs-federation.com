<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($header); ?></h2></td>
	</tr>
	<tr>
		<td align="left">
		</td>
	</tr>
  <tr>
    <td valign="top" align="center">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
      <tr>
        <td class="header_title" width="35%" ><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
  			<td class="header_title" width="15%" ><h5><? echo($this->lang->line('admin_tpl_human_name')); ?></h5></td>
  			<td class="header_title" width="50%" ><h5><? echo($this->lang->line('admin_tpl_email')); ?></h5></td>
  			<td class="header_title"></td>
      </tr>
		  <? if($data) { ?>
		    <? foreach($data as $item){ ?>
        <tr<? echo($item->css_class); ?>>
          <td class="item"><? echo($item->started); ?></td>
		  	  <td class="item"><? echo($item->name); ?></td>
		  	  <td class="item"><? echo($item->email); ?></td>
		  	  <td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/unsubscribe/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_unsibscribe')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_unsibscribe')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_unsibscribe')); ?>" width="16" height="16" /></a></td>
        </tr>
		  <? } ?>
		<? } ?>
    </table>
	</td>
  </tr>	
</table>