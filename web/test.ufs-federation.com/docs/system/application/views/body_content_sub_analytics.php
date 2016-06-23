  <div class="overflow over-promo">
    <div class="column_2">
      <div class="section-akzii">
        <img src="<? echo($this->base_url); ?>/img/userfiles/ill-akzii-1.png" alt="" />
        <div class="text">
          <h2><a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('debt_market').$this->urlsufix); ?>"><? echo(dictionary('Долговой рынок')); ?></a></h2>
          <p><? echo(isset($debt_market->content)?$debt_market->content:''); ?></p>
        </div>
      </div>
    </div>
    <div class="column_2">
      <div class="section-akzii">
        <img src="<? echo($this->base_url); ?>/img/userfiles/ill-akzii-2.png" alt="" />
        <div class="text">
          <h2><a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('share_market').$this->urlsufix); ?>"><? echo(dictionary('Рынок акций')); ?></a></h2>
          <p><? echo(isset($share_market->content)?$share_market->content:''); ?></p>
        </div>
      </div>
    </div>
  </div>
  <? if (isset($news)) { ?>
  <div class="overflow promo-anal">
    
    <h2><? echo(dictionary('Новости аналитики')); ?></h2>
    <? $a=true; foreach($news as $item){ ?>
    <div class="column_3">
      <div class="article">
        <var><? echo($item->timestamp); ?></var>
        <a href="<? echo($this->phpself.$item->page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->short_content); ?></a>
      </div>
      <? if($a==true){ ?>
        <a class="all-news" href="<? echo($this->phpself.$item->page_url.$this->urlsufix); ?>"><? echo(dictionary('Все новости')); ?></a>
      <? } ?>
    </div>
    <? $a=false; }  ?>  
  </div>
  <? }  ?>  