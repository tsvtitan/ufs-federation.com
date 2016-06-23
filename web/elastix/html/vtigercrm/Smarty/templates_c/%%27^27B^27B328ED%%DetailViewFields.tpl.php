<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:35
         compiled from DetailViewFields.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'DetailViewFields.tpl', 30, false),array('modifier', 'vtiger_imageurl', 'DetailViewFields.tpl', 42, false),array('modifier', 'cat', 'DetailViewFields.tpl', 91, false),array('modifier', 'replace', 'DetailViewFields.tpl', 96, false),array('modifier', 'regex_replace', 'DetailViewFields.tpl', 112, false),)), $this); ?>

<!-- This file is used to display the fields based on the ui type in detailview -->
		<?php if ($this->_tpl_vars['keyid'] == '1' || $this->_tpl_vars['keyid'] == 2 || $this->_tpl_vars['keyid'] == '11' || $this->_tpl_vars['keyid'] == '7' || $this->_tpl_vars['keyid'] == '9' || $this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '71' || $this->_tpl_vars['keyid'] == '72' || $this->_tpl_vars['keyid'] == '255'): ?> <!--TextBox-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;
				<?php if ($this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '255'): ?><!--SalutationSymbol-->
					<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
						<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>
					<?php else: ?>
						<?php echo $this->_tpl_vars['keysalut']; ?>

					<?php endif; ?>
								<?php endif; ?>
				<span id ="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
				
                <?php if ($this->_tpl_vars['keyid'] == '71' && $this->_tpl_vars['keyfldname'] == 'unit_price'): ?>	
                	<?php if (count($this->_tpl_vars['PRICE_DETAILS']) > 0): ?>				
						<span id="multiple_currencies" width="38%" style="align:right;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="toggleShowHide('currency_class','multiple_currencies');"><?php echo $this->_tpl_vars['APP']['LBL_MORE_CURRENCIES']; ?>
 &raquo;</a>
						</span>
						
						<div id="currency_class" class="multiCurrencyDetailUI">					
							<table width="100%" height="100%" class="small" cellpadding="5">
							<tr class="detailedViewHeader">							
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
										<?php echo $this->_tpl_vars['price']['currencylabel']; ?>
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
		<?php elseif ($this->_tpl_vars['keyid'] == '13'): ?> <!--Email-->
			<td width=25% class="dvtCellInfo" align="left">
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
			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '15' || $this->_tpl_vars['keyid'] == '16'): ?> <!--ComboBox-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;
				<?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
					<?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
						<?php $this->assign('keyval', $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']); ?>
						<?php $this->assign('fontval', 'red'); ?>
					<?php else: ?>
						<?php $this->assign('fontval', ''); ?>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
				<font color="<?php echo $this->_tpl_vars['fontval']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</font>
			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '33'): ?>
			<td width=25% class="dvtCellInfo" align="left">&nbsp;
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

			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '17'): ?> <!--WebSite-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="http://<?php echo $this->_tpl_vars['keyval']; ?>
" target="_blank"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '85'): ?><!--Skype-->
			<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">
				&nbsp;<img src="<?php echo vtiger_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" LANGUAGE=javascript align="absmiddle"></img>
				<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="skype:<?php echo $this->_tpl_vars['keyval']; ?>
?call"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
			</td>	
		<?php elseif ($this->_tpl_vars['keyid'] == '19' || $this->_tpl_vars['keyid'] == '20'): ?> <!--TextArea/Description-->
			<?php if ($this->_tpl_vars['label'] == $this->_tpl_vars['MOD']['LBL_ADD_COMMENT']): ?>
				<?php $this->assign('keyval', ''); ?>
			<?php endif; ?>
			<td width=100% class="dvtCellInfo" align="left">&nbsp;
				<!--To give hyperlink to URL-->
				<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>
                   
			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '21' || $this->_tpl_vars['keyid'] == '24' || $this->_tpl_vars['keyid'] == '22'): ?> <!--TextArea/Street-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;<span id ="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '50' || $this->_tpl_vars['keyid'] == '73' || $this->_tpl_vars['keyid'] == '51' || $this->_tpl_vars['keyid'] == '57' || $this->_tpl_vars['keyid'] == '59' || $this->_tpl_vars['keyid'] == '75' || $this->_tpl_vars['keyid'] == '81' || $this->_tpl_vars['keyid'] == '76' || $this->_tpl_vars['keyid'] == '78' || $this->_tpl_vars['keyid'] == '80'): ?> <!--AccountPopup-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == 82): ?> <!--Email Body-->
			<td colspan="3" width=100% class="dvtCellInfo" align="left">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>

			</td>
		<?php elseif ($this->_tpl_vars['keyid'] == '53'): ?> <!--Assigned To-->
            <td width=25% class="dvtCellInfo" align="left">&nbsp;
	            <?php if ($this->_tpl_vars['keyseclink'] == ''): ?>
	                <?php echo $this->_tpl_vars['keyval']; ?>

	            <?php else: ?>
	               	<a href="<?php echo $this->_tpl_vars['keyseclink']['0']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>         
	            <?php endif; ?>
				&nbsp;            
            </td>
		<?php elseif ($this->_tpl_vars['keyid'] == '56'): ?> <!--CheckBox--> 
			<td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>
&nbsp;
			</td>     
		<?php elseif ($this->_tpl_vars['keyid'] == 83): ?><!-- Handle the Tax in Inventory -->
				<td align="right" class="dvtCellLabel">
					<?php echo $this->_tpl_vars['APP']['LBL_VAT']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>
							
				</td>
				<td class="dvtCellInfo" align="left">&nbsp;
					<?php echo $this->_tpl_vars['VAT_TAX']; ?>

				</td>
				<td colspan="2" class="dvtCellInfo">&nbsp;</td>
			</tr>
			<tr>
				<td align="right" class="dvtCellLabel">
					<?php echo $this->_tpl_vars['APP']['LBL_SALES']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_TAX']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

				</td> 
				<td class="dvtCellInfo" align="left">&nbsp;
					<?php echo $this->_tpl_vars['SALES_TAX']; ?>

				</td>	
				<td colspan="2" class="dvtCellInfo">&nbsp;</td>
			</tr>
			<tr>
				<td align="right" class="dvtCellLabel">
					<?php echo $this->_tpl_vars['APP']['LBL_SERVICE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_TAX']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

				</td>
				<td class="dvtCellInfo" align="left" >&nbsp;
					<?php echo $this->_tpl_vars['SERVICE_TAX']; ?>

				</td>	

		<?php elseif ($this->_tpl_vars['keyid'] == 69): ?><!-- for Image Reflection -->
			<td align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
		<?php else: ?>									
			<td class="dvtCellInfo" align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
		<?php endif; ?>