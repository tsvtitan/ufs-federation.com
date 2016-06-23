<?php /* Smarty version 2.6.14, created on 2014-09-11 10:52:25
         compiled from /var/www/html/modules/campaign_out/themes/default/filter-list-campaign.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', '/var/www/html/modules/campaign_out/themes/default/filter-list-campaign.tpl', 4, false),)), $this); ?>
<table width="100%" border="0">
<tr>
    <td align="right" class="letra12" width="20%" ><b><?php echo $this->_tpl_vars['LABEL_CAMPAIGN_STATE']; ?>
:</b></td>
    <td><?php echo smarty_function_html_options(array('name' => 'cbo_estado','id' => 'cbo_estado','options' => $this->_tpl_vars['estados'],'selected' => $this->_tpl_vars['estado_sel'],'onchange' => 'submit();'), $this);?>
</td>
    <td align="right"><a href="?menu=<?php echo $this->_tpl_vars['MODULE_NAME']; ?>
&amp;action=new_campaign"><b><?php echo $this->_tpl_vars['LABEL_CREATE_CAMPAIGN']; ?>
&nbsp;&raquo;</b></a></td>
</tr>
<tr>
    <td align="right" class='letra12' width='20%'><b><?php echo $this->_tpl_vars['LABEL_WITH_SELECTION']; ?>
:</b></td>
    <td colspan='2'><input class="button" type="submit" name="activate" value="<?php echo $this->_tpl_vars['LABEL_ACTIVATE']; ?>
" />&nbsp;
        <input class="button" type="submit" name="deactivate" value="<?php echo $this->_tpl_vars['LABEL_DEACTIVATE']; ?>
" onclick="return confirmSubmit('<?php echo $this->_tpl_vars['MESSAGE_CONTINUE_DEACTIVATE']; ?>
')" />&nbsp;
        <input class="button" type="submit" name="delete" value="<?php echo $this->_tpl_vars['LABEL_DELETE']; ?>
" onclick="return confirmSubmit('<?php echo $this->_tpl_vars['MESSAGE_CONTINUE_DELETE']; ?>
')" />
     </td>
</tr>
</table>
