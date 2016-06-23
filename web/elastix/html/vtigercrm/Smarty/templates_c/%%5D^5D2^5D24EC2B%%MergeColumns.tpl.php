<?php /* Smarty version 2.6.18, created on 2014-09-02 12:44:00
         compiled from MergeColumns.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'MergeColumns.tpl', 107, false),)), $this); ?>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/JavaScript">    
        var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;
        function setObjects() 
        {
            availListObj=getObj("availList")
            selectedColumnsObj=getObj("selectedColumns")

        }

        function addColumn() 
        {
        setObjects();
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
        setObjects();
            for (i=selectedColumnsObj.options.length;i>0;i--) 
            {
                if (selectedColumnsObj.options.selectedIndex>=0)
                selectedColumnsObj.remove(selectedColumnsObj.options.selectedIndex)
            }
        }
        
        function formSelectColumnString()
        {
            var selectedColStr = "";
            setObjects();
            for (i=0;i<selectedColumnsObj.options.length;i++) 
            {
                selectedColStr += selectedColumnsObj.options[i].value + ",";
            }
            if (selectedColStr == "")
            {
            	alert('<?php echo $this->_tpl_vars['APP']['LBL_MERGE_SHOULDHAVE_INFO']; ?>
');
            	return false;
            }
            document.mergeDuplicates.selectedColumnsString.value = selectedColStr;
            return;
        }
	setObjects();		
</script>	

<form enctype="multipart/form-data" name="mergeDuplicates" method="post" action="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=FindDuplicateRecords" onsubmit="VtigerJS_DialogBox.block();">
	<input type="hidden" name="module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
	<input type="hidden" name="parenttab" value="<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
	<input type="hidden" name="action" value="FindDuplicateRecords">
	<input type="hidden" name="selectedColumnsString"/>
	<table class="searchUIBasic small" border="0" cellpadding="5" cellspacing="0" width="80%" height="10" align="center">
		<tbody><tr class="lvtCol" style="Font-Weight: normal"><br>
					<td colspan="2">
						<span class="moduleName"><?php echo $this->_tpl_vars['APP']['LBL_SELECT_MERGECRITERIA_HEADER']; ?>
</span><br>
						<span font-weight:normal><?php echo $this->_tpl_vars['APP']['LBL_SELECT_MERGECRITERIA_TEXT']; ?>
</span>
					</td>
					<td valign="top" align="right" class="small">
						<span>&nbsp;</span>
						<span align="right" onClick="mergeshowhide('mergeDup')" onmouseover="this.style.cursor='pointer';"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span><br>
					</td>
			   </tr>
			   <tr><td colspan="3"></td></tr>
				<tr>
					<td><b><?php echo $this->_tpl_vars['APP']['LBL_AVAILABLE_FIELDS']; ?>
</b></td>
					<td></td>
					<td><b><?php echo $this->_tpl_vars['APP']['LBL_SELECTED_FIELDS']; ?>
</b></td>
				</tr>
				<tr>
					<td width=47%>
						<select id="availList" multiple size="10" name="availList" class="txtBox" Style="width: 100%">
						<?php echo $this->_tpl_vars['AVALABLE_FIELDS']; ?>

						</select>
					</td>
					<td width="6%">
						<div align="center">
							<input type="button" name="Button" value="&nbsp;&rsaquo;&rsaquo;&nbsp;" onClick="addColumn()" class="crmButton small" width="100%" /><br /><br />
							<input type="button" name="Button1" value="&nbsp;&lsaquo;&lsaquo;&nbsp;" onClick="delColumn()" class="crmButton small" width="100%" /><br /><br />
						</div>
					</td>
					<td width="47%">
						<select id="selectedColumns" size="10" name="selectedColumns" multiple class="txtBox" Style="width: 100%">
						<?php echo $this->_tpl_vars['FIELDS_TO_MERGE']; ?>

						</select>
					</td>
				</tr> 
				<tr>
					<td colspan="3" align="center">
					<input type="submit" name="save&merge" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_MERGE_BUTTON_TITLE']; ?>
" class="crmbutton small edit" onClick="return formSelectColumnString()"/>
					<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="crmbutton small cancel" type="button" onClick="mergeshowhide('mergeDup');">
					</td>
				</tr>
		</tbody>
	</table>
</form>
