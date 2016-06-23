<?php /* Smarty version 2.6.18, created on 2014-09-02 08:32:17
         compiled from Settings/ModuleManager/ModuleManagerAjax.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'implode', 'Settings/ModuleManager/ModuleManagerAjax.tpl', 17, false),array('modifier', 'vtiger_imageurl', 'Settings/ModuleManager/ModuleManagerAjax.tpl', 59, false),)), $this); ?>
<script type="text/javascript">
<?php echo '
function vtlib_modulemanager_toggleTab(shownode, hidenode, highlighttab, dehighlighttab) {
	if($(shownode)) $(shownode).show();
	if($(hidenode)) $(hidenode).hide();
	if($(highlighttab)) { $(highlighttab).addClassName(\'dvtSelectedCell\'); $(highlighttab).removeClassName(\'dvtUnSelectedCell\'); }
	if($(dehighlighttab)) { $(dehighlighttab).addClassName(\'dvtUnSelectedCell\'); $(dehighlighttab).removeClassName(\'dvtSelectedCell\'); }
}
'; ?>

</script>

<?php if ($this->_tpl_vars['DIR_NOTWRITABLE_LIST'] && ! empty ( $this->_tpl_vars['DIR_NOTWRITABLE_LIST'] )): ?>
<table class="small" width="100%" cellpadding=0 cellspacing=0 border=0>
	<tr>
		<td>
			<div style='background-color: #FFFABF; padding: 2px; margin: 0 0 2px 0; border: 1px solid yellow'>
			<b style='color: red'><?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_WARNING']; ?>
:</b> <?php echo implode($this->_tpl_vars['DIR_NOTWRITABLE_LIST'], ', '); ?>
 <b><?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_NOT_WRITEABLE']; ?>
!</b>
		</td>
	</tr>
</table>
<?php endif; ?>	

<table class="small" width="100%" cellpadding=2 cellspacing=0 border=0>
	<tr>
		<td class="dvtTabCache" style="width: 10px;" nowrap>&nbsp;</td>
		<td class="dvtSelectedCell" style="width: 120px;" align="center" nowrap id="modmgr_standard_tab"
			onclick="vtlib_modulemanager_toggleTab('modmgr_standard','modmgr_custom','modmgr_standard_tab','modmgr_custom_tab');">
		<?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_MODULE_MANAGER_STANDARDMOD']; ?>
</td>
		<td class="dvtTabCache" style="width: 10px;" nowrap>&nbsp;</td>
		<td class="dvtUnSelectedCell" style="width: 120px;" align="center" nowrap id="modmgr_custom_tab" 
			onclick="vtlib_modulemanager_toggleTab('modmgr_custom','modmgr_standard','modmgr_custom_tab','modmgr_standard_tab');">
		<?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_MODULE_MANAGER_CUSTOMMOD']; ?>
</td>
		<td class="dvtTabCache" style="width: 10px;" nowrap>&nbsp;</td>
	</tr>
</table>

<!-- Custom Modules -->
<table border=0 cellspacing=0 cellpadding=3 width=100% class="listRow" id="modmgr_custom" style='display: none;'>
<tr>
	<td class="big tableHeading" colspan=2>&nbsp;</td>
	<td class="big tableHeading" colspan=4 width="10%" align="right">
		<form style="display: inline;" action="index.php?module=Settings&action=ModuleManager&module_import=Step1&parenttab=Settings" method="POST">
			<input type="submit" class="crmbutton small create" value='<?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_NEW']; ?>
' title='<?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
'>
		</form>
	</td>
</tr>
<tr>
</tr>

<?php $this->assign('totalCustomModules', '0'); ?>

<?php $_from = $this->_tpl_vars['TOGGLE_MODINFO']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['modulename'] => $this->_tpl_vars['modinfo']):
?>
<?php if ($this->_tpl_vars['modinfo']['customized'] == true): ?>
	<?php $this->assign('totalCustomModules', $this->_tpl_vars['totalCustomModules']+1); ?>

	<?php $this->assign('modulelabel', $this->_tpl_vars['modulename']); ?>
	<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['modulename']]): ?><?php $this->assign('modulelabel', $this->_tpl_vars['APP'][$this->_tpl_vars['modulename']]); ?><?php endif; ?>
	<tr>
		<td class="cellText small" width="20px"><img src="<?php echo vtiger_imageurl('uparrow.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
		<td class="cellLabel small"><?php echo $this->_tpl_vars['modulelabel']; ?>
</td>
		<td class="cellText small" width="15px" align=center>
			<a href="index.php?module=Settings&action=ModuleManager&module_update=Step1&src_module=<?php echo $this->_tpl_vars['modulename']; ?>
&parenttab=Settings"><img src="<?php echo vtiger_imageurl('reload.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_UPGRADE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_UPGRADE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
		</td>
		<td class="cellText small" width="15px" align=center>
		<?php if ($this->_tpl_vars['modinfo']['presence'] == 0): ?>
			<a href="javascript:void(0);" onclick="vtlib_toggleModule('<?php echo $this->_tpl_vars['modulename']; ?>
', 'module_disable');"><img src="<?php echo vtiger_imageurl('enabled.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_DISABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_DISABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
		<?php else: ?>
			<a href="javascript:void(0);" onclick="vtlib_toggleModule('<?php echo $this->_tpl_vars['modulename']; ?>
', 'module_enable');"><img src="<?php echo vtiger_imageurl('disabled.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_ENABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_ENABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
		<?php endif; ?>
		</td>
		<td class="cellText small" width="15px" align=center>
			<?php if ($this->_tpl_vars['modulename'] == 'Calendar' || $this->_tpl_vars['modulename'] == 'Home'): ?>
				<img src="<?php echo vtiger_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle">
			<?php else: ?>
				<a href="index.php?modules=Settings&action=ModuleManagerExport&module_export=<?php echo $this->_tpl_vars['modulename']; ?>
"><img src="<?php echo vtiger_imageurl('webmail_uparrow.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
			<?php endif; ?>
		</td>
		<td class="cellText small" width="15px" align=center>
			<?php if ($this->_tpl_vars['modinfo']['presence'] == 0 && $this->_tpl_vars['modinfo']['hassettings']): ?><a href="index.php?module=Settings&action=ModuleManager&module_settings=true&formodule=<?php echo $this->_tpl_vars['modulename']; ?>
&parenttab=Settings"><img src="<?php echo vtiger_imageurl('Settings.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['modulelabel']; ?>
 <?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
" title="<?php echo $this->_tpl_vars['modulelabel']; ?>
 <?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
"></a>
			<?php elseif ($this->_tpl_vars['modinfo']['hassettings'] == false): ?>&nbsp;
			<?php endif; ?>
		</td>
	</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['TOGGLE_LANGINFO']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['langprefix'] => $this->_tpl_vars['langinfo']):
?>
	<?php if ($this->_tpl_vars['langprefix'] != 'en_us'): ?>

	<?php $this->assign('totalCustomModules', $this->_tpl_vars['totalCustomModules']+1); ?>
	<tr>
		<td class="cellText small"><img src="<?php echo vtiger_imageurl('text.gif', $this->_tpl_vars['THEME']); ?>
" border=0"></td>
		<td class="cellLabel small"><?php echo $this->_tpl_vars['langinfo']['label']; ?>
</td>
		<td class="cellText small" width="15px" align=center>
			<a href="index.php?module=Settings&action=ModuleManager&module_update=Step1&src_module=<?php echo $this->_tpl_vars['langprefix']; ?>
&parenttab=Settings"><img src="<?php echo vtiger_imageurl('reload.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_UPGRADE']; ?>
 <?php echo $this->_tpl_vars['langinfo']['label']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_UPGRADE']; ?>
 <?php echo $this->_tpl_vars['langinfo']['label']; ?>
"></a>
		</td>
		<td class="cellText small" width="15px" align=center>
		<?php if ($this->_tpl_vars['langinfo']['active'] == 1): ?>
			<a href="javascript:void(0);" onclick="vtlib_toggleModule('<?php echo $this->_tpl_vars['langprefix']; ?>
', 'module_disable', 'language');"><img src="<?php echo vtiger_imageurl('enabled.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_DISABLE']; ?>
 Language <?php echo $this->_tpl_vars['langinfo']['label']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_DISABLE']; ?>
 Language <?php echo $this->_tpl_vars['langinfo']['label']; ?>
"></a>
		<?php else: ?>
			<a href="javascript:void(0);" onclick="vtlib_toggleModule('<?php echo $this->_tpl_vars['langprefix']; ?>
', 'module_enable', 'language');"><img src="<?php echo vtiger_imageurl('disabled.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_ENABLE']; ?>
 Language <?php echo $this->_tpl_vars['langinfo']['label']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_ENABLE']; ?>
 Language <?php echo $this->_tpl_vars['langinfo']['label']; ?>
"></a>
		<?php endif; ?>
		</td>
		<td class="cellText small" width="15px" align=center colspan=2>&nbsp;</td>
		<!--td class="cellText small" width="15px" align=center>
			<?php if ($this->_tpl_vars['modulename'] == 'Calendar' || $this->_tpl_vars['modulename'] == 'Home'): ?>
				<img src="<?php echo vtiger_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle">
			<?php else: ?>
				<a href="index.php?modules=Settings&action=ModuleManagerExport&module_export=<?php echo $this->_tpl_vars['modulename']; ?>
"><img src="themes/images/webmail_uparrow.gif" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
			<?php endif; ?>
		</td-->
	</tr>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['totalCustomModules'] == 0): ?>
	<tr>
		<td class="cellLabel small" colspan=4><b><?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_MODULE_MANAGER_NOMODULES']; ?>
</b></td>
	</tr>
<?php endif; ?>
</table>

<!-- Standard modules -->
<table border=0 cellspacing=0 cellpadding=3 width=100% class="listRow" id="modmgr_standard" style="">
<tr>
	<td class="big tableHeading" colspan=2>&nbsp;</td>
	<td class="big tableHeading" colspan=3 width=10% align="center">&nbsp;</td>
</tr>
<tr>
</tr>
<?php $_from = $this->_tpl_vars['TOGGLE_MODINFO']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['modulename'] => $this->_tpl_vars['modinfo']):
?>

<?php if ($this->_tpl_vars['modinfo']['customized'] == false): ?>
	<?php $this->assign('modulelabel', $this->_tpl_vars['modulename']); ?>
	<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['modulename']]): ?><?php $this->assign('modulelabel', $this->_tpl_vars['APP'][$this->_tpl_vars['modulename']]); ?><?php endif; ?>
	<tr>
		<!--td class="cellLabel small" width="20px">&nbsp;</td -->
		<td class="cellLabel small" colspan=2><?php echo $this->_tpl_vars['modulelabel']; ?>
</td>
		<td class="cellText small" width="15px" align=center>
			<?php if ($this->_tpl_vars['modinfo']['presence'] == 0): ?>
				<a href="javascript:void(0);" onclick="vtlib_toggleModule('<?php echo $this->_tpl_vars['modulename']; ?>
', 'module_disable');"><img src="<?php echo vtiger_imageurl('enabled.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_DISABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_DISABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
			<?php else: ?>
				<a href="javascript:void(0);" onclick="vtlib_toggleModule('<?php echo $this->_tpl_vars['modulename']; ?>
', 'module_enable');"><img src="<?php echo vtiger_imageurl('disabled.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['MOD']['LBL_ENABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_ENABLE']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
			<?php endif; ?>
		</td>
		<td class="cellText small" width="15px" align=center>&nbsp;</td>
		<!--td class="cellText small" width="15px" align=center>
			<?php if ($this->_tpl_vars['modulename'] == 'Calendar' || $this->_tpl_vars['modulename'] == 'Home'): ?>
				<img src="<?php echo vtiger_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle">
			<?php else: ?>
				<a href="index.php?modules=Settings&action=ModuleManagerExport&module_export=<?php echo $this->_tpl_vars['modulename']; ?>
"><img src="themes/images/webmail_uparrow.gif" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo $this->_tpl_vars['modulelabel']; ?>
"></a>
			<?php endif; ?>
		</td-->
		<td class="cellText small" width="15px" align=center>
			<?php if ($this->_tpl_vars['modinfo']['presence'] == 0 && $this->_tpl_vars['modinfo']['hassettings']): ?><a href="index.php?module=Settings&action=ModuleManager&module_settings=true&formodule=<?php echo $this->_tpl_vars['modulename']; ?>
&parenttab=Settings"><img src="<?php echo vtiger_imageurl('Settings.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" alt="<?php echo $this->_tpl_vars['modulelabel']; ?>
 <?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
" title="<?php echo $this->_tpl_vars['modulelabel']; ?>
 <?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
"></a>
			<?php elseif ($this->_tpl_vars['modinfo']['hassettings'] == false): ?>&nbsp;
			<?php endif; ?>
		</td>
	</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

</table>
