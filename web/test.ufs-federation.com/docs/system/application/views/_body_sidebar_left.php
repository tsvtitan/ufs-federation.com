<ul class="navig">
<? if($menu){ ?>
  <? foreach($menu as $item){ ?>
  <li<? echo($item->select); ?>>
    <? if ((dictionary('Товарный рынок')==$item->name) || (dictionary('Репо паев фонда')==$item->name)) { ?>
    <table class='table-item'>
      <tr>
        <td class="td-name"><a href="<? echo($this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a></td>
        <? if ($this->site_lang=="ru") { ?>
        <td class="td-new"><img src='/images/new_ru.gif'></td>
        <? } else { ?>  
        <td class="td-new"><img src='/images/new_en.gif'></td>
        <? } ?>
      </tr>
    </table>
    <? } else { ?>
    <a href="<? echo($this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a>
    <? } ?>
    <? if(isset($item->sub)){ ?>
      <ul>
        <? foreach($item->sub as $val){ ?>
          <? if(dictionary('Технический анализ')==$val->name) { ?>
          <li<? echo($val->select); ?>>
            <table>
              <tr>
                <td class="td-name"><a href="<? echo($this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.'/'.$val->url.$this->urlsufix); ?>"><? echo($val->name); ?></a></td>
                <? if ($this->site_lang=="ru") { ?>
                <td class="td-new"><img src='/images/new_ru.gif'></td>
                <? } else { ?>  
                <td class="td-new"><img src='/images/new_en.gif'></td>
                <? } ?>
              </tr>
            </table>
          </li>
          <? } else { ?>
          <li<? echo($val->select); ?>><a href="<? echo($this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.'/'.$val->url.$this->urlsufix); ?>"><? echo($val->name); ?></a></li> 
          <? } ?>
        <? } ?>
      </ul>
    <? } ?>
  </li>
  <? } ?>
<? } ?>
</ul>
<? if(isset($other)){ ?>
<div class="see-also">
  <small>Смотрите также:</small>
  <ul>
    <li><a href="">Модельные портфели</a></li>
    <li><a href="">Рекомендации</a></li>
    <li><a href="">Сотрудничество с агентами</a></li>
  </ul>
</div>
<? } ?>