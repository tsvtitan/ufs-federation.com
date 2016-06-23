<?php /* Smarty version 2.6.18, created on 2014-09-03 08:40:30
         compiled from ActivityEditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtlib_purify', 'ActivityEditView.tpl', 23, false),array('modifier', 'count', 'ActivityEditView.tpl', 358, false),array('modifier', 'vtiger_imageurl', 'ActivityEditView.tpl', 986, false),)), $this); ?>


<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-<?php echo $this->_tpl_vars['CALENDAR_LANG']; ?>
.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/Calendar.js"></script>
<script type="text/javascript">
var gVTModule = '<?php echo vtlib_purify($_REQUEST['module']); ?>
';
</script>

<form name="EditView" method="POST" action="index.php" 
	<?php if ($this->_tpl_vars['ACTIVITY_MODE'] != 'Task'): ?> onsubmit="if(check_form()){ VtigerJS_DialogBox.block(); } else { return false; }" 
	<?php else: ?> onsubmit="maintask_check_form();if(formValidate()) { VtigerJS_DialogBox.block(); } else { return false; }" <?php endif; ?> >
<input type="hidden" name="time_start" id="time_start">
<input type="hidden" name="view" value="<?php echo $this->_tpl_vars['view']; ?>
">
<input type="hidden" name="hour" value="<?php echo $this->_tpl_vars['hour']; ?>
">
<input type="hidden" name="day" value="<?php echo $this->_tpl_vars['day']; ?>
">
<input type="hidden" name="month" value="<?php echo $this->_tpl_vars['month']; ?>
">
<input type="hidden" name="year" value="<?php echo $this->_tpl_vars['year']; ?>
">
<input type="hidden" name="viewOption" value="<?php echo $this->_tpl_vars['viewOption']; ?>
">
<input type="hidden" name="subtab" value="<?php echo $this->_tpl_vars['subtab']; ?>
">
<input type="hidden" name="maintab" value="<?php echo $this->_tpl_vars['maintab']; ?>
">
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
        <td>
                <table cellpadding="0" cellspacing="5" border="0">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'EditViewHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </table>
<table  border="0" cellpadding="5" cellspacing="0" width="100%" >
<tr>
        <td class="lvtHeaderText" style="border-bottom:1px dotted #cccccc">

                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr><td>
		
				<?php if ($this->_tpl_vars['OP_MODE'] == 'edit_view'): ?>   
					<span class="lvtHeaderText"><font color="purple">[ <?php echo $this->_tpl_vars['ID']; ?>
 ] </font><?php echo $this->_tpl_vars['NAME']; ?>
 - <?php echo $this->_tpl_vars['APP']['LBL_EDITING']; ?>
 <?php echo $this->_tpl_vars['SINGLE_MOD']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</span> <br>
					<span class="small"><?php echo $this->_tpl_vars['UPDATEINFO']; ?>
	 </span> 
				<?php endif; ?>
				<?php if ($this->_tpl_vars['OP_MODE'] == 'create_view'): ?>
					<?php if ($this->_tpl_vars['DUPLICATE'] != 'true'): ?>
					<span class="lvtHeaderText"><?php echo $this->_tpl_vars['APP']['LBL_CREATING']; ?>
 <?php echo $this->_tpl_vars['SINGLE_MOD']; ?>
</span> <br>
					<?php else: ?>
					<span class="lvtHeaderText"><?php echo $this->_tpl_vars['APP']['LBL_DUPLICATING']; ?>
 "<?php echo $this->_tpl_vars['NAME']; ?>
"</span> <br>
					<?php endif; ?>
				<?php endif; ?>
			</td></tr>
		</table>
        </td>
</tr>

<tr><td>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
                <td valign=top align=left >
                           <table border=0 cellspacing=0 cellpadding=0 width=100%>
                                <tr>
					<td align=left>
					<!-- content cache -->

					<table border=0 cellspacing=0 cellpadding=0 width=100%>
					  <tr>
					     <td style="padding:10px">
						     <!-- General details -->
						     <table border=0 cellspacing=0 cellpadding=0 width=100% >
						     <tr>
							<td  colspan=4 style="padding:5px">
								<div align="center">
								<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';"  type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
								<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
								</div>
							</td>
						     </tr>
						     </table>
						     <!-- included to handle the edit fields based on ui types -->
						     <?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['data']):
?>
							     <?php if ($this->_tpl_vars['header'] != $this->_tpl_vars['APP']['LBL_CUSTOM_INFORMATION']): ?>
						     <table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
						     <tr>
							<td colspan=4 class="tableHeading">
								<b><?php echo $this->_tpl_vars['header']; ?>
</b>
							</td>
						     </tr>
						     </table>
							     <?php endif; ?>
						     <?php endforeach; endif; unset($_from); ?>
						     <?php if ($this->_tpl_vars['ACTIVITY_MODE'] != 'Task'): ?>
							<input type="hidden" name="time_end" id="time_end">
							<input type="hidden" name="followup_due_date" id="followup_due_date">
							<input type="hidden" name="followup_time_start" id="followup_time_start">
                                                        <input type="hidden" name="followup_time_end" id="followup_time_end">
							<input type=hidden name="inviteesid" id="inviteesid" value="">
							<input type="hidden" name="duration_hours" value="0">
							<input type="hidden" name="duration_minutes" value="0">
							<input type="hidden" name="dateformat" value="<?php echo $this->_tpl_vars['DATEFORMAT']; ?>
">
						     <table border=0 cellspacing=0 cellpadding=5 width=100% >
							<?php if ($this->_tpl_vars['LABEL']['activitytype'] != ''): ?>
							<tr>
								<td class="cellLabel" nowrap  width=20% align="right"><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTTYPE']; ?>
</b></td>
								<td class="cellInfo" width=80% align="left">
									<table>
										<tr>
<!--										<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['activitytype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tyeparrkey'] => $this->_tpl_vars['typearr']):
?>
                                                                                <?php if ($this->_tpl_vars['typearr'][2] == 'selected' && $this->_tpl_vars['typearr'][1] == 'Call'): ?>
                                                                                        <?php $this->assign('meetcheck', ''); ?>
                                                                                        <?php $this->assign('callcheck', 'checked'); ?>
                                                                                <?php elseif ($this->_tpl_vars['typearr'][2] == 'selected' && $this->_tpl_vars['typearr'][1] == 'Meeting'): ?>
                                                                                        <?php $this->assign('meetcheck', 'checked'); ?>
                                                                                        <?php $this->assign('callcheck', ''); ?>
                                                                                <?php else: ?>
																						<?php $this->assign('meetcheck', ''); ?>
                                                                                        <?php $this->assign('callcheck', 'checked'); ?>
                                                                                <?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?>-->
	                                    <select name="activitytype" class="small">
											<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['activitytype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
											<?php endforeach; endif; unset($_from); ?>
									   </select>
										</tr>
									</table>
								</td>
							</tr>
							<?php endif; ?>
							<tr>
								<td class="cellLabel" nowrap align="right"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['subject']; ?>
</font><?php echo $this->_tpl_vars['MOD']['LBL_EVENTNAME']; ?>
</b></td>
								<td class="cellInfo" align="left"><input name="subject" type="text" class="textbox" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['subject']; ?>
" style="width:50%">&nbsp;&nbsp;&nbsp;
								<?php if ($this->_tpl_vars['LABEL']['visibility'] != ''): ?>
								<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['visibility']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
                                                                        <?php if ($this->_tpl_vars['arr'][1] == 'Public' && $this->_tpl_vars['arr'][2] == 'selected'): ?>
                                                                                <?php $this->assign('visiblecheck', 'checked'); ?>
                                                                        <?php else: ?>
                                                                                <?php $this->assign('visiblecheck', ""); ?>
                                                                        <?php endif; ?>
                                                                        <?php endforeach; endif; unset($_from); ?>
                                                                        <input name="visibility" value="Public" type="checkbox" <?php echo $this->_tpl_vars['visiblecheck']; ?>
><?php echo $this->_tpl_vars['MOD']['LBL_PUBLIC']; ?>

								<?php endif; ?>
								</td>
							</tr>
							<?php if ($this->_tpl_vars['LABEL']['description'] != ''): ?>
							<tr>
                        					<td class="cellLabel" valign="top" nowrap align="right"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['description']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['description']; ?>
</b></td> 
								<td class="cellInfo" align="left"><textarea style="width:100%; height : 60px;" name="description"><?php echo $this->_tpl_vars['ACTIVITYDATA']['description']; ?>
</textarea></td>
                					</tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['LABEL']['location'] != ''): ?>
							<tr>
			                     <td class="cellLabel" align="right" valign="top"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['location']; ?>
</font><?php echo $this->_tpl_vars['MOD']['LBL_APP_LOCATION']; ?>
</b></td>
								<td class="cellInfo" align="left"><input name="location" type="text" class="textbox" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['location']; ?>
" style="width:50%">
							</tr>
							<?php endif; ?>

							<tr>
								<td colspan=2 width=80% align="center">
								<table border=0 cellspacing=0 cellpadding=3 width=80%>
									<tr>
										 <td ><?php if ($this->_tpl_vars['LABEL']['eventstatus'] != ''): ?><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['eventstatus']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['eventstatus']; ?>
</b><?php endif; ?></td>                                        
                                                                                <td ><?php if ($this->_tpl_vars['LABEL']['assigned_user_id'] != ''): ?><b>
											<?php echo $this->_tpl_vars['LABEL']['assigned_user_id']; ?>
</b>
											<?php endif; ?></td>
									</tr>
									<tr>
										<td valign=top>
										<?php if ($this->_tpl_vars['LABEL']['eventstatus'] != ''): ?>
                                                                                <select name="eventstatus" id="eventstatus" class=small onChange = "getSelectedStatus();" >
                                                                                        <?php $_from = $this->_tpl_vars['ACTIVITYDATA']['eventstatus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
										<?php endif; ?>
                                                                        	</td>
										<td valign=top rowspan=2>
											<?php if ($this->_tpl_vars['ACTIVITYDATA']['assigned_user_id'] != ''): ?>
											<?php $this->assign('check', 1); ?>
                                        						<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['assigned_user_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
                                        						<input type="radio" name="assigntype" <?php echo $this->_tpl_vars['select_user']; ?>
 value="U" onclick="toggleAssignType(this.value)">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>

                                        						<?php if ($this->_tpl_vars['secondvalue']['assigned_user_id'] != ''): ?>
                                                					<input type="radio" name="assigntype" <?php echo $this->_tpl_vars['select_group']; ?>
 value="T" onclick="toggleAssignType(this.value)">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>

                                        						<?php endif; ?>
											<span id="assign_user" style="<?php echo $this->_tpl_vars['style_user']; ?>
">
                                     				           			<select name="assigned_user_id">
                                                        					<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['assigned_user_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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

                                        						<?php if ($this->_tpl_vars['secondvalue']['assigned_user_id'] != ''): ?>
                                                					<span id="assign_team" style="<?php echo $this->_tpl_vars['style_group']; ?>
">
                                                        					<select name="assigned_group_id" >';
                                                                				<?php $_from = $this->_tpl_vars['secondvalue']['assigned_user_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
											<?php else: ?>
											<input name="assigned_user_id" value="<?php echo $this->_tpl_vars['CURRENTUSERID']; ?>
" type="hidden">
											<?php endif; ?>
											<br><?php if ($this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
												<?php if ($this->_tpl_vars['ACTIVITYDATA']['sendnotification'] == 1): ?>

												<input type="checkbox" name="sendnotification" checked>&nbsp;<?php echo $this->_tpl_vars['LABEL']['sendnotification']; ?>

												<?php else: ?>
												<input type="checkbox" name="sendnotification" >&nbsp;<?php echo $this->_tpl_vars['LABEL']['sendnotification']; ?>

												<?php endif; ?>
											<?php endif; ?>
										</td>
									</tr>
									<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != ''): ?>
									<tr>
										<td valign=top><b><?php echo $this->_tpl_vars['LABEL']['taskpriority']; ?>
</b>
										<br>
										<select name="taskpriority" id="taskpriority">
                                                                                        <?php $_from = $this->_tpl_vars['ACTIVITYDATA']['taskpriority']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
										</td> 
										
									</tr>
									<?php endif; ?>
								</table>
							</td></tr>
						     </table>
						     <hr noshade size=1>
						     <table border=0 id="date_table" cellspacing=0 cellpadding=5 width=100% align=center bgcolor="#FFFFFF">
							<tr>
								<td >
									<table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
									<tr><td width=50% id="date_table_firsttd" valign=top style="border-right:1px solid #dddddd">
										<table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
											<tr><td colspan=3 ><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTSTAT']; ?>
</b></td></tr>
											<tr><td colspan=3><?php echo $this->_tpl_vars['STARTHOUR']; ?>
</td></tr>
											<tr><td>
												<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['date_start']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
                                                                                                        <?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
                                                                                                        <?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
	                                                                                        <?php endforeach; endif; unset($_from); ?>
                                                                                                <input type="text" name="date_start" id="jscal_field_date_start" class="textbox" style="width:90px" onChange="dochange('jscal_field_date_start','jscal_field_due_date');" value="<?php echo $this->_tpl_vars['date_val']; ?>
"></td><td width=100%><img border=0 src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" id="jscal_trigger_date_start">
													<?php $_from = $this->_tpl_vars['secondvalue']['date_start']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_fmt'] => $this->_tpl_vars['date_str']):
?>
													<?php $this->assign('date_vl', ($this->_tpl_vars['date_fmt'])); ?>
													<?php endforeach; endif; unset($_from); ?>
													<script type="text/javascript">
														Calendar.setup ({
														inputField : "jscal_field_date_start", ifFormat : "<?php echo $this->_tpl_vars['date_vl']; ?>
", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
														})
													</script>
											</td></tr>
										</table></td>
										<td width=50% valign=top id="date_table_secondtd">
											<table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
												<tr><td colspan=3><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTEDAT']; ?>
</b></td></tr>
												<tr><td colspan=3><?php echo $this->_tpl_vars['ENDHOUR']; ?>

												</td></tr>
												<tr><td>
													<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['due_date']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
													<?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
													<?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
													<?php endforeach; endif; unset($_from); ?>
													<input type="text" name="due_date" id="jscal_field_due_date" class="textbox" style="width:90px" value="<?php echo $this->_tpl_vars['date_val']; ?>
"></td><td width=100%><img border=0 src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" id="jscal_trigger_due_date">
													<?php $_from = $this->_tpl_vars['secondvalue']['due_date']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_fmt'] => $this->_tpl_vars['date_str']):
?>
													<?php $this->assign('date_vl', ($this->_tpl_vars['date_fmt'])); ?>
                                                                                                        <?php endforeach; endif; unset($_from); ?>
													<script type="text/javascript">
														Calendar.setup ({
														inputField : "jscal_field_due_date", ifFormat : "<?php echo $this->_tpl_vars['date_vl']; ?>
", showsTime : false, button : "jscal_trigger_due_date", singleClick : true, step : 1
														})
													</script>
												</td></tr>
											</table>
										</td>
										<td width=33% valign=top style="display:none;border-left:1px solid #dddddd" id="date_table_thirdtd">
                                                                                        <table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
                                                                                                <tr><td colspan=3><b><input type="checkbox" name="followup"><b><?php echo $this->_tpl_vars['MOD']['LBL_HOLDFOLLOWUP']; ?>
</b></td></tr>
                                                                                                <tr><td colspan=3><?php echo $this->_tpl_vars['FOLLOWUP']; ?>
</td></tr>
                                                                                                <tr><td>
                                                                                                        <?php $_from = $this->_tpl_vars['ACTIVITYDATA']['due_date']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
                                                                                                        <?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
                                                                                                        <?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
                                                                                                        <?php endforeach; endif; unset($_from); ?>
                                                                                                        <input type="text" name="followup_date" id="jscal_field_followup_date" class="textbox" style="width:90px" value="<?php echo $this->_tpl_vars['date_val']; ?>
"></td><td width=100%><img border=0 src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" id="jscal_trigger_followup_date">
                                                                                                        <?php $_from = $this->_tpl_vars['secondvalue']['due_date']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_fmt'] => $this->_tpl_vars['date_str']):
?>
                                                                                                        <?php $this->assign('date_vl', ($this->_tpl_vars['date_fmt'])); ?>
                                                                                                        <?php endforeach; endif; unset($_from); ?>
													<script type="text/javascript">
                                                                                                        Calendar.setup ({
                                                                                                                inputField : "jscal_field_followup_date", ifFormat : "<?php echo $this->_tpl_vars['date_vl']; ?>
", showsTime : false, button : "jscal_trigger_followup_date", singleClick : true, step : 1
                                                                                                                })
                                                                                                        </script>
                                                                                                </td></tr>
                                                                                        </table>
                                                                                </td>
									</tr>
								</table></td>
							</tr>
						     </table>

						     <?php if (count($this->_tpl_vars['CUSTOM_FIELDS_DATA']) > 0): ?>
	                             <table border=0 cellspacing=0 cellpadding=5 width=100% >
	                             	<tr><?php echo '<td colspan=4 class="tableHeading"><b>'; ?><?php echo $this->_tpl_vars['APP']['LBL_CUSTOM_INFORMATION']; ?><?php echo '</b></td>'; ?>

						          	</tr>
						          	<tr>
						          		<?php $_from = $this->_tpl_vars['CUSTOM_FIELDS_DATA']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['maindata']):
?>
						          			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "EditViewUI.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
											<?php if (( $this->_tpl_vars['index']+1 ) % 2 == 0): ?>
												</tr><tr>
											<?php endif; ?>
							            <?php endforeach; endif; unset($_from); ?>
							        <?php if (( $this->_tpl_vars['index']+1 ) % 2 != 0): ?>
							        	<td width="20%"></td><td width="30%"></td>
							        <?php endif; ?>
						            </tr>
	                             </table>   
                             <?php endif; ?>
						     <br>
						     <table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
							<tr><td>
								<table border=0 cellspacing=0 cellpadding=3 width=100%>
									<tr>
										<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
										<td id="cellTabInvite" class="dvtSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','on');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','off');ghide('addEventAlarmUI');gshow('addEventInviteUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_INVITE']; ?>
</a></td>
										<td class="dvtTabCache" style="width:10px">&nbsp;</td>
										<?php if ($this->_tpl_vars['LABEL']['reminder_time'] != ''): ?>
										<td id="cellTabAlarm" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','on');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','off');gshow('addEventAlarmUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventInviteUI');ghide('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_REMINDER']; ?>
</a></td>
										<?php endif; ?>
										<td class="dvtTabCache" style="width:10px">&nbsp;</td>
										<?php if ($this->_tpl_vars['LABEL']['recurringtype'] != ''): ?>
										<td id="cellTabRepeat" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','on');switchClass('cellTabRelatedto','off');ghide('addEventAlarmUI');ghide('addEventInviteUI');gshow('addEventRepeatUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_REPEAT']; ?>
</a></td>
										<?php endif; ?>
										<td class="dvtTabCache" style="width:10px">&nbsp;</td>
										<td id="cellTabRelatedto" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','on');ghide('addEventAlarmUI');ghide('addEventInviteUI');gshow('addEventRelatedtoUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventRepeatUI');"><?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
</a></td>
										<td class="dvtTabCache" style="width:100%">&nbsp;</td>
									</tr>
								</table>
							</td></tr>
							<tr>
								<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px;height:120px">
								<!-- Invite UI -->
									<DIV id="addEventInviteUI" style="display:block;width:100%">
									<table border=0 cellspacing=0 cellpadding=2 width=100%>
										<tr>
											<td valign=top> 
												<table border=0 cellspacing=0 cellpadding=2 width=100%>
													<tr><td colspan=3>
														<ul style="padding-left:20px">
														<li><?php echo $this->_tpl_vars['MOD']['LBL_INVITE_INST1']; ?>

														<li><?php echo $this->_tpl_vars['MOD']['LBL_INVITE_INST2']; ?>

														</ul>
													</td></tr>
													<tr>
														<td><b><?php echo $this->_tpl_vars['MOD']['LBL_AVL_USERS']; ?>
</b></td>
														<td>&nbsp;</td>
														<td><b><?php echo $this->_tpl_vars['MOD']['LBL_SEL_USERS']; ?>
</b></td>
													</tr>
													<tr>
														<td width=40% align=center valign=top>
														<select name="availableusers" id="availableusers" class=small size=5 multiple style="height:70px;width:100%">
														<?php $_from = $this->_tpl_vars['USERSLIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['userid'] => $this->_tpl_vars['username']):
?>
														<?php if ($this->_tpl_vars['userid'] != ''): ?>
														<option value="<?php echo $this->_tpl_vars['userid']; ?>
"><?php echo $this->_tpl_vars['username']; ?>
</option>
														<?php endif; ?>
														<?php endforeach; endif; unset($_from); ?>
														</select>
														</td>
														<td width=20% align=center valign=top>
														<input type=button value="<?php echo $this->_tpl_vars['MOD']['LBL_ADD_BUTTON']; ?>
 >>" class="crm button small save" style="width:100%"  onClick="incUser('availableusers','selectedusers')"><br>
														<input type=button value="<< <?php echo $this->_tpl_vars['MOD']['LBL_RMV_BUTTON']; ?>
 " class="crm button small cancel" style="width:100%" onClick="rmvUser('selectedusers')">
														</td>
														<td width=40% align=center valign=top>
														<select name="selectedusers" id="selectedusers" class=small size=5 multiple style="height:70px;width:100%">
														<?php $_from = $this->_tpl_vars['INVITEDUSERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['userid'] => $this->_tpl_vars['username']):
?>
														<?php if ($this->_tpl_vars['userid'] != ''): ?>
														<option value="<?php echo $this->_tpl_vars['userid']; ?>
"><?php echo $this->_tpl_vars['username']; ?>
</option>
                                                                                                                <?php endif; ?>
                                                                                                                <?php endforeach; endif; unset($_from); ?>
														</select>
														<div align=left> <?php echo $this->_tpl_vars['MOD']['LBL_SELUSR_INFO']; ?>

														</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									</DIV>
									<!-- Reminder UI -->	
									<DIV id="addEventAlarmUI" style="display:none;width:100%">
									<?php if ($this->_tpl_vars['LABEL']['reminder_time'] != ''): ?>
										<table>
											<?php $this->assign('secondval', $this->_tpl_vars['secondvalue']['reminder_time']); ?>
											<?php $this->assign('check', $this->_tpl_vars['secondval'][0]); ?>
											<?php $this->assign('yes_val', $this->_tpl_vars['secondval'][1]); ?>
											<?php $this->assign('no_val', $this->_tpl_vars['secondval'][2]); ?>
											
											<tr><td><?php echo $this->_tpl_vars['LABEL']['reminder_time']; ?>
</td><td>

										<?php if ($this->_tpl_vars['check'] == 'CHECKED'): ?>
											<?php $this->assign('reminstyle', 'style="display:block;width:100%"'); ?>
											<input type="radio" name="set_reminder" value="Yes" <?php echo $this->_tpl_vars['check']; ?>
 onClick="showBlock('reminderOptions')">&nbsp;<?php echo $this->_tpl_vars['yes_val']; ?>
&nbsp;
											<input type="radio" name="set_reminder" value="No" onClick="fnhide('reminderOptions')">&nbsp;<?php echo $this->_tpl_vars['no_val']; ?>
&nbsp;
											
										<?php else: ?>
											<?php $this->assign('reminstyle', 'style="display:none;width:100%"'); ?>
											<input type="radio" name="set_reminder" value="Yes" onClick="showBlock('reminderOptions')">&nbsp;<?php echo $this->_tpl_vars['yes_val']; ?>
&nbsp;
											<input type="radio" name="set_reminder" value="No" checked onClick="fnhide('reminderOptions')">&nbsp;<?php echo $this->_tpl_vars['no_val']; ?>
&nbsp;
											
										<?php endif; ?>
											</td></tr>
										</table>
										<DIV id="reminderOptions" <?php echo $this->_tpl_vars['reminstyle']; ?>
>
											<table border=0 cellspacing=0 cellpadding=2  width=100%>
												<tr>
													<td nowrap align=right width=20% valign=top><b><?php echo $this->_tpl_vars['MOD']['LBL_RMD_ON']; ?>
 : </b></td>
													<td width=80%>
														<table border=0>
														<tr>
															<td colspan=2>
															<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['reminder_time']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val_arr']):
?>
															<?php $this->assign('start', ($this->_tpl_vars['val_arr'][0])); ?>
															<?php $this->assign('end', ($this->_tpl_vars['val_arr'][1])); ?>
															<?php $this->assign('sendname', ($this->_tpl_vars['val_arr'][2])); ?>
															<?php $this->assign('disp_text', ($this->_tpl_vars['val_arr'][3])); ?>
															<?php $this->assign('sel_val', ($this->_tpl_vars['val_arr'][4])); ?>
															<select name="<?php echo $this->_tpl_vars['sendname']; ?>
">
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
															<OPTION VALUE="<?php echo $this->_sections['reminder']['index']; ?>
" SELECTED><?php echo $this->_sections['reminder']['index']; ?>
</OPTION>
															<?php else: ?>
															<OPTION VALUE="<?php echo $this->_sections['reminder']['index']; ?>
" ><?php echo $this->_sections['reminder']['index']; ?>
</OPTION>
															<?php endif; ?>
															<!--OPTION VALUE="<?php echo $this->_sections['reminder']['index']; ?>
" "<?php echo $this->_tpl_vars['sel_value']; ?>
"><?php echo $this->_sections['reminder']['index']; ?>
</OPTION-->
															<?php endfor; endif; ?>
															</select>
															&nbsp;<?php echo $this->_tpl_vars['disp_text']; ?>

															<?php endforeach; endif; unset($_from); ?>
															</td>
														</tr>
														</table>
													</td>
												</tr>
												<!--This is now required as of now, as we aree not allowing to change the email id
	                                        and it is showing logged in User's email id, instead of Assigned to user's email id
															
												<tr>
													<td nowrap align=right>
														<?php echo $this->_tpl_vars['MOD']['LBL_SDRMD']; ?>

													</td>
													<td >
														<input type=text name="toemail" readonly="readonly" class=textbox style="width:90%" value="<?php echo $this->_tpl_vars['USEREMAILID']; ?>
">
													</td>
												</tr> -->
											</table>
										</DIV>
									<?php endif; ?>
									</DIV>
									<!-- Repeat UI -->
									<div id="addEventRepeatUI" style="display:none;width:100%">
									<?php if ($this->_tpl_vars['LABEL']['recurringtype'] != ''): ?>
									<table border=0 cellspacing=0 cellpadding=2  width=100%>
										<tr>
											<td nowrap align=right width=20% valign=top>
												<strong><?php echo $this->_tpl_vars['MOD']['LBL_REPEAT']; ?>
</strong>
											</td>
											<td nowrap width=80% valign=top>
												<table border=0 cellspacing=0 cellpadding=0>
												<tr>
							
													<td width=20>
													<?php if ($this->_tpl_vars['ACTIVITYDATA']['recurringcheck'] == 'Yes'): ?>
														<?php $this->assign('rptstyle', 'style="display:block"'); ?>
														<?php if ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Daily'): ?>
															<?php $this->assign('rptmonthstyle', 'style="display:none"'); ?>
															<?php $this->assign('rptweekstyle', 'style="display:none"'); ?>
														<?php elseif ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Weekly'): ?>
															<?php $this->assign('rptmonthstyle', 'style="display:none"'); ?>
															<?php $this->assign('rptweekstyle', 'style="display:block"'); ?>
														<?php elseif ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Monthly'): ?>
															<?php $this->assign('rptmonthstyle', 'style="display:block"'); ?>
															<?php $this->assign('rptweekstyle', 'style="display:none"'); ?>
														<?php elseif ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Yearly'): ?>
															<?php $this->assign('rptmonthstyle', 'style="display:none"'); ?>
															<?php $this->assign('rptweekstyle', 'style="display:none"'); ?>
														<?php endif; ?>
													<input type="checkbox" name="recurringcheck" onClick="showhide('repeatOptions')" checked>
													<?php else: ?>
														<?php $this->assign('rptstyle', 'style="display:none"'); ?>
														<?php $this->assign('rptmonthstyle', 'style="display:none"'); ?>
														<?php $this->assign('rptweekstyle', 'style="display:none"'); ?>
													<input type="checkbox" name="recurringcheck" onClick="showhide('repeatOptions')">
													<?php endif; ?>
													</td>
													<td><?php echo $this->_tpl_vars['MOD']['LBL_ENABLE_REPEAT']; ?>
<td>
												</tr>
												<tr>
													<td colspan=2>
													<div id="repeatOptions" <?php echo $this->_tpl_vars['rptstyle']; ?>
>
													<table border=0 cellspacing=0 cellpadding=2>
													<tr>
													<td><?php echo $this->_tpl_vars['MOD']['LBL_REPEAT_ONCE']; ?>
</td>
													<td>
													<select name="repeat_frequency">
                                                                                                                <?php unset($this->_sections['repeat']);
$this->_sections['repeat']['name'] = 'repeat';
$this->_sections['repeat']['loop'] = is_array($_loop=15) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['repeat']['start'] = (int)1;
$this->_sections['repeat']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['repeat']['show'] = true;
$this->_sections['repeat']['max'] = $this->_sections['repeat']['loop'];
if ($this->_sections['repeat']['start'] < 0)
    $this->_sections['repeat']['start'] = max($this->_sections['repeat']['step'] > 0 ? 0 : -1, $this->_sections['repeat']['loop'] + $this->_sections['repeat']['start']);
else
    $this->_sections['repeat']['start'] = min($this->_sections['repeat']['start'], $this->_sections['repeat']['step'] > 0 ? $this->_sections['repeat']['loop'] : $this->_sections['repeat']['loop']-1);
if ($this->_sections['repeat']['show']) {
    $this->_sections['repeat']['total'] = min(ceil(($this->_sections['repeat']['step'] > 0 ? $this->_sections['repeat']['loop'] - $this->_sections['repeat']['start'] : $this->_sections['repeat']['start']+1)/abs($this->_sections['repeat']['step'])), $this->_sections['repeat']['max']);
    if ($this->_sections['repeat']['total'] == 0)
        $this->_sections['repeat']['show'] = false;
} else
    $this->_sections['repeat']['total'] = 0;
if ($this->_sections['repeat']['show']):

            for ($this->_sections['repeat']['index'] = $this->_sections['repeat']['start'], $this->_sections['repeat']['iteration'] = 1;
                 $this->_sections['repeat']['iteration'] <= $this->_sections['repeat']['total'];
                 $this->_sections['repeat']['index'] += $this->_sections['repeat']['step'], $this->_sections['repeat']['iteration']++):
$this->_sections['repeat']['rownum'] = $this->_sections['repeat']['iteration'];
$this->_sections['repeat']['index_prev'] = $this->_sections['repeat']['index'] - $this->_sections['repeat']['step'];
$this->_sections['repeat']['index_next'] = $this->_sections['repeat']['index'] + $this->_sections['repeat']['step'];
$this->_sections['repeat']['first']      = ($this->_sections['repeat']['iteration'] == 1);
$this->_sections['repeat']['last']       = ($this->_sections['repeat']['iteration'] == $this->_sections['repeat']['total']);
?>
                                                                                                                <?php if ($this->_sections['repeat']['iteration'] == $this->_tpl_vars['ACTIVITYDATA']['repeat_frequency']): ?>
                                                                                                                        <?php $this->assign('test', 'selected'); ?>
                                                                                                                <?php else: ?>                                                                                                                             <?php $this->assign('test', ""); ?>                                                                                                                                                                                                                  <?php endif; ?>
                                                                                                                <option "<?php echo $this->_tpl_vars['test']; ?>
" value="<?php echo $this->_sections['repeat']['iteration']; ?>
"><?php echo $this->_sections['repeat']['iteration']; ?>
</option>
                                                                                                                <?php endfor; endif; ?>
                                                                                                        </select>
													</td>
													<td><select name="recurringtype" onChange="rptoptDisp(this)">
													<option value="Daily" <?php if ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Daily'): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAYS']; ?>
</option>
													<option value="Weekly" <?php if ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Weekly'): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_WEEKS']; ?>
</option>
												<option value="Monthly" <?php if ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Monthly'): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_MONTHS']; ?>
</option>
													<option value="Yearly" <?php if ($this->_tpl_vars['ACTIVITYDATA']['eventrecurringtype'] == 'Yearly'): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_YEAR']; ?>
</option>
													</select>
													<!-- Repeat Feature Enhanced -->
													<b><?php echo $this->_tpl_vars['MOD']['LBL_UNTIL']; ?>
</b> <input type="text" name="calendar_repeat_limit_date" id="calendar_repeat_limit_date" class="textbox" style="width:90px" value="" ></td><td align="left"><img border=0 src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
..." title="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
..." id="jscal_trigger_calendar_repeat_limit_date">
													<?php echo '
														<script type="text/javascript">
														Calendar.setup ({inputField : "calendar_repeat_limit_date", ifFormat : '; ?>

"<?php echo $this->_tpl_vars['REPEAT_LIMIT_DATEFORMAT']; ?>
"
<?php echo ', showsTime : false, button : "jscal_trigger_calendar_repeat_limit_date", singleClick : true, step : 1})</script>
													'; ?>

													<!-- END -->
													</td>
												</tr>
												</table>
												<div id="repeatWeekUI" <?php echo $this->_tpl_vars['rptweekstyle']; ?>
>
												<table border=0 cellspacing=0 cellpadding=2>
												<tr>
													<td><input name="sun_flag" value="sunday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week0']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_SUN']; ?>
</td>
													<td><input name="mon_flag" value="monday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week1']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_MON']; ?>
</td>
													<td><input name="tue_flag" value="tuesday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week2']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_TUE']; ?>
</td>
													<td><input name="wed_flag" value="wednesday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week3']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_WED']; ?>
</td>
													<td><input name="thu_flag" value="thursday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week4']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_THU']; ?>
</td>
													<td><input name="fri_flag" value="friday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week5']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_FRI']; ?>
</td>
													<td><input name="sat_flag" value="saturday" <?php echo $this->_tpl_vars['ACTIVITYDATA']['week6']; ?>
 type="checkbox"></td><td><?php echo $this->_tpl_vars['MOD']['LBL_SM_SAT']; ?>
</td>
												</tr>
												</table>
												</div>
	
												<div id="repeatMonthUI" <?php echo $this->_tpl_vars['rptmonthstyle']; ?>
>
												<table border=0 cellspacing=0 cellpadding=2>
												<tr>
													<td>
														<table border=0 cellspacing=0 cellpadding=2>
														<tr>
														<td><input type="radio" checked name="repeatMonth" <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth'] == 'date'): ?> checked <?php endif; ?> value="date"></td><td>on</td><td><input type="text" class=textbox style="width:20px" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['repeatMonth_date']; ?>
" name="repeatMonth_date" ></td><td>day of the month</td>
														</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td>
														<table border=0 cellspacing=0 cellpadding=2>
														<tr><td>
														<input type="radio" name="repeatMonth" <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth'] == 'day'): ?> checked <?php endif; ?> value="day"></td>
														<td>on</td>
														<td>
														<select name="repeatMonth_daytype">
															<option value="first" <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_daytype'] == 'first'): ?> selected <?php endif; ?>>First</option>
															<option value="last" <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_daytype'] == 'last'): ?> selected <?php endif; ?>>Last</option>
														</select>
														</td>
														<td>
														<select name="repeatMonth_day">
															<option value=1 <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_day'] == 1): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAY1']; ?>
</option>
															<option value=2 <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_day'] == 2): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAY2']; ?>
</option>
															<option value=3 <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_day'] == 3): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAY3']; ?>
</option>
															<option value=4 <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_day'] == 4): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAY4']; ?>
</option>
															<option value=5 <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_day'] == 5): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAY5']; ?>
</option>
															<option value=6 <?php if ($this->_tpl_vars['ACTIVITYDATA']['repeatMonth_day'] == 6): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['MOD']['LBL_DAY6']; ?>
</option>
														</select>
														</td>
														</tr>
														</table>
													</td>
												</tr>
												</table>
												</div>
								
											</div>
										</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<?php endif; ?>
						</div>
						<div id="addEventRelatedtoUI" style="display:none;width:100%">
						<table width="100%" cellpadding="5" cellspacing="0" border="0">
							<?php if ($this->_tpl_vars['LABEL']['parent_id'] != ''): ?>	
							<tr>
								<!--td width="10%"><b><?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
</b></td-->
								<td width="10%"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['relatedto']; ?>
</font><?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
</b></td>
								<td>
									<input name="parent_id" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']['parent_id']; ?>
">
									<input name="del_actparent_rel" type="hidden" >
									<select name="parent_type" class="small" id="parent_type" onChange="document.EditView.parent_name.value='';document.EditView.parent_id.value=''">
									<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['LABEL']['parent_id']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
										<option value="<?php echo $this->_tpl_vars['fldlabel_combo']['parent_id'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel']['parent_id'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['LABEL']['parent_id'][$this->_sections['combo']['index']]; ?>
</option>
									<?php endfor; endif; ?>
                                             				</select>
								</td>
								<td>
									<div id="eventrelatedto" align="left">
										<input name="parent_name" readonly type="text" class="calTxt small" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['parent_id']; ?>
">
										<input type="button" name="selectparent" class="crmButton small edit" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
" onclick="return window.open('index.php?module='+document.EditView.parent_type.value+'&action=Popup','test','width=640,height=602,resizable=0,scrollbars=0,top=150,left=200');">
										<input type='button' value='<?php echo $this->_tpl_vars['APP']['LNK_DELETE']; ?>
' class="crmButton small edit" onclick="document.EditView.del_actparent_rel.value=document.EditView.parent_id.value;document.EditView.parent_id.value='';document.EditView.parent_name.value='';">
									</div>
								</td>
							</tr>
							<?php endif; ?>
							<tr>
								<td><b><?php echo $this->_tpl_vars['APP']['Contacts']; ?>
</b></td>
								<td colspan="2">
									<input name="contactidlist" id="contactidlist" value="<?php echo $this->_tpl_vars['CONTACTSID']; ?>
" type="hidden">
									<input name="deletecntlist" id="deletecntlist" type="hidden">
									<select name="contactlist" size=5  style="height: 100px;width: 300px"  id="parentid" class="small" multiple>
									<?php echo $this->_tpl_vars['CONTACTSNAME']; ?>
	
									</select>  	 
	
									<input type="button" onclick="selectContact('true','general',document.EditView);" class="crmButton small edit" name="selectcnt" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_CONTACT_BUTTON_LABEL']; ?>
">
									<input type='button' value='<?php echo $this->_tpl_vars['APP']['LNK_DELETE']; ?>
' class="crmButton small edit" onclick='removeActContacts();'>
							
								</td>
							</tr>
						</table>
					</div>
			</td>
		</tr>
		</table>
		<!-- Alarm, Repeat, Invite stops-->
		<?php else: ?>
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr>
                        	<td class="cellLabel" width="20%" align="right"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['subject']; ?>
</font><?php echo $this->_tpl_vars['MOD']['LBL_TODO']; ?>
</b></td>
                        	<td class="cellInfo" width="80%" align="left"><input name="subject" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['subject']; ?>
" class="textbox" style="width: 70%;" type="text"></td>
           		</tr>
			
			<tr>
				<?php if ($this->_tpl_vars['LABEL']['description'] != ''): ?>
				<td class="cellLabel" align="right"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['description']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['description']; ?>
</b></td>
				<td class="cellInfo" align="left"><textarea style="width: 90%; height: 60px;" name="description"><?php echo $this->_tpl_vars['ACTIVITYDATA']['description']; ?>
</textarea>
				<?php endif; ?>
				
			</tr>
			<tr>
		    		<td colspan="2" align="center" width="100%" style="padding:0px">
					<table border="0" cellpadding="5" cellspacing="1" width="100%">
            					<tr>
							<?php if ($this->_tpl_vars['LABEL']['taskstatus'] != ''): ?>
							<td class="cellLabel" width=33% align="left"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['taskstatus']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['taskstatus']; ?>
</b></td>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != ''): ?>
              						<td class="cellLabel" width=33% align="left"><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['taskpriority']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['taskpriority']; ?>
</b></td>
							<?php endif; ?>
              						<?php if ($this->_tpl_vars['LABEL']['assigned_user_id'] != ''): ?>
							<td class="cellLabel" width=34% align="left"><b><?php echo $this->_tpl_vars['LABEL']['assigned_user_id']; ?>
</b></td>
							<?php endif; ?>
						</tr>
						<tr>
							<?php if ($this->_tpl_vars['LABEL']['taskstatus'] != ''): ?>
							<td align="left" valign="top">
								<select name="taskstatus" id="taskstatus" class=small>
                                        			<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['taskstatus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
							</td>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != ''): ?>
							<td align="left" valign="top">
								<select name="taskpriority" id="taskpriority" class=small>
        			                                <?php $_from = $this->_tpl_vars['ACTIVITYDATA']['taskpriority']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
							</td>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['LABEL']['assigned_user_id'] != ''): ?>
							<td align="left" valign="top">
								<?php $this->assign('check', 1); ?>
                                        			<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['assigned_user_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
				                                <input type="radio" name="assigntype" <?php echo $this->_tpl_vars['select_user']; ?>
 value="U" onclick="toggleAssignType(this.value)">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>

				                                <?php if ($this->_tpl_vars['secondvalue']['assigned_user_id'] != ''): ?>
                                			        <input type="radio" name="assigntype" <?php echo $this->_tpl_vars['select_group']; ?>
 value="T" onclick="toggleAssignType(this.value)">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>

                                        			<?php endif; ?>
                                        			<span id="assign_user" style="<?php echo $this->_tpl_vars['style_user']; ?>
">
                                                		<select name="assigned_user_id" class=small>
                                                        	<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['assigned_user_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
								<?php if ($this->_tpl_vars['secondvalue']['assigned_user_id'] != ''): ?>
                                                		<span id="assign_team" style="<?php echo $this->_tpl_vars['style_group']; ?>
">
                                                        		<select name="assigned_group_id" class=small>';
                                                                		<?php $_from = $this->_tpl_vars['secondvalue']['assigned_user_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
							<?php else: ?>
								<input name="assigned_user_id" value="<?php echo $this->_tpl_vars['CURRENTUSERID']; ?>
" type="hidden">
							<?php endif; ?>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="1" width="100%" align=center>
			<tr><td width=50% valign=top>
				<table border=0 cellspacing=0 cellpadding=2 width=100% align=center >
					<tr><td colspan=3  class="mailSubHeader"><b><?php echo $this->_tpl_vars['MOD']['LBL_TODODATETIME']; ?>
</b></td></tr>
					<tr><td colspan=3><?php echo $this->_tpl_vars['STARTHOUR']; ?>
</td></tr>
					<tr><td>
							<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['date_start']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
	                                        		<?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
								<?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
                                        		<?php endforeach; endif; unset($_from); ?>
							<input name="date_start" id="date_start" class="textbox" style="width: 90px;" onChange="dochange('date_start','due_date');" value="<?php echo $this->_tpl_vars['date_val']; ?>
" type="text"></td><td width=100%><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" id="jscal_trigger_date_start" align="middle" border="0">
							<?php $_from = $this->_tpl_vars['secondvalue']['date_start']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_fmt'] => $this->_tpl_vars['date_str']):
?>
								<?php $this->assign('date_vl', ($this->_tpl_vars['date_fmt'])); ?>
							<?php endforeach; endif; unset($_from); ?>				
							<script type="text/javascript">
								Calendar.setup ({
	        	                                	inputField : "date_start", ifFormat : "<?php echo $this->_tpl_vars['date_vl']; ?>
", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
							})
							</script>
						</td></tr>
					</table></td>
					<td width=50% valign="top">
                                                <table border="0" cellpadding="2" cellspacing="0" width="100%" align=center>
							<tr><td class="mailSubHeader" colspan=3><b><?php echo $this->_tpl_vars['LABEL']['due_date']; ?>
</b></td></tr>
							<tr><td>
								<?php $_from = $this->_tpl_vars['ACTIVITYDATA']['due_date']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
									<?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
									<?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
								<?php endforeach; endif; unset($_from); ?>
								<input name="due_date" id="due_date" class="textbox" style="width: 90px;" value="<?php echo $this->_tpl_vars['date_val']; ?>
" type="text"></td><td width=100%><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calendar.gif" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_SET_DATE']; ?>
" id="jscal_trigger_due_date" border="0">
								<?php $_from = $this->_tpl_vars['secondvalue']['due_date']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_fmt'] => $this->_tpl_vars['date_str']):
?>
                                                			<?php $this->assign('date_vl', ($this->_tpl_vars['date_fmt'])); ?>
                                        			<?php endforeach; endif; unset($_from); ?>
				      				<script type="text/javascript">
								Calendar.setup ({
	                                        			inputField : "due_date", ifFormat : "<?php echo $this->_tpl_vars['date_vl']; ?>
", showsTime : false, button : "jscal_trigger_due_date", singleClick : true, step : 1
					   			})
								</script>
        						</td></tr>
						</table></td>
					</tr>
				</table>

			     <?php if (count($this->_tpl_vars['CUSTOM_FIELDS_DATA']) > 0): ?>
					<br><br>
                     <table border=0 cellspacing=0 cellpadding=5 width=100% >
                     	<tr><?php echo '<td colspan=4 class="tableHeading"><b>'; ?><?php echo $this->_tpl_vars['APP']['LBL_CUSTOM_INFORMATION']; ?><?php echo '</b></td>'; ?>

			          	</tr>
			          	<tr>
			          		<?php $_from = $this->_tpl_vars['CUSTOM_FIELDS_DATA']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['maindata']):
?>
			          			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "EditViewUI.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php if (( $this->_tpl_vars['index']+1 ) % 2 == 0): ?>
									</tr><tr>
								<?php endif; ?>
				            <?php endforeach; endif; unset($_from); ?>
				        <?php if (( $this->_tpl_vars['index']+1 ) % 2 != 0): ?>
				        	<td width="20%"></td><td width="30%"></td>
				        <?php endif; ?>
			            </tr>
                     </table>   
                 <?php endif; ?>
				<br><br>
		<?php if ($this->_tpl_vars['LABEL']['sendnotification'] != '' || ( $this->_tpl_vars['LABEL']['parent_id'] != '' ) || ( $this->_tpl_vars['LABEL']['contact_id'] != '' )): ?>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%" bgcolor="#FFFFFF">
			<tr>
				<td>
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
							<?php if ($this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
                                                                <?php $this->assign('class_val', 'dvtUnSelectedCell'); ?>
								<td id="cellTabInvite" class="dvtSelectedCell" align="center" nowrap="nowrap"><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','on');switchClass('cellTabRelatedto','off');Taskshow('addTaskAlarmUI','todo',document.EditView.date_start.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value);ghide('addTaskRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_NOTIFICATION']; ?>
</a></td>
							<?php else: ?>
                                                                <?php $this->assign('class_val', 'dvtSelectedCell'); ?>
                                                        <?php endif; ?>
							<td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
							<?php if (( $this->_tpl_vars['LABEL']['parent_id'] != '' ) || ( $this->_tpl_vars['LABEL']['contact_id'] != '' )): ?>
                                                        <td id="cellTabRelatedto" class=<?php echo $this->_tpl_vars['class_val']; ?>
 align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabRelatedto','on');Taskshow('addTaskRelatedtoUI','todo',document.EditView.date_start.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value);ghide('addTaskAlarmUI');"><?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
</a></td>
							<?php endif; ?>
                                                        <td class="dvtTabCache" style="width:100%">&nbsp;</td>
						</tr>

					</table>
				</td>
			</tr>
			<tr>
				<td class="dvtContentSpace" style="padding: 10px; height: 120px;" align="left" valign="top" width="100%">
			<!-- Reminder UI -->
			<div id="addTaskAlarmUI" style="display: block; width: 100%;">
			<?php if ($this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
				<?php $this->assign('vision', 'none'); ?>
                	<table>
				<tr><td><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['sendnotification']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['sendnotification']; ?>
</td>
					<?php if ($this->_tpl_vars['ACTIVITYDATA']['sendnotification'] == 1): ?>
                                        <td>
                                                <input name="sendnotification" type="checkbox" checked>
                                        </td>
                                	<?php else: ?>
                                        <td>
                                                <input name="sendnotification" type="checkbox">
                                        </td>
                                	<?php endif; ?>
				</tr>
			</table>
			<?php else: ?>
                                <?php $this->assign('vision', 'block'); ?>
                        <?php endif; ?>
			</div>
			<div id="addTaskRelatedtoUI" style="display:<?php echo $this->_tpl_vars['vision']; ?>
;width:100%">
           		     <table width="100%" cellpadding="5" cellspacing="0" border="0">
			     <?php if ($this->_tpl_vars['LABEL']['parent_id'] != ''): ?>
                	     <tr>
                        	     <td><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['parent_id']; ?>
</font><?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
</b></td>
                                     <td>
					<input name="parent_id" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']['parent_id']; ?>
">
					<input name="del_actparent_rel" type="hidden" >
                                             <select name="parent_type" class="small" id="parent_type" onChange="document.EditView.parent_name.value='';document.EditView.parent_id.value=''">
							<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['LABEL']['parent_id']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<option value="<?php echo $this->_tpl_vars['fldlabel_combo']['parent_id'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel']['parent_id'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['LABEL']['parent_id'][$this->_sections['combo']['index']]; ?>
</option>
							<?php endfor; endif; ?>
					     </select>
                                     </td>
                                     <td>
                              	        <div id="taskrelatedto" align="left">
						<input name="parent_name" readonly type="text" class="calTxt small" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['parent_id']; ?>
">
						<input type="button" name="selectparent" class="crmButton small edit" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" onclick="return window.open('index.php?module='+document.EditView.parent_type.value+'&action=Popup','test','width=640,height=602,resizable=0,scrollbars=0,top=150,left=200');">
						<input type='button' value='<?php echo $this->_tpl_vars['APP']['LNK_DELETE']; ?>
' class="crmButton small edit" onclick="document.EditView.del_actparent_rel.value=document.EditView.parent_id.value;document.EditView.parent_id.value='';document.EditView.parent_name.value='';">
					 </div>
                                     </td>
			     </tr>
			     <?php endif; ?>
			     <?php if ($this->_tpl_vars['LABEL']['contact_id'] != ''): ?>	
			     <tr>
                                     <td><b><font color="red"><?php echo $this->_tpl_vars['TYPEOFDATA']['contact_id']; ?>
</font><?php echo $this->_tpl_vars['LABEL']['contact_id']; ?>
</b></td> 
				     <td colspan="2">
						<input name="contact_name" id = "contact_name" readonly type="text" class="calTxt" value="<?php echo $this->_tpl_vars['ACTIVITYDATA']['contact_id']; ?>
"><input name="contact_id"  type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']['contact_id']; ?>
">&nbsp;
						<input name="deletecntlist"  id="deletecntlist" type="hidden">
						<input type="button" onclick="selectContact('false','task',document.EditView);" class="crmButton small edit" name="selectcnt" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
&nbsp;<?php echo $this->_tpl_vars['APP']['SINGLE_Contacts']; ?>
">
						<input type='button' value='<?php echo $this->_tpl_vars['APP']['LNK_DELETE']; ?>
' class="crmButton small edit" onclick='document.EditView.deletecntlist.value =document.EditView.contact_id.value;document.EditView.contact_name.value = "";document.EditView.contact_id.value="";'>
				     </td>
                             </tr>
			     <?php endif; ?>
		</table>
		<?php endif; ?>
              	</div>
                </td></tr></table>

		<?php endif; ?>
			</td></tr>
			<tr>
				<td  colspan=4 style="padding:5px">
					<div align="center">
                        	        	<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; " type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
						<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
					</div>
				</td>
			</tr></table>
		</td></tr></table>
		</td></tr></table>
		</td></tr></table>
		</td></tr></table>
		</td></tr></table>
</td></tr>
<input name='search_url' id="search_url" type='hidden' value='<?php echo $this->_tpl_vars['SEARCH']; ?>
'>
</form></table>
</td></tr></table>
</td></tr></table>
</td></tr></table>
        </td></tr></table>
        </td></tr></table>
        </div>
        </td>
        <td valign=top><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
        </tr>
        </table>
<script>
<?php if ($this->_tpl_vars['ACTIVITY_MODE'] == 'Task'): ?>
	var fieldname = new Array('subject','date_start','time_start','due_date','taskstatus');
        var fieldlabel = new Array('<?php echo $this->_tpl_vars['MOD']['LBL_LIST_SUBJECT']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_START_DATE']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_TIME']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_DUE_DATE']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_STATUS']; ?>
');
        var fielddatatype = new Array('V~M','D~M~time_start','T~O','D~M~OTH~GE~date_start~Start Date & Time','V~O');
<?php else: ?>
	var fieldname = new Array('subject','date_start','time_start','due_date','eventstatus','taskpriority','sendnotification','parent_id','contact_id','reminder_time','recurringtype');
        var fieldlabel = new Array('<?php echo $this->_tpl_vars['MOD']['LBL_LIST_SUBJECT']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_START_DATE']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_TIME_START']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_DUE_DATE']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_STATUS']; ?>
','<?php echo $this->_tpl_vars['MOD']['Priority']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_SENDNOTIFICATION']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_CONTACT_NAME']; ?>
','<?php echo $this->_tpl_vars['MOD']['LBL_SENDREMINDER']; ?>
','<?php echo $this->_tpl_vars['MOD']['Recurrence']; ?>
');
        var fielddatatype = new Array('V~M','D~M','T~O','D~M~OTH~GE~date_start~Start Date','V~O','V~O','C~O','I~O','I~O','I~O','O~O');
<?php endif; ?>
</script>
<script>	
	var ProductImages=new Array();
	var count=0;

	function delRowEmt(imagename)
	{
		ProductImages[count++]=imagename;
	}

	function displaydeleted()
	{
		var imagelists='';
		for(var x = 0; x < ProductImages.length; x++)
		{
			imagelists+=ProductImages[x]+'###';
		}

		if(imagelists != '')
			document.EditView.imagelist.value=imagelists
	}

</script>