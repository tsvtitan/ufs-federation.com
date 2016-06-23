<?php /* Smarty version 2.6.18, created on 2014-09-01 11:06:51
         compiled from Home/MainHomeBlock.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'Home/MainHomeBlock.tpl', 3, false),array('modifier', 'vtiger_imageurl', 'Home/MainHomeBlock.tpl', 19, false),)), $this); ?>

<?php $this->assign('homepagedashboard_title', getTranslatedString('Home Page Dashboard', 'Home')); ?>
<?php $this->assign('keymetrics_title', getTranslatedString('Key Metrics', 'Home')); ?>
<?php $this->assign('stitle', $this->_tpl_vars['tablestuff']['Stufftitle']); ?>
<script type="text/javascript">var vtdashboard_defaultDashbaordWidgetTitle = '<?php echo $this->_tpl_vars['homepagedashboard_title']; ?>
';</script>
<div id="stuff_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="MatrixLayer <?php if ($this->_tpl_vars['tablestuff']['Stufftitle'] == $this->_tpl_vars['homepagedashboard_title']): ?>twoColumnWidget<?php endif; ?>" style="float:left;overflow-x:hidden;overflow-y:auto;">
	<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">
		<tr id="headerrow_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="headerrow">
			<td align="left" class="homePageMatrixHdr" style="height:30px;" nowrap width=60%><b>&nbsp;<?php echo $this->_tpl_vars['stitle']; ?>
</b></td>
			<td align="right" class="homePageMatrixHdr" style="height:30px;" width=5%>
				<span id="refresh_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" style="position:relative;">&nbsp;&nbsp;</span>
			</td>
			<td align="right" class="homePageMatrixHdr" style="height:30px;" width=35% nowrap>

<?php if (( $this->_tpl_vars['tablestuff']['Stufftype'] != 'Default' || $this->_tpl_vars['tablestuff']['Stufftitle'] != $this->_tpl_vars['keymetrics_title'] ) && ( $this->_tpl_vars['tablestuff']['Stufftype'] != 'Default' || $this->_tpl_vars['tablestuff']['Stufftitle'] != $this->_tpl_vars['homepagedashboard_title'] ) && ( $this->_tpl_vars['tablestuff']['Stufftype'] != 'Tag Cloud' ) && ( $this->_tpl_vars['tablestuff']['Stufftype'] != 'Notebook' )): ?>
				<a id="editlink" style='cursor:pointer;' onclick="showEditrow(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
)">
					<img src="<?php echo vtiger_imageurl('windowSettings.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" hspace="2" align="absmiddle"/>
				</a>	
<?php else: ?>
				<img src="<?php echo vtiger_imageurl('windowSettings-off.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" hspace="2" align="absmiddle"/>
<?php endif; ?>

<?php if ($this->_tpl_vars['tablestuff']['Stufftitle'] == $this->_tpl_vars['homepagedashboard_title']): ?>
				<a style='cursor:pointer;' onclick="fetch_homeDB(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
);">
					<img src="<?php echo vtiger_imageurl('windowRefresh.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_REFRESH']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_REFRESH']; ?>
" hspace="2" align="absmiddle"/>
				</a>
<?php else: ?>
				<a style='cursor:pointer;' onclick="loadStuff(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
,'<?php echo $this->_tpl_vars['tablestuff']['Stufftype']; ?>
');">
					<img src="<?php echo vtiger_imageurl('windowRefresh.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_REFRESH']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_REFRESH']; ?>
" hspace="2" align="absmiddle"/>
				</a>
<?php endif; ?>

<?php if ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Default' || $this->_tpl_vars['tablestuff']['Stufftype'] == 'Tag Cloud'): ?>
				<a style='cursor:pointer;' onclick="HideDefault(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
)"><img src="<?php echo vtiger_imageurl('windowMinMax.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_HIDE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_HIDE']; ?>
" hspace="5" align="absmiddle"/></a>
<?php else: ?>
				<img src="<?php echo vtiger_imageurl('windowMinMax-off.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_HIDE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_HIDE']; ?>
" hspace="5" align="absmiddle"/>
<?php endif; ?>

<?php if ($this->_tpl_vars['tablestuff']['Stufftype'] != 'Default' && $this->_tpl_vars['tablestuff']['Stufftype'] != 'Tag Cloud'): ?>
				<a id="deletelink" style='cursor:pointer;' onclick="DelStuff(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
)"><img src="<?php echo vtiger_imageurl('windowClose.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOSE']; ?>
" hspace="5" align="absmiddle"/></a>
<?php else: ?>
				<img src="<?php echo vtiger_imageurl('windowClose-off.gif', $this->_tpl_vars['THEME']); ?>
" border="0" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOSE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOSE']; ?>
" hspace="5" align="absmiddle"/>
<?php endif; ?>
			</td>
		</tr>
	</table>
	
	<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">
<?php if ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Module'): ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="show_tab winmarkModulesusr">
<?php elseif ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Default' && $this->_tpl_vars['tablestuff']['Stufftitle'] != $this->_tpl_vars['homepagedashboard_title']): ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="show_tab winmarkModulesdef">
<?php elseif ($this->_tpl_vars['tablestuff']['Stufftype'] == 'RSS'): ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="show_tab winmarkRSS">
<?php elseif ($this->_tpl_vars['tablestuff']['Stufftype'] == 'DashBoard'): ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="show_tab winmarkDashboardusr">
<?php elseif ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Default' && $this->_tpl_vars['tablestuff']['Stufftitle'] == $this->_tpl_vars['homepagedashboard_title']): ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="show_tab winmarkDashboarddef">
<?php elseif ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Notebook' || $this->_tpl_vars['tablestuff']['Stufftype'] == 'Tag Cloud'): ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
">
<?php else: ?>
		<tr id="maincont_row_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" class="show_tab">
<?php endif; ?>
			<td colspan="2">
				<div id="stuffcont_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
" style="height:260px; overflow-y: auto; overflow-x:hidden;width:100%;height:100%;"> 
				</div>
			</td>
		</tr>
	</table>
	
	<table width="100%" cellpadding="0" cellspacing="0" class="small scrollLink">
	<tr>
		<td align="left">
			<a href="javascript:;" onclick="addScrollBar(<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
);">
				<?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>

			</a>
		</td>
<?php if ($this->_tpl_vars['tablestuff']['Stufftype'] == 'Module' || ( $this->_tpl_vars['tablestuff']['Stufftype'] == 'Default' && $this->_tpl_vars['tablestuff']['Stufftitle'] != 'Key Metrics' && $this->_tpl_vars['tablestuff']['Stufftitle'] != $this->_tpl_vars['homepagedashboard_title'] && $this->_tpl_vars['tablestuff']['Stufftitle'] != 'My Group Allocation' ) || $this->_tpl_vars['tablestuff']['Stufftype'] == 'RSS' || $this->_tpl_vars['tablestuff']['Stufftype'] == 'DashBoard'): ?>
		<td align="right">
			<a href="#" id="a_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
">
				<?php echo $this->_tpl_vars['MOD']['LBL_MORE']; ?>

			</a>
		</td>
<?php endif; ?>
	</tr>	
	</table>
</div>

<script language="javascript">
		window.onresize = function(){positionDivInAccord('stuff_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
','<?php echo $this->_tpl_vars['tablestuff']['Stufftitle']; ?>
','<?php echo $this->_tpl_vars['tablestuff']['Stufftype']; ?>
');};
	positionDivInAccord('stuff_<?php echo $this->_tpl_vars['tablestuff']['Stuffid']; ?>
','<?php echo $this->_tpl_vars['tablestuff']['Stufftitle']; ?>
','<?php echo $this->_tpl_vars['tablestuff']['Stufftype']; ?>
');
</script>	