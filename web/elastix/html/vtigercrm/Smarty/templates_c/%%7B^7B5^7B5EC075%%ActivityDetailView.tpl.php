<?php /* Smarty version 2.6.18, created on 2014-09-02 13:49:02
         compiled from ActivityDetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'ActivityDetailView.tpl', 122, false),array('modifier', 'count', 'ActivityDetailView.tpl', 272, false),array('modifier', 'getTranslatedString', 'ActivityDetailView.tpl', 597, false),)), $this); ?>
<script type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/Calendar.js"></script>
<script type="text/javascript" src="include/js/reflection.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;"><?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
</a>
</span>

<script>
function tagvalidate()
{
	if(trim(document.getElementById('txtbox_tagfields').value) != '')
		SaveTag('txtbox_tagfields','<?php echo $this->_tpl_vars['ID']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
');	
	else
	{
		alert("<?php echo $this->_tpl_vars['APP']['PLEASE_ENTER_TAG']; ?>
");
		return false;
	}
}

<?php echo '
function setCoOrdinate(elemId)
{
	oBtnObj = document.getElementById(elemId);
	var tagName = document.getElementById(\'lstRecordLayout\');
	leftpos  = 0;
	toppos = 0;
	aTag = oBtnObj;
	do 
	{					  
	  leftpos  += aTag.offsetLeft;
	  toppos += aTag.offsetTop;
	} while(aTag = aTag.offsetParent);
	
	tagName.style.top= toppos + 20 + \'px\';
	tagName.style.left= leftpos - 276 + \'px\';
}

function getListOfRecords(obj, sModule, iId,sParentTab)
{
		new Ajax.Request(
		\'index.php\',
		{queue: {position: \'end\', scope: \'command\'},
			method: \'post\',
			postBody: \'module=Users&action=getListOfRecords&ajax=true&CurModule=\'+sModule+\'&CurRecordId=\'+iId+\'&CurParentTab=\'+sParentTab,
			onComplete: function(response) {
				sResponse = response.responseText;
				$("lstRecordLayout").innerHTML = sResponse;
				Lay = \'lstRecordLayout\';	
				var tagName = document.getElementById(Lay);
				var leftSide = findPosX(obj);
				var topSide = findPosY(obj);
				var maxW = tagName.style.width;
				var widthM = maxW.substring(0,maxW.length-2);
				var getVal = eval(leftSide) + eval(widthM);
				if(getVal  > document.body.clientWidth ){
					leftSide = eval(leftSide) - eval(widthM);
					tagName.style.left = leftSide + 230 + \'px\';
				}
				else
					tagName.style.left= leftSide + 388 + \'px\';
				
				setCoOrdinate(obj.id);
				
				tagName.style.display = \'block\';
				tagName.style.visibility = "visible";
			}
		}
	);
}

'; ?>


function DeleteTag(id,recordid)
{
        $("vtbusy_info").style.display="inline";
        Effect.Fade('tag_'+id);
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: "file=TagCloud&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=<?php echo $this->_tpl_vars['MODULE']; ?>
Ajax&ajxaction=DELETETAG&recordid="+recordid+"&tagid=" +id,
                        onComplete: function(response) {
                                                getTagCloud();
                                                $("vtbusy_info").style.display="none";
                        }
                }
        );
}

</script>

<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div> <!-- Code added by SAKTI on 17th Jun, 2008 -->

<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr><td>&nbsp;</td>
	<td>
                <table cellpadding="0" cellspacing="5" border="0">
		</table>	

<!-- Contents -->
<table  border="0" cellpadding="5" cellspacing="0" width="100%" >
<tr>
	<td style="border-bottom:1px dotted #cccccc">
	
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="left">		
					<span class="lvtHeaderText"><font color="purple">[ <?php echo $this->_tpl_vars['ID']; ?>
 ] </font><?php echo $this->_tpl_vars['NAME']; ?>
 -  <?php echo $this->_tpl_vars['SINGLE_MOD']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</span>&nbsp;&nbsp;<span class="small"><?php echo $this->_tpl_vars['UPDATEINFO']; ?>
</span>&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="<?php echo vtiger_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="<?php echo vtiger_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>
				</td>
				<td align="right">		
					<?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted'): ?>
					<input title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small edit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
&nbsp;">&nbsp;
					<?php endif; ?>
					
					<?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted'): ?>
					<input title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_KEY']; ?>
" class="crmbutton small create" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>
">&nbsp;
                    <?php endif; ?>
                    
                    <?php if ($this->_tpl_vars['DELETE'] == 'permitted'): ?>
					<input title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; <?php if ($this->_tpl_vars['VIEWTYPE'] == 'calendar'): ?> DetailView.return_action.value='index'; <?php else: ?> DetailView.return_action.value='ListView'; <?php endif; ?>  submitFormForActionWithConfirmation('DetailView', 'Delete', '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
');" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">&nbsp;
					<?php endif; ?>
					
					<?php if ($this->_tpl_vars['privrecord'] != ''): ?>
					<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['privrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
'" name="privrecord" value="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo vtiger_imageurl('rec_prev.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp;
					<?php else: ?>
					<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo vtiger_imageurl('rec_prev_disabled.gif', $this->_tpl_vars['THEME']); ?>
">
					<?php endif; ?>
					
					<?php if ($this->_tpl_vars['privrecord'] != '' || $this->_tpl_vars['nextrecord'] != ''): ?>
					<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" onclick="var obj = this;var lhref = getListOfRecords(obj, '<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['CATEGORY']; ?>
');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="<?php echo vtiger_imageurl('rec_jump.gif', $this->_tpl_vars['THEME']); ?>
">
					<?php endif; ?>
					&nbsp;
					<?php if ($this->_tpl_vars['nextrecord'] != ''): ?>
					<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['nextrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
'" name="nextrecord" src="<?php echo vtiger_imageurl('rec_next.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp;
					<?php else: ?>
					<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" src="<?php echo vtiger_imageurl('rec_next_disabled.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp;
					<?php endif; ?>
				</td>
			</tr>
		 </table>
	</td>
</tr>
<tr><td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign=top align=left >
			<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr valign=top>
					<td align=left>					
					<form action="index.php" method="post" name="DetailView" id="form" onsubmit="VtigerJS_DialogBox.block();">
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'DetailViewHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					<!-- content cache -->
					
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                  <tr>
					     <td style="padding:3px">
						     <!-- General details -->
						     <?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>
							     <?php if ($this->_tpl_vars['header'] != $this->_tpl_vars['APP']['LBL_CUSTOM_INFORMATION']): ?>
						     <table border=0 cellspacing=0 cellpadding=5 width=100% class="small">
						     	<tr><?php echo '<td colspan=4 class="tableHeading"><b>'; ?><?php echo $this->_tpl_vars['header']; ?><?php echo '</b></td>'; ?>

					             	</tr>
						     </table>
							     <?php endif; ?>
						     <?php endforeach; endif; unset($_from); ?>
						     <?php if ($this->_tpl_vars['ACTIVITYDATA']['activitytype'] != 'Task'): ?>	
							 <!-- display of fields starts -->
						     <table border=0 cellspacing=0 cellpadding=5 width=100% >
               						 <tr>
								<?php if ($this->_tpl_vars['LABEL']['activitytype'] != ''): ?>
								<?php $this->assign('type', $this->_tpl_vars['ACTIVITYDATA']['activitytype']); ?>
								<td class="cellLabel" width="20%" align="right"><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTTYPE']; ?>
</b></td>
								<td class="cellInfo" width="30%"align="left"><?php echo $this->_tpl_vars['type']; ?>
</td>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['LABEL']['visibility'] != ''): ?>
								<?php $this->assign('vblty', $this->_tpl_vars['ACTIVITYDATA']['visibility']); ?>
								<td class="cellLabel" width="20%" align="right"><b><?php echo $this->_tpl_vars['LABEL']['visibility']; ?>
</b></td>
                                                                <td class="cellInfo" width="30%" align="left" ><?php echo $this->_tpl_vars['vblty']; ?>
</td>
								<?php endif; ?>
							 </tr>
							 <tr>
                        					<td class="cellLabel" align="right"><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTNAME']; ?>
</b></td>
					                        <td class="cellInfo" colspan=3 align="left" ><?php echo $this->_tpl_vars['ACTIVITYDATA']['subject']; ?>
</td>
             						 </tr>
							 <?php if ($this->_tpl_vars['LABEL']['description'] != ''): ?>
							 <tr>
								<td class="cellLabel" align="right" nowrap valign="top"><b><?php echo $this->_tpl_vars['LABEL']['description']; ?>
</b></td>
								<td class="cellInfo" valign="top" align="left" colspan="3" height="60px"><?php echo $this->_tpl_vars['ACTIVITYDATA']['description']; ?>
&nbsp;</td>
							 </tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['LABEL']['location'] != ''): ?>
							<tr>
								<td class="cellLabel" align="right" valign="top"><b><?php echo $this->_tpl_vars['LABEL']['location']; ?>
</b></td>
								<td class="cellInfo" colspan=3 align="left" ><?php echo $this->_tpl_vars['ACTIVITYDATA']['location']; ?>
&nbsp;</td>
							</tr>
							<?php endif; ?>	
							 <tr>
								<?php if ($this->_tpl_vars['LABEL']['eventstatus'] != ''): ?>
								<td class="cellLabel" align="right" nowrap valign="top"><b><?php echo $this->_tpl_vars['LABEL']['eventstatus']; ?>
</b></td>
								<td class="cellInfo" align="left" nowrap valign="top">
									<?php if ($this->_tpl_vars['ACTIVITYDATA']['eventstatus'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
										<font color="red"><?php echo $this->_tpl_vars['ACTIVITYDATA']['eventstatus']; ?>
</font>
										<?php else: ?>
											<?php echo $this->_tpl_vars['ACTIVITYDATA']['eventstatus']; ?>

									<?php endif; ?>
								</td>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['LABEL']['assigned_user_id'] != ''): ?>
								<td class="cellLabel" align="right" nowrap valign="top"><b><?php echo $this->_tpl_vars['LABEL']['assigned_user_id']; ?>
</b></td>
								<td class="cellInfo" align="left" nowrap valign="top"><?php echo $this->_tpl_vars['ACTIVITYDATA']['assigned_user_id']; ?>
</td>
								<?php endif; ?>
                                                         </tr>
							<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != '' || $this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
							 <tr>
								<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != ''): ?>
                                                                <td class="cellLabel" align="right" nowrap valign="top"><b><?php echo $this->_tpl_vars['LABEL']['taskpriority']; ?>
</b></td>
                                                                <td class="cellInfo" align="left" nowrap valign="top">
									<?php if ($this->_tpl_vars['ACTIVITYDATA']['taskpriority'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
										<font color="red" ><?php echo $this->_tpl_vars['ACTIVITYDATA']['taskpriority']; ?>
</font>
									<?php else: ?>
										<?php echo $this->_tpl_vars['ACTIVITYDATA']['taskpriority']; ?>

									<?php endif; ?>
								</td>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
                                                                <td class="cellLabel" align="right" nowrap valign="top"><b><?php echo $this->_tpl_vars['LABEL']['sendnotification']; ?>
</b></td>
                                                                <td class="cellInfo" align="left" nowrap valign="top"><?php echo $this->_tpl_vars['ACTIVITYDATA']['sendnotification']; ?>
</td>
								<?php endif; ?>
                                                         </tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['LABEL']['createdtime'] != '' || $this->_tpl_vars['LABEL']['modifiedtime'] != ''): ?>
                                                         <tr>
                                                                <td class="cellLabel" align="right" nowrap valign="top"align="right"><?php if ($this->_tpl_vars['LABEL']['createdtime'] != ''): ?><b><?php echo $this->_tpl_vars['LABEL']['createdtime']; ?>
</b><?php endif; ?></td>
                                                                <td class="cellInfo" align="left" nowrap valign="top"><?php if ($this->_tpl_vars['LABEL']['createdtime'] != ''): ?><?php echo $this->_tpl_vars['ACTIVITYDATA']['createdtime']; ?>
<?php endif; ?></td>
                                                                <td class="cellLabel" align="right" nowrap valign="top"align="right"><?php if ($this->_tpl_vars['LABEL']['modifiedtime'] != ''): ?><b><?php echo $this->_tpl_vars['LABEL']['modifiedtime']; ?>
</b><?php endif; ?></td>
                                                                <td class="cellInfo" align="left" nowrap valign="top"><?php if ($this->_tpl_vars['LABEL']['modifiedtime'] != ''): ?><?php echo $this->_tpl_vars['ACTIVITYDATA']['modifiedtime']; ?>
<?php endif; ?></td>
                                                         </tr>
							<?php endif; ?>
						     </table>
						     <table border=0 cellspacing=1 cellpadding=0 width=100%>
							<tr><td width=50% valign=top >
								<table border=0 cellspacing=0 cellpadding=2 width=100%>
                                                                        <tr><td class="mailSubHeader"><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTSTAT']; ?>
</b></td></tr>
                                                                        <tr><td class=small><?php echo $this->_tpl_vars['ACTIVITYDATA']['starthr']; ?>
:<?php echo $this->_tpl_vars['ACTIVITYDATA']['startmin']; ?>
<?php echo $this->_tpl_vars['ACTIVITYDATA']['startfmt']; ?>
</td></tr>
                                                                        <tr><td class=small><?php echo $this->_tpl_vars['ACTIVITYDATA']['date_start']; ?>
</td></tr>
                                                                </table></td>
							<td width=50% valign=top >
                                                                <table border=0 cellspacing=0 cellpadding=2 width=100%>
                                                                        <tr><td  class="mailSubHeader"><b><?php echo $this->_tpl_vars['MOD']['LBL_EVENTEDAT']; ?>
</b></td></tr>
                                                                        <tr><td class=small><?php echo $this->_tpl_vars['ACTIVITYDATA']['endhr']; ?>
:<?php echo $this->_tpl_vars['ACTIVITYDATA']['endmin']; ?>
<?php echo $this->_tpl_vars['ACTIVITYDATA']['endfmt']; ?>
</td></tr>
                                                                        <tr><td class=small><?php echo $this->_tpl_vars['ACTIVITYDATA']['due_date']; ?>
</td></tr>
                                                                </table>
                                                        </td></tr>
                                                     </table>
							<?php if (count($this->_tpl_vars['CUSTOM_FIELDS_DATA']) > 0): ?>
	                             <table border=0 cellspacing=0 cellpadding=5 width=100% >
	                             	<tr><?php echo '<td colspan=4 class="tableHeading"><b>'; ?><?php echo $this->_tpl_vars['APP']['LBL_CUSTOM_INFORMATION']; ?><?php echo '</b></td>'; ?>

						          	</tr>
						          	<tr>
						          		<?php $_from = $this->_tpl_vars['CUSTOM_FIELDS_DATA']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['custom_field']):
?>
						          		<?php $this->assign('keyid', $this->_tpl_vars['custom_field']['2']); ?>
						          		<?php $this->assign('keyval', $this->_tpl_vars['custom_field']['1']); ?>
						          		<?php $this->assign('keyfldname', $this->_tpl_vars['custom_field']['0']); ?>
						          		<?php $this->assign('keyoptions', $this->_tpl_vars['custom_field']['options']); ?>
						          		<td class="cellLabel" align="right" width="20%"><b><?php echo $this->_tpl_vars['keyfldname']; ?>
</b></td>
						          		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewFields.tpl", 'smarty_include_vars' => array()));
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
                             
                             							<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && ! empty ( $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWWIDGET'] )): ?>
							<table border=0 cellspacing=0 cellpadding=5 width=100% >
							<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWWIDGET']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOM_LINK_DETAILVIEWWIDGET']):
?>
								<?php if (preg_match ( "/^block:\/\/.*/" , $this->_tpl_vars['CUSTOM_LINK_DETAILVIEWWIDGET']->linkurl )): ?>
								<tr>
									<td style="padding:5px;" >
									<?php 
										echo vtlib_process_widget($this->_tpl_vars['CUSTOM_LINK_DETAILVIEWWIDGET'], $this->_tpl_vars);
									 ?>
									</td>
								</tr>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
							</table>
							<?php endif; ?>
														    
						     <br>
					             <table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
                					 <tr>
                        					<td>
                         				        	<table border=0 cellspacing=0 cellpadding=3 width=100%>
                             						<tr>
                                        					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					                                        <td id="cellTabInvite" class="dvtSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','on');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','off');ghide('addEventAlarmUI');dispLayer('addEventInviteUI');ghide('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_INVITE']; ?>
</a></td>
										<td class="dvtTabCache" style="width:10px">&nbsp;</td>
										<?php if ($this->_tpl_vars['LABEL']['reminder_time'] != ''): ?>
										<td id="cellTabAlarm" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','on');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','off');dispLayer('addEventAlarmUI');ghide('addEventInviteUI');ghide('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_REMINDER']; ?>
</a></td>
										<?php endif; ?>
										<td class="dvtTabCache" style="width:10px">&nbsp;</td>
										<?php if ($this->_tpl_vars['LABEL']['recurringtype'] != ''): ?>
										<td id="cellTabRepeat" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','on');switchClass('cellTabRelatedto','off');ghide('addEventAlarmUI');ghide('addEventInviteUI');dispLayer('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_REPEAT']; ?>
</a></td>
										<?php endif; ?>
										<td class="dvtTabCache" style="width:10px">&nbsp;</td>
										<td id="cellTabRelatedto" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','on');ghide('addEventAlarmUI');ghide('addEventInviteUI');dispLayer('addEventRelatedtoUI');ghide('addEventRepeatUI');"><?php echo $this->_tpl_vars['MOD']['LBL_LIST_RELATED_TO']; ?>
</a></td>
										<td class="dvtTabCache" style="width:100%">&nbsp;</td>
									</tr>
									</table>
								</td>
							 </tr>
							 
							 <tr>
								<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px;height:120px">
									<!-- Invite UI -->
									<DIV id="addEventInviteUI" style="display:block;width:100%">
									<table width="100%" cellpadding="5" cellspacing="0" border="0">
										<tr>
                                                                                        <td width="30%" valign="top" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_USERS']; ?>
</b></td>
                                                                                        <td width="70%" align=left valign="top" >
												<?php $_from = $this->_tpl_vars['INVITEDUSERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['userid'] => $this->_tpl_vars['username']):
?>
                                                                                        	        <?php echo $this->_tpl_vars['username']; ?>
<br>
                                                                                                <?php endforeach; endif; unset($_from); ?>
											</td>
                                                                                </tr>
									</table>
									</DIV>
									<!-- Reminder UI -->
					                                <DIV id="addEventAlarmUI" style="display:none;width:100%">
									<?php if ($this->_tpl_vars['LABEL']['reminder_time'] != ''): ?>
									<table width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                                <tr>
                                                                                        <td width="30%" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_SENDREMINDER']; ?>
</b></td>
                                                                                        <td width="70%" align=left><?php echo $this->_tpl_vars['ACTIVITYDATA']['set_reminder']; ?>
</td>
                                                                                </tr>
										<?php if ($this->_tpl_vars['ACTIVITYDATA']['set_reminder'] != 'No'): ?>
										<tr>
                                                                                        <td width="30%" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_RMD_ON']; ?>
</b></td>
											<td width="70%" align=left><?php echo $this->_tpl_vars['ACTIVITYDATA']['reminder_str']; ?>
</td>
										</tr>
										<?php endif; ?>
                                                                        </table>
									<?php endif; ?>
									</DIV>
									<!-- Repeat UI -->
                                					<div id="addEventRepeatUI" style="display:none;width:100%">
									<?php if ($this->_tpl_vars['LABEL']['recurringtype'] != ''): ?>
									<table width="100%" cellpadding="5" cellspacing="0" border="0">
										<tr>
                                                                                        <td width="30%" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_ENABLE_REPEAT']; ?>
</b></td>
                                                                                        <td width="70%" align=left><?php echo $this->_tpl_vars['ACTIVITYDATA']['recurringcheck']; ?>
</td>
                                                                                </tr>
										<?php if ($this->_tpl_vars['ACTIVITYDATA']['recurringcheck'] != 'No'): ?>
										<tr>
											<td width="30%" align=right>&nbsp;</td>
											<td><?php echo $this->_tpl_vars['MOD']['LBL_REPEATEVENT']; ?>
&nbsp;<?php echo $this->_tpl_vars['ACTIVITYDATA']['repeat_frequency']; ?>
&nbsp;<?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['ACTIVITYDATA']['recurringtype']]; ?>
</td>
										</tr>
										<tr>
                                                                                        <td width="30%" align=right>&nbsp;</td>
                                                                                        <td><?php echo $this->_tpl_vars['ACTIVITYDATA']['repeat_str']; ?>
</td>
                                                                                </tr>
										<?php endif; ?>
									</table>
									<?php endif; ?>
									</div>
									<!-- Relatedto UI -->
									<div id="addEventRelatedtoUI" style="display:none;width:100%">
									<table width="100%" cellpadding="5" cellspacing="0" border="0">
										<?php if ($this->_tpl_vars['LABEL']['parent_id'] != ''): ?>
										<tr>
											<td width="30%" align=right valign="top"><b><?php echo $this->_tpl_vars['LABEL']['parent_id']; ?>
</b></td>
											<td width="70%" align=left valign="top"><?php echo $this->_tpl_vars['ACTIVITYDATA']['parent_name']; ?>
</td>
										</tr>
										<?php endif; ?>
										<tr>
											<td width="30%" valign="top" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_CONTACT_NAME']; ?>
</b></td>	
											<td width="70%" valign="top" align=left>
											<?php $_from = $this->_tpl_vars['CONTACTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cntid'] => $this->_tpl_vars['contactname']):
?>
	                                        	<?php echo $this->_tpl_vars['contactname']['0']; ?>

	                                            <?php if ($this->_tpl_vars['IS_PERMITTED_CNT_FNAME'] == '0'): ?>
	                                            	&nbsp;<?php echo $this->_tpl_vars['contactname']['1']; ?>

	                                            <?php endif; ?>
	                                            <br>
                                            <?php endforeach; endif; unset($_from); ?>
										</tr>
									</table>
									</div>
								</td>
                					 </tr>
						     </table>
						    <?php else: ?>
							<!-- detailed view of a ToDo -->
					 	     <table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td class="cellLabel" width="20%" align="right"><b><?php echo $this->_tpl_vars['MOD']['LBL_TODO']; ?>
</b></td>
								<td class="cellInfo" width="80%" align="left"><?php echo $this->_tpl_vars['ACTIVITYDATA']['subject']; ?>
</td>
							</tr>
							<?php if ($this->_tpl_vars['LABEL']['description'] != ''): ?>
							<tr>
								<td class="cellLabel" align="right" valign="top"><b><?php echo $this->_tpl_vars['LABEL']['description']; ?>
</b></td>
                                                                <td class="cellInfo" align="left" colspan="3" valign="top" height="60px"><?php echo $this->_tpl_vars['ACTIVITYDATA']['description']; ?>
&nbsp;</td>
                					</tr>
							<?php endif; ?>
							<tr>
                        					<td colspan="2" align="center" style="padding:0px">
                                				<table border="0" cellpadding="5" cellspacing="1" width="100%" >
                                       					<tr>
										<?php if ($this->_tpl_vars['LABEL']['taskstatus'] != ''): ?>
                                                					<td class="cellLabel" width=33% align="left"><b><?php echo $this->_tpl_vars['LABEL']['taskstatus']; ?>
</b></td>
										<?php endif; ?>
										<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != ''): ?>
											<td class="cellLabel" width=33% align="left"><b><?php echo $this->_tpl_vars['LABEL']['taskpriority']; ?>
</b></td>
										<?php endif; ?>
										<td class="cellLabel" width=34% align="left"><b><?php echo $this->_tpl_vars['LABEL']['assigned_user_id']; ?>
</b></td>
									</tr>
									<tr>
										<?php if ($this->_tpl_vars['LABEL']['taskstatus'] != ''): ?>
											<td class="cellInfo" align="left" valign="top">
											<?php if ($this->_tpl_vars['ACTIVITYDATA']['taskstatus'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                                                                                	<font color="red"><?php echo $this->_tpl_vars['ACTIVITYDATA']['taskstatus']; ?>
</font>
											<?php else: ?> <?php echo $this->_tpl_vars['ACTIVITYDATA']['taskstatus']; ?>
<?php endif; ?>
                                                					</td>
										<?php endif; ?>
										<?php if ($this->_tpl_vars['LABEL']['taskpriority'] != ''): ?>		
											<td class="cellInfo" align="left" valign="top">
											<?php if ($this->_tpl_vars['ACTIVITYDATA']['taskpriority'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
											<font color="red"><?php echo $this->_tpl_vars['ACTIVITYDATA']['taskpriority']; ?>
</font>
											<?php else: ?><?php echo $this->_tpl_vars['ACTIVITYDATA']['taskpriority']; ?>
<?php endif; ?>
											</td>
										<?php endif; ?>
										<td class="cellInfo" align="left" valign="top"><?php echo $this->_tpl_vars['ACTIVITYDATA']['assigned_user_id']; ?>
</td>
									</tr>
								</table>
								</td>
							</tr>
						     </table>
						     <table border="0" cellpadding="0" cellspacing="0" width="100%" align=center>
	                                                <tr><td width=50% valign=top >
								<table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
									<tr><td class="mailSubHeader" align=left ><b><?php echo $this->_tpl_vars['MOD']['LBL_TIMEDATE']; ?>
</b></td></tr>
									<tr><td class="small" ><?php echo $this->_tpl_vars['ACTIVITYDATA']['starthr']; ?>
:<?php echo $this->_tpl_vars['ACTIVITYDATA']['startmin']; ?>
<?php echo $this->_tpl_vars['ACTIVITYDATA']['startfmt']; ?>
</td></tr>
									<tr><td class="cellInfo" style="padding-left:0px"><?php echo $this->_tpl_vars['ACTIVITYDATA']['date_start']; ?>
</td></tr>
								</table>
							</td>
							<td width=50% valign="top">
								<table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
									<tr><td class="mailSubHeader"><b><?php echo $this->_tpl_vars['LABEL']['due_date']; ?>
</b></td></tr>
									<tr><td class="small"><?php echo $this->_tpl_vars['ACTIVITYDATA']['due_date']; ?>
</td></tr>
									<tr><td class="cellInfo">&nbsp;</td></tr>
								</table>
							</td>
						     </table>	
						     <table border=0 cellspacing=0 cellpadding=5 width=100% >
							<tr>
								<td class="cellLabel" align=right nowrap width=20%><?php if ($this->_tpl_vars['LABEL']['createdtime'] != ''): ?><b><?php echo $this->_tpl_vars['LABEL']['createdtime']; ?>
</b><?php endif; ?></td>
                                                                <td class="cellInfo" align=left nowrap width=30%><?php if ($this->_tpl_vars['LABEL']['createdtime'] != ''): ?><?php echo $this->_tpl_vars['ACTIVITYDATA']['createdtime']; ?>
<?php endif; ?></td>
                                                                <td class="cellLabel" align=right nowrap width=20%><?php if ($this->_tpl_vars['LABEL']['modifiedtime'] != ''): ?><b><?php echo $this->_tpl_vars['LABEL']['modifiedtime']; ?>
</b><?php endif; ?></td>
                                                                <td class="cellInfo" align=left  nowrap width=30%><?php if ($this->_tpl_vars['LABEL']['modifiedtime'] != ''): ?><?php echo $this->_tpl_vars['ACTIVITYDATA']['modifiedtime']; ?>
<?php endif; ?></td>
                                                        </tr>
                                                     </table>

							<?php if (count($this->_tpl_vars['CUSTOM_FIELDS_DATA']) > 0): ?>
	                             <table border=0 cellspacing=0 cellpadding=5 width=100% >
	                             	<tr><?php echo '<td colspan=4 class="tableHeading"><b>'; ?><?php echo $this->_tpl_vars['APP']['LBL_CUSTOM_INFORMATION']; ?><?php echo '</b></td>'; ?>

						          	</tr>
						          	<tr>
						          		<?php $_from = $this->_tpl_vars['CUSTOM_FIELDS_DATA']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['custom_field']):
?>
						          		<?php $this->assign('keyid', $this->_tpl_vars['custom_field']['2']); ?>
						          		<?php $this->assign('keyval', $this->_tpl_vars['custom_field']['1']); ?>
						          		<?php $this->assign('keyfldname', $this->_tpl_vars['custom_field']['0']); ?>
						          		<?php $this->assign('keyoptions', $this->_tpl_vars['custom_field']['options']); ?>
						          		<td class="cellLabel" align="right" width="20%"><b><?php echo $this->_tpl_vars['keyfldname']; ?>
</b></td>
						          		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewFields.tpl", 'smarty_include_vars' => array()));
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

						     <?php if ($this->_tpl_vars['LABEL']['sendnotification'] != '' || ( $this->_tpl_vars['LABEL']['parent_id'] != '' ) || ( $this->_tpl_vars['LABEL']['contact_id'] != '' )): ?> 
						     <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
									<table border="0" cellpadding="3" cellspacing="0" width="100%">
									<tr>
										<td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
										<?php if ($this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
                                                                                        <?php $this->assign('class_val', 'dvtUnSelectedCell'); ?>
	                                                                                <td id="cellTabInvite" class="dvtSelectedCell" align="center" nowrap="nowrap"><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','on');switchClass('cellTabRelatedto','off');dispLayer('addTaskAlarmUI');ghide('addTaskRelatedtoUI');"><?php echo $this->_tpl_vars['MOD']['LBL_NOTIFICATION']; ?>
</td></a></td>
										<?php else: ?>
                                                                                        <?php $this->assign('class_val', 'dvtSelectedCell'); ?>
                                                                                <?php endif; ?>
										<td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
										<?php if (( $this->_tpl_vars['LABEL']['parent_id'] != '' ) || ( $this->_tpl_vars['LABEL']['contact_id'] != '' )): ?>
                                                                                <td id="cellTabRelatedto" class=<?php echo $this->_tpl_vars['class_val']; ?>
 align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabRelatedto','on');dispLayer('addTaskRelatedtoUI');ghide('addTaskAlarmUI');"><?php echo $this->_tpl_vars['MOD']['LBL_RELATEDTO']; ?>
</a></td>
										<?php endif; ?>

                                                                                <td class="dvtTabCache" style="width: 100%;">&nbsp;</td>
									</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px;height:120px">
                                                                <!-- Notification UI -->
                                                                        <DIV id="addTaskAlarmUI" style="display:block;width:100%">
									<?php if ($this->_tpl_vars['LABEL']['sendnotification'] != ''): ?>
									<?php $this->assign('vision', 'none'); ?>
                                                                        <table width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                                <tr>
                                                                                        <td width="30%" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_SENDNOTIFICATION']; ?>
</b></td>
                                                                                        <td width="70%" align=left><?php echo $this->_tpl_vars['ACTIVITYDATA']['sendnotification']; ?>
</td>
                                                                                </tr>
                                                                        </table>
									<?php else: ?>
                                                                        <?php $this->assign('vision', 'block'); ?>
                                                                        <?php endif; ?>
                                                                        </DIV>
									<div id="addTaskRelatedtoUI" style="display:<?php echo $this->_tpl_vars['vision']; ?>
;width:100%">
									<table width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                                <tr>
										<?php if ($this->_tpl_vars['LABEL']['parent_id'] != ''): ?>
                                                                                        <td width="30%" align=right><b><?php echo $this->_tpl_vars['LABEL']['parent_id']; ?>
</b></td>
                                                                                        <td width="70%" align=left><?php echo $this->_tpl_vars['ACTIVITYDATA']['parent_name']; ?>
</td>
										<?php endif; ?>
                                                                                </tr>
                                                                                <tr>
										<?php if ($this->_tpl_vars['LABEL']['contact_id'] != ''): ?>
                                                                                        <td width="30%" align=right><b><?php echo $this->_tpl_vars['MOD']['LBL_CONTACT_NAME']; ?>
</b></td>
											<td width="70%" align=left><a href="<?php echo $this->_tpl_vars['ACTIVITYDATA']['contact_idlink']; ?>
"><?php echo $this->_tpl_vars['ACTIVITYDATA']['contact_id']; ?>
</a></td>
										<?php endif; ?>
                                                                                </tr>
                                                                        </table>
                                                                        </div>
								</td>
							</tr>
						     </table>
						     <?php endif; ?>

                     	                      </td>
					   </tr>
                </table>
		<?php endif; ?>
	</table>
	</form>
	</td>
	<td width=22% valign=top style="border-left:2px dashed #cccccc;padding:13px">
						<!-- right side relevant info -->
					<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWBASIC']): ?>
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWBASIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
				<tr>
					<td align="left" style="padding-left:10px;">
						<?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
						<?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
						<?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
							<?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
						<?php else: ?>
														<?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['CUSTOMLINK']->linkicon): ?>
						<a class="webMnu" href="<?php echo $this->_tpl_vars['customlink_href']; ?>
"><img hspace=5 align="absmiddle" border=0 src="<?php echo $this->_tpl_vars['CUSTOMLINK']->linkicon; ?>
"></a>
						<?php endif; ?>
						<a class="webMnu" href="<?php echo $this->_tpl_vars['customlink_href']; ?>
"><?php echo $this->_tpl_vars['customlink_label']; ?>
</a>
					</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
			<?php endif; ?>
			
						<?php if ($this->_tpl_vars['CUSTOM_LINKS'] && $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEW']): ?>
				<br>
				<?php if (! empty ( $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEW'] )): ?>					
					<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr><td align="left" class="dvtUnSelectedCell dvtCellLabel">
							<a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 &#187;</b></a>
						</td></tr>
					</table>
					<br>
					<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay" 
						onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
						<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 &#187;</b></td></tr>
						<tr>
							<td>
								<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEW']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
			<?php endif; ?>
		
		<?php if ($this->_tpl_vars['TAG_CLOUD_DISPLAY'] == 'true'): ?>
		<!-- Tag cloud display -->
		<table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">
			<tr>
				<td class="tagCloudTopBg"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tagCloudName.gif" border=0></td>
			</tr>
			<tr>
				<td><div id="tagdiv" style="display:visible;"><form method="POST" action="javascript:void(0);" onsubmit="return tagvalidate();"><input class="textbox"  type="text" id="txtbox_tagfields" name="textbox_First Name" value="" style="width:100px;margin-left:5px;"></input>&nbsp;&nbsp;<input name="button_tagfileds" type="submit" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_TAG_IT']; ?>
" /></form></div></td>
			</tr>
			<tr>
				<td class="tagCloudDisplay" valign=top> <span id="tagfields"><?php echo $this->_tpl_vars['ALL_TAG']; ?>
</span></td>
			</tr>
		</table>
		<!-- End Tag cloud display -->
		<?php endif; ?>
		
		<?php if (! empty ( $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWWIDGET'] )): ?>
		<?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWWIDGET']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK_NO'] => $this->_tpl_vars['CUSTOMLINK']):
?>
			<?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
			<?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
						<?php if (! preg_match ( "/^block:\/\/.*/" , $this->_tpl_vars['customlink_href'] )): ?>
				<?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
					<?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
				<?php else: ?>
										<?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
				<?php endif; ?>
				<br/>
				<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
	  				<tr>
						<td class="rightMailMergeHeader">
							<b><?php echo $this->_tpl_vars['customlink_label']; ?>
</b>
							<img id="detailview_block_<?php echo $this->_tpl_vars['CUSTOMLINK_NO']; ?>
_indicator" style="display:none;" src="<?php echo vtiger_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="absmiddle" />
						</td>
	  				</tr>
	  				<tr style="height:25px">
						<td class="rightMailMergeContent"><div id="detailview_block_<?php echo $this->_tpl_vars['CUSTOMLINK_NO']; ?>
"></div></td>
	  				</tr>
	  				<script type="text/javascript">
	  					vtlib_loadDetailViewWidget("<?php echo $this->_tpl_vars['customlink_href']; ?>
", "detailview_block_<?php echo $this->_tpl_vars['CUSTOMLINK_NO']; ?>
", "detailview_block_<?php echo $this->_tpl_vars['CUSTOMLINK_NO']; ?>
_indicator");
	  				</script>
				</table>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		
		<br>
	</td>
</tr>
</table>
		
		</div>
		<!-- PUBLIC CONTENTS STOPS-->
	</td>
</tr>
<tr>
	<td align="right" style="border-top:1px dotted #cccccc">		
		<?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted'): ?>
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small edit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
&nbsp;">&nbsp;
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted'): ?>
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_KEY']; ?>
" class="crmbutton small create" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>
">&nbsp;
        <?php endif; ?>
        
        <?php if ($this->_tpl_vars['DELETE'] == 'permitted'): ?>
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; <?php if ($this->_tpl_vars['VIEWTYPE'] == 'calendar'): ?> DetailView.return_action.value='index'; <?php else: ?> DetailView.return_action.value='ListView'; <?php endif; ?> submitFormForActionWithConfirmation('DetailView', 'Delete', '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
');" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">&nbsp;
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['privrecord'] != ''): ?>
		<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['privrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
'" name="privrecord" value="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo vtiger_imageurl('rec_prev.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp;
		<?php else: ?>
		<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo vtiger_imageurl('rec_prev_disabled.gif', $this->_tpl_vars['THEME']); ?>
">
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['privrecord'] != '' || $this->_tpl_vars['nextrecord'] != ''): ?>
		<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" onclick="var obj = this;var lhref = getListOfRecords(obj, '<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['CATEGORY']; ?>
');" name="jumpBtnIdBottom" id="jumpBtnIdBottom" src="<?php echo vtiger_imageurl('rec_jump.gif', $this->_tpl_vars['THEME']); ?>
">
		<?php endif; ?>
		&nbsp;
		<?php if ($this->_tpl_vars['nextrecord'] != ''): ?>
		<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['nextrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
'" name="nextrecord" src="<?php echo vtiger_imageurl('rec_next.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp;
		<?php else: ?>
		<img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" src="<?php echo vtiger_imageurl('rec_next_disabled.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp;
		<?php endif; ?>
	</td>
</tr>
</table>

<?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
<script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script>
<script language="JavaScript" type="text/javascript">Carousel();</script>
<?php endif; ?>

<script>
function getTagCloud()
{
new Ajax.Request(
        'index.php',
        {queue: {position: 'end', scope: 'command'},
        method: 'post',
        postBody: 'module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=<?php echo $this->_tpl_vars['MODULE']; ?>
Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid=<?php echo $this->_tpl_vars['ID']; ?>
',
        onComplete: function(response) {
                                $("tagfields").innerHTML=response.responseText;
                                $("txtbox_tagfields").value ='';
                        }
        }
);
}
getTagCloud();
</script>
<!-- added for validation -->
<script language="javascript">
  var fieldname = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDNAME']; ?>
);
  var fieldlabel = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDLABEL']; ?>
);
  var fielddatatype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDDATATYPE']; ?>
);
</script>
</td>

</tr></table>
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

