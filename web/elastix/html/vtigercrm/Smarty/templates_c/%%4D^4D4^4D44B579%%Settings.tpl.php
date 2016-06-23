<?php /* Smarty version 2.6.18, created on 2014-09-02 14:22:21
         compiled from modules/Vtiger/Settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'modules/Vtiger/Settings.tpl', 15, false),)), $this); ?>
<br />
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
	<tr>
		<td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
	    <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	    	<br />
	    	<div align=center>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SetMenu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<table class="settingsSelUITopLine" align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
			    	
					<td rowspan="2" valign="top" width="50"><img src="<?php echo vtiger_imageurl('vtlib_modmng.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['MOD']['LBL_MODULE_MANAGER']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_MODULE_MANAGER']; ?>
" border="0" height="48" width="48"></td>
					<td class="heading2" valign="bottom"> <b><a href="index.php?module=Settings&action=ModuleManager&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_MODULE_MANAGER']; ?>
</a> &gt; <?php echo $this->_tpl_vars['MODULE_LBL']; ?>
 </td>
				</tr>
				<tr>
					<td class="small" valign="top"><?php echo $this->_tpl_vars['MOD']['VTLIB_LBL_MODULE_MANAGER_DESCRIPTION']; ?>
</td>
				</tr>
				</table>
				
				<br>
				<table border="0" cellspacing="0" cellpadding="20" width="100%" class="settingsUI">
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="10" width="100%">
								<tr>
									<?php $_from = $this->_tpl_vars['MENU_ARRAY']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['itr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['itr']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['mod_name'] => $this->_tpl_vars['mod_array']):
        $this->_foreach['itr']['iteration']++;
?>
									<td width=25% valign=top>
										<?php if ($this->_tpl_vars['mod_array']['label'] == ''): ?>
											&nbsp;
										<?php else: ?>
										<table border=0 cellspacing=0 cellpadding=5 width="100%">
											<tr>
												<?php $this->assign('count', $this->_foreach['itr']['iteration']); ?>
												<td rowspan=2 valign=top width="20%">
													<a href="<?php echo $this->_tpl_vars['mod_array']['location']; ?>
">
													<img src="<?php echo $this->_tpl_vars['mod_array']['image_src']; ?>
" alt="<?php echo $this->_tpl_vars['mod_array']['label']; ?>
" width="48" height="48" border=0 title="<?php echo $this->_tpl_vars['mod_array']['label']; ?>
">
													</a>
												</td>
												<td class=big valign=top>
													<a href="<?php echo $this->_tpl_vars['mod_array']['location']; ?>
">
													<?php echo $this->_tpl_vars['mod_array']['label']; ?>

													</a>
												</td>
											</tr>
											<tr>
												<td class="small" valign=top width="80%">
													<?php echo $this->_tpl_vars['mod_array']['desc']; ?>

												</td>
											</tr>
										</table>
										<?php endif; ?>
									</td>
									<?php if ($this->_tpl_vars['count'] % 3 == 0): ?>
										</tr><tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
			</div>
		</td>
		<td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
	</tr>
</table>
<br>