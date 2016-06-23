<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:07
         compiled from CreateView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtlib_purify', 'CreateView.tpl', 33, false),array('modifier', 'vtiger_imageurl', 'CreateView.tpl', 62, false),array('modifier', 'getTranslatedString', 'CreateView.tpl', 74, false),array('modifier', 'cat', 'CreateView.tpl', 80, false),)), $this); ?>


<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-<?php echo $this->_tpl_vars['CALENDAR_LANG']; ?>
.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<!-- overriding the pre-defined #company to avoid clash with vtiger_field in the view -->
<?php echo '
<style type=\'text/css\'>
#company {
	width: 90%;
	height: auto;
}
</style>
'; ?>


<script type="text/javascript">
var gVTModule = '<?php echo vtlib_purify($_REQUEST['module']); ?>
';
function sensex_info()
{
        var Ticker = $('tickersymbol').value;
        if(Ticker!='')
        {
                $("vtbusy_info").style.display="inline";
                new Ajax.Request(
                      'index.php',
                      {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=Tickerdetail&tickersymbol='+Ticker,
                                onComplete: function(response) {
                                        $('autocom').innerHTML = response.responseText;
                                        $('autocom').style.display="block";
                                        $("vtbusy_info").style.display="none";
                                }
                        }
                );
        }
}
</script>

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Buttons_List1.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
   <tr>
	<td valign=top>
		<img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
">
	</td>

	<td class="showPanelBg" valign=top width=100%>
	     	     <div class="small" style="padding:20px">
		
				<?php $this->assign('SINGLE_MOD_LABEL', $this->_tpl_vars['SINGLE_MOD']); ?>
		<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]): ?> <?php $this->assign('SINGLE_MOD_LABEL', $this->_tpl_vars['APP']['SINGLE_MOD']); ?> <?php endif; ?>
				
		 <?php if ($this->_tpl_vars['OP_MODE'] == 'edit_view'): ?>   
			 <span class="lvtHeaderText"><font color="purple">[ <?php echo $this->_tpl_vars['ID']; ?>
 ] </font><?php echo $this->_tpl_vars['NAME']; ?>
 -  <?php echo $this->_tpl_vars['APP']['LBL_EDITING']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['SINGLE_MOD'], $this->_tpl_vars['MODULE']); ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</span> <br>
			<?php echo $this->_tpl_vars['UPDATEINFO']; ?>
	 
		 <?php endif; ?>

		 <?php if ($this->_tpl_vars['OP_MODE'] == 'create_view'): ?>
			<?php if ($this->_tpl_vars['DUPLICATE'] != 'true'): ?>
			<?php $this->assign('create_new', ((is_array($_tmp='LBL_CREATING_NEW_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['SINGLE_MOD']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['SINGLE_MOD']))); ?>
								<?php $this->assign('create_newlabel', $this->_tpl_vars['APP'][$this->_tpl_vars['create_new']]); ?>
				<?php if ($this->_tpl_vars['create_newlabel'] != ''): ?>
					<span class="lvtHeaderText"><?php echo $this->_tpl_vars['create_newlabel']; ?>
</span> <br>
				<?php else: ?>
					<span class="lvtHeaderText"><?php echo $this->_tpl_vars['APP']['LBL_CREATING']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_NEW']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['SINGLE_MOD'], $this->_tpl_vars['MODULE']); ?>
</span> <br>
				<?php endif; ?>
		        
			<?php else: ?>
			<span class="lvtHeaderText"><?php echo $this->_tpl_vars['APP']['LBL_DUPLICATING']; ?>
 "<?php echo $this->_tpl_vars['NAME']; ?>
" </span> <br>
			<?php endif; ?>
		 <?php endif; ?>

		 <hr noshade size=1>
		 <br> 
		
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'EditViewHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		   <tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				   <tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>

					<?php if ($this->_tpl_vars['ADVBLOCKS'] != ''): ?>	
						<td width=75 style="width:15%" align="center" nowrap class="dvtSelectedCell" id="bi" onclick="fnLoadValues('bi','mi','basicTab','moreTab','normal','<?php echo $this->_tpl_vars['MODULE']; ?>
')"><b><?php echo $this->_tpl_vars['APP']['LBL_BASIC']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</b></td>
                    				<td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap id="mi" onclick="fnLoadValues('mi','bi','moreTab','basicTab','normal','<?php echo $this->_tpl_vars['MODULE']; ?>
')"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
 </b></td>
                   				<td class="dvtTabCache" style="width:65%" nowrap>&nbsp;</td>
					<?php else: ?>
						<td class="dvtSelectedCell" align=center nowrap><?php echo $this->_tpl_vars['APP']['LBL_BASIC']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</td>
	                                        <td class="dvtTabCache" style="width:65%">&nbsp;</td>
					<?php endif; ?>
				   <tr>
				</table>
			</td>
		   </tr>
		   <tr>
			<td valign=top align=left >

			    <!-- Basic Information Tab Opened -->
			    <div id="basicTab">

				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				   <tr>
					<td align=left>
					<!-- content cache -->
					
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
						   <tr>
							<td id ="autocom"></td>
						   </tr>
						   <tr>
							<td style="padding:10px">
							<!-- General details -->
								<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="center">
										<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
											<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php else: ?>
											<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php endif; ?>
										<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
									   </div>
									</td>
								   </tr>

								   <?php $_from = $this->_tpl_vars['BASBLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['data']):
?>
								   <tr>
									<?php if ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_ADDRESS_INFORMATION'] && ( $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Quotes' || $this->_tpl_vars['MODULE'] == 'PurchaseOrder' || $this->_tpl_vars['MODULE'] == 'SalesOrder' || $this->_tpl_vars['MODULE'] == 'Invoice' )): ?>
                                                                        <td colspan=2 class="detailedViewHeader">
                                                                        <b><?php echo $this->_tpl_vars['header']; ?>
</b></td>
                                                                        <td class="detailedViewHeader">
                                                                        <input name="cpy" onclick="return copyAddressLeft(EditView)" type="radio"><b><?php echo $this->_tpl_vars['APP']['LBL_RCPY_ADDRESS']; ?>
</b></td>
                                                                        <td class="detailedViewHeader">
                                                                        <input name="cpy" onclick="return copyAddressRight(EditView)" type="radio"><b><?php echo $this->_tpl_vars['APP']['LBL_LCPY_ADDRESS']; ?>
</b></td>

									<?php elseif ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_ADDRESS_INFORMATION'] && $this->_tpl_vars['MODULE'] == 'Contacts'): ?>
									<td colspan=2 class="detailedViewHeader">
                                                                        <b><?php echo $this->_tpl_vars['header']; ?>
</b></td>
                                                                        <td class="detailedViewHeader">
                                                                        <input name="cpy" onclick="return copyAddressLeft(EditView)" type="radio"><b><?php echo $this->_tpl_vars['APP']['LBL_CPY_OTHER_ADDRESS']; ?>
</b></td>
                                                                        <td class="detailedViewHeader">
                                                                        <input name="cpy" onclick="return copyAddressRight(EditView)" type="radio"><b><?php echo $this->_tpl_vars['APP']['LBL_CPY_MAILING_ADDRESS']; ?>
</b></td>
                                                                        <?php else: ?>
						         		<td colspan=4 class="detailedViewHeader">
                                                	        		<b><?php echo $this->_tpl_vars['header']; ?>
</b>
									<?php endif; ?>
							 		</td>
		                                        	   </tr>

								   <!-- Here we should include the uitype handlings-->
								   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DisplayFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>							
								   <tr style="height:25px"><td>&nbsp;</td></tr>
								   <?php endforeach; endif; unset($_from); ?>

								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="center">
										<?php if ($this->_tpl_vars['MODULE'] == 'Emails'): ?>
                                                                			<input title="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_KEY']; ?>
" class="crmbutton small create" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid=<?php echo $this->_tpl_vars['ENTITY_ID']; ?>
&entity=<?php echo $this->_tpl_vars['ENTITY_TYPE']; ?>
','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL']; ?>
">
                                                                			<input title="<?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
" accessKey="<?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
  " >
                                                                		<?php endif; ?>
										<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
											<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php else: ?>
                                                                		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php endif; ?>
                                                                		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
									   </div>
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
			    <!-- Basic Information Tab Closed -->

			    <!-- More Information Tab Opened -->
			    <div id="moreTab">
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				   <tr>
					<td align=left>
										
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
						   <tr>
							<td id ="autocom"></td>
						   </tr>
						   <tr>
							<td style="padding:10px">
							<!-- General details -->
								<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="center">
										<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
								<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php else: ?>
										<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php endif; ?>
                                                                 		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
									   </div>
									</td>
								   </tr>

								   <?php $_from = $this->_tpl_vars['ADVBLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['data']):
?>
								   <tr>
						         		<td colspan=4 class="detailedViewHeader">
                                                	        		<b><?php echo $this->_tpl_vars['header']; ?>
</b>
                                                         		</td>
                                                         	   </tr>

								   <!-- Here we should include the uitype handlings-->
                                                        	   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DisplayFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

							 	   <tr style="height:25px"><td>&nbsp;</td></tr>
								   <?php endforeach; endif; unset($_from); ?>

								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="center">
										<?php if ($this->_tpl_vars['MODULE'] == 'Emails'): ?>
                                                                			<input title="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_KEY']; ?>
" class="crmbutton small create" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid=<?php echo $this->_tpl_vars['ENTITY_ID']; ?>
&entity=<?php echo $this->_tpl_vars['ENTITY_TYPE']; ?>
','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL']; ?>
">
                                                                			<input title="<?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
" accessKey="<?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
  " >
                                                                		<?php endif; ?>
							<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
											<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php else: ?>
											<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										<?php endif; ?>
										<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
									   </div>
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
		   </tr>
		</table>
	     </div>
	</td>
	<td align=right valign=top><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
   </tr>
</table>
</form>

<?php if (( $this->_tpl_vars['MODULE'] == 'Emails' || $this->_tpl_vars['MODULE'] == 'Documents' ) && ( $this->_tpl_vars['USE_RTE'] == 'true' )): ?>
<script type="text/javascript" src="include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" defer="1">
	var textAreaName = null;
	<?php if ($this->_tpl_vars['MODULE'] == 'Documents'): ?>
		textAreaName = "notecontent";
	<?php else: ?>
		textAreaName = 'description';
	<?php endif; ?>

<!-- Solution for ticket #6756-->
	CKEDITOR.replace( textAreaName,
	{
		extraPlugins : 'uicolor',
		uiColor: '#dfdff1',
			on : {
				instanceReady : function( ev ) {
					 this.dataProcessor.writer.setRules( 'p',  {
						indent : false,
						breakBeforeOpen : false,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : false
				});
			}
		}
	});

	var oCKeditor = CKEDITOR.instances[textAreaName];
</script>
<?php endif; ?>
<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
<script>
	ScrollEffect.limit = 201;
	ScrollEffect.closelimit= 200;
</script>
<?php endif; ?>
<script>
        var fieldname = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDNAME']; ?>
)
        var fieldlabel = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDLABEL']; ?>
)
        var fielddatatype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDDATATYPE']; ?>
)
</script>

<!-- vtlib customization: Help information assocaited with the fields -->
<?php if ($this->_tpl_vars['FIELDHELPINFO']): ?>
<script type='text/javascript'>
<?php echo 'var fieldhelpinfo = {}; '; ?>

<?php $_from = $this->_tpl_vars['FIELDHELPINFO']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['FIELDHELPKEY'] => $this->_tpl_vars['FIELDHELPVAL']):
?>
	fieldhelpinfo["<?php echo $this->_tpl_vars['FIELDHELPKEY']; ?>
"] = "<?php echo $this->_tpl_vars['FIELDHELPVAL']; ?>
";
<?php endforeach; endif; unset($_from); ?>
</script>
<?php endif; ?>
<!-- END -->