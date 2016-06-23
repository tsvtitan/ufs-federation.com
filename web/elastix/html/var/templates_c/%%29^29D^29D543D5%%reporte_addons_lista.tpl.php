<?php /* Smarty version 2.6.14, created on 2014-09-11 11:04:57
         compiled from /var/www/html/modules/addons_availables/themes/default/reporte_addons_lista.tpl */ ?>
<?php $_from = $this->_tpl_vars['arrData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['data']):
?>
  <div class="neo-addons-row">
    <input type="hidden" id="url_moreinfo" value="<?php echo $this->_tpl_vars['data']['url_moreinfo']; ?>
"/>
    <div class="neo-addons-row-title"><?php echo $this->_tpl_vars['data']['name']; ?>
 - <?php echo $this->_tpl_vars['data']['version']; ?>
-<?php echo $this->_tpl_vars['data']['release']; ?>
</div>
    <div class="neo-addons-row-author"><?php echo $this->_tpl_vars['by']; ?>
 <?php echo $this->_tpl_vars['data']['developed_by']; ?>
</div>
    <div class="neo-addons-row-icon"><img src="<?php echo $this->_tpl_vars['url_images']; ?>
/<?php echo $this->_tpl_vars['data']['name_rpm']; ?>
.jpeg " width="65" height="65" alt="iconaddon" align="absmiddle" /></div>
    <div class="neo-addons-row-desc"><?php echo $this->_tpl_vars['data']['description']; ?>
</div>
    <div class="neo-addons-row-location"><font style='font-weight:bold;'><?php echo $this->_tpl_vars['location']; ?>
: </font><?php echo $this->_tpl_vars['data']['location']; ?>
</div>
    <?php if ($this->_tpl_vars['data']['note']): ?>
    <div class="neo-addons-row-note"><font style='font-weight:bold;'><?php echo $this->_tpl_vars['note']; ?>
: </font><?php echo $this->_tpl_vars['data']['note']; ?>
</div>
    <?php endif; ?>
    <div class="neo-addons-row-button">
                <input type="hidden" id="name_rpm" value="<?php echo $this->_tpl_vars['data']['name_rpm']; ?>
"/>
        <?php if ($this->_tpl_vars['data']['installed_version'] == ''): ?>
            <?php if ($this->_tpl_vars['data']['is_commercial'] && $this->_tpl_vars['data']['fecha_compra'] == 0): ?>
	    <input type="hidden" id="<?php echo $this->_tpl_vars['data']['name_rpm']; ?>
_link" value="<?php echo $this->_tpl_vars['data']['url_marketplace'];  echo $this->_tpl_vars['server_key']; ?>
&referer="/>
	    <?php if ($this->_tpl_vars['data']['has_trialversion'] == 1): ?>
	    <div class="neo-addons-row-button-trial-left"><?php echo $this->_tpl_vars['TRIAL']; ?>
</div><div class="neo-addons-row-button-trial-right"><img width="17" height="17" alt="Install" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/addons_icon_install.png"></div>
	    <?php endif; ?>
            <div class="neo-addons-row-button-buy-left"><?php echo $this->_tpl_vars['BUY']; ?>
</div>
            <div class="neo-addons-row-button-buy-right"><img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/addons_icon_buy.png" width="19" height="18" alt="Buy" /></div>
            <?php else: ?>
            <div class="neo-addons-row-button-install-left"><?php echo $this->_tpl_vars['INSTALL']; ?>
</div>
            <div class="neo-addons-row-button-install-right"><img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/addons_icon_install.png" width="17" height="17" alt="Install" /></div>
            <?php endif; ?>
        <?php else: ?>
        <?php if ($this->_tpl_vars['data']['can_update']): ?>
            <div class="neo-addons-row-button-install-left tooltipInfo"><?php echo $this->_tpl_vars['UPDATE'];  if (! empty ( $this->_tpl_vars['data']['upgrade_info'] )): ?><span><?php echo $this->_tpl_vars['data']['upgrade_info']; ?>
</span><?php endif; ?></div>
            <div class="neo-addons-row-button-install-right"><img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/addons_icon_update.png" width="20" height="17" alt="Update" /></div>
        <?php endif; ?>
	<input type="hidden" id="<?php echo $this->_tpl_vars['data']['name_rpm']; ?>
_installed" value="yes"/>
        <div class="neo-addons-row-button-uninstall-left"><?php echo $this->_tpl_vars['UNINSTALL']; ?>
</div>
        <div class="neo-addons-row-button-uninstall-right"><img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/addons_icon_uninstall.png" width="17" height="17" alt="Uninstall" /></div>
	<?php if ($this->_tpl_vars['data']['fecha_compra'] == 0 && $this->_tpl_vars['data']['is_commercial']): ?>
	    <input type="hidden" id="<?php echo $this->_tpl_vars['data']['name_rpm']; ?>
_link" value="<?php echo $this->_tpl_vars['data']['url_marketplace'];  echo $this->_tpl_vars['server_key']; ?>
&referer="/>
	    <div class="neo-addons-row-button-buy-left"><?php echo $this->_tpl_vars['BUY']; ?>
</div>
	    <div class="neo-addons-row-button-buy-right"><img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/addons_icon_buy.png" width="19" height="18" alt="Buy" /></div>
	<?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="neo-addons-row-moreinfo"><?php echo $this->_tpl_vars['more_info']; ?>
...</div>
  </div>
<?php endforeach; endif; unset($_from); ?>

<input type="hidden" name="callback" id="callback" value="" />