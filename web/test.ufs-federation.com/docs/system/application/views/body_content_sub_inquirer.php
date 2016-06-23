<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,300&amp;subset=latin,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/css/anketa.css">
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script src="/js/jquery.validate.js" type="text/javascript"></script>
<script src="/js/jquery.mask.min.js" type="text/javascript"></script>
<script src="/js/jquery.scrollTo.min.js" type="text/javascript"></script>
<script src="/js/popup.js" type="text/javascript"></script>
<script src="/js/anketa.js" type="text/javascript"></script>
<div id="callback-popup" class="popup-popup popup-mode-call">
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
<div class="popup-cover"><a class="close-dialog"><i></i></a></div>

<? if (isset($submit)) {
  if (!$submit) { ?>
  <div class="splash">
    <h3>Уважаемый инвестор!</h3>
    <p>Надеемся, что наши торговые идеи позволяют увеличивать Ваш капитал, а&nbsp;аналитические обзоры &laquo;держать руку на&nbsp;пульсе&raquo; рынка.</p>
    <p>Изо дня в&nbsp;день мы&nbsp;помогаем нашим клиентам находить и&nbsp;использовать максимальные возможности.</p>
    <p>Будем признательны, если&nbsp;Вы оцените наши тарифные планы на&nbsp;брокерское и&nbsp;депозитарное обслуживание.</p>
  </div>
  <form method="post" id="anketa-part1"><input type="hidden" name="part" value="1"/>
    <h3>Просим Вас оценить:</h3>
    <ul class="list-items">
      <li><strong>1. Величины торговых оборотов</strong>
        <span class="hr"></span>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios1" id="optionsRadios11" value="Все подходит">
            Все подходит, я&nbsp;легко нахожу свой оборот в&nbsp;таблице
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios1" id="optionsRadios12" value="Деления должны быть меньше">
            Нет, деления должны быть меньше
          </label>
          <input class="flat-ui hidden" type="text" id="q1-option2" name="q1-option2" placeholder="Укажите свой вариант"/>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios1" id="optionsRadios13" value="Комиссия не должна зависеть от оборота">
            Комиссия не&nbsp;должна зависеть от&nbsp;оборота
          </label>
        </div>
      </li>
      
      <li><strong>2. Величина комиссии брокера</strong>
        <span class="hr"></span>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios2" id="optionsRadios21" value="Комиссия по всем оборотам устраивает"/>
            Комиссия по&nbsp;всем оборотам устраивает
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios2" id="optionsRadios22" value="Комиссия для минимального оборота высока"/>
            Комиссия для минимального оборота высока
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios2" id="optionsRadios23" value="Комиссия для максимального оборота высока"/>
            Комиссия для максимального оборота высока
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios2" id="optionsRadios24" value="Комиссия на внебиржевом рынке высока"/>
            Комиссия на&nbsp;внебиржевом рынке высока
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios2" id="optionsRadios25" value="Комиссия на внебиржевом рынке устраивает"/>
            Комиссия на&nbsp;внебиржевом рынке устраивает
          </label>
        </div>
      </li>
      
      <li><strong>3. Места для заключения сделок</strong>
        <span class="hr"></span>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios3" id="optionsRadios31" value="Да, я имею представление по всем площадкам, на которых хотел бы совершать торги">
            Да, я&nbsp;имею представление по&nbsp;всем площадкам, на&nbsp;которых хотел&nbsp;бы совершать торги
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios3" id="optionsRadios32" value="Нет, мне не хватает информации по бирже">
            Нет, мне не&nbsp;хватает информации по бирже
          </label>
          <input class="flat-ui hidden" type="text" id="q3-option2" name="q3-option2" placeholder="Укажите биржу"/>
        </div>
      </li>
      
      <li><strong>4. Как вы&nbsp;оцениваете тарифный план в&nbsp;целом</strong>
        <span class="hr"></span>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios4" id="optionsRadios41" value="Мне нравится, что он единый">
            Мне нравится, что он&nbsp;единый
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios4" id="optionsRadios42" value="Тарифных планов должно быть несколько">
            Тарифных планов должно быть несколько
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios4" id="optionsRadios43" value="Тарифный план устраивает">
            Тарифный план устраивает
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios4" id="optionsRadios44" value="Тарифный план не устраивает">
            Тарифный план не&nbsp;устраивает
          </label>
          <input class="flat-ui hidden" type="text" id="q4-option4" name="q4-option4" placeholder="Указажите причину"/>
        </div>
      </li>
      
      <li><strong><span class="multilinefix">5.&nbsp;</span>Тарифы на&nbsp;зачисление и&nbsp;списание ценных бумаг по&nbsp;внебиржевым сделкам</strong>
      
        <span class="hr"></span>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios5" id="optionsRadios51" value="Тариф комфортный, недорогой">
            Тариф комфортный, недорогой
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios5" id="optionsRadios52" value="Тариф дорогой">
            Тариф дорогой
          </label>
        </div>
      </li>
      
      <li><input name="experience" class="flat-ui mini fright" type="text" maxlength="2" value="" placeholder="лет" />Пожалуйста, укажите Ваш торговый опыт на&nbsp;рынке ценных бумаг</li>
      <li><input name="brokers" class="flat-ui mini fright" type="text" maxlength="4" value="" placeholder="шт." />Пожалуйста, укажите количество брокеров, с&nbsp;которыми у&nbsp;Вас заключены договоры</li>
      <li><input name="email" class="flat-ui fright" style="margin-top: -0.48em; width: 240px" type="text" name="email" placeholder="Ваш Email"/>Ваш email</li>
      <li>
        <p><input class="flat-ui" type="submit" name="submit" value="Отправить" /></p>
      </li>
    </ul>
  </form>
<? } else {
    if ($success) {
      if ($part == 1) { ?>

<form method="post" id="anketa-part2"><input type="hidden" name="part" value="2"/><input type="hidden" name="email" value="<?=$email?>"/>
  <h3 id="thanks">Благодарим Вас за&nbsp;уделенное нам время<br/>и&nbsp;Ваши ответы!</h3>
  
  <div id="thanks2extra">
    <p>Вы&nbsp;можете задать свои вопросы нашим специалистам</p>
    <ul>
      <li>Посредством онлайн консультанта</li>
      <li>Позвонив по&nbsp;телефону <span style="white-space: nowrap">+7&nbsp;495&nbsp;781 73 00</span></li>
      <li>Или заказав <a id="callback" href="/">обратный звонок</a></li>
    </ul>
    <p style="text-align: center; font-weight: bold">Удачных инвестиционных решений!</p>
  </div>
  
  <h3>Если Вы&nbsp;не&nbsp;устали, мы&nbsp;будем Вам признательны за&nbsp;оценку наших аналитических материалов.</h3>
  <ul class="list-items">
    <li><strong><span class="multilinefix">1.&nbsp;</span>Пожалуйста, выберите из&nbsp;списка самые полезные для Вас обзоры (не&nbsp;более трех)</strong>
      <span class="hr"></span>
      <!-- <p>Долговой рынок</p> -->
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsCheckboxes111" value="Ежедневные комментарии по долговому рынку">
          Ежедневные комментарии по&nbsp;долговому рынку
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Ежемесячные аналитические обзоры">
          Ежемесячные аналитические обзоры
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Прочие аналитические материалы по долговому рынку">
          Прочие аналитические материалы по&nbsp;долговому рынку
        </label>
      </div>
      
      <!-- <p>Рынок акций</p> -->
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Ежедневные комментарии по рынку акций">
          Ежедневные комментарии по&nbsp;рынку акций
        </label>
      </div>
          
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Ежедневные комментарии по техническому анализу">
          Ежедневные комментарии по&nbsp;техническому анализу
        </label>
      </div>
      
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Торговые идеи и рекомендации на основе технического анализа">
          Торговые идеи и&nbsp;рекомендации на&nbsp;основе технического анализа
        </label>
      </div>
      
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Прочие аналитические материалы по рынку акций">
          Прочие аналитические материалы по&nbsp;рынку акций
        </label>
      </div>
      
      <!-- <p>Товарный рынок</p> -->
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Комментарии по рынку золота">
          Комментарии по&nbsp;рынку золота
        </label>
      </div>
      
      <!-- <p>Экономика</p> -->
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Макроэкономические обзоры">
          Макроэкономические обзоры
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Тенденции в экономике РФ">
          Тенденции в&nbsp;экономике РФ
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Мониторинг банковского сектора">
          Мониторинг банковского сектора
        </label>
      </div>
      
      <!-- <p>Другое</p> -->
      <div class="radio">
        <label>
          <input type="checkbox" name="optionsCheckbox[]" id="optionsRadios11" value="Модельный портфель">
          Модельный портфель
        </label>
      </div>
    </li>
    
    <li><strong><span class="multilinefix">2.&nbsp;</span>Пожалуйста, укажите в&nbsp;какое время&nbsp;Вы предпочитаете читать обзор</strong>
      <span class="hr"></span>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios12" id="optionsRadios121" value="C 7 до 9 часов утра">
          C 7 до 9 часов утра
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios12" id="optionsRadios122" value="После начала торгов и до 12.00">
          После начала торгов и&nbsp;до&nbsp;12.00
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios12" id="optionsRadios123" value="Вечером">
          Вечером
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios12" id="optionsRadios124" value="В течение всего дня">
          В&nbsp;течение всего дня
        </label>
      </div>
    </li>
    
    <li><strong><span class="multilinefix">3.&nbsp;</span>Вся&nbsp;ли необходимая информация содержится в&nbsp;наших обзорах для принятия Вами инвестиционного решения</strong>
      <span class="hr"></span>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios13" id="optionsRadios131" value="Да">
          Да
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios13" id="optionsRadios132" value="Нет, мне не хватает информации">
          Нет, мне не&nbsp;хватает информации
        </label>
        <input class="flat-ui hidden" type="text" id="q13-option2" name="q13-option2" placeholder="Укажите какой"/>
      </div>
    </li>
    
    <li><strong>4. Как Вы&nbsp;предпочитаете читать нашу аналитику?</strong>
      <span class="hr"></span>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios14" id="optionsRadios141" value="После получения письма на почту">
          После получения письма на&nbsp;почту
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios14" id="optionsRadios142" value="Через мобильное приложение">
          Через мобильное приложение
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios14" id="optionsRadios143" value="Непосредственно на сайте">
          Непосредственно на&nbsp;сайте
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" name="optionsRadios14" id="optionsRadios144" value="Всеми указанными способами">
          Всеми указанными способами
        </label>
      </div>
    </li>
    
    <li>
      <!-- <p class="mute acenter">Отправляя форму, Вы&nbsp;подтверждаете,<br/>что ознакомлены с&nbsp;<a id="show-policy2" title="Настоящим, нажав кнопку Продолжить, я выражаю и подтверждаю свое согласие на обработку, как это определено в ФЗ «О персональных данных» N 152-ФЗ от 27.07.2006 года, моих персональных данных любым способом, в том числе: хранение, запись на электронные носители, сбор, систематизация, уточнение (обновление, изменение), использование, обезличивание, блокирование, уничтожение. Настоящее согласие предоставляется компании ООО «ИК «Ю Эф Эс Финанс» (адрес места нахождения: Российская Федерация, 105082, г. Москва, Балакиревский пер., д.19, стр.1, оф.206) в целях заключения договора по инициативе субъекта персональных данных. Настоящее согласие выдано на неопределенный срок.">правилами конкурса</a>.</p> -->
      <p><input class="flat-ui" type="submit" name="submit" value="Отправить" /></p>
    </li>
  </ul>
</form>

<? } elseif ($part == 2) { ?>

<h3 id="thanks2">Благодарим за&nbsp;Вашу помощь в&nbsp;улучшении наших аналитических материалов!</h3>
<div id="thanks2extra">
  <p>Вы&nbsp;можете задать свои вопросы нашим специалистам</p>
  <ul>
    <li>Посредством онлайн консультанта</li>
    <li>Позвонив по&nbsp;телефону <span style="white-space: nowrap">+7&nbsp;495&nbsp;781 73 00</span></li>
    <li>Или заказав <a id="callback" href="/">обратный звонок</a></li>
  </ul>
  <p style="text-align: center; font-weight: bold">Удачных инвестиционных решений!</p>
</div>

<? }
} else { ?>
    <? if($this->site_lang=='ru') { ?> 
        <h3>Произошла ошибка! Попробуйте ещё раз.</h3>
    <? } ?>
    <? if($this->site_lang=='en') { ?> 
        <h3>An error has occurred. Please try again.</h3>
    <? } ?>
    <? if($this->site_lang=='de') { ?> 
        <h3>Ein Fehler ist geschehen. Versuchen Sie bitte noch mal.</h3>
    <? } ?>
    <? if($this->site_lang=='fr') { ?> 
        <h3>Une erreur est survenue. Essayez de nouveau s’il vous plait.</h3>
    <? } ?>
<? } ?>

<? } } ?>