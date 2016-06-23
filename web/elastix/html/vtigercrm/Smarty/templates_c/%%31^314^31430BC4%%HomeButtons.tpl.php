<?php /* Smarty version 2.6.18, created on 2014-09-01 11:06:51
         compiled from Home/HomeButtons.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'Home/HomeButtons.tpl', 27, false),array('modifier', 'getTranslatedString', 'Home/HomeButtons.tpl', 158, false),)), $this); ?>

<table border=0 cellspacing=0 cellpadding=5 width="50%" class="small homePageButtons">
<tr style="cursor: pointer;">
	<td style="padding-left:10px;padding-right:50px" width=10% class="moduleName" nowrap>
		<?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['CATEGORY']]; ?>
&nbsp;&gt; 
		<a class="hdrLink" href="index.php?action=index&module=<?php echo $this->_tpl_vars['MODULE']; ?>
">
			<?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>

		</a>
	</td>
	<td class="sep1">
		&nbsp;
	</td>	

	<td align='left'>
		<img width="27" height="27" onClick='fnAddWindow(this,"addWidgetDropDown");' onMouseOut='fnRemoveWindow();' src="<?php echo vtiger_imageurl('btnL3Add.gif', $this->_tpl_vars['THEME']); ?>
" border="0" title="<?php echo $this->_tpl_vars['MOD']['LBL_HOME_ADDWINDOW']; ?>
" alt"<?php echo $this->_tpl_vars['MOD']['LBL_HOME_ADDWINDOW']; ?>
" style="cursor:pointer;">
	</td>
	
<?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes' && $this->_tpl_vars['CALENDAR_ACTIVE'] == 'yes' && $this->_tpl_vars['CALENDAR_DISPLAY'] == 'true'): ?>
	<td>
		<img width="27" height="27" src="<?php echo vtiger_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>
" border=0  onClick='fnvshobj(this,"miniCal");getMiniCal();'/>
	</td>
<?php endif; ?>
<?php if ($this->_tpl_vars['WORLD_CLOCK_DISPLAY'] == 'true'): ?>
	<td>
		<img width="27" height="27" src="<?php echo vtiger_imageurl('btnL3Clock.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'wclock');">
	</td>
<?php endif; ?>
<?php if ($this->_tpl_vars['CALCULATOR_DISPLAY'] == 'true'): ?>
	<td>
		<img width="27" height="27" src="<?php echo vtiger_imageurl('btnL3Calc.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();">
	</td>
<?php endif; ?>
<?php if ($this->_tpl_vars['CHAT_DISPLAY'] == 'true'): ?>
	<td>
		<img width="27" height="27" src="<?php echo vtiger_imageurl('tbarChat.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CHAT_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CHAT_TITLE']; ?>
" border=0  onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'>
	</td>	
<?php endif; ?>
	<td>
		<img width="27" height="27" src="<?php echo vtiger_imageurl('btnL3Tracker.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" border="0" onClick="fnvshobj(this,'tracker');">
	</td>

	<td>
		<img width="27" height="27" src="<?php echo vtiger_imageurl('btnL3AllMenu.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_TITLE']; ?>
" border="0" onmouseout="fninvsh('allMenu');" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))">
	</td>
	
	<td align='left'>
		<img width="27" height="27" onClick='showOptions("changeLayoutDiv");' src="<?php echo vtiger_imageurl('orgshar.gif', $this->_tpl_vars['THEME']); ?>
" border="0" title="<?php echo $this->_tpl_vars['MOD']['LBL_HOME_LAYOUT']; ?>
" alt"<?php echo $this->_tpl_vars['MOD']['LBL_HOME_LAYOUT']; ?>
" style="cursor:pointer;">
	</td>
	
	<td width="100%" align="center">
		<div id="vtbusy_info" style="display: none;">
			<img src="<?php echo vtiger_imageurl('status.gif', $this->_tpl_vars['THEME']); ?>
" border="0" />
		</div>
	</td>
</tr>
</table>

<form name="Homestuff" id="formStuff" style="display: inline;">
	<input type="hidden" name="action" value="homestuff">
	<input type="hidden" name="module" value="Home">
	<div id='addWidgetDropDown' style='background-color: #fff; display:none;' onmouseover='fnShowWindow()' onmouseout='fnRemoveWindow()'>
		<ul class="widgetDropDownList">
		<li>
			<a href='javascript:;' class='drop_down' id="addmodule">
				<?php echo $this->_tpl_vars['MOD']['LBL_HOME_MODULE']; ?>

			</a>
		</li>
<?php if ($this->_tpl_vars['ALLOW_RSS'] == 'yes'): ?>
		<li>
			<a href='javascript:;' class='drop_down' id="addrss">
				<?php echo $this->_tpl_vars['MOD']['LBL_HOME_RSS']; ?>

			</a>
		</li>
<?php endif; ?>	
<?php if ($this->_tpl_vars['ALLOW_DASH'] == 'yes'): ?>
		<li>
			<a href='javascript:;' class='drop_down' id="adddash">
				<?php echo $this->_tpl_vars['MOD']['LBL_HOME_DASHBOARD']; ?>

			</a>
		</li>
<?php endif; ?>
		<li>
			<a href='javascript:;' class='drop_down' id="addNotebook">
				<?php echo $this->_tpl_vars['MOD']['LBL_NOTEBOOK']; ?>

			</a>
		</li>
			</div>
	
		<div id="addWidgetsDiv" class="layerPopup" style="z-index:2000; display:none; width: 400px;">
		<input type="hidden" name="stufftype" id="stufftype_id">	
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
		<tr>
			<td class="layerPopupHeading" align="left" id="divHeader"></td>
			<td align="right"><a href="javascript:;" onclick="fnhide('addWidgetsDiv');$('stufftitle_id').value='';"><img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  align="absmiddle" /></a></td>
		</tr>
		</table>
		<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
		<tr>
			<td class=small >
						<table border="0" cellspacing="0" cellpadding="3" width="100%" align="center" bgcolor="white">
			<tr id="StuffTitleId" style="display:block;">
				<td class="dvtCellLabel"  width="110" align="right">
					<?php echo $this->_tpl_vars['MOD']['LBL_HOME_STUFFTITLE']; ?>

					<font color='red'>*</font>
				</td>
				<td class="dvtCellInfo" colspan="2" width="300">
					<input type="text" name="stufftitle" id="stufftitle_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%">
				</td>
			</tr>
						<tr id="showrow">
				<td class="dvtCellLabel"  width="110" align="right"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_SHOW']; ?>
</td>
				<td class="dvtCellInfo" width="300" colspan="2">
					<select name="maxentries" id="maxentryid" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
						<?php unset($this->_sections['iter']);
$this->_sections['iter']['name'] = 'iter';
$this->_sections['iter']['start'] = (int)1;
$this->_sections['iter']['loop'] = is_array($_loop=13) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['iter']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['iter']['show'] = true;
$this->_sections['iter']['max'] = $this->_sections['iter']['loop'];
if ($this->_sections['iter']['start'] < 0)
    $this->_sections['iter']['start'] = max($this->_sections['iter']['step'] > 0 ? 0 : -1, $this->_sections['iter']['loop'] + $this->_sections['iter']['start']);
else
    $this->_sections['iter']['start'] = min($this->_sections['iter']['start'], $this->_sections['iter']['step'] > 0 ? $this->_sections['iter']['loop'] : $this->_sections['iter']['loop']-1);
if ($this->_sections['iter']['show']) {
    $this->_sections['iter']['total'] = min(ceil(($this->_sections['iter']['step'] > 0 ? $this->_sections['iter']['loop'] - $this->_sections['iter']['start'] : $this->_sections['iter']['start']+1)/abs($this->_sections['iter']['step'])), $this->_sections['iter']['max']);
    if ($this->_sections['iter']['total'] == 0)
        $this->_sections['iter']['show'] = false;
} else
    $this->_sections['iter']['total'] = 0;
if ($this->_sections['iter']['show']):

            for ($this->_sections['iter']['index'] = $this->_sections['iter']['start'], $this->_sections['iter']['iteration'] = 1;
                 $this->_sections['iter']['iteration'] <= $this->_sections['iter']['total'];
                 $this->_sections['iter']['index'] += $this->_sections['iter']['step'], $this->_sections['iter']['iteration']++):
$this->_sections['iter']['rownum'] = $this->_sections['iter']['iteration'];
$this->_sections['iter']['index_prev'] = $this->_sections['iter']['index'] - $this->_sections['iter']['step'];
$this->_sections['iter']['index_next'] = $this->_sections['iter']['index'] + $this->_sections['iter']['step'];
$this->_sections['iter']['first']      = ($this->_sections['iter']['iteration'] == 1);
$this->_sections['iter']['last']       = ($this->_sections['iter']['iteration'] == $this->_sections['iter']['total']);
?>
						<option value="<?php echo $this->_sections['iter']['index']; ?>
"><?php echo $this->_sections['iter']['index']; ?>
</option>
						<?php endfor; endif; ?>
					</select>&nbsp;&nbsp;<?php echo $this->_tpl_vars['MOD']['LBL_HOME_ITEMS']; ?>

				</td>
			</tr>
			<tr id="moduleNameRow" style="display:block">
				<td class="dvtCellLabel"  width="110" align="right"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_MODULE']; ?>
</td>
				<td width="300" class="dvtCellInfo" colspan="2">
					<select name="selmodule" id="selmodule_id" onchange="setFilter(this)" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
						<?php $_from = $this->_tpl_vars['MODULE_NAME']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
							<?php $this->assign('MODULE_LABEL', ((is_array($_tmp=$this->_tpl_vars['arr']['1'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['arr']['1']) : getTranslatedString($_tmp, $this->_tpl_vars['arr']['1']))); ?>
							<option value="<?php echo $this->_tpl_vars['arr']['1']; ?>
"><?php echo $this->_tpl_vars['MODULE_LABEL']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
					<input type="hidden" name="fldname">
				</td>
			</tr>
			<tr id="moduleFilterRow" style="display:block">
				<td class="dvtCellLabel" align="right" width="110" ><?php echo $this->_tpl_vars['MOD']['LBL_HOME_FILTERBY']; ?>
</td>
				<td id="selModFilter_id" colspan="2" class="dvtCellInfo" width="300">
				</td>
			</tr>
			<tr id="modulePrimeRow" style="display:block">
				<td class="dvtCellLabel" width="110" align="right" valign="top"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_Fields']; ?>
</td>
				<td id="selModPrime_id" colspan="2" class="dvtCellInfo" width="300">
				</td>
			</tr>
			<tr id="rssRow" style="display:none">
				<td class="dvtCellLabel"  width="110" align="right"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_RSSURL']; ?>
<font color='red'>*</font></td>
				<td width="300" colspan="2" class="dvtCellInfo"><input type="text" name="txtRss" id="txtRss_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:58%"></td>
			</tr>
			<tr id="dashNameRow" style="display:none">
				<td class="dvtCellLabel"  width="110" align="right"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_DASHBOARD_NAME']; ?>
</td>
				<td id="selDashName" class="dvtCellInfo" colspan="2" width="300"></td>
			</tr>
			<tr id="dashTypeRow" style="display:none">
				<td class="dvtCellLabel" align="right" width="110"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_DASHBOARD_TYPE']; ?>
</td>
				<td id="selDashType" class="dvtCellInfo" width="300" colspan="2">
					<select name="seldashtype" id="seldashtype_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
						<option value="horizontalbarchart"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_HORIZONTAL_BARCHART']; ?>
</option>
						<option value="verticalbarchart"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_VERTICAL_BARCHART']; ?>
</option>
						<option value="piechart"><?php echo $this->_tpl_vars['MOD']['LBL_HOME_PIE_CHART']; ?>
</option>
					</select>
				</td>
			</tr>
			</table>	
						</td>
		</tr>
		</table>
		
		<table border=0 cellspacing=0 cellpadding=5 width=95% align="center">
			<tr>
				<td align="right">
					<input type="button" name="save" value=" &nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
&nbsp; " id="savebtn" class="crmbutton small save" onclick="frmValidate()"></td>
				<td align="left"><input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="crmbutton small cancel" onclick="fnhide('addWidgetsDiv');$('stufftitle_id').value='';">
				</td>
			</tr>
		</table>
	</div>
</form>

<div id="seqSettings" style="background-color:#E0ECFF;z-index:6000000;display:none;">
</div>


<div id="changeLayoutDiv" class="layerPopup" style="z-index:2000; display:none;">
	<table>
	<tr class="layerHeadingULine">
		<td class="big">
			<?php echo $this->_tpl_vars['MOD']['LBL_HOME_LAYOUT']; ?>

		</td>
		<td>
			<img onclick="hideOptions('changeLayoutDiv');" src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0" align="right" style="cursor: pointer;"/>
		</td>
	</tr>
	<tr id="numberOfColumns">
		<td class="dvtCellLabel" align="right">
			<?php echo $this->_tpl_vars['MOD']['LBL_NUMBER_OF_COLUMNS']; ?>

		</td>
		<td class="dvtCellLabel">
			<select id="layoutSelect" class="small">
				<option value="2">
					<?php echo $this->_tpl_vars['MOD']['LBL_TWO_COLUMN']; ?>

				</option>
				<option value="3">
					<?php echo $this->_tpl_vars['MOD']['LBL_THREE_COLUMN']; ?>

				</option>
				<option value="4">
					<?php echo $this->_tpl_vars['MOD']['LBL_FOUR_COLUMN']; ?>

				</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">
			<input type="button" name="save" value=" &nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
&nbsp; " id="savebtn" class="crmbutton small save" onclick="saveLayout();">
		</td>
		<td align="left">
			<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
" class="crmbutton small cancel" onclick="hideOptions('changeLayoutDiv');">
		</td>
	</tr>
	
	</table>
</div>