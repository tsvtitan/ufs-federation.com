<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center"><h2><? echo($this->tpl_header) ?></h2></td>
  </tr>
  <tr>
    <td align="left">
      <? if(isset($error)) { ?>
      <b><? echo($this->lang->line('admin_tpl_error')) ?>:</b><br />
      <ul>
        <? foreach($error as $item) { ?>
        <li><? echo($item) ?></li>
        <? } ?>
      </ul>
      <? } else { ?>
        &nbsp;
      <? } ?>
    </td>
  </tr>
  <tr>
    <td valign="top" align="center">
      <form action="<? echo($this->uri->uri_string) ?>" method="post" enctype="multipart/form-data">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="test" name="data[name]" value="<? echo(isset($name)?$name:'') ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_ticker')); ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" name="data[ticker]" value="<? echo(isset($ticker)?$ticker:'') ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_priority')) ?></h5></td>
          </tr>
          <tr>
            <td class="item">
              <table width=100% border=0 cellspacing=0 cellpadding=0>
                <tr>
                  <td style="padding:3px 0px"><input style="width:450px" type="text" name="data[priority]" value="<? echo(isset($priority)?$priority:'') ?>" class="text" /></td>
                  <td nowrap><label><input class="checkbox" style="margin-right: 5px;" type="checkbox"<? echo(isset($finished)?'':' checked value="1"'); ?> name="visible"><? echo($this->lang->line('admin_tpl_visible')) ?></label></td>
                </tr>
              </table>
            </td>
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
                  <? if(isset($emitent_id)) { ?>
                  <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$emitent_id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                  <? } ?>
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