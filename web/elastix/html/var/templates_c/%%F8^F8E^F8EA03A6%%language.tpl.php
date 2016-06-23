<?php /* Smarty version 2.6.14, created on 2014-09-11 11:07:59
         compiled from file:/var/www/html/modules/language/themes/default/language.tpl */ ?>
<form method="POST">
<table width="99%" border="0" cellspacing="0" cellpadding="4" align="center">
    <tr class="letra12">
	<td>
        <?php if ($this->_tpl_vars['conectiondb']): ?>
        <input class="button" type="submit" name="save_language" value="<?php echo $this->_tpl_vars['CAMBIAR']; ?>
" >
        <?php else: ?>
        <?php echo $this->_tpl_vars['MSG_ERROR']; ?>

        <?php endif; ?>
	</td>
    </tr>
</table>
<table class="tabForm" style="font-size: 16px;" width="100%" >
    <tr class="letra12">
        <td width="9%"><b><?php echo $this->_tpl_vars['language']['LABEL']; ?>
:</b></td>
	<td width="35%"><?php echo $this->_tpl_vars['language']['INPUT']; ?>
</td>
    </tr>
</table>
</form>