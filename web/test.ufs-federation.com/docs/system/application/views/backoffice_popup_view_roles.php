<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($header); ?></h2></td>
	</tr>
	<tr>
		<td align="left">
		<? if(isset($new)){ ?>
		<a class="button" href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3)); ?>/add"><? echo($this->lang->line('admin_tpl_add')); ?></a>
		<? }else{ echo('&nbsp;'); } ?>
		</td>
	</tr>
  <tr>
    <td valign="top" align="center">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
			<td class="header_title"></td>
          </tr>
		<? if($data){ ?>
		  <? foreach($data as $item){ ?>
          <tr<? echo($item->css_class); ?>>
		  	<td width="100%" class="item"><? echo($item->role); ?></td>
			<td class="item">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>					
						<td class="item_sort"></td>
						<td class="item_sort"></td>
						<td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/edit/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_edit')); ?>"><img src="/images/pencil.png" alt="<? echo($this->lang->line('admin_tpl_edit')); ?>" width="16" height="16" /></a></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/del/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')); ?>" width="16" height="16" /></a></td>
                        <td><img src="/images/empty.gif" alt="" width="16" height="16" /></td>
   					    <td><img src="/images/empty.gif" alt="" width="16" height="16" /></td>
                    </tr>
				</table>
			</td>
          </tr>
		  <? } ?>
		<? } //if data ?>
        </table>
	</td>
  </tr>	
</table>