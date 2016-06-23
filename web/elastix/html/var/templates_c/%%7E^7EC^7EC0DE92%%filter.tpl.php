<?php /* Smarty version 2.6.14, created on 2014-09-11 10:52:51
         compiled from file:/var/www/html/modules/calls_detail/themes/default/filter.tpl */ ?>
<table width="99%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr class="letra12">
            <td width="12%" align="right"><?php echo $this->_tpl_vars['date_start']['LABEL']; ?>
: <span  class="required">*</span></td>
            <td width="12%" align="left"  nowrap="nowrap"><?php echo $this->_tpl_vars['date_start']['INPUT']; ?>
</td>
            <td width="12%" align="right"><?php echo $this->_tpl_vars['date_end']['LABEL']; ?>
: <span  class="required">*</span></td>
            <td width="12%" align="left"  nowrap="nowrap"><?php echo $this->_tpl_vars['date_end']['INPUT']; ?>
</td>
            <td width="12%" align="center"><input class="button" type="submit" name="filter" value="<?php echo $this->_tpl_vars['Filter']; ?>
" /></td>
        </tr>
        <tr>
            <td width="12%" align="right"><?php echo $this->_tpl_vars['calltype']['LABEL']; ?>
:</td>
            <td width="12%" align="left" nowrap><?php echo $this->_tpl_vars['calltype']['INPUT']; ?>
</td>
            <td width="12%" align="right"><?php echo $this->_tpl_vars['phone']['LABEL']; ?>
:</td>
            <td width="12%" align="left" nowrap><?php echo $this->_tpl_vars['phone']['INPUT']; ?>
</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="12%" align="right"><?php echo $this->_tpl_vars['agent']['LABEL']; ?>
:</td>
            <td width="12%" align="left" nowrap><?php echo $this->_tpl_vars['agent']['INPUT']; ?>
</td>
            <td width="12%" align="right"><?php echo $this->_tpl_vars['queue']['LABEL']; ?>
:</td>
            <td width="12%" align="left" nowrap><?php echo $this->_tpl_vars['queue']['INPUT']; ?>
</td>
            <td>&nbsp;</td>
        </tr>
</table>
