  <div id="navigation">
        <script defer src="/js/jquery.flexslider-min.js"></script>
    <!-- 3sec popup -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,300&amp;subset=latin,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/css/anketa.css">
    <script src="/js/popup.js" type="text/javascript"></script>
    <!-- //3sec popup -->
    <script>
      $(document).ready(function() {
        $('.flexslider').flexslider({
          animation: "slide",
          slideshowSpeed: 5775
        });
        
        /* 3sec popup */
        $(window).load(function() {
          setTimeout(function(){
            Avgrund.show('#broker-popup');
            return false;
          },6400);
        });
        $('.close-dialog, .popup-cover').click(function(e){
          e.stopPropagation();
          e.preventDefault();
          Avgrund.hide();
          return false;
        });
        /* //3sec popup */
      });
      
      
    </script>
    <script> 

      $(document).ready(function() {
        $('#callback, .callback').click(function(e){
          var theInput = $('#callback-phone');
          theInput.focus();

          e.stopPropagation();
          e.preventDefault();

          Avgrund.show('#callback-popup');
          return false;
        });
        $('#callback-popup button').click(function(){
          var phone = $('#callback-phone').val();
          var length = phone.length;

          if (length == 11) {
            $('#callback-popup input').prop('disabled', true);
            $('#callback-popup input').addClass('.disabled');

            var url='http://ru.ufs-federation.com/callback.html?phone='+phone;
            $.getJSON(url,{format:'json'}).done(function(data) {
              if (data) {
                if (data.success) {
                  $('#callback-popup button').text('Соединение...');
                } else {
                  $('#callback-popup button').text(data.message);
                }
              }
              //$('#mc-embedded-callback').show();
            });
          } else {
            $('#callback-phone').focus();
          }
          return false;
        });
        $('.open-dialog').click(function(e){
          e.stopPropagation();
          e.preventDefault();
          return false;

          Avgrund.show('#default-popup');
        });
        $('.close-dialog, .popup-cover').click(function(e){
          e.stopPropagation();
          e.preventDefault();
          Avgrund.hide();
          return false;
        });
      });
    </script>
    <div id="callback-popup" class="popup-popup popup-mode-call" style="z-index: 1200">
      <form action="action">
        <div class="popup-caption">
          <div class="badge"></div>
          Пожалуйста, укажите Ваш номер телефона
          <input type="text" id="callback-phone" placeholder="Например: 8**********" maxlength="11"/>
        </div>
        <div class="popup-footer">
          <button>Заказать звонок</button>
        </div>
      </form>
    </div>
    <div class="popup-cover" style="z-index: 11"><a class="close-dialog"><i></i></a></div>

    <? /* <div class="extra-banner<?=($this->site_lang == 'ru' || $this->site_lang == 'en' || $this->site_lang == 'de' || $this->site_lang == 'fr') ? ' olymp-' . $this->site_lang : ' olymp-en'?>"></div> */ ?>
    <div class="flexslider extra-banner" style="background-image: none">
      <div class="best-analytics-<?=$this->site_lang?>"></div>
      <ul class="slides best-analytics">
        <li id="slide01"><a href="http://ru.ufs-federation.com/pages/o-kompanii/news/komanda-liderov.html"></a></li>
        <li id="slide02"><a href="http://ru.ufs-federation.com/pages/o-kompanii/news/ufs-ic-pobeditel-konkyrsa-thomson-reuters.html"></a></li>
        <li id="slide03"><a href="http://ru.ufs-federation.com/pages/o-kompanii/news/ufs-ic-stala-layreatom-premii-cbonds-awards.html"></a></li>
      </ul>
    </div>
    <!-- 3 sec popup -->
    <? if($this->last_page_name=='torgovue-idei' || $this->last_page_name=='advice') { ?>
      <div id="broker-popup" class="popup-popup popup-mode-call" style="z-index: 12;padding: 32px;height: 236px;">
        <div class="popup-caption" style="padding-top: 6px; min-height: 185px; font-size: 18px;"><h2 style="color: #000; font-size: 26px; font-family: open sans; font-weight: normal; margin: 0 0 0.5em;">Еще больше торговых идей и&nbsp;рекомендаций доступны клиентам на&nbsp;брокерском обслуживании.</h2>Рекомендации аналитиков UFS IC&nbsp;включены<br/>в&nbsp;<a href="/pages/o-kompanii/news/komanda-liderov.html" target="_blank" style="color: #ffec64;">ранг абсолютного дохода (Bloomberg)</a>.</div>
        <div class="popup-footer">
          <a href="/application-form.html" target="_blank" class="button">Отправить заявку</a>
        </div>
      </div>
      <div class="popup-cover" style="z-index: 11"><a class="close-dialog"><i></i></a></div>
    <? } ?>
    <!-- //3 sec popup -->
    
    <div class="search-form">
        <form method="POST" action="<? echo($this->phpself.'search.html'); ?>">
            <input type="text" name="text" id="text" placeholder="<? echo(dictionary('Поиск по сайту')); ?>"  />
            <input type="hidden" name="option" value="1" />
            <input type="submit" value="" />
        </form>
    </div>
    <ul class="sub-nav">
        <li><a href="http://ufs-finance.com/disclosure-of-information.html" target="_blank"><? echo(dictionary('Раскрытие информации')); ?></a></li>
        <li><a href="<? echo($this->phpself.'downloads'.$this->urlsufix); ?>"><? echo(dictionary('Документы и файлы')); ?></a></li>
        <li><a href="#" class="callback" style="white-space: nowrap;"><? echo(dictionary('8-800 234-0202')); ?></a></li>
        <li class="language">
            <div id="popup-language" class="showed">
                <? foreach($langs as $item){ ?>
                <a href="<? echo($this->phpself.'lang-select/'.$item->lang.$this->urlsufix); ?>" class="lang-<? echo($item->lang); ?>"><!-- <span><? echo($item->name); ?></span> --></a>
                <? } ?>
            </div>
        </li>
    </ul>
    
    <ul id="nav">
    <? if($menu) { $i=0; ?>
       <? foreach($menu as $item) { ?>
          
          <li class="<? echo (($item->select)?'selected':'') ?><? echo (isset($item->childs)?' nested':'') ?>"><a href="<? echo($this->phpself.'pages/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
          <? if (isset($item->childs)) { ?>
            <ul class="tabs">
            <? foreach ($item->childs as $child) { ?>
              <li><a href="<? echo($this->phpself.'pages/'.$item->url.'/'.$child->url.$this->urlsufix); ?>"><? echo($child->name); ?></a></li>  
            <? } ?>
            </ul>
          <? } ?>
          </li>
          <? echo (($i % 2 > 0) && $i < count($menu)-1) ? '</ul><ul id="nav">' : ''; $i++; ?>
      <? } ?>
    <? } ?>
    </ul>
</div>