<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:08
         compiled from DisplayFields.tpl */ ?>

<?php $this->assign('fromlink', ""); ?>

<!-- Added this file to display the fields in Create Entity page based on ui types  -->
<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['subdata']):
?>
	<?php if ($this->_tpl_vars['header'] == 'Product Details'): ?>
		<tr>
	<?php else: ?>
		<tr style="height:25px">
	<?php endif; ?>
	<?php $_from = $this->_tpl_vars['subdata']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mainlabel'] => $this->_tpl_vars['maindata']):
?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'EditViewUI.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endforeach; endif; unset($_from); ?>
   </tr>
<?php endforeach; endif; unset($_from); ?>