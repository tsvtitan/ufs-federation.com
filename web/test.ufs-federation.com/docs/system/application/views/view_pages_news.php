<? if(isset($data_arr)){ ?> 
<div class="news-block">
 <ul class="news hfeed">
 <? foreach($data_arr as $item){ ?>
  <li class="hentry subblock">
    <dl>
      <dt><!--img src="/img/userfiles/news_1.png" alt="" /--></dt>
      <dd>
      <var title="<? echo($item->date); ?>" class="updated published"><? echo($item->date); ?> <? //echo($item->name); ?></var>
      </dd>
    </dl>
    <div class="entry-summary">
      <p>
          <a href="<? echo($this->phpself.$page_url.'/'.$item->url.$this->urlsufix); ?>" class="url" rel="bookmark">
              <? echo($item->short_content); ?>
          </a>
      </p>
    </div>				
  </li>   
 <? } ?>
 </ul>
 <?echo(isset($pages)?$pages:'')?>
</div>
<? }else{ ?>
  <div class="article">
    <var class="news-logo news">
      <var><? echo($data->date); ?></var> <span><? echo($data->name); ?></span>
    </var>
        <? echo($data->content); ?>
  </div>
    <? if(count($data->prev)>0){ ?>
    <div class="news-more">
      <h3><? echo(dictionary('Другие новости')); ?></h3>
        <ul class="other-links">
            <? foreach($data->prev as $val){ ?>
            <li><a href="<? echo($this->phpself.$page_url.'/'.$val->url.$this->urlsufix); ?>" class="url"><? echo($val->timestamp); ?> - <? echo($val->name); ?></a></li>
            <? } ?>
        </ul>
    </div>
    <? } ?>
<? } ?>
