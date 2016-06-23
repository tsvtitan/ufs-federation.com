<? if(isset($data_arr)){ ?> 
<div class="news-block">
 <ul class="news hfeed">
 <? foreach($data_arr as $item){ ?>
  <li class="hentry subblock">
    <dl>
      <dt><!--img src="/img/userfiles/news_1.png" alt="" /--></dt>
      <dd>
      <var><? echo($item->date); ?><? //echo($item->name); ?></var> 
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
</div>
<? }else{ ?>
  <div class="article">
    <var class="news-logo news">
      <var><? echo($data->date); ?></var> <span><? echo($data->name); ?></span>
    </var>
        <? echo($data->content); ?>
  </div>
<? } ?>
