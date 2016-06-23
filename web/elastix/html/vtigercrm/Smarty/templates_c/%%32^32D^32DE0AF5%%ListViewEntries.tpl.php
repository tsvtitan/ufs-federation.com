<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:01
         compiled from ListViewEntries.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'ListViewEntries.tpl', 64, false),array('modifier', 'vtiger_imageurl', 'ListViewEntries.tpl', 74, false),array('function', 'html_options', 'ListViewEntries.tpl', 348, false),)), $this); ?>
<?php if ($_REQUEST['ajax'] != ''): ?>
&#&#&#<?php echo $this->_tpl_vars['ERROR']; ?>
&#&#&#
<?php endif; ?>
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<form name="massdelete" method="POST" id="massdelete" onsubmit="VtigerJS_DialogBox.block();">
     <input name='search_url' id="search_url" type='hidden' value='<?php echo $this->_tpl_vars['SEARCH_URL']; ?>
'>
     <input name="idlist" id="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
     <input name="action" type="hidden">
     <input name="where_export" type="hidden" value="<?php  echo to_html($_SESSION['export_where']); ?>">
     <input name="step" type="hidden">
     <input name="allids" type="hidden" id="allids" value="<?php echo $this->_tpl_vars['ALLIDS']; ?>
">
     <input name="selectedboxes" id="selectedboxes" type="hidden" value="<?php echo $this->_tpl_vars['SELECTEDIDS']; ?>
">
     <input name="allselectedboxes" id="allselectedboxes" type="hidden" value="<?php echo $this->_tpl_vars['ALLSELECTEDIDS']; ?>
">
     <input name="current_page_boxes" id="current_page_boxes" type="hidden" value="<?php echo $this->_tpl_vars['CURRENT_PAGE_BOXES']; ?>
">
				<!-- List View Master Holder starts -->
				<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
				<tr>
				<td>
				<!-- List View's Buttons and Filters starts -->
		        <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
			    <tr>
				<!-- Buttons -->
				<td style="padding-right:20px" nowrap>

                 <?php $_from = $this->_tpl_vars['BUTTONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['button_check'] => $this->_tpl_vars['button_label']):
?>
                    <?php if ($this->_tpl_vars['button_check'] == 'del'): ?>
                         <input class="crmbutton small delete" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massDelete('<?php echo $this->_tpl_vars['MODULE']; ?>
')"/>
                    <?php elseif ($this->_tpl_vars['button_check'] == 'mass_edit'): ?>
                         <input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return mass_edit(this, 'massedit', '<?php echo $this->_tpl_vars['MODULE']; ?>
', '<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"/>
                    <?php elseif ($this->_tpl_vars['button_check'] == 's_mail'): ?>
                         <input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return eMail('<?php echo $this->_tpl_vars['MODULE']; ?>
',this);"/>
					<?php elseif ($this->_tpl_vars['button_check'] == 's_cmail'): ?>
                         <input class="crmbutton small edit" type="submit" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massMail('<?php echo $this->_tpl_vars['MODULE']; ?>
')"/>
                    <?php elseif ($this->_tpl_vars['button_check'] == 'mailer_exp'): ?>
                         <input class="crmbutton small edit" type="submit" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return mailer_export()"/>
                                        <?php elseif ($this->_tpl_vars['button_check'] == 'c_owner' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                    	<input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return change(this,'changeowner')"/>
					<?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
                
                				<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEWBASIC']): ?>
					<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEWBASIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
						<?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
						<?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
						<?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
							<?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
						<?php else: ?>
														<?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
						<?php endif; ?>
						<input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['customlink_label']; ?>
" onclick="<?php echo $this->_tpl_vars['customlink_href']; ?>
" />
					<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
				
								<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && ! empty ( $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEW'] )): ?>
					&nbsp;
					<a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');">
							<b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 <img src="<?php echo vtiger_imageurl('arrow_down.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></b>
					</a>
					<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay" 
						onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
						<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 &#187;</b></td></tr>
						<tr>
							<td>
								<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEW']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
									<?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
									<?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
									<?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
										<?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
									<?php else: ?>
																				<?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
									<?php endif; ?>
									<a href="<?php echo $this->_tpl_vars['customlink_href']; ?>
" class="drop_down"><?php echo $this->_tpl_vars['customlink_label']; ?>
</a>
								<?php endforeach; endif; unset($_from); ?>
							</td>
						</tr>
						</table>
					</div>
				<?php endif; ?>
							
                </td>
				<td class="small" nowrap>
					<?php echo $this->_tpl_vars['recordListRange']; ?>

				</td>
				<!-- Page Navigation -->
				<td nowrap width="30%" align="center">
					<table border=0 cellspacing=0 cellpadding=0 class="small">
						<tr><?php echo $this->_tpl_vars['NAVIGATION']; ?>
</tr>
					</table>
                </td>
				<td width=40% align="right">
				   <!-- Filters -->
				   <?php if ($this->_tpl_vars['HIDE_CUSTOM_LINKS'] != '1'): ?>
					<table border=0 cellspacing=0 cellpadding=0 class="small">
					<tr>
						<td><?php echo $this->_tpl_vars['APP']['LBL_VIEW']; ?>
</td>
						<td style="padding-left:5px;padding-right:5px">
                            <SELECT NAME="viewname" id="viewname" class="small" onchange="showDefaultCustomView(this,'<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><?php echo $this->_tpl_vars['CUSTOMVIEW_OPTION']; ?>
</SELECT></td>
                            <?php if ($this->_tpl_vars['ALL'] == 'All'): ?>
							<td><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_CREATEVIEW']; ?>
</a>
							<span class="small">|</span>
							<span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</span>
							<span class="small">|</span>
							<span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</span></td>
						    <?php else: ?>
							<td>
								<a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_CREATEVIEW']; ?>
</a>
								<span class="small">|</span>
								<?php if ($this->_tpl_vars['CV_EDIT_PERMIT'] != 'yes'): ?>
									<span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</span>
								<?php else: ?>
									<a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&record=<?php echo $this->_tpl_vars['VIEWID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</a>
								<?php endif; ?>
								<span class="small">|</span>
								<?php if ($this->_tpl_vars['CV_DELETE_PERMIT'] != 'yes'): ?>
									<span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</span>
								<?php else: ?>
									<a href="javascript:confirmdelete('index.php?module=CustomView&action=Delete&dmodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&record=<?php echo $this->_tpl_vars['VIEWID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</a>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['CUSTOMVIEW_PERMISSION']['ChangedStatus'] != '' && $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['Label'] != ''): ?>
									<span class="small">|</span>	
								   		<a href="#" id="customstatus_id" onClick="ChangeCustomViewStatus(<?php echo $this->_tpl_vars['VIEWID']; ?>
,<?php echo $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['Status']; ?>
,<?php echo $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['ChangedStatus']; ?>
,'<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><?php echo $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['Label']; ?>
</a>
								<?php endif; ?>
							</td>
						    <?php endif; ?>
					</tr>
					</table> 
				   <!-- Filters  END-->
				   <?php endif; ?>

				</td>	
       		    </tr>
			</table>
			<!-- List View's Buttons and Filters ends -->
			
			<div  >
			<table border=0 cellspacing=1 cellpadding=3 width=100% class="lvt small">
			<!-- Table Headers -->
			<tr>
            <td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect_ListView(this.checked,"selected_id")></td>
				 <?php $_from = $this->_tpl_vars['LISTHEADER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['listviewforeach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['listviewforeach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['header']):
        $this->_foreach['listviewforeach']['iteration']++;
?>
 			<td class="lvtCol"><?php echo $this->_tpl_vars['header']; ?>
</td>
				<?php endforeach; endif; unset($_from); ?>
			</tr>
			<!-- Table Contents -->
			<?php $_from = $this->_tpl_vars['LISTENTITY']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entity_id'] => $this->_tpl_vars['entity']):
?>
			<tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_<?php echo $this->_tpl_vars['entity_id']; ?>
">
			<td width="2%"><input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value= '<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)"></td>
			<?php $_from = $this->_tpl_vars['entity']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				
			<td onmouseover="vtlib_listview.trigger('cell.onmouseover', $(this))" onmouseout="vtlib_listview.trigger('cell.onmouseout', $(this))"><?php echo $this->_tpl_vars['data']; ?>
</td>
				        <?php endforeach; endif; unset($_from); ?>
			</tr>
			<?php endforeach; else: ?>
			<tr><td style="background-color:#efefef;height:340px" align="center" colspan="<?php echo $this->_foreach['listviewforeach']['iteration']+1; ?>
">
			<div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 10000000;">
				<?php $this->assign('vowel_conf', 'LBL_A'); ?>
				<?php if ($this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Invoice'): ?>
				<?php $this->assign('vowel_conf', 'LBL_AN'); ?>
				<?php endif; ?>
				<?php $this->assign('MODULE_CREATE', $this->_tpl_vars['SINGLE_MOD']); ?>
				<?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
				<?php $this->assign('MODULE_CREATE', 'Ticket'); ?>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Emails' && $this->_tpl_vars['MODULE'] != 'Webmails'): ?>
							
				<table border="0" cellpadding="5" cellspacing="0" width="98%">
				<tr>
					<td rowspan="2" width="25%"><img src="<?php echo vtiger_imageurl('empty.jpg', $this->_tpl_vars['THEME']); ?>
" height="60" width="61"></td>
					<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">
					<?php if ($this->_tpl_vars['MODULE_CREATE'] == 'SalesOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'PurchaseOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'Invoice' || $this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
						<?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
					<?php elseif ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
						<?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo $this->_tpl_vars['APP']['ACTIVITIES']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
					<?php else: ?>
												<?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
					<?php endif; ?>
					</span></td>
				</tr>
				<tr>
					<td class="small" align="left" nowrap="nowrap"><?php echo $this->_tpl_vars['APP']['LBL_YOU_CAN_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>


					<?php if ($this->_tpl_vars['MODULE_CREATE'] == 'SalesOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'PurchaseOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'Invoice' || $this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
						 <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

					<?php else: ?>
						 						 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
					<?php endif; ?>

					<?php echo $this->_tpl_vars['APP']['LBL_NOW']; ?>
. <?php echo $this->_tpl_vars['APP']['LBL_CLICK_THE_LINK']; ?>
:<br>
					<?php if ($this->_tpl_vars['MODULE'] != 'Calendar'): ?>	
		  			&nbsp;&nbsp;-<a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>

					<?php if ($this->_tpl_vars['MODULE_CREATE'] == 'SalesOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'PurchaseOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'Invoice' || $this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
						 <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

					<?php else: ?>
						 						 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
					<?php endif; ?>
					</a><br>
					<?php else: ?>
					&nbsp;&nbsp;-<a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&amp;action=EditView&amp;return_module=Calendar&amp;activity_mode=Events&amp;return_action=DetailView&amp;parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_AN']; ?>
 <?php echo $this->_tpl_vars['APP']['Event']; ?>
</a><br>
					&nbsp;&nbsp;-<a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&amp;action=EditView&amp;return_module=Calendar&amp;activity_mode=Task&amp;return_action=DetailView&amp;parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_A']; ?>
 <?php echo $this->_tpl_vars['APP']['Task']; ?>
</a>
					<?php endif; ?>
					</td>
				</tr>
				</table> 
			<?php else: ?>
				<table border="0" cellpadding="5" cellspacing="0" width="98%">
				<tr>
				<td rowspan="2" width="25%"><img src="<?php echo vtiger_imageurl('denied.gif', $this->_tpl_vars['THEME']); ?>
"></td>
				<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">
				<?php if ($this->_tpl_vars['MODULE_CREATE'] == 'SalesOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'PurchaseOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'Invoice' || $this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
					<?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !</span></td>
				<?php else: ?>
										<?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !</span></td>
				<?php endif; ?>
				</tr>
				<tr>
				<td class="small" align="left" nowrap="nowrap"><?php echo $this->_tpl_vars['APP']['LBL_YOU_ARE_NOT_ALLOWED_TO_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>

				<?php if ($this->_tpl_vars['MODULE_CREATE'] == 'SalesOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'PurchaseOrder' || $this->_tpl_vars['MODULE_CREATE'] == 'Invoice' || $this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
					 <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

				<?php else: ?>
					 					 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
				<?php endif; ?>
				<br>
				</td>
				</tr>
				</table>
				<?php endif; ?>
				</div>					
				</td></tr>	
			     <?php endif; unset($_from); ?>
			 </table>
			 </div>
			 
			 <table border=0 cellspacing=0 cellpadding=2 width=100%>
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 <?php $_from = $this->_tpl_vars['BUTTONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['button_check'] => $this->_tpl_vars['button_label']):
?>
                                        <?php if ($this->_tpl_vars['button_check'] == 'del'): ?>
                                            <input class="crmbutton small delete" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massDelete('<?php echo $this->_tpl_vars['MODULE']; ?>
')"/>
					                    <?php elseif ($this->_tpl_vars['button_check'] == 'mass_edit'): ?>
					                         <input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return mass_edit(this, 'massedit', '<?php echo $this->_tpl_vars['MODULE']; ?>
')"/>
                                        <?php elseif ($this->_tpl_vars['button_check'] == 's_mail'): ?>
                                             <input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return eMail('<?php echo $this->_tpl_vars['MODULE']; ?>
',this)"/>
                                        <?php elseif ($this->_tpl_vars['button_check'] == 's_cmail'): ?>
                                             <input class="crmbutton small edit" type="submit" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massMail('<?php echo $this->_tpl_vars['MODULE']; ?>
')"/>
                                        <?php elseif ($this->_tpl_vars['button_check'] == 'mailer_exp'): ?>
                                             <input class="crmbutton small edit" type="submit" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return mailer_export()"/>
                                                                                <?php elseif ($this->_tpl_vars['button_check'] == 'c_owner' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
											<input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return change(this,'changeowner')"/>
										<?php endif; ?>
                                 <?php endforeach; endif; unset($_from); ?>						
                
		                						<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEWBASIC']): ?>
							<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEWBASIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
								<?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
								<?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
								<?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
									<?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
								<?php else: ?>
																		<?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
								<?php endif; ?>
								<input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['customlink_label']; ?>
" onclick="<?php echo $this->_tpl_vars['customlink_href']; ?>
" />
							<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						
												<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && ! empty ( $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEW'] )): ?>
							&nbsp;
							<a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');">
									<b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 <img src="<?php echo vtiger_imageurl('arrow_down.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></b>
							</a>
							<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay" 
								onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
								<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 &#187;</b></td></tr>
								<tr>
									<td>
										<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['LISTVIEW']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
											<?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
											<?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
											<?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
												<?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
											<?php else: ?>
																								<?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
											<?php endif; ?>
											<a href="<?php echo $this->_tpl_vars['customlink_href']; ?>
" class="drop_down"><?php echo $this->_tpl_vars['customlink_label']; ?>
</a>
										<?php endforeach; endif; unset($_from); ?>
									</td>
								</tr>
								</table>
							</div>
						<?php endif; ?>
						                    </td>
				<td class="small" nowrap>
					<?php echo $this->_tpl_vars['recordListRange']; ?>

				</td>
				<td nowrap width="30%" align="center">
				    <table border=0 cellspacing=0 cellpadding=0 class="small">
				         <tr><?php echo $this->_tpl_vars['NAVIGATION']; ?>
</tr>
				     </table>
				 </td>
				 <td align="right" width=40%>
				   <table border=0 cellspacing=0 cellpadding=0 class="small">
					<tr>
                                           <?php echo $this->_tpl_vars['WORDTEMPLATEOPTIONS']; ?>
<?php echo $this->_tpl_vars['MERGEBUTTON']; ?>

					</tr>
				   </table>
				 </td>
			      </tr>
       		    </table>
		       </td>
		   </tr>
	    </table>

   </form>	
<?php echo $this->_tpl_vars['SELECT_SCRIPT']; ?>

<div id="basicsearchcolumns" style="display:none;"><select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['SEARCHLISTHEADER']), $this);?>
</select></div>