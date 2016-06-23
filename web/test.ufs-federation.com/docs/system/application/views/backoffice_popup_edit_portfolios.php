<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center"><h2><? echo($header) ?></h2></td>
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
      <form action="<? echo($this->uri->uri_string) ?>" method="post">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')) ?></h5></td>
          </tr>
          <tr>
            <td class="item"><input type="text" name="data[name]" value="<? echo(isset($name)?$name:'') ?>" class="text" /></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_description')) ?></h5></td>
          </tr>
          <tr>
            <td class="item"><textarea class="textarea" style="height: 50px;" name="data[description]"><? echo(isset($description)?$description:'') ?></textarea></td>
          </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_investment')) ?></h5></td>
          </tr>
          <tr>
            <td class="item">
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td style="width:75%"><input type="text" name="data[investment]" value="<? echo(isset($investment)?$investment:'') ?>" class="text" style="width:95%"></td>
                  <td>
                    <? if(isset($currency)) { ?>
                    <select name="data[currency_id]">
                      <? foreach($currency as $c) { ?>
                      <option value="<? echo($c->currency_id) ?>"<? echo((isset($currency_id) && ($currency_id==$c->currency_id))?' selected':'') ?>><? echo($c->name) ?></option>
                      <? } ?>
                    </select>
                    <? } ?>
                  </td>
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
                  <td><input type="image" src="<? echo($this->base_url) ?>images/save1.gif" /></td>
                  <td><a href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3)) ?>"><img src="<? echo($this->base_url) ?>images/cancel.gif"></a></td>
                  <td width="100%">&nbsp;</td>
                  <? if(isset($portfolio_id)) { ?>
                  <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/'.$this->uri->segment(3).'/del/'.$portfolio_id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')) ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                  <? } ?>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <? if(isset($portfolio_id)){ ?>
        <input type="hidden" name="id" value="<? echo($portfolio_id); ?>" class="hidden" />
        <? } ?>
        <input type="hidden" name="submit" value="submit" class="hidden" />
      </form>
    </td>
  </tr>  
</table>