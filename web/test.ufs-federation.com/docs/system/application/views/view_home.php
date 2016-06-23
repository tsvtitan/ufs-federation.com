<script>
    (function($){  
      $(function(){  
        var e = $(".scrollTop"),  
        speed = 500;  
  
        e.click(function(){  
            $("html:not(:animated)" +( !$.browser.opera ? ",body:not(:animated)" : "")).animate({ scrollTop: 0}, 500 );  
            return false; //важно!  
        });  
      
    function show_scrollTop(){  
            ( $(window).scrollTop()>300 ) ? e.fadeIn(600) : e.hide();  
        }  
        $(window).scroll( function(){show_scrollTop()} ); show_scrollTop();  
    });  
  
})(jQuery)  
</script> 
<div class="promo-section">

<!-- Finance day greetings -->
<? if($this->site_lang=='ru' && (time() <= strtotime('2015-05-12 07:00:00'))) { ?>
<style>
    #slider {
        margin-bottom: 40px;
    }
    #feb23 {
        margin: 10px 0 20px;
    }
    body.index div.promo-section div.section.contacts {
        margin-top: -472px;
    }
</style>
<!-- <img id="feb23" src="http://ru.ufs-federation.com/banners/23febfinal.jpg" width="770" height="326" alt=""/> -->
<img id="feb23" src="/banners/9may/pobeda70banner2.jpg" width="770" height="433" alt=""/>
<? } ?>
<!-- end of Finance day greetings --> 
		<!--<div class="section online_consultant">
		<? echo($this->load->view('body_online_consultant','',true)) ?>
		</div>-->
		<!--<div class="section online_consultant">
		<? /*echo($this->load->view('body_online_consultant','',true))*/ ?>
		</div>-->

<? if($this->site_lang=='ru'||$this->site_lang=='en'||$this->site_lang=='de'||$this->site_lang=='fr') {
switch ($this->site_lang) {
  case 'ru': $banner_link333 = 'http://ru.ufs-federation.com/pages/lizing/lizing-aviasydov.html'; break;
  case 'en': $banner_link333 = 'http://en.ufs-federation.com/pages/leasing/leasing-branches/aircraft-lease.html'; break;
  case 'de': $banner_link333 = 'http://de.ufs-federation.com/pages/leasing/leasingsbranche/flugzeugsleasing.html'; break;
  case 'fr': $banner_link333 = 'http://fr.ufs-federation.com/pages/crdit-bail/branches-de-crdit-bail/crdit-bail-davions.html'; break;
}

switch ($this->site_lang) {
  case 'ru': $banner_link407= 'http://ru.ufs-federation.com/pages/iemitentam/debt-capital-market/restryktyrizatsii-evroobligatsiyi-v-azii.html'; break;
  case 'en': $banner_link407 = 'http://en.ufs-federation.com/pages/issuers/debt-capital-market/eurobonds-restructuring-in-asia.html'; break;
  case 'de': $banner_link407 = 'http://de.ufs-federation.com/pages/emittenten/fremdkapitalmarkt/eurobondsumstrukturierung-in-asien.html'; break;
  case 'fr': $banner_link407 = 'http://fr.ufs-federation.com/pages/emetteurs/debt-capital-market/restructuration-des-euro-obligations-en-asie.html'; break;
}
?>
<style>
    .xpromo {
         height: 170px;
         margin-top: 10px;
    }
    .xpromo img {
        float: left;
        margin-right: 30px;
    }
    body.index div.promo-section div.section.contacts {
        margin-top: -190px;
    }
</style>
<div class="xpromo">
<a href="<?=$banner_link407?>"><img src="/banners/407_<?=$this->site_lang?>.jpg" width="407" height="150" alt=""/></a>
<a href="<?=$banner_link333?>"><img src="/banners/333_<?=$this->site_lang?>.jpg" width="333" height="150" alt=""/></a>
</div>
<? } ?>

		<div class="section second_s">
			<h2><a href="<? echo($press_about_us_url); ?>"><? echo(dictionary('Мы в СМИ')); ?></a></h2>
			<? if(count($press_about_us)>0){ ?>
      <? if($this->site_lang=='ru'){ ?>
      <!--div class="article">
				<var class="news-logo clearfix">
          <span class="block-img" style="width: 90px"><img src="<? echo($this->base_url.'/img/press_logo.gif'); ?>" style="height: 47px"/></span>
					<var>
						<? echo('10.07.2015, 10:36'); ?><span><? echo(dictionary('Ответы на вопросы СМИ')); ?></span>
					</var>
				</var>
				<a href="<? echo($this->phpself.'pages/o-kompanii/nam-zadayut-voprosu-mu-otvechaem.html'); ?>" class="shot"><? echo(dictionary('Мы отвечаем на вопросы журналистов по ситуации…')); ?></a>
			</div-->
      <? } ?>
			<? foreach($press_about_us as $item){ ?>
			<div class="article">
				<var class="news-logo clearfix">
					<? if(!empty($item->img)){ ?>
					<span class="block-img"><img src="<? echo($this->base_url.'/upload/press_about_us/small/'.$item->img); ?>" /></span>
					<? } ?>
					<var>
						<? echo($item->timestamp); ?><span><? echo($item->name); ?></span>
					</var>
				</var>
				<a href="<? echo($this->phpself.'pages/'.$item->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot"><? echo($item->short_content); ?></a>
			</div>
			<? } ?>
			<a href="<? echo($this->phpself.'pages/'.$press_about_us[0]->page_url.$this->urlsufix); ?>" class="all-news"><? echo(dictionary('Все публикации')); ?></a>
			<? } ?>
		</div>
		
		<div class="section first_s">
			<h2><a href="<? echo($news_url); ?>"><? echo(dictionary('Новости')); ?></a></h2>
			<? if(count($news)>0){ ?>
			<? foreach($news as $item){ ?>
			<div class="article">
				<var><? echo($item->timestamp); ?></var>
				<a href="<? echo($this->phpself.'pages/'.$item->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot"><? echo($item->short_content); ?></a>
			</div>
			<? } ?>
			<a href="<? echo($this->phpself.'pages/'.$news[0]->page_url.$this->urlsufix); ?>" class="all-news"><? echo(dictionary('Все новости')); ?></a><? } ?></div>
<div class="section contacts">
 <div class="banner"> 
   <? if($this->site_lang=='ru') { ?> 
     <? echo($this->load->view('body_callback','',true)) ?>
     <br />
	 

<? if($this->site_lang=='ru') { ?>  
 
                <!-- <a href="http://ru.test.ufs-federation.com/pages/investoram/osobennosti-nalogooblozheniya-operatsiyi-s-tsennumi-bymagami.html" class="blue-button"><? echo(dictionary('Особенности налогообложения операций с ценными бумагами')); ?></a> -->
                <a href="/pages/investoram/stat-klientom.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
     <? if($this->site_lang=='en') { ?> 
        <a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
     <? if($this->site_lang=='de') { ?> 
        <a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
     <? if($this->site_lang=='fr') { ?> 
      <a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?>
	 
	 <br />
	 

	 
   <!-- <a href="http://ru.cbonds.info/votes/151" target=_blank>
      	 <img src='http://ru.ufs-federation.com/banners/analitic_banner.gif' width="230"  alt="">
   </a><br /> -->
  <!-- <a href="http://ru.ufs-federation.com/pages/analitika/1804-modelnuyi-portfel.html">
       <img src='http://ru.ufs-federation.com/banners/torg_3_230х150.gif' width="230" alt="">
   </a>      -->
        <!-- <a href="/pages/analitika/ielita-fondovogo-runka.html">
        <img src="/banners/элита-фондового-рынка.gif" width="230" height="150" alt="">
      </a><br/> -->

    <!--  <a href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank">
        <img src="/banners/app-store_230_150.gif" width="230" height="150" alt=""> 
      </a><br/> 
      -->
    <!--  <a href="https://play.google.com/store/apps/details?id=ru.ideast.ufs" target="_blank">
        <img src="/banners/google_play.gif" width="230" height="150" alt=""> 
      </a><br/> -->
     <!--      <a href="/crossword.html">
        <img src="/banners/crossword_230_150.gif" width="230" height="150" alt=""> 
      </a><br/> -->
    <!-- <a href="/investoram/mutual-funds/fond-alternativnoyi-ienergetiki.html"><img src="/banners/green_230-150.gif" width="230" height="150" alt=""/></a>
     <br> -->
    <!--  <a href="/pages/o-kompanii/meetings.html">
        <img src="/banners/april_2014_230-150.gif" width="230" height="150" alt=""> 
      </a><br/> -->
     <!-- <a href="/pages/o-kompanii/aprel-v-aprele.html">
        <img src="/banners/april.gif" width="230" height="150" alt=""> 
      </a><br/> -->
       <!-- <a href="/pages/analitika/investorawards.html">
        <img src="/banners/ИИ_ГОЛОСОВАНИЕ_2014_230х150.gif" width="230" height="150" alt="">
      </a><br/> -->
      <!--      <a href="/investoram/brokerage/aktsiya-14.html">
        <img src="/banners/1+4_2014_230х150.gif" width="230" height="150" alt="">
      </a><br/> -->
      <!--
      <a href="/sochi2014.html">
        <img src="/banners/soch2014_230х150.gif" width="230" height="150" alt="">
      </a><br/> -->
     <!-- <a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html">
        <img src="/banners/kamin_ru_230x150.gif" width="230" height="150" alt="">
      </a><br/> -->
      <!--
<a href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank"><img width="230px" src="/banners/app store.png"></a>   --> 
<!--      <a href="http://ru.ufs-federation.com/pages/analitika/strategiya.html ">
       <img src='http://ru.ufs-federation.com/banners/инвестиционные-идеи-2014_230х150.gif' width="230" alt="">
   </a>    
-->

 <!--  <a href="/pages/o-kompanii/meetings.html">
    <img src="/banners/ok.gif" width="230"  alt="">
</a> -->
<!--      <br />
      <a href="/pages/analitika/strategiya.html">
    <img src="/banners/kstrategy.gif" width="230"  alt="">
</a>
      <br /> -->
<!--      <a href="/pages/investitsii-v-zoloto/investitsii-v-zoloto.html">
    <img src="/banners/0.gif" width="230"  alt="">
</a>
      <br /> -->
    <!--  <a href="/investoram/mutual-funds/fond-alternativnoyi-ienergetiki.html">
    <img src="/banners/alternative_ru.gif" width="230"  alt="">
</a>
      <br /> -->
    <? /* <a href="/pages/o-kompanii/osen-pora-novux-znaniyi.html"><img src="/banners/09_09_230-150.gif" width="230"  alt=""></a> <br />  */ ?>
      

     <? } ?> 
     <? if($this->site_lang=='en') { ?> 
    <!--  <a href="http://en.ufs-federation.com/pages/about-us/april-in-april.html">
    <img src="/banners/ok_en.gif" width="230"  alt="">
</a>
      <br /> -->
 <!--   <a href="/pages/for-investors/mutual-funds/green-energy-and-development-fund.html">
    <img src="/banners/alternative_en.gif" width="230"  alt="">
</a>
      <br /> -->
      
      
 <!--  <a href="/pages/about-us/april-in-april.html">
        <img src="/banners/april_en.gif" width="230" height="150" alt=""> 
      </a><br/>  -->
 
      <!-- <a href="/pages/research/investorawards.html">
        <img src="/banners/ИИ_-2014_230х150_англ.gif" width="230" height="150" alt="">
      </a><br/> -->
      <!--  <a href="/pages/about-us/warm-meetings-by-the-fireplace.html">
          <img src="/banners/kamin_en_230x150.gif" width="230" height="150" alt="">
        </a>-->
       <!-- <a href="http://ru.cbonds.info/votes/151" target=_blank>
            <img src='http://ru.ufs-federation.com/banners/analitic_banner_en.gif' width="230"  alt="">
        </a><br />-->
             
          <!-- <a href="/pages/about-us/autumn-is-time-for-new-knowledge.html">
    <img src="/banners/09_09_3_en.gif" width="230"  alt="">
</a>
      <br /> -->

    <? } ?> 
     <? if($this->site_lang=='de') { ?> 
      <!-- <a href="/pages/ber-die-firma/april-im-april.html">
    <img src="/banners/ok_de.gif" width="230"  alt="">
</a>
      <br /> -->
  <!--    <a href="http://de.ufs-federation.com/pages/sie-sind-investoren/anteilfonds/green-energy-and-development.html">
    <img src="/banners/alternative_de.gif" width="230"  alt="">
</a>
      <br /> -->
       <!--   <a href="/pages/ber-die-firma/april-im-april.html">
        <img src="/banners/april_de.gif" width="230" height="150" alt=""> 
      </a><br/>  -->
        <!--      <a href="http://en.ufs-federation.com/pages/research/investorawards.html">
        <img src="/banners/ИИ_-2014_230х150_нем.gif" width="230" height="150" alt="">
      </a><br/> -->
    <!--  <a href="/pages/about-us/warm-meetings-by-the-fireplace.html">
          <img src="/banners/kamin_de_230x150.gif" width="230" height="150" alt="">
        </a>-->
       <!-- <a href="http://ru.cbonds.info/votes/151" target=_blank>
            <img src='http://ru.ufs-federation.com/banners/analitic_banner_de.gif' width="230"  alt="">
        </a><br />-->
             
          <!-- <a href="/pages/ber-die-firma/herbst-ist-die-zeit-des-neuen-wissens.html">
    <img src="/banners/09_09_3_de.gif" width="230"  alt="">
</a>
      <br /> -->

     <? } ?> 
     <? if($this->site_lang=='fr') { ?> 
    <!--   <a href="http://fr.ufs-federation.com/pages/nous-connatre/avril-en-avril.html">
    <img src="/banners/ok_fr.gif" width="230"  alt="">
</a>
      <br /> -->
  <!--    <a href="http://fr.ufs-federation.com/pages/vous-tes-investisseurs/mutual-funds/fond-alternativnoyi-ienergetiki.html">
    <img src="/banners/alternative_fr.gif" width="230"  alt="">
</a>
      <br /> -->
    <!--     <a href="/pages/nous-connatre/avril-en-avril.html">
        <img src="/banners/april_fr.gif" width="230" height="150" alt=""> 
      </a><br/>  -->
        <!--      <a href="http://en.ufs-federation.com/pages/research/investorawards.html">
        <img src="/banners/ИИ_-2014_230х150_фр.gif" width="230" height="150" alt="">
      </a><br/> -->
        <!--<a href="/pages/about-us/warm-meetings-by-the-fireplace.html">
          <img src="/banners/kamin_fr_230x150.gif" width="230" height="150" alt="">
        </a>-->
      <!--  <a href="http://ru.cbonds.info/votes/151" target=_blank>
            <img src='http://ru.ufs-federation.com/banners/analitic_banner_fr.gif' width="230"  alt="">
        </a><br />-->      
          <!-- <a href="/pages/nous-connatre/lautomne-est-le-moment-de-nouvelles-connaissances.html">
    <img src="/banners/09_09_3_fr.gif" width="230"  alt="">
</a>
      <br /> -->
     <? } ?>
	 <?
// Голосование 2015

$banner_name      = 'golos2015';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = 'http://ru.cbonds.info/votes/193?'; break;
}


if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}

?>
	 <?
// banner это просто бомба
/*
$banner_name      = 'bomba2015';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';
switch ($this->site_lang) {
  case 'ru': $banner_link = '/learning.html'; break;
  case 'en': $banner_link = '/pages/about-us/training-center.html'; break;
  case 'de': $banner_link = '/pages/ber-die-firma/trainingszentrum.html'; break;
  case 'fr': $banner_link = '/pages/nous-connatre/centre-dtudes.html'; break;
}

if ($this->site_lang != 'cn') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' .$this->site_lang. '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
*/
?>


<?
// banner Угадайка для футбола
/*
$banner_name      = 'football2014';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';
$banner_link = 'https://www.facebook.com/UFS.IC';

if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' .$this->site_lang. '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
*/
?>

<?
// banner GSS

/*

$banner_name      = 'gss';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = '/pages/iemitentam/debt-capital-market/gss.html'; break;
  case 'en': $banner_link = '/pages/issuers/gss.html'; break;
  case 'de': $banner_link = 'http://en.ufs-federation.com/pages/issuers/gss.html'; break;
  case 'fr': $banner_link = '/pages/emetteurs/debt-capital-market/zao-avions-civiles-de-sukhoi.html'; break;
}

//if ($this->site_lang == 'ru' || $this->site_lang == 'en') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
//}

*/

// banner Мандариновое настроение
/*
$banner_name      = 'mandarin2014';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = '/pages/o-kompanii/mandarinovoe-nastroenie.html'; break;
  case 'en': $banner_link = '/pages/about-us/mandarin-mood.html'; break;
  case 'de': $banner_link = '/pages/ber-die-firma/mandarinen-stimmung.html'; break;
  case 'fr': $banner_link = '/pages/nous-connatre/humeur-de-mandarine.html'; break;
}
//if ($this->site_lang == 'ru') {
    echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
//}
*/

// banner Теплые встречи у камина
/*
$banner_name      = 'uc2014';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = '/pages/o-kompanii/winter-fireplace.html'; break;
  case 'en': $banner_link = '/pages/about-us/1994-warm-meetings-by-the-fireplace.html'; break;
  case 'de': $banner_link = '/pages/ber-die-firma/1995-warme-treffen-bei-dem-kamin.html'; break;
  case 'fr': $banner_link = '/pages/nous-connatre/1996-les-rencontres-chaleureuses-au-coin-du-feu.html'; break;
}

echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
*/


// banner Become a client
/* $banner_name      = 'become-a-client';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
  case 'en': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
  case 'de': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
  case 'fr': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
}

if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
} */


// banner Masleniza

$banner_name      = 'masleniza';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = 'https://www.facebook.com/UFS.IC'; break;
  case 'en': $banner_link = 'https://www.facebook.com/UFS.IC'; break;
  case 'de': $banner_link = 'https://www.facebook.com/UFS.IC'; break;
  case 'fr': $banner_link = 'https://www.facebook.com/UFS.IC'; break;
}

if ($this->site_lang == 'ru' && time() <= strtotime('2015-02-22 23:59:59')) {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
    

// banner Front Album
/*
$banner_name      = 'front_album2';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '333';

switch ($this->site_lang) {
  case 'ru': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/frontovoyi-albom.html '; break;
}


if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
*/
/*
// top8

$banner_name      = '8top_idea';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '333';

switch ($this->site_lang) {
  case 'en': $banner_link = 'http://en.ufs-federation.com/top8-en.html '; break;
  case 'de': $banner_link = 'http://de.ufs-federation.com/top8-de.html '; break;
  case 'fr': $banner_link = 'http://fr.ufs-federation.com/top8-fr.html '; break;
}


if ($this->site_lang == 'en' || $this->site_lang == 'de' || $this->site_lang == 'fr') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
*/
/*
// banner Valentine
$banner_name      = 'stval';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
  case 'en': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
  case 'de': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
  case 'fr': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
}

if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
*/

// banner Осень - пора новых знаний
/*
$banner_name      = 'ok2';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = '/pages/o-kompanii/osen-pora-novux-znaniyi.html'; break;
  case 'en': $banner_link = '/pages/about-us/autumn-is-time-for-new-knowledge.html'; break;
  case 'de': $banner_link = '/pages/ber-die-firma/herbst-ist-die-zeit-des-neuen-wissens.html'; break;
  case 'fr': $banner_link = '/pages/nous-connatre/lautomne-est-le-moment-de-nouvelles-connaissances.html'; break;
}

// if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
// }
*/
// banner Helicopter's Gold

/*
$banner_name      = 'hg2014';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = '/pages/o-kompanii/helicopters-gold.html'; break;
  case 'en': $banner_link = '/pages/about-us/helicopters-gold.html'; break;
  case 'de': $banner_link = '/pages/ber-die-firma/helicopters-gold.html'; break;
  case 'fr': $banner_link = '/pages/nous-connatre/helicopters-gold.html'; break;
}

//if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_ru' . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
//}
*/
?>

<div style="background: #fff url('/banners/app-banner_<?=$this->site_lang?>.jpg') 0 0 no-repeat; width: 230px; height: 37px; padding-top: 116px;">
<a style="display: block; float: right; width: 115px; height: 37px" href="https://play.google.com/store/apps/details?id=ru.ideast.ufs" target="_blank"></a>
<a style="display: block; float: left; width: 115px; height: 37px" href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank"></a>
</div><br/>

 <?
// ultimate banner
// $banner_name      = 'mystery';
// $banner_extension = 'gif';
// $banner_width     = '230';
// $banner_height    = '150';
 
// if ($this->site_lang=='en') {         // en
//   $banner_link = '/pages/about-us/news/discover-the-mysteries-of-the-past-with-ufs-ic.html';
// } elseif ($this->site_lang=='de') {   // de
//   $banner_link = '/pages/ber-die-firma/nachrichten/entdecken-sie-die-geheimnisse-der-vergangenheit-zusammen-mit-ufs-ic.html';
// } elseif ($this->site_lang=='fr') {   // fr
//   $banner_link = '/pages/nous-connatre/actualits/dcouvrez-les-mystres-du-pass-avec-lufs-ic.html';
// } else {                              // default
//   $banner_link = '/pages/o-kompanii/news/raskruvayite-tayinu-proshlogo-s-ufs-ic.html';
// }
// echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' .$this->site_lang. '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a>';
 ?>
</div>
        <? //if ($this->site_lang == 'ru') {
          switch ($this->site_lang) {
            case 'ru': $section_parent = 'o-kompanii'; break;
            case 'en': $section_parent = 'about-us'; break;
            case 'de': $section_parent = 'ber-die-firma'; break;
            case 'fr': $section_parent = 'nous-connatre'; break;
          }
        ?><a href="http://<?=$this->site_lang?>.ufs-federation.com/pages/<?=$section_parent?>/hrnews.html" class="become-client" >HR news</a><?
        //} ?>
        
        

            <? echo($this->load->view('body_contacts','',true)) ?>
            <br> 
              
              <? echo($this->load->view('body_banners','',true)) ?>
              <? echo($this->load->view('body_adv','',true)) ?>
              <? echo($this->load->view('body_subscriber','',true)) ?>
            <br>
          </div>
	</div>
	<a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a> 
	<div class="bottom-block clearfix">
	<? if($conferencies){ ?>
        <div class="conference-block">
            <div class="conference-inner">
            <b><? echo(dictionary('Прошедшее мероприятие')); ?>:</b>
            <p>
         <? echo((isset($conferencies->link) && trim($conferencies->link)!='')?'<a href="'.$conferencies->link.'" class="shot">'.$conferencies->name.'</a>':$conferencies->name) ?>
            </p>
            <a href="<? echo($this->phpself.'pages/'.$conferencies->page_url.'/all'.$this->urlsufix); ?>" class="icon-conference"><? echo(dictionary('Все мероприятия')); ?></a>
            </div>
        </div>
        <? } ?>

        <?if(!empty($lase_debt_emmiter) or !empty($lase_dmarket_daily)){?>
        <div class="comments">
        <h2><? echo(dictionary('Специальная аналитика')); ?></h2>
            <ul>
                <?if(!empty($lase_debt_emmiter)):?>
                <li><span><a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('debt_market').$this->urlsufix); ?>"><? echo(dictionary('Долговой рынок')); ?></a>:</span> <a class="icon-comment" href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url_by_id($lase_debt_emmiter->page_id).'/'.$lase_debt_emmiter->url.$this->urlsufix); ?>"><? echo(dictionary('Комментарий за')); ?> <?echo($lase_debt_emmiter->date)?></a></li>
                <?endif;?>             
                <?if(!empty($lase_dmarket_daily)):?><li><span><a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('share_market').$this->urlsufix); ?>"><? echo(dictionary('Рынок акций')); ?></a>:</span> <a class="icon-comment" href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url_by_id($lase_dmarket_daily->page_id).'/'.$lase_dmarket_daily->url.$this->urlsufix); ?>"><? echo(dictionary('Комментарий за')); ?> <?echo($lase_dmarket_daily->date)?></a></li>
                <?endif;?></ul></div><?}?></div>
