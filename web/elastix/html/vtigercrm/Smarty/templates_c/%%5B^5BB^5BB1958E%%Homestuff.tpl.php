<?php /* Smarty version 2.6.18, created on 2014-09-01 11:06:51
         compiled from Home/Homestuff.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'Home/Homestuff.tpl', 23, false),array('modifier', 'getTranslatedString', 'Home/Homestuff.tpl', 36, false),array('modifier', 'count', 'Home/Homestuff.tpl', 51, false),array('modifier', 'array_reverse', 'Home/Homestuff.tpl', 52, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="modules/Home/Homestuff.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/scriptaculous.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/unittest.js"></script>
<script language="javascript" type="text/javascript" src="include/js/notebook.js"></script>

<input id="homeLayout" type="hidden" value="<?php echo $this->_tpl_vars['LAYOUT']; ?>
">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Home/HomeButtons.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="vtbusy_homeinfo" style="display:none;">
	<img src="<?php echo vtiger_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0">
</div>

<table width="97%" class="small showPanelBg" cellpadding="0" cellspacing="0" border="0" align="center" valign="top">
<tr>
	<td width="100%" align="center" valign="top" height="350">
		<div id="MainMatrix" class="show_tab topMarginHomepage" style="padding:0px;width:100%">
			<?php $_from = $this->_tpl_vars['HOMEFRAME']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['homeframe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['homeframe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['tablestuff']):
        $this->_foreach['homeframe']['iteration']++;
?>
								<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Home/MainHomeBlock.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<script>
										<?php if ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Default' && $this->_tpl_vars['tablestuff']['Stufftitle'] == getTranslatedString('Home Page Dashboard', 'Home')): ?>
						fetch_homeDB(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
);
					<?php elseif ($this->_tpl_vars['tablestuff']['Stufftype'] == 'DashBoard'): ?>
						loadStuff(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
,'<?php echo $this->_tpl_vars['tablestuff']['Stufftype']; ?>
');
					<?php endif; ?>
				</script>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</td>
</tr>
</table>
	
<script>
var Vt_homePageWidgetInfoList = [
<?php if (count($this->_tpl_vars['HOMEFRAME']) > 0): ?>
	<?php $this->assign('HOMEFRAME', array_reverse($this->_tpl_vars['HOMEFRAME'])); ?>
	<?php $_from = $this->_tpl_vars['HOMEFRAME']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['homeframe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['homeframe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['tablestuff']):
        $this->_foreach['homeframe']['iteration']++;
?>
		<?php if (( $this->_tpl_vars['tablestuff']['Stufftype'] != 'Default' || $this->_tpl_vars['tablestuff']['Stufftitle'] != getTranslatedString('Home Page Dashboard', 'Home') ) && $this->_tpl_vars['tablestuff']['Stufftype'] != 'DashBoard'): ?>
			{
				'widgetId':<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
,
				'widgetType':'<?php echo $this->_tpl_vars['tablestuff']['Stufftype']; ?>
'
			}
			<?php if ($this->_tpl_vars['index']+1 < count($this->_tpl_vars['HOMEFRAME'])): ?>,<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
	];
loadAllWidgets(Vt_homePageWidgetInfoList, <?php echo $this->_tpl_vars['widgetBlockSize']; ?>
);
<?php echo '
initHomePage();

/**
 * this function is used to display the add window for different dashboard widgets
 */
function fnAddWindow(obj,CurrObj){
	var tagName = document.getElementById(CurrObj);
	var left_Side = findPosX(obj);
	var top_Side = findPosY(obj);
	tagName.style.left= left_Side + 2 + \'px\';
	tagName.style.top= top_Side + 22 + \'px\';
	tagName.style.display = \'block\';
	document.getElementById("addmodule").href="javascript:chooseType(\'Module\');fnRemoveWindow();setFilter($(\'selmodule_id\'))";
	document.getElementById("addNotebook").href="javascript:chooseType(\'Notebook\');fnRemoveWindow();show(\'addWidgetsDiv\');placeAtCenter($(\'addWidgetsDiv\'));";
	//document.getElementById("addURL").href="javascript:chooseType(\'URL\');fnRemoveWindow();show(\'addWidgetsDiv\');placeAtCenter($(\'addWidgetsDiv\'));";
'; ?>

<?php if ($this->_tpl_vars['ALLOW_RSS'] == 'yes'): ?>
	document.getElementById("addrss").href="javascript:chooseType('RSS');fnRemoveWindow();show('addWidgetsDiv');placeAtCenter($('addWidgetsDiv'));";
<?php endif; ?>
<?php if ($this->_tpl_vars['ALLOW_DASH'] == 'yes'): ?>
	document.getElementById("adddash").href="javascript:chooseType('DashBoard');fnRemoveWindow()";
<?php endif; ?>
<?php echo '	
}
'; ?>
	
</script>

