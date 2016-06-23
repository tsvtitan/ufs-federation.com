<? if(is_array($data->held)){ ?>
<div class="konver-list">
  <h2><? echo(dictionary('Состоявшиеся ранее')); ?></h2>
  <? foreach($data->held as $item){ ?>
  <div class="article">
    <var><? echo($item->timestamp); ?></var>
    <a href="<? echo($this->phpself.$data->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot"><? echo($item->name); ?></a>
  </div>
  <? } ?>
  <a class="all-news" href="<? echo($this->phpself.$data->page_url.'/all'.$this->urlsufix); ?>"><? echo(dictionary('Все мероприятия')); ?></a>
</div>
<? } ?>
<? if(is_array($data->upcoming)){ ?>
<div class="konver-list">
  <h2><? echo(dictionary('Ближайшие мероприятия')); ?></h2>
  <? foreach($data->upcoming as $item){ ?>
  <div class="article">
    <var><? echo($item->timestamp); ?></var>
    <a href="<? echo($this->phpself.$data->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot"><? echo($item->name); ?></a>
  </div>
  <? } ?>
  <a class="all-news" href="<? echo($this->phpself.$data->page_url.'/all'.$this->urlsufix); ?>"><? echo(dictionary('Все мероприятия')); ?></a>
</div>
<? } ?>

<address class="glavni-office">
  <? echo($this->load->view('body_contacts','',true)) ?>
</address>