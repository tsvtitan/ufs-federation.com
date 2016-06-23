<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
        <td align="center" colspan="2"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center" class="left_sidebar">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="left_content">
          <? if(isset($is_update)){ ?>
          <tr>
                <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_status')); ?></h5></td>
          </tr>
          <tr>
                <td class="item"><? echo($is_update); ?></td>
          </tr>
          <? } ?>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_search')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <form action="<? echo($this->phpself.$this->page_name); ?>" method="post">
                    <input type="text" class="text" name="search" value="<? echo(isset($_SESSION['search'])?stripslashes($_SESSION['search']):''); ?>" />
                    <br />
                    <input type="image" src="<? echo($this->base_url); ?>images/btn_ok_admin.png" />
                    <input type="hidden" name="form_search" value="1">
                </form>
            </td>
          </tr>
          <tr>
              <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_menu')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <ul class="sub_menu">
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/add"><? echo($this->lang->line('admin_tpl_add')); ?></a></li>
                </ul>
            </td>
          </tr>
        </table>
	</td>
	<td valign="top" align="center">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <? if($data){ ?>
		  <tr>
		    <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		    <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_priority')); ?></h5></td>
		    <td class="header_title"></td>
		    </tr>
		  <? foreach($data as $item){ ?>
          <tr<? echo($item->css_class); ?>>
            <td class="item" width="75%"><? echo($item->prefix); ?><? echo($item->name); ?></td>
            <td class="item" width="25%" style="text-align: right;"><? echo($item->priority); ?></td>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                <!--        <td class="item_sort">
                        <? if($item->end_el==1){ ?>
                            <img src="/images/empty.gif" alt="" width="12" height="16" />
                        <? }else{ ?>	
                            <a href="<? echo($this->phpself.$this->page_name.'/sort/down/'.$item->group_id.'/'.$item->priority); ?>" title="<? echo($this->lang->line('admin_tpl_down')); ?>"><img src="/images/arrow_down.gif" alt="<? echo($this->lang->line('admin_tpl_down')); ?>" width="12" height="16" /></a>
                        <? } ?>
                        </td>
                        <td class="item_sort">
                        <? if($item->first_el==1){ ?>
                            <img src="/images/empty.gif" alt="" width="12" height="16" />
                        <? }else{ ?>
                            <a href="<? echo($this->phpself.$this->page_name.'/sort/up/'.$item->group_id.'/'.$item->priority); ?>" title="<? echo($this->lang->line('admin_tpl_up')); ?>"><img src="/images/arrow_up.gif" alt="<? echo($this->lang->line('admin_tpl_up')); ?>" width="12" height="16" /></a>
                        <? } ?>
                        </td> --!>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/edit/'.$item->group_id); ?>" title="<? echo($this->lang->line('admin_tpl_edit')); ?>"><img src="<? echo($this->base_url); ?>images/pencil.png" alt="<? echo($this->lang->line('admin_tpl_edit')); ?>" width="16" height="16" /></a></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/del/'.$item->group_id); ?>" title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')); ?>" width="16" height="16" /></a></td>
                    </tr>
                </table>
			</td>
          </tr>
		  <? } ?>
		<? }else{ //if data ?>
          <tr>
			<td class="item"><h4><? echo($this->lang->line('admin_tpl_not_found')); ?></h4></td>
          </tr>
		<? } ?>
        </table>
        <? echo(isset($pages)?$pages:''); ?>
	</td>
  </tr>	
</table>