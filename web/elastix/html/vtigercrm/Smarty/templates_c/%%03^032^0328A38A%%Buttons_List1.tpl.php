<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:08
         compiled from Buttons_List1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'Buttons_List1.tpl', 17, false),array('modifier', 'vtiger_imageurl', 'Buttons_List1.tpl', 43, false),)), $this); ?>
<script type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/<?php echo $this->_tpl_vars['MODULE']; ?>
.js"></script>

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<?php $this->assign('MODULELABEL', getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE'])); ?>
	<?php if ($this->_tpl_vars['CATEGORY'] == 'Settings'): ?>
<!-- No List View in Settings - Action is index -->
		<td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap><a class="hdrLink" href="index.php?action=index&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['MODULELABEL']; ?>
</a></td>
	<?php else: ?>
		<td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['CATEGORY']]; ?>
 > <a class="hdrLink" href="index.php?action=ListView&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['MODULELABEL']; ?>
</a></td>
	<?php endif; ?>
	<td width=100% nowrap>
	
		<table border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td class="sep1" style="width:1px;"></td>
		<td class=small >
			<!-- Add and Search -->
			<table border=0 cellspacing=0 cellpadding=0>
			<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes'): ?>
			        		<?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
		                      	        	<td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" id="showSubMenu"  onMouseOver="fnvshobj(this,'reportLay');" onMouseOut="fninvsh('reportLay');"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Add.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['SINGLE_MOD'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." title="<?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['SINGLE_MOD'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." border=0></a></td>
                	   			 <?php else: ?>
	                        		       	<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Add.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['SINGLE_MOD'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." title="<?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['SINGLE_MOD'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." border=0></a></td>
			                       	<?php endif; ?>
					<?php else: ?>
						<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('btnL3Add-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border=0></td>	
					<?php endif; ?>
									
						<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('btnL3Search-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border=0></td>
					
				</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Calendar Clock Calculator and Chat -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<?php if ($this->_tpl_vars['CALENDAR_DISPLAY'] == 'true'): ?> 
 		                                                <?php if ($this->_tpl_vars['CATEGORY'] == 'Settings' || $this->_tpl_vars['CATEGORY'] == 'Tools' || $this->_tpl_vars['CATEGORY'] == 'Analytics'): ?> 
 		                                                        <?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes'): ?> 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>
" border=0></a></a></td> 
						<?php else: ?>
                                                <td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('btnL3Calendar-Faded.gif', $this->_tpl_vars['THEME']); ?>
"></td>
                                                <?php endif; ?>
					<?php else: ?>
						<?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes'): ?> 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
");'><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>
" border=0></a></a></td> 
 		                                                        <?php else: ?> 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('btnL3Calendar-Faded.gif', $this->_tpl_vars['THEME']); ?>
"></td> 
								<?php endif; ?>
 		                               <?php endif; ?> 
					<?php endif; ?>
					<?php if ($this->_tpl_vars['WORLD_CLOCK_DISPLAY'] == 'true'): ?> 
 		                                                <td style="padding-right:0px"><a href="javascript:;"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Clock.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td> 
 		                                        <?php endif; ?> 
 		                                        <?php if ($this->_tpl_vars['CALCULATOR_DISPLAY'] == 'true'): ?> 
 		                                                <td style="padding-right:0px"><a href="#"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calc.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td> 
 		                                        <?php endif; ?> 
 		                                        <?php if ($this->_tpl_vars['CHAT_DISPLAY'] == 'true'): ?> 
 		                                                <td style="padding-right:0px"><a href="javascript:;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarChat.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CHAT_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CHAT_TITLE']; ?>
" border=0></a> 
 		                                        <?php endif; ?> 
				</td>
					<td style="padding-right:10px"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Tracker.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" border=0 onClick="fnvshobj(this,'tracker');">
                    			</td>	
				</tr>
				</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Import / Export -->
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>

			
			<?php if ($this->_tpl_vars['MODULE'] == 'Vendors' || $this->_tpl_vars['MODULE'] == 'HelpDesk' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Potentials' || $this->_tpl_vars['MODULE'] == 'Products' || $this->_tpl_vars['MODULE'] == 'Documents' || $this->_tpl_vars['CUSTOM_MODULE'] == 'true'): ?>
		   		<?php if ($this->_tpl_vars['CHECK']['Import'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Documents'): ?>	
					<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=Import&step=1&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=index&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarImport.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" border="0"></a></td>	
				<?php else: ?>	
					<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('tbarImport-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>	
				<?php endif; ?>	
				<?php if ($this->_tpl_vars['CHECK']['Export'] == 'yes'): ?>
				<td style="padding-right:10px"><a name='export_link' href="javascript:void(0)" onclick="return selectedRecords('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarExport.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" border="0"></a></td>			
			
				<?php else: ?>	
					<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('tbarExport-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
                <?php endif; ?>
			<?php else: ?>
				<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('tbarImport-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
                <td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('tbarExport-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Products' || $this->_tpl_vars['MODULE'] == 'HelpDesk' || $this->_tpl_vars['MODULE'] == 'Potentials' || $this->_tpl_vars['MODULE'] == 'Vendors'): ?> 
				<?php if ($this->_tpl_vars['VIEW'] == true): ?>
					<td style="padding-right:10px"><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=FindDuplicateRecords&button_view&list_view"><img src="<?php echo vtiger_imageurl('findduplicates.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPICATES']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPLICATES']; ?>
" border="0"></a></td>
				<?php else: ?>
					<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('FindDuplicates-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>	
				<?php endif; ?>
			<?php else: ?>
				<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('FindDuplicates-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
			<?php endif; ?>
			</tr>
			</table>	
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- All Menu -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-left:10px;"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3AllMenu.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_TITLE']; ?>
" border="0"></a></td>
				<?php if ($this->_tpl_vars['CHECK']['moduleSettings'] == 'yes'): ?>
	        		<td style="padding-left:10px;"><a href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=Settings'><img src="<?php echo vtiger_imageurl('settingsBox.png', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
 <?php echo $this->_tpl_vars['APP']['LBL_SETTINGS']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
 <?php echo $this->_tpl_vars['APP']['LBL_SETTINGS']; ?>
" border="0"></a></td>
				<?php endif; ?>
				</tr>
				</table>
		</td>			
		</tr>
		</table>
	</td>
</tr>
</TABLE>