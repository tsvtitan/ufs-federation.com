  <div id="navigation">
    <ul class="sub-nav">
      <li><a href="<? echo($this->phpself.'downloads'.$this->urlsufix); ?>"><? echo(dictionary('Документы и файлы')); ?></a></li>
      <!-- <li class="login"><a href="http://www.ufs-federation.com">Вход для клиентов</a></li> -->
	  <li>
	    <div class="search-form">
          <form method="POST" action="<? echo($this->phpself.'search.html'); ?>">
            <input type="text" name="searched" id="searched" placeholder="<? echo(dictionary('Поиск по сайту')); ?>"  />
            <input type="submit" value="" />
          </form>
        </div>
	  </li>	
       <li class="language lang-<? echo($this->site_lang); ?>">
            <a onclick="javascript: toggle_id('popup-language');" href="javascript://"><? echo(dictionary('Язык')); ?></a>
            <div id="popup-language">
             <? foreach($langs as $item){ ?>
              <a href="<? echo($this->phpself.'lang-select/'.$item->lang.$this->urlsufix); ?>" class="lang-<? echo($item->lang); ?>"><span><? echo($item->name); ?></span></a>
             <? } ?>
            </div>
          </li>
    </ul>
    <ul id="nav">
    <? if($menu){ ?>
      <? foreach($menu as $item){ ?>
      <li<? echo($item->select); ?>><a href="<? echo($this->phpself.'pages/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a></li>
      <? } ?>
    <? } ?>
    </ul>
  </div>