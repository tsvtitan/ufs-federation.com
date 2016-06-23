<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($header); ?></h2></td>
	</tr>
	<tr>
		<td align="left">
		<? if(isset($new)){ ?>
		<a class="button" href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3)); ?>/add">Add New</a>
		<? }else{ echo('&nbsp;'); } ?>
		</td>
	</tr>
  <tr>
    <td valign="top" align="center">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <tr>
			<td class="header_title"><h5>Name</h5></td>
			<td class="header_title"></td>
          </tr>
		<? if($data){ ?>
		  <? foreach($data as $item){ ?>
          <tr<? echo($item->css_class); ?>>
		  	<td class="item"><? echo($item->name); ?></td>
			<td width="45" class="item">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/edit/'.$item->id); ?>" title="edit"><img src="/images/pencil.png" alt="edit" width="16" height="16" /></a></td>
						<td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/del/'.$item->id); ?>" title="delete" onclick="return confirm('delete?'); false;"><img src="/images/delete.png" alt="delete" width="16" height="16" /></a></td>
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