<?php /* Smarty version 2.6.18, created on 2014-09-09 07:37:57
         compiled from Settings/LayoutBlockEntries.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'Settings/LayoutBlockEntries.tpl', 24, false),array('modifier', 'count', 'Settings/LayoutBlockEntries.tpl', 389, false),)), $this); ?>
			<form action="index.php" method="post" name="form" onsubmit="VtigerJS_DialogBox.block();">
				<input type="hidden" name="fld_module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
				<input type="hidden" name="module" value="Settings">
				<input type="hidden" name="parenttab" value="Settings">
				<input type="hidden" name="mode">
				<script language="JavaScript" type="text/javascript" src="include/js/customview.js"></script>
				<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
						
				<table class="listTable" border="0" cellpadding="3" cellspacing="0" width="100%">
					
					<?php $_from = $this->_tpl_vars['CFENTRIES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['entries']):
        $this->_foreach['outer']['iteration']++;
?>
						<?php if ($this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['RELPRODUCTSECTIONID'] || $this->_tpl_vars['entries']['blocklabel'] != ''): ?>
							<?php if (($this->_foreach['outer']['iteration'] <= 1) != true): ?>
							<tr><td><img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;</td></tr>
							<?php endif; ?>
							<tr>
								<td  class="colHeader small" colspan="2">
								<select name="display_status_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="border:1px solid #666666;font-family:Arial, Helvetica, sans-serif;font-size:11px; width:auto" onChange="changeShowstatus('<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
')" id='display_status_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
'>
			                		    <option value="show" <?php if ($this->_tpl_vars['entries']['display_status'] == 1): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_Show']; ?>
</option>
										<option value="hide" <?php if ($this->_tpl_vars['entries']['display_status'] == 0): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_Hide']; ?>
</option>			                
								</select>
								&nbsp;&nbsp;<?php echo $this->_tpl_vars['entries']['blocklabel']; ?>
&nbsp;&nbsp;
				  				</td>
								<td class="colHeader small"  id = "blockid"_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
 colspan="2" align='right'> 
									
									<?php if ($this->_tpl_vars['entries']['iscustom'] == 1): ?>
									<img style="cursor:pointer;" onClick=" deleteCustomBlock('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','<?php echo $this->_tpl_vars['entries']['no']; ?>
')" src="<?php echo vtiger_imageurl('delete.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  alt="Delete" title="Delete"/>&nbsp;&nbsp;
									<?php endif; ?>
									<?php if ($this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['COMMENTSECTIONID'] && $this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['SOLUTIONBLOCKID']): ?>
									<img src="<?php echo vtiger_imageurl('hidden_fields.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;"  onclick="fnvshobj(this,'hiddenfields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');" alt="<?php echo $this->_tpl_vars['MOD']['HIDDEN_FIELDS']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['HIDDEN_FIELDS']; ?>
"/>&nbsp;&nbsp;
									<?php endif; ?>	
										<div id = "hiddenfields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="display:none; position:absolute; width:300px;" class="layerPopup">
											<div style="position:relative; display:block">
		 										<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
													<tr>
														<td width="95%" align="left"  class="layerPopupHeading">
															<?php echo $this->_tpl_vars['MOD']['HIDDEN_FIELDS']; ?>

														</td>
														<td width="5%" align="right">
															<a href="javascript:fninvsh('hiddenfields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  align="absmiddle" /></a>
														</td>
													</tr>
												</table>
												<table border="0" cellspacing="0" cellpadding="0" width="95%">
													<tr>
														<td class=small >
															<table border="0" celspacing="0" cellpadding="0" width="100%" align="center" bgcolor="white">
																<tr>
																	<td align="center">	
																		<table border="0" cellspacing="0" cellpadding="0" width="100%">
																			<tr>
																				<td><?php if ($this->_tpl_vars['entries']['hidden_count'] != '0' || $this->_tpl_vars['entries']['hidden_count'] != null): ?>
																					<?php echo $this->_tpl_vars['APP']['LBL_SELECT_FIELD_TO_MOVE']; ?>

																					<?php endif; ?> 
																				</td>
																			</tr>
																			<tr align="left">
																				<td><?php if ($this->_tpl_vars['entries']['hidden_count'] != '0'): ?>
																					<select class="small" id="hiddenfield_assignid_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="width:225px" size="10" multiple>
																					<?php $_from = $this->_tpl_vars['entries']['hiddenfield']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['inner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['inner']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['value']):
        $this->_foreach['inner']['iteration']++;
?>	
																						<option value="<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
"><?php echo $this->_tpl_vars['value']['fieldlabel']; ?>
</option>
																					<?php endforeach; endif; unset($_from); ?>
																						</select>
																					<?php else: ?>
																					<?php echo $this->_tpl_vars['MOD']['NO_HIDDEN_FIELDS']; ?>

																					<?php endif; ?>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
													<tr>
														<td align="center">
															<input type="button" name="save" value="<?php echo $this->_tpl_vars['APP']['LBL_UNHIDE_FIELDS']; ?>
" class="crmButton small save" onclick ="show_move_hiddenfields('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','showhiddenfields');"/>
															<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="crmButton small cancel" onclick="fninvsh('hiddenfields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');" />
														</td>
													</tr>
												</table>
											</div>
										</div>						
																						
									<?php if ($this->_tpl_vars['entries']['hascustomtable'] && $this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['COMMENTSECTIONID'] && $this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['SOLUTIONBLOCKID']): ?>
										<img src="<?php echo vtiger_imageurl('plus_layout.gif', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;"  onclick="fnvshobj(this,'addfield_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
'); " alt="<?php echo $this->_tpl_vars['MOD']['LBL_ADD_CUSTOMFIELD']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_ADD_CUSTOMFIELD']; ?>
"/>&nbsp;&nbsp;
									<?php endif; ?>
											<!-- for adding customfield -->
												<div id="addfield_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="display:none; position:absolute; width:500px;" class="layerPopup">
												  	<input type="hidden" name="mode" id="cfedit_mode" value="add">
	  												<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
														<tr>
															<td width="60%" align="left" class="layerPopupHeading"><?php echo $this->_tpl_vars['MOD']['LBL_ADD_FIELD']; ?>

															</td>
															<td width="40%" align="right"><a href="javascript:fninvsh('addfield_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');">
															<img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  align="absmiddle" /></a>
															</td>
														</tr>
													</table>
													<table border="0" cellspacing="0" cellpadding="5" width="95%" align="center"> 
														<tr>
															<td class="small" >
																<table border="0" celspacing="0" cellpadding="5" width="100%" align="center" bgcolor="white">
																	<tr>
																		<td>
																			<table>
																				<tr>
																					<td><?php echo $this->_tpl_vars['APP']['LBL_SELECT_FIELD_TYPE']; ?>

																					</td>
																				</tr>
																				<tr>
																					<td>
																						<div name="cfcombo" id="cfcombo" class="small"  style="width:205px; height:150px; overflow-y:auto ;overflow-x:hidden ;overflow:auto; border:1px  solid #CCCCCC ;">
																							<table>
																								<tr><td align="left"><a id="field0_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('text.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,0,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['Text']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field1_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('number.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,1,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
)" >  <?php echo $this->_tpl_vars['MOD']['Number']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field2_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('percent.gif', $this->_tpl_vars['THEME']); ?>
);" 	onclick = "makeFieldSelected(this,2,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['Percent']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field3_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('cfcurrency.gif', $this->_tpl_vars['THEME']); ?>
);" 	onclick = "makeFieldSelected(this,3,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['Currency']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field4_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('date.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,4,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['Date']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field5_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('email.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,5,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['Email']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field6_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('phone.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,6,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['Phone']; ?>
 </a>	</td></tr>
																								<tr><td align="left"><a id="field7_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" 	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('cfpicklist.gif', $this->_tpl_vars['THEME']); ?>
);" 	onclick = "makeFieldSelected(this,7,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['PickList']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field8_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('url.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,8,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['LBL_URL']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field9_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" 	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('checkbox.gif', $this->_tpl_vars['THEME']); ?>
);" 	onclick = "makeFieldSelected(this,9,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);">  <?php echo $this->_tpl_vars['MOD']['LBL_CHECK_BOX']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field10_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('text.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,10,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);"> <?php echo $this->_tpl_vars['MOD']['LBL_TEXT_AREA']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field11_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('cfpicklist.gif', $this->_tpl_vars['THEME']); ?>
);" 	onclick = "makeFieldSelected(this,11,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);"> <?php echo $this->_tpl_vars['MOD']['LBL_MULTISELECT_COMBO']; ?>
 </a></td></tr>
																								<tr><td align="left"><a id="field12_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"	href="javascript:void(0);" class="customMnu" style="text-decoration:none; background-image:url(<?php echo vtiger_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
);" 		onclick = "makeFieldSelected(this,12,<?php echo $this->_tpl_vars['entries']['blockid']; ?>
);"> <?php echo $this->_tpl_vars['MOD']['Skype']; ?>
 </a></td></tr>
																							</table>
																						</div>	
																					</td>
																				</tr>
																			</table>
																		</td>				
																		<td width="50%">
																			<table width="100%" border="0" cellpadding="5" cellspacing="0">
																				<tr>
																					<td class="dataLabel" nowrap="nowrap" align="right" width="30%"><b><?php echo $this->_tpl_vars['MOD']['LBL_LABEL']; ?>
 </b>
																					</td>
																					<td align="left" width="70%">
																					<input id="fldLabel_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
"  value="" type="text" class="txtBox">
																					</td>
																				</tr>
																				<tr id="lengthdetails_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
">
																					<td class="dataLabel" nowrap="nowrap" align="right"><b><?php echo $this->_tpl_vars['MOD']['LBL_LENGTH']; ?>
</b>
																					</td>
																					<td align="left">
																					<input type="text" id="fldLength_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" value="" class="txtBox">
																					</td>
																				</tr>
																				<tr id="decimaldetails_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="visibility:hidden;">
																					<td class="dataLabel_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" nowrap="nowrap" align="right"><b><?php echo $this->_tpl_vars['MOD']['LBL_DECIMAL_PLACES']; ?>
</b>
																					</td>
																					<td align="left">
																					<input type="text" id="fldDecimal_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" value=""  class="txtBox">
																					</td>
																				</tr>
																				<tr id="picklistdetails_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="visibility:hidden;">
																					<td class="dataLabel" nowrap="nowrap" align="right" valign="top"><b><?php echo $this->_tpl_vars['MOD']['LBL_PICK_LIST_VALUES']; ?>
</b>
																					</td>
																					<td align="left" valign="top">
																					<textarea id="fldPickList_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" rows="10" class="txtBox" ></textarea>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>				
																</table>
															</td>
														</tr>
													</table>
													
													<table border="0" cellspacing="0" cellpadding="5" width="100%" class="layerPopupTransport">
														<tr>
															<td align="center">
																<input type="button" name="save" value=" <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" class="crmButton small save"  onclick = "getCreateCustomFieldForm('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','add');"/>&nbsp;
																<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
 " class="crmButton small cancel" onclick="fninvsh('addfield_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');" />
															</td>
														<input type="hidden" name="fieldType_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" id="fieldType_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" value="">
														<input type="hidden" name="selectedfieldtype_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" id="selectedfieldtype_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" value="">
														</tr>
													</table>									
												</div>	
									<!-- end custom field -->
									
									
									<?php if ($this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['COMMENTSECTIONID'] && $this->_tpl_vars['entries']['blockid'] != $this->_tpl_vars['SOLUTIONBLOCKID']): ?>
										<img src="<?php echo vtiger_imageurl('moveinto.png', $this->_tpl_vars['THEME']); ?>
" border="0"  style="cursor:pointer; height:16px; width:16px" onClick="fnvshobj(this,'movefields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');"  alt="Move Fields" title="Move Fields"/>&nbsp;&nbsp;
									<?php endif; ?>
									<div id = "movefields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="display:none; position:absolute; width:300px;" class="layerPopup">
											<div style="position:relative; display:block">
		 										<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
													<tr>
														<td width="95%" align="left"  class="layerPopupHeading">
															<?php echo $this->_tpl_vars['MOD']['LBL_MOVE_FIELDS']; ?>

														</td>
														<td width="5%" align="right">
															<a href="javascript:fninvsh('movefields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  align="absmiddle" /></a>
														</td>
													</tr>
												</table>
												<table border="0" cellspacing="0" cellpadding="0" width="95%">
													<tr>
														<td class=small align="left" >
															<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="white">
																<tr>
																	<td>	
																		<table border="0" cellspacing="5" cellpadding="0" width="100%" align="left" class=small>
																			<tr>
																				<td><?php echo $this->_tpl_vars['MOD']['LBL_SELECT_FIELD_TO_MOVE']; ?>
</td>
																			</tr>
																			<tr>
																				<td><select class="small" id="movefield_assignid_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
" style="width:225px" size="10" multiple>
																					<?php $_from = $this->_tpl_vars['entries']['movefield']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['inner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['inner']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['value']):
        $this->_foreach['inner']['iteration']++;
?>	
																						<option value="<?php echo $this->_tpl_vars['value']['fieldid']; ?>
"><?php echo $this->_tpl_vars['value']['fieldlabel']; ?>
</option>
																					<?php endforeach; endif; unset($_from); ?>
																					</select>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<table border="0" cellspacing="0" cellpadding="5" width="100%" class="layerPopupTransport">
													<tr>
														<td align="center">
															<input type="button" name="save" value="<?php echo $this->_tpl_vars['APP']['LBL_APPLY_BUTTON_LABEL']; ?>
" class="crmButton small save" onclick ="show_move_hiddenfields('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','movehiddenfields');"/>
															<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="crmButton small cancel" onclick="fninvsh('movefields_<?php echo $this->_tpl_vars['entries']['blockid']; ?>
');" />
														</td>
													</tr>
												</table>
											</div>
										</div>						
											
									<?php if (($this->_foreach['outer']['iteration'] <= 1)): ?>
									 	<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<img src="<?php echo vtiger_imageurl('arrow_down.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeBlockorder('block_down','<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
">&nbsp;&nbsp;
									<?php elseif (($this->_foreach['outer']['iteration'] == $this->_foreach['outer']['total'])): ?>
										<img src="<?php echo vtiger_imageurl('arrow_up.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeBlockorder('block_up','<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
">&nbsp;&nbsp;
										<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
									<?php else: ?>
									 	<img src="<?php echo vtiger_imageurl('arrow_up.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeBlockorder('block_up','<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
">&nbsp;&nbsp;
									 	<img src="<?php echo vtiger_imageurl('arrow_down.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeBlockorder('block_down','<?php echo $this->_tpl_vars['entries']['tabid']; ?>
','<?php echo $this->_tpl_vars['entries']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
">&nbsp;&nbsp;
									<?php endif; ?>
									
								</td>
							</tr>
							<tr>
								<?php $_from = $this->_tpl_vars['entries']['field']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['inner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['inner']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['value']):
        $this->_foreach['inner']['iteration']++;
?>		
							
									<?php if ($this->_tpl_vars['value']['no'] % 2 == 0): ?>
								  		</tr>
								  		<?php $this->assign('rightcellclass', ""); ?>
								  		<tr>
								 	<?php else: ?>
								 		<?php $this->assign('rightcellclass', "class='rightCell'"); ?>
								 	<?php endif; ?>
								<td width="30%" id="colourButton" >&nbsp;
							 	<span onmouseover="tooltip.tip(this, showProperties('<?php echo $this->_tpl_vars['value']['label']; ?>
',<?php echo $this->_tpl_vars['value']['mandatory']; ?>
,<?php echo $this->_tpl_vars['value']['presence']; ?>
,<?php echo $this->_tpl_vars['value']['quickcreate']; ?>
,<?php echo $this->_tpl_vars['value']['massedit']; ?>
));" onmouseout="tooltip.untip(false);" ><?php echo $this->_tpl_vars['value']['label']; ?>
</span>
							 		<?php if ($this->_tpl_vars['value']['fieldtype'] == 'M'): ?>
							 			<font color='red'> *</font>
							 		<?php endif; ?>
							 	</td>
								<td width="19%" align = "right" class="colData small" >
									<img src="<?php echo vtiger_imageurl('editfield.gif', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="fnvshNrm('editfield_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
'); posLay(this, 'editfield_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
');" alt="Popup" title="<?php echo $this->_tpl_vars['MOD']['LBL_EDIT_PROPERTIES']; ?>
"/>&nbsp;&nbsp;
							 		
							 		<div id="editfield_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
" style="display:none; position: absolute; width: 225px; left: 300px; top: 300px;" >
							 			<div class="layerPopup" style="position:relative; display:block">
		 									<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
												<tr class="detailedViewHeader">
													<th width="95%" align="left">
														<?php echo $this->_tpl_vars['value']['label']; ?>

													</th>
													<th width="5%" align="right">
														<a href="javascript:fninvsh('editfield_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
');"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  align="absmiddle" /></a>
													</th>
												</tr>
											</table>
											<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">												
												<tr>
													<td valign="top" class="dvtCellInfo" align="left" width="10px">
														<input id="mandatory_check_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
"  type="checkbox"
														<?php if ($this->_tpl_vars['value']['fieldtype'] != 'M' && $this->_tpl_vars['value']['mandatory'] == '0'): ?>
															 disabled
														<?php elseif ($this->_tpl_vars['value']['mandatory'] == '0' && $this->_tpl_vars['value']['fieldtype'] == 'M'): ?> 
															checked  disabled 
														<?php elseif ($this->_tpl_vars['value']['mandatory'] == '3'): ?> 
															disabled 
														<?php elseif ($this->_tpl_vars['value']['mandatory'] == '2'): ?>
														 	checked 
														<?php endif; ?>
														 onclick = "<?php if ($this->_tpl_vars['value']['presence'] != '0'): ?> enableDisableCheckBox(this,presence_check_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
); <?php endif; ?>
														 			<?php if ($this->_tpl_vars['value']['quickcreate'] != '0' && $this->_tpl_vars['value']['quickcreate'] != '3'): ?> enableDisableCheckBox(this,quickcreate_check_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
); <?php endif; ?>">
													</td>
													<td valign="top" class="dvtCellInfo" align="left">
														&nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_MANDATORY_FIELD']; ?>

													</td>
												</tr>
												<tr>
													<td valign="top" class="dvtCellInfo" align="left" width="10px">
														<input id="presence_check_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
"  type="checkbox"
														<?php if ($this->_tpl_vars['value']['displaytype'] == '2'): ?>
															checked disabled
														<?php else: ?>  
															<?php if ($this->_tpl_vars['value']['presence'] == '0' || $this->_tpl_vars['value']['mandatory'] == '0' || $this->_tpl_vars['value']['quickcreate'] == '0' || $this->_tpl_vars['value']['mandatory'] == '2'): ?> 
																checked  disabled   
															<?php endif; ?>
															<?php if ($this->_tpl_vars['value']['presence'] == '2'): ?> 
														 		checked
														 	<?php endif; ?>
														  	<?php if ($this->_tpl_vars['value']['presence'] == '3'): ?>
																disabled
															<?php endif; ?>
														<?php endif; ?>
														 >
													</td>
													<td valign="top" class="dvtCellInfo" align="left">	
														&nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_ACTIVE']; ?>

													</td>
												</tr>
												<tr>
													<td valign="top" class="dvtCellInfo" align="left" width="10px">
														<input id="quickcreate_check_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
"  type="checkbox" 
														<?php if ($this->_tpl_vars['value']['quickcreate'] == '0' || $this->_tpl_vars['value']['quickcreate'] == '2' && ( $this->_tpl_vars['value']['mandatory'] == '0' || $this->_tpl_vars['value']['mandatory'] == '2' )): ?> 
															checked  disabled   
														<?php endif; ?>
														<?php if ($this->_tpl_vars['value']['quickcreate'] == '2'): ?>
															checked
														<?php endif; ?>
														<?php if ($this->_tpl_vars['value']['quickcreate'] == '3'): ?>
															disabled
														<?php endif; ?>
														 >
													</td>
													<td valign="top" class="dvtCellInfo" align="left">	
														&nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_QUICK_CREATE']; ?>
 
													</td>
												</tr>
												<tr>
													<td valign="top" class="dvtCellInfo" align="left" width="10px">
														<input id="massedit_check_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
"  type="checkbox" 
														<?php if ($this->_tpl_vars['value']['massedit'] == '0'): ?>
															disabled 
														<?php endif; ?>
														<?php if ($this->_tpl_vars['value']['massedit'] == '1'): ?> 
															checked
														<?php endif; ?>
														<?php if ($this->_tpl_vars['value']['displaytype'] != '1' || $this->_tpl_vars['value']['massedit'] == '3'): ?>
															disabled
														<?php endif; ?>>
													</td>
													<td valign="top" class="dvtCellInfo" align="left">	
													&nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_MASS_EDIT']; ?>

													</td>
												</tr>
												<tr>
													<td colspan="3" class="dvtCellInfo" align="center">
														<input  type="button" name="save"  value=" &nbsp; <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
 &nbsp; " class="crmButton small save" onclick="saveFieldInfo('<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
','updateFieldProperties');" />&nbsp;
														<?php if ($this->_tpl_vars['value']['customfieldflag'] != 0): ?>
															<input type="button" name="delete" value=" <?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
 " class="crmButton small delete" onclick="deleteCustomField('<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['value']['columnname']; ?>
','<?php echo $this->_tpl_vars['value']['uitype']; ?>
')" />
														<?php endif; ?>
														<input  type="button" name="cancel" value=" <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
 " class="crmButton small cancel" onclick="fninvsh('editfield_<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
');" />
													</td>
												</tr>
											</table>
										</div>							 		
							 		</div>
									
									
									<?php if (($this->_foreach['inner']['iteration'] <= 1)): ?>
										<?php if ($this->_tpl_vars['value']['no'] % 2 != 0): ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
										<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
								 		<?php if ($this->_tpl_vars['value']['no'] != ( count($this->_tpl_vars['entries']['field']) - 2 ) && $this->_tpl_vars['entries']['no'] != 1): ?>
											<img src="<?php echo vtiger_imageurl('arrow_down.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('down','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
">&nbsp;&nbsp;
										<?php else: ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
										<?php if ($this->_tpl_vars['entries']['no'] != 1): ?>
											<img src="<?php echo vtiger_imageurl('arrow_right.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('Right','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
')" alt="<?php echo $this->_tpl_vars['MOD']['RIGHT']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['RIGHT']; ?>
"/>&nbsp;&nbsp;
										<?php else: ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
									<?php elseif (($this->_foreach['inner']['iteration'] == $this->_foreach['inner']['total'])): ?>
										<?php if ($this->_tpl_vars['value']['no'] % 2 != 0): ?>
											<img src="<?php echo vtiger_imageurl('arrow_left.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('Left','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
')" alt="<?php echo $this->_tpl_vars['MOD']['LEFT']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LEFT']; ?>
"/>&nbsp;&nbsp;
										<?php endif; ?>
										<?php if ($this->_tpl_vars['value']['no'] != 1): ?>
											<img src="<?php echo vtiger_imageurl('arrow_up.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('up','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
"/>&nbsp;&nbsp;
									 	<?php else: ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
										<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php if ($this->_tpl_vars['value']['no'] % 2 == 0): ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
									<?php else: ?>
										<?php if ($this->_tpl_vars['value']['no'] % 2 != 0): ?>
											<img src="<?php echo vtiger_imageurl('arrow_left.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('Left','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
')" alt="<?php echo $this->_tpl_vars['MOD']['LEFT']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LEFT']; ?>
"/>&nbsp;&nbsp;
										<?php endif; ?>
										<?php if ($this->_tpl_vars['value']['no'] != 1): ?>
											<img src="<?php echo vtiger_imageurl('arrow_up.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('up','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['UP']; ?>
"/>&nbsp;&nbsp;
									 	<?php else: ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
										<?php if ($this->_tpl_vars['value']['no'] != ( count($this->_tpl_vars['entries']['field']) - 2 )): ?>
											<img src="<?php echo vtiger_imageurl('arrow_down.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('down','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
') " alt="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['DOWN']; ?>
">&nbsp;&nbsp;
										<?php else: ?>
											<img src="<?php echo vtiger_imageurl('blank.gif', $this->_tpl_vars['THEME']); ?>
" style="width:16px;height:16px;" border="0" />&nbsp;&nbsp;
										<?php endif; ?>
										<?php if ($this->_tpl_vars['value']['no'] % 2 == 0): ?>
											<img src="<?php echo vtiger_imageurl('arrow_right.png', $this->_tpl_vars['THEME']); ?>
" border="0" style="cursor:pointer;" onclick="changeFieldorder('Right','<?php echo $this->_tpl_vars['value']['fieldselect']; ?>
','<?php echo $this->_tpl_vars['value']['blockid']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
')" alt="<?php echo $this->_tpl_vars['MOD']['RIGHT']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['RIGHT']; ?>
"/>&nbsp;&nbsp;
										<?php endif; ?>
									<?php endif; ?>
								</td>
										
							<?php endforeach; endif; unset($_from); ?>
							</tr>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</table>
				
					<div id="addblock" style="display:none; position:absolute; width:500px;" class="layerPopup">
						<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
							<tr>
								<td width="95%" align="left" class="layerPopupHeading"><?php echo $this->_tpl_vars['MOD']['LBL_ADD_BLOCK']; ?>

								</td>
								<td width="5%" align="right"><a href="javascript:fninvsh('addblock');"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  align="absmiddle" /></a>
								</td>
							</tr>
						</table>
						<table border="0" cellspacing="0" cellpadding="0" width="95%" align="center"> 
							<tr>
								<td class="small" >
									<table border="0" celspacing="0" cellpadding="0" width="100%" align="center" bgcolor="white">
										<tr>
											<td width="50%">
												<table width="100%" border="0" cellpadding="5" cellspacing="0">
													<tr>
														<td class="dataLabel" nowrap="nowrap" align="right" width="30%"><b><?php echo $this->_tpl_vars['MOD']['LBL_BLOCK_NAME']; ?>
</b></td>
														<td align="left" width="70%">
														<input id="blocklabel" value="" type="text" class="txtBox">
														</td>
													</tr>
													<tr>
														<td class="dataLabel" align="right" width="30%"><b><?php echo $this->_tpl_vars['MOD']['AFTER']; ?>
</b></td>
														<td align="left" width="70%">
														<select id="after_blockid" name="after_blockid">
															<?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['blockid'] => $this->_tpl_vars['blockname']):
?>
															<option value = <?php echo $this->_tpl_vars['blockid']; ?>
> <?php echo $this->_tpl_vars['blockname']; ?>
 </option>
															<?php endforeach; endif; unset($_from); ?>
														</select>				
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<table border=0 cellspacing=0 cellpadding=5 width=100% >
										<tr>
											<td align="center">
												<input type="button" name="save"  value= "<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
"  class="crmButton small save" onclick=" getCreateCustomBlockForm('<?php echo $this->_tpl_vars['MODULE']; ?>
','add') "/>&nbsp;
												<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
"  class="crmButton small cancel" onclick= "fninvsh('addblock');" />
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</form>