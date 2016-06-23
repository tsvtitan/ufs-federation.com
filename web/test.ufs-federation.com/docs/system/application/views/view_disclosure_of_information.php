<div class="h_block">
  <div class="postion">
    <? if($data){ ?>
    <ul>
      <li><a href="<? echo($this->phpself.$this->page_url.$this->urlsufix); ?>"><? echo(dictionary('Раскрытие информации')); ?></a></li>
      <? if($data->page_url!='' and $data->page_url!='all'){ ?>
      <li><a href="<? echo($this->phpself.$this->page_url.'/'.$data->parent_url.$this->urlsufix); ?>"><? echo($data->parent_name); ?></a></li>
      <? } ?>
    </ul>
    <h1<? echo((strlen($data->header)>65)?' class="small-text"':''); ?>><? echo($data->header); ?></h1>
    <? } ?>
  </div>
</div>
<div class="block">    
    <? if((count($data->year_arr)>0)&&($data->calendar_type=='0')){ ?>
    <div id="tabs">
    <ul class="year">
      <? foreach($data->year_arr as $item){ ?>
        <li<? echo(($data->year_url==$item)?' class="ui-state-active"':''); ?>><a href="<? echo($this->phpself.$this->page_url.'/'.$data->parent_url.'/'.$data->page_url.'/'.$item.$this->urlsufix); ?>"><? echo($item); ?></a></li>
      <? } ?>
    </ul>
        <div class="tabs">
          <? foreach($data->content as $item){ ?>
            <div class="subblock">
                <h2><? echo($item->name); ?></h2>
                <ul class="pdf">
                  <? foreach($item->arr as $val){ ?>
                    <li><a target="_blank" href="<? echo($this->phpself.$this->page_url.'-download-pdf/'.$val->url.$this->urlsufix); ?>"><? echo($val->name); ?></a> 
                        <br><small>(<? echo(dictionary('Объем файла')); ?> - <? echo($val->size); ?>; <? echo(dictionary('Дата публикации')); ?> <? echo($val->published); ?>)</small>
                    </li>
                  <? } ?>
                </ul>
            </div>
          <? } ?>
        </div>
    </div>		     			
    <? } else { ?>
       <div class="tabs"> 
              <ul class="pdf">
                <? foreach($data->content as $item){ ?>
                  <? foreach($item->arr as $val){ ?>
                    <li><a target="_blank" href="<? echo($this->phpself.$this->page_url.'-download-pdf/'.$val->url.$this->urlsufix); ?>"><? echo($val->name); ?></a> 
                        <br><small>(<? echo(dictionary('Объем файла')); ?> - <? echo($val->size); ?>; <? echo(dictionary('Дата публикации')); ?> <? echo($val->published); ?>)</small>
                    </li>
                  <? } ?>
                <? } ?>  
              </ul>
        </div>  
     <? } ?>
</div>