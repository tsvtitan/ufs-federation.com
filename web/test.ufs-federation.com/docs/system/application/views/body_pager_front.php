<? if($param['count']>1){ ?>
	<ul class="pager-universal">
	<? foreach($data as $item){ ?>
		<? if($item['type']=='link'){ ?>
			<li><a href="<? echo($this->phpself.$param['url'].'/page/'.$item['id'].$this->urlsufix); ?>"><? echo($item['id']); ?></a></li>
		<? }elseif($item['type']=='prev'){ ?>
			<li class="direction nazad"><a href="<? echo($this->phpself.$param['url'].'/page/'.$item['id'].$this->urlsufix); ?>" title="<? echo(dictionary('назад')); ?>"><? echo(dictionary('назад')); ?></a></li>
		<? }elseif($item['type']=='next'){ ?>
			<li class="direction vpered"><a href="<? echo($this->phpself.$param['url'].'/page/'.$item['id'].$this->urlsufix); ?>" title="<? echo(dictionary('вперед')); ?>"><? echo(dictionary('вперед')); ?></a></li>
		<? }elseif($item['type']=='select'){ ?>
			<li class="sel"><a><? echo($item['id']); ?></a></li>
		<? }elseif($item['type']=='text'){ ?>
			<li class="text"><a><? echo($item['id']); ?></a></li>
		<? } ?>
	<? } ?>
	</ul>
<? } ?>