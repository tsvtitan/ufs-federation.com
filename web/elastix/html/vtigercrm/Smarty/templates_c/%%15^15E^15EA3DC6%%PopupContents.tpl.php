<?php /* Smarty version 2.6.18, created on 2014-09-09 07:45:57
         compiled from PopupContents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'PopupContents.tpl', 17, false),array('modifier', 'vtiger_imageurl', 'PopupContents.tpl', 69, false),)), $this); ?>
<!-- BEGIN: main -->
<form name="selectall" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
	<tr>
	<?php if ($this->_tpl_vars['SELECT'] == 'enable' && ( $this->_tpl_vars['POPUPTYPE'] != 'inventory_prod' && $this->_tpl_vars['POPUPTYPE'] != 'inventory_prod_po' && $this->_tpl_vars['POPUPTYPE'] != 'inventory_service' )): ?>
		<td style="padding-left:10px;" align="left"><input class="crmbutton small save" type="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE']); ?>
" onclick="if(SelectAll('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
')) window.close();"/></td>
	<?php elseif ($this->_tpl_vars['SELECT'] == 'enable' && ( $this->_tpl_vars['POPUPTYPE'] == 'inventory_prod' || $this->_tpl_vars['POPUPTYPE'] == 'inventory_prod_po' )): ?>
		<?php if ($this->_tpl_vars['RECORD_ID']): ?>
			<td style="padding-left:10px;" align="left" width=10%><input class="crmbutton small save" type="button" value="<?php echo $this->_tpl_vars['APP']['LBL_BACK']; ?>
" onclick="window.history.back();"/></td>
		<?php endif; ?>
		<td style="padding-left:10px;" align="left"><input class="crmbutton small save" type="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE']); ?>
" onclick="if(InventorySelectAll('<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
',image_pth))window.close();"/></td>
	<?php elseif ($this->_tpl_vars['SELECT'] == 'enable' && $this->_tpl_vars['POPUPTYPE'] == 'inventory_service'): ?>
		<td style="padding-left:10px;" align="left"><input class="crmbutton small save" type="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE']); ?>
" onclick="if(InventorySelectAllServices('<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
',image_pth))window.close();"/></td>
	<?php else: ?>		
		<td>&nbsp;</td>	
	<?php endif; ?>
	<td style="padding-right:10px;" align="right"><?php echo $this->_tpl_vars['RECORD_COUNTS']; ?>
</td></tr>
   	<tr>
	    <td style="padding:10px;" colspan=3>

       	<input name="module" type="hidden" value="<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
">
		<input name="action" type="hidden" value="<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
">
        <input name="pmodule" type="hidden" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
		<input type="hidden" name="curr_row" value="<?php echo $this->_tpl_vars['CURR_ROW']; ?>
">	
		<input name="entityid" type="hidden" value="">
		<input name="popuptype" id="popup_type" type="hidden" value="<?php echo $this->_tpl_vars['POPUPTYPE']; ?>
">
		<input name="idlist" type="hidden" value="">
		<div style="overflow:auto;height:348px;">
		<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
		<tbody>
		<tr>
			<?php if ($this->_tpl_vars['SELECT'] == 'enable'): ?>
				<td class="lvtCol" width="3%"><input type="checkbox" name="select_all" value="" onClick=toggleSelect(this.checked,"selected_id")></td>
            <?php endif; ?>
		    <?php $_from = $this->_tpl_vars['LISTHEADER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header']):
?>
		        <td class="lvtCol"><?php echo $this->_tpl_vars['header']; ?>
</td>
		    <?php endforeach; endif; unset($_from); ?>
			<?php if ($this->_tpl_vars['SELECT'] == 'enable' && ( $this->_tpl_vars['POPUPTYPE'] == 'inventory_prod' || $this->_tpl_vars['POPUPTYPE'] == 'inventory_prod_po' )): ?>
				<?php if (! $this->_tpl_vars['RECORD_ID']): ?>
					<td class="lvtCol"><?php echo $this->_tpl_vars['APP']['LBL_ACTION']; ?>
</td>
				<?php endif; ?>
			<?php endif; ?>
		</tr>
		<?php $_from = $this->_tpl_vars['LISTENTITY']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entity_id'] => $this->_tpl_vars['entity']):
?>
	        <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
		   <?php if ($this->_tpl_vars['SELECT'] == 'enable'): ?>
			<td><input type="checkbox" name="selected_id" value="<?php echo $this->_tpl_vars['entity_id']; ?>
" onClick=toggleSelectAll(this.name,"select_all")></td>
		   <?php endif; ?>
                   <?php $_from = $this->_tpl_vars['entity']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		        <td><?php echo $this->_tpl_vars['data']; ?>
</td>
                   <?php endforeach; endif; unset($_from); ?>
		</tr>
		<?php endforeach; else: ?>
                        <tr><td colspan="<?php echo $this->_tpl_vars['HEADERCOUNT']; ?>
">
                        <div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 99%;position: relative; z-index: 10000000;">
                        <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                <tr>
                                        <td rowspan="2" width="25%"><img src="<?php echo vtiger_imageurl('empty.jpg', $this->_tpl_vars['THEME']); ?>
" height="60" width="61%"></td>
                                        <?php if ($this->_tpl_vars['recid_var_value'] != '' && $this->_tpl_vars['mod_var_value'] != '' && $this->_tpl_vars['RECORD_COUNTS'] == 0): ?>
					<script>redirectWhenNoRelatedRecordsFound();</script>
                                        <td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall"><?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE']); ?>
 <?php echo $this->_tpl_vars['APP']['RELATED']; ?>
 !</td>
                                        <?php else: ?>
                                        <td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall"><?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE']); ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !</td>
                                        <?php endif; ?>
                                </tr>
                        </table>
                        </div>
                        </td></tr>
                <?php endif; unset($_from); ?>
	      	</tbody>
	    	</table>
			<div>
	    </td>
	</tr>

</table>
<table width="100%" align="center" class="reportCreateBottom">
<tr>
	<?php echo $this->_tpl_vars['NAVIGATION']; ?>
	
<td width="35%">&nbsp;</td>
</tr>
</table>
</form>
