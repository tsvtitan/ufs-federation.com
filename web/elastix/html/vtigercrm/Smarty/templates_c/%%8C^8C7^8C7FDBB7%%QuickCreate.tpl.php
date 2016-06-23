<?php /* Smarty version 2.6.18, created on 2014-09-02 14:35:11
         compiled from QuickCreate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'QuickCreate.tpl', 19, false),)), $this); ?>
<body class=small>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'QuickCreateHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table border="0" align="center" cellspacing="0" cellpadding="0" width="90%" class="mailClient mailClientBg">
<tr>
<td>
	<table border="0" cellspacing="0" cellpadding="0" width="100%" class=small>
	<tr>
		<td width="90%" class="mailSubHeader" background="<?php echo vtiger_imageurl('qcBg.gif', $this->_tpl_vars['THEME']); ?>
"><b ><?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo $this->_tpl_vars['QCMODULE']; ?>
</b></td>
		<td nowrap class="mailSubHeader moduleName" align=right><i><?php echo $this->_tpl_vars['APP']['LBL_QUICK_CREATE']; ?>
</i></td></tr>
	</table>

	<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
	<tr>
		<td>
		
			<!-- quick create UI starts -->
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="small" bgcolor="white" >
			<?php $this->assign('fromlink', 'qcreate'); ?>
			<?php $_from = $this->_tpl_vars['QUICKCREATE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subdata']):
?>
				<tr>
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
						
			</table>	
		
		<!-- save cancel buttons -->
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class=qcTransport>
			<tr>
				<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<td width=50% align=right><input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  if(getFormValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" ></td>
				<?php else: ?>
				<td width=50% align=right><input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" style="width:70px" ></td>
				<?php endif; ?>

				<td width="50%" align="left">
					<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="hide('qcform'); $('qccombo').options.selectedIndex=0;" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
				</td>
			</tr>
		</table>

		</td>
	</tr>
	</table>
</td>
</tr>
</table>
<?php if ($this->_tpl_vars['QCMODULE'] == 'Event'): ?>
<SCRIPT id="qcvalidate">
        var qcfieldname = new Array('subject','date_start','time_start','eventstatus','activitytype','due_date','time_end','due_date','time_end');
        var qcfieldlabel = new Array('Subject','Start Date & Time','Start Date & Time','Status','Activity Type','End Date & Time','End Date & Time','End Date & Time','End Date & Time');
        var qcfielddatatype = new Array('V~M','DT~M~time_start','T~O','V~O','V~O','D~M~OTH~GE~date_start~Start Date & Time','T~M','DT~M~time_end','T~O~OTH~GE~time_start~Start Date & Time');
</SCRIPT>
<?php elseif ($this->_tpl_vars['QCMODULE'] == 'Todo'): ?>
<SCRIPT id="qcvalidate">
	var qcfieldname = new Array('subject','date_start','time_start','taskstatus');
        var qcfieldlabel = new Array('Subject','Start Date & Time','Start Date & Time','Status');
        var qcfielddatatype = new Array('V~M','DT~M~time_start','T~O','V~O');
</SCRIPT>
<?php else: ?>
<SCRIPT id="qcvalidate">
        var qcfieldname = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDNAME']; ?>
);
        var qcfieldlabel = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDLABEL']; ?>
);
        var qcfielddatatype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDDATATYPE']; ?>
);
</SCRIPT>
<?php endif; ?>
</form>
</body>