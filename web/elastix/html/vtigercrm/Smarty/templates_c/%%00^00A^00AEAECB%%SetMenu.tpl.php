<?php /* Smarty version 2.6.18, created on 2014-09-02 08:32:17
         compiled from SetMenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'SetMenu.tpl', 30, false),array('modifier', 'vtiger_imageurl', 'SetMenu.tpl', 65, false),)), $this); ?>
<?php 
	//add the settings page values
	$this->assign("BLOCKS",getSettingsBlocks());
	$this->assign("FIELDS",getSettingsFields());
 ?>

<table border=0 cellspacing=0 cellpadding=20 width="99%" class="settingsUI">
	<tr>
		<td valign=top>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
					<td valign=top id="settingsSideMenu" width="10%" >
						<!--Left Side Navigation Table-->
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
<?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['BLOCKID'] => $this->_tpl_vars['BLOCKLABEL']):
?>
	<?php if ($this->_tpl_vars['BLOCKLABEL'] != 'LBL_MODULE_MANAGER'): ?>
	<?php $this->assign('blocklabel', getTranslatedString($this->_tpl_vars['BLOCKLABEL'], 'Settings')); ?>
										<tr>
								<td class="settingsTabHeader" nowrap>
									<?php echo $this->_tpl_vars['blocklabel']; ?>

								</td>
							</tr>
		<?php $_from = $this->_tpl_vars['FIELDS'][$this->_tpl_vars['BLOCKID']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<?php if ($this->_tpl_vars['data']['link'] != ''): ?>
				<?php $this->assign('label', getTranslatedString($this->_tpl_vars['data']['name'], 'Settings')); ?>
				<?php if (( $_REQUEST['action'] == $this->_tpl_vars['data']['action'] && $_REQUEST['module'] == $this->_tpl_vars['data']['module'] )): ?>
							<tr>
								<td class="settingsTabSelected" nowrap>
									<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
">
										<?php echo $this->_tpl_vars['label']; ?>

									</a>
								</td>
							</tr>
				<?php else: ?>
							<tr>
								<td class="settingsTabList" nowrap>
									<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
">
										<?php echo $this->_tpl_vars['label']; ?>

									</a>
								</td>
							</tr>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
						</table>
						<!-- Left side navigation table ends -->
		
					</td>
					<td width="8px" valign="top"> 
						<img src="<?php echo vtiger_imageurl('panel-left.png', $this->_tpl_vars['THEME']); ?>
" title="Hide Menu" id="hideImage" style="display:inline;cursor:pointer;" onclick="toggleShowHide_panel('showImage','settingsSideMenu'); toggleShowHide_panel('showImage','hideImage');" />
						<img src="<?php echo vtiger_imageurl('panel-right.png', $this->_tpl_vars['THEME']); ?>
" title="Show Menu" id="showImage" style="display:none;cursor:pointer;" onclick="toggleShowHide_panel('settingsSideMenu','showImage'); toggleShowHide_panel('hideImage','showImage');"/>
					</td>
					<td class="small settingsSelectedUI" valign=top align=left>
						<script type="text/javascript">
<?php echo '
							function toggleShowHide_panel(showid, hideid){
								var show_ele = document.getElementById(showid);
								var hide_ele = document.getElementById(hideid);
								if(show_ele != null){ 
									show_ele.style.display = "";
									}
								if(hide_ele != null) 
									hide_ele.style.display = "none";
							}
'; ?>

						</script>