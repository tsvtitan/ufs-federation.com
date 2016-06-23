<?php /* Smarty version 2.6.18, created on 2014-09-02 13:44:49
         compiled from RoleEditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'RoleEditView.tpl', 62, false),)), $this); ?>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="javascript">
function dup_validation()
{
	var rolename = $('rolename').value;
	var mode = getObj('mode').value;
	var roleid = getObj('roleid').value;
	if(mode == 'edit')
		var urlstring ="&mode="+mode+"&roleName="+rolename+"&roleid="+roleid;
	else
		var urlstring ="&roleName="+rolename;
	//var status = CharValidation(rolename,'namespace');
	//if(status)
	//{
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Settings&action=SettingsAjax&file=SaveRole&ajax=true&dup_check=true'+urlstring,
                                onComplete: function(response) {
					if(response.responseText.indexOf('SUCCESS') > -1)
						document.newRoleForm.submit();
					else
						alert(response.responseText);
                                }
                        }
                );
	//}
	//else
	//	alert(alert_arr.NO_SPECIAL+alert_arr.IN_ROLENAME)

}
function validate()
{
	formSelectColumnString();
	if( !emptyCheck("roleName", "Role Name", "text" ) )
		return false;

	if(document.newRoleForm.selectedColumnsString.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{

		alert('<?php echo $this->_tpl_vars['APP']['ROLE_SHOULDHAVE_INFO']; ?>
');
		return false;
	}
	dup_validation();return false
}
</script>
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
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<?php echo '
				<form name="newRoleForm" action="index.php" method="post" onSubmit="if(validate()) { VtigerJS_DialogBox.block();} else { return false;} ">
				'; ?>

				<input type="hidden" name="module" value="Settings">
				<input type="hidden" name="action" value="SaveRole">
				<input type="hidden" name="parenttab" value="Settings">
				<input type="hidden" name="returnaction" value="<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
">
				<input type="hidden" name="roleid" value="<?php echo $this->_tpl_vars['ROLEID']; ?>
">
				<input type="hidden" name="mode" value="<?php echo $this->_tpl_vars['MODE']; ?>
">
				<input type="hidden" name="parent" value="<?php echo $this->_tpl_vars['PARENT']; ?>
">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="<?php echo vtiger_imageurl('ico-roles.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['CMOD']['LBL_ROLES']; ?>
" width="48" height="48" border=0 title="<?php echo $this->_tpl_vars['CMOD']['LBL_ROLES']; ?>
"></td>
					<?php if ($this->_tpl_vars['MODE'] == 'edit'): ?>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <a href="index.php?module=Settings&action=listroles&parenttab=Settings"><?php echo $this->_tpl_vars['CMOD']['LBL_ROLES']; ?>
</a> &gt; <?php echo $this->_tpl_vars['MOD']['LBL_EDIT']; ?>
 &quot;<?php echo $this->_tpl_vars['ROLENAME']; ?>
&quot; </b></td>
					<?php else: ?>	
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <a href="index.php?module=Settings&action=listroles&parenttab=Settings"><?php echo $this->_tpl_vars['CMOD']['LBL_ROLES']; ?>
</a> &gt; <?php echo $this->_tpl_vars['CMOD']['LBL_CREATE_NEW_ROLE']; ?>
</b></td>
					<?php endif; ?>
				</tr>
				<tr>
					<?php if ($this->_tpl_vars['MODE'] == 'edit'): ?>
					<td valign=top class="small"><?php echo $this->_tpl_vars['MOD']['LBL_EDIT']; ?>
 <?php echo $this->_tpl_vars['CMOD']['LBL_PROPERTIES']; ?>
 &quot;<?php echo $this->_tpl_vars['ROLENAME']; ?>
&quot; <?php echo $this->_tpl_vars['MOD']['LBL_LIST_CONTACT_ROLE']; ?>
</td>
					<?php else: ?>
					<td valign=top class="small"><?php echo $this->_tpl_vars['CMOD']['LBL_NEW_ROLE']; ?>
</td>
					<?php endif; ?>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td valign=top>
					
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<?php if ($this->_tpl_vars['MODE'] == 'edit'): ?>
						<td class="big"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_PROPERTIES']; ?>
 &quot;<?php echo $this->_tpl_vars['ROLENAME']; ?>
&quot; </strong></td>
						<?php else: ?>
						<td class="big"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_NEW_ROLE']; ?>
</strong></td>
						<?php endif; ?>
						<td><div align="right">
							<input type="button" class="crmButton small save" name="add" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " onClick="return validate()">
						
						<input type="button" class="crmButton cancel small" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" onClick="window.history.back()">
						</div></td>
					  </tr>
					</table>
					<table width="100%"  border="0" cellspacing="0" cellpadding="5">
                      <tr class="small">
                        <td width="15%" class="small cellLabel"><font color="red">*</font><strong><?php echo $this->_tpl_vars['CMOD']['LBL_ROLE_NAME']; ?>
</strong></td>
                        <td width="85%" class="cellText" ><input id="rolename" name="roleName" type="text" value="<?php echo $this->_tpl_vars['ROLENAME']; ?>
" class="detailedViewTextBox"></td>
                      </tr>
                      <tr class="small">
                        <td class="small cellLabel"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_REPORTS_TO']; ?>
</strong></td>
                        <td class="cellText"><?php echo $this->_tpl_vars['PARENTNAME']; ?>
</td>
                      </tr>
                      <tr class="small">
                        <td colspan="2" valign=top class="cellLabel"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_PROFILE_M']; ?>
</strong>						</td>
                      </tr>
                      <tr class="small">
                        <td colspan="2" valign=top class="cellText"> 
						<br>
						<table width="95%"  border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr>
                            <td width="40%" valign=top class="cellBottomDotLinePlain small"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_PROFILES_AVLBL']; ?>
</strong></td>
                            <td width="10%">&nbsp;</td>
                            <td width="40%" class="cellBottomDotLinePlain small"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_ASSIGN_PROFILES']; ?>
</strong></td>
                          </tr>

			<tr class=small>
	 	               <td valign=top><?php echo $this->_tpl_vars['CMOD']['LBL_PROFILES_M']; ?>
 <?php echo $this->_tpl_vars['CMOD']['LBL_MEMBER']; ?>
 <br>
				<select multiple id="availList" name="availList" class="small crmFormList" size=10 >
																				<?php $_from = $this->_tpl_vars['PROFILELISTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
																					<option value="<?php echo $this->_tpl_vars['element']['0']; ?>
"><?php echo $this->_tpl_vars['element']['1']; ?>
</option>
																				<?php endforeach; endif; unset($_from); ?>
				</select>
				</td>
                        	<td width="50"><div align="center">
																				<input type="hidden" name="selectedColumnsString"/>
																					<input name="Button" value="&nbsp;&rsaquo;&rsaquo;&nbsp;" type="button" class="crmButton small" style="width:100%" onClick="addColumn()">
                                  <br>
                                  <br>
																					<input type="button" name="Button1" value="&nbsp;&lsaquo;&lsaquo;&nbsp;" class="crmButton small" onClick="delColumn()" style="width:100%">
				  <br>
				  <br>
                            	</div></td>
                            <td class="small" style="background-color:#ddFFdd" valign=top><?php echo $this->_tpl_vars['CMOD']['LBL_MEMBER']; ?>
 of &quot;<?php echo $this->_tpl_vars['ROLENAME']; ?>
&quot; <br>
                             <select multiple id="selectedColumns" name="selectedColumns" class="small crmFormList" size=10 >
																			<?php $_from = $this->_tpl_vars['SELPROFILELISTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
																				<option value="<?php echo $this->_tpl_vars['element']['0']; ?>
"><?php echo $this->_tpl_vars['element']['1']; ?>
</option>
																			<?php endforeach; endif; unset($_from); ?>
                	    </select></td>
                       </tr>
						  
                        </table>
						
						</td>
                      </tr>
                        	 
                        </table>
						
						</td>
                      </tr>
                    </table>
					<br>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr><td class="small" nowrap align=right><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></td></tr>
					</table>
					
					
				</td>
				</tr>
				<tr>
				  <td valign=top>&nbsp;</td>
				  </tr>
				</table>
			
			
			
			</td>
			</tr>
			</table>
		</td>
	</tr>
	</form>
	</table>
		
	</div>

</td>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
   </tr>
</tbody>
</table>
	
<script language="JavaScript" type="text/JavaScript">    
        var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;
        function setObjects() 
        {
            availListObj=getObj("availList")
            selectedColumnsObj=getObj("selectedColumns")

        }

        function addColumn() 
        {
            for (i=0;i<selectedColumnsObj.length;i++) 
            {
                selectedColumnsObj.options[i].selected=false
            }

            for (i=0;i<availListObj.length;i++) 
            {
                if (availListObj.options[i].selected==true) 
                {            	
                	var rowFound=false;
                	var existingObj=null;
                    for (j=0;j<selectedColumnsObj.length;j++) 
                    {
                        if (selectedColumnsObj.options[j].value==availListObj.options[i].value) 
                        {
                            rowFound=true
                            existingObj=selectedColumnsObj.options[j]
                            break
                        }
                    }

                    if (rowFound!=true) 
                    {
                        var newColObj=document.createElement("OPTION")
                        newColObj.value=availListObj.options[i].value
                        if (browser_ie) newColObj.innerText=availListObj.options[i].innerText
                        else if (browser_nn4 || browser_nn6) newColObj.text=availListObj.options[i].text
                        selectedColumnsObj.appendChild(newColObj)
                        availListObj.options[i].selected=false
                        newColObj.selected=true
                        rowFound=false
                    } 
                    else 
                    {
                        if(existingObj != null) existingObj.selected=true
                    }
                }
            }
        }

        function delColumn() 
        {
            for (i=selectedColumnsObj.options.length;i>0;i--) 
            {
                if (selectedColumnsObj.options.selectedIndex>=0)
                selectedColumnsObj.remove(selectedColumnsObj.options.selectedIndex)
            }
        }
                        
        function formSelectColumnString()
        {
            var selectedColStr = "";
            for (i=0;i<selectedColumnsObj.options.length;i++) 
            {
                selectedColStr += selectedColumnsObj.options[i].value + ";";
            }
            document.newRoleForm.selectedColumnsString.value = selectedColStr;
        }
	setObjects();			
</script>	