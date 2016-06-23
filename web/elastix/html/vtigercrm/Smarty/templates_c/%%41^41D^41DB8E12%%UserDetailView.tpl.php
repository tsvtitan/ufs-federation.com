<?php /* Smarty version 2.6.18, created on 2014-09-01 11:53:05
         compiled from UserDetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'UserDetailView.tpl', 25, false),array('modifier', 'getTranslatedString', 'UserDetailView.tpl', 161, false),array('modifier', 'vtlib_purify', 'UserDetailView.tpl', 275, false),)), $this); ?>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ColorPicker2.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;"><?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
</a>
</span>

<br>
<!-- Shadow table -->
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
    <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
    <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
    <br>
    <div align=center>
		<?php if ($this->_tpl_vars['CATEGORY'] == 'Settings'): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SetMenu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="padTab" align="left">
						<form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form" style="margin:0px" onsubmit="VtigerJS_DialogBox.block();">
							<input type="hidden" name="module" value="Users" style="margin:0px">
							<input type="hidden" name="record" id="userid" value="<?php echo $this->_tpl_vars['ID']; ?>
" style="margin:0px">
							<input type="hidden" name="isDuplicate" value=false style="margin:0px">
							<input type="hidden" name="action" style="margin:0px">
							<input type="hidden" name="changepassword" style="margin:0px">
							<?php if ($this->_tpl_vars['CATEGORY'] != 'Settings'): ?>
								<input type="hidden" name="modechk" value="prefview" style="margin:0px">
							<?php endif; ?>
							<input type="hidden" name="old_password" style="margin:0px">
							<input type="hidden" name="new_password" style="margin:0px">
							<input type="hidden" name="return_module" value="Users" style="margin:0px">
							<input type="hidden" name="return_action" value="ListView"  style="margin:0px">
							<input type="hidden" name="return_id" style="margin:0px">
							<input type="hidden" name="forumDisplay" style="margin:0px">
							<input type="hidden" name="hour_format" id="hour_format" value="<?php echo $this->_tpl_vars['HOUR_FORMAT']; ?>
" style="margin:0px">
							<input type="hidden" name="start_hour" id="start_hour" value="<?php echo $this->_tpl_vars['START_HOUR']; ?>
" style="margin:0px">
							<input type="hidden" name="form_token" value="<?php echo $this->_tpl_vars['FORM_TOKEN']; ?>
">
							<?php if ($this->_tpl_vars['CATEGORY'] == 'Settings'): ?>
							<input type="hidden" name="parenttab" value="<?php echo $this->_tpl_vars['PARENTTAB']; ?>
" style="margin:0px">
							<?php endif; ?>	
							<table width="100%" border="0" cellpadding="0" cellspacing="0" >
							<tr>
								<td colspan=2>
									<!-- Heading and Icons -->
									<table width="100%" cellpadding="5" cellspacing="0" border="0" class="settingsSelUITopLine">
									<tr>
										<td width=50 rowspan="2"><img src="<?php echo vtiger_imageurl('ico-users.gif', $this->_tpl_vars['THEME']); ?>
" align="absmiddle"></td>	
										<td>
											<?php if ($this->_tpl_vars['CATEGORY'] == 'Settings'): ?>
											<span class="heading2">
											<b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
 </a> &gt; <a href="index.php?module=Administration&action=index&parenttab=Settings"> <?php echo $this->_tpl_vars['MOD']['LBL_USERS']; ?>
 </a>&gt;"<?php echo $this->_tpl_vars['USERNAME']; ?>
" </b></span>
											<?php else: ?>
											<span class="heading2">	
											<b><?php echo $this->_tpl_vars['APP']['LBL_MY_PREFERENCES']; ?>
</b>
											</span>
											<?php endif; ?>
											<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="<?php echo vtiger_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>					
										</td>
										
									</tr>
									<tr>
										<td><?php echo $this->_tpl_vars['UMOD']['LBL_USERDETAIL_INFO']; ?>
 "<?php echo $this->_tpl_vars['USERNAME']; ?>
"</td>
									</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr>
								<td colspan="2" nowrap align="right">
									<?php if ($this->_tpl_vars['IS_ADMIN'] == 'true'): ?>
									<input type="button" onclick="showAuditTrail();" value="<?php echo $this->_tpl_vars['MOD']['LBL_VIEW_AUDIT_TRAIL']; ?>
" class="crmButton small save"></input>
									<?php endif; ?>
									<?php if ($this->_tpl_vars['CATEGORY'] == 'Settings'): ?>
														<?php echo $this->_tpl_vars['DUPLICATE_BUTTON']; ?>

												<?php endif; ?>
									<?php echo $this->_tpl_vars['EDIT_BUTTON']; ?>

									<?php if ($this->_tpl_vars['CATEGORY'] == 'Settings' && $this->_tpl_vars['ID'] != 1 && $this->_tpl_vars['ID'] != $this->_tpl_vars['CURRENT_USERID']): ?>
									<input type="button" onclick="deleteUser(<?php echo $this->_tpl_vars['ID']; ?>
);" class="crmButton small cancel" value="<?php echo $this->_tpl_vars['UMOD']['LBL_DELETE']; ?>
"></input>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td colspan="2" align=left>
								<!-- User detail blocks -->
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
								<td align="left" valign="top">
									<?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['blockforeach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['blockforeach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
        $this->_foreach['blockforeach']['iteration']++;
?>
									<br>
									<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
									<tr>
										<?php echo '<td class="big"><strong>'; ?><?php echo $this->_foreach['blockforeach']['iteration']; ?><?php echo '. '; ?><?php echo $this->_tpl_vars['header']; ?><?php echo '</strong></td><td class="small" align="right">&nbsp;</td>'; ?>

									</tr>
									</table>
									
									<table border="0" cellpadding="5" cellspacing="0" width="100%">
									<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
									<tr >
										<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['data']):
?>
										   <?php $this->assign('keyid', $this->_tpl_vars['data']['ui']); ?>
										   <?php $this->assign('keyval', $this->_tpl_vars['data']['value']); ?>
										   <?php $this->assign('keytblname', $this->_tpl_vars['data']['tablename']); ?>
										   <?php $this->assign('keyfldname', $this->_tpl_vars['data']['fldname']); ?>
										   <?php $this->assign('keyfldid', $this->_tpl_vars['data']['fldid']); ?>
										   <?php $this->assign('keyoptions', $this->_tpl_vars['data']['options']); ?>
										   <?php $this->assign('keysecid', $this->_tpl_vars['data']['secid']); ?>
										   <?php $this->assign('keyseclink', $this->_tpl_vars['data']['link']); ?>
										   <?php $this->assign('keycursymb', $this->_tpl_vars['data']['cursymb']); ?>
										   <?php $this->assign('keysalut', $this->_tpl_vars['data']['salut']); ?>
										   <?php $this->assign('keycntimage', $this->_tpl_vars['data']['cntimage']); ?>
										   <?php $this->assign('keyadmin', $this->_tpl_vars['data']['isadmin']); ?>
										   
										   <?php if ($this->_tpl_vars['label'] != ''): ?>
										   <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
></input><?php echo $this->_tpl_vars['label']; ?>
</td>
											<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewUI.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
										   <?php else: ?>
										   <td class="dvtCellLabel" align=right>&nbsp;</td>
										   <td class="dvtCellInfo" align=left >&nbsp;</td>
										   <?php endif; ?>	
										<?php endforeach; endif; unset($_from); ?>
									</tr>
									<?php endforeach; endif; unset($_from); ?>
									</table>
									<?php $this->assign('list_numbering', $this->_foreach['blockforeach']['iteration']); ?>
									<?php endforeach; endif; unset($_from); ?>
									
									<br>
									<!-- Home page components -->
									<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
									<tr>
										 <td class="big">	
										<strong><?php echo $this->_tpl_vars['list_numbering']+1; ?>
. <?php echo $this->_tpl_vars['UMOD']['LBL_HOME_PAGE_COMP']; ?>
</strong>
										 </td>
										 <td class="small" align="right"><img src="<?php echo vtiger_imageurl('showDown.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" onClick="ShowHidefn('home_comp');"></td>	
									</tr>
									</table>
									
									<div style="float: none; display: none;" id="home_comp">	
									<table border="0" cellpadding="5" cellspacing="0" width="100%">
									<?php $_from = $this->_tpl_vars['HOMEORDER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['values'] => $this->_tpl_vars['homeitems']):
?>
										<tr><td class="dvtCellLabel" align="right" width="30%"><?php echo getTranslatedString($this->_tpl_vars['UMOD'][$this->_tpl_vars['values']], 'Home'); ?>
</td>
											<?php if ($this->_tpl_vars['homeitems'] != ''): ?>
												<td class="dvtCellInfo" align="center" width="5%">
												<img src="<?php echo vtiger_imageurl('prvPrfSelectedTick.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['UMOD']['LBL_SHOWN']; ?>
" title="<?php echo $this->_tpl_vars['UMOD']['LBL_SHOWN']; ?>
" height="12" width="12"></td><td class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['UMOD']['LBL_SHOWN']; ?>
</td> 		
												<?php else: ?>	
												<td class="dvtCellInfo" align="center" width="5%">
												<img src="<?php echo vtiger_imageurl('no.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['UMOD']['LBL_HIDDEN']; ?>
" title="<?php echo $this->_tpl_vars['UMOD']['LBL_HIDDEN']; ?>
" height="12" width="12"></td><td class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['UMOD']['LBL_HIDDEN']; ?>
</td> 		
											<?php endif; ?>	
										</tr>			
									<?php endforeach; endif; unset($_from); ?>
									</table>	
									</div>
								
									<br>
									<!-- Tag Cloud Display -->
									<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
									<tr>
										<td class="big">
										<strong><?php echo $this->_tpl_vars['list_numbering']+2; ?>
. <?php echo $this->_tpl_vars['UMOD']['LBL_TAGCLOUD_DISPLAY']; ?>
</strong>
										</td>
										<td class="small" align="right"><img src="<?php echo vtiger_imageurl('showDown.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" onClick="ShowHidefn('tagcloud_disp');"></td>
									</tr>
									</table>
									<div style="float: none; display: none;" id="tagcloud_disp">
									<table border="0" cellpadding="5" cellspacing="0" width="100%">
										<tr><td class="dvtCellLabel" align="right" width="30%"><?php echo $this->_tpl_vars['UMOD']['LBL_TAG_CLOUD']; ?>
</td>
											<?php if ($this->_tpl_vars['TAGCLOUDVIEW'] == 'true'): ?>
												<td class="dvtCellInfo" align="center" width="5%">
												<img src="<?php echo vtiger_imageurl('prvPrfSelectedTick.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['UMOD']['LBL_SHOWN']; ?>
" title="<?php echo $this->_tpl_vars['UMOD']['LBL_SHOWN']; ?>
" height="12" width="12"></td><td class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['UMOD']['LBL_SHOWN']; ?>
</td>
                                                                                                <?php else: ?>
												<td class="dvtCellInfo" align="center" width="5%">
												<img src="<?php echo vtiger_imageurl('no.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['UMOD']['LBL_HIDDEN']; ?>
" title="<?php echo $this->_tpl_vars['UMOD']['LBL_HIDDEN']; ?>
" height="12" width="12"></td><td class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['UMOD']['LBL_HIDDEN']; ?>
</td>
											<?php endif; ?>
										</tr>
                                                                        </table>                                                                                                                   </div>
                                                                                                                                                                                                   <br>
									<!-- My Groups -->
									<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
									<tr>
										<td class="big">	
										<strong><?php echo $this->_tpl_vars['list_numbering']+3; ?>
. <?php echo $this->_tpl_vars['UMOD']['LBL_MY_GROUPS']; ?>
</strong>
										 </td>
										 <td class="small" align="right">
										<?php if ($this->_tpl_vars['GROUP_COUNT'] > 0): ?>
										<img src="<?php echo vtiger_imageurl('showDown.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" onClick="fetchGroups_js(<?php echo $this->_tpl_vars['ID']; ?>
);">
										<?php else: ?>
											&nbsp;
										<?php endif; ?>
										</td>	
									</tr>
									</table>
									
									<table border="0" cellpadding="5" cellspacing="0" width="100%">
									<tr><td align="left"><div id="user_group_cont" style="display:none;"></div></td></tr>	
									</table>	
									<br>
									<!-- Login History -->
									<?php if ($this->_tpl_vars['IS_ADMIN'] == 'true'): ?>
									<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
										<tr>
										 <td class="big">	
										<strong><?php echo $this->_tpl_vars['list_numbering']+4; ?>
. <?php echo $this->_tpl_vars['UMOD']['LBL_LOGIN_HISTORY']; ?>
</strong>
										 </td>
										 <td class="small" align="right"><img src="<?php echo vtiger_imageurl('showDown.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" onClick="fetchlogin_js(<?php echo $this->_tpl_vars['ID']; ?>
);"></td>	
										</tr>
									</table>

									<table border="0" cellpadding="5" cellspacing="0" width="100%">
									<tr><td align="left"><div id="login_history_cont" style="display:none;"></div></td></tr>	
									</table>	
									<br>	
									<?php endif; ?>	
								</td>
								</tr>
								</table>
								<!-- User detail blocks ends -->
								
								</td>
							</tr>
							<tr>
								<td colspan=2 class="small"><div align="right"><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></div></td>
							</tr>
							</table>
							
						</form>
			
					</td>
				</tr>
				</table>

		
	</div>
	</td>
	
</tr>
</table>
			
			</td>
			</tr>
			</table>
			
			</td>
			<td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>			
			</tr>
			</table>
			



<br>
<?php echo $this->_tpl_vars['JAVASCRIPT']; ?>

<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<!-- added for validation -->
<script language="javascript">
  var gVTModule = '<?php echo vtlib_purify($_REQUEST['module']); ?>
';
  var fieldname = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDNAME']; ?>
);
  var fieldlabel = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDLABEL']; ?>
);
  var fielddatatype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDDATATYPE']; ?>
);
function ShowHidefn(divid)
{
	if($(divid).style.display != 'none')
		Effect.Fade(divid);
	else
		Effect.Appear(divid);
}
<?php echo '
function fetchlogin_js(id)
{
	if($(\'login_history_cont\').style.display != \'none\')
		Effect.Fade(\'login_history_cont\');
	else
		fetchLoginHistory(id);

}
function fetchLoginHistory(id)
{
        $("status").style.display="inline";
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'module=Users&action=UsersAjax&file=ShowHistory&ajax=true&record=\'+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("login_history_cont").innerHTML= response.responseText;
				Effect.Appear(\'login_history_cont\');
                        }
                }
        );

}
function fetchGroups_js(id)
{
	if($(\'user_group_cont\').style.display != \'none\')
		Effect.Fade(\'user_group_cont\');
	else
		fetchUserGroups(id);
}
function fetchUserGroups(id)
{
        $("status").style.display="inline";
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'module=Users&action=UsersAjax&file=UserGroups&ajax=true&record=\'+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("user_group_cont").innerHTML= response.responseText;
				Effect.Appear(\'user_group_cont\');
                        }
                }
        );

}

function showAuditTrail()
{
	var userid =  document.getElementById(\'userid\').value;
	window.open("index.php?module=Settings&action=SettingsAjax&file=ShowAuditTrail&userid="+userid,"","width=650,height=800,resizable=0,scrollbars=1,left=100");
}

function deleteUser(userid)
{
        $("status").style.display="inline";
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'action=UsersAjax&file=UserDeleteStep1&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record=\'+userid,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("tempdiv").innerHTML= response.responseText;
                        }
                }
        );
}
function transferUser(del_userid)
{
        $("status").style.display="inline";
        $("DeleteLay").style.display="none";
        var trans_userid=$(\'transfer_user_id\').options[$(\'transfer_user_id\').options.selectedIndex].value;
	window.document.location.href = \'index.php?module=Users&action=DeleteUser&ajax_delete=false&delete_user_id=\'+del_userid+\'&transfer_user_id=\'+trans_userid;
}
'; ?>

</script>
<script>
function getListViewEntries_js(module,url)
{
	$("status").style.display="inline";
        new Ajax.Request(
        	'index.php',
                {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:"module="+module+"&action="+module+"Ajax&file=ShowHistory&record=<?php echo $this->_tpl_vars['ID']; ?>
&ajax=true&"+url,
			onComplete: function(response) {
                        	$("status").style.display="none";
                                $("login_history_cont").innerHTML= response.responseText;
                  	}
                }
        );
}
</script>
