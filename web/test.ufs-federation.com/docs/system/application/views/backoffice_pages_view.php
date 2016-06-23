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
                    <? if($this->global_model->is_add('pages_menu',$this->cat_id)==true){ ?>
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/add/<? echo($this->cat_id); ?>"><? echo($this->lang->line('admin_tpl_add')); ?></a></li>
                    <? } ?>
                    <li><a href="javascript:void(0)"  onclick="javascript:window.open('<? echo($this->phpself); ?>popup/pages_menu','popup','width=550,height=600,left=50,top=50,scrollbars=yes');"><? echo($this->lang->line('admin_pages_cat_editor')); ?></a></li>
                </ul>
            </td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_pages_cat_menu')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <ul class="nav_menu">
                    <? foreach($menu as $item){ ?>
                    <li>
                        <a<? echo($item->sel); ?> href="<? echo($this->phpself.$this->page_name); ?>/cat/<? echo($item->id); ?>"><? echo($item->name); ?></a>
                    </li>
                    <? } ?>
                </ul>
            </td>
          </tr>
        </table>
	</td>
	<td valign="top" align="center">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <? if($data){ ?>
		  <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_page')); ?></h5></td>
            <td class="header_title" nowrap><h5><? echo($this->lang->line('admin_tpl_link_to_page')); ?></h5></td>
			<td class="header_title"></td>
          </tr>
		  <? foreach($data as $item){ ?>
          <tr<? echo($item->css_class); ?>>
            <td class="item"><? echo($item->timestamp); ?></td>
			<td class="item" width="100%"><? echo((($item->list_level=='sub')?'&nbsp;&nbsp;&bull;&nbsp;':'').$item->name); ?></td>
            <td class="item" nowrap><? echo(empty($item->link_to_page)?'-':$item->link_to_page); ?></td>
			<td class="item">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <? if($item->list_level=='sub'){ ?>
                        <td><img src="/images/empty.gif" alt="" width="16" height="16" /></td>
                        <? } ?>
                        <td class="item_sort">
                        <? if($item->end_el==1){ ?>
                            <img src="/images/empty.gif" alt="" width="12" height="16" />
                        <? }else{ ?>	
                            <a href="<? echo($this->phpself.$this->page_name.'/sort/'.$item->cat_id.'/down/'.$item->id.'/'.$item->sort_id.'/'.$item->parent_id); ?>" title="<? echo($this->lang->line('admin_tpl_down')); ?>"><img src="/images/arrow_down.gif" alt="<? echo($this->lang->line('admin_tpl_down')); ?>" width="12" height="16" /></a>
                        <? } ?>
                        </td>
                        <td class="item_sort">
                        <? if($item->first_el==1){ ?>
                            <img src="/images/empty.gif" alt="" width="12" height="16" />
                        <? }else{ ?>
                            <a href="<? echo($this->phpself.$this->page_name.'/sort/'.$item->cat_id.'/up/'.$item->id.'/'.$item->sort_id.'/'.$item->parent_id); ?>" title="<? echo($this->lang->line('admin_tpl_up')); ?>"><img src="/images/arrow_up.gif" alt="<? echo($this->lang->line('admin_tpl_up')); ?>" width="12" height="16" /></a>
                        <? } ?>
                        </td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/edit/'.$item->cat_id.'/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_edit')); ?>"><img src="<? echo($this->base_url); ?>images/pencil.png" alt="<? echo($this->lang->line('admin_tpl_edit')); ?>" width="16" height="16" /></a></td>
                        <td>
                          <? if($item->is_delete=='yes'){ ?>
                            <a href="<? echo($this->phpself.$this->page_name.'/del/'.$item->cat_id.'/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')); ?>" width="16" height="16" /></a>
                          <? }else{ ?>
                            <img src="/images/empty.gif" alt="" width="16" height="16" />
                          <? } ?>
                        </td>
                        <? if($item->list_level=='main'){ ?>
                        <td>
                          <? if(in_array($item->sub_page_type,$this->global_model->open_types) 
                                and $item->link_to_page==''){ ?>
                            <a href="<? echo($this->phpself.$this->page_name.'/add/'.$item->cat_id.'/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_add')); ?>">
                                <img src="<? echo($this->base_url); ?>images/add.png" alt="<? echo($this->lang->line('admin_tpl_add')); ?>" width="16" height="16" />
                            </a>
                          <? }else{ ?>
                            <img src="/images/empty.gif" alt="" width="16" height="16" />
                          <? } ?>
                        </td>
                        <? } ?>
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