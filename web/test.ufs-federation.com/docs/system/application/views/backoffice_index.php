<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="<? echo($this->base_url); ?>favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<? echo($this->base_url); ?>css/main_admin.css" type="text/css" />
    <script type="text/javascript" src="<? echo($this->base_url); ?>js/jquery.js"></script>
    <script type="text/javascript" src="<? echo($this->base_url); ?>js/light_gallery_admin.js"></script>
    <title><? echo($title); ?> - <? echo($this->lang->line('admin_title')); ?></title>
  </head>
  <body>
    
  <? if (isset($access_denied)) { echo ($access_denied); } ?>
  <? if (isset($message_content)) { echo ($message_content); } ?>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" align="center" class="content_top">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="header"><a>UFS IC</a></td>
              <td width=5px></td>
              <td>
                <? if (isset($menu)) { ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <? foreach($menu as $mk=>$mv) { ?>
                  <tr>
                    <td style="text-align:right;vertical-align:top;padding-top:5px;"><font color="white"><b><? echo($mk) ?></b></font>&nbsp</td>
                    <td>
                      <ul class="menu">
                        <? foreach($mv as $k=>$v) { ?>
                        <li<? echo($v->sel) ?>><a href="<? echo($this->phpself.$k) ?>"><? echo($v->name) ?></a></li>
                        <? } ?>
                      </ul>
                    </td>
                  </tr>
                  <? } ?>
                </table>  
               <? } else { ?>
               &nbsp;
               <? } ?>  
             </td>
             <td valign="top" align="right">
               <? if(isset($logout_and_change_pass)){ ?>
               <table border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td align=right>
                      <p><b><? echo($this->login); ?></b></p>
                    </td>
                    <td>&nbsp;|&nbsp;</td>
                    <td nowrap="nowrap">
                      <a href="<? echo($this->phpself); ?>logout"><img src="<? echo($this->base_url); ?>images/status_busy.png" alt="" class="icon" /> <? echo($this->lang->line('admin_link_logout')); ?></a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                     <select name="lang" class="select lang" onchange="javascript:self.location.href='<? echo($this->phpself.'lang_select/'); ?>'+this.value">
                       <? foreach($langs as $item){ ?>
                       <option<? echo($item->select); ?> value="<? echo($item->lang); ?>"><? echo($item->name); ?></option>
                       <? } ?>
                     </select>
                    </td>
                    <td>&nbsp;|&nbsp;</td>
                    <td nowrap="nowrap">
                      <a href="<? echo($this->phpself); ?>change_pass"><img src="<? echo($this->base_url); ?>images/key_go.png" alt="" class="icon" /> <? echo($this->lang->line('admin_link_change_password')); ?></a>    
                    </td>
                  </tr>
                </table>
                <? }else{ ?>
                &nbsp;
                <? } ?>  
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td valign="top" align="center" class="content">
        <? echo($content); ?>  
        </td>
      </tr>
    </table>
  </body>
</html>