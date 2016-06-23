<div class="h_block">
  <div class="postion">
    <? if($data){ ?>
    <ul>
      <li><a href="<? echo($this->phpself.$this->page_url.$this->urlsufix); ?>"><? echo(dictionary('Файлы для скачивания')); ?></a></li>
    </ul>
    <h1<? echo((strlen($data->header)>65)?' class="small-text"':''); ?>><? echo($data->header); ?></h1>
    <? } ?>
  </div>
</div>
<div class="block">
   

    
    <? if(count($data->content)>0){ ?>
    <div id="tabs">
        <div class="tabs">
            <div class="subblock">
                <ul class="zip">
                  <? foreach($data->content as $val){ ?>
                    <li><a target="_blank" href="<? echo($this->phpself.'download-files/'.$val->url.$this->urlsufix); ?>"><? echo($val->name); ?></a>
                        <br><small>(<? echo(dictionary('Объем файла')); ?> - <? echo($val->size); ?>; <? echo(dictionary('Дата публикации')); ?> <? echo($val->timestamp); ?>)</small>
                    </li>
                  <? } ?>
                </ul>
            </div>
        </div>
    </div>		     			
    <? } ?>
</div>