<?php /* Smarty version 2.6.14, created on 2014-09-11 10:58:23
         compiled from /var/www/html/modules/dashboard/applets/HardDrives/tpl/harddrives.tpl */ ?>
<link rel="stylesheet" media="screen" type="text/css" href="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/HardDrives/tpl/css/styles.css" />
<script type='text/javascript' src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/HardDrives/js/javascript.js'></script>
<?php $_from = $this->_tpl_vars['part']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['particion']):
?>
<div>
    <?php if ($this->_tpl_vars['fastgauge']): ?>
	<div style="width: <?php echo $this->_tpl_vars['htmldiskuse_width']; ?>
px; height: <?php echo $this->_tpl_vars['htmldiskuse_height']; ?>
px;">
		<div style="position: relative; left: 33%; width: 33%; background: #6e407e;  height: 100%; border: 1px solid #000000;">
			<div style="position: relative; background: #3184d5; top: <?php echo $this->_tpl_vars['particion']['height_free']; ?>
%; height: <?php echo $this->_tpl_vars['particion']['height_used']; ?>
%">&nbsp;</div>
		</div>
	</div>
	<?php else: ?>
	<img alt="ObtenerInfo_Particion" src="?menu=<?php echo $this->_tpl_vars['module_name']; ?>
&amp;rawmode=yes&amp;applet=HardDrives&amp;action=graphic&amp;percent=<?php echo $this->_tpl_vars['particion']['porcentaje_usado']; ?>
" width="140" />	
	<?php endif; ?>
    <div class="neo-applet-hd-innerbox">
      <div class="neo-applet-hd-innerbox-top">
       <img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/HardDrives/images/light_usedspace.png" width="13" height="11" alt="used" /> <?php echo $this->_tpl_vars['particion']['formato_porcentaje_usado']; ?>
% <?php echo $this->_tpl_vars['LABEL_PERCENT_USED']; ?>
 &nbsp;&nbsp;<img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/HardDrives/images/light_freespace.png" width="13" height="11" alt="used" /> <?php echo $this->_tpl_vars['particion']['formato_porcentaje_libre']; ?>
% <?php echo $this->_tpl_vars['LABEL_PERCENT_AVAILABLE']; ?>

      </div>
      <div class="neo-applet-hd-innerbox-bottom">
        <div><strong><?php echo $this->_tpl_vars['LABEL_DISK_CAPACITY']; ?>
:</strong> <?php echo $this->_tpl_vars['particion']['sTotalGB']; ?>
GB</div>
        <div><strong><?php echo $this->_tpl_vars['LABEL_MOUNTPOINT']; ?>
:</strong> <?php echo $this->_tpl_vars['particion']['punto_montaje']; ?>
</div>
        <div><strong><?php echo $this->_tpl_vars['LABEL_DISK_VENDOR']; ?>
:</strong> <?php echo $this->_tpl_vars['particion']['sModelo']; ?>
</div>
      </div>
    </div>
</div>
<?php endforeach; endif; unset($_from); ?>

<div class="neo-divisor"></div>
<div id="harddrives_dirspacereport">
<p><?php echo $this->_tpl_vars['TEXT_WARNING_DIRSPACEREPORT']; ?>
</p>
<button class="submit" id="harddrives_dirspacereport_fetch" ><?php echo $this->_tpl_vars['FETCH_DIRSPACEREPORT']; ?>
</button>
</div>