  <div id="slider" style="overflow: visible">
    <? if($data){ ?>
    <? $x=1; for($i=1;$i<=6;$i++){ 
        $img='img_'.$i; 
        $promo_title='promo_title_'.$i;
        $promo_url='promo_url_'.$i;
        $promo_text='promo_text_'.$i; 
        $promo_hide='promo_hide_'.$i; ?>
      <? if($data->$promo_hide=='no'){ ?>
        <div class="fragment-block" id="fragment-<? echo($x); ?>" style="background-image: url('<? echo($this->base_url.'/upload/home/small/'.$data->$img); ?>');">
          <div class="resume">
            <h2>
              <? if(!empty($data->$promo_url)){ ?>
                <a href="<? echo($data->$promo_url); ?>">
                    <? echo($data->$promo_title); ?>
                </a>
              <? }else{ ?>
                <? echo($data->$promo_title); ?>
              <? } ?>
            </h2>
            <p><? echo($data->$promo_text); ?></p>
           <?
           switch ($this->site_lang) {
            case 'ru': $btn_link = '/learning.html'; break;
            case 'de': $btn_link = '/pages/ber-die-firma/trainingszentrum.html'; break;
            case 'fr': $btn_link = '/pages/nous-connatre/centre-dtudes.html'; break;
            default:   $btn_link = 'http://en.ufs-federation.com/pages/about-us/training-center.html'; break;
          }
           if ($i==5) echo '<a href="'.$btn_link.'" class="btn-reg" style="width: 299px; height: 50px; font-size: 20px; font-weight: 300; font-family: open sans; background-color: #e1ca5a; border: 2px solid #dbc558; color: #000; border-radius: 50px; position: absolute; top: 55%; display: block; text-align: center;line-height: 50px;left: 50%;text-decoration: none;margin-left: 500px;">'.dictionary('Зарегистрироваться').'</a>'; ?>
          </div>
        </div>
      <? $x++; } ?>
    <? } ?>
<nav class="slider-menu">    
    <ul class="slider-pager">
    <? $x=1; for($i=1;$i<=6;$i++){ 
        $promo_url='promo_url_'.$i;
        $promo_title='promo_title_'.$i; 
        $promo_hide='promo_hide_'.$i; ?>
      <? if($data->$promo_hide=='no'){ ?>
      <li><a href="#fragment-<? echo($x); ?>"<? if(!empty($data->$promo_url)){ ?> onclick="document.location.href='<? echo($data->$promo_url); ?>'"<? } ?>><? echo($data->$promo_title); ?></a></li>
      <? $x++; } ?>
    <? } ?>
    </ul>
</nav>	
    <? } ?>
	<div class="bg-slider">&nbsp;</div>
  
<link href='http://fonts.googleapis.com/css?family=Forum&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
  <? if($this->site_lang == 'ru') { ?>
    <!--<div style="position: absolute; width: 100%; display: block; margin-top: -19px; z-index: 1000; padding: 0;">
      <a class="callback" style="margin: 0; padding: 0; width: 300px; text-decoration: none; background: #e7e7e7 url(/img/icon24-7.png) 90px 50% no-repeat; display: block; width: 1080px; margin: auto; font-size: 22px; font-family: forum; color: #000; text-align: center; z-index: 1000; padding: 14px 0">
        <img src="">Круглосуточная поддержка по&nbsp;бесплатному федеральному номеру <nobr>8-800 234 02 02</nobr>
      </a>
    </div>-->
    <div style="position: absolute; width: 100%; display: block; margin-top: -19px; z-index: 998; padding: 0;">
      <a id="allnight" class="callback allnight" onclick="ga('send', 'event', 'Действия', 'Обратный звонок Главная');">Круглосуточный колл-центр. Мы работаем, чтобы вам было удобно.<br/><span><? echo(dictionary('8-800 234-0202')); ?></span></a>
    </div>
  <? } ?>
  </div>