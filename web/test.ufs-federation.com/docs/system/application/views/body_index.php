<?php 
$user_agent = $_SERVER['HTTP_USER_AGENT'];
header("X-UA-Compatible: IE=edge");
if ((stripos($user_agent, 'MSIE 6.0') !== false) && (stripos($user_agent, 'MSIE 7.0') === false) && (stripos($user_agent, 'MSIE 8.0') === false) && (stripos($user_agent, 'MSIE 9.0') === false))
{
	if (!isset($_COOKIE["ie"]))
	{
		setcookie("ie", "yes", time()+60*60*24);
		$include_ie =1; 
	}
}
?>
<!DOCTYPE html>
<html lang="<? echo($this->site_lang); ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="language" content="<? echo($this->site_lang); ?>" />
  <meta charset="utf-8" />
  <link rel="shortcut icon" type="image/x-icon" href="<? echo($this->base_url); ?>/favicon.ico" />
  <link rel="apple-touch-icon" href="<? echo($this->base_url); ?>/apple-touch-icon.png" />
  <link type="image/x-icon" href="/favicon.ico" rel="shortcut icon" />

  <link rel="home" href="<? echo($this->base_url); ?>/index.html" title="Home page" type="text/html" />
  <link rel="start" href="<? echo($this->base_url); ?>/index.html" title="Home page" type="text/html" />
  <link rel="profile" href="http://gmpg.org/xfn/11" />

  <link rel="stylesheet" href="<? echo($this->base_url); ?>/css/main.css?t=<? echo(microtime(true)) ?>" media="screen,projection" title="screenview" />
  <link rel="stylesheet" href="<? echo($this->base_url); ?>/css/print.css" media="print" />
  <!--[if gt IE 7]><!--><link rel="alternate stylesheet" media="screen,projection" href="<? echo($this->base_url); ?>/css/print.css" title="printview" /><!--<![endif]-->
  <!--[if lt IE 8]><link rel="alternate stylesheet" media="screen,projection" href="<? echo($this->base_url); ?>/css/print.css" title="printview" disabled="disabled" /><![endif]-->
  <!--[if !IE]><!--><link rel="stylesheet" media="only screen and (max-device-width: 480px)" href="<? echo($this->base_url); ?>/css/iphone.css" /><!--<![endif]-->
  <!--<link rel="stylesheet" href="<? echo($this->base_url); ?>/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />-->
  <meta name="viewport" content="width=device-width" />
  <link rel="stylesheet" href="<? echo($this->base_url); ?>/css/handheld.css" media="handheld" />
  <!--[if lte IE 7]><link rel="stylesheet" href="<? echo($this->base_url); ?>/css/ie7.css" media="screen,projection" /><script language="JavaScript" type="text/javascript" src="<? echo($this->base_url); ?>/js/ie7.js"></script><![endif]-->
  <title><? echo($title); ?></title>
  <meta name="keywords" content="<? echo($keywords); ?>" />
  <meta name="description" content="<? echo($description); ?>" />  
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/jquery.js"></script>
  
  <? if(isset($share_market_script)){ ?>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/raphael.js"></script>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/jscharts.js"></script>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/tabs.js"></script>
  <? } ?>
  <? if(isset($about_companu_feedback_script)){ ?>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/jquery.placeholder.min.js"></script>
  <? } ?>
  <? if(isset($issuers_debt_market_script)){ ?>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/jqtabs.js"></script>
  <? } ?>
  <? if(isset($trade_ideas_script)){ ?>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/jqtabs.js"></script>
  <? } ?>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/js.js"></script>
  <? if(isset($home_page)){ ?>
  <script type="text/javascript" src="<? echo($this->base_url); ?>/js/slider.js"></script>
  
  <script type="text/javascript">
  	$(document).ready(function(){
$("#slider").tabs({ fx: {opacity: 'toggle', duration:200} }).tabs({event: 'mouseover'}).tabs('rotate',<? echo(isset($slider_speed)?$slider_speed:7); ?>000, true);

$('#slider').mouseover(function(){
    $(this).tabs('rotate', 0, false);
});
$('#slider').mouseout(function(){
    $(this).tabs({ fx: {opacity: 'toggle', duration:200} }).tabs({event: 'mouseover'}).tabs('rotate', <? echo(isset($slider_speed)?$slider_speed:7); ?>000);
});
    });
	
  </script>
  <? } ?>
  
  <?if(is_array($langs)):?>
     <style>
     <?foreach($langs as $item):?>
       <?if($item->is_display==1):?>
      	 #header ul.sub-nav #popup-language a.lang-<?echo($item->lang);?> {display: block;}
       <?endif;?>
     <?endforeach;?>
     </style>
  <?endif;?>
  
<? /* <!--<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32124074-1']);
  _gaq.push(['_setDomainName', 'ufs-federation.com']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>-->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-32124074-1', 'ufs-federation.com');
  ga('send', 'pageview');

</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-32124074-1', 'ufs-federation.com');
  ga('send', 'pageview');

</script>
*/ ?>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-32124074-1', 'auto');
  ga('send', 'pageview');

</script>
  
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter14549002 = new Ya.Metrika({id:14549002, enableAll: true, ut:"noindex", webvisor:true});
        } catch(e) {}
    });
    
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/14549002?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
function to_content_click(obj) {

  c=document.getElementById('online_consultant');
  if (c!=null) {
	  
	if (c.style.top=='280px'){
	  //c.style.top='550px';
	} else {
	  //c.style.top='280px';	  
    }	  		
	  
  }	  
}
</script>


</head>
<body class="<? echo($body_css_class); ?>" id="ufs-investment-company">
<!-- 8 mar theme -->
<? if (($this->last_page_name != 'runki-aktsionnogo-kapitala' &&
$this->last_page_name != 'debt-capital-market' &&
$this->last_page_name != 'lokalnue-obligatsii' &&
$this->last_page_name != '573-evroobligatsii' &&
$this->last_page_name != 'vekselnue-zayimu' &&
$this->last_page_name != 'islamskoe-finansirovanie' &&
$this->last_page_name != 'pereypakovka-dolga' &&
$this->last_page_name != '578-stryktyra-dlya-vupyska-evroobligatsiyi' &&
$this->last_page_name != 'ufs') && time() <= strtotime('2015-03-10 06:00:00')) { ?>
<style>
body {
  background-image: url("/banners/8mar/bg-body8mar.png");
  padding-top: 126px;
}
body.index #slider .bg-slider {
  background-image: url("/banners/8mar/bg-slider8mar.png");
}
body.inner {
  background-image: url("/banners/8mar/bg-body-inner8mar2.jpg");
}
#content-text div.h_block {
  background-image: url("/banners/8mar/bg-h_block8mar2.jpg");
}
</style>
<? } ?>
<!-- //8mar theme -->

<!-- Winter Holidays Theme -->
<? if (
/* ($this->last_page_name != 'runki-aktsionnogo-kapitala' &&
$this->last_page_name != 'debt-capital-market' &&
$this->last_page_name != 'lokalnue-obligatsii' &&
$this->last_page_name != '573-evroobligatsii' &&
$this->last_page_name != 'vekselnue-zayimu' &&
$this->last_page_name != 'islamskoe-finansirovanie' &&
$this->last_page_name != 'pereypakovka-dolga' &&
$this->last_page_name != '578-stryktyra-dlya-vupyska-evroobligatsiyi' &&
$this->last_page_name != 'ufs') && */ time() <= strtotime('2015-01-15 07:00:00')) { ?>
<style>
  body, body.inner, body.inner.dcm {
    background: #fff url('/banners/ny2015bgonly.jpg') 50% 0 no-repeat;
    padding-top: 143px;
  }
  #wrapper {
    margin: 0 auto;
    background: #fff url('/img/bg-body.png') 50% -4px no-repeat;
    width: 1120px;
    -webkit-box-shadow: 0px 0px 14px 0 rgba(0,0,0,0.35);
    box-shadow: 0px 0px 14px 0 rgba(0,0,0,0.35);
    overflow: hidden;
  }
  body.inner #wrapper {
    background: #fff url('/img/bg-body-inner.png') 50% 0 no-repeat;
  }
  #slider {
    width: 1080px;
    left: 20px;
  }
  div.illustration {
     display: none;
  }
  #ny-ribbon {
    display: block;
    position: absolute;
    height: 620px;
    width: 100%;
    top: 0;
    background: transparent url('http://www.ufs-federation.com/banners/NY_<?=($this->site_lang=='ru' ? 'ru' : 'en')?>.png') 50% 0 no-repeat;
  }
<? if ($this->site_lang=='ru' && time() <= strtotime('2014-12-31 00:00:00')) { ?>
    #ny-ribbon {
        background-image: url('http://www.ufs-federation.com/banners/preNY_ru.png');
    }
<? } ?>
</style>
<div id="ny-ribbon"></div>
<? } ?>
<?=($this->last_page_name != 'runki-aktsionnogo-kapitala' &&
$this->last_page_name != 'debt-capital-market' &&
$this->last_page_name != 'lokalnue-obligatsii' &&
$this->last_page_name != '573-evroobligatsii' &&
$this->last_page_name != 'vekselnue-zayimu' &&
$this->last_page_name != 'islamskoe-finansirovanie' &&
$this->last_page_name != 'pereypakovka-dolga' &&
$this->last_page_name != '578-stryktyra-dlya-vupyska-evroobligatsiyi' &&
$this->last_page_name != 'ufs' && time() <= strtotime('2015-01-15 07:00:00')) ? '<div id="finday-link"></div>' : ''?>
<!-- //Winter Holidays Theme -->

<div id="wrapper">
	<div id="header">
    
<!-- facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<!-- //facebook -->

<? if($this->site_lang=='ru' || $this->site_lang=='en' || $this->site_lang=='fr' || $this->site_lang=='de' || $this->site_lang=='cn') {
	$logo_class = ' class="logo5years_' . $this->site_lang . '"';
} ?>
		 <h1 id="logo"<?=$logo_class?>>UFS Investment Company <a href="<? echo($this->base_url); ?>/">(на главную)</a></h1>
        <? echo($body_header); ?>
    </div>
	<? echo(isset($promo)?$promo:''); ?>  
   <? if($this->site_lang == 'cn' && $this->last_page_name=='') {} else { ?>
    <div class="content">
    <? if(isset($home_page)){ ?>
    <div id="promo">
       <? echo($content); ?> 
    </div>
  	<a state="visible" href="#promo" class="to-content" onclick="to_content_click(this);">to content</a> 
    <? }else{ ?>
    <div id="content">
        <? if(isset($body_sidebar_left)){ ?>
        <div class="sidebar s_left">
            <? echo($body_sidebar_left); ?>
        </div>
        <? } ?>
        <div id="content-text">     
          <? echo($content); ?>
        </div>
        <? if(isset($body_sidebar_right)){ ?>
        <div class="sidebar s_right">
           <? echo($body_sidebar_right); ?>  
        </div>
        <? } ?>
         <? echo(isset($body_content_sub_in_section_content)?$body_content_sub_in_section_content:''); ?>  
        <? //echo(isset($pages)?$pages:''); ?>
    </div>
        <? if(isset($body_content_sub_with_sidebar)){ ?>
        <div id="content-sub" class="with_sidebar">
            <? echo($body_content_sub_with_sidebar); ?>
        </div>
        <? } ?>
        <? if(isset($body_content_sub)){ ?>
        <div id="content-sub">
            <? echo($body_content_sub); ?>
        </div>
        <? } ?>
    <? } ?>
   </div>
   <? } ?>
  </div>
   
  <div id="wrapper-footer">
    <div id="footer"><? echo($body_footer); ?></div>
  </div>
  <? echo(isset($body_popup)?$body_popup:''); ?>
  <? echo(isset($illustration)?$illustration:''); ?>
  <? echo(isset($share_market_script)?$share_market_script:''); ?>
  <? if(isset($include_ie)){ echo($body_ie6); } ?>
  
  <? if ($this->site_lang == 'ru') { echo($this->load->view('body_adviser','',true)); } ?>
    
    
<!--  
<? if ($this->site_lang == 'ru') { ?>
<div class="modalscreen"></div>
<script>
$(document).ready(function(){
    $('#snowgirl-close').click(function(){
        $('#snowgirl, #snowgirl-close').animate({top: '110px', opacity: 0.25}).fadeOut();
        event.preventDefault()
    });
});
</script>
<a href="/pages/o-kompanii/ychebnuyi-tsentr/teplue-vstrechi-y-kamina.html" target="_blank" id="snowgirl"></a>
<a id="snowgirl-close" title="Закрыть"></a>
<? } ?>
-->

<!--
<link rel="stylesheet" href="/css/magnific-popup.css"> 
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="/js/jquery.magnific-popup.js"></script>
<script>
$(document).ready(function() {
$('.popup-gallery').magnificPopup({
      delegate: 'a',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: { 
              enabled: true,
              navigateByImgClick: true,
              preload: [0,1] // Will preload 0 - before current, and 1 after the current image
      },
      image: {
              tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
              /* titleSrc: function(item) {
                      return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
              } */
      }
});
});
</script>
-->
</body>
</html>