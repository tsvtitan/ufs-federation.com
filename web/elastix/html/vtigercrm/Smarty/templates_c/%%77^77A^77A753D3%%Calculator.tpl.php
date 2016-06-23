<?php /* Smarty version 2.6.18, created on 2014-09-02 13:47:32
         compiled from Calculator.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'Calculator.tpl', 19, false),)), $this); ?>

<div id="calc" style="z-index:10000002" class="layerPopup" >
	<table  border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr style="cursor:move;" >
			<td class="mailClientBg small" id="calc_Handle"><b><?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR']; ?>
</b></td>
			<td align="right"class="mailClientBg small">
			<a href="javascript:;">
			<img src="<?php echo vtiger_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  onClick="fninvsh('calc')" hspace="5" align="absmiddle">
			</a>
			</td>
		</tr>
	</table>
	<table  border="0" cellpadding="0" cellspacing="0" width="100%" class="hdrNameBg">
	</tr>
	<tr><td style="padding:10px;" colspan="2"><?php echo $this->_tpl_vars['CALC']; ?>
</td></tr>
	</table>
</div>

<script>

	var cal_Handle = document.getElementById("calc_Handle");
	var cal_Root   = document.getElementById("calc");
	Drag.init(cal_Handle, cal_Root);
</script>	