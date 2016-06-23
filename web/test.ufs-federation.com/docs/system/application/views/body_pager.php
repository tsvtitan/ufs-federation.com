<? if($param['count']>1){ ?>
	<ul class="pager">
	<? foreach($data as $item){ ?>
		<? if($item['type']=='link'){ ?>
			<li><a href="<? echo($this->phpself.$this->page_name.$param['url'].'/page/'.$item['id'].$this->urlsufix); ?>"><? echo($item['id']); ?></a></li>
		<? }elseif($item['type']=='prev'){ ?>
			<li class="direction prev-page"><a href="<? echo($this->phpself.$this->page_name.$param['url'].'/page/'.$item['id'].$this->urlsufix); ?>" rel="prev" title="Previous page">« <span></span></a></li>
		<? }elseif($item['type']=='next'){ ?>
			<li class="direction next-page"><a href="<? echo($this->phpself.$this->page_name.$param['url'].'/page/'.$item['id'].$this->urlsufix); ?>" rel="next" title="Next page"> »<span></span></a></li>
		<? }elseif($item['type']=='select'){ ?>
			<li class="sel"><? echo($item['id']); ?></li>
		<? }elseif($item['type']=='text'){ ?>
			<li class="text"><? echo($item['id']); ?></li>
		<? } ?>
	<? } ?>
	</ul>
<? } ?>