<?php /* Smarty version 2.6.14, created on 2014-09-11 10:58:20
         compiled from /var/www/html/modules/dashboard/applets/News/tpl/rssfeed.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/var/www/html/modules/dashboard/applets/News/tpl/rssfeed.tpl', 5, false),)), $this); ?>
<link rel="stylesheet" media="screen" type="text/css" href="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/News/tpl/css/styles.css" />
<?php $_from = $this->_tpl_vars['NEWS_LIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['NEWS_ITEM']):
?>
<div class="neo-applet-news-row">
    <span class="neo-applet-news-row-date"><?php echo $this->_tpl_vars['NEWS_ITEM']['date_format']; ?>
</span>
    <a href="https://twitter.com/share?original_referer=<?php echo ((is_array($_tmp=$this->_tpl_vars['WEBSITE'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&related=&source=tweetbutton&text=<?php echo ((is_array($_tmp=$this->_tpl_vars['NEWS_ITEM']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&url=<?php echo ((is_array($_tmp=$this->_tpl_vars['NEWS_ITEM']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&via=elastixGui"  target="_blank">
        <img src="modules/<?php echo $this->_tpl_vars['module_name']; ?>
/applets/News/images/twitter-icon.png" width="16" height="16" alt="tweet" />
    </a>
    <a href="<?php echo $this->_tpl_vars['NEWS_ITEM']['link']; ?>
" target="_blank"><?php echo ((is_array($_tmp=$this->_tpl_vars['NEWS_ITEM']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
</div>
<?php endforeach; else: ?>
<div class="neo-applet-news-row"><?php echo $this->_tpl_vars['NO_NEWS']; ?>
</div>
<?php endif; unset($_from); ?>