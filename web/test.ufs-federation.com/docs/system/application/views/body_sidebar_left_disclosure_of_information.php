<ul class="navig">
<? if($menu){ ?>
  <? foreach($menu as $item){ ?>
  <li<? echo($item->select); ?>>
    <a href="<? echo($this->phpself.$this->page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
    <? if(isset($item->sub)){ ?>
      <ul>
        <? foreach($item->sub as $val){ ?>
          <li<? echo($val->select); ?>><a href="<? echo($this->phpself.$this->page_url.'/'.$item->url.'/'.$val->url.$this->urlsufix); ?>"><? echo($val->name); ?></a></li>
        <? } ?>
      </ul>
    <? } ?>
  </li>
  <? } ?>
<? } ?>
</ul>