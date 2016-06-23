    <? if((($this->last_page_name=='onlayin-zayavka-brokery') ||
     ($this->last_page_name=='itrading') ||
//     ($this->last_page_name=='mutual-funds') ||
   /*  ($this->last_page_name=='investitsii-v-zoloto') || */
     ($this->last_page_name=='fond-umnuyi-robot-arbitrazh') ||
     ($this->last_page_name=='fond-pifagor') ||
     ($this->last_page_name=='ezhednevnue-obzoru') ||
     ($this->last_page_name=='ezhednevnue-kommentarii') ||
 /*    ($this->last_page_name=='press') || */
     ($this->last_page_name=='application-form')  ||
//   (($this->last_page_name=='ufs')&&($this->site_lang=='ru'))  ||  
     ($this->last_page_name=='aprel-v-aprele') ||
     ($this->last_page_name=='subscribe') ||
     ($this->last_page_name=='monitoring-bankovskogo-sektora') ||
//   ($this->last_page_name=='brokerage') ||
     ($this->last_page_name=='makroiekonomicheskie-obzoru'))&&($this->site_lang=='ru')){ ?>
     <div class="banner">
         <!-- <a href="http://ru.ufs-federation.com/pages/analitika/1804-modelnuyi-portfel.html">
             <img src='http://ru.ufs-federation.com/banners/model_port.gif' width="230" alt="">-->
  <!--  <img src='http://ru.ufs-federation.com/banners/torg_3_230х150.gif' width="230" alt=""> -->
  </a><br>
</div>
<? } ?>

<?
// Баннер Голос 2015
$banner_name      = 'golos2015';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';
switch ($this->site_lang) {
  case 'ru': $banner_link = 'http://ru.cbonds.info/votes/193?'; break;
}
switch ($this->last_page_name) {
  case 'obzor';
  case 'sobutiya-dnya';
  case 'strategiya';
  case 'dolgovoyi-runok';
  case 'ezhednevnue-obzoru';
  case '340-spetsialnue-kommentarii';
  case 'ezhednevnue-kommentarii';
  case 'texnicheskiyi-analiz';
  case 'spetsialnue-kommentarii';
  case 'obzoru-po-iemiteram';
  case 'kommentarii-po-runky-zolota';
  case 'makroiekonomicheskie-obzoru';
  case 'tendentsii-v-iekonomike-rf';
  case 'monitoring-bankovskogo-sektora';
  case 'ypravlenie-riskami';
  case 'subscribe';
    echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' .$this->site_lang. '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
  break;
}
?>

<?
// banner Награды
$banner_name      = 'awards'; // уникальное имя баннера для каждой pr-кампании
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

// в данном случае на всех языковых версиях ссылка одинаковая
$banner_link = '/subscribe.html';

// на каких страницах показывать баннер
switch ($this->last_page_name) {
  case '490-ufs-gold-fund';
  case '54-ufs-gold-fund';
  case 'anteilfonds';
  case 'auberbrsliche-geschfte';
  case 'awards';
  case 'ber-die-firma';
  case 'brokerage';
  case 'brokergeschfte';
  case 'investitsii-v-zoloto';
  case 'mutual-funds';
  case 'nagradu';
  case 'otc-transactions';
  case 'preise';
  case 'press';
  case 'pressa-o-nas';
  case 'presse-ber-uns';
  case 'prix';
  case 'treuhandverwaltung';
  case 'trust';
  case 'trust-management';
  case 'financial-d';
  case 'ufs';
  case 'ufs-investment-company';
  case 'vnebirzhevue-operatsii-evroobligatsii';
    echo '<a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' .$this->site_lang. '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
  break;
}
?>


<? /*
// Форма регистрации для правого сайдбара - Отключена

if($this->last_page_name=='ufs'){ ?>
<link rel="stylesheet" href="/css/anketa.css">
<style>
  #regForm.list-items li,
  #regForm input.flat-ui[type="submit"] {
    font-size: 100%;
  }
  #regForm .radio {
    padding-left: 0;
  }
  #regForm .radio input[type="text"],
  #regForm input.flat-ui[type="submit"] {
    width: 100%;
    margin-bottom: 1em;
   -moz-box-sizing: border-box;
   -webkit-box-sizing: border-box;
    box-sizing: border-box;
  }
  a.btn-callback {
    line-height: 22px;
    margin-bottom: 1.5em;
    padding-top: 12px;
  }
  #regForm h4 {
    font-weight: bold;
    text-align: center;
    color: #44474d;
  }
  div.sidebar h2.msg-success,
  div.sidebar h2.msg-error {
    margin: 2em 0 !important;
  }
  div.sidebar h2.msg-success {
    color: #318c23;
  }
  div.sidebar h2.msg-error {
    color: #e1261c;
  }
</style>
<script>
$(document).ready(function() {
  $('#openForm').click(function(){
    $(this).slideUp(150);
    $('#regForm').slideDown(150,function(){
      $('#regForm #q1').focus();
    });
    return false;
  });
  $('#regFormData').submit(function(){
    var name = $('#q1').val();
    var phone = $('#q2').val();
    var email = $('#q3').val();
    var event = $('#qdate').val();
    if (email.length>0) {
      //var word = document.getElementById("word").value;
      //var url='<?=$this->phpself?>notify.html?type=nsend&name='+name+'&phone='+phone+'&date='+event;
      
      var url='<?=$this->phpself?>/notify.html?type=nnsend&name='+name+'&phone='+phone+'&email='+email+'&date='+event;
      
      $.getJSON(url,{format:'json'}).done(function(data) {
        if (data.success) {
          $('#regForm').slideUp(150);
          $('#msg-error').slideUp(150);
          $('#msg-success').slideDown(150);
        } else {
          $('#msg-error').text(data.message);
          $('#msg-error').slideDown(150);
        }
      });
    } else {
      $('#msg-error').slideDown(150);
    }
    return false;
  });
});
</script>
<p><strong>Приглашаем начать знакомство с&nbsp;нашей компанией 17&nbsp;июня с&nbsp;<a href="/pages/o-kompanii/news/raskruvayite-tayinu-proshlogo-s-ufs-ic.html" target="_blank">экскурсии на&nbsp;биржу</a>.</strong></p>

<a href="#" class="btn-callback" id="openForm">Отправить<br/>заявку на&nbsp;участие</a>
<h2 id="msg-success" class="msg-success" style="display: none">Заявка на&nbsp;участие принята.</h2>
<h2 id="msg-error" class="msg-error" style="display: none">Ошибка. Заявка не&nbsp;отправлена.</h2>
<ul class="list-items regForm" id="regForm" style="display: none"><form id="regFormData"><input type="hidden" id="qdate" name="Date" value="June-17"/>
  <li>
    <div class="radio">
      <h4>Заявка на&nbsp;участие</h4>
      <label>Имя<input class="flat-ui" type="text" id="q1" name="Name" placeholder="Ваше имя"/></label>
    </div>

    <div class="radio">
      <label>Телефон<input class="flat-ui" type="text" id="q2" name="Phone" placeholder="Телефон"/></label>
    </div>

    <div class="radio">
      <label>Email<input class="flat-ui" type="text" id="q3" name="Email" placeholder="Email"/></label>
    </div>
    <p><input class="flat-ui" type="submit" name="submit" value="Отправить"/></p>
  </li></form>
</ul>
<? } */ ?>


<? if(($this->last_page_name=='opros2014')){ ?>
<h2>Наши тарифы</h2>
<table  style="border:0" >
    <tbody>
        <tr>
            <td width="20%"><small><b>Место заключения сделки</b></small></td>
            <td width="60%"><small><b>Оборот за день, RUR</b></small></td>
            <td width="20%"><small><b>Комиссия брокера, %</b></small></td>
        </tr>
        <tr>
            <td rowspan="6"><small>ЗАО &laquo;ФБ ММВБ&raquo;</small><br />
            <br type="_moz" />
            <br />
            <br type="_moz" />
            <br />
            <br type="_moz" />
            <br />
            <br type="_moz" />
            <br />
            &nbsp;</td>
            <td><small>до 125 000,00</small></td>
            <td><small>0,03</small></td>
        </tr>
        <tr>
            <td><small>от 125 000,01 до 375 000,00</small></td>
            <td><small>0,025</small></td>
        </tr>
        <tr>
            <td><small>от 375 000,01 до 1 250 000,00</small></td>
            <td><small>0,02</small></td>
        </tr>
        <tr>
            <td><small>от 1 250 000,01 до 6 250 000,00</small></td>
            <td><small>0,017</small></td>
        </tr>
        <tr>
            <td><small>от 6 250 000,01 до 18 750 000,00</small></td>
            <td><small>0,015</small></td>
        </tr>
        <tr>
            <td><small>свыше 18 750 000,01</small></td>
            <td><small>0,01</small></td>
        </tr>
        <tr>
            <td><small>ВНБР (в т.ч. RTS Board)</small></td>
            <td><small>вне зависимости от оборота</small></td>
            <td><small>0,085 от суммы сделки, но не менее 850 RUR за одну сделку</small></td>
        </tr>
        <tr>
            <td colspan="3"><br type="_moz" />
            <small><b>Комиссия по сделкам РЕПО</b></small><br type="_moz" />
            &nbsp;</td>
        </tr>
        <tr>
            <td><small>ЗАО &laquo;ФБ ММВБ&raquo;</small><br />
            &nbsp;</td>
            <td>&nbsp;</td>
            <td><small>0,085 от суммы денежных средств, уплаченной по первой части договора РЕПО</small></td>
        </tr>
    </tbody>
</table>
<table style="border:0px" > 
    <tbody>
        <tr>
            <td colspan="3"><small><b>Зачисление и списание ценных бумаг в результате проведения расчетов по внебиржевым сделкам с ценными бумагами</b></small></td>
        </tr>
        <tr>
            <td rowspan="3"><small>Зачисление и списание ценных бумаг в результате проведения расчетов по внебиржевым сделкам с еврооблигациями, акциями, паями и другими ценными бумагами иностранных эмитентов</small><br />
            <br type="_moz" />
            <br />
            &nbsp;</td>
            <td colspan="2"><small>Количество поручений на зачисление и списание ценных бумаг по таким сделкам в месяц</small></td>
        </tr>
        <tr>
            <td><small>от 1 до 49 поручений</small></td>
            <td><small>от 50 и выше поручений</small></td>
        </tr>
        <tr>
            <td><small>50&nbsp;RUR за каждое поручение</small></td>
            <td><small>30 RUR за каждое последующее поручение</small></td>
        </tr>
        <tr>
            <td><small>Зачисление и списание ценных бумаг в результате проведения расчетов по внебиржевым сделкам с остальными ценными бумагами</small></td>
            <td colspan="2"><br type="_moz" /><small>30 RUR за поручение</small></td>
        </tr>
    </tbody>
</table>
<? } ?> 
<? if($this->last_page_name=='tarifnue-kanikylu'){ ?>
     <div class="banner"><a href="/application-form.html">
         <br><img src="http://ru.ufs-federation.com/banners/application_form_ru.gif"> 
  </a><br>
</div>
<? } ?>    
      <? if(!empty($contacts)){ ?>
        <h2><? echo($contacts->name); ?></h2>
        <a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('contact').$this->urlsufix); ?>" class="all-offices">
        <address>
          <p class="sp"><? echo($contacts->address); ?></p>    
          <? if(!empty($contacts->phones)){ ?>
            <? echo($contacts->phones); ?>
          <? } ?>
          
        </address>
        </a>
      <? } ?>    



<? 

 // СуперДжет 100 
 
if($this->last_page_name=='flugzeugsleasing' || $this->last_page_name=='crdit-bail-davions'|| $this->last_page_name=='lizing-aviasydov' || $this->last_page_name=='aircraft-lease') { ?>
	<div style="text-align: left;">
		<img src="/banners/333529.jpg" align="left">
		<? if ($this->site_lang=='ru') { ?>
		<p>Sukhoi Superjet 100 – это региональный 100-местный самолет нового поколения, который объединяет в себе новейшие технологии в области авиастроения. SSJ100 предлагает  непревзойденный комфорт пассажирам, значительные экономические преимущества перевозчикам, удобство для экипажа и максимальную экологическую безопасность для окружающей среды</p>
		<? } elseif ($this->site_lang=='en') { ?>
		<p>Sukhoi Superjet 100 is a 100-seater regional aircraft of the new generation, which combines the latest technology in the field of aviation. SSJ100 offers unsurpassed comfort to passengers, significant economic benefits to the air carriers, convenience for the crew and maximum environmental safety for the environment.</p>
		<? } elseif ($this->site_lang=='de') { ?>
		<p>Sukhoi Superjet 100 ist ein regionales 100-sitziges Flugzeug der neuen Generation, dass die neueste Technologie auf dem Gebiet des Flugzeugbaus vereint. SSJ100 bietet unübertroffenen Komfort der Passagiere, erhebliche wirtschaftliche Vorteile für Luftfahrtunternehmen, Komfort für die Besatzung und maximale ökologische Sicherheit für die Umwelt an.</p>
		<? } elseif ($this->site_lang=='fr') { ?>
		<p>Le Sukhoi Superjet 100 est un avion régional de 100 places de la nouvelle génération, qui combine les dernières technologies dans le domaine d'aviation. SSJ100 offre un confort inégalé aux passagers, des avantages importants économiques pour les transporteurs, un comport pour l'équipage et la sécurité maximale pour l'environnement.</p>
		<? } ?>
	</div>
<? } ?>




<?
// Томбстоуны
$show_stone = false;
switch ($this->site_lang) {
  
  case 'ru':
    switch ($this->last_page_name) {
      case 'lokalnue-obligatsii';
	  case 'restryktyrizatsii-evroobligatsiyi-v-azii';
      case '573-evroobligatsii';
      case 'vekselnue-zayimu';
      case 'islamskoe-finansirovanie';
      case 'pereypakovka-dolga';
      case '578-stryktyra-dlya-vupyska-evroobligatsiyi';
      case 'debt-capital-market';
        $show_stone = true;
      break;
    }
  break;
  
  case 'en':
    switch ($this->last_page_name) {
	  case 'eurobonds-restructuring-in-asia';
      case 'debt-capital-market';
      case '584-local-bonds';
      case 'eurobonds';
      case '585-issuance-of-promissory-notes';
      case '582-islamic-financing';
      case '581-debt-repackaging';
      case '579-structure-for-a-eurobond-issue';
        $show_stone = true;
      break;
    }
  break;
  
  case 'de':
    switch ($this->last_page_name) {
	  case 'eurobondsumstrukturierung-in-asien';
      case 'fremdkapitalmarkt';
      case '593-lokale-obligationen';
      case 'eurobonds';
      case '591-wechselkredite';
      case '590-islamische-finanzierung';
      case '589-debt-repackaging';
      case '587-struktur-der-eurobonds-emission';
        $show_stone = true;
      break;
    }
  break;
  
  case 'fr':
    switch ($this->last_page_name) {
	  case 'restructuration-des-euro-obligations-en-asie';
      case 'debt-capital-market';
      case 'lokalnue-obligatsii';
      case '573-evroobligatsii';
      case 'vekselnue-zayimu';
      case 'islamskoe-finansirovanie';
      case 'pereypakovka-dolga';
      case '578-stryktyra-dlya-vupyska-evroobligatsiyi';
        $show_stone = true;
      break;
    }
  break;
}
if ($show_stone == true) { ?>
    <div class="banner popup-gallery" style="margin-top: 16px">
      <a class="stone" href="/upload/dept-capital-market/gssnew_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="0" src="/upload/dept-capital-market/gssnew_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/gssnew2_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/gssnew2_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/1_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/1_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/2_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/2_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/3_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/3_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/4_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/4_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/6_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/6_<?=$this->site_lang?>.jpg"></a>
      <a class="stone" href="/upload/dept-capital-market/5_<?=$this->site_lang?>.jpg" target="_blank"><img width="100" border="1" src="/upload/dept-capital-market/5_<?=$this->site_lang?>.jpg"></a>
    </div>
<? } ?>

        
        
        <? if(
        $this->last_page_name=='brokerage'
        /* ||  $this->last_page_name=='onlayin-zayavka-brokery' ||
        //$this->last_page_name=='application-form') ||
        $this->last_page_name=='itrading' ||
        $this->last_page_name=='vnebirzhevue-operatsii-evroobligatsii' ||
        $this->last_page_name=='trust' ||
        $this->last_page_name=='381-sbalansirovannaya-runok-rf'*/
                ) {
?>
<br/><!-- <div class="banner">
<?
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
?>
</div> -->
                <? } ?>














    <? if(($this->last_page_name=='gss')) { ?>
<style>.sidebar.s_right {background: transparent}</style>
<div class="section_extra" style="margin-top: -51px;">
<a href="http://ru.ufs-federation.com/upload/BO-04_2015_program_Final.pdf" target="_blank" style="display: block; border: 1px solid #bebebe; text-align: center; padding: 0.5em 0.25em; font-size: 11px; margin: 8px 0"><? echo(dictionary('Скачать')); ?> <? echo(dictionary('презентацию')); ?>&nbsp;&nbsp;<span style="color: #777; font-size: 10px;">(pdf, 5 Mb)</span></a>
<a href="http://www.youtube.com/watch?v=0wJ6NL-idbM" target="_blank" style="display: block; border: 1px solid #bebebe; text-align: center; padding: 0.5em 0.25em; font-size: 11px; margin: 8px 0"><? echo(dictionary('Посмотреть')); ?> <? echo(dictionary('видеопрезентацию')); ?></a>

<h2><? echo(dictionary('Заявка на участие в размещении облигаций')); ?></h2>
    <div class="subscription-box" style="margin-left: 0; margin-bottom: 22px">
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Email')); ?>*:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="name" id="mce-EMAIL" class="email required" /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Организация')); ?>:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="company" id="mce-COMPANY"  /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Должность')); ?>:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="position" id="mce-POSITION"  /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Телефон')); ?>*:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="phone" id="mce-PHONE" class="required"  /></div>
        <div id="mc_embed_signup"><h5>&nbsp;&nbsp;&nbsp;&nbsp;<? echo(dictionary('Желаемый объем инвестиций')); ?>*:</h5>&nbsp;&nbsp;&nbsp;<input type="text" name="volume" id="mce-VOLUME" class="required" /></div>
        <h5 style="dispplay: block;"><? echo(dictionary('Поля, отмеченные *, обязательны для заполнения')); ?></h5>
        <div id="div-info" style="display: none;" class="conference-inner">
            <p id="div-info-p">&#160;</p>
        </div>
        <div id="text-hide">
            <p>&#160;</p>
        </div>
        <input type="button" onclick="notify1(this);" value="<? echo(dictionary('Отправить')); ?>" name="notify" id="mc-embedded-subscribe" class="button" />
    </div>
<!-- <a href="http://ru.ufs-federation.com/upload/GSS_Desknote.pdf" target="_blank" style="display: block; border: 1px solid #bebebe; text-align: center; padding: 0.5em 0.25em; font-size: 11px; margin: 8px 0"><? echo(dictionary('Скачать')); ?> Desknote&nbsp;&nbsp;<span style="color: #777; font-size: 10px;">pdf, 1 Mb</span></a> -->
<a href="http://ru.ufs-federation.com/upload/TermSheetGSS.pdf" target="_blank" style="display: block; border: 1px solid #bebebe; text-align: center; padding: 0.5em 0.25em; font-size: 11px; margin: 8px 0"><? echo(dictionary('Скачать')); ?> Term Sheet <? echo(dictionary('ГСС')); ?>&nbsp;&nbsp;<span style="color: #777; font-size: 10px;">pdf, 96 Kb</span></a><br />

        <script>
   function notify1(obj)  
   {  
    document.getElementById('text-hide').innerHTML = '<p>&#160;</p>';
    var url='<? echo($this->phpself) ?>notify.html?type=sendgss&\n\email='+document.getElementById("mce-EMAIL").value+'&\n\company='+document.getElementById("mce-COMPANY").value+'&\n\position='+document.getElementById("mce-POSITION").value+'&\n\phone='+document.getElementById("mce-PHONE").value+'&\n\ivolume='+document.getElementById("mce-VOLUME").value;
    $.getJSON(url,{format: 'json'}).done(function(data) {    
     if (data) {
        var o = document.getElementById("div-info-p");
        
        if (data.success) {
          o.innerHTML = "<span style=\"color:#0da96c\"><? echo(dictionary('Заявка отправлена. Специалисты компании свяжутся с Вами в ближайшее время')); ?></span>";
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

    <? } ?>  