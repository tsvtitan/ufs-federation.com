  
    <? if(isset($data_arr)){ ?> 
 <? foreach($data_arr as $item){ ?>
  <div class="article">
    <var class="news-logo news">
      <var><? echo($item->date); ?></var> <span>
		<a href="<? echo($this->phpself.$page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot">
		<? echo($item->name); ?>
		</a>
	  </span>
    </var>
        <? echo($item->short_content); ?>
  </div>
 <? } ?>
<? }else{ ?>
  <div class="article">
    <var class="news-logo news">
      <var><? echo($data->date); ?></var> <span><? echo($data->name); ?></span>
    </var>
        <? echo($data->content); ?>
  </div>
<? } ?>
