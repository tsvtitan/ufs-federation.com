<?php /* Smarty version 2.6.14, created on 2014-09-11 10:52:07
         compiled from _common/_menu.tpl */ ?>
 
<?php if ($this->_tpl_vars['AUTO_POPUP'] == '1'): ?>
   <?php echo '
   	<script type=\'text/javascript\'>
 	$(\'.togglestickynote\').ready(function(e) {
            $("#neo-sticky-note-auto-popup").attr(\'checked\', true);
	    note();
	});
	</script>
   '; ?>

<?php endif; ?>
<div id="fullMenu">
  <table cellspacing=0 cellpadding=0 width="100%" border=0>
    <tr>
      <td>
        <table cellSpacing="0" cellPadding="0" width="100%" border="0" height="76">
          <tr>
            <td class="menulogo" width=380><a href='http://www.elastix.org' target='_blank'><img src="images/logo_elastix_new3.gif" border='0' /></a></td>
            <?php $_from = $this->_tpl_vars['arrMainMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idMenu'] => $this->_tpl_vars['menu']):
?>
            <?php if ($this->_tpl_vars['idMenu'] == $this->_tpl_vars['idMainMenuSelected']): ?>
            <td class="headlinkon" valign="bottom">
              <table cellSpacing="0" cellPadding="2" height="30" border="0">
                <tr><td class="menutabletabon_left" nowrap valign="top"><IMG src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/1x1.gif"></td><td class="menutabletabon" title="" nowrap><a
                        class="menutableon" href="index.php?menu=<?php echo $this->_tpl_vars['idMenu']; ?>
"><?php echo $this->_tpl_vars['menu']['Name']; ?>
</a></td><td class="menutabletabon_right" nowrap valign="top"><IMG src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/1x1.gif"></td>
                </tr>
              </table>
            </td>
            <?php else: ?>
            <td class="headlink" valign="bottom">
              <div style="position:absolute; z-index:200; top:65px;"><a href="javascript:mostrar_Menu('<?php echo $this->_tpl_vars['idMenu']; ?>
')"><img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/corner.gif" border="0"></a></div>
              <input type="hidden" id="idMenu" value=""></input>
              <div class="vertical_menu_oculto" id="<?php echo $this->_tpl_vars['idMenu']; ?>
">
                <table cellpadding=0 cellspacing=0>
                    <?php $_from = $this->_tpl_vars['menu']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menuchild']):
?>
                        <tr><td><a href="index.php?menu=<?php echo $this->_tpl_vars['menuchild']['id']; ?>
"><?php echo $this->_tpl_vars['menuchild']['Name'];  if ($this->_tpl_vars['menuchild']['HasChild']): ?>...<?php endif; ?></a></td></tr>
                    <?php endforeach; endif; unset($_from); ?>
                </table>
              </div>
              <table cellSpacing="0" cellPadding="2" height="29" border="0">
                <tr><td class="menutabletaboff_left" nowrap valign="top"><IMG src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/1x1.gif"></td><td class="menutabletaboff" title="" nowrap><a
                        class="menutable" href="index.php?menu=<?php echo $this->_tpl_vars['idMenu']; ?>
"><?php echo $this->_tpl_vars['menu']['Name']; ?>
</a></td><td class="menutabletaboff_right" nowrap valign="top"><IMG src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/1x1.gif"></td>
                </tr>
              </table> 
            </td>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
            <td>
                <div id='acerca_de'>
                    <table border='0' cellspacing="0" cellpadding="2" width='100%'>
                        <tr class="moduleTitle">
                            <td class="moduleTitle" align="center" colspan='2'>
                                <?php echo $this->_tpl_vars['ABOUT_ELASTIX']; ?>

                            </td>
                        </tr>
                        <tr class="tabForm" >
                            <td class="tabForm"  height='138' colspan='2' align='center'>
                                <?php echo $this->_tpl_vars['ABOUT_ELASTIX_CONTENT']; ?>
<br />
                                <a href='http://www.elastix.org' target='_blank'>www.elastix.org</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="moduleTitle" align="center" colspan='2'>
                                <input type='button' value='<?php echo $this->_tpl_vars['ABOUT_CLOSED']; ?>
' onclick="javascript:cerrar();" />
                            </td>
                        </tr>
                    </table> 
                </div>
            </td>
	    <td class="menuaftertab" align="right"><span><a class="register_link" style="color: <?php echo $this->_tpl_vars['ColorRegister']; ?>
; cursor: pointer; font-weight: bold; font-size: 13px;" onclick="showPopupCloudLogin('<?php echo $this->_tpl_vars['Register']; ?>
',540,460)"><?php echo $this->_tpl_vars['Registered']; ?>
</a></span>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="menuaftertab" width="40%" align="right">&nbsp;<a class="logout" id="viewDetailsRPMs"><?php echo $this->_tpl_vars['VersionDetails']; ?>
</a></td>
            <td class="menuaftertab" width="40%" align="right">&nbsp;<a href="javascript:mostrar();"><?php echo $this->_tpl_vars['ABOUT_ELASTIX']; ?>
</a></td>
            <td class="menuaftertab" width="20%" align="right">&nbsp;<a href="index.php?logout=yes"><?php echo $this->_tpl_vars['LOGOUT']; ?>
</a></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="menudescription">
        <table cellspacing="0" cellpadding="2" width="100%">
          <tr>
            <td>
              <table cellspacing="2" cellpadding="4" border="0">
                <tr>
                  <?php $_from = $this->_tpl_vars['arrSubMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idSubMenu'] => $this->_tpl_vars['subMenu']):
?>
                  <?php if ($this->_tpl_vars['idSubMenu'] == $this->_tpl_vars['idSubMenuSelected']): ?>
                  <td title="" class="botonon"><a href="index.php?menu=<?php echo $this->_tpl_vars['idSubMenu']; ?>
" class="submenu_on"><?php echo $this->_tpl_vars['subMenu']['Name']; ?>
</td>
                  <?php else: ?>
                  <td title="" class="botonoff"><a href="index.php?menu=<?php echo $this->_tpl_vars['idSubMenu']; ?>
"><?php echo $this->_tpl_vars['subMenu']['Name']; ?>
</a></td>
                  <?php endif; ?>
                  <?php endforeach; endif; unset($_from); ?>
                </tr>
              </table>
            </td>
            <td align="right" valign="middle">
				<img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/tab_notes.png" alt="tabnotes" id="togglestickynote1" class="togglestickynote" style="cursor: pointer;" border="0" />&nbsp;
				<?php if (! empty ( $this->_tpl_vars['idSubMenu2Selected'] )): ?>
                    <a href="javascript:popUp('help/?id_nodo=<?php echo $this->_tpl_vars['idSubMenu2Selected']; ?>
&name_nodo=<?php echo $this->_tpl_vars['nameSubMenu2Selected']; ?>
','1000','460')">
                    <input type="hidden" id="elastix_framework_module_id" value="<?php echo $this->_tpl_vars['idSubMenu2Selected']; ?>
" />
                <?php else: ?>
                    <a href="javascript:popUp('help/?id_nodo=<?php echo $this->_tpl_vars['idSubMenuSelected']; ?>
&name_nodo=<?php echo $this->_tpl_vars['nameSubMenuSelected']; ?>
','1000','460')">
                    <input type="hidden" id="elastix_framework_module_id" value="<?php echo $this->_tpl_vars['idSubMenuSelected']; ?>
" />
                <?php endif; ?><img
                src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/help_top.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:changeMenu()"><img
                src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/arrow_top.gif" border="0"></a>&nbsp;&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr class="downshadow"><td><img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/1x1.gif" height="5"></td></tr>
  </table>
</div>
<div id="miniMenu" style="display: none;">
  <table cellspacing="0" cellpadding="0" width="100%" class="menumini">
    <tr>
      <td><img src="images/logo_elastix_new_mini.png" border="0"></td>
      <td align="right" class="letra_gris" valign="middle"><?php echo $this->_tpl_vars['nameMainMenuSelected']; ?>
 &rarr; <?php echo $this->_tpl_vars['nameSubMenuSelected']; ?>
 <?php if (! empty ( $this->_tpl_vars['idSubMenu2Selected'] )): ?> &rarr; <?php echo $this->_tpl_vars['nameSubMenu2Selected']; ?>
 <?php endif; ?>
		  &nbsp;&nbsp;<img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/tab_notes_bottom.png" alt="tabnotes" id="togglestickynote2"  class="togglestickynote" style="cursor: pointer;" border="0"
          align="absmiddle" />
          &nbsp;&nbsp;<?php if (! empty ( $this->_tpl_vars['idSubMenu2Selected'] )): ?>
            <a href="javascript:popUp('help/?id_nodo=<?php echo $this->_tpl_vars['idSubMenu2Selected']; ?>
&name_nodo=<?php echo $this->_tpl_vars['nameSubMenu2Selected']; ?>
','1000','460')">
          <?php else: ?>
            <a href="javascript:popUp('help/?id_nodo=<?php echo $this->_tpl_vars['idSubMenuSelected']; ?>
&name_nodo=<?php echo $this->_tpl_vars['nameSubMenuSelected']; ?>
','1000','460')">
          <?php endif; ?><img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/help_bottom.gif" border="0" 
          align="absmiddle"></a>
          &nbsp;&nbsp;<a href="javascript:changeMenu()"><img src="themes/<?php echo $this->_tpl_vars['THEMENAME']; ?>
/images/arrow_bottom.gif" border="0" align="absmiddle"></a>&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</div>

<!--<div id="boxRPM" style="display:none;">
    <div class="popup">
        <table>
            <tr>
                <td class="tl"/>
                <td class="b"/>
                <td class="tr"/>
            </tr>
            <tr>
                <td class="b"/>
                <td class="body">
                    <div class="content_box">
                        <div id="table_boxRPM">
                           <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
                                <tr class="moduleTitle">
                                    <td class="moduleTitle">
                                        <div>
                                            <div style="float: left;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['VersionPackage']; ?>
&nbsp;</div>
                                            <div align="right" style="padding-top: 5px;"><a id="changeMode" style="visibility: hidden;">(<?php echo $this->_tpl_vars['textMode']; ?>
)</a></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="moduleTitle" id="loadingRPM" align="center" style="display: block;">
                                        <img class="loadingRPMimg" alt="loading" src="images/loading.gif"  />
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdRpm" style="display: block;">
                                        <table  id="tableRMP" width="100%" border="1" cellspacing="0" cellpadding="4" align="center">

                                        </table> 
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdTa" style="display: none;">
                                        <textarea  id="txtMode" value="" rows="60" cols="60"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="footer">
                        <a class="close_box_RPM">
                        <img src="images/closelabel.gif" title="close" class="close_image_box" />
                        </a>
                    </div>
                </td>
                <td class="b"/>
            </tr>
            <tr>
                <td class="bl"/>
                <td class="b"/>
                <td class="br"/>
            </tr>
        </table>
    </div>
</div>-->
<div id="fade_overlay" class="black_overlay"></div>

<table width="100%" cellpadding="0" cellspacing="0" height="100%">
  <tr>
    <?php if (! empty ( $this->_tpl_vars['idSubMenu2Selected'] )): ?>
    <td width="200px" align="left" valign="top" bgcolor="#f6f6f6" id="tdMenuIzq">
      <table cellspacing="0" cellpadding="0" width="100%" class="" align="left">
        <?php $_from = $this->_tpl_vars['arrSubMenu2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idSubMenu2'] => $this->_tpl_vars['subMenu2']):
?>
          <?php if ($this->_tpl_vars['idSubMenu2'] == $this->_tpl_vars['idSubMenu2Selected']): ?>
          <tr><td title="" class="menuiz_botonon"><a href="index.php?menu=<?php echo $this->_tpl_vars['idSubMenu2']; ?>
"><?php echo $this->_tpl_vars['subMenu2']['Name']; ?>
</td></tr>
          <?php else: ?>
          <tr><td title="" class="menuiz_botonoff"><a href="index.php?menu=<?php echo $this->_tpl_vars['idSubMenu2']; ?>
"><?php echo $this->_tpl_vars['subMenu2']['Name']; ?>
</a></td></tr>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
      </table>
    </td>
    <?php endif; ?>
<!-- Va al tpl index.tlp-->

<div id="PopupElastix" style="position: absolute; top: 0px; left: 0px;">
</div>

<?php echo '
<style type=\'text/css\'>
#acerca_de{
    position:fixed;
    background-color:#FFFFFF; 
    width:440px;
    height:203px;
    border:1px solid #800000;
    z-index: 10000;
}
</style>
<script type=\'text/javascript\'>
cerrar();
function cerrar()
{
    var div_contenedor = document.getElementById(\'acerca_de\');
    div_contenedor.style.display = \'none\';
}

function mostrar()
{
    var ancho = 440;
    var div_contenedor = document.getElementById(\'acerca_de\');
    var eje_x=(screen.width - ancho) / 2;
    div_contenedor.setAttribute("style","left:"+ eje_x + "px; top:123px");
    div_contenedor.style.display = \'block\';
}

function mostrar_Menu(element)
{
    var subMenu;

    var idMenu = document.getElementById("idMenu");
    if(idMenu.value!="")
    {
        subMenu = document.getElementById(idMenu.value);
        subMenu.setAttribute("class", "vertical_menu_oculto");
    }
    if(element != idMenu.value)
    {
        subMenu = document.getElementById(element);
        subMenu.setAttribute("class", "vertical_menu_visible");
        idMenu.setAttribute("value", element);
    }
    else idMenu.setAttribute("value", "");
}
</script>
'; ?>


<input type="hidden" id="lblTextMode" value="<?php echo $this->_tpl_vars['textMode']; ?>
" />
<input type="hidden" id="lblHtmlMode" value="<?php echo $this->_tpl_vars['htmlMode']; ?>
" />
<input type="hidden" id="lblRegisterCm"   value="<?php echo $this->_tpl_vars['lblRegisterCm']; ?>
" />
<input type="hidden" id="lblRegisteredCm" value="<?php echo $this->_tpl_vars['lblRegisteredCm']; ?>
" />
<input type="hidden" id="amount_char_label" value="<?php echo $this->_tpl_vars['AMOUNT_CHARACTERS']; ?>
" />
<input type="hidden" id="save_note_label" value="<?php echo $this->_tpl_vars['MSG_SAVE_NOTE']; ?>
" />
<input type="hidden" id="get_note_label" value="<?php echo $this->_tpl_vars['MSG_GET_NOTE']; ?>
" />
<input type="hidden" id="elastix_theme_name" value="<?php echo $this->_tpl_vars['THEMENAME']; ?>
" />
<input type="hidden" id="lbl_no_description" value="<?php echo $this->_tpl_vars['LBL_NO_STICKY']; ?>
" />