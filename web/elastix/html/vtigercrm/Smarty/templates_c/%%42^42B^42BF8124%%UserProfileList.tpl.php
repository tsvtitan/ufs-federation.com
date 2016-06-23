<?php /* Smarty version 2.6.18, created on 2014-09-02 13:45:14
         compiled from UserProfileList.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'UserProfileList.tpl', 18, false),)), $this); ?>

<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<form action="index.php" method="post" name="new" id="form" onsubmit="VtigerJS_DialogBox.block();">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="action" value="CreateProfile">
<input type="hidden" name="parenttab" value="Settings">

<br>
	<div align=center>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SetMenu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="<?php echo vtiger_imageurl('ico-profile.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['MOD']['LBL_PROFILES']; ?>
" width="48" height="48" border=0 title="<?php echo $this->_tpl_vars['MOD']['LBL_PROFILES']; ?>
"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <?php echo $this->_tpl_vars['MOD']['LBL_PROFILES']; ?>
 </b></td>
				</tr>
				<tr>
					<td valign=top class="small"><?php echo $this->_tpl_vars['MOD']['LBL_PROFILE_DESCRIPTION']; ?>
</td>
				</tr>
				</table>
				
				
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td valign=top>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong><?php echo $this->_tpl_vars['MOD']['LBL_PROFILES_LIST']; ?>
</strong></td>
						<td class="small" align=right><?php echo $this->_tpl_vars['CMOD']['LBL_TOTAL']; ?>
 <?php echo $this->_tpl_vars['COUNT']; ?>
 <?php echo $this->_tpl_vars['MOD']['LBL_PROFILES']; ?>
 </td>
					</tr>
					</table>
					
					
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTableTopButtons">
					<tr>
						<td class=small align=right><input type="submit" value="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_PROFILE']; ?>
" title="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_PROFILE']; ?>
" class="crmButton create small"></td>
					</tr>
					</table>
						
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTable">
					<tr>
						<td class="colHeader small" valign=top width=2%><?php echo $this->_tpl_vars['LIST_HEADER']['0']; ?>
</td>
						<td class="colHeader small" valign=top width=8%><?php echo $this->_tpl_vars['LIST_HEADER']['1']; ?>
</td>
						<td class="colHeader small" valign=top width=30%><?php echo $this->_tpl_vars['LIST_HEADER']['2']; ?>
 </td>
						<td class="colHeader small" valign=top width=60%><?php echo $this->_tpl_vars['LIST_HEADER']['3']; ?>
</td>
					  </tr>
					 <?php $_from = $this->_tpl_vars['LIST_ENTRIES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profilelist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profilelist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['listvalues']):
        $this->_foreach['profilelist']['iteration']++;
?>
					<tr>
						<td class="listTableRow small" valign=top><?php echo $this->_foreach['profilelist']['iteration']; ?>
</td>
						<td class="listTableRow small" valign=top nowrap>
							<a href="index.php?module=Settings&action=profilePrivileges&return_action=ListProfiles&parenttab=Settings&mode=edit&profileid=<?php echo $this->_tpl_vars['listvalues']['profileid']; ?>
"><img src="<?php echo vtiger_imageurl('editfield.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EDIT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT']; ?>
" border="0" align="absmiddle"></a>
	                                                <?php if ($this->_tpl_vars['listvalues']['del_permission'] == 'yes'): ?>
        	                                                &nbsp;|&nbsp;
                	                                <a href="javascript:;"><img src="<?php echo vtiger_imageurl('delete.gif', $this->_tpl_vars['THEME']); ?>
" border="0" height="15" width="15" onclick="DeleteProfile(this,'<?php echo $this->_tpl_vars['listvalues']['profileid']; ?>
')" align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON']; ?>
"></a>
                                                	<?php else: ?>
                                                	<?php endif; ?>

						</td>
						<td class="listTableRow small" valign=top><a href="index.php?module=Settings&action=profilePrivileges&mode=view&parenttab=Settings&profileid=<?php echo $this->_tpl_vars['listvalues']['profileid']; ?>
"><b><?php echo $this->_tpl_vars['listvalues']['profilename']; ?>
</b></a></td>
						<td class="listTableRow small" valign=top><?php echo $this->_tpl_vars['listvalues']['description']; ?>
</td>
					  </tr>
					<?php endforeach; endif; unset($_from); ?>		
					
					</table>
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
	</table>
		
	</div>
</td>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
</form>
   </tr>
</tbody>
</table>
<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<script>
function DeleteProfile(obj,profileid)
{
        $("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody:'module=Users&action=UsersAjax&file=ProfileDeleteStep1&profileid='+profileid,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("tempdiv").innerHTML=response.responseText;
				fnvshobj(obj,"tempdiv");
                        }
                }
        );
}
</script>
