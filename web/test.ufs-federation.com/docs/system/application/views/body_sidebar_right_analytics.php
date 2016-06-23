 <div class="banner"> 
<?
// banner Голосование Cbonds Awards 2014
/*
$banner_name      = 'ca2014';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = '/pages/analitika/awards.html'; break;
  case 'en': $banner_link = '/pages/analitika/awards.html'; break;
  case 'de': $banner_link = '/pages/analitika/awards.html'; break;
  case 'fr': $banner_link = '/pages/analitika/awards.html'; break;
}

if ($this->site_lang == 'ru') {
  echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
*/
?>


<?
// banner открыть счет у брокера
/*
if($this->last_page_name=='ezhednevnue-kommentarii' ||$this->last_page_name=='broker' ) {

$banner_name      = 'become-a-client';
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
}
}

 */
?>
    
      
    <? if(($this->last_page_name=='investorawards')&&($this->site_lang=='ru')) { ?> 
            <h2>Благодаря вашей поддержке мы завоевали ряд признаний</h2>
            <ul>
                <li>Прорыв 2013 года (ИА&#160;Сbonds по&#160;итогам опросов инвестиционного сообщества).</li>
                <li>Победители конкурса прогнозов Thomson Reuters.</li>
                <li>Одни из&#160;самых точных и&#160;приносящих максимально возможный за&#160;период доход рекомендации (Bloomberg, рэнкинг BARR).</li>
            </ul>
            
            <table style="border:none">
                <tbody>
                    <tr>
                        <td style="background-color: #f2f6f7;">
                        <h2 style="text-align: left;">Узнайте больше</h2>
                        <ul class="learn-more">
                            <li class="robot" style="text-align: left;">Читайте аналитику в <a href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank">мобильном приложении</a></li>
                            <li class="subscribe" style="text-align: left;"><a href="http://ru.ufs-federation.com/subscribe.html">Подписывайтесь</a> и будьте в курсе главных событий</li>
<!--                        <li class="subscribe" style="text-align: left;"><a href="http://ru.ufs-federation.com/subscribe.html">Подписывайтесь</a> и будьте в курсе самых главных событий на российском и глобальном рынках</li> -->
                            <li class="model" style="text-align: left;"><a href="http://ru.ufs-federation.com/pages/analitika/1804-modelnuyi-portfel.html">Следите</a>, как работают наши торговые идеи</li>
                            <li class="model" style="text-align: left;"><a href="http://ru.ufs-federation.com/pages/o-kompanii/news/modelnuyi-portfel-ot-ufs-ic.html">Зарабатывайте лучше прогнозов</a></li>
                            <li class="youtube" style="text-align: left;"><? echo(dictionary('Смотрите наши выступления на ')); ?><a href="http://www.youtube.com/UFSInvestmentCompany" target="_blank">YouTube</a></li>
                        </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
<? } ?>
<? if(($this->last_page_name=='investorawards')&&($this->site_lang=='en')) { ?> 
            <h2>Our research team has already won a series of awards thanks to your support</h2>
            <ul>
                <li>Breakthrough of the year 2013 (according to the surveys of investment community by Cbonds).</li>
                <li>Winners of the forecast competition by Thomson Reuters.</li>
                <li>One of the most accurate recommendations that bring the highest possible income for the given period (Bloomberg, BAAR ranking).</li>
            </ul>
            
            <table style="border:none">
                <tbody>
                    <tr>
                        <td style="background-color: #f2f6f7;">
                        <h2 style="text-align: left;">Find out more</h2>
                        <ul class="learn-more">
                            <li class="robot" style="text-align: left;">Read our research in <a href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank">the mobile application</a></li>
                            <li class="subscribe" style="text-align: left;"><a href="http://ru.ufs-federation.com/subscribe.html">Subscribe</a> and be aware of the main events</li>
<!--                        <li class="subscribe" style="text-align: left;"><a href="http://ru.ufs-federation.com/subscribe.html">Подписывайтесь</a> и будьте в курсе самых главных событий на российском и глобальном рынках</li> -->
                            <li class="model" style="text-align: left;"><a href="http://ru.ufs-federation.com/pages/analitika/1804-modelnuyi-portfel.html">Follow</a> our trading ideas</li>
                            <li class="model" style="text-align: left;"><a href="http://ru.ufs-federation.com/pages/o-kompanii/news/modelnuyi-portfel-ot-ufs-ic.html">Earn better then expected</a></li>
                            <li class="youtube" style="text-align: left;"><? echo(dictionary('Смотрите наши выступления на ')); ?><a href="http://www.youtube.com/UFSInvestmentCompany" target="_blank"><? echo(dictionary('YouTube')); ?></a></li>
                        </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
<? } ?>
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

<div style="padding-left: 0px;">
	<? echo($this->load->view('body_subscriber','',true)) ?>
</div>     
<? if(($this->last_page_name=='ezhednevnue-kommentarii')||($this->last_page_name=='ezhednevnue-obzoru')) { ?>
<br><h2><? echo(dictionary('Смотрите также')); ?></h2>
    <? if($this->site_lang=='ru') { ?>
    <ul class="learn-more">
    <li class="gold"><a href="/investitsii-v-zoloto/investitsii-v-zoloto.html"><small>«Золотой Фонд»</small></a></li>
    <!-- <li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><small>Фонд «Умный робот Арбитраж»</small></a></li> -->
    <li class="strategy"><a href="/pages/investoram/trust/strategiya-1.html"><small>Стратегия «Рынок Акций»</small></a></li>
    <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?> <? } ?>
<? if($this->site_lang=='ru') { ?>    
<br>
<div class="banner"><a href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank"><img width="230px" src="/banners/ufs-appstore.png"></a><br/>
        <a href="https://play.google.com/store/apps/details?id=ru.ideast.ufs" target="_blank"><img src="/banners/ufs-googleplay.png" width="230"  alt=""></a><br/> 
        <br>        
       
<!-- Баннер <<Альтернативный фонд>> -->
   <!-- <? if(($this->last_page_name=='iemitentu-dolgovogo-runka')||($this->last_page_name=='obzor')||($this->last_page_name=='dividendnuyi-kalendar')||($this->last_page_name=='dolgovoyi-runok')||($this->last_page_name=='torgovue-idei')) { ?><a href="/investoram/mutual-funds/fond-alternativnoyi-ienergetiki.html"><img src="/banners/green_230-150.gif" width="230" height="150" alt=""/></a></div><? } ?> -->
<!-- End Баннер <<Альтернативный фонд>> -->
        <? } ?> 
<? if($this->site_lang=='en') { ?> 
        <div class="banner"></div>
    <? } ?> 
     <? if($this->site_lang=='de') { ?> 
        <div class="banner"></div>
     <? } ?> 
     <? if($this->site_lang=='fr') { ?> 
        <div class="banner"></div>
     <? } ?>
<? if((($this->last_page_name=='brokerage')||($this->last_page_name=='strategiya'))&&($this->site_lang=='ru')) { ?>      
	<div class="banner" style="margin-top: 12px"><a href="/application-form.html"><img src="/banners/application_form_ru.gif" width="230" height="67" alt=""/></a></div>
<? } ?>

    <!-- Плашка на InvestorAwards 
<? if($this->last_page_name=='investorawards'){ ?>  
<table style="border:none">
    <tbody>
        <tr>
            <td style="background-color: #f2f6f7;">
            <h2 style="text-align: left;"><? echo(dictionary('Обратите внимание')); ?></h2>
            <ul class="learn-more">
                <li class="robot" style="text-align: left;"><? echo(dictionary('Добавляйте нас в друзья в ')); ?><a href="https://www.facebook.com/UFS.IC" target="_blank">Facebook</a></li>
                <li class="youtube" style="text-align: left;"><? echo(dictionary('Смотрите наши выступления на ')); ?><a href="http://www.youtube.com/UFSInvestmentCompany" target="_blank">YouTube</a></li>
                <li class="strategy" style="text-align: left;"><? echo(dictionary('Приходите на')); ?> <a href="http://ru.ufs-federation.com/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><? echo(dictionary('Теплые встречи')); ?></a></li>
            </ul>
            </td>
        </tr>
    </tbody>
</table>
    <br>    
    <a href="http://ru.ufs-federation.com/pages/o-kompanii/teplue-vstrechi-y-kamina.html">
        <img src="/banners/kamin_ru_230x150.gif" width="230" height="150" alt="">
    </a>
<? } ?>
     Конец плашки на InvestorAwards -->

    <!-- Плашка Узнайте больше на странице Команда аналитиков -->
        <? if(($this->last_page_name=='komanda-analitiki')||($this->last_page_name=='research-team')) { ?>
        
            <table style="border:none;">
            <td><ul class="learn-more"><li class="model">&nbsp;</li></ul></td><td><small><? echo(dictionary('Самая читаемая аналитика на рынке облигаций 2014 года по версии Cbonds')); ?></small></td><tr></tr>
            <td><ul class="learn-more"><li class="model">&nbsp;</li></ul></td><td><small><? echo(dictionary('Команда аналитики - прорыв 2013 года по версии Cbonds')); ?></small></td><tr></tr>
            <td><ul class="learn-more"><li class="model">&nbsp;</li></ul></td><td><small><? echo(dictionary('Команда аналитиков входит в TOP-3 рэнкинга BAAR - Ранг абсолютного дохода Bloomberg')); ?></small></td><tr></tr>
            <td><ul class="learn-more"><li class="gold">&nbsp;</li></ul></td><td><small><? echo(dictionary('Алексей Козлов - автор самых точных прогнозов по версии Thomson Reuters')); ?></small></td><tr></tr></td>

            </table>
        <!--
        <table class="leasing-table" "><tr><td>
<h3>Команда аналитики</h3><h2>прорыв 2013 года по версии Cbonds</h2></td></tr>
            <tr><td><h5>Алексей Козлов</h5><h2>автор самых точных прогнозов по версии Tomson Reiters</h2></td><tr><td>
<h5>Вадим Ведерников</h5><h2>лучший аналитик на рынке Fixed Income по версии Cbonds</h2></td></tr></table>
    -->
        <? } ?>
    
    <!-- Конец плашки Узнайте больше на странице Команда аналитиков -->
    <!--<br><h5><? echo(dictionary('По вопросам получения комментариев экспертов просьба обращаться в пресс-службу Компании +7 (495) 781-73-05')); ?></h5> <br><br> -->
<? if(($this->last_page_name!='texnicheskiyi-analiz')&&($this->last_page_name!='kommentarii-po-runky-zolota')&&($this->last_page_name!='ypravlenie-riskami')&&($this->last_page_name!='komanda-analitiki')&&($this->last_page_name!='subscribe')) { ?>
<? if(!empty($indexes_box)){ ?>
<div class="analytic">
<? if(($this->last_page_name!='monitoring-bankovskogo-sektora')&&($this->last_page_name!='investorawards')){ ?>
    <? echo(settings($indexes_box)); ?>
    <? } ?>
</div>
<? } ?>
<? } ?>
<address class="glavni-office">
  <? echo($this->load->view('body_contacts','',true)) ?>
</address>
    

 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>