<?php /* Smarty version 2.6.14, created on 2014-09-11 10:52:48
         compiled from file:/var/www/html/modules/reports_break/themes/default/filter.tpl */ ?>
<form method="POST" style="margin-bottom:0;" action="?menu=<?php echo $this->_tpl_vars['module_name']; ?>
">
  <table width="99%" border="0" cellspacing="0" cellpadding="4" align="center">
      <tr class="letra12">
        <td width="8%" align="right"><?php echo $this->_tpl_vars['txt_fecha_init']['LABEL']; ?>
: <span  class="required">*</span></td>
        <td width="12%" align="left" nowrap><?php echo $this->_tpl_vars['txt_fecha_init']['INPUT']; ?>
</td>
        <td width="8%" align="right"><?php echo $this->_tpl_vars['txt_fecha_end']['LABEL']; ?>
: <span  class="required">*</span></td>
        <td width="12%" align="left" nowrap><?php echo $this->_tpl_vars['txt_fecha_end']['INPUT']; ?>
</td>
        <td width="8%" align="center">
            <input class="button" type="submit" name="submit_fecha" value="<?php echo $this->_tpl_vars['btn_consultar']; ?>
" >
        </td>
      </tr>
   </table>
</form>