<?php /* Smarty version 2.6.18, created on 2014-09-02 14:35:11
         compiled from QuickCreateHidden.tpl */ ?>
<?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
	<?php echo '
	<form name="QcEditView" onSubmit="if(getFormValidate(\'qcform\')) { VtigerJS_DialogBox.block(); return true;} else { return false; }" method="POST" action="index.php"  ENCTYPE="multipart/form-data">
	'; ?>

<?php else: ?>
	<?php echo '
	<form name="QcEditView" onSubmit="if(getFormValidate(\'qcform\')) { VtigerJS_DialogBox.block(); return true;} else { return false; }" method="POST" action="index.php">
	'; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
	<input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
">
<?php elseif ($this->_tpl_vars['MODULE'] == 'Events'): ?>
        <input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
">
<?php endif; ?>
	<input type="hidden" name="record" value="">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">