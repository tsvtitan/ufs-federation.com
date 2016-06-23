<?php /* Smarty version 2.6.18, created on 2014-09-02 13:38:32
         compiled from Settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'Settings.tpl', 17, false),array('modifier', 'getTranslatedString', 'Settings.tpl', 43, false),)), $this); ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Buttons_List1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<br>
	<div align=center>
	<table border=0 cellspacing=0 cellpadding=20 width=90% class="settingsUI">
	<tr>
		<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
			<?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['BLOCKID'] => $this->_tpl_vars['BLOCKLABEL']):
?>
				<?php if ($this->_tpl_vars['BLOCKLABEL'] != 'LBL_MODULE_MANAGER'): ?>
				<tr>
					<td class="settingsTabHeader">
						<?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['BLOCKLABEL']]; ?>

					</td>
				</tr>
				<tr>
				<td class="settingsIconDisplay small">
					<table border=0 cellspacing=0 cellpadding=10 width=100%>
						<tr>
						<?php $_from = $this->_tpl_vars['FIELDS'][$this->_tpl_vars['BLOCKID']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['itr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['itr']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['itr']['iteration']++;
?>
							<td width=25% valign=top>
							<?php if ($this->_tpl_vars['data']['name'] == ''): ?>
								&nbsp;
							<?php else: ?>							
							<table border=0 cellspacing=0 cellpadding=5 width=100%>
								<tr>
									<?php $this->assign('label', getTranslatedString($this->_tpl_vars['data']['name'], 'Settings')); ?>
									<?php $this->assign('count', $this->_foreach['itr']['iteration']); ?>
									<td rowspan=2 valign=top>
										<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
">
											<img src="<?php echo vtiger_imageurl($this->_tpl_vars['data']['icon'], $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['label']; ?>
" width="48" height="48" border=0 title="<?php echo $this->_tpl_vars['label']; ?>
">
										</a>
									</td>
									<td class=big valign=top>
										<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
">
											<?php echo $this->_tpl_vars['label']; ?>

										</a>
									</td>
								</tr>
								<tr>
									<?php $this->assign('description', getTranslatedString($this->_tpl_vars['data']['description'], 'Settings')); ?>
									<td class="small" valign=top>
										<?php echo $this->_tpl_vars['description']; ?>

									</td>
								</tr>
							</table>
							<?php endif; ?>
							</td>
						<?php if ($this->_tpl_vars['count'] % $this->_tpl_vars['NUMBER_OF_COLUMNS'] == 0): ?>
							</tr><tr>
						<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
						</table>
					</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>