<?php /* Smarty version 2.6.14, created on 2014-09-11 10:58:13
         compiled from /var/www/html/modules/dashboard/themes/default/appletgrid.tpl */ ?>
<table width="80%" cellspacing="0" id="applet_grid" align="center">
<tr>
    <td class="appletcolumn" id="applet_col_1">
        <?php $_from = $this->_tpl_vars['applet_col_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['applet']):
?>
        <div class='appletwindow' id='portlet-<?php echo $this->_tpl_vars['applet']['code']; ?>
'>
            <div class='appletwindow_topbar'>
                <div class='appletwindow_title' width='80%'><img src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/<?php echo $this->_tpl_vars['applet']['applet']; ?>
/images/<?php echo $this->_tpl_vars['applet']['icon']; ?>
' align='absmiddle' />&nbsp;<?php echo $this->_tpl_vars['applet']['name']; ?>
</div>
                <div class='appletwindow_widgets' align='right' width='10%'>
                    <a class='appletrefresh'>
                        <img class='ima' src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/reload.png' border='0' align='absmiddle' />
                    </a>
                </div>
            </div>
            <div class='appletwindow_content' id='<?php echo $this->_tpl_vars['applet']['code']; ?>
'>
                <div class='appletwindow_wait'><img class='ima' src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/loading.gif' border='0' align='absmiddle' />&nbsp;<?php echo $this->_tpl_vars['LABEL_LOADING']; ?>
</div>
                <div class='appletwindow_fullcontent'></div>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
    </td>
    <td class="appletcolumn" id="applet_col_2">
        <?php $_from = $this->_tpl_vars['applet_col_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['applet']):
?>
        <div class='appletwindow' id='portlet-<?php echo $this->_tpl_vars['applet']['code']; ?>
'>
            <div class='appletwindow_topbar'>
                <div class='appletwindow_title' width='80%'><img src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/<?php echo $this->_tpl_vars['applet']['applet']; ?>
/images/<?php echo $this->_tpl_vars['applet']['icon']; ?>
' align='absmiddle' />&nbsp;<?php echo $this->_tpl_vars['applet']['name']; ?>
</div>
                <div class='appletwindow_widgets' align='right' width='10%'>
                    <a class='appletrefresh'>
                        <img src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/reload.png' border='0' align='absmiddle' />
                    </a>
                </div>
            </div>
            <div class='appletwindow_content' id='<?php echo $this->_tpl_vars['applet']['code']; ?>
'>
                <div class='appletwindow_wait'><img class='ima' src='modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/loading.gif' border='0' align='absmiddle' />&nbsp;<?php echo $this->_tpl_vars['LABEL_LOADING']; ?>
</div>
                <div class='appletwindow_fullcontent'></div>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
    </td>
</tr>
</table>