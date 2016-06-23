<?php /* Smarty version 2.6.18, created on 2014-09-01 11:53:06
         compiled from DetailViewUI.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'DetailViewUI.tpl', 41, false),array('modifier', 'vtiger_imageurl', 'DetailViewUI.tpl', 53, false),array('modifier', 'getTranslatedCurrencyString', 'DetailViewUI.tpl', 64, false),array('modifier', 'cat', 'DetailViewUI.tpl', 126, false),array('modifier', 'replace', 'DetailViewUI.tpl', 131, false),array('modifier', 'wordwrap', 'DetailViewUI.tpl', 133, false),array('modifier', 'regex_replace', 'DetailViewUI.tpl', 193, false),array('modifier', 'parse_calendardate', 'DetailViewUI.tpl', 361, false),)), $this); ?>

<!-- This file is used to display the fields based on the ui type in detailview -->
		<?php if ($this->_tpl_vars['keyid'] == '1' || $this->_tpl_vars['keyid'] == 2 || $this->_tpl_vars['keyid'] == '11' || $this->_tpl_vars['keyid'] == '7' || $this->_tpl_vars['keyid'] == '9' || $this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '71' || $this->_tpl_vars['keyid'] == '72' || $this->_tpl_vars['keyid'] == '103' || $this->_tpl_vars['keyid'] == '255'): ?> <!--TextBox-->
			<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');" valign="top">
				<?php if ($this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '255'): ?><!--SalutationSymbol-->
					<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
						<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>	
					<?php else: ?>
						<?php echo $this->_tpl_vars['keysalut']; ?>

					<?php endif; ?>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['keyid'] == 11): ?>
					<?php if ($this->_tpl_vars['USE_ASTERISK'] == 'true'): ?>
						&nbsp;&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href='javascript:;' onclick='startCall("<?php echo $this->_tpl_vars['keyval']; ?>
", "<?php echo $this->_tpl_vars['ID']; ?>
")'><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
					<?php else: ?>
						&nbsp;&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
					<?php endif; ?>
				<?php else: ?>
					&nbsp;&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
				<?php endif; ?>
                <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                	<input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
                    <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                    <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                </div>
                <?php if ($this->_tpl_vars['keyid'] == '71' && $this->_tpl_vars['keyfldname'] == 'unit_price'): ?>	
                	<?php if (count($this->_tpl_vars['PRICE_DETAILS']) > 0): ?>				
						<span id="multiple_currencies" width="38%" style="align:right;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="toggleShowHide('currency_class','multiple_currencies');"><?php echo $this->_tpl_vars['APP']['LBL_MORE_CURRENCIES']; ?>
 &raquo;</a>
						</span>
						
						<div id="currency_class" class="multiCurrencyDetailUI">					
							<table width="100%" height="100%" class="small" cellpadding="5">
							<tr>
								<th colspan="2">
									<b><?php echo $this->_tpl_vars['MOD']['LBL_PRODUCT_PRICES']; ?>
</b>
								</th>
								<th align="right">
									<img border="0" style="cursor: pointer;" onclick="toggleShowHide('multiple_currencies','currency_class');" src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
"/>
								</th>
							</tr>							
							<tr class="detailedViewHeader">
								<th><?php echo $this->_tpl_vars['APP']['LBL_CURRENCY']; ?>
</th>
								<th colspan="2"><?php echo $this->_tpl_vars['APP']['LBL_PRICE']; ?>
</th>
							</tr>
							<?php $_from = $this->_tpl_vars['PRICE_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['price']):
?>
								<tr>
																		<td class="dvtCellLabel" width="40%">
										<?php echo getTranslatedCurrencyString($this->_tpl_vars['price']['currencylabel']); ?>
 (<?php echo $this->_tpl_vars['price']['currencysymbol']; ?>
)
									</td>
									<td class="dvtCellInfo" width="60%" colspan="2">
										<?php echo $this->_tpl_vars['price']['curvalue']; ?>

									</td>
								</tr>
							<?php endforeach; endif; unset($_from); ?>
							</table>
						</div>
					<?php endif; ?>
                <?php endif; ?>
            </td>
        <?php elseif ($this->_tpl_vars['keyid'] == '13' || $this->_tpl_vars['keyid'] == '104'): ?> <!--Email-->
            <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
				<?php if ($_SESSION['internal_mailer'] == 1): ?>
					<a href="javascript:InternalMailer(<?php echo $this->_tpl_vars['ID']; ?>
,<?php echo $this->_tpl_vars['keyfldid']; ?>
,'<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
','record_id');"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
				<?php else: ?>
					<a href="mailto:<?php echo $this->_tpl_vars['keyval']; ?>
" target="_blank" ><?php echo $this->_tpl_vars['keyval']; ?>
</a>
				<?php endif; ?>
				</span>
                <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                	<input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
                	<br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                	<a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                </div>
				<div id="internal_mailer_<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="display: none;"><?php echo $this->_tpl_vars['keyfldid']; ?>
####<?php echo $_SESSION['internal_mailer']; ?>
</div>
                                                  </td>
						                     <?php elseif ($this->_tpl_vars['keyid'] == '15' || $this->_tpl_vars['keyid'] == '16'): ?> <!--ComboBox-->
						<?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
							<?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE'] && $this->_tpl_vars['arr'][2] == 'selected'): ?>
								<?php $this->assign('keyval', $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']); ?>
								<?php $this->assign('fontval', 'red'); ?>
							<?php else: ?>
                                                                <?php $this->assign('fontval', ''); ?>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>               
							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="<?php echo $this->_tpl_vars['fontval']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font></span>
                                              		<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                    							   <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
                    								<?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
											<?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                    									<option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
><?php echo $this->_tpl_vars['arr'][0]; ?>
</option>
											<?php else: ?>
        							                                <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
							                                                <?php echo $this->_tpl_vars['arr'][0]; ?>

								                                </option>
							                                <?php endif; ?>

										<?php endforeach; endif; unset($_from); ?>
                    							   </select>
                    							   <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                    							</div>
               							</td>
                                          <?php elseif ($this->_tpl_vars['keyid'] == '33'): ?><!--Multi Select Combo box-->
						<!--code given by Neil start Ref:http://forums.vtiger.com/viewtopic.php?p=31096#31096-->
						<!--<?php $this->assign('MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING', ", "); ?>  						<?php $this->assign('DETAILVIEW_WORDWRAP_WIDTH', '70'); ?> -->
                                          <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
					<?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_val']):
?>
						<?php if ($this->_tpl_vars['sel_val'][2] == 'selected'): ?>
							<?php if ($this->_tpl_vars['selected_val'] != ''): ?>
							<?php $this->assign('selected_val', ((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('cat', true, $_tmp, ', ') : smarty_modifier_cat($_tmp, ', '))); ?>
							<?php endif; ?>
							<?php $this->assign('selected_val', ((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['sel_val'][0]) : smarty_modifier_cat($_tmp, $this->_tpl_vars['sel_val'][0]))); ?>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;&nbsp;")); ?>

						<!-- commented to fix ticket4631 -using wordwrap will affect Not Accessible font color -->
						<!--<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('replace', true, $_tmp, $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING'], "\x1") : smarty_modifier_replace($_tmp, $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING'], "\x1")))) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', "\x0") : smarty_modifier_replace($_tmp, ' ', "\x0")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\x1", $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING']) : smarty_modifier_replace($_tmp, "\x1", $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING'])))) ? $this->_run_mod_handler('wordwrap', true, $_tmp, $this->_tpl_vars['DETAILVIEW_WORDWRAP_WIDTH'], "<br>&nbsp;") : smarty_modifier_wordwrap($_tmp, $this->_tpl_vars['DETAILVIEW_WORDWRAP_WIDTH'], "<br>&nbsp;")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\x0", "&nbsp;") : smarty_modifier_replace($_tmp, "\x0", "&nbsp;")); ?>
-->
						</span>
						<!--code given by Neil End-->
                                          <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                                          <select MULTIPLE id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" size="4" style="width:160px;" class="small">
				                                    <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
										<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
><?php echo $this->_tpl_vars['arr'][0]; ?>
</option>
				                                    <?php endforeach; endif; unset($_from); ?>
			                                   </select>
			                                   <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                    							</div>
               							</td>
						<?php elseif ($this->_tpl_vars['keyid'] == '115'): ?> <!--ComboBox Status edit only for admin Users-->
               							<td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>
</td>
						<?php elseif ($this->_tpl_vars['keyid'] == '116' || $this->_tpl_vars['keyid'] == '117'): ?> <!--ComboBox currency id edit only for admin Users-->
								<?php if ($this->_tpl_vars['keyadmin'] == 1 || $this->_tpl_vars['keyid'] == '117'): ?>
               							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
								<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                    							   <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
									<?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uivalueid'] => $this->_tpl_vars['arr']):
?>
									<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
										<option value="<?php echo $this->_tpl_vars['uivalueid']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo getTranslatedCurrencyString($this->_tpl_vars['sel_value']); ?>
</option>	
									<?php endforeach; endif; unset($_from); ?>
									<?php endforeach; endif; unset($_from); ?>
                    							   </select>
                    							   <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                    							</div>
								<?php else: ?>
               							<td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>

								<?php endif; ?>	

                                        		
               							</td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == '17'): ?> <!--WebSite-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="http://<?php echo $this->_tpl_vars['keyval']; ?>
" target="_blank"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
                                              		<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('<?php echo $this->_tpl_vars['keyfldname']; ?>
');" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
                                              		  <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                                                       </div>
                                                  </td>
					     <?php elseif ($this->_tpl_vars['keyid'] == '85'): ?><!--Skype-->
                                                <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<img src="<?php echo vtiger_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" LANGUAGE=javascript align="absmiddle"></img><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="skype:<?php echo $this->_tpl_vars['keyval']; ?>
?call"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
                                                        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                                                          <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
                                                          <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                                          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                                                       </div>
                                                  </td>	
                                             <?php elseif ($this->_tpl_vars['keyid'] == '19' || $this->_tpl_vars['keyid'] == '20'): ?> <!--TextArea/Description-->
						<!-- we will empty the value of ticket and faq comment -->
						<?php if ($this->_tpl_vars['label'] == $this->_tpl_vars['MOD']['LBL_ADD_COMMENT']): ?>
							<?php $this->assign('keyval', ''); ?>
						<?php endif; ?>
							<!--<?php $this->assign('DESCRIPTION_SEPARATOR_STRING', ' '); ?>  -->
							<!--<?php $this->assign('DESCRIPTION_WORDWRAP_WIDTH', '70'); ?> -->
							<?php if ($this->_tpl_vars['MODULE'] == 'Documents'): ?>
							<!--To give hyperlink to URL-->
                                                        <td width="100%" colspan="3" class="dvtCellInfo" align="left"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>
&nbsp;
                                                        </td>
                                                  	<?php else: ?>
                                                        <td width="100%" colspan="3" class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
								<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>

                                                                </span>
                                                                <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                                                                <textarea id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" cols="90" rows="8"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<br>", "\n") : smarty_modifier_replace($_tmp, "<br>", "\n")); ?>
</textarea>
                                                                <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                                                <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                                                                </div>
                                                        </td>
                                                   <?php endif; ?>
                                             <?php elseif ($this->_tpl_vars['keyid'] == '21' || $this->_tpl_vars['keyid'] == '24' || $this->_tpl_vars['keyid'] == '22'): ?> <!--TextArea/Street-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
                                              		<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                                              		  <textarea id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2><?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/<br\s*\/>/", "") : smarty_modifier_regex_replace($_tmp, "/<br\s*\/>/", "")); ?>
</textarea>                                            		  
                                              		  <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                                                       </div>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == '50' || $this->_tpl_vars['keyid'] == '73' || $this->_tpl_vars['keyid'] == '51'): ?> <!--AccountPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == '57'): ?> <!--ContactPopup-->
						<!-- Ajax edit link not provided for contact - Reports To -->
                                                  	<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == '59'): ?> <!--ProductPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
                                              		<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">                                              		  
                                                         <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="product_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
"><input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                                                       </div>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == '75' || $this->_tpl_vars['keyid'] == '81'): ?> <!--VendorPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == 76): ?> <!--PotentialPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == 78): ?> <!--QuotePopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == 82): ?> <!--Email Body-->
                                                  <td colspan="3" width=100% class="dvtCellInfo" align="left"><div id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
" style="width:100%;height:200px;overflow:hidden;border:1px solid gray" class="detailedViewTextBox" onmouseover="this.className='detailedViewTextBoxOn'" onmouseout="this.className='detailedViewTextBox'"><?php echo $this->_tpl_vars['keyval']; ?>
</div>
                                                  </td>
                                             <?php elseif ($this->_tpl_vars['keyid'] == 80): ?> <!--SalesOrderPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
                                                  </td>
					     <?php elseif ($this->_tpl_vars['keyid'] == '52' || $this->_tpl_vars['keyid'] == '77'): ?> 
                                                                <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
                                                        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                                                                           <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
                                                                                <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uid'] => $this->_tpl_vars['arr']):
?>
                                                                                        <?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
                                                                                                <option value="<?php echo $this->_tpl_vars['uid']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['sel_value']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['sel_value']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['sel_value']; ?>
<?php endif; ?></option>

                                                                                        <?php endforeach; endif; unset($_from); ?>
                                                                                <?php endforeach; endif; unset($_from); ?>
                                                                           </select>
                                                                           <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                                           <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                                                                        </div>
                                                                </td>	
						<?php elseif ($this->_tpl_vars['keyid'] == '53'): ?> <!--Assigned To-->
							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
							<?php if ($this->_tpl_vars['keyadmin'] == 1): ?>
								<a href="<?php echo $this->_tpl_vars['keyseclink']['0']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>         
							<?php else: ?>	
								<?php echo $this->_tpl_vars['keyval']; ?>

							<?php endif; ?>
							&nbsp;</span>
							<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
							<input type="hidden" id="hdtxt_<?php echo $this->_tpl_vars['label']; ?>
" value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
						<?php if ($this->_tpl_vars['keyoptions']['0'] == 'User'): ?>
							<input name="assigntype" id="assigntype" checked="checked" value="U" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>

							<?php if ($this->_tpl_vars['keyoptions']['2'] != ''): ?>
								<input name="assigntype" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP_NAME']; ?>

							<?php endif; ?>
							<span id="assign_user" style="display: block;">
						<?php else: ?>
							<input name="assigntype" id="assigntype" value="U" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>

							<input name="assigntype" checked="checked" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP_NAME']; ?>

							<span id="assign_user" style="display: none;">
						<?php endif; ?>
                   				<select id="txtbox_U<?php echo $this->_tpl_vars['label']; ?>
" onchange="setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
')" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
				                    <?php $_from = $this->_tpl_vars['keyoptions']['1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['arr']):
?>
				                    	<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
                       						 <option value="<?php echo $this->_tpl_vars['id']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
				                        <?php endforeach; endif; unset($_from); ?>
				                    <?php endforeach; endif; unset($_from); ?>
			                    	</select>
						</span>
					<?php if ($this->_tpl_vars['keyoptions']['0'] == 'Group'): ?>
						<span id="assign_team" style="display: block;">
					<?php else: ?>
						<span id="assign_team" style="display: none;">
					<?php endif; ?>
                   	<select id="txtbox_G<?php echo $this->_tpl_vars['label']; ?>
" onchange="setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
')" name="assigned_group_id" class="groupname small">
                    <?php $_from = $this->_tpl_vars['keyoptions']['2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['arr']):
?>
                    	<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
                       		 <option value="<?php echo $this->_tpl_vars['id']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
					</span>

                    <br>
                    <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                    <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                    </div>
                    </td>
						<?php elseif ($this->_tpl_vars['keyid'] == '99'): ?><!-- Password Field-->
						<td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['CHANGE_PW_BUTTON']; ?>
</td>	
					    <?php elseif ($this->_tpl_vars['keyid'] == '56'): ?> <!--CheckBox--> 
                      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onMouseOver="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
&nbsp;</span>
                    	<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                    	<?php if ($this->_tpl_vars['MODULE'] != 'Documents'): ?>
                        	<?php if ($this->_tpl_vars['keyval'] == $this->_tpl_vars['APP']['yes']): ?>
                            	<input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="checkbox" style="border:1px solid #bababa;" checked value="1">
                        	<?php else: ?>
                          		<input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="border:1px solid #bababa;" value="0">
                       		<?php endif; ?>
                       	<?php else: ?>
                         	<?php if ($this->_tpl_vars['keyval'] == $this->_tpl_vars['APP']['yes']): ?>
                            	<input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="checkbox" style="border:1px solid #bababa;" checked value="0">
                        	<?php else: ?>
                          		<input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="border:1px solid #bababa;" value="1">
                       		<?php endif; ?>                      	
                       	<?php endif; ?>
                         <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                        </div>
                        </td>    
			<?php elseif ($this->_tpl_vars['keyid'] == '156'): ?> <!--CheckBox for is admin-->
			<?php if ($_REQUEST['record'] != $this->_tpl_vars['CURRENT_USERID'] && $this->_tpl_vars['keyadmin'] == 1): ?> 
                      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onMouseOver="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?>&nbsp;</span>
                    	<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                        <?php if ($this->_tpl_vars['keyval'] == 'on'): ?>                                              		  
                            <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="checkbox" style="border:1px solid #bababa;" checked value="1">
                        <?php else: ?>
                          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="border:1px solid #bababa;" value="0">
                       	<?php endif; ?>
                         <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
                        </div>
			<?php else: ?>
				 <td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>

			<?php endif; ?>
                        </td>    
			 
						<?php elseif ($this->_tpl_vars['keyid'] == 83): ?><!-- Handle the Tax in Inventory -->
							<?php $_from = $this->_tpl_vars['TAX_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['tax']):
?>
								<td align="right" class="dvtCellLabel">
									<?php echo $this->_tpl_vars['tax']['taxlabel']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

							
								</td>
								<td class="dvtCellInfo" align="left">
									<?php echo $this->_tpl_vars['tax']['percentage']; ?>

								</td>
								<td colspan="2" class="dvtCellInfo">&nbsp;</td>
							   </tr>
							<?php endforeach; endif; unset($_from); ?>

				<?php elseif ($this->_tpl_vars['keyid'] == 5): ?>
										<?php if (empty ( $this->_tpl_vars['dateFormat'] )): ?>
						<?php $this->assign('dateFormat', parse_calendardate($this->_tpl_vars['APP']['NTC_DATE_FORMAT'])); ?>
					<?php endif; ?>
					<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
						&nbsp;&nbsp;<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
							<?php echo $this->_tpl_vars['keyval']; ?>

						</span>
						<div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
							<input style="border:1px solid #bababa;" size="11" maxlength="10" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/[^-]*(--)[^-]*$/', '') : smarty_modifier_regex_replace($_tmp, '/[^-]*(--)[^-]*$/', '')); ?>
"></input>
							<img src="<?php echo vtiger_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="jscal_trigger_<?php echo $this->_tpl_vars['keyfldname']; ?>
">
							<br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

							<a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
							<script type="text/javascript">
								Calendar.setup ({
									inputField : "txtbox_<?php echo $this->_tpl_vars['label']; ?>
", ifFormat : '<?php echo $this->_tpl_vars['dateFormat']; ?>
', showsTime : false, button : "jscal_trigger_<?php echo $this->_tpl_vars['keyfldname']; ?>
", singleClick : true, step : 1
								})
							</script>
						</div>
					</td>

				<?php elseif ($this->_tpl_vars['keyid'] == 69): ?><!-- for Image Reflection -->
     				<td align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
				<?php else: ?>									
					<td class="dvtCellInfo" align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
				<?php endif; ?>