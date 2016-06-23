<?php /* Smarty version 2.6.14, created on 2014-09-11 11:04:37
         compiled from file:/var/www/html/modules/registration/themes/default/_registration.tpl */ ?>
<link href="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/themes/default/css/styles.css" rel="stylesheet" />

<div id="moduleContainer">
    <div id="moduleTitle" valign="middle" align="left"><span class="div_title_style">&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['registration']; ?>
</span></div>

    <div id="formContainer" class="div_content_style"><div align="center"><?php echo $this->_tpl_vars['alert_message']; ?>
</div></div>
    
    <div class="div_content_style">
	<div id="msnTextErr" align="center"></div>
        <div class="div_table_style">
            <div class="div_tr1_style">
                <div class="div_td1_style tdIdServer"><?php echo $this->_tpl_vars['identitykeylbl']; ?>
</div>
                <div class="div_td2_style tdIdServer"><b id="identitykey" class="b-style"></b></div>               
            </div>
            <div class="div_tr2_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['companyReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['companyReg']['INPUT']; ?>
 <span class="required">*</span></div>              
            </div>
            <div class="div_tr1_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['countryReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['countryReg']['INPUT']; ?>
 <span class="required">*</span></div>               
            </div>
            <div class="div_tr2_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['cityReg']['LABEL']; ?>
</div>
                <div class="div_td2_style" style="width:140px"><?php echo $this->_tpl_vars['cityReg']['INPUT']; ?>
 <span class="required">*</span></div>              
                <div class="div_td1_style" style="width:75px"><?php echo $this->_tpl_vars['phoneReg']['LABEL']; ?>
</div>
                <div class="div_td2_style" style="width:140px"><?php echo $this->_tpl_vars['phoneReg']['INPUT']; ?>
 <span class="required">*</span></div> 
            </div>
            <div class="div_tr1_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['addressReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['addressReg']['INPUT']; ?>
 <span class="required">*</span></div>               
            </div>           
            <div class="div_tr2_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['contactNameReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['contactNameReg']['INPUT']; ?>
 <span class="required">*</span></div>                              
            </div>
            <div class="div_tr1_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['emailReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['emailReg']['INPUT']; ?>
 <span class="required">*</span> (<?php echo $this->_tpl_vars['USERNAME']; ?>
)</div>
            </div>
            <div class="div_tr2_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['emailConfReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['emailConfReg']['INPUT']; ?>
 <span class="required">*</span></div>
            </div>
            <div class="div_tr1_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['passwdReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['passwdReg']['INPUT']; ?>
 <span class="required">*</span></div>                                             
            </div>
            <div class="div_tr2_style">
                <div class="div_td1_style"><?php echo $this->_tpl_vars['passwdConfReg']['LABEL']; ?>
</div>
                <div class="div_td2_style"><?php echo $this->_tpl_vars['passwdConfReg']['INPUT']; ?>
 <span class="required">*</span></div>                                                                   
            </div>
            <div class="div_tr1_style" id="tdButtons">
                <input type="button" class="cloud-login-button" style="width:160px" value="<?php echo $this->_tpl_vars['Activate_registration']; ?>
" name="btnAct" id="btnAct" onclick="registration();" />
                <input type="hidden" name="msgtmp" id="msgtmp" value="<?php echo $this->_tpl_vars['sending']; ?>
"/>
            </div>
       </div>
    </div>
</div>
