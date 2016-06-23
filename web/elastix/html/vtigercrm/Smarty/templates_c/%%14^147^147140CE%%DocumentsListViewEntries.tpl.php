<?php /* Smarty version 2.6.18, created on 2014-09-02 14:34:20
         compiled from DocumentsListViewEntries.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'DocumentsListViewEntries.tpl', 62, false),array('modifier', 'count', 'DocumentsListViewEntries.tpl', 86, false),array('function', 'html_options', 'DocumentsListViewEntries.tpl', 295, false),)), $this); ?>
<?php if ($_REQUEST['ajax'] != ''): ?>
&#&#&#<?php echo $this->_tpl_vars['ERROR']; ?>
&#&#&#
<?php endif; ?>
<form name="massdelete" method="POST" id="massdelete">
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
	<table border="0" cellspacing="1" cellpadding="0" width="100%" class="lvtBg">
		<tr>
			<?php if ($this->_tpl_vars['NO_FOLDERS'] == 'yes'): ?>
			<td width="100%" valign="top" height="250px;"><br><br>
	        	<div align="center"> <br><br><br><br><br>
				<table width="80%" cellpadding="5" cellspacing="0"  class="searchUIBasic small" align="center" border=0>
				<tr><td align="center" style="padding:20px;">
					<a href="javascript:;" onclick="fnvshobj(this,'orgLay');"><?php echo $this->_tpl_vars['MOD']['LBL_CLICK_HERE']; ?>
</a>&nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_TO_ADD_FOLDER']; ?>

				</td></tr></table>
	        	</div>
			</td>
			<?php else: ?>
			<td>
				<!-- List View's Buttons and Filters starts -->
		        <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
			    	<tr>
			    		<td>
							<table border=0 cellspacing=0 cellpadding=0>
								<tr>
                        		<?php if ($this->_tpl_vars['MASS_DELETE'] == 'yes'): ?>
            						<td style="padding-right:5px"><input type="button" name="delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE']; ?>
" class="crmbutton small delete" onClick="return massDelete('<?php echo $this->_tpl_vars['MODULE']; ?>
');"></td>
                        		<?php endif; ?>
                        		<?php if ($this->_tpl_vars['IS_ADMIN'] == 'on'): ?>
            					<td style="padding-right:5px">
            						<input type="button" name="move" value="<?php echo $this->_tpl_vars['MOD']['LBL_MOVE']; ?>
" class="crmbutton small edit" onClick="fnvshNrm('movefolderlist'); posLay(this,'movefolderlist');" title="<?php echo $this->_tpl_vars['MOD']['LBL_MOVE_DOCUMENTS']; ?>
">
	            					<div style="display:none;position:absolute;width:150px;" id="movefolderlist" >
										<div class="layerPopup thickborder" style="display:block;position:relative;width:250px;">
											<table  class="layerHeadingULine" border="0" cellpadding="5" cellspacing="0" width="100%">
												<tr>
													<td class="genHeaderSmall" align="left" width="90%">
														<?php echo $this->_tpl_vars['MOD']['LBL_MOVE_TO']; ?>

													</td>
													<td align="right" width="10%">
														<a onclick="fninvsh('movefolderlist')" href="javascript:void(0);">
														<img border="0" align="absmiddle" src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
"/></a>
													</td>
												</tr>
											</table>
											<div style='padding: 10px;'>
											<table class="drop_down"  border="0" cellpadding="5" cellspacing="1" width="100%">
												<?php $_from = $this->_tpl_vars['ALL_FOLDERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['folder']):
?>
												<tr class='lvtColData' onmouseout="this.className='lvtColData'" onmouseover="this.className='lvtColDataHover'">
													<td align="left">
														<a href="javascript:;" onClick="MoveFile('<?php echo $this->_tpl_vars['folder']['folderid']; ?>
','<?php echo $this->_tpl_vars['folder']['foldername']; ?>
');" > <?php echo $this->_tpl_vars['folder']['foldername']; ?>
</a>
													</td>
												</tr>
												<?php endforeach; endif; unset($_from); ?>
											</table>
											</div>
										</div>
								</div>


            					</td>
            					<?php endif; ?>
            					<?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes'): ?>
            						<td style="padding-right:5px"><input type="button" name="add" value="<?php echo $this->_tpl_vars['MOD']['LBL_ADD_NEW_FOLDER']; ?>
" class="crmbutton small edit" onClick="fnvshobj(this,'orgLay');" title="<?php echo $this->_tpl_vars['MOD']['LBL_ADD_NEW_FOLDER']; ?>
"></td>
      							<?php endif; ?>
      							<?php if (count($this->_tpl_vars['EMPTY_FOLDERS']) > 0): ?>
      							<td>
      								<input type="button" name="show" value="<?php echo $this->_tpl_vars['MOD']['LBL_VIEW_EMPTY_FOLDERS']; ?>
" class="crmbutton small cancel" onClick="fnvshobj(this,'emptyfolder');" title="<?php echo $this->_tpl_vars['MOD']['LBL_VIEW_EMPTY_FOLDERS']; ?>
">
								</td>
								<?php endif; ?>
							</tr>
							</table>
						</td>
						<td width="100%" align="right">
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
</SELECT>
									</td>
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
</span>
									</td>
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
				<?php $_from = $this->_tpl_vars['FOLDERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['folder']):
?>
				<!-- folder division starts -->
				<?php $this->assign('foldercount', count($this->_tpl_vars['FOLDERS'])); ?>
				<br>
				<div id='<?php echo $this->_tpl_vars['folder']['folderid']; ?>
'>
					<table class="reportsListTable" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="mailSubHeader" width="30%">
								<b><?php echo $this->_tpl_vars['folder']['foldername']; ?>
</b>
								&nbsp;&nbsp;
								<?php if ($this->_tpl_vars['folder']['description'] != ''): ?>
								<font class="copy">[<i><?php echo $this->_tpl_vars['folder']['description']; ?>
</i>]</font>
								<?php endif; ?>
							</td>
							<td class="mailSubHeader small" nowrap><?php echo $this->_tpl_vars['folder']['recordListRange']; ?>
</td>
							<?php echo $this->_tpl_vars['folder']['record_count']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['folder']['navigation']; ?>

						</tr>
						<tr>
							<td colspan="4" >
								<div id="FileList_<?php echo $this->_tpl_vars['folder']['folderid']; ?>
">
					 				<!-- File list table for a folder starts -->
									<table border=0 cellspacing=1 cellpadding=3 width=100%>
										<!-- Table Headers -->
										<?php $this->assign('header_count', count($this->_tpl_vars['folder']['header'])); ?>
										<tr>
		            						<td class="lvtCol" width="10px"><input type="checkbox"  name="selectall<?php echo $this->_tpl_vars['folder']['folderid']; ?>
" onClick='toggleSelect_ListView(this.checked,"selected_id<?php echo $this->_tpl_vars['folder']['folderid']; ?>
","selectall<?php echo $this->_tpl_vars['folder']['folderid']; ?>
");'></td>
											<?php $_from = $this->_tpl_vars['folder']['header']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['listviewforeach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['listviewforeach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['header']):
        $this->_foreach['listviewforeach']['iteration']++;
?>
											<td class="lvtCol"><?php echo $this->_tpl_vars['header']; ?>
</td>
											<?php endforeach; endif; unset($_from); ?>
										</tr>
										<!-- Table Contents -->
										<?php $_from = $this->_tpl_vars['folder']['entries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entity_id'] => $this->_tpl_vars['entity']):
?>
										<tr class="lvtColData" bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_<?php echo $this->_tpl_vars['entity_id']; ?>
">
											<td width="2%"><input type="checkbox" name="selected_id<?php echo $this->_tpl_vars['folder']['folderid']; ?>
" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value= '<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick='check_object(this,"selectall<?php echo $this->_tpl_vars['folder']['folderid']; ?>
")'></td>
											<?php $_from = $this->_tpl_vars['entity']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['record_id'] => $this->_tpl_vars['recordid']):
?>
												<?php $_from = $this->_tpl_vars['recordid']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
																										<td onmouseover="vtlib_listview.trigger('cell.onmouseover', $(this))" onmouseout="vtlib_listview.trigger('cell.onmouseout', $(this))"><?php echo $this->_tpl_vars['data']; ?>
</td>
																						        	<?php endforeach; endif; unset($_from); ?>
								        	<?php endforeach; endif; unset($_from); ?>
										</tr>
										<!-- If there are no entries for a folder -->
										<?php endforeach; else: ?>
											<?php if ($this->_tpl_vars['foldercount'] == 1): ?>
												<tr>
													<td align="center" style="background-color:#efefef;height:340px" colspan="<?php echo $this->_tpl_vars['header_count']+1; ?>
">
														<div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 45%; position: relative;">
															<?php $this->assign('vowel_conf', 'LBL_A'); ?>
															<?php $this->assign('MODULE_CREATE', $this->_tpl_vars['SINGLE_MOD']); ?>
															<?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes'): ?>
																<table border="0" cellpadding="5" cellspacing="0" width="98%">
																	<tr>
																		<td rowspan="2" width="25%"><img src="<?php echo vtiger_imageurl('empty.jpg', $this->_tpl_vars['THEME']); ?>
" height="60" width="61"></td>
																		<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">
																																				<?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
																			</span>
																		</td>
																	</tr>
																	<tr>
																		<td class="small" align="left" nowrap="nowrap"><?php echo $this->_tpl_vars['APP']['LBL_YOU_CAN_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>

																												 										<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?>
									 											<?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>

									 										<?php else: ?>
									 											<?php echo $this->_tpl_vars['MODULE_CREATE']; ?>

									 										<?php endif; ?>
									 										<?php echo $this->_tpl_vars['APP']['LBL_NOW']; ?>
. <?php echo $this->_tpl_vars['APP']['LBL_CLICK_THE_LINK']; ?>
:<br>
									 										&nbsp;&nbsp;-<a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>
 <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

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
													</td>
												</tr>
											<?php endif; ?>
										<?php endif; unset($_from); ?>
					 				</table>
								</div>
							<!-- File list table for a folder ends -->
							</td>
						</tr>
					</table>
				</div>
				<!-- folder division ends -->
				<?php endforeach; endif; unset($_from); ?>
				<!-- $FOLDERS ends -->
			</td>
			<?php endif; ?>
		<!-- conditional statement for $NO_FOLDERS ends -->
		</tr>
	</table>

			<!-- Move documents UI for Documents module starts -->


		<!-- Move documents UI for Documents module ends -->
		<div class="layerPopup thickborder" style="display:none;position:absolute; left:193px;top:106px;width:250px;" id="emptyfolder">
			<table  class="layerHeadingULine" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td class="genHeaderSmall" align="left">
						<?php echo $this->_tpl_vars['MOD']['LBL_EMPTY_FOLDERS']; ?>

					</td>
					<td align="right" width="40%">
						<a onclick="fninvsh('emptyfolder')" href="javascript:void(0);">
						<img border="0" align="absmiddle" src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
"/></a>
					</td>
				</tr>
			</table>
			<div style='padding: 10px;'>
			<table class="drop_down"  border=0 cellpadding=5 cellspacing=0 width=100%>
			<?php $_from = $this->_tpl_vars['EMPTY_FOLDERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['folder']):
?>
				<tr onmouseout="this.className='lvtColData'" onmouseover="this.className='lvtColDataHover'">
					<td><?php echo $this->_tpl_vars['folder']['foldername']; ?>
</td>
					<td align=right>
						<?php if ($this->_tpl_vars['IS_ADMIN'] == 'on' && $this->_tpl_vars['folder']['folderid'] != '1'): ?>
							<a href="javascript:;" onclick="DeleteFolderCheck(<?php echo $this->_tpl_vars['folder']['folderid']; ?>
);"><img border="0" src="<?php echo vtiger_imageurl('delete.gif', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;"/></a>
						<?php else: ?>
							&nbsp;
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
			</table>
			</div>
		</div>

</form>
<?php echo $this->_tpl_vars['SELECT_SCRIPT']; ?>

<div id="basicsearchcolumns" style="display:none;"><select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['SEARCHLISTHEADER']), $this);?>
</select></div>

<script>
<?php echo '
function showHideFolders(show_ele, hide_ele) {
	var show_obj = document.getElementById(show_ele);
	var hide_obj = document.getElementById(hide_ele);
	if (show_obj != null) {
		show_obj.style.display="block";
	}
	if (hide_obj != null) {
		hide_obj.style.display="none";
	}
}
'; ?>

</script>