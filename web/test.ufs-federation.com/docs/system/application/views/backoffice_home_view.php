<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><br><br><h2><? echo($header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="left">
    <h4><? echo($this->lang->line('admin_home_menu_title')); ?></h4>
	<? if($data){ ?>
       <ul>
       <? foreach($data as $item){ ?>
            <li><a href="<? echo($this->phpself.$item->url); ?>"><? echo($item->name); ?></a></li>
       <? } ?>
			<li><a href="http://ru.ufs-federation.com/twitter/twitter.php">Twitter: Новости и обзоры</a></li>
       </ul>
	<? } //if data ?>
	</td>
  </tr>	
</table>