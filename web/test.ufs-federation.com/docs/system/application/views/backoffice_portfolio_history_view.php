<table border="0" cellspacing="0" cellpadding="0" align="center"> 
  <tr>
    <td align="center" colspan="2"><h2><? echo($this->tpl_header) ?></h2></td>
  </tr>
  <tr>
    <td valign="top" align="center" class="left_sidebar">
      <table border="0" cellspacing="0" cellpadding="0" align="center" class="left_content">
        <? if(isset($is_update)) { ?>
        <tr>
          <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_status')) ?></h5></td>
        </tr>
        <tr>
          <td class="item"><? echo($is_update) ?></td>
        </tr>
        <? } ?>
        <tr>
          <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_search')) ?></h5></td>
        </tr>
        <tr>
          <td class="item">
            <form action="<? echo($this->phpself.$this->page_name) ?>" method="post">
              <input type="text" class="text" name="search" value="<? echo(isset($_SESSION['search'])?stripslashes($_SESSION['search']):'') ?>" /><br>
              <input type="image" src="<? echo($this->base_url) ?>images/btn_ok_admin.png" />
              <input type="hidden" name="form_search" value="1">
            </form>
          </td>
        </tr>
        <tr>
          <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_menu')) ?></h5></td>
        </tr>
        <tr>
          <td class="item">
            <ul class="sub_menu">
              <li><a href="<? echo($this->phpself.$this->page_name.'/import_xls/'.$this->portfolio_id) ?>"><? echo($this->lang->line('admin_tpl_import_xls')) ?></a></li>
              <? if ($this->portfolio_id!=0) { ?><li><a href="<? echo($this->phpself.$this->page_name.'/recalc/'.$this->portfolio_id) ?>"><? echo($this->lang->line('admin_tpl_recalc')) ?></a></li><? } ?>
              <li><a href="javascript:void(0)"  onclick="javascript:window.open('<? echo($this->phpself) ?>popup/portfolios','popup','width=550,height=600,left=50,top=50,scrollbars=yes');"><? echo($this->lang->line('admin_portfolios_editor')) ?></a></li>
            </ul>
          </td>
        </tr>
        <tr>
          <td class="header_title"><h5><? echo($this->lang->line('admin_portfolios')) ?></h5></td>
        </tr>
        <tr>
          <td class="item">
            <ul class="nav_menu">
              <? if (is_array($portfolios)) { ?>
              <? foreach($portfolios as $p) { ?>
              <li>
                <a<? echo(isset($p->sel)?$p->sel:'') ?> href="<? echo($this->phpself.$this->page_name) ?>/portfolio/<? echo($p->portfolio_id) ?>"><? echo($p->name) ?></a>
              </li>
              <? } } ?>
            </ul>
          </td>
        </tr>
      </table>
    </td>
    <td valign="top" align="center">
      <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
        <? if ($data) { ?>
        <tr>
          <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_method')) ?></h5></td>
          <td class="header_title" style="width:50px"><h5><? echo($this->lang->line('admin_tpl_created')) ?></h5></td>
          <td class="header_title center" style="width:700px">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
              <tr><td colspan=6 style="text-align:center;border-bottom:1px dotted;"><h5><? echo($this->lang->line('admin_tpl_structure')) ?></h5></td></tr>
              <tr>
                <td style="width:85px"><h5><? echo($this->lang->line('admin_tpl_operation')) ?></h5></td>
                <td style="width:250px"><h5><? echo($this->lang->line('admin_tpl_name')) ?></h5></td>
                <td style="width:100px"><h5><? echo($this->lang->line('admin_tpl_position')) ?></h5></td>
                <td style="width:100px"><h5><? echo($this->lang->line('admin_tpl_amount')) ?></h5></td>
                <td style="width:100px"><h5><? echo($this->lang->line('admin_tpl_price')) ?></h5></td>
                <td style="width:100px"><h5><? echo($this->lang->line('admin_tpl_sum')) ?></h5></td>
              </tr>
            </table>
          </td>
          <td class="header_title center"><h5><? echo($this->lang->line('admin_tpl_portfolio_cost')) ?></h5></td>
          <td class="header_title center"><h5><? echo($this->lang->line('admin_tpl_rest')) ?></h5></td>
          <td class="header_title"></td>
        </tr>
        <? foreach($data as $item) { ?>
        <tr<? echo($item->css_class) ?>>
          <td class="item center"><? echo($item->method) ?></td>
          <td class="item center"><? echo($item->created) ?></td>
          <td class="item" style="width:700px">
            <? if (isset($item->instruments)) { ?>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" style="border:none;margin:0;padding:0;">
              <? foreach($item->instruments as $i) { ?>
              <tr>
                <td class="center" style="width:100px"><font color="<? echo(($i->operation=='buy')?'green':(($i->operation=='sell')?'red':'blue')) ?>"><? echo($i->operation) ?></font></td>
                <td class="left" style="width:250px"><font color="<? echo(($i->operation=='sell')?'silver':'black') ?>"><? echo(!is_null($i->isin)?$i->name.' ('.$i->isin.')':$i->name) ?></font></td>
                <td class="center" style="width:100px"><font color="<? echo(($i->operation=='sell')?'silver':(($i->position=='long')?'green':'red')) ?>"><? echo($i->position) ?></font></td>
                <td class="right" style="width:100px" nowrap><font color="<? echo(($i->operation=='sell')?'silver':'black') ?>"><? echo(number_format($i->amount,0,'.',' ')) ?></font></td>
                <td class="right" style="width:100px" nowrap><font color="<? echo(($i->operation=='sell')?'silver':'black') ?>"><? echo($i->value) ?></font></td>
                <td class="right" style="width:100px" nowrap><font color="<? echo(($i->operation=='sell')?'silver':'black') ?>"><?echo(number_format($i->total,2,'.',' ')) ?></font></td>
              </tr>
              <? } ?>
            </table>
            <? } ?>
          </td>
          <td class="item right"><b><? echo((trim($item->cost)!='')?number_format($item->cost,2,'.',' '):'') ?></b></td>
          <td class="item right"><? echo((trim($item->rest)!='')?number_format($item->rest,2,'.',' '):'') ?></td>
          <td class="item">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><a href="<? echo($this->phpself.$this->page_name.'/del/'.$item->portfolio_id.'/'.$item->portfolio_history_id) ?>" title="<? echo($this->lang->line('admin_tpl_del')) ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')) ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')) ?>" width="16" height="16" /></a></td>
              </tr>
            </table>
          </td>
        </tr>
        <? } } else { ?>
        <tr>
          <td class="item"><h4><? echo($this->lang->line('admin_tpl_not_found')) ?></h4></td>
        </tr>
        <? } ?>
      </table>
      <? echo(isset($pages)?$pages:'') ?>
    </td>
  </tr>  
</table>