<?php /* Smarty version 2.6.14, created on 2014-09-11 11:08:07
         compiled from /var/www/html/modules/agent_console/themes/default/login_agent.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', '/var/www/html/modules/agent_console/themes/default/login_agent.tpl', 82, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['LISTA_JQUERY_CSS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CURR_ITEM']):
?>
    <?php if ($this->_tpl_vars['CURR_ITEM'][0] == 'css'): ?>
<link rel="stylesheet" href='<?php echo $this->_tpl_vars['CURR_ITEM'][1]; ?>
' />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['CURR_ITEM'][0] == 'js'): ?>
<script type="text/javascript" src='<?php echo $this->_tpl_vars['CURR_ITEM'][1]; ?>
'></script>
    <?php endif;  endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['NO_EXTENSIONS']): ?>
<p><h4 align="center"><?php echo $this->_tpl_vars['LABEL_NOEXTENSIONS']; ?>
</h4></p>
<?php elseif ($this->_tpl_vars['NO_AGENTS']): ?>
<p><h4 align="center"><?php echo $this->_tpl_vars['LABEL_NOAGENTS']; ?>
</h4></p>
<?php else: ?>
<form method="POST"  action="index.php?menu=<?php echo $this->_tpl_vars['MODULE_NAME']; ?>
" onsubmit="do_login(); return false;">

<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="498"  class="menudescription">
      <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr>
          <td class="menudescription2">
              <div align="left"><font color="#ffffff">&nbsp;&raquo;&nbsp;<?php echo $this->_tpl_vars['WELCOME_AGENT']; ?>
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
<br/><br/></div>
          </td>
        </tr>
        <tr id="login_fila_estado" <?php echo $this->_tpl_vars['ESTILO_FILA_ESTADO_LOGIN']; ?>
>
          <td colspan="2">
            <div align="center" id="login_icono_espera" height='1'><img id="reloj" src="modules/<?php echo $this->_tpl_vars['MODULE_NAME']; ?>
/images/loading.gif" border="0" alt=""></div>
            <div align="center" style="font-weight: bold;" id="login_msg_espera"><?php echo $this->_tpl_vars['MSG_ESPERA']; ?>
</div>
            <div align="center" id="login_msg_error" style="color: #ff0000;"></div>
          </td>
        </tr>
        <tr>
          <td width="40%">
              <div align="right" id="label_agent_user"><?php echo $this->_tpl_vars['USERNAME']; ?>
:</div>
              <div align="right" id="label_extension_callback"><?php echo $this->_tpl_vars['CALLBACK_EXTENSION']; ?>
:</div>
          </td>
          <td width="60%">
                <select align="center" id="input_agent_user" name="input_agent_user">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['LISTA_AGENTES'],'selected' => $this->_tpl_vars['ID_AGENT']), $this);?>

                </select>
                <select align="center" id="input_extension_callback" name="input_extension_callback">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['LISTA_EXTENSIONES_CALLBACK'],'selected' => $this->_tpl_vars['ID_EXTENSION_CALLBACK']), $this);?>

                </select>
          </td>
        </tr>
        <tr>
          <td width="40%">
              <div align="right" id="label_extension"><?php echo $this->_tpl_vars['EXTENSION']; ?>
:</div>
              <div align="right" id="label_password_callback"><?php echo $this->_tpl_vars['PASSWORD']; ?>
:</div>
          </td>
          <td width="60%">
                <select align="center" name="input_extension" id="input_extension">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['LISTA_EXTENSIONES'],'selected' => $this->_tpl_vars['ID_EXTENSION']), $this);?>

                </select>
		<input type="password" name="input_password_callback" id="input_password_callback">
          </td>
        </tr>
<!-- Begin: CallbackLogin checkbox -->
	<tr>
          <td width="40%">
              <div align="center"><?php echo $this->_tpl_vars['CALLBACK_LOGIN']; ?>
:</div>
          </td>
          <td width="60%">               
	      <input type="checkbox" name="input_callback" id="input_callback">
          </td>
        </tr>
<!-- End: CallbackLogin checkbox -->
        <tr>
          <td colspan="2" align="center">
            <input type="button" id="submit_agent_login" name="submit_agent_login" value="<?php echo $this->_tpl_vars['LABEL_SUBMIT']; ?>
" class="button" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</form>

<?php if ($this->_tpl_vars['REANUDAR_VERIFICACION']): ?>
<script type="text/javascript">
do_checklogin();
</script>
<?php endif;  endif; ?>