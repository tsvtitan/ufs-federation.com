<? if(isset($data_arr)){ ?> 
<div class="press">
 <ul class="news hfeed">
 <? foreach($data_arr as $item){ ?>
  <li class="hentry subblock">
    <dl>
      <dt>
        <? if(!empty($item->img)){ ?>
          <img src="<? echo($this->base_url.'/upload/press_about_us/small/'.$item->img); ?>" />
        <? } ?>
      </dt>
      <dd>
      <var title="<? echo($item->date); ?>" class="updated published"><? echo($item->date); ?></var><? echo($item->publisher_name); ?>
      </dd>
    </dl>
    <div class="entry-summary">
      <h2 class="entry-title"><a href="<? echo($this->phpself.$page_url.'/'.$item->url.$this->urlsufix); ?>" class="url" rel="bookmark"><? echo($item->name); ?></a></h2>
    <p><? echo($item->short_content); ?></p>
    </div>				
  </li>
 <? } ?>
 </ul> <?echo(isset($pages)?$pages:'')?>
</div>
<? }else{ ?>
  <div class="article">
    <var class="news-logo clearfix">
      <? if(!empty($data->img)){ ?>
      <span class="block-img"><img src="<? echo($this->base_url.'/upload/press_about_us/small/'.$data->img); ?>" /></span>
      <? } ?>
      <var><? echo($data->date); ?> <span><? echo($data->publisher_name); ?></span></var> 
      <h2 class="entry-title"><? echo($data->name); ?></h2>
    </var>
        <p><? echo($data->content); ?></p>
  </div>
<? } ?>
