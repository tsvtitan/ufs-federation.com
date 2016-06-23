<?php /* Smarty version 2.6.18, created on 2014-09-02 12:41:02
         compiled from modules/PBXManager/Settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'modules/PBXManager/Settings.tpl', 20, false),)), $this); ?>

<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody>
	<tr>
        <td valign="top">
        	<img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
">
        </td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
			<form action="index.php" method="post" id="form">
			<input type='hidden' name='module' value='Users'>
			<input type='hidden' name='action' value='DefModuleView'>
			<input type='hidden' name='return_action' value='ListView'>
			<input type='hidden' name='return_module' value='Users'>
			<input type='hidden' name='parenttab' value='Settings'>
			<br>
			<div align=center>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SetMenu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
					<tr>
						<td width=50 rowspan=3 valign=top><img src="<?php echo vtiger_imageurl('Call.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['MOD']['LBL_SOFTPHONE_SERVER_SETTINGS']; ?>
" width="48" height="38" border=0 title="<?php echo $this->_tpl_vars['MOD']['LBL_SOFTPHONE_SERVER_SETTINGS']; ?>
"></td>
						<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <?php echo $this->_tpl_vars['MOD']['LBL_SOFTPHONE_SERVER_SETTINGS']; ?>
</b></td>
					</tr>
					<tr>
						<td valign=top class="small"><?php echo $this->_tpl_vars['MOD']['LBL_SOFTPHONE_SERVER_SETTINGS_DESCRIPTION']; ?>
</td>
					</tr>
					<tr>
						<td valign="top" class="small">
							<?php echo $this->_tpl_vars['ERROR']; ?>

						</td>
					</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
					<tr>
						<td>
						<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
							<tr>
								<td width='70%'>
								<table border=0 cellspacing=0 cellpadding=5 width=100%>
									<tr>
										<td id='asterisk' class="big" height="20px;" width="75%">
											<strong><?php echo $this->_tpl_vars['MOD']['ASTERISK_CONFIGURATION']; ?>
</strong>
										</td>
										<!-- for now only asterisk is there :: later we can add a dropdown here and add settings for all -->
									</tr>
								</table>
								</td>
							</tr>
						</table>

						<span id='AsteriskCustomization' style='display:block'>
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
								<tr>
	         	    				<td class="small" valign=top >
	         	    				<table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        			<tr>
                            			<td width="20%" nowrap class="small cellLabel"><strong><?php echo $this->_tpl_vars['MOD']['ASTERISK_SERVER_IP']; ?>
</strong></td>
                            			<td width="80%" class="small cellText">
											<input type="text" id="asterisk_server_ip" name="asterisk_server_ip" class="small" style="width:30%" value="<?php echo $this->_tpl_vars['ASTERISK_SERVER_IP']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['ASTERISK_SERVER_IP_TITLE']; ?>
"/>
										</td>
                        			</tr>
			                        <tr>
										<td width="20%" nowrap class="small cellLabel"><strong><?php echo $this->_tpl_vars['MOD']['ASTERISK_PORT']; ?>
</strong></td>
                						<td width="80%" class="small cellText">
											<input type="text" id="asterisk_port" name="asterisk_port" class="small" style="width:30%" value="<?php echo $this->_tpl_vars['ASTERISK_PORT']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['ASTERISK_PORT_TITLE']; ?>
"/>
										</td>
									</tr>
                        			<tr>
                            			<td width="20%" nowrap class="small cellLabel"><strong><?php echo $this->_tpl_vars['MOD']['ASTERISK_USERNAME']; ?>
</strong></td>
                            			<td width="80%" class="small cellText">
											<input type="text" id="asterisk_username" name="asterisk_username" class="small" style="width:30%" value="<?php echo $this->_tpl_vars['ASTERISK_USERNAME']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['ASTERISK_USERNAME_TITLE']; ?>
"/>
										</td>
                        			</tr>
			                        <tr>
										<td width="20%" nowrap class="small cellLabel"><strong><?php echo $this->_tpl_vars['MOD']['ASTERISK_PASSWORD']; ?>
</strong></td>
                						<td width="80%" class="small cellText">
											<input type="password" id="asterisk_password" name="asterisk_password" class="small" style="width:30%" value="<?php echo $this->_tpl_vars['ASTERISK_PASSWORD']; ?>
" title="<?php echo $this->_tpl_vars['MOD']['ASTERISK_PASSWORD_TITLE']; ?>
"/>
										</td>
									</tr>
									<tr>
										<td width="20%" nowrap class="small cellLabel"><strong><?php echo $this->_tpl_vars['MOD']['ASTERISK_VERSION']; ?>
</strong></td>
                						<td width="80%" class="small cellText">
                							<select name="asterisk_version" id="asterisk_version" title="<?php echo $this->_tpl_vars['MOD']['ASTERISK_VERSION_TITLE']; ?>
">
                								<?php if ($this->_tpl_vars['ASTERISK_VERSION'] == '1.4'): ?>
													<option value="1.4" selected>1.4</option>
                                                    <option value="1.6">1.6</option>
													<option value="1.6">1.8</option>
                                               	<?php else: ?>
                                                	<option value="1.6" selected>1.8</option>
													<option value="1.6">1.6</option>
                                                    <option value="1.4">1.4</option>
												<?php endif; ?>
                							</select>
										</td>
									</tr>
									<tr>
										<td width="20%" nowrap colspan="2" align ="center">
											<input type="button" name="update" class="crmbutton small create" value="<?php echo $this->_tpl_vars['MOD']['LBL_UPDATE_BUTTON']; ?>
" onclick="validatefn1('asterisk');" />
											<input type="button" name="cancel" class="crmbutton small cancel" value="<?php echo $this->_tpl_vars['MOD']['LBL_CANCEL_BUTTON']; ?>
"  onClick="window.history.back();"/>
									    </td>
                        			</tr>
                        			</table>
									</td>
								</tr>                       
                       		</table>
      	        		</span>
                		<!-- asterisk ends :: can add another <span> for another SIP, say asterisk -->
                
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td valign="top">
			<img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
">
		</td>
	</tr>
</tbody>
</form>
</table>

<?php echo '
<script>

function setSoftphoneDetails(module){
	var asterisk_server_ip = document.getElementById("asterisk_server_ip").value;
	var asterisk_port = document.getElementById("asterisk_port").value;
	var asterisk_username = document.getElementById("asterisk_username").value;
	var asterisk_password = document.getElementById("asterisk_password").value;
	var asterisk_version = $(\'asterisk_version\').value;
	
	if(asterisk_port == ""){
		//port not specified :: so set default
		asterisk_port = "5038";
	}
	$("status").style.display="block";
	new Ajax.Request(
		\'index.php\',
		{queue: {position: \'end\', scope: \'command\'},
			method: \'post\',
			postBody: \'module=PBXManager&action=PBXManagerAjax&file=UpdatePBXDetails&ajax=true&qserver=\'+asterisk_server_ip+\'&qport=\'+asterisk_port+\'&qusername=\'+asterisk_username+\'&qpassword=\'+asterisk_password+\'&semodule=\'+module+\'&version=\'+asterisk_version,
			onComplete: function(response) {
				if((response.responseText != \'\')){
					alert(response.responseText);
				}else{
					window.history.back();	//successfully saved, so go back
				}		
				$("status").style.display="none";
		    }
		}
    );
}

function validatefn1(module){
	var asterisk_server_ip = document.getElementById("asterisk_server_ip").value;
	var asterisk_port = document.getElementById("asterisk_port").value;

	if (!emptyCheck("asterisk_server_ip","Asterisk Server","text")){
		return false;
	}
	if (!emptyCheck("asterisk_username","Asterisk Username","text")){
		return false;
	}
	if (!emptyCheck("asterisk_password","Asterisk Password","text")){
		return false;
	}
	setSoftphoneDetails(module);
}

</script>
'; ?>

