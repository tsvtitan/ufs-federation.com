<?php /* Smarty version 2.6.18, created on 2014-09-02 13:45:24
         compiled from ProfileDetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'ProfileDetailView.tpl', 77, false),)), $this); ?>
<?php echo '
<style>
.showTable{
	display:inline-table;
}
.hideTable{
	display:none;
}
</style>
'; ?>

<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JAVASCRIPT" type="text/javascript">
<?php echo '
function UpdateProfile()
{
	if(default_charset.toLowerCase() == \'utf-8\')
	{
		var prof_name = $(\'profile_name\').value;
		var prof_desc = $(\'description\').value;
	}
	else
	{
		var prof_name = escapeAll($(\'profile_name\').value);
		var prof_desc = escapeAll($(\'description\').value);
	}
	if(prof_name == \'\')
	{
		
		$(\'profile_name\').focus();
		'; ?>

                alert("<?php echo $this->_tpl_vars['APP']['PROFILENAME_CANNOT_BE_EMPTY']; ?>
");
                <?php echo '
	}
	else
	{
		
'; ?>

		
		var urlstring ="module=Users&action=UsersAjax&file=RenameProfile&profileid="+<?php echo $this->_tpl_vars['PROFILEID']; ?>
+"&profilename="+prof_name+"&description="+prof_desc;
<?php echo '
	new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody:urlstring,
			onComplete: function(response)
			{
				$(\'renameProfile\').style.display="none";
				window.location.reload();
				'; ?>

                                alert("<?php echo $this->_tpl_vars['APP']['PROFILE_DETAILS_UPDATED']; ?>
");
                                <?php echo '
			}
                }
		);
	}
		
	
}
</script>
'; ?>


<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
        <br>
	<div align=center>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SetMenu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			
				<form  method="post" name="new" id="form" onsubmit="VtigerJS_DialogBox.block();">
			        <input type="hidden" name="module" value="Settings">
			        <input type="hidden" name="action" value="profilePrivileges">
			        <input type="hidden" name="parenttab" value="Settings">
			        <input type="hidden" name="return_action" value="profilePrivileges">
			        <input type="hidden" name="mode" value="edit">
			        <input type="hidden" name="profileid" value="<?php echo $this->_tpl_vars['PROFILEID']; ?>
">
				
				<!-- DISPLAY -->
				<table class="settingsSelUITopLine" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tbody><tr>
					<td rowspan="2" valign="top" width="50"><img src="<?php echo vtiger_imageurl('ico-profile.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['MOD']['LBL_PROFILES']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['LBL_PROFILES']; ?>
" border="0" height="48" width="48"></td>
					<td class="heading2" valign="bottom"><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <a href="index.php?module=Settings&action=ListProfiles&parenttab=Settings"><?php echo $this->_tpl_vars['CMOD']['LBL_PROFILE_PRIVILEGES']; ?>
</a> &gt; <?php echo $this->_tpl_vars['CMOD']['LBL_VIEWING']; ?>
 &quot;<?php echo $this->_tpl_vars['PROFILE_NAME']; ?>
&quot;</b></td>
				</tr>
				<tr>
					<td class="small" valign="top"><?php echo $this->_tpl_vars['CMOD']['LBL_PROFILE_MESG']; ?>
 &quot;<?php echo $this->_tpl_vars['PROFILE_NAME']; ?>
&quot; </td>
				</tr>
				</tbody></table>
				
				
				<table border="0" cellpadding="10" cellspacing="0" width="100%">
				<tbody><tr>
				<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tbody><tr>
                        <td><table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody><tr class="small">
                              <td><img src="<?php echo vtiger_imageurl('prvPrfTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
                              <td class="prvPrfTopBg" width="100%"></td>
                              <td><img src="<?php echo vtiger_imageurl('prvPrfTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
                            </tr>
                          </tbody></table>
                            <table class="prvPrfOutline" border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tbody><tr>
                                <td><!-- tabs -->
                                    
                                    <!-- Headers -->
                                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                      <tbody><tr>
                                        <td><table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
                                            <tbody><tr>
                                              <td><!-- Module name heading -->
                                                  <table class="small" border="0" cellpadding="2" cellspacing="0">
                                                    <tbody><tr>
                                                      <td valign="top"><img src="<?php echo vtiger_imageurl('prvPrfHdrArrow.gif', $this->_tpl_vars['THEME']); ?>
"> </td>
                                                      <td class="prvPrfBigText"><b> <?php echo $this->_tpl_vars['CMOD']['LBL_DEFINE_PRIV_FOR']; ?>
 &lt;<?php echo $this->_tpl_vars['PROFILE_NAME']; ?>
&gt; </b><br>
                                                      <font class="small"><?php echo $this->_tpl_vars['CMOD']['LBL_USE_OPTION_TO_SET_PRIV']; ?>
</font> </td>
                                                      <td class="small" style="padding-left: 10px;" align="right"></td>

                                                    </tr>
                                                </tbody></table></td>
					      <td align="right" valign="bottom">&nbsp;<input type="button" value="<?php echo $this->_tpl_vars['APP']['LBL_RENAMEPROFILE_BUTTON_LABEL']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_RENAMEPROFILE_BUTTON_LABEL']; ?>
" class="crmButton small edit" name="rename_profile"  onClick = "show('renameProfile');">&nbsp;<input type="submit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" class="crmButton small edit" name="edit" >
                              		     </td>
					    	
                                            </tr></tbody></table>
					    <!-- RenameProfile Div start -->	 
					    <div class="layerPopup"  style="left:350px;width:500px;top:300px;display:none;" id="renameProfile">
						<table class="layerHeadingULine" border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr style="cursor:move;">
						<td class="layerPopupHeading" id = "renameUI" align="left" width="60%"><?php echo $this->_tpl_vars['APP']['LBL_RENAME_PROFILE']; ?>
</td>
						<td align="right" width="40%"><a href="javascript:fnhide('renameProfile');"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" align="middle" border="0"></a></td>
						</tr>
						</table>
					    <table align="center" border="0" cellpadding="5" cellspacing="0" width="95%">

						<tr>
						<td class="small">
							<table cellspacing="0" align="center" bgcolor="white" border="0" cellpadding="5" width="100%">
								<tr>
								<td align="right" width="25%" style="padding-right:10px;" nowrap><b><?php echo $this->_tpl_vars['APP']['LBL_PROFILE_NAME']; ?>
 :</b></td>
								<td align="left" width="75%" style="padding-right:10px;"><input id = "profile_name" name="profile_name" class="txtBox" value="<?php echo $this->_tpl_vars['PROFILE_NAME']; ?>
" type="text"></td>
								</tr>
								<tr>
                                                                <td align="right" width="25%" style="padding-right:10px;" nowrap><b><?php echo $this->_tpl_vars['APP']['LBL_DESCRIPTION']; ?>
 :</b></td>
                                                                <td align="left" width="75%" style="padding-right:10px;"><textarea name="description" id = "description" class="txtBox"><?php echo $this->_tpl_vars['PROFILE_DESCRIPTION']; ?>
 </textarea></td>
                                                                </tr>
							</table>
						</td>
						</tr>
					    </table>
					    <table class="layerPopupTransport" border="0" cellpadding="5" cellspacing="0" width="100%">
					    <tr>
						<td align = "center">
							<input name="save" value="<?php echo $this->_tpl_vars['APP']['LBL_UPDATE']; ?>
" class="crmbutton small save" onclick="UpdateProfile();" type="button" title="<?php echo $this->_tpl_vars['APP']['LBL_UPDATE']; ?>
">&nbsp;&nbsp;
							<input name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="crmbutton small save" onclick="fnhide('renameProfile');" type="button" title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
">&nbsp;&nbsp;
						</td>
					    </tr>		
					    </table>		
					    </div>		
				             <!-- RenameProfile Div end -->		

                                         
                                            <!-- privilege lists -->
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                              <tbody><tr>
                                                <td style="height: 10px;" align="center"></td>
                                              </tr>
                                            </tbody></table>
                                            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                              <tbody><tr>
                                                <td>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
  						<tbody>
							<tr>
    							<td class="cellLabel big"> <?php echo $this->_tpl_vars['CMOD']['LBL_SUPER_USER_PRIV']; ?>
 </td>
						       </tr>
						</tbody>
						</table>
						<table class="small" align="center" border="0" cellpadding="5" cellspacing="0" width="90%">
                                                <tbody><tr>
                                                    <td class="prvPrfTexture" style="width: 20px;">&nbsp;</td>
                                                    <td valign="top" width="97%"><table class="small" border="0" cellpadding="2" cellspacing="0" width="100%">
                                                      <tbody>
				                         <tr id="gva">
                                                          <td valign="top"><?php echo $this->_tpl_vars['GLOBAL_PRIV']['0']; ?>
</td>
                                                          <td ><b><?php echo $this->_tpl_vars['CMOD']['LBL_VIEW_ALL']; ?>
</b> </td>
                                                        </tr>
                                                        <tr >
                                                          <td valign="top"></td>
                                                          <td width="100%" ><?php echo $this->_tpl_vars['CMOD']['LBL_ALLOW']; ?>
 "<?php echo $this->_tpl_vars['PROFILE_NAME']; ?>
" <?php echo $this->_tpl_vars['CMOD']['LBL_MESG_VIEW']; ?>
</td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                        </tr>
							<tr>
							<td valign="top"><?php echo $this->_tpl_vars['GLOBAL_PRIV']['1']; ?>
</td>
							<td ><b><?php echo $this->_tpl_vars['CMOD']['LBL_EDIT_ALL']; ?>
</b> </td>
							</tr>
                                                        <tr>
                                                          <td valign="top"></td>
                                                          <td > <?php echo $this->_tpl_vars['CMOD']['LBL_ALLOW']; ?>
 "<?php echo $this->_tpl_vars['PROFILE_NAME']; ?>
" <?php echo $this->_tpl_vars['CMOD']['LBL_MESG_EDIT']; ?>
</td>
                                                        </tr>

                                                      </tbody></table>
						</td>
                                                  </tr>
                                                </tbody></table>
<br>

			<table border="0" cellpadding="5" cellspacing="0" width="100%">
			  <tbody><tr>
			    <td class="cellLabel big"> <?php echo $this->_tpl_vars['CMOD']['LBL_SET_PRIV_FOR_EACH_MODULE']; ?>
 </td>
			  </tr>
			</tbody></table>
			<table class="small" align="center" border="0" cellpadding="5" cellspacing="0" width="90%">
			  <tbody><tr>
			    <td class="prvPrfTexture" style="width: 20px;">&nbsp;</td>
			    <td valign="top" width="97%">
				<table class="small listTable" border="0" cellpadding="5" cellspacing="0" width="100%">
			        <tbody>
				<tr id="gva">
			          <td colspan="2" rowspan="2" class="small colHeader"><strong> <?php echo $this->_tpl_vars['CMOD']['LBL_TAB_MESG_OPTION']; ?>
 </strong><strong></strong></td>
			          <td colspan="3" class="small colHeader"><div align="center"><strong> <?php echo $this->_tpl_vars['CMOD']['LBL_EDIT_PERMISSIONS']; ?>
 </strong></div></td>
			          <td rowspan="2" class="small colHeader" nowrap="nowrap"> <?php echo $this->_tpl_vars['CMOD']['LBL_FIELDS_AND_TOOLS_SETTINGS']; ?>
 </td>
			        </tr>
			        <tr id="gva">
			          <td class="small colHeader"><div align="center"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_CREATE_EDIT']; ?>

			          </strong></div></td>
			          <td class="small colHeader"> <div align="center"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_VIEW']; ?>
 </strong></div></td>
			          <td class="small colHeader"> <div align="center"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_DELETE']; ?>
</strong></div></td>
			        </tr>
					
				<!-- module loops-->
			        <?php $_from = $this->_tpl_vars['TAB_PRIV']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tabid'] => $this->_tpl_vars['elements']):
?>	
			        <tr>
					<?php $this->assign('modulename', $this->_tpl_vars['TAB_PRIV'][$this->_tpl_vars['tabid']][0]); ?>
					<?php $this->assign('MODULELABEL', $this->_tpl_vars['modulename']); ?>
					<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['modulename']] != ''): ?>
						<?php $this->assign('MODULELABEL', $this->_tpl_vars['APP'][$this->_tpl_vars['modulename']]); ?>
					<?php endif; ?>
			          <td class="small cellLabel" width="3%"><div align="right">
					<?php echo $this->_tpl_vars['TAB_PRIV'][$this->_tpl_vars['tabid']][1]; ?>

			          </div></td>
			          <td class="small cellLabel" width="40%"><p><?php echo $this->_tpl_vars['MODULELABEL']; ?>
</p></td>
			          <td class="small cellText" width="15%">&nbsp;<div align="center">
					<?php echo $this->_tpl_vars['STANDARD_PRIV'][$this->_tpl_vars['tabid']][1]; ?>

			          </div></td>
			          <td class="small cellText" width="15%">&nbsp;<div align="center">
					<?php echo $this->_tpl_vars['STANDARD_PRIV'][$this->_tpl_vars['tabid']][3]; ?>

			          </div></td>
			          <td class="small cellText" width="15%">&nbsp;<div align="center">
					<?php echo $this->_tpl_vars['STANDARD_PRIV'][$this->_tpl_vars['tabid']][2]; ?>

        			  </div></td>
			          <td class="small cellText" width="22%">&nbsp;<div align="center">
				<?php if ($this->_tpl_vars['FIELD_PRIVILEGES'][$this->_tpl_vars['tabid']] != NULL): ?>
				<img src="<?php echo vtiger_imageurl('showDown.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPAND_COLLAPSE']; ?>
" onclick="fnToggleVIew('<?php echo $this->_tpl_vars['modulename']; ?>
_view')" border="0" height="16" width="40">
				<?php endif; ?>
				</div></td>
				  </tr>
		                  <tr class="hideTable" id="<?php echo $this->_tpl_vars['modulename']; ?>
_view" className="hideTable">
				          <td colspan="6" class="small settingsSelectedUI">
						<table class="small" border="0" cellpadding="2" cellspacing="0" width="100%">
			        	    	<tbody>
						<?php if ($this->_tpl_vars['FIELD_PRIVILEGES'][$this->_tpl_vars['tabid']] != ''): ?>
						<tr>
							<?php if ($this->_tpl_vars['modulename'] == 'Calendar'): ?>
				                	<td class="small colHeader" colspan="6" valign="top"><?php echo $this->_tpl_vars['CMOD']['LBL_FIELDS_TO_BE_SHOWN']; ?>
 (<?php echo $this->_tpl_vars['APP']['Tasks']; ?>
)</td>
							<?php else: ?>
				                	<td class="small colHeader" colspan="6" valign="top"><?php echo $this->_tpl_vars['CMOD']['LBL_FIELDS_TO_BE_SHOWN']; ?>
</td>
							<?php endif; ?>
					        </tr>
						<?php endif; ?>
						<?php $_from = $this->_tpl_vars['FIELD_PRIVILEGES'][$this->_tpl_vars['tabid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row_values']):
?>
				            	<tr>
						      <?php $_from = $this->_tpl_vars['row_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
					              <td valign="top"><?php echo $this->_tpl_vars['element']['1']; ?>
</td>
					              <td><?php echo $this->_tpl_vars['element']['0']; ?>
</td>
						      <?php endforeach; endif; unset($_from); ?>
				                </tr>
						<?php endforeach; endif; unset($_from); ?>
						<?php if ($this->_tpl_vars['modulename'] == 'Calendar'): ?>
						<tr>
				                	<td class="small colHeader" colspan="6" valign="top"><?php echo $this->_tpl_vars['CMOD']['LBL_FIELDS_TO_BE_SHOWN']; ?>
  (<?php echo $this->_tpl_vars['APP']['Events']; ?>
)</td>
					        </tr>
						<?php $_from = $this->_tpl_vars['FIELD_PRIVILEGES'][16]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row_values']):
?>
				            	<tr>
						      <?php $_from = $this->_tpl_vars['row_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
					              <td valign="top"><?php echo $this->_tpl_vars['element']['1']; ?>
</td>
					              <td><?php echo $this->_tpl_vars['element']['0']; ?>
</td>
						      <?php endforeach; endif; unset($_from); ?>
				                </tr>
						<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['UTILITIES_PRIV'][$this->_tpl_vars['tabid']] != ''): ?>
					        <tr>
					              <td colspan="6" class="small colHeader" valign="top"><?php echo $this->_tpl_vars['CMOD']['LBL_TOOLS_TO_BE_SHOWN']; ?>
 </td>
						</tr>
						<?php endif; ?>
						<?php $_from = $this->_tpl_vars['UTILITIES_PRIV'][$this->_tpl_vars['tabid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['util_value']):
?>
						<tr>
							<?php $_from = $this->_tpl_vars['util_value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['util_elements']):
?>
					              		<td valign="top"><?php echo $this->_tpl_vars['util_elements']['1']; ?>
</td>
						                <td><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['util_elements']['0']]; ?>
</td>
							<?php endforeach; endif; unset($_from); ?>
				               	</tr>
						<?php endforeach; endif; unset($_from); ?>
					        </tbody>
						</table>
					</td>
			          </tr>
				  <?php endforeach; endif; unset($_from); ?>	
			    	  </tbody>
				  </table>
			  </td>
			  </tr>
                          </tbody>
			</table>
		</td>
                </tr>
		<table border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td align="left"><font color="red" size=5>*</font><?php echo $this->_tpl_vars['CMOD']['LBL_MANDATORY_MSG']; ?>
</td>
			</tr>
			<tr>
				<td align="left"><font color="blue" size=5>*</font><?php echo $this->_tpl_vars['CMOD']['LBL_DISABLE_FIELD_MSG']; ?>
</td>
			</tr>
		</table>
		<tr>
		<td style="border-top: 2px dotted rgb(204, 204, 204);" align="right">
		<!-- wizard buttons -->
		<table border="0" cellpadding="2" cellspacing="0">
		<tbody>
			<tr>
				<td><input type="submit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" class="crmButton small edit" name="edit"></td>
				<td>&nbsp;</td>
			</tr>
			
		</tbody>
		</table>
		</td>
		</tr>
          </tbody>
	  </table>
	</td>
        </tr>
        </tbody>
	</table>
      </td>
      </tr>
      </tbody></table>
      <table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
           <tbody><tr>
                <td><img src="<?php echo vtiger_imageurl('prvPrfBottomLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
                <td class="prvPrfBottomBg" width="100%"></td>
                <td><img src="<?php echo vtiger_imageurl('prvPrfBottomRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
                </tr>
            </tbody>
      </table></td>
      </tr>
      </tbody></table>
	<p>&nbsp;</p>
	<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tbody><tr><td class="small" align="right" nowrap="nowrap"><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></td></tr>
	</tbody></table>
					
	</td>
	</tr>
	</tbody></table>
	</form>	
	<!-- End of Display -->
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</div>

	</td>
	<td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
	</tr>
</tbody>
</table>
<script language="javascript" type="text/javascript">
<?php echo '
function fnToggleVIew(obj){
	if($(obj).hasClassName(\'hideTable\')) {
		$(obj).removeClassName(\'hideTable\');
	} else {
		$(obj).addClassName(\'hideTable\');
	}
}
'; ?>

<?php echo '
        //for move RenameProfile
        var Handle = document.getElementById("renameUI");
        var Root   = document.getElementById("renameProfile");
        Drag.init(Handle,Root);
'; ?>

</script>
