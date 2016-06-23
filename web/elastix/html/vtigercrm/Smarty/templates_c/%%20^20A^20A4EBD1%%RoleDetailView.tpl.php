<?php /* Smarty version 2.6.18, created on 2014-09-02 13:44:58
         compiled from RoleDetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'RoleDetailView.tpl', 17, false),)), $this); ?>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>

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
				<form id="form" name="roleView" action="index.php" method="post" onsubmit="VtigerJS_DialogBox.block();">
				<input type="hidden" name="module" value="Settings">
				<input type="hidden" name="action" value="createrole">
				<input type="hidden" name="parenttab" value="Settings">
				<input type="hidden" name="returnaction" value="RoleDetailView">
				<input type="hidden" name="roleid" value="<?php echo $this->_tpl_vars['ROLEID']; ?>
">
				<input type="hidden" name="mode" value="edit">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="<?php echo vtiger_imageurl('ico-roles.gif', $this->_tpl_vars['THEME']); ?>
" width="48" height="48" border=0 ></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <a href="index.php?module=Settings&action=listroles&parenttab=Settings"><?php echo $this->_tpl_vars['CMOD']['LBL_ROLES']; ?>
</a> &gt; <?php echo $this->_tpl_vars['CMOD']['LBL_VIEWING']; ?>
 &quot;<?php echo $this->_tpl_vars['ROLE_NAME']; ?>
&quot; </b></td>
				</tr>
				<tr>
					<td valign=top class="small"><?php echo $this->_tpl_vars['CMOD']['LBL_VIEWING']; ?>
 <?php echo $this->_tpl_vars['CMOD']['LBL_PROPERTIES']; ?>
 &quot;<?php echo $this->_tpl_vars['ROLE_NAME']; ?>
&quot; <?php echo $this->_tpl_vars['MOD']['LBL_LIST_CONTACT_ROLE']; ?>
 </td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td valign=top>
					
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_PROPERTIES']; ?>
 &quot;<?php echo $this->_tpl_vars['ROLE_NAME']; ?>
&quot; </strong></td>
						<td><div align="right">
					 	    <input value="   <?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
   " title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmButton small edit" type="submit" name="Edit" >
						</div></td>
					  </tr>
					</table>
					<table width="100%"  border="0" cellspacing="0" cellpadding="5">
                      <tr class="small">
                        <td width="15%" class="small cellLabel"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_ROLE_NAME']; ?>
</strong></td>
                        <td width="85%" class="cellText" ><?php echo $this->_tpl_vars['ROLE_NAME']; ?>
</td>
                      </tr>
                      <tr class="small">
                        <td class="small cellLabel"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_REPORTS_TO']; ?>
</strong></td>
                        <td class="cellText"><?php echo $this->_tpl_vars['PARENTNAME']; ?>
</td>
                      </tr>
                      <tr class="small">
                        <td valign=top class="cellLabel"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_MEMBER']; ?>
</strong></td>
                        <td class="cellText">
						<table width="70%"  border="0" cellspacing="0" cellpadding="5">
                          <tr class="small">
                            		<td colspan="2" class="cellBottomDotLine">
						<div align="left"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_ASSOCIATED_PROFILES']; ?>
</strong></div>
					</td>
                            </tr>
			<?php $_from = $this->_tpl_vars['ROLEINFO']['profileinfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['elements']):
?>
                          <tr class="small">

                            <td width="16"><div align="center"></div></td>
                            <td>
										<a href="index.php?module=Settings&action=profilePrivileges&parenttab=Settings&profileid=<?php echo $this->_tpl_vars['elements']['0']; ?>
&mode=view"><?php echo $this->_tpl_vars['elements']['1']; ?>
</a><br>
			    </td>  	 
                          </tr>
			<?php endforeach; endif; unset($_from); ?>
   <tr class="small">
                            		<td colspan="2" class="cellBottomDotLine">
						<div align="left"><strong><?php echo $this->_tpl_vars['CMOD']['LBL_ASSOCIATED_USERS']; ?>
</strong></div>
					</td>
                            </tr>
				<?php if ($this->_tpl_vars['ROLEINFO']['userinfo']['0'] != ''): ?>
			<?php $_from = $this->_tpl_vars['ROLEINFO']['userinfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['elements']):
?>
                          <tr class="small">

                            <td width="16"><div align="center"></div></td>
                            <td>
				<a href="index.php?module=Users&action=DetailView&parenttab=Settings&record=<?php echo $this->_tpl_vars['elements']['0']; ?>
"><?php echo $this->_tpl_vars['elements']['1']; ?>
</a><br>
			    </td>  	 
                          </tr>
			<?php endforeach; endif; unset($_from); ?>	
			<?php endif; ?>
                        </table></td>
                      </tr>
                    </table>
					<br>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr><td class="small" nowrap align=right><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></td></tr>
					</table>
					
					
				</td>
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