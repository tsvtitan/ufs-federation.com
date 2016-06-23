<? if($this->site_lang=='ru') { ?>

<div id="online_consultant">
    <a href="/webim/client.php?locale=ru" target="_blank" onclick="if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 &amp;&amp; window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open('/webim/client.php?locale=ru&amp;url='+escape(document.location.href)+'&amp;referrer='+escape(document.referrer), 'webim', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=640,height=480,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;"><? echo(dictionary('Online консультант')) ?></a>
</div>

<? } ?>