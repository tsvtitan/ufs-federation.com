<?php /* Smarty version 2.6.14, created on 2014-09-11 10:58:23
         compiled from /var/www/html/modules/dashboard/applets/ProcessesStatus/tpl/process_status.tpl */ ?>
<link rel="stylesheet" media="screen" type="text/css" href="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/ProcessesStatus/tpl/css/styles.css" />
<script type='text/javascript' src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/ProcessesStatus/js/javascript.js'></script>
<div class="neo-applet-processes-menu">
<input type="hidden" id="neo_applet_selected_process" value="" />
<div id="neo-applet-processes-controles">
<input type="button" class="neo_applet_process" name="processcontrol_stop" id="neo_applet_process_stop" value="<?php echo $this->_tpl_vars['sMsgStop']; ?>
" />
<input type="button" class="neo_applet_process" name="processcontrol_start" id="neo_applet_process_start" value="<?php echo $this->_tpl_vars['sMsgStart']; ?>
" />
<input type="button" class="neo_applet_process" name="processcontrol_restart" id="neo_applet_process_restart" value="<?php echo $this->_tpl_vars['sMsgRestart']; ?>
" />
<input type="button" class="neo_applet_process" name="processcontrol_activate" id="neo_applet_process_activate" value="<?php echo $this->_tpl_vars['sMsgActivate']; ?>
" />
<input type="button" class="neo_applet_process" name="processcontrol_deactivate" id="neo_applet_process_deactivate" value="<?php echo $this->_tpl_vars['sMsgDeactivate']; ?>
" />
</div>
<img id="neo-applet-processes-processing" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/ProcessesStatus/images/loading.gif" style="display: none;" alt="" />
</div>
<?php $_from = $this->_tpl_vars['services']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sServicio'] => $this->_tpl_vars['infoServicio']):
?>
<div class="neo-applet-processes-row">
    <div class="neo-applet-processes-row-icon"><img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/ProcessesStatus/images/<?php echo $this->_tpl_vars['infoServicio']['icon']; ?>
" width="32" height="28" alt="<?php echo $this->_tpl_vars['sServicio']; ?>
" /></div>
    <div class="neo-applet-processes-row-name"><?php echo $this->_tpl_vars['infoServicio']['name_service']; ?>
</div>
    <div class="neo-applet-processes-row-menu">
        <input type="hidden" name="key-servicio" id="key-servicio" value="<?php echo $this->_tpl_vars['sServicio']; ?>
" />
        <input type="hidden" name="status-servicio" id="status-servicio" value="<?php echo $this->_tpl_vars['infoServicio']['status_service']; ?>
" />
        <input type="hidden" name="activate-process" id="activate-process" value="<?php echo $this->_tpl_vars['infoServicio']['activate']; ?>
" />
        <img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/ProcessesStatus/images/<?php echo $this->_tpl_vars['infoServicio']['status_service_icon']; ?>
" style="cursor:<?php echo $this->_tpl_vars['infoServicio']['pointer_style']; ?>
;" width="15" height="15" alt="menu" />
    </div>
    <div class="neo-applet-processes-row-status-msg" style="color: <?php echo $this->_tpl_vars['infoServicio']['status_color']; ?>
"><?php echo $this->_tpl_vars['infoServicio']['status_desc']; ?>
</div>
    <div class="neo-applet-processes-row-status-icon"></div>
</div>
<?php endforeach; endif; unset($_from); ?>