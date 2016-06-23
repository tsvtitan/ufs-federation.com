<?php /* Smarty version 2.6.14, created on 2014-09-11 10:58:38
         compiled from file:/var/www/html/modules/registration/themes/default/_cloud_login.tpl */ ?>
<link href="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/themes/default/css/styles.css" rel="stylesheet" />

<div id="moduleContainer">
    <div id="moduleTitle" valign="middle" align="left"><span class="div_title_style">&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['registration_server']; ?>
</span></div>

    <div id="formContainer" class="div_content_style">
        <div align="center"><?php echo $this->_tpl_vars['alert_message']; ?>
</div>
    </div>
    <div class="div_content_style">
	<div id="msnTextErr" align="center"></div>
        <div id='cloud-login-content'>
            <div id="cloud-login-logo">
                <img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/cloud_logo_login.png" width="281px" height="70px" alt="elastix logo" />
            </div>
            <div class="cloud-login-line">
                <img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/user_login.png" width="23px" height="26px" alt="elastix logo" class="cloud-login-img-input"/>
                <input type="text" id="input_user" name="input_user" class="cloud-login-input" defaultVal="<?php echo $this->_tpl_vars['EMAIL']; ?>
"/>
            </div>
            <div class="cloud-login-line">
                <img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/images/psswrd_login.png" width="23px" height="26px" alt="elastix logo" class="cloud-login-img-input"/>
                <input type="password" id="input_pass" name="input_pass" class="cloud-login-input" defaultVal="<?php echo $this->_tpl_vars['PASSWORD']; ?>
"/>
            </div>
            <div class="cloud-login-line">                
                <input type="button" name="input_register" class="cloud-login-button" onclick="registrationByAccount();" value="<?php echo $this->_tpl_vars['REGISTER_ACTION']; ?>
"/>
                <input type="hidden" name="msgtmp" id="msgtmp" value="<?php echo $this->_tpl_vars['sending']; ?>
" />
            </div>
            <div class="cloud-login-line" >
                <a class="cloud-link_subscription" href="#" onclick="showPopupCloudRegister('<?php echo $this->_tpl_vars['registration']; ?>
',540,500)"><?php echo $this->_tpl_vars['DONT_HAVE_ACCOUNT']; ?>
</a>
            </div>
            <div class="cloud-footernote"><a href="http://www.elastix.org" style="text-decoration: none;" target='_blank'>Elastix</a> <?php echo $this->_tpl_vars['ELASTIX_LICENSED']; ?>
 <a href="http://www.opensource.org/licenses/gpl-license.php" style="text-decoration: none;" target='_blank'>GPL</a> <?php echo $this->_tpl_vars['BY']; ?>
 <a href="http://www.palosanto.com" style="text-decoration: none;" target='_blank'>PaloSanto Solutions</a>. 2006 - <?php echo $this->_tpl_vars['currentyear']; ?>
.</div>
            <br>
        </div>
    </div>
</div>

<?php echo '
<script src="modules/';  echo $this->_tpl_vars['module_name'];  echo '/themes/default/js/javascript.js" type="text/javascript"></script>
'; ?>