<?php /* Smarty version 2.6.14, created on 2014-09-11 10:58:15
         compiled from /var/www/html/modules/dashboard/applets/SystemResources/tpl/system_resources.tpl */ ?>
<link rel="stylesheet" media="screen" type="text/css" href="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/SystemResources/tpl/css/styles.css" />
<script type='text/javascript' src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/SystemResources/js/javascript.js'></script>
<div style='height:165px; position:relative; text-align:center;'>
    <div style='width:155px; float:left; position: relative;' id='cpugauge'>
	    <?php if ($this->_tpl_vars['fastgauge']): ?>
	    <div style="width: 140px; height: 140px;">
	        <div style="position: relative; left: 33%; width: 33%; background: #ffffff;  height: 100%; border: 1px solid #000000;">
	            <div style="position: relative; background: <?php echo $this->_tpl_vars['cpugauge']['color']; ?>
; top: <?php echo $this->_tpl_vars['cpugauge']['height_free']; ?>
%; height: <?php echo $this->_tpl_vars['cpugauge']['height_used']; ?>
%">&nbsp;</div>
	        </div>
	    </div>
	    <?php else: ?>
	    <img alt="rbgauge" src="" />
	       
	    <?php endif; ?>
        <div class="neo-applet-sys-gauge-percent"><?php echo $this->_tpl_vars['cpugauge']['percent']; ?>
%</div><div><?php echo $this->_tpl_vars['LABEL_CPU']; ?>
</div>
        <input type="hidden" name="cpugauge_value" id="cpugauge_value" value="<?php echo $this->_tpl_vars['cpugauge']['fraction']; ?>
" />
    </div>
    <div style='width:154px; float:left; position: relative;' id='memgauge'>
        <?php if ($this->_tpl_vars['fastgauge']): ?>
        <div style="width: 140px; height: 140px;">
            <div style="position: relative; left: 33%; width: 33%; background: #ffffff;  height: 100%; border: 1px solid #000000;">
                <div style="position: relative; background: <?php echo $this->_tpl_vars['memgauge']['color']; ?>
; top: <?php echo $this->_tpl_vars['memgauge']['height_free']; ?>
%; height: <?php echo $this->_tpl_vars['memgauge']['height_used']; ?>
%">&nbsp;</div>
            </div>
        </div>
        <?php else: ?>
        <img alt="rbgauge" src="" />
           
        <?php endif; ?>
        <div class="neo-applet-sys-gauge-percent"><?php echo $this->_tpl_vars['memgauge']['percent']; ?>
%</div><div><?php echo $this->_tpl_vars['LABEL_RAM']; ?>
</div>
        <input type="hidden" name="memgauge_value" id="memgauge_value" value="<?php echo $this->_tpl_vars['memgauge']['fraction']; ?>
" />
    </div>
    <div style='width:155px; float:right; position: relative;' id='swapgauge'>
        <?php if ($this->_tpl_vars['fastgauge']): ?>
        <div style="width: 140px; height: 140px;">
            <div style="position: relative; left: 33%; width: 33%; background: #ffffff;  height: 100%; border: 1px solid #000000;">
                <div style="position: relative; background: <?php echo $this->_tpl_vars['swapgauge']['color']; ?>
; top: <?php echo $this->_tpl_vars['swapgauge']['height_free']; ?>
%; height: <?php echo $this->_tpl_vars['swapgauge']['height_used']; ?>
%">&nbsp;</div>
            </div>
        </div>
        <?php else: ?>
        <img alt="rbgauge" src="" />
           
        <?php endif; ?>
        <div class="neo-applet-sys-gauge-percent"><?php echo $this->_tpl_vars['swapgauge']['percent']; ?>
%</div><div><?php echo $this->_tpl_vars['LABEL_SWAP']; ?>
</div>
        <input type="hidden" name="swapgauge_value" id="swapgauge_value" value="<?php echo $this->_tpl_vars['swapgauge']['fraction']; ?>
" />
    </div>
</div>
<div class='neo-divisor'></div>
<div class=neo-applet-tline>
    <div class='neo-applet-titem'><strong><?php echo $this->_tpl_vars['LABEL_CPUINFO']; ?>
:</strong></div>
    <div class='neo-applet-tdesc'><?php echo $this->_tpl_vars['cpu_info']; ?>
</div>
</div>
<div class=neo-applet-tline>
    <div class='neo-applet-titem'><strong><?php echo $this->_tpl_vars['LABEL_UPTIME']; ?>
:</strong></div>
    <div class='neo-applet-tdesc'><?php echo $this->_tpl_vars['uptime']; ?>
</div>
</div>
<div class='neo-applet-tline'>
    <div class='neo-applet-titem'><strong><?php echo $this->_tpl_vars['LABEL_CPUSPEED']; ?>
:</strong></div>
    <div class='neo-applet-tdesc'><?php echo $this->_tpl_vars['speed']; ?>
</div>
</div>
<div class='neo-applet-tline'>
    <div class='neo-applet-titem'><strong><?php echo $this->_tpl_vars['LABEL_MEMORYUSE']; ?>
:</strong></div>
    <div class='neo-applet-tdesc'>RAM: <?php echo $this->_tpl_vars['memtotal']; ?>
 SWAP: <?php echo $this->_tpl_vars['swaptotal']; ?>
</div>
</div>