<?php /* Smarty version 2.6.14, created on 2014-09-11 11:04:52
         compiled from /var/www/html/modules/addons_availables/themes/default/reporte_addons.tpl */ ?>
<div
    id="neo-addons-error-message"
    class="ui-corner-all"
    style="display: none;">
    <p>
        <span class="ui-icon" style="float: left; margin-right: .3em;"></span>
        <span id="neo-addons-error-message-text"></span>
    </p>
</div>
  <div class="neo-addons-header-row">
    <div class="neo-addons-header-row-filter">
      <?php echo $this->_tpl_vars['filter_by']; ?>
:
      <select id="filter_by" class="neo-addons-header-row-select" name="filter_by" onchange="javascript:do_listarAddons(null)">
        <option value="available"><?php echo $this->_tpl_vars['available']; ?>
</option>
        <option value="installed"><?php echo $this->_tpl_vars['installed']; ?>
</option>
        <option value="purchased"><?php echo $this->_tpl_vars['purchased']; ?>
</option>
        <option value="update_available"><?php echo $this->_tpl_vars['update_available']; ?>
</option>
      </select>
    </div>
    <div class="neo-addons-header-row-filter">
      <span style="vertical-align:top;"><?php echo $this->_tpl_vars['name']; ?>
:</span>
      <input type="text" id="filter_namerpm" value="" name="filter_namerpm" onkeypress="javascript:keyPressed(event)">
      <a onclick="javascript:do_listarAddons(null)" href="#">
      <img width="19" height="21" border="0" align="absmiddle" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/searchw.png" alt="">
      </a>
    </div>
    <div class="neo-addons-header-row-navigation">
        <img id="imgPrimero" style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-first.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblStart']; ?>
' align='absmiddle' />
        <img id="imgAnterior"  style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-previous.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblPrevious']; ?>
' align='absmiddle' />
        (<?php echo $this->_tpl_vars['showing']; ?>
 <span id="addonlist_start_range">?</span> - <span id="addonlist_end_range">?</span> <?php echo $this->_tpl_vars['of']; ?>
 <span id="addonlist_total">?</span>)
        <img id="imgSiguiente" style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-next.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblNext']; ?>
' align='absmiddle' />
        <img id="imgFinal" style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-last.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblEnd']; ?>
' align='absmiddle' />
    </div>
  </div>
<div id="addonlist">
<div style="text-align: center; padding: 40px;">
<img src="images/loading.gif" />
</div>
</div>
     <div id="footer" style="background: url(../../../images/addons_header_row_bg.png) repeat-x top; width: 100%; height:40px;"  >
     <div class="neo-addons-header-row-navigation">
        <img id="imgPrimeroFooter" style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-first.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblStart']; ?>
' align='absmiddle' />
        <img id="imgAnteriorFooter"  style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-previous.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblPrevious']; ?>
' align='absmiddle' />
        (<?php echo $this->_tpl_vars['showing']; ?>
 <span id="addonlist_start_range_footer">?</span> - <span id="addonlist_end_range_footer">?</span> <?php echo $this->_tpl_vars['of']; ?>
 <span id="addonlist_total_footer">?</span>)
        <img id="imgSiguienteFooter" style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-next.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblNext']; ?>
' align='absmiddle' />
        <img id="imgFinalFooter" style="cursor: pointer;" src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/table-arrow-last.gif" width="16" height="16" alt='<?php echo $this->_tpl_vars['lblEnd']; ?>
' align='absmiddle' />
    </div>
    </div>
<!-- Neo Progress Bar -->
<div class="neo-modal-box">
  <div id="container">
    <div class="neo-progress-bar-percentage"><span class="neo-progress-bar-percentage-tag"></span></div>
    <div class="neo-progress-bar"><div class="neo-progress-bar-progress"></div></div>
    <span class="neo-progress-bar-label"><img src="images/loading2.gif" align="absmiddle" />&nbsp;<span id="feedback"></span></span>
    <div class="neo-progress-bar-title"></div>
    <div class="neo-progress-bar-close"></div>
  </div>
</div>
<div class="neo-modal-blockmask"></div>