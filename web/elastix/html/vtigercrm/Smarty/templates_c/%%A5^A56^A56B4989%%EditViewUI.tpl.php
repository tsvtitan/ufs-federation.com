<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:09
         compiled from EditViewUI.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'EditViewUI.tpl', 34, false),array('modifier', 'getTranslatedString', 'EditViewUI.tpl', 61, false),array('modifier', 'getTranslatedCurrencyString', 'EditViewUI.tpl', 407, false),array('modifier', 'substr', 'EditViewUI.tpl', 464, false),)), $this); ?>
		<?php $this->assign('uitype', ($this->_tpl_vars['maindata'][0][0])); ?>
		<?php $this->assign('fldlabel', ($this->_tpl_vars['maindata'][1][0])); ?>
		<?php $this->assign('fldlabel_sel', ($this->_tpl_vars['maindata'][1][1])); ?>
		<?php $this->assign('fldlabel_combo', ($this->_tpl_vars['maindata'][1][2])); ?>
		<?php $this->assign('fldlabel_other', ($this->_tpl_vars['maindata'][1][3])); ?>
		<?php $this->assign('fldname', ($this->_tpl_vars['maindata'][2][0])); ?>
		<?php $this->assign('fldvalue', ($this->_tpl_vars['maindata'][3][0])); ?>
		<?php $this->assign('secondvalue', ($this->_tpl_vars['maindata'][3][1])); ?>
		<?php $this->assign('thirdvalue', ($this->_tpl_vars['maindata'][3][2])); ?>
		<?php $this->assign('typeofdata', ($this->_tpl_vars['maindata'][4])); ?> 
	 	<?php $this->assign('vt_tab', ($this->_tpl_vars['maindata'][5][0])); ?>

		<?php if ($this->_tpl_vars['typeofdata'] == 'M'): ?>
			<?php $this->assign('mandatory_field', "*"); ?>
		<?php else: ?>
			<?php $this->assign('mandatory_field', ""); ?>
		<?php endif; ?>

				<?php $this->assign('usefldlabel', $this->_tpl_vars['fldlabel']); ?>
		<?php $this->assign('fldhelplink', ""); ?>
		<?php if ($this->_tpl_vars['FIELDHELPINFO'] && $this->_tpl_vars['FIELDHELPINFO'][$this->_tpl_vars['fldname']]): ?>
			<?php $this->assign('fldhelplinkimg', vtiger_imageurl('help_icon.gif', $this->_tpl_vars['THEME'])); ?>
			<?php $this->assign('fldhelplink', "<img style='cursor:pointer' onclick='vtlib_field_help_show(this, \"".($this->_tpl_vars['fldname'])."\");' border=0 src='".($this->_tpl_vars['fldhelplinkimg'])."'>"); ?>
			<?php if ($this->_tpl_vars['uitype'] != '10'): ?>
				<?php $this->assign('usefldlabel', ($this->_tpl_vars['fldlabel'])." ".($this->_tpl_vars['fldhelplink'])); ?>
			<?php endif; ?>
		<?php endif; ?>
		
				<?php if ($this->_tpl_vars['uitype'] == '10'): ?>
			<td width=20% class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
			<?php echo $this->_tpl_vars['fldlabel']['displaylabel']; ?>
 

			<?php if (count ( $this->_tpl_vars['fldlabel']['options'] ) == 1): ?>
				<?php $this->assign('use_parentmodule', $this->_tpl_vars['fldlabel']['options']['0']); ?>
				<input type='hidden' class='small' name="<?php echo $this->_tpl_vars['fldname']; ?>
_type" value="<?php echo $this->_tpl_vars['use_parentmodule']; ?>
">
			<?php else: ?>
			<br>
			<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
			<select id="<?php echo $this->_tpl_vars['fldname']; ?>
_type" class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
_type" onChange='document.QcEditView.<?php echo $this->_tpl_vars['fldname']; ?>
_display.value=""; document.QcEditView.<?php echo $this->_tpl_vars['fldname']; ?>
.value="";'>
			<?php else: ?>
			<select id="<?php echo $this->_tpl_vars['fldname']; ?>
_type" class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
_type" onChange='document.EditView.<?php echo $this->_tpl_vars['fldname']; ?>
_display.value=""; document.EditView.<?php echo $this->_tpl_vars['fldname']; ?>
.value="";$("qcform").innerHTML=""'>
			<?php endif; ?>
			<?php $_from = $this->_tpl_vars['fldlabel']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
				<option value="<?php echo $this->_tpl_vars['option']; ?>
" 
				<?php if ($this->_tpl_vars['fldlabel']['selected'] == $this->_tpl_vars['option']): ?>selected<?php endif; ?>>
				<?php echo getTranslatedString($this->_tpl_vars['option'], $this->_tpl_vars['option']); ?>

				</option> 
			<?php endforeach; endif; unset($_from); ?>
			</select>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			<?php echo $this->_tpl_vars['fldhelplink']; ?>


			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['fldvalue']['entityid']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
">
				<input id="<?php echo $this->_tpl_vars['fldname']; ?>
_display" name="<?php echo $this->_tpl_vars['fldname']; ?>
_display" id="edit_<?php echo $this->_tpl_vars['fldname']; ?>
_display" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']['displayvalue']; ?>
">&nbsp;
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
				<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" 
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.QcEditView.<?php echo $this->_tpl_vars['fldname']; ?>
_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=<?php echo $this->_tpl_vars['fldname']; ?>
&srcmodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&forrecord=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
				<?php else: ?>
				<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" 
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.EditView.<?php echo $this->_tpl_vars['fldname']; ?>
_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=<?php echo $this->_tpl_vars['fldname']; ?>
&srcmodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&forrecord=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
				<?php endif; ?>
				<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" 
alt="Clear" title="Clear" LANGUAGE=javascript	onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
			</td>
				<?php elseif ($this->_tpl_vars['uitype'] == 2): ?>
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?>
			</td>
			<td width=30% align=left class="dvtCellInfo">
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 3 || $this->_tpl_vars['uitype'] == 4): ?><!-- Non Editable field, only configured value will be loaded -->
				<td width=20% class="dvtCellLabel" align=right><font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?></td>
                                <td width=30% align=left class="dvtCellInfo"><input readonly type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" <?php if ($this->_tpl_vars['MODE'] == 'edit'): ?> value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['MOD_SEQ_ID']; ?>
" <?php endif; ?> class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
		<?php elseif ($this->_tpl_vars['uitype'] == 11 || $this->_tpl_vars['uitype'] == 1 || $this->_tpl_vars['uitype'] == 13 || $this->_tpl_vars['uitype'] == 7 || $this->_tpl_vars['uitype'] == 9): ?>
			<td width=20% class="dvtCellLabel" align=right><font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?></td>

			<?php if ($this->_tpl_vars['fldname'] == 'tickersymbol' && $this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<td width=30% align=left class="dvtCellInfo">
					<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';<?php if ($this->_tpl_vars['fldname'] == 'tickersymbol' && $this->_tpl_vars['MODULE'] == 'Accounts'): ?>sensex_info()<?php endif; ?>">
					<span id="vtbusy_info" style="display:none;">
						<img src="<?php echo vtiger_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>
				</td>

			<?php else: ?>
				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
			<?php endif; ?>
		<?php elseif ($this->_tpl_vars['uitype'] == 19 || $this->_tpl_vars['uitype'] == 20): ?>
			<!-- In Add Comment are we should not display anything -->
			<?php if ($this->_tpl_vars['fldlabel'] == $this->_tpl_vars['MOD']['LBL_ADD_COMMENT']): ?>
				<?php $this->assign('fldvalue', ""); ?>
			<?php endif; ?>
			<td width=20% class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font> 
				<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td colspan=3>
				<textarea class="detailedViewTextBox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onFocus="this.className='detailedViewTextBoxOn'" name="<?php echo $this->_tpl_vars['fldname']; ?>
"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
				<?php if ($this->_tpl_vars['fldlabel'] == $this->_tpl_vars['MOD']['Solution']): ?>
				<input type = "hidden" name="helpdesk_solution" value = '<?php echo $this->_tpl_vars['fldvalue']; ?>
'>
				<?php endif; ?>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 21 || $this->_tpl_vars['uitype'] == 24): ?>
			<td width=20% class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
				<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width=30% align=left class="dvtCellInfo">
				<textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 15 || $this->_tpl_vars['uitype'] == 16): ?> <!-- uitype 111 added for noneditable existing picklist values - ahmed -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
				<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
			   		<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" style="width:160px;">
				<?php else: ?>
			   		<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   	<?php endif; ?>
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
					<?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
					<option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
						<?php echo $this->_tpl_vars['arr'][0]; ?>

					</option>
					<?php else: ?>
					<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                                                <?php echo $this->_tpl_vars['arr'][0]; ?>

                                        </option>
					<?php endif; ?>
				<?php endforeach; else: ?>
					<option value=""></option>
					<option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
				<?php endif; unset($_from); ?>
			   </select>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 33): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <select MULTIPLE name="<?php echo $this->_tpl_vars['fldname']; ?>
[]" size="4" style="width:160px;" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
					<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                                                <?php echo $this->_tpl_vars['arr'][0]; ?>

                                        </option>
				<?php endforeach; endif; unset($_from); ?>
			   </select>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 53): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php $this->assign('check', 1); ?>
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
					<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
						<?php if ($this->_tpl_vars['value'] != ''): ?>
							<?php $this->assign('check', $this->_tpl_vars['check']*0); ?>
						<?php else: ?>
							<?php $this->assign('check', $this->_tpl_vars['check']*1); ?>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>

				<?php if ($this->_tpl_vars['check'] == 0): ?>
					<?php $this->assign('select_user', 'checked'); ?>
					<?php $this->assign('style_user', 'display:block'); ?>
					<?php $this->assign('style_group', 'display:none'); ?>
				<?php else: ?>
					<?php $this->assign('select_group', 'checked'); ?>
					<?php $this->assign('style_user', 'display:none'); ?>
					<?php $this->assign('style_group', 'display:block'); ?>
				<?php endif; ?>

				<input type="radio" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="assigntype" <?php echo $this->_tpl_vars['select_user']; ?>
 value="U" onclick="toggleAssignType(this.value)" >&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>


				<?php if ($this->_tpl_vars['secondvalue'] != ''): ?>
					<input type="radio" name="assigntype" <?php echo $this->_tpl_vars['select_group']; ?>
 value="T" onclick="toggleAssignType(this.value)">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>

				<?php endif; ?>
				
				<span id="assign_user" style="<?php echo $this->_tpl_vars['style_user']; ?>
">
					<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" class="small">
						<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
							<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
								<option value="<?php echo $this->_tpl_vars['key_one']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</span>

				<?php if ($this->_tpl_vars['secondvalue'] != ''): ?>
					<span id="assign_team" style="<?php echo $this->_tpl_vars['style_group']; ?>
">
						<select name="assigned_group_id" class="small">';
							<?php $_from = $this->_tpl_vars['secondvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
								<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
									<option value="<?php echo $this->_tpl_vars['key_one']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
								<?php endforeach; endif; unset($_from); ?>
							<?php endforeach; endif; unset($_from); ?>
						</select>
					</span>
				<?php endif; ?>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 52 || $this->_tpl_vars['uitype'] == 77): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['uitype'] == 52): ?>
					<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php elseif ($this->_tpl_vars['uitype'] == 77): ?>
					<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php else: ?>
					<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php endif; ?>

				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
					<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
						<option value="<?php echo $this->_tpl_vars['key_one']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 51): ?>
			<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<?php $this->assign('popuptype', 'specific_account_address'); ?>
			<?php else: ?>
				<?php $this->assign('popuptype', 'specific_contact_account_address'); ?>
			<?php endif; ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" style="border:1px solid #bababa;" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=<?php echo $this->_tpl_vars['popuptype']; ?>
&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&recordid=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 50): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 73): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" id = "single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 75 || $this->_tpl_vars['uitype'] == 81): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<?php if ($this->_tpl_vars['uitype'] == 81): ?>
					<?php $this->assign('pop_type', 'specific_vendor_address'); ?>
					<?php else: ?><?php $this->assign('pop_type', 'specific'); ?>
				<?php endif; ?>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype=<?php echo $this->_tpl_vars['pop_type']; ?>
&form=EditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 57): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact("false","general",document.QcEditView)' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php else: ?>
					<input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact("false","general",document.EditView)' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php endif; ?>
			</td>
		
		<?php elseif ($this->_tpl_vars['uitype'] == 58): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="campaignname" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaigns&action=Popup&html=Popup_picker&popuptype=specific_campaign&form=EditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.campaignid.value=''; this.form.campaignname.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 80): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectSalesOrder();' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 78): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" >&nbsp;<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectQuote()' align="absmiddle" style='cursor:hand;cursor:pointer' >&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 76): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectPotential()' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 17): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				&nbsp;&nbsp;http://
			<input style="width:74%;" class = 'detailedViewTextBox' type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('<?php echo $this->_tpl_vars['fldname']; ?>
');" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 85): ?>
            <td width="20%" class="dvtCellLabel" align=right>
                <font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
                <?php echo $this->_tpl_vars['usefldlabel']; ?>

                <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
                	<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" >
                <?php endif; ?>
            </td>
            <td width="30%" align=left class="dvtCellInfo">
				<img src="<?php echo vtiger_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
" alt="Skype" title="Skype" LANGUAGE=javascript align="absmiddle"></img>
				<input class='detailedViewTextBox' type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 71 || $this->_tpl_vars['uitype'] == 72): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">				
				<?php if ($this->_tpl_vars['fldname'] == 'unit_price' && $this->_tpl_vars['fromlink'] != 'qcreate'): ?>
					<span id="multiple_currencies">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'; updateUnitPrice('unit_price', '<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
');"  value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:60%;">
					<?php if ($this->_tpl_vars['MASS_EDIT'] != 1): ?>
						&nbsp;<a href="javascript:void(0);" onclick="updateUnitPrice('unit_price', '<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
'); toggleShowHide('currency_class','multiple_currencies');"><?php echo $this->_tpl_vars['APP']['LBL_MORE_CURRENCIES']; ?>
 &raquo;</a>
					<?php endif; ?>
					</span>
					<?php if ($this->_tpl_vars['MASS_EDIT'] != 1): ?>
					<div id="currency_class" class="multiCurrencyEditUI" width="350">
						<input type="hidden" name="base_currency" id="base_currency" value="<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
" />
						<input type="hidden" name="base_conversion_rate" id="base_currency" value="<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
" />
						<table width="100%" height="100%" class="small" cellpadding="5">
						<tr class="detailedViewHeader">
							<th colspan="4">
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
							<th><?php echo $this->_tpl_vars['APP']['LBL_PRICE']; ?>
</th>
							<th><?php echo $this->_tpl_vars['APP']['LBL_CONVERSION_RATE']; ?>
</th>
							<th><?php echo $this->_tpl_vars['APP']['LBL_RESET_PRICE']; ?>
</th>							
							<th><?php echo $this->_tpl_vars['APP']['LBL_BASE_CURRENCY']; ?>
</th>
						</tr>
						<?php $_from = $this->_tpl_vars['PRICE_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['price']):
?>
							<tr>
								<?php if ($this->_tpl_vars['price']['check_value'] == 1 || $this->_tpl_vars['price']['is_basecurrency'] == 1): ?>
									<?php $this->assign('check_value', 'checked'); ?>
									<?php $this->assign('disable_value', ""); ?>
								<?php else: ?>
									<?php $this->assign('check_value', ""); ?>
									<?php $this->assign('disable_value', "disabled=true"); ?>
								<?php endif; ?>
								
								<?php if ($this->_tpl_vars['price']['is_basecurrency'] == 1): ?>
									<?php $this->assign('base_cur_check', 'checked'); ?>
								<?php else: ?>
									<?php $this->assign('base_cur_check', ""); ?>
								<?php endif; ?>
								
								<?php if ($this->_tpl_vars['price']['curname'] == $this->_tpl_vars['BASE_CURRENCY']): ?>
									<?php $this->assign('call_js_update_func', "updateUnitPrice('".($this->_tpl_vars['BASE_CURRENCY'])."', 'unit_price');"); ?>
								<?php else: ?>
									<?php $this->assign('call_js_update_func', ""); ?>
								<?php endif; ?>
								
								<td align="right" class="dvtCellLabel">
									<?php echo getTranslatedCurrencyString($this->_tpl_vars['price']['currencylabel']); ?>
 (<?php echo $this->_tpl_vars['price']['currencysymbol']; ?>
)
									<input type="checkbox" name="cur_<?php echo $this->_tpl_vars['price']['curid']; ?>
_check" id="cur_<?php echo $this->_tpl_vars['price']['curid']; ?>
_check" class="small" onclick="fnenableDisable(this,'<?php echo $this->_tpl_vars['price']['curid']; ?>
'); updateCurrencyValue(this,'<?php echo $this->_tpl_vars['price']['curname']; ?>
','<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
','<?php echo $this->_tpl_vars['price']['conversionrate']; ?>
');" <?php echo $this->_tpl_vars['check_value']; ?>
>
								</td>
								<td class="dvtCellInfo" align="left">
									<input <?php echo $this->_tpl_vars['disable_value']; ?>
 type="text" size="10" class="small" name="<?php echo $this->_tpl_vars['price']['curname']; ?>
" id="<?php echo $this->_tpl_vars['price']['curname']; ?>
" value="<?php echo $this->_tpl_vars['price']['curvalue']; ?>
" onBlur="<?php echo $this->_tpl_vars['call_js_update_func']; ?>
 fnpriceValidation('<?php echo $this->_tpl_vars['price']['curname']; ?>
');">
								</td>
								<td class="dvtCellInfo" align="left">
									<input disabled=true type="text" size="10" class="small" name="cur_conv_rate<?php echo $this->_tpl_vars['price']['curid']; ?>
" value="<?php echo $this->_tpl_vars['price']['conversionrate']; ?>
">
								</td>
								<td class="dvtCellInfo" align="center">
									<input <?php echo $this->_tpl_vars['disable_value']; ?>
 type="button" class="crmbutton small edit" id="cur_reset<?php echo $this->_tpl_vars['price']['curid']; ?>
"  onclick="updateCurrencyValue(this,'<?php echo $this->_tpl_vars['price']['curname']; ?>
','<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
','<?php echo $this->_tpl_vars['price']['conversionrate']; ?>
');" value="<?php echo $this->_tpl_vars['APP']['LBL_RESET']; ?>
"/>
								</td>
								<td class="dvtCellInfo">
									<input <?php echo $this->_tpl_vars['disable_value']; ?>
 type="radio" class="detailedViewTextBox" id="base_currency<?php echo $this->_tpl_vars['price']['curid']; ?>
" name="base_currency_input" value="<?php echo $this->_tpl_vars['price']['curname']; ?>
" <?php echo $this->_tpl_vars['base_cur_check']; ?>
 onchange="updateBaseCurrencyValue()" />
								</td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
						</table>
					</div>
					<?php endif; ?>
				<?php else: ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 56): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<?php if ($this->_tpl_vars['fldname'] == 'notime' && $this->_tpl_vars['ACTIVITY_MODE'] == 'Events'): ?>
				<?php if ($this->_tpl_vars['fldvalue'] == 1): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onclick="toggleTime()" checked>
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" onclick="toggleTime()" >
					</td>
				<?php endif; ?>
			<!-- For Portal Information we need a hidden field existing_portal with the current portal value -->
			<?php elseif ($this->_tpl_vars['fldname'] == 'portal'): ?>
				<td width="30%" align=left class="dvtCellInfo">
					<input type="hidden" name="existing_portal" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" <?php if ($this->_tpl_vars['fldvalue'] == 1): ?>checked<?php endif; ?>>
				</td>
			<?php else: ?>
				<?php if ($this->_tpl_vars['fldvalue'] == 1): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" checked>
					</td>
				<?php elseif ($this->_tpl_vars['fldname'] == 'filestatus' && $this->_tpl_vars['MODE'] == 'create'): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" checked>
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" <?php if (( $this->_tpl_vars['PROD_MODE'] == 'create' && ((is_array($_tmp=$this->_tpl_vars['fldname'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) != 'cf_' ) || ( ((is_array($_tmp=$this->_tpl_vars['fldname'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) != 'cf_' && $this->_tpl_vars['PRICE_BOOK_MODE'] == 'create' ) || $this->_tpl_vars['USER_MODE'] == 'create'): ?>checked<?php endif; ?>>
					</td>
				<?php endif; ?>
			<?php endif; ?>
		<?php elseif ($this->_tpl_vars['uitype'] == 23 || $this->_tpl_vars['uitype'] == 5 || $this->_tpl_vars['uitype'] == 6): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
					<?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
					<?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
				<?php endforeach; endif; unset($_from); ?>

				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" id="jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo $this->_tpl_vars['date_val']; ?>
">
				<img src="<?php echo vtiger_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="jscal_trigger_<?php echo $this->_tpl_vars['fldname']; ?>
">
				
				<?php if ($this->_tpl_vars['uitype'] == 6): ?>
					<input name="time_start" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="<?php echo $this->_tpl_vars['time_val']; ?>
">
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['uitype'] == 6 && $this->_tpl_vars['QCMODULE'] == 'Event'): ?>
					<input name="dateFormat" type="hidden" value="<?php echo $this->_tpl_vars['dateFormat']; ?>
">
				<?php endif; ?>
				<?php if ($this->_tpl_vars['uitype'] == 23 && $this->_tpl_vars['QCMODULE'] == 'Event'): ?>
					<input name="time_end" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="<?php echo $this->_tpl_vars['time_val']; ?>
">
				<?php endif; ?>
				
				<?php $_from = $this->_tpl_vars['secondvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_format'] => $this->_tpl_vars['date_str']):
?>
					<?php $this->assign('dateFormat', ($this->_tpl_vars['date_format'])); ?>
					<?php $this->assign('dateStr', ($this->_tpl_vars['date_str'])); ?>
				<?php endforeach; endif; unset($_from); ?>

				<?php if ($this->_tpl_vars['uitype'] == 5 || $this->_tpl_vars['uitype'] == 23): ?>
					<br><font size=1><em old="(yyyy-mm-dd)">(<?php echo $this->_tpl_vars['dateStr']; ?>
)</em></font>
				<?php else: ?>
					<br><font size=1><em old="(yyyy-mm-dd)">(<?php echo $this->_tpl_vars['dateStr']; ?>
)</em></font>
				<?php endif; ?>

				<script type="text/javascript" id='massedit_calendar_<?php echo $this->_tpl_vars['fldname']; ?>
'>
					Calendar.setup ({
						inputField : "jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
", ifFormat : "<?php echo $this->_tpl_vars['dateFormat']; ?>
", showsTime : false, button : "jscal_trigger_<?php echo $this->_tpl_vars['fldname']; ?>
", singleClick : true, step : 1
					})
				</script>


			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 63): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="text" size="2" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" >&nbsp;
				<select name="duration_minutes" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
					<?php $_from = $this->_tpl_vars['secondvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['labelval'] => $this->_tpl_vars['selectval']):
?>
						<option value="<?php echo $this->_tpl_vars['labelval']; ?>
" <?php echo $this->_tpl_vars['selectval']; ?>
><?php echo $this->_tpl_vars['labelval']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>

		<?php elseif ($this->_tpl_vars['uitype'] == 68 || $this->_tpl_vars['uitype'] == 66 || $this->_tpl_vars['uitype'] == 62): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<select class="small" name="parent_type" onChange='document.QcEditView.parent_name.value=""; document.QcEditView.parent_id.value=""'>
				<?php else: ?>
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
				<?php endif; ?>
					<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
						<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
					<?php endfor; endif; ?>
				</select>
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="parent_id_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>			
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				<input name="parent_name" readonly id = "parentid" type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
				&nbsp;
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php else: ?>
					<img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 357): ?>
			<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
			<td width="90%" colspan="3">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				<textarea readonly name="parent_name" cols="70" rows="2"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>&nbsp;
				<select name="parent_type" class="small">
					<?php $_from = $this->_tpl_vars['fldlabel']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['labelval'] => $this->_tpl_vars['selectval']):
?>
						<option value="<?php echo $this->_tpl_vars['labelval']; ?>
" <?php echo $this->_tpl_vars['selectval']; ?>
><?php echo $this->_tpl_vars['labelval']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>
				&nbsp;
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php else: ?>
					<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php endif; ?>
			</td>
		   <tr style="height:25px">
			<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>	
			<td width="30%" align=left class="dvtCellInfo">
				<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
			<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
		   </tr>

		<?php elseif ($this->_tpl_vars['uitype'] == 59): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				<input name="product_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">&nbsp;<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 55 || $this->_tpl_vars['uitype'] == 255): ?> 
			<td width="20%" class="dvtCellLabel" align=right>
			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1' && $this->_tpl_vars['fldvalue'] != ''): ?>
				<?php echo $this->_tpl_vars['APP']['Salutation']; ?>
<input type="checkbox" name="salutationtype_mass_edit_check" id="salutationtype_mass_edit_check" class="small" ><br />
			<?php endif; ?>
			<?php if ($this->_tpl_vars['uitype'] == 55): ?>
				<?php echo $this->_tpl_vars['usefldlabel']; ?>
<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			<?php elseif ($this->_tpl_vars['uitype'] == 255): ?>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			<?php endif; ?>
			</td>
			
			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['fldvalue'] != ''): ?>
			<select name="salutationtype" class="small">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
				<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
				<?php echo $this->_tpl_vars['arr'][0]; ?>

				</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><br /><?php endif; ?>
			<?php endif; ?>
			<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "<?php echo $this->_tpl_vars['secondvalue']; ?>
" >
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 22): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<textarea name="<?php echo $this->_tpl_vars['fldname']; ?>
" cols="30" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" rows="2"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 69): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  >
				<?php endif; ?>
			</td>
			<td colspan="3" width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>
						<!--input type="hidden" name="file_1_hidden" value=""/-->
						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>
				<?php else: ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" />
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
					<input type="hidden" name="id" value=""/>
					<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != "" && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						<div id="replaceimage">[<?php echo $this->_tpl_vars['maindata'][3]['0']['orgname']; ?>
] <a href="javascript:;" onClick="delimage(<?php echo $this->_tpl_vars['ID']; ?>
)">Del</a></div>
					<?php endif; ?>
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 61): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  disabled >
				<?php endif; ?>
			</td>

			<td colspan="1" width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this)"/>
				<input type="hidden" name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"/>
				<input type="hidden" name="id" value=""/><?php echo $this->_tpl_vars['fldvalue']; ?>

			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 156): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
				<?php if ($this->_tpl_vars['fldvalue'] == 'on'): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<?php if (( $this->_tpl_vars['secondvalue'] == 1 && $this->_tpl_vars['CURRENT_USERID'] != $_REQUEST['record'] ) || ( $this->_tpl_vars['MODE'] == 'create' )): ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" checked>
						<?php else: ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="on">
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" disabled tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" checked>
						<?php endif; ?>	
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<?php if (( $this->_tpl_vars['secondvalue'] == 1 && $this->_tpl_vars['CURRENT_USERID'] != $_REQUEST['record'] ) || ( $this->_tpl_vars['MODE'] == 'create' )): ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox">
						<?php else: ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" disabled tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox">
						<?php endif; ?>	
					</td>
				<?php endif; ?>
		<?php elseif ($this->_tpl_vars['uitype'] == 98): ?><!-- Role Selection Popup -->		
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['thirdvalue'] == 1): ?>
				<input name="role_name" id="role_name" readonly class="txtBox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" type="text">&nbsp;
				<a href="javascript:openPopup();"><img src="<?php echo vtiger_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" align="absmiddle" border="0"></a>
			<?php else: ?>	
				<input name="role_name" id="role_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="txtBox" readonly value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" type="text">&nbsp;
			<?php endif; ?>	
			<input name="user_role" id="user_role" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" type="hidden">
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 104): ?><!-- Mandatory Email Fields -->			
			 <td width=20% class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			 </td>
    	     <td width=30% align=left class="dvtCellInfo"><input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
			<?php elseif ($this->_tpl_vars['uitype'] == 115): ?><!-- for Status field Disabled for nonadmin -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <?php if ($this->_tpl_vars['secondvalue'] == 1 && $this->_tpl_vars['CURRENT_USERID'] != $_REQUEST['record']): ?>
			   	<select id="user_status" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   <?php else: ?>
			   	<select id="user_status" disabled name="<?php echo $this->_tpl_vars['fldname']; ?>
" class="small">
			   <?php endif; ?> 
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                                        <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 >
                                                <?php echo $this->_tpl_vars['arr'][0]; ?>

                                        </option>
				<?php endforeach; endif; unset($_from); ?>
			   </select>
			</td>
			<?php elseif ($this->_tpl_vars['uitype'] == 105): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['IMAGENAME'] != ''): ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" /><div id="replaceimage">[<?php echo $this->_tpl_vars['IMAGENAME']; ?>
]&nbsp;<a href="javascript:;" onClick="delUserImage(<?php echo $this->_tpl_vars['ID']; ?>
)">Del</a></div>
					<br><?php echo getTranslatedString('LBL_IMG_FORMATS', $this->_tpl_vars['MODULE']); ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
				<?php else: ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" /><br><?php echo getTranslatedString('LBL_IMG_FORMATS', $this->_tpl_vars['MODULE']); ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
				<?php endif; ?>
					<input type="hidden" name="id" value=""/>
					<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>

			</td>
			<?php elseif ($this->_tpl_vars['uitype'] == 103): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" colspan="3" align=left class="dvtCellInfo">
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>	
			<?php elseif ($this->_tpl_vars['uitype'] == 101): ?><!-- for reportsto field USERS POPUP -->
				<td width="20%" class="dvtCellLabel" align=right>
			      <font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
	            </td>
				<td width="30%" align=left class="dvtCellInfo">
					<input readonly name='reports_to_name' class="small" type="text" value='<?php echo $this->_tpl_vars['fldvalue']; ?>
' tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" >
					<input name='reports_to_id' type="hidden" value='<?php echo $this->_tpl_vars['secondvalue']; ?>
'>&nbsp;<input title="Change [Alt+C]" accessKey="C" type="button" class="small" value='<?php echo $this->_tpl_vars['UMOD']['LBL_CHANGE']; ?>
' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Users&action=Popup&form=UsersEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&recordid=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=640,height=603,resizable=0,scrollbars=0");'>
	            	&nbsp;<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.reports_to_id.value=''; this.form.reports_to_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
	            </td>
			<?php elseif ($this->_tpl_vars['uitype'] == 116 || $this->_tpl_vars['uitype'] == 117): ?><!-- for currency in users details-->	
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <?php if ($this->_tpl_vars['secondvalue'] == 1 || $this->_tpl_vars['uitype'] == 117): ?>
			   	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   <?php else: ?>
			   	<select disabled name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   <?php endif; ?> 

				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uivalueid'] => $this->_tpl_vars['arr']):
?>
					<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
						<option value="<?php echo $this->_tpl_vars['uivalueid']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo getTranslatedCurrencyString($this->_tpl_vars['sel_value']); ?>
</option>
						<!-- code added to pass Currency field value, if Disabled for nonadmin -->
						<?php if ($this->_tpl_vars['value'] == 'selected' && $this->_tpl_vars['secondvalue'] != 1): ?>
							<?php $this->assign('curr_stat', ($this->_tpl_vars['uivalueid'])); ?>
						<?php endif; ?>
						<!--code ends -->
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
			   </select>
			<!-- code added to pass Currency field value, if Disabled for nonadmin -->
			<?php if ($this->_tpl_vars['curr_stat'] != '' && $this->_tpl_vars['uitype'] != 117): ?>
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['curr_stat']; ?>
">
			<?php endif; ?>
			<!--code ends -->
			</td>
			<?php elseif ($this->_tpl_vars['uitype'] == 106): ?>
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width=30% align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODE'] == 'edit'): ?>
				<input type="text" readonly name="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				<?php else: ?>
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				<?php endif; ?>
			</td>
			<?php elseif ($this->_tpl_vars['uitype'] == 99): ?>
				<?php if ($this->_tpl_vars['MODE'] == 'create'): ?>
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>
				<td width=30% align=left class="dvtCellInfo">
					<input type="password" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				</td>
				<?php endif; ?>
		<?php elseif ($this->_tpl_vars['uitype'] == 30): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td colspan="3" width="30%" align=left class="dvtCellInfo">
				<?php $this->assign('check', $this->_tpl_vars['secondvalue'][0]); ?>
				<?php $this->assign('yes_val', $this->_tpl_vars['secondvalue'][1]); ?>
				<?php $this->assign('no_val', $this->_tpl_vars['secondvalue'][2]); ?>

				<input type="radio" name="set_reminder" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="Yes" <?php echo $this->_tpl_vars['check']; ?>
>&nbsp;<?php echo $this->_tpl_vars['yes_val']; ?>
&nbsp;
				<input type="radio" name="set_reminder" value="No">&nbsp;<?php echo $this->_tpl_vars['no_val']; ?>
&nbsp;

				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val_arr']):
?>
					<?php $this->assign('start', ($this->_tpl_vars['val_arr'][0])); ?>
					<?php $this->assign('end', ($this->_tpl_vars['val_arr'][1])); ?>
					<?php $this->assign('sendname', ($this->_tpl_vars['val_arr'][2])); ?>
					<?php $this->assign('disp_text', ($this->_tpl_vars['val_arr'][3])); ?>
					<?php $this->assign('sel_val', ($this->_tpl_vars['val_arr'][4])); ?>
					<select name="<?php echo $this->_tpl_vars['sendname']; ?>
" class="small">
						<?php unset($this->_sections['reminder']);
$this->_sections['reminder']['name'] = 'reminder';
$this->_sections['reminder']['start'] = (int)$this->_tpl_vars['start'];
$this->_sections['reminder']['max'] = (int)$this->_tpl_vars['end'];
$this->_sections['reminder']['loop'] = is_array($_loop=$this->_tpl_vars['end']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['reminder']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['reminder']['show'] = true;
if ($this->_sections['reminder']['max'] < 0)
    $this->_sections['reminder']['max'] = $this->_sections['reminder']['loop'];
if ($this->_sections['reminder']['start'] < 0)
    $this->_sections['reminder']['start'] = max($this->_sections['reminder']['step'] > 0 ? 0 : -1, $this->_sections['reminder']['loop'] + $this->_sections['reminder']['start']);
else
    $this->_sections['reminder']['start'] = min($this->_sections['reminder']['start'], $this->_sections['reminder']['step'] > 0 ? $this->_sections['reminder']['loop'] : $this->_sections['reminder']['loop']-1);
if ($this->_sections['reminder']['show']) {
    $this->_sections['reminder']['total'] = min(ceil(($this->_sections['reminder']['step'] > 0 ? $this->_sections['reminder']['loop'] - $this->_sections['reminder']['start'] : $this->_sections['reminder']['start']+1)/abs($this->_sections['reminder']['step'])), $this->_sections['reminder']['max']);
    if ($this->_sections['reminder']['total'] == 0)
        $this->_sections['reminder']['show'] = false;
} else
    $this->_sections['reminder']['total'] = 0;
if ($this->_sections['reminder']['show']):

            for ($this->_sections['reminder']['index'] = $this->_sections['reminder']['start'], $this->_sections['reminder']['iteration'] = 1;
                 $this->_sections['reminder']['iteration'] <= $this->_sections['reminder']['total'];
                 $this->_sections['reminder']['index'] += $this->_sections['reminder']['step'], $this->_sections['reminder']['iteration']++):
$this->_sections['reminder']['rownum'] = $this->_sections['reminder']['iteration'];
$this->_sections['reminder']['index_prev'] = $this->_sections['reminder']['index'] - $this->_sections['reminder']['step'];
$this->_sections['reminder']['index_next'] = $this->_sections['reminder']['index'] + $this->_sections['reminder']['step'];
$this->_sections['reminder']['first']      = ($this->_sections['reminder']['iteration'] == 1);
$this->_sections['reminder']['last']       = ($this->_sections['reminder']['iteration'] == $this->_sections['reminder']['total']);
?>
							<?php if ($this->_sections['reminder']['index'] == $this->_tpl_vars['sel_val']): ?>
								<?php $this->assign('sel_value', 'SELECTED'); ?>
							<?php else: ?>
								<?php $this->assign('sel_value', ""); ?>
							<?php endif; ?>
							<OPTION VALUE="<?php echo $this->_sections['reminder']['index']; ?>
" "<?php echo $this->_tpl_vars['sel_value']; ?>
"><?php echo $this->_sections['reminder']['index']; ?>
</OPTION>
						<?php endfor; endif; ?>
					</select>
					&nbsp;<?php echo $this->_tpl_vars['disp_text']; ?>

				<?php endforeach; endif; unset($_from); ?>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 26): ?>
		<td width="20%" class="dvtCellLabel" align=right>
		<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['fldlabel']; ?>

		<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
		</td>
		<td width="30%" align=left class="dvtCellInfo">
			<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>	 
				<option value="<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['v']; ?>
</option> 
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 27): ?>
		<td width="20%" class="dvtCellLabel" align="right" >
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['fldlabel_other']; ?>
&nbsp;
			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>			
		</td>
		<td width="30%" align=left class="dvtCellInfo">
			<select class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
" onchange="changeDldType((this.value=='I')? 'file': 'text');">
				<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
					<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
 ><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
				<?php endfor; endif; ?>
			</select>
			<script>
				function vtiger_<?php echo $this->_tpl_vars['fldname']; ?>
Init(){
					var d = document.getElementsByName('<?php echo $this->_tpl_vars['fldname']; ?>
')[0];
					var type = (d.value=='I')? 'file': 'text';

					changeDldType(type, true);
				}
				if(typeof window.onload =='function'){
					var oldOnLoad = window.onload;
					document.body.onload = function(){
						vtiger_<?php echo $this->_tpl_vars['fldname']; ?>
Init();
						oldOnLoad();
					}
				}else{
					window.onload = function(){
						vtiger_<?php echo $this->_tpl_vars['fldname']; ?>
Init();
					}
				}
				
			</script>
		</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 28): ?>
		<td width="20%" class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
				<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  disabled >
			<?php endif; ?>
		</td>

		<td colspan="1" width="30%" align="left" class="dvtCellInfo">
		<script type="text/javascript">
			function changeDldType(type, onInit){
				var fieldname = '<?php echo $this->_tpl_vars['fldname']; ?>
';
				if(!onInit){
					var dh = getObj('<?php echo $this->_tpl_vars['fldname']; ?>
_hidden');
					if(dh) dh.value = '';
				}
				
				var v1 = document.getElementById(fieldname+'_E__');
				var v2 = document.getElementById(fieldname+'_I__');
				
				var text = v1.type =="text"? v1: v2;
				var file = v1.type =="file"? v1: v2;
				var filename = document.getElementById(fieldname+'_value');
				<?php echo '
				if(type == \'file\'){
					// Avoid sending two form parameters with same key to server
					file.name = fieldname;
					text.name = \'_\' + fieldname;
					
					file.style.display = \'\';
					text.style.display = \'none\';	
					text.value = \'\';
					filename.style.display = \'\';
				}else{
					// Avoid sending two form parameters with same key to server
					text.name = fieldname;
					file.name = \'_\' + fieldname;
					
					file.style.display = \'none\';
					text.style.display = \'\';		
					file.value = \'\';
					filename.style.display = \'none\';
					filename.innerHTML="";
				}
				'; ?>

			}
		</script>
		<div>
			<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
_I__" type="file" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this)" style="display: none;"/>
			<input type="hidden" name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"/>
			<input type="hidden" name="id" value=""/>
			<input type="text" id="<?php echo $this->_tpl_vars['fldname']; ?>
_E__" name="<?php echo $this->_tpl_vars['fldname']; ?>
" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" /><br>
			<span id="<?php echo $this->_tpl_vars['fldname']; ?>
_value" style="display:none;">
				<?php if ($this->_tpl_vars['secondvalue'] != ''): ?>
					[<?php echo $this->_tpl_vars['secondvalue']; ?>
]
				<?php endif; ?>
			</span>
		</div>	
		</td>
		
		<?php elseif ($this->_tpl_vars['uitype'] == 83): ?> <!-- Handle the Tax in Inventory -->
			<?php $_from = $this->_tpl_vars['TAX_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['tax']):
?>
				<?php if ($this->_tpl_vars['tax']['check_value'] == 1): ?>
					<?php $this->assign('check_value', 'checked'); ?>
					<?php $this->assign('show_value', 'visible'); ?>
				<?php else: ?>
					<?php $this->assign('check_value', ""); ?>
					<?php $this->assign('show_value', 'hidden'); ?>
				<?php endif; ?>
				<td align="right" class="dvtCellLabel" style="border:0px solid red;">
					<?php echo $this->_tpl_vars['tax']['taxlabel']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

					<input type="checkbox" name="<?php echo $this->_tpl_vars['tax']['check_name']; ?>
" id="<?php echo $this->_tpl_vars['tax']['check_name']; ?>
" class="small" onclick="fnshowHide(this,'<?php echo $this->_tpl_vars['tax']['taxname']; ?>
')" <?php echo $this->_tpl_vars['check_value']; ?>
>
				</td>
				<td class="dvtCellInfo" align="left" style="border:0px solid red;">
					<input type="text" class="detailedViewTextBox" name="<?php echo $this->_tpl_vars['tax']['taxname']; ?>
" id="<?php echo $this->_tpl_vars['tax']['taxname']; ?>
" value="<?php echo $this->_tpl_vars['tax']['percentage']; ?>
" style="visibility:<?php echo $this->_tpl_vars['show_value']; ?>
;" onBlur="fntaxValidation('<?php echo $this->_tpl_vars['tax']['taxname']; ?>
')">
				</td>
			   </tr>
			<?php endforeach; endif; unset($_from); ?>

			<td colspan="2" class="dvtCellInfo">&nbsp;</td>
		<?php endif; ?>