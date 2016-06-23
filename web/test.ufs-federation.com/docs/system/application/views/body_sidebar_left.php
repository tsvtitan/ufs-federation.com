<style>
  
.bubble {
  position: absolute;
  min-width: 10px;
  height: 12px;
  padding: 1px 3px 2px 3px;
  margin-top: -16px;
  margin-left: -1px;
  background: red;
  -webkit-border-radius: 10px;
  -moz-border-radius: 10px;
  border-radius: 10px;
  color: white;
  font-size: 10px;
}

</style>

<ul class="navig">
  
<? if($menu) {
  
  $new = '&nbsp;<span class="new-label">' . ucfirst(dictionary('Новое')) . '</span>';
  $bubble = '<div style="display:inline-block;min-width:12px;text-align:center;"><span class="bubble">%s</span></div>';
  
  foreach($menu as $item) {
    echo '<li ' . $item->select . '>';
    if(isset($item->sub)){
      echo '<a href="' . $this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.$this->urlsufix . '">' 
                       . $item->name . (isset($item->as_new) && ($item->as_new == 1) ? $new : '') 
                                     . ((isset($item->duration_count) && $item->duration_count>0)?sprintf($bubble,$item->duration_count):'') .
           '</a>';
      ?>
      <ul>
        <? foreach($item->sub as $val) { ?>
          <li<? echo($val->select); ?>>
            <a href="<? echo($this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.'/'.$val->url.$this->urlsufix); ?>">
              <? echo $val->name . (isset($val->as_new) && ($val->as_new == 1) && $item->as_new != 1 ? $new : '') 
                                 . ((isset($val->duration_count) && $val->duration_count>0)?sprintf($bubble,$val->duration_count):''); ?>
            </a>
          </li>
        <? } ?>
      </ul>
    <? } else {
      echo '<a href="' . $this->phpself.$this->page_url.'/'.$this->uri->segment(3).'/'.$item->url.$this->urlsufix . '">' 
                       . $item->name . (isset($item->as_new) && ($item->as_new == 1) ? $new : '')
                       . ((isset($item->duration_count) && $item->duration_count>0)?sprintf($bubble,$item->duration_count):'') .
           '</a>';
    } ?>
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