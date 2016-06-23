<?php /* Smarty version 2.6.14, created on 2014-09-10 17:40:35
         compiled from _common/login.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Elastix - <?php echo $this->_tpl_vars['PAGE_NAME']; ?>
</title>
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">-->
<link rel="stylesheet" href="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/styles.css">
</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table cellspacing=0 cellpadding=0 width="100%" border=0>
    <tr>
      <td>
        <table cellSpacing="0" cellPadding="0" width="100%" border="0">
          <tr>
            <td class="menulogo" width=380><img src="images/logo_elastix.png" width="233" height="75" /></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<form method="POST">
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="498" class="menudescription">
      <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr>
          <td>
              <div align="left"><font color="#ffffff">&nbsp;&raquo;&nbsp;<?php echo $this->_tpl_vars['WELCOME']; ?>
</font></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="498" bgcolor="#ffffff">
      <table width="100%" border="0" cellspacing="0" cellpadding="8" class="tabForm">
        <tr>
          <td colspan="2">
            <div align="center"><?php echo $this->_tpl_vars['ENTER_USER_PASSWORD']; ?>
<br><br></div>
          </td>
        </tr>
        <tr>
          <td>
              <div align="right"><?php echo $this->_tpl_vars['USERNAME']; ?>
:</div>
          </td>
          <td>
            <input type="text" id="input_user" name="input_user" style="color:#000000; FONT-FAMILY: verdana, arial, helvetica, sans-serif; FONT-SIZE: 8pt;
             font-weight: none; text-decoration: none; background: #fbfeff; border: 1 solid #000000;">
          </td>
        </tr>
        <tr>
          <td>
              <div align="right"><?php echo $this->_tpl_vars['PASSWORD']; ?>
:</div>
          </td>
          <td>
            <input type="password" name="input_pass" style="color:#000000; FONT-FAMILY: verdana, arial, helvetica, sans-serif; FONT-SIZE: 8pt;
             font-weight: none; text-decoration: none; background: #fbfeff; border: 1 solid #000000;">
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="submit" name="submit_login" value="<?php echo $this->_tpl_vars['SUBMIT']; ?>
" class="button">
          </td>
        </tr>
        <tr>
            <td colspan="2"><img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/0.gif" width="1" height="5"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<br>
    <div align="center" class="copyright"><a href="http://www.elastix.org" target='_blank'>Elastix</a> is licensed under <a href="http://www.opensource.org/licenses/gpl-license.php" target='_blank'>GPL</a> by <a href="http://www.palosanto.com" target='_blank'>PaloSanto Solutions</a>. 2006 - <?php echo $this->_tpl_vars['currentyear']; ?>
.</div>
<br>
<script type="text/javascript">
    document.getElementById("input_user").focus();
</script>
</body>
</html>