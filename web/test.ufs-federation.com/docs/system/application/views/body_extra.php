<? 
if($this->last_page_name=='broker') {

/* !!!
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
  echo '<br><a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}



  // banner Become a client
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
    echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a>';
  }
  */
}




if($this->last_page_name=='broker'){
    echo($this->load->view('body_subscriber','',true));
    ?>
    <br/>
    <? if ($this->site_lang=='ru') { ?><a href="http://ru.ufs-federation.com/pages/analitika/1804-modelnuyi-portfel.html">
        <img src='http://ru.ufs-federation.com/banners/torg_3_230х150.gif' width="230" alt="">
    </a>
    <br/><? } ?>
    <div style="background: #fff url('/banners/app-banner_<?=$this->site_lang?>.jpg') 0 0 no-repeat; width: 230px; height: 37px; padding-top: 116px;">
    <a style="display: block; float: right; width: 115px; height: 37px" href="https://play.google.com/store/apps/details?id=ru.ideast.ufs" target="_blank"></a>
    <a style="display: block; float: left; width: 115px; height: 37px" href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank"></a>
    </div><br/>
    <?
}
if($this->last_page_name=='gss'){
    echo"<h1>00000</h1>";
}

if($this->last_page_name=='helicopters-gold'){
if($this->site_lang=='ru'){ ?>

<h2>Информация об&nbsp;организаторах</h2>
<strong style="color:#08509d">UFS Investment Company</strong>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;предоставляет частным лицам доступ к&nbsp;финансовым инструментам и&nbsp;финансовым решениям, способным генерировать дополнительный заработок.</p>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;также помогаем предприятиям достигать заявленных стратегий, привлекая капитал в&nbsp;реальный сектор экономики. Инновационное мышление и&nbsp;анализ позволяют компании среди огромного массива информации отыскивать, а&nbsp;также создавать новые финансовые решения, работающие на&nbsp;повышение благосостояния ее&nbsp;клиентов.</p>
<p style="text-align: justify; font-size: 92%">Фонды компании &laquo;Золотой&raquo; и&nbsp;&laquo;Петр Великий&raquo; считаются одними из&nbsp;лучших в&nbsp;индустрии велс менеджмент. Линейка Private Equity Funds компании UFS IC&nbsp;позволяет инвесторам на&nbsp;коротком временном интервале зарабатывать порядка&nbsp;35% годовых.</p>
<br/>
<strong style="color:#08509d">АЭРОСОЮЗ</strong>
<p style="text-align: justify; font-size: 92%">Вертолетная компания &laquo;Аэросоюз&raquo; основана в&nbsp;2002 году и&nbsp;является крупнейшим в&nbsp;России официальным дилером и&nbsp;сервисным центром Robinson Helicopter Company (USA), официальным сервисным центром AgustaWestland (Италия), агентом и&nbsp;сервисным центром Eurocopter (Франция), авиационно-учебным центром Robinson и&nbsp;Eurocopter. Специалисты сертифицированы и&nbsp;прошли обучение на&nbsp;заводах-изготовителях. &laquo;Аэросоюз&raquo; является управляющей компанией вертодромов и&nbsp;вертолетных площадок на&nbsp;объектах партнеров: в&nbsp;элитных коттеджных поселках, спортивно-развлекательных комплексах и&nbsp;промышленных предприятиях.</p>
<p style="text-align: justify; font-size: 92%">В&nbsp;компании нет случайных людей. Это высококлассные авиационные специалисты, профессиональные менеджеры, увлеченные небом.</p>

<? } elseif($this->site_lang=='en'){ ?>

<h2>Information about organizers</h2>
<strong style="color:#08509d">UFS Investment Company</strong>
<p style="text-align: justify; font-size: 92%">UFS Investment Company provides individuals and corporations with access to&nbsp;financial instruments and financial decisions, helping them to&nbsp;generate additional earnings on&nbsp;global stock markets.</p>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;also helps enterprises achieve the stated strategies, attracting capital in&nbsp;the real sector of&nbsp;the economy. Innovative thinking and analysis allow the company to&nbsp;find and to&nbsp;create new financial decisions among the vast array of&nbsp;information, working on&nbsp;improving the welfare of&nbsp;its clients.</p>
<p style="text-align: justify; font-size: 92%">The company&rsquo;s funds &laquo;UFS Gold&raquo; and &laquo;Peter the Great&raquo; are one of&nbsp;the best in&nbsp;the Wealth Management industry. The range of&nbsp;Private Equity Funds of&nbsp;UFS IC&nbsp;enable investors to&nbsp;earn about&nbsp;35% p.a. in&nbsp;a&nbsp;short time lapse.</p>
<br/>
<strong style="color:#08509d">Aerosoyuz</strong>
<p style="text-align: justify; font-size: 92%">Helicopter company Aerosoyuz is&nbsp;founded in&nbsp;2002 and is&nbsp;the largest company in&nbsp;Russia being an&nbsp;authorized dealer and service center of&nbsp;Robinson Helicopter Company (USA), official service center of&nbsp;AgustaWestland (Italy), agent and service center of&nbsp;Eurocopter (France), learning aviation center of&nbsp;Robinson and Eurocopter. The experts are certified and went through training on&nbsp;the manufacturing plants. &laquo;Aerosoyuz&raquo; is&nbsp;the managing company of&nbsp;heliports and helipads on&nbsp;its partners&rsquo; sites: in&nbsp;elite cottage settlements, sports and entertainment complexes and industrial plants.</p>
<p style="text-align: justify; font-size: 92%">There are no&nbsp;random people in&nbsp;the company. There are only high quality aviation professionals and professional managers, fascinated by&nbsp;the sky.</p>

<? } elseif($this->site_lang=='de'){ ?>

<h2>Information &uuml;ber die Organisatoren</h2>
<strong style="color:#08509d">UFS Investment Company</strong>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;gew&auml;hrt die Privatpersonen den Zugriff auf Finanzinstrumente und finanzielle Entscheidungen, die f&auml;hig sind, ein zus&auml;tzliches Einkommen zu&nbsp;generieren.</p>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;hilft auch den Unternehmen dabei, die erkl&auml;rten Strategien zu&nbsp;erreichen, indem sie das Kapital in&nbsp;die Realwirtschaft ziehen. Innovatives Denken und Analyse erlauben der Gesellschaft, unter der Vielzahl der Informationen neue finanzielle L&ouml;sungen zu&nbsp;suchen und zu&nbsp;schaffen, die auf die Verbesserung des Lebens des Kunden gezielt sind.</p>
<p style="text-align: justify; font-size: 92%">Die Fonds der Gesellschaft &laquo;UFS Gold&raquo; und &laquo;Peter der Gro&szlig;e&raquo; sind eine der der besten in&nbsp;der Industrie Wales Management. Die Palette von Private Equity Funds von UFS-IC erm&ouml;glicht den Anlegern, in&nbsp;einem kurzen Zeitintervall etwa&nbsp;35% pro Jahr zu&nbsp;verdienen.</p>
<br/>
<strong style="color:#08509d">Aerosoyuz</strong>
<p style="text-align: justify; font-size: 92%">Das Hubschrauber-Unternehmen &laquo;Aerosoyuz&raquo; wurde im&nbsp;Jahr 2002 gegr&uuml;ndet und ist der gr&ouml;&szlig;te in&nbsp;Russland offizielle H&auml;ndler und Service-Zentrum von Robinson Helicopter Company (USA), das offizielle von AgustaWestland (Italien), Agenten und Service-zentrum von Eurocopter (Frankreich), Luft-und Ausbildungszentrum von Robinson und Eurocopter. Seine Experten sind zertifiziert und an&nbsp;Herstellerwerken ausgebildet. &laquo;Aerosoyuz&raquo; ist die Verwaltungsgesellschaft von Heliports und Hubschrauberlandepl&auml;tze auf Objekten der Partner: in&nbsp;der Elite-Einfamilienh&auml;usersiedlungen, Sport -, Unterhaltungs-sowie in&nbsp;Industrieanlagen.</p>
<p style="text-align: justify; font-size: 92%">In&nbsp;der Firma gibt es&nbsp;keine zuf&auml;llige Menschen. Alle sind erstklassige Luftfahrt-Experten, professionelle Manager, die vom Himmel begeistert sind.</p>

<? } elseif($this->site_lang=='fr'){ ?>

<h2>Information sur les organisateurs</h2>
<strong style="color:#08509d">UFS Investment Company</strong>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;fournit aux individus un&nbsp;acc&egrave;s &agrave;&nbsp;des outils financiers et&nbsp;des solutions financi&egrave;res, capable de&nbsp;g&eacute;n&eacute;rer de&nbsp;l&rsquo;argent suppl&eacute;mentaire.</p>
<p style="text-align: justify; font-size: 92%">UFS IC&nbsp;aide &eacute;galement aux entreprises &agrave;&nbsp;atteindre les strat&eacute;gies d&eacute;clar&eacute;es, en&nbsp;attirant des capitaux dans le&nbsp;secteur r&eacute;el de&nbsp;l&rsquo;&eacute;conomie. La&nbsp;pens&eacute;e novatrice et&nbsp;l&rsquo;analyse permettent &agrave;&nbsp;l&rsquo;entreprise de&nbsp;trouver et&nbsp;de&nbsp;cr&eacute;er de&nbsp;nouvelles solutions de&nbsp;financement parmi une vaste gamme de&nbsp;l&rsquo;information, contribuant &agrave;&nbsp;l&rsquo;am&eacute;lioration du&nbsp;bien-&ecirc;tre de&nbsp;ses clients.</p>
<p style="text-align: justify; font-size: 92%">Les fonds de&nbsp;la&nbsp;soci&eacute;t&eacute; &laquo;UFS Gold&raquo; et&nbsp;&laquo;Pierre le&nbsp;Grand&raquo;, sont consid&eacute;r&eacute; comme l&rsquo;un des meilleurs dans l&rsquo;industrie de&nbsp;gestion de&nbsp;richesse. La&nbsp;gamme de&nbsp;Private Equity Funds de&nbsp;la&nbsp;soci&eacute;t&eacute; UFS IC&nbsp;permet aux investisseurs &agrave;&nbsp;gagner environ&nbsp;35% par an&nbsp;sur un&nbsp;court intervalle de&nbsp;temps.</p>
<br/>
<strong style="color:#08509d">Aerosoyuz</strong>
<p style="text-align: justify; font-size: 92%">La&nbsp;soci&eacute;t&eacute; d&rsquo;h&eacute;licopt&egrave;re &laquo;Aerosoyuz&raquo; est fond&eacute;e en&nbsp;2002&nbsp;et est le&nbsp;plus grand en&nbsp;Russie concessionnaire officiel et&nbsp;le&nbsp;centre de&nbsp;maintenance de&nbsp;Robinson Helicopter Company (USA), le&nbsp;centre officiel de&nbsp;maintenance deAgustaWestland (Italie), l&rsquo;agent et&nbsp;le&nbsp;centre de&nbsp;maintenance agr&eacute;&eacute; par Eurocopter (France), le&nbsp;centre de&nbsp;formation a&eacute;rienne de&nbsp;Robinson et&nbsp;Eurocopter. Les experts sont certifi&eacute;s et&nbsp;ont re&ccedil;u une formation sur les usines de&nbsp;fabrication. &laquo;Aerosoyuz&raquo; est une soci&eacute;t&eacute; de&nbsp;gestion des h&eacute;listations sur les sites de&nbsp;sespartenaires: dans les villages l&rsquo;&eacute;lite, dans les complexes sportives et&nbsp;de&nbsp;divertissement, ainsi que dans les entreprises industrielles.</p>
<p style="text-align: justify; font-size: 92%">Dans la&nbsp;soci&eacute;t&eacute; il&nbsp;n&rsquo; y&nbsp;pas de&nbsp;gens au&nbsp;hasard. Il&nbsp;n&rsquo;y a&nbsp;que des sp&eacute;cialistes d&rsquo;aviation hautement qualifi&eacute; et&nbsp;de&nbsp;gestionnaires professionnels, passionn&eacute;s du&nbsp;ciel.</p>

<? } } ?>

<? if(($this->last_page_name=='vnebirzhevue-operatsii-evroobligatsii')&&($this->site_lang=='ru')) { ?>      
	<div class="banner" style="margin-top: 12px">
    <a href="/application-form.html"><img src="/banners/application_form_ru.gif" width="230" height="67" alt=""/></a><br/>
    <? /* <a href="/investoram/mutual-funds/fond-alternativnoyi-ienergetiki.html"><img src="/banners/green_230-150.gif" width="230" height="150" alt=""/></a> */ ?>
  </div>
<? } ?>
<? if(($this->last_page_name=='vnebirzhevue-operatsii-evroobligatsii')&&($this->site_lang=='ru')) { ?>      
	<!-- <div class="banner" style="margin-top: 12px"><a href="/application-form.html"><img src="/banners/application_form_ru.gif" width="230" height="67" alt=""/></a><br><a href="/investoram/mutual-funds/fond-alternativnoyi-ienergetiki.html"><img src="/banners/green_230-150.gif" width="230" height="150" alt=""/></a></div> -->
<? } ?>
 <? 
if ($this->last_page_name=='head' || $this->last_page_name=='management') {
  ?><div style="padding: 12px; background-color: #f2f6f7; margin-bottom: 22px;"><?
  if ($this->site_lang=='ru') { ?>

    <p><i>&laquo;Ключевой составляющей группы компаний UFS&nbsp;IC является команда высоко квалифицированных специалистов, вместе прошедших через множество сделок и&nbsp;проектов. Накопленный ими опыт позволяет не&nbsp;только эффективно работать над существующими направлениями бизнеса, но&nbsp;и&nbsp;не&nbsp;упускать из&nbsp;вида новейшие разработки в&nbsp;области инвестиций&raquo;.</i></p>
    <p><strong>Елена Железнова,<br/>Управляющий партнер группы компаний UFS&nbsp;IC</strong></p>
    
  <? } elseif ($this->site_lang=='en') { ?>
    
    <p><i>A&nbsp;key component of&nbsp;the UFS&nbsp;IC group of&nbsp;companies is&nbsp;a&nbsp;team of&nbsp;highly qualified experts who passed together through hundreds of&nbsp;transactions and projects. Their experience enables them not only to&nbsp;work effectively over already established business areas, but also to&nbsp;take into consideration the most up-to-date developments in&nbsp;the field of&nbsp;investments.</i></p>
    <p><strong>Elena Zheleznova,<br/>Managing partner of&nbsp;the UFS&nbsp;IC group of&nbsp;companies</strong></p>
    
  <? } elseif ($this->site_lang=='de') { ?>
    
    <p><i>Ein wesentlicher Bestandteil der Unternehmensgruppe UFS&nbsp;IC ist die Mannschaft der hoch qualifizierten Experten, die zusammen Hunderte von Gesch&auml;ften und Projekten gef&uuml;hrt haben. Ihre Erfahrung erlaubt Ihnen, nicht nur effektiv an&nbsp;gefestigten Businessausrichtungen zu&nbsp;arbeiten, sondern auch die letzten Entwicklungen im&nbsp;Gebiet der Kapitalanlagen fest im&nbsp;Auge zu&nbsp;behalten.</i></p>
    <p><strong>Elena Zheleznova,<br/>Gesch&auml;ftsf&uuml;hrende Partner von Unternehmensgruppe UFS Investment Company</strong></p>
    
  <? } elseif ($this->site_lang=='fr') { ?>
    
    <p><i>La&nbsp;composante-cl&eacute; du&nbsp;groupe des companies l&rsquo;UFS&nbsp;IC est une &eacute;quipe d&rsquo;experts hautement qualifi&eacute;s qui ont effectu&eacute; une centaine de&nbsp;transactions et&nbsp;de&nbsp;projets. L&rsquo;exp&eacute;rience accumul&eacute;e leur permet non seulement de&nbsp;travailler d&rsquo;une mani&egrave;re efficace sur les caps de&nbsp;business d&eacute;j&agrave; bien &eacute;tabli&eacute;es, mais aussi de&nbsp;prendre en&nbsp;consid&eacute;ration les derniers d&eacute;veloppements dans le&nbsp;domaine des investissements</i></p>
    <p><strong>Elena Jeleznova,<br/>Partenaire g&eacute;rant du&nbsp;groupe des compagnies l&rsquo;UFS&nbsp;IC</strong></p>
  
  <? } ?>
  </div>
<? } ?>
<? if(($this->last_page_name=='osen-pora-novux-znaniyi')||($this->last_page_name!='investitsii-v-zoloto')&&(($this->last_page_name=='water-treatments-ufs')||($this->last_page_name=='meetings')||($this->last_page_name=='april-in-april')||($this->last_page_name=='april-im-april')||($this->last_page_name=='avril-en-avril')||($this->last_page_name=='learning'))) { ?>
<table style="border:none"> 
    <tbody>
        <tr>
            <td style="background-color: #f2f6f7;">
            <h2 style="text-align: left;"><? echo(dictionary('Обратите внимание')); ?></h2>
            <p><? echo(dictionary('Требуется предварительная регистрация.')); ?></p>
            <ul class="learn-more">
                <li class="robot"><? echo(dictionary('Телефон')); ?>:&nbsp;<nobr>+7 (495) 419-01-26</nobr></li>
                <li class="robot"><? echo(dictionary('Телефон')); ?>:&nbsp;<nobr>+7 (800) 234-02-02</nobr></li>
                <li class="subscribe">E-mail: <nobr><a href="mailto:apply@ufs-federation.com">apply@ufs-federation.com</a></nobr></li>
            </ul>
            <p><em><? echo(dictionary('Для зарегистрированных участников вход бесплатный.')); ?></em></p>
            </td>
        </tr>
    </tbody>
</table><? } ?>
   
<? if(($this->last_page_name=='leasing')||($this->last_page_name=='calculator')||($this->last_page_name=='special-offers')||($this->last_page_name=='sonderangebote')||($this->last_page_name=='preimyshcestva')||($this->last_page_name=='lizing')||($this->last_page_name=='686-leasing')||($this->last_page_name=='leasing-branches')||($this->last_page_name=='passenger-car-leasing')||($this->last_page_name=='leasing-of-commercial-vehicles')||($this->last_page_name=='truck-leasing')||($this->last_page_name=='machinery-leasing')||($this->last_page_name=='equipment-leasing')||($this->last_page_name=='operational-leasing')||($this->last_page_name=='our-services')||($this->last_page_name=='our-advantages')||($this->last_page_name=='partners')||($this->last_page_name=='leasingsbranche')||($this->last_page_name=='pkw-leasing')||($this->last_page_name=='pkw-lesing')||($this->last_page_name=='lkw-leasing')||($this->last_page_name=='leasing-von-speziellen-anlagen')||($this->last_page_name=='leasing-von-ausrstung')||($this->last_page_name=='operatives-leasing')||($this->last_page_name=='unsere-vorteile')||($this->last_page_name=='unsere-service')||($this->last_page_name=='crdit-bail')||($this->last_page_name=='branches-de-crdit-bail')||($this->last_page_name=='crdit-bail-de-vhicule-lger')||($this->last_page_name=='crdit-bail-de-vhicule-lger-commercial')||($this->last_page_name=='crdit-bail-de-camion')||($this->last_page_name=='crdit-bail-de-matriels')||($this->last_page_name=='l')||($this->last_page_name=='crdit-bail-oprationnel')||($this->last_page_name=='nos-services')||($this->last_page_name=='avantages')||($this->last_page_name=='partenaires')||($this->last_page_name=='napravleniya-lizinga')||($this->last_page_name=='spetspredlozheniya')||($this->last_page_name=='partneru')||($this->last_page_name=='preimyshcestva-lizinga')||($this->last_page_name=='yslygi')||($this->last_page_name=='operatsionnuyi-lizing-legkovogo-transporta')||($this->last_page_name=='lizing-oborydovaniya')||($this->last_page_name=='lizing-spetstexniki')||($this->last_page_name=='lizing-gryzovogo-avtotransporta')||($this->last_page_name=='lizing-legkovogo-kommercheskogo-avtotransporta')||($this->last_page_name=='o-lizinge')||($this->last_page_name=='lizing-legkovogo-avtotransporta')) { ?>
<script> 
  function notify_leasing(obj)  
  {  
    document.getElementById('text-hide').innerHTML = ' ';
    var word = document.getElementById("word").value;
    var url='<? echo($this->phpself) ?>notify.html?type=nsend&captcha='+word+'&name='+document.getElementById("mce-NAME").value+'&phone='+document.getElementById("mce-PHONE").value+'&object='+document.getElementById("mce-OBJECT").value+'&cost='+document.getElementById("mce-COST").value+'&prepayment='+document.getElementById("mce-PREPAYMENT").value+'&contractdate='+document.getElementById("mce-CONTRACTDATE").value+'&typepayment='+document.getElementById("mce-TYPEPAYMENT").value;
    $.getJSON(url,{format:'json'}).done(function(data) {    
     if (data) {
        var o = document.getElementById("div-info-p");
        
        if (data.success) {
          o.innerHTML = "<? echo(dictionary('Спасибо, Ваша заявка принята')); ?>";
        } else {
          o.innerHTML = data.message;
        }
        document.getElementById("div-info").style.display = "inline";
      }
    });
    return false;
  }
  function info_close() {
    var o = document.getElementById("div-info");
    if (o) {
      o.style.display = "none";
    }
  }
</script>

<div class="section_extra">
    <h2><? echo(dictionary('Online заявка на лизинг')); ?></h2>
    <div class="subscription-box" style="margin-left: 0; margin-bottom: 22px">
        <div id="mc_embed_signup">
            <form>
         <h5 style="margin-top:  5px;"><? echo(dictionary('Представьтесь, пожалуйста:')); ?> <span></span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="name" id="mce-NAME"  />
         <h5 style="margin-top:  5px;"><? echo(dictionary('Контакты для связи:')); ?> <span></span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="phone" id="mce-PHONE" />
         <h5 style="margin-top:  5px;"><? echo(dictionary('Предмет лизинга:')); ?> <span></span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="object" id="mce-OBJECT" />
         <h5 style="margin-top:  5px;"><? echo(dictionary('Стоимость предмета лизинга с НДС:')); ?> <span></span></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="cost" id="mce-COST"/>
         <h5 style="margin-top:  5px;"><? echo(dictionary('Аванс в процентах')); ?>: <span></span></h5>
         <span style="margin-left: 12px;"><select style="border: 1px solid #c2cdcf; box-shadow: inset 1px 1px 1px rgba(0,0,0,0.1); width: 160px; " onchange="changeSel(this);" id="mce-PREPAYMENT"><option></option>
                <option value="5%">5%</option>
                <option value="10%">10%</option>
                <option value="15%">15%</option>
                <option value="20%">20%</option>
                <option value="25%">25%</option>
                <option value="30%">30%</option>
                <option value="35%">35%</option>
                <option value="40%">40%</option>
                <option value="45%">45%</option>
                <option value="49%">49%</option>
            </select>
         </span>
       <h5 style="margin-top:  5px;"><? echo(dictionary('Срок договора лизинга в месяцах')); ?>: <span></span></h5>
           <span  style="margin-left: 12px;"><select style="border: 1px solid #c2cdcf; box-shadow: inset 1px 1px 1px rgba(0,0,0,0.1); width: 160px; " onchange="changeSel(this);" id="mce-CONTRACTDATE">
                <option></option>
                <option value="12">12</option>
                <option value="18">18</option>
                <option value="24">24</option>
                <option value="30">30</option>
                <option value="36">36</option>
           </select>
        </span>
        <h5 style="margin-top:  5px;"><? echo(dictionary('Тип платежей')); ?>: <span></span></h5>
            <span style="margin-left: 12px;"><select style="border: 1px solid #c2cdcf; box-shadow: inset 1px 1px 1px rgba(0,0,0,0.1); width: 160px; " onchange="changeSel(this);" id="mce-TYPEPAYMENT">
                <option></option>
                <option value="Аннуитет"><? echo(dictionary('Аннуитет')); ?></option>
                <option value="Дегрессия"><? echo(dictionary('Дегрессия')); ?></option>
            </select></span>
        <h5 style="margin-top:  10px; margin-bottom: 10px;"><input type="checkbox" name="right" value="a1" checked>&nbsp;<? echo(dictionary('Даю согласие на обработку персональных данных')); ?></h5>
        <div style="margin-left: 40px;">    
            <? if (isset($captcha_image)) {  echo($captcha_image);  ?>
             <input type="text" id="word" style=" width: 92px; height:30px; text-align: center;" name="word" placeholder="<? echo(dictionary('Число с картинки')); ?>" />
             <? }  ?></div></div>
            </form>
            
       <div id="div-info" style="display: none; float:none; width:100%" class="conference-inner">
            <p id="div-info-p">&#160;</p>
        </div>
        <div id="text-hide">
            <p>&#160;</p>
        </div>
        <div class="clear"><input type="button" style="border:0; display: block; margin: auto; margin-bottom: 7px;" onclick="notify_leasing(this);" value="<? echo(dictionary('Отправить')); ?>" name="notify" id="mc-embedded-subscribe" class="button" /></div>
    </div>
<!--    <div class="banner"><table style="border:none"><td><a href="#"><img src="/upload/calc.jpg"></a></td><td style="vertical-align: middle"><a href="#"><? echo(dictionary('Сделайте расчет')); ?></a></td></table></div> -->
    <div class="banner">
        <? if($this->site_lang=='ru') { ?>
        <a href="/pages/lizing/spetspredlozheniya.html" class="deals"><? echo(dictionary('Специальные предложения')); ?></a>
            <? } ?>
        <? if($this->site_lang=='en') { ?>
        <a href="http://en.ufs-federation.com/pages/leasing/special-offers.html" class="deals"><? echo(dictionary('Специальные предложения')); ?></a> 
            <? } ?>
        <? if($this->site_lang=='de') { ?> 
        <a href="http://de.ufs-federation.com/pages/leasing/sonderangebote.html" class="deals"><? echo(dictionary('Специальные предложения')); ?></a>
            <? } ?>
        <? if($this->site_lang=='fr') { ?> 
        <a href="http://fr.ufs-federation.com/pages/crdit-bail/offres-speciales.html" class="deals"><? echo(dictionary('Специальные предложения')); ?></a>
            <? } ?>

            </div>
<? } ?>    
    <? if(($this->last_page_name!='717-registratsiya-ychastnikov')  ||($this->last_page_name!='registratsiya-ychastnikov')) { ?>
<? if(($this->last_page_name=='learning')|| ($this->last_page_name!='meetings')|| ($this->last_page_name=='ychebnuyi-tsentr')|| ($this->last_page_name=='training-center')||($this->last_page_name=='trainingszentrum') ||($this->last_page_name=='centre-dtudes'))  { ?> 
<? if($this->last_page_name!='osobennosti-nalogooblozheniya-operatsiyi-s-tsennumi-bymagami'){ ?>
<!--    <h2><? echo(dictionary('Скачать расписание занятий')); ?></h2> -->
<? if($this->site_lang=='ru') { ?>  
<!--<p><a href="/upload/schedule/n-rus.jpg" target="_blank"><? echo(dictionary('Ноябрь')); ?></a> <small>(jpg, 0,97 Мб)</small></p>-->
<? } ?>
<? if($this->site_lang=='en') { ?>  
<!--<p><a href="/upload/schedule/n-eng.jpg" target="_blank"><? echo(dictionary('Ноябрь')); ?></a> <small>(jpg, 1,2 Mb)</small></p>-->
<? } ?>
<? if($this->site_lang=='de') { ?>  
<!--<p><a href="/upload/schedule/n-nem.jpg" target="_blank"><? echo(dictionary('Ноябрь')); ?></a> <small>(jpg, 0,97 Mb)</small></p>-->
<? } ?>
<? if($this->site_lang=='fr') { ?>  
<!--<p><a href="/upload/schedule/n-fran.jpg" target="_blank"><? echo(dictionary('Ноябрь')); ?></a> <small>(jpg, 0,97 Mb)</small></p>-->
<? } ?>
    
<? } ?> 
<? } ?> 

<? if(($this->last_page_name=='zolotaya-osen') ||($this->last_page_name=='master-klassu')||($this->last_page_name=='master-classes')||($this->last_page_name=='master-klasse')){ ?>  
<table style="border:none">
    <tbody>
        <tr>  
            <td style="background-color: #f2f6f7;">
            <h2 style="text-align: left;"><? echo(dictionary('Зарегистрироваться')); ?></h2>
            <ul class="learn-more">
                <li class="robot" style="text-align: left;"><? echo(dictionary('позвонив по телефону')); ?> <strong><nobr>+7 (495) 781-73-00</nobr></strong></li>
                <li style="text-align: left;"><? echo(dictionary('отправив письмо на')); ?> <a href="mailto:apply@ufs-federation.com">apply@ufs-federation.com</a></li>
                <li style="text-align: left;"><? echo(dictionary('заполнив')); ?> <strong><? echo(dictionary('регистрационную форму')); ?></strong></li>
            </ul>
            </td>
        </tr>
    </tbody>
</table>
<? } ?>
 
<!-- +'&date='+document.getElementById("mce-DATE").value -->
    <? /* if(($this->last_page_name=='lautomne-est-le-moment-de-nouvelles-connaissances')||($this->last_page_name=='hydrothrapie-avec-lufs-ic')||($this->last_page_name=='herbst-ist-die-zeit-des-neuen-wissens')||($this->last_page_name=='autumn-is-time-for-new-knowledge')||($this->last_page_name=='osen-pora-novux-znaniyi')||($this->last_page_name=='avril-en-avril')||($this->last_page_name=='april-im-april')||($this->last_page_name=='april-in-april')||($this->last_page_name=='water-treatments-ufs')||($this->last_page_name=='2-sep')||($this->last_page_name=='26-aug')||($this->last_page_name=='19-aug')||($this->last_page_name=='12-aug')||($this->last_page_name=='meetings')||($this->last_page_name=='717-registratsiya-ychastnikov')||($this->last_page_name=='master-klassu')||($this->last_page_name=='master-classes')||($this->last_page_name=='master-klasse')||($this->last_page_name=='learning')||($this->last_page_name=='training-center')||($this->last_page_name=='trainingszentrum')||($this->last_page_name=='centre-dtudes')||($this->last_page_name=='master-klassu')||($this->last_page_name=='zolotaya-osen')||($this->last_page_name=='registratsiya-ychastnikov')||($this->last_page_name=='program-of-events')||($this->last_page_name=='programm-der-veranstaltungen')||($this->last_page_name=='programme-des-vnements')||($this->last_page_name=='winter-fireplace')||($this->last_page_name=='mandarinovoe-nastroenie')||($this->last_page_name=='mandarin-mood')||($this->last_page_name=='mandarinen-stimmung')||($this->last_page_name=='humeur-de-mandarine')) { */ ?>
<? if(($this->last_page_name=='summer-veranda')) { ?>
<script>
   function notify1(obj)  
   {  
    document.getElementById('text-hide').innerHTML = ' ';
    var url='<? echo($this->phpself) ?>notify.html?type=send&email='+document.getElementById("mce-EMAIL").value+'&name='+document.getElementById("mce-NAME").value+'&fathername='+document.getElementById("mce-FATHERNAME").value+'&secondname='+document.getElementById("mce-SECONDNAME").value+'&phone='+document.getElementById("mce-PHONE").value; 
    $.getJSON(url,{format: 'json'}).done(function(data) {    
     if (data) {
        var o = document.getElementById("div-info-p");
        
        if (data.success) {
          o.innerHTML = "<? echo(dictionary('Спасибо за Вашу регистрацию. Специалисты компании отправят Вам на электронную почту место и время нашей следующей встречи')); ?>";
                } else {
          o.innerHTML = "<? echo(dictionary('Ошибка. Попробуйте еще раз')); ?>";
        }
        document.getElementById("div-info").style.display = "inline";
      }
    });
    return false;
  }
  
  function info_close() {
    var o = document.getElementById("div-info");
    if (o) {
      o.style.display = "none";
    }
  }
</script>

<div class="section_extra">
    <h2><? echo(dictionary('Регистрация участников')); ?></h2>
    <div class="subscription-box" style="margin-left: 0; margin-bottom: 22px">
        <h5><strong>1. <? echo(dictionary('Персональные данные')); ?></strong></h5>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Имя*:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="name" id="mce-NAME" class="email required" /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Отчество:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="fathername" id="mce-FATHERNAME"  /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Фамилия:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="secondname" id="mce-SECONDNAME"  /></div>
        <div>&nbsp;</div>
        <h5><strong>2. <? echo(dictionary('Контактная информация')); ?></strong></h5>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;E-mail*:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="email" class="email required" id="mce-EMAIL" /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Телефон*:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="phone" id="mce-PHONE"/></div>
        <div>&nbsp;</div>
    	<!--
        <h5><strong>3. <? echo(dictionary('Дата мероприятия')); ?></strong></h5>
        <h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Выберите мероприятие')); ?></h5>   
        <div id="mc_embed_signup">&nbsp;&nbsp;&nbsp;
            <select style="width:100%" onchange="changeSel(this);" id="mce-DATE">
              <option value="20/01/2015"><? echo(dictionary('Летняя веранда')); ?></option>
            </select>
        </div> 
        <div>&nbsp;</div>
        -->
        <h5><input type="checkbox" name="right" value="a1" checked>&nbsp;<? echo(dictionary('Даю согласие на обработку персональных данных')); ?></h5>
        <div>&nbsp;</div>
        <div><h5><? echo(dictionary('Поля, отмеченные *, обязательны для заполнения')); ?></h5></div>
        <div id="div-info" style="display: none; float:none; width:100%" class="conference-inner">
            <p id="div-info-p">&#160;</p>
        </div>
        <div id="text-hide">
            <p>&#160;</p>
        </div>
        <div class="clear"><input type="button" onclick="notify1(this);" value="<? echo(dictionary('Регистрация')); ?>" name="notify" id="mc-embedded-subscribe" class="button" /></div>
    </div>

    <? } ?>






<? if(($this->last_page_name=='gss')){ ?>
<script>
   function notify1(obj)  
   {  
    document.getElementById('text-hide').innerHTML = ' ';
    var url='<? echo($this->phpself) ?>notify.html?type=send&email='+document.getElementById("mce-EMAIL").value+'&name='+document.getElementById("mce-NAME").value+'&fathername='+document.getElementById("mce-FATHERNAME").value+'&secondname='+document.getElementById("mce-SECONDNAME").value+'&phone='+document.getElementById("mce-PHONE").value; 
    $.getJSON(url,{format: 'json'}).done(function(data) {    
     if (data) {
        var o = document.getElementById("div-info-p");
        
        if (data.success) {
          o.innerHTML = "<? echo(dictionary('Спасибо за Вашу заявку. Специалисты компании свяжутся с Вами в ближайшее время.')); ?>";
                } else {
          o.innerHTML = "<? echo(dictionary('Ошибка. Попробуйте еще раз')); ?>";
        }
        document.getElementById("div-info").style.display = "inline";
      }
    });
    return false;
  }
  
  function info_close() {
    var o = document.getElementById("div-info");
    if (o) {
      o.style.display = "none";
    }
  }
</script>

<div class="section_extra">
    <h2><? echo(dictionary('Заявка на размещение')); ?></h2>
    <div class="subscription-box" style="margin-left: 0; margin-bottom: 22px">
        <!-- <h5><strong>1. <? echo(dictionary('Персональные данные')); ?></strong></h5> -->
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Email*:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="name" id="mce-NAME" class="email required" /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Организация:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="fathername" id="mce-FATHERNAME"  /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Должность:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="secondname" id="mce-SECONDNAME"  /></div>
        <!-- <h5><strong>2. <? echo(dictionary('Контактная информация')); ?></strong></h5> -->
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Телефон*:')); ?></h5>&nbsp;&nbsp;&nbsp;<input type="text" name="phone" id="mce-PHONE"/></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;Желаемый объем на&nbsp;покупку*:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="email" class="email required" id="mce-EMAIL" /></div>
        <div>&nbsp;</div>
        <!-- <h5><strong>3. <? echo(dictionary('Дата мероприятия')); ?></strong></h5>
        <h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Выберите дату мероприятия')); ?></h5>   
        <div id="mc_embed_signup">&nbsp;&nbsp;&nbsp;
            <select style="width:100%" onchange="changeSel(this);" id="mce-DATE">
              <option value="16/12/2014"><? echo(dictionary('16/12 - Долговой рынок: свежие идеи и рекомендации')); ?></option>
            </select>
        </div> 
        <div>&nbsp;</div>
        <h5><input type="checkbox" name="right" value="a1" checked>&nbsp;<? echo(dictionary('Даю согласие на обработку персональных данных')); ?></h5> -->
        <div>&nbsp;</div>
        <div><h5><? echo(dictionary('Поля, отмеченные *, обязательны для заполнения')); ?></h5></div>
        <div id="div-info" style="display: none; float:none; width:100%" class="conference-inner">
            <p id="div-info-p">&#160;</p>
        </div>
        <div id="text-hide">
            <p>&#160;</p>
        </div>
        <div class="clear"><input type="button" onclick="notify1(this);" value="<? echo(dictionary('Отправить')); ?>" name="notify" id="mc-embedded-subscribe" class="button" /></div>
    </div>

    <? } ?> 








    
    
    
    <? if((($this->last_page_name=='itrading') || ($this->last_page_name=='onlayin-zayavka-brokery') || ($this->last_page_name=='application-form'))&&($this->site_lang=='ru')) { ?>  
<h2>Наши тарифы</h2>
<table style="border: 1px;">
    <tbody>
        <tr>
            <td>
            <p style="text-align: center;"><small><b>Рынок</b></small></p>
            </td>
            <td>
            <p style="text-align: center;"><small><b>Комиссия брокера</b></small></p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;"><nobr><small>ЗАО &laquo;ФБ ММВБ&raquo;</small></nobr><small> (основной рынок) зависит от&nbsp;оборота</small></td>
            <td style="text-align: center;"><small>От&nbsp;0,03% и&nbsp;ниже</small></td>
        </tr>
        <tr>
            <td style="text-align: center;"><small>ВНБР (</small><nobr><small>в т. ч.</small></nobr><small> RTS Board)</small></td>
            <td style="text-align: center;"><small>0,085% от&nbsp;суммы сделки, но&nbsp;не&nbsp;менее 850&nbsp;рублей за&nbsp;одну сделку</small></td>
        </tr>
        <tr>
            <td style="text-align: center;"><small>ОАО </small><nobr><small>ММВБ-РТС</small></nobr><small> (FORTS) Все контракты</small></td>
            <td style="text-align: center;"><small>0,8&nbsp;рублей за&nbsp;1 контракт</small></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><a href="http://www.ufs-finance.com/uploads/pr4.pdf" target="_blank" style="color: rgb(17, 85, 204);"><small><strong>Смотреть тарифы полностью</strong></small></a></td>
        </tr>
    </tbody>
</table>

   <? } ?>
    <? if(($this->last_page_name=='brokerage')||($this->last_page_name=='vnebirzhevue-operatsii-evroobligatsii')||($this->last_page_name=='trust')||($this->last_page_name=='meetings')||($this->last_page_name=='nagradu')){ ?>
 <!--   <br><a href="/pages/analitika/runok-aktsiyi/advice.html"><img src="/banners/bloomberg2014_230_150.gif" width="230" height="150" alt="">
      </a> -->
     <? } ?> 
    
    <? if(($this->last_page_name=='investitsii-v-zoloto')||($this->last_page_name=='gold-standart')){ ?> 
           <a href="/pages/investoram/stat-klientom.html" class="become-client" ><? echo(dictionary('Стать инвестором фонда')); ?></a>
        <br/>
    <? } ?>  
    <? if(($this->last_page_name=='history')||($this->last_page_name=='ufsgold')&&($this->site_lang=='ru')) { ?>  
        <a href="/pages/investoram/stat-klientom.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
   <? if($this->last_page_name=='osobennosti-nalogooblozheniya-operatsiyi-s-tsennumi-bymagami'){ ?> 
    <? if($this->site_lang=='ru') { ?>  
        <a href="/pages/investoram/stat-klientom.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
        <br/>
        <!--<a href="http://ru.ufs-federation.com/pages/o-kompanii/teplue-vstrechi-y-kamina.html">
        <img src="/banners/kamin_ru_230x150.gif" width="230" height="150" alt="">
      </a> -->
     <? } ?> 
     <? if($this->site_lang=='en') { ?> 
        <a href="/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
     <? if($this->site_lang=='de') { ?> 
        <a href="/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
     <? if($this->site_lang=='fr') { ?> 
      <a href="/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
   <? }
        echo($this->load->view('body_subscriber','',true)) ?>
 <? } ?> 
      
    <? if(($this->last_page_name=='stat-klientom')||($this->last_page_name=='722-stat-klientom') ||($this->last_page_name=='become-the-client')) { ?>
<script>
   function notify2(obj)
  {
    var url='<? echo($this->phpself) ?>notify.html?type=nsend&email='+document.getElementById("mce-EMAIL").value+'&name='+document.getElementById("mce-NAME").value+'&phone='+document.getElementById("mce-PHONE").value+'&prod='+document.getElementById("mce-PROD").value+'&city='+document.getElementById("mce-CITY").value+'&check='+document.getElementById("mce-CHECK");
   $.getJSON(url,{format: 'json'}).done(function(data) {
      if (data) {
        var o = document.getElementById("div-info-p");
        if (data.success) {
          o.innerText = "<? echo(dictionary('Спасибо, ваша заявка отправлена в департамент развития. В самое ближайшее время специалисты свяжутся с вами.')); ?>";
        } else {
          o.innerText = "<? echo(dictionary('Ошибка. Пожалуйста, заполните все необходимые поля')); ?>";
        }
        document.getElementById("div-info").style.display = "inline";
      }
    });
    return false;
  }
  
  function become_client(obj)
  {
    var url='<? echo($this->phpself) ?>notify.html?type=become_client&name='+document.getElementById("mce-NAME").value+
                                                                    '&city='+document.getElementById("mce-CITY").value+
                                                                   '&phone='+document.getElementById("mce-PHONE").value+
                                                                   '&email='+document.getElementById("mce-EMAIL").value+
                                                                    '&prod='+document.getElementById("mce-PROD").value+
                                                                   '&check='+document.getElementById("mce-CHECK");
   $.getJSON(url,{format: 'json'}).done(function(data) {
      if (data) {
        var o = document.getElementById("div-info-p");
        if (data.success) {
          o.innerText = "<? echo(dictionary('Спасибо, ваша заявка отправлена. В самое ближайшее время специалисты свяжутся с вами.')); ?>";
        } else {
          o.innerText = "<? echo(dictionary('Ошибка. Пожалуйста, заполните все необходимые поля')); ?>";
        }
        document.getElementById("div-info").style.display = "inline";
      }
    });
    return false;
  }  
  
  function info_close() {
    var o = document.getElementById("div-info");
    if (o) {
      o.style.display = "none";
    }
  }
</script>
<? } ?>

<? if($this->last_page_name=='ufs' || ($this->last_page_name=='brokerage')||($this->last_page_name=='itrading')||($this->last_page_name=='trust')||($this->last_page_name=='fond-umnuyi-robot-arbitrazh')||($this->last_page_name=='ezhednevnue-kommentarii')||($this->last_page_name=='ezhednevnue-obzoru')||($this->last_page_name=='makroiekonomicheskie-obzoru')) { ?> 
<? if($this->site_lang=='ru') { ?>  
        <a href="/pages/investoram/stat-klientom.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
<? } elseif($this->site_lang=='en' || $this->site_lang=='de' || $this->site_lang=='fr') { ?> 
        <a href="/pages/for-investors/become-the-client.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
     <? } ?> 
<? } ?><br>


<? if(($this->last_page_name=='trust')||($this->last_page_name=='trust-management')||($this->last_page_name=='treuhandverwaltung')){ ?>
<!-- <table class="leasing-table" style="border:none; font-size: 100%; padding: 0em;">
  <tbody>
    <tr></tr>
    <tr>
      <td style="border:none; text-align:center; background: #002954; font-size: 100%; color:white;padding: 0.5em;" colspan="2"><? echo(dictionary('3 квартал 2013 г.')); ?></td>
    </tr>
    <tr></tr> 
    <tr>
      <td style="border:none;vertical-align: middle; font-size: 100%; text-align: center;" class="blue"><span style="font-size:25px">10,65%</span></td>
      <td class="blue" style="border:none; font-size: 100%; padding: 1em 0.5em;"><small><? echo(dictionary('Результат управления портфелем, в % годовых')); ?></small>.</td>
    </tr>
    <tr></tr>
    <tr style="border-top: 1px solid #fff;">
      <td style="border:none; vertical-align: middle;font-size: 100%; text-align: center;" class="blue"><span style="font-size:25px;text-align: center;">9,03%</span></td>
      <td class="blue" style="border:none;font-size: 100%; padding: 1em 0.5em;"><small><? echo(dictionary('Средняя максимальная ставка по вкладам TOP-10 банков по данным ЦБ')); ?>.</small></td>
    </tr>
    <tr></tr>
  </tbody>
</table> -->
<p>&nbsp;</p>    
<!--<p style="cursor:default" class="icon-service big-service sprite06"><span style="font-size:25px">14,18%</span><br><small>Результат управления портфелем<br>1 кв. 2013&nbsp;г.</small></p>
<p style="cursor:default" class="icon-service big-service sprite10"><span style="font-size:25px">9,87%</span><br><small>Средняя максимальная ставка по вкладам <nobr>TOP-10</nobr> банков по данным ЦБ.</small></p>

<p>&nbsp;</p> -->
<!--<p style="cursor:default" class="icon-service big-service sprite06"><small>Результат управления<br>портфелем&nbsp;&mdash;</small> <strong>14,18%</strong><br><small>1 кв. 2013&nbsp;г.</small></p>
<p style="cursor:default" class="icon-service big-service sprite10"><small>Средняя максимальная<br>ставка по вкладам <nobr>TOP-10</nobr><br>банков по данным ЦБ,<br>1 кв. 2013&nbsp;г.&nbsp;&mdash;</small> <strong>9,87%</strong>.</p>
<p>&nbsp;</p>    -->
    <? } ?> 
<? if(($this->last_page_name!='avril-en-avril')&&($this->last_page_name!='crossword')&&($this->last_page_name!='april-im-april')&&($this->last_page_name!='april-in-april')&&($this->last_page_name!='napravleniya-lizinga')&&($this->last_page_name!='lizing-legkovogo-avtotransporta')&&($this->last_page_name!='lizing-legkovogo-kommercheskogo-avtotransporta')&&($this->last_page_name!='lizing-gryzovogo-avtotransporta')&&($this->last_page_name!='lizing-spetstexniki')&&($this->last_page_name!='lizing-oborydovaniya')&&($this->last_page_name!='operatsionnuyi-lizing-legkovogo-transporta')&&($this->last_page_name!='yslygi')&&($this->last_page_name!='preimyshcestva')&&($this->last_page_name!='partneru')&&($this->last_page_name!='leasing')&&($this->last_page_name!='team')&&($this->last_page_name!='meetings')&&($this->last_page_name!='master-klassu')&&($this->last_page_name!='vebinaru')&&($this->last_page_name!='news')&&($this->last_page_name!='672-dlya-zolotodobutchikov')&&($this->last_page_name!='672-dlya-zolotodobutchikov')&&($this->last_page_name!='learning')&&($this->last_page_name!='license')&&($this->last_page_name!='head')&&($this->last_page_name!='contact')&&($this->last_page_name!='press')&&($this->last_page_name!='vesna-na-finansovom-runke')&&($this->last_page_name!='history')) { ?>
<!--<h2><? echo(dictionary('Смотрите также')); ?></h2>
 <? if(($this->site_lang=='ru')&&($this->last_page_name=='happy-birthday-meetings')) { ?>
<h4>Зарегистрируйтесь на встречу</h4>
<ul class="learn-more">
   <li class="robot">+7 (495) 781-73-00, <nobr>+7 (495) 781-73-02;</nobr></li>
<li class="message"><a href="mailto:apply@ufs-federation.com?subject=Happy birthday meetings">apply@ufs-federation.com</a>.</li>
</ul>
 <? } ?>    
     <? if($this->last_page_name=='osen-pora-novux-znaniyi') { ?>  
<ul class="learn-more">
    <li class="subscribe"><a href="/pages/o-kompanii/water-treatments-ufs.html#comment">Отзывы по прошлым встречам</a></li>
</ul>
<? } ?> 
-->
    <? if(($this->site_lang=='ru')&&($this->last_page_name=='sochi2014')) { ?>  
     <div class="banner">
     <!--  <a href="/investoram/brokerage/aktsiya-14.html">
        <img src="/banners/1+4_2014_230х150.gif" width="230" height="150" alt="">
        </a><br/> -->
        <a href="/pages/investoram/stat-klientom.html" class="become-client" ><? echo(dictionary('Стать клиентом')); ?></a>
        <br/>
       <!-- <a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html">
        <img src="/banners/kamin_ru_230x150.gif" width="230" height="150" alt="">
        </a><br/>-->
        <a href="https://itunes.apple.com/ru/app/ufs-investment-company/id796967484?mt=8" target="_blank"><img width="230px" src="/banners/ufs-appstore.png"></a><br/>
        <a href="https://play.google.com/store/apps/details?id=ru.ideast.ufs" target="_blank"><img src="/banners/ufs-googleplay.png" width="230" height="150" alt=""></a><br/> 
    </div> <? } ?> 

<? if(($this->site_lang=='ru')&&($this->last_page_name=='wealth-management')) { ?>
    <ul class="learn-more">
  <!--  <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li>-->
    <li class="gold"><a href="/investitsii-v-zoloto/investitsii-v-zoloto.html"><small>«Золотой Фонд»</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
    <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='ru')&&($this->last_page_name=='318-assets')) { ?> 
 <ul class="learn-more">
    <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
    <li class="strategy"><a href="/pages/investoram/trust.html"><small>Стратегии доверительного управления</small></a></li>
    <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
    <? } ?> 

<? if(($this->site_lang=='ru')&&($this->last_page_name=='ufs')) { ?>
 <ul class="learn-more">
    <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
   <!-- <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
    <? } ?> 

<? if(($this->site_lang=='ru')&&($this->last_page_name=='financial-d')) { ?>
 <ul class="learn-more">
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
    <? } ?> 

<? if(($this->site_lang=='ru')&&($this->last_page_name=='nagradu')) { ?>
 <ul class="learn-more">
  <!-- <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li>-->
    <li class="strategy"><a href="/pages/analitika/komanda-analitiki.html"><small>Команда аналитиков</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
    <? } ?> 
<? if(($this->site_lang=='ru')&&($this->last_page_name=='application-form')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/investoram/trust.html"><small>Стратегии доверительного управления</small></a></li>
        <li class="gold"><a href="/investitsii-v-zoloto/investitsii-v-zoloto.html"><small>«Золотой Фонд»</small></a></li>
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
        <li class="gold"><a href="/pages/o-kompanii/nagradu.html"><small>Награды</small></a></li>
</ul>
<? } ?>
    <? if(($this->site_lang=='ru')&&(($this->last_page_name=='trust')||($this->last_page_name=='strategiya-ypravleniya-aktsii-sootvetstvyyushcie-printsipam-islamskix-finansov')||($this->last_page_name=='strategiya-ypravleniya-nezavisimaya-rossiyiskiyi-runok')||($this->last_page_name=='strategiya-1')||($this->last_page_name=='strategiya-3')||($this->last_page_name=='381-sbalansirovannaya-runok-rf'))) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
      <!--  <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?>

<? if(($this->site_lang=='ru')&&($this->last_page_name=='526-depozitariyi')) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="/pages/investoram/526-depozitariyi/dividendu.html"><small><? echo(dictionary('Дивиденды')); ?></small></a></li>
        <li class="gold"><a href="/pages/analitika/dividendnuyi-kalendar.html"><small><? echo(dictionary('Дивидендный календарь')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&($this->last_page_name=='546-depository-services')) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="http://en.ufs-federation.com/pages/for-investors/546-depository-services/dividends.html"><small><? echo(dictionary('Дивиденды')); ?></small></a></li>
        <li class="gold"><a href="/pages/research/dividend-calendar.html"><small><? echo(dictionary('Дивидендный календарь')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='depositar')) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="http://en.ufs-federation.com/pages/for-investors/546-depository-services/dividends.html"><small><? echo(dictionary('Дивиденды')); ?></small></a></li>
        <li class="gold"><a href="http://en.ufs-federation.com/pages/research/dividend-calendar.html"><small><? echo(dictionary('Дивидендный календарь')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='526-depozitariyi')) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="http://en.ufs-federation.com/pages/for-investors/546-depository-services/dividends.html"><small><? echo(dictionary('Дивиденды')); ?></small></a></li>
        <li class="gold"><a href="http://en.ufs-federation.com/pages/research/dividend-calendar.html"><small><? echo(dictionary('Дивидендный календарь')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>


<? if(($this->site_lang=='ru')&&($this->last_page_name=='fond-alternativnoyi-ienergetiki')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
        <li class="strategy"><a href="/pages/investoram/trust.html"><small>Стратегии доверительного управления</small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&($this->last_page_name=='green-energy-and-development-fund')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="strategy"><a href="/pages/for-investors/trust-management.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='green-energy-and-development')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='fond-alternativnoyi-ienergetiki')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="strategy"><a href="/pages/vous-tes-investisseurs/trust.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>


<? if(($this->site_lang=='ru')&&($this->last_page_name=='503-fond-pryamux-investitsiyi')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
        <li class="strategy"><a href="/pages/analitika/dolgovoyi-runok/torgovue-idei.html"><small>Торговые идеи</small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
        <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
    <!--    <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&($this->last_page_name=='fond-of-direct-investments')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
    <!--    <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='555-private-equity-fund')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
    <!--    <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='503-fond-pryamux-investitsiyi')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
    <!--    <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>





<? if(($this->site_lang=='ru')&&($this->last_page_name=='fond-pyotr-velikiyi')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/analitika/dolgovoyi-runok/torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/investoram/trust.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="model"><a href="/application-form.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&($this->last_page_name=='520-peter-the-great-fund')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/for-investors/trust-management.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='556-peter-the-great-fund')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='fond-pyotr-velikiyi')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/vous-tes-investisseurs/trust.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>


<? if(($this->site_lang=='en')&&($this->last_page_name=='trust-management')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="strategy"><a href="/pages/about-us/april-in-april.html"><small><? echo(dictionary('Апрель в «Апреле»')); ?></small></a></li>
        <!--<li class="strategy"><a href="/pages/about-us/warm-meetings-by-the-fireplace.html"><small><? echo(dictionary('Теплые встречи у камина')); ?></small></a></li>
     -->   <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='treuhandverwaltung')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
   <!--    <li class="strategy"><a href="/pages/ber-die-firma/trainingszentrum/warme-treffen-bei-dem-kamin.html"><small><? echo(dictionary('Теплые встречи у камина')); ?></small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='trust')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="strategy"><a href="/pages/nous-connatre/avril-en-avril.html"><small><? echo(dictionary('Апрель в «Апреле»')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='ru')&&($this->last_page_name=='repo-paev-fonda')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/analitika/dolgovoyi-runok/torgovue-idei.html"><small>Торговые идеи</small></a></li>
        <li class="model"><a href="/pages/investoram/brokerage/onlayin-zayavka-brokery.html"><small>Online заявка брокеру</small></a></li>
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
        <!-- <li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><small>Фонд «Умный робот Арбитраж»</small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&($this->last_page_name=='repo-of-gold-fund-shares')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Online заявка брокеру')); ?></small></a></li>
       <!-- <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li> -->
        <!-- <li class="robot"><a href="/pages/funds/fund-intelligent-robot-arbitrage.html"><small><? echo(dictionary('Фонд «Умный робот Арбитраж»')); ?></small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='repo-der-anteile-des-geltfondsanteile')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Online заявка брокеру')); ?></small></a></li>
    <!--    <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li> -->
        <!-- <li class="robot"><a href="/pages/fonds/fonds-intelligent-robot-arbitrage.html"><small><? echo(dictionary('Фонд «Умный робот Арбитраж»')); ?></small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='repo-paev-fonda')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Online заявка брокеру')); ?></small></a></li>
       <!-- <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li> -->
        <!-- <li class="robot"><a href="/pages/fonds/fonds-arbitrage-intelligent-robot.html"><small><? echo(dictionary('Фонд «Умный робот Арбитраж»')); ?></small></a></li> -->
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>







<? if(($this->site_lang=='ru')&&(($this->last_page_name=='tekyshcie-tsenu-na-zoloto')||($this->last_page_name=='stsenarnuyi-analiz'))) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="/pages/analitika/tovarnuyi-runok/kommentarii-po-runky-zolota.html"><small>Аналитика по золоту</small></a></li>
   <!--     <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small>Теплые встречи у камина</small></a></li> -->
        <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
        <li class="strategy"><a href="/pages/investoram/trust.html"><small>Стратегии доверительного управления</small></a></li>
        
</ul>
<? } ?>

<? if(($this->site_lang=='en')&&(($this->last_page_name=='fund-redirect-3')||($this->last_page_name=='scenario-analysis'))) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="/pages/research/commodity-market/comments-on-the-gold-market.html"><small><? echo(dictionary('Аналитика по золоту')); ?></small></a></li>
       <!-- <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small><? echo(dictionary('Теплые встречи у камина')); ?></small></a></li> -->
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="strategy"><a href="/pages/for-investors/trust-management.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        
</ul>
<? } ?>

<? if(($this->site_lang=='de')&&(($this->last_page_name=='aktuelle-goldpreise')||($this->last_page_name=='drehbuch-analyse'))) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="http://en.ufs-federation.com/pages/research/commodity-market/comments-on-the-gold-market.html"><small><? echo(dictionary('Аналитика по золоту')); ?></small></a></li>
       <!-- <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small><? echo(dictionary('Теплые встречи у камина')); ?></small></a></li> -->
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        
</ul>
<? } ?>

<? if(($this->site_lang=='fr')&&(($this->last_page_name=='tekyshcie-tsenu-na-zoloto')||($this->last_page_name=='lanalyse-de-scnario-dinvestissement'))) { ?>
    <ul class="learn-more">
        <li class="gold"><a href="http://en.ufs-federation.com/pages/research/commodity-market/comments-on-the-gold-market.html"><small><? echo(dictionary('Аналитика по золоту')); ?></small></a></li>
       <!-- <li class="strategy"><a href="/pages/o-kompanii/teplue-vstrechi-y-kamina.html"><small><? echo(dictionary('Теплые встречи у камина')); ?></small></a></li> -->
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="strategy"><a href="/pages/vous-tes-investisseurs/trust.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        
</ul>
<? } ?>
<? if(($this->site_lang=='ru')&&(($this->last_page_name=='1816-fond-umnuyi-robot-arbitrazh')||($this->last_page_name=='1830-fond-pifagor'))) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
        <li class="model"><a href="/pages/investoram/brokerage/onlayin-zayavka-brokery.html"><small>Online заявка брокеру</small></a></li>
        <li class="strategy"><a href="/pages/analitika/dolgovoyi-runok/torgovue-idei.html"><small>Торговые идеи</small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>        
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&(($this->last_page_name=='fund-intelligent-robot-arbitrage')||($this->last_page_name=='1834-pythagoras-funds'))) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Online заявка брокеру')); ?></small></a></li>
        <li class="strategy"><a href="/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>        
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&(($this->last_page_name=='1820-fonds-intelligent-robot-arbitrage')||($this->last_page_name=='1841-fonds-pythagoras'))) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Online заявка брокеру')); ?></small></a></li>
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>        
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&(($this->last_page_name=='1824-fonds-arbitrage-intelligent-robot')||($this->last_page_name=='1827-fonds-pythagore'))) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html"><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Online заявка брокеру')); ?></small></a></li>
        <li class="strategy"><a href="http://en.ufs-federation.com/pages/research/debt-market/429-torgovue-idei.html"><small><? echo(dictionary('Торговые идеи')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>        
</ul>
<? } ?>


<? if(($this->site_lang=='ru')&&($this->last_page_name=='sertifikat-v-podarok')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/investoram/trust.html"><small>Стратегии доверительного управления</small></a></li>
        <!-- <li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><small>Фонд «Умный робот Арбитраж»</small></a></li> -->
        <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
        <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
</ul>
<? } ?>

<? if(($this->site_lang=='en')&&($this->last_page_name=='certificate-as-a-gift')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/for-investors/trust-management.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
       <!-- <li class="robot"><a href="/pages/funds/fund-intelligent-robot-arbitrage.html"><small><? echo(dictionary('Фонд «Умный робот Арбитраж»')); ?></small></a></li> -->
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li> 
</ul>
<? } ?>

<? if(($this->site_lang=='de')&&($this->last_page_name=='zertifikat-als-geschenk')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
      <!--  <li class="robot"><a href="/pages/fonds/fonds-intelligent-robot-arbitrage.html"><small><? echo(dictionary('Фонд «Умный робот Арбитраж»')); ?></small></a></li> -->
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
</ul>
<? } ?>

<? if(($this->site_lang=='fr')&&($this->last_page_name=='certificat-en-cadeau')) { ?>
    <ul class="learn-more">
        <li class="strategy"><a href="/pages/vous-tes-investisseurs/trust.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
       <!-- <li class="robot"><a href="/pages/fonds/fonds-arbitrage-intelligent-robot.html"><small><? echo(dictionary('Фонд «Умный робот Арбитраж»')); ?></small></a></li> -->
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
</ul>
<? } ?>

<? if(($this->site_lang=='ru')&&($this->last_page_name=='mutual-funds')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/application-form.html"><small>Открыть счет online</small></a></li>
        <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
        <li class="strategy"><a href="/pages/investoram/trust.html"><small>Стратегии доверительного управления</small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small>Следите за нами на You Tube</small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='en')&&($this->last_page_name=='mutual-funds')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="model"><a href="/pages/research/1943-model-portfolio.html "><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/for-investors/trust-management.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="subscribe"><a href="/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='de')&&($this->last_page_name=='anteilfonds')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html "><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>
<? if(($this->site_lang=='fr')&&($this->last_page_name=='mutual-funds')) { ?>
    <ul class="learn-more">
        <li class="model"><a href="http://en.ufs-federation.com/pages/for-investors/become-the-client.html"><small><? echo(dictionary('Открыть счет online')); ?></small></a></li>
        <li class="model"><a href="http://en.ufs-federation.com/pages/research/1943-model-portfolio.html "><small><? echo(dictionary('Модельный портфель')); ?></small></a></li>
        <li class="strategy"><a href="/pages/vous-tes-investisseurs/trust.html"><small><? echo(dictionary('Стратегии доверительного управления')); ?></small></a></li>
        <li class="subscribe"><a href="http://en.ufs-federation.com/subscribe.html"><small><? echo(dictionary('Подписка на аналитику и торговые идеи UFS IC')); ?></small></a></li>
        <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small><? echo(dictionary('Наша страница в Facebook')); ?></small></a></li>
        <li class="youtube"><a href="https://www.youtube.com/user/UFSInvestmentCompany" target="_blank"><small><? echo(dictionary('Следите за нами на You Tube')); ?></small></a></li>
</ul>
<? } ?>


<? if((($this->last_page_name=='brokerage')||($this->last_page_name=='vnebirzhevue-operatsii-evroobligatsii')||($this->last_page_name=='itrading')||($this->last_page_name=='onlayin-zayavka-brokery'))&&($this->site_lang=='ru')) { ?>
<ul class="learn-more">
    <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
    <li class="gold"><a href="/investitsii-v-zoloto/investitsii-v-zoloto.html"><small>«Золотой Фонд»</small></a></li>
   <!-- <li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><small>Фонд «Умный робот Арбитраж»</small></a></li> -->
    <li class="strategy"><a href="/pages/investoram/trust/381-sbalansirovannaya-runok-rf.html"><small>Стратегии доверительного управления</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
    <!--<li class="model"><a href="http://ru.ufs-federation.com/pages/investoram/brokerage/bitcoin.html"><small>Пара слов о биткоинах</small></a></li>-->
</ul>
<? } ?>
<? if($this->last_page_name=='aktsiya-14') { ?>
<ul class="learn-more">
    <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
    <li class="gold"><a href="/investitsii-v-zoloto/investitsii-v-zoloto.html"><small>«Золотой Фонд»</small></a></li>
    <!-- <li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><small>Фонд «Умный робот Арбитраж»</small></a></li> -->
    <li class="strategy"><a href="/pages/investoram/trust/381-sbalansirovannaya-runok-rf.html"><small>Стратегии доверительного управления</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
</ul>
<? } ?>
<? if($this->last_page_name=='bitcoin') { ?>
<ul class="learn-more">
    <li class="model"><a href="/pages/analitika/modelnuyi-portfel.html"><small>Модельный портфель</small></a></li>
    <li class="gold"><a href="/investitsii-v-zoloto/investitsii-v-zoloto.html"><small>«Золотой Фонд»</small></a></li>
    <!-- <li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><small>Фонд «Умный робот Арбитраж»</small></a></li> -->
    <li class="strategy"><a href="/pages/investoram/trust/381-sbalansirovannaya-runok-rf.html"><small>Стратегии доверительного управления</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Подписка на аналитику и торговые идеи UFS IC</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Наша страница в Facebook</small></a></li>
</ul>
<? } ?>
<? if((($this->last_page_name=='brokerage')||($this->last_page_name=='otc-transactions')||($this->last_page_name=='internet-trading'))&&($this->site_lang=='en')) { ?>
<ul class="learn-more">
        <li class="gold"><a href="/pages/gold-investments/54-ufs-gold-fund.html"><small>“UFS Gold” fund</small></a></li>
    <li class="robot"><a href="/pages/funds/fund-intelligent-robot-arbitrage.html"><small>“Intelligent Robot Arbitrage” Fund</small></a></li>
    <li class="strategy"><a href="/pages/for-investors/trust-management/investment-strategies.html"><small>Trust management strategies</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Subscription for UFS IC research and trading ideas</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Our page on Facebook</small></a></li>
</ul>
<? } ?>
<? if(($this->last_page_name=='brokergeschfte')||($this->last_page_name=='auberbrsliche-geschfte')||($this->last_page_name=='brsengeschfte-und-internethandel')) { ?>
<ul class="learn-more">
    <li class="gold"><a href="/pages/goldinvestitionen/490-ufs-gold-fund.html"><small>Fonds “UFS Gold”</small></a></li>
    <li class="robot"><a href="/pages/fonds/fonds-intelligent-robot-arbitrage.html"><small>Fonds “Intelligent Robot Arbitrage”</small></a></li>
    <li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung/investitionsstrategien.html"><small>Treuhandverwaltungstrategie</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Subskription für die Analytik und Handelsideen von UFS IC</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Unsere Seite auf Facebook</small></a></li>
</ul>
<? } ?>
<? if((($this->last_page_name=='brokerage')||($this->last_page_name=='vnebirzhevue-operatsii-evroobligatsii')||($this->last_page_name=='itrading'))&&($this->site_lang=='fr')) { ?>
<ul class="learn-more">
    <li class="gold"><a href="/pages/investissements-en-or/investitsii-v-zoloto.html"><small>Fonds “UFS Gold”</small></a></li>
    <li class="robot"><a href="/pages/fonds/fonds-arbitrage-intelligent-robot.html"><small>Fonds “Intelligent Robot Arbitrage”</small></a></li>
    <li class="strategy"><a href="/pages/vous-tes-investisseurs/trust/381-sbalansirovannaya-runok-rf.html"><small>Stratégies de gestion fiduciaire</small></a></li>
    <li class="subscribe"><a href="/subscribe.html"><small>Abonnement à la recherche et aux idées commerciales de l’UFS IC</small></a></li>
    <li class="facebook"><a href="https://www.facebook.com/UFS.Finance" target="_blank"><small>Notre page sur Facebook</small></a></li>
</ul>
<? } ?>
<? if((($this->last_page_name=='investitsii-v-zoloto')&&($this->site_lang=='ru'))||(($this->last_page_name=='gold-standart')&&($this->site_lang=='ru'))) { ?>
<ul class="learn-more"><li class="gold"><a href="/pages/analitika/tovarnuyi-runok/kommentarii-po-runky-zolota.html"><? echo(dictionary('Комментарий по рынку золота')); ?></a></li>
<li class="strategy"><a href="/pages/investoram/trust/strategiya-3.html"><? echo(dictionary('Сбалансированная стратегия')); ?></a></li>
<li class="robot"><a href="/pages/fondu/fond-umnuyi-robot-arbitrazh.html"><? echo(dictionary('Умные стратегии')); ?></a></li>
<li class="model"><a href="/pages/o-kompanii/news/ayditorskoe-zaklyuchenie.html"><? echo(dictionary('Фонд получил положительное заключение аудиторов за 2012')); ?></a></li>
</ul>
<? } ?>

<? if(($this->last_page_name=='investitsii-v-zoloto')&&($this->site_lang=='fr')) { ?>
<ul class="learn-more"><li class="gold"><a href="/pages/research/commodity-market/comments-on-the-gold-market.html"><? echo(dictionary('Комментарий по рынку золота')); ?></a></li>
<li class="strategy"><a href="/strategiya-le-marche.html"><? echo(dictionary('Сбалансированная стратегия')); ?></a></li>
<li class="robot"><a href="/pages/fonds/fonds-arbitrage-intelligent-robot.html"><? echo(dictionary('Умные стратегии')); ?></a></li>
<li class="model"><a href="/pages/nous-connatre/actualits/rapport-daudit.html">Le fonds UFS Gold a reçu l’avis positif des auditeurs pour 2012</a></li>
</ul>
<? } ?>
<? if(($this->last_page_name=='490-ufs-gold-fund')&&($this->site_lang=='de')) { ?>
<ul class="learn-more"><li class="gold"><a href="/pages/research/commodity-market/comments-on-the-gold-market.html"><? echo(dictionary('Комментарий по рынку золота')); ?></a></li>
<li class="strategy"><a href="/pages/sie-sind-investoren/treuhandverwaltung/verwaltungsstrategie-ausgeglichene-der-russische-markt.html"><? echo(dictionary('Сбалансированная стратегия')); ?></a></li>
<li class="robot"><a href="/pages/fonds/fonds-intelligent-robot-arbitrage.html"><? echo(dictionary('Умные стратегии')); ?></a></li>
<li class="model"><a href="/pages/ber-die-firma/nachrichten/auditbericht.html">Der UFS Gold Fonds bekam eine positive Bewertung von Auditoren für 2012</a></li>
</ul>
<? } ?>

<? if((($this->last_page_name=='54-ufs-gold-fund')||($this->last_page_name=='balanced-market-of-the-russian-federation'))&&($this->site_lang=='en')) { ?>
<ul class="learn-more"><li class="gold"><a href="/pages/research/commodity-market/comments-on-the-gold-market.html"><? echo(dictionary('Комментарий по рынку золота')); ?></a></li>
<li class="strategy"><a href="/pages/for-investors/trust-management/bonds-pro.html"><? echo(dictionary('Сбалансированная стратегия')); ?></a></li>
<li class="robot"><a href="/pages/funds/fund-intelligent-robot-arbitrage.html"><? echo(dictionary('Умные стратегии')); ?></a></li>
<li class="model"><a href="/pages/about-us/news/audit-report.html">The UFS Gold Fond gained positive auditors’ opinion for 2012</a></li>
</ul>
<? } ?>  
<? if(($this->last_page_name=='hrnews')||($this->last_page_name=='programmist-1s')||($this->last_page_name=='career')||($this->last_page_name=='arbeiten-bei-ufs')||($this->last_page_name=='arbeiten-bei-ufs')||($this->last_page_name=='work')||($this->last_page_name=='trader')||($this->last_page_name=='1767-personal-assistant')||($this->last_page_name=='deputy-head-backoffice')||($this->last_page_name=='support-infrastructure-investment-funds')||($this->last_page_name=='organization-ipo')||($this->last_page_name=='vedyshciyi-spetsialist-po-vupysky-obligatsionnux-zayimov')||($this->last_page_name=='1868-analitik-po-obligatsiyam')) { ?>
<ul class="learn-more"><li class="subscribe"><? echo(dictionary('Управление кадров')); ?><br><a href="mailto:hr@ufs-federation.com">hr@ufs-federation.com</a></li></ul>
<? } ?>   
<? } ?>
<div class="webim"><? echo($this->load->view('body_webim','',true)) ?></div> 

 <br/> 
<? if($this->last_page_name=='stat-klientom') { ?>     
    <h2>Департамент развития бизнеса</h2>
    <p>+7 (495) 781-73-00</p> 
    <h2>Департамент Wealth management</h2>
    <p>+7 (495) 781-73-02</p>
<? } ?>   

<br/> 
<div style="padding-left: 31px">
<? if (($this->last_page_name!='license')&&($this->last_page_name!='crossword')) { ?>
    <?
if (($this->last_page_name=='zolotaya-osen')||
 ($this->last_page_name=='brokerage')||
 ($this->last_page_name=='onlayin-zayavka-brokery')||
 ($this->last_page_name=='fond-alternativnoyi-ienergetiki')||
 ($this->last_page_name=='503-fond-pryamux-investitsiyi')||
 ($this->last_page_name=='fond-pyotr-velikiyi')||
 ($this->last_page_name=='fond-lev-tolstoyi')||
 ($this->last_page_name=='investitsii-v-zoloto')||
 ($this->last_page_name=='leasing')||
 ($this->last_page_name=='obzor')||
 ($this->last_page_name=='subscribe')||
 ($this->last_page_name=='application-form')) {
?>
<? } else { ?>
  <? if ($this->site_lang!='cn') { ?>
    <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FUFS.IC&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true">
    </iframe>
  <? } ?>  
<? } ?>
</div>
    <? } ?>    <? } ?>
     <? if($this->site_lang=='de') { ?> 
     
<? if($this->last_page_name=='webinaren') { ?>     
<table style="border:none">
    <tbody>
        <tr>
            <td  style="background-color: #f2f6f7;">
            <h2><? echo(dictionary('Полезная информация')); ?></h2>
            <ul class="learn-more">
                <li class="model"><? echo(dictionary('Скачайте')); ?> <a title="Скачать расписание вебинаров" href="/upload/timetable-vebinar_de.jpg" target="_blank"><? echo(dictionary('расписание вебинаров')); ?></a> <small>&nbsp;(jpg, 2,54 Мб).</small></li>
                <li class="facebook"><? echo(dictionary('Присоединяйтесь к нам в')); ?>&nbsp;<a href="https://www.facebook.com/UFS.IC">Facebook.</a></li>
                <? if($this->site_lang=='ru') { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://ru.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li>
                <? } else { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://en.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li><? } ?>
                <li class="robot"><? echo(dictionary('Звоните менеджерам:')); ?> <nobr><strong>+7 (495) 781-73-00</strong>.</nobr></li>
            </ul>
            </td>
        </tr>
    </tbody>
</table>
<? } ?>
 <? } ?> 
<? if($this->site_lang=='ru') { ?>     
<? if($this->last_page_name=='vebinaru') { ?>     
<table style="border:none">
    <tbody>
        <tr>
            <td  style="background-color: #f2f6f7;">
            <h2><? echo(dictionary('Полезная информация')); ?></h2>
            <ul class="learn-more">
                <li class="model"><? echo(dictionary('Скачайте')); ?> <a title="Скачать расписание вебинаров" href="/upload/timetable-vebinar.jpg" target="_blank"><? echo(dictionary('расписание вебинаров')); ?></a> <small>&nbsp;(jpg, 2,54 Мб).</small></li>
                <li class="facebook"><? echo(dictionary('Присоединяйтесь к нам в')); ?>&nbsp;<a href="https://www.facebook.com/UFS.IC">Facebook.</a></li>
                <? if($this->site_lang=='ru') { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://ru.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li>
                <? } else { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://ru.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li><? } ?>
                <li class="robot"><? echo(dictionary('Звоните менеджерам:')); ?> <nobr><strong>+7 (495) 781-73-00</strong>.</nobr></li>
            </ul>
            </td>
        </tr>
    </tbody>
</table>
<? } ?>
 <? } ?> 
     <? if($this->site_lang=='fr') { ?> 
     
<? if($this->last_page_name=='webinars') { ?>     
<table style="border:none">
    <tbody>
        <tr>
            <td  style="background-color: #f2f6f7;">
            <h2><? echo(dictionary('Полезная информация')); ?></h2>
            <ul class="learn-more">
                <li class="model"><? echo(dictionary('Скачайте')); ?> <a title="Скачать расписание вебинаров" href="/upload/timetable-vebinar_fr.jpg" target="_blank"><? echo(dictionary('расписание вебинаров')); ?></a> <small>&nbsp;(jpg, 2,54 Мб).</small></li>
                <li class="facebook"><? echo(dictionary('Присоединяйтесь к нам в')); ?>&nbsp;<a href="https://www.facebook.com/UFS.IC">Facebook.</a></li>
                <? if($this->site_lang=='ru') { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://ru.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li>
                <? } else { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://en.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li><? } ?>
                <li class="robot"><? echo(dictionary('Звоните менеджерам:')); ?> <nobr><strong>+7 (495) 781-73-00</strong>.</nobr></li>
            </ul>
            </td>
        </tr>
    </tbody>
</table>
<? } ?>
 <? } ?> 
     <? if($this->site_lang=='en') { ?> 
     
<? if($this->last_page_name=='webinars')  { ?>     
<table style="border:none">
    <tbody>
        <tr>
            <td  style="background-color: #f2f6f7;">
            <h2><? echo(dictionary('Полезная информация')); ?></h2>
            <ul class="learn-more">
                <li class="model"><? echo(dictionary('Скачайте')); ?> <a title="Скачать расписание вебинаров" href="/upload/timetable-vebinar_en.jpg" target="_blank"><? echo(dictionary('расписание вебинаров')); ?></a> <small>&nbsp;(jpg, 2,54 Мб).</small></li>
                <li class="facebook"><? echo(dictionary('Присоединяйтесь к нам в')); ?>&nbsp;<a href="https://www.facebook.com/UFS.IC">Facebook.</a></li>
                <? if($this->site_lang=='ru') { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://ru.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li>
                <? } else { ?><li class="subscribe"><? echo(dictionary('Читайте и')); ?>&nbsp;<a href="http://en.ufs-federation.com/subscribe.html"><? echo(dictionary('подписывайтесь')); ?></a> <? echo(dictionary('на нашу аналитику')); ?>.</li><? } ?>
                <li class="robot"><? echo(dictionary('Звоните менеджерам:')); ?> <nobr><strong>+7 (495) 781-73-00</strong>.</nobr></li>
            </ul>
            </td>
        </tr>
    </tbody>
</table>
<? } ?>
 <? } ?> 
</div>

