<div class="subblock-anltc">
  <ul class="news hfeed">
	<li class="hentry subblock">
	  
	  <dl>
		<dt><h2 class="entry-title"><? echo($data->name); ?></h2></dt>
		<dd><var title="<? echo($data->date); ?>"><? echo($data->date); ?><? if (isset($data->subject) && (trim($data->subject)!='')) { echo(' / '.$data->subject); } ?></var></dd>
	  </dl>

	  <div class="entry-summary">
		<p><? echo($data->content); ?></p>
	  </div>	
      
        
      <? if(!empty($files)) { ?>
        <div>
          <ul class="docs"> 
          <? foreach($files as $item) { ?>
            <?if(!empty($item->_file)) {?>
            <li class="pdf">
			  <a href="http://<? echo($this->site_lang.'.'.$this->host); ?>/download-files/<? echo($item->url.$this->mail_urlsufix); ?>" target="_blank"><? echo($item->name); ?> <var>(<?echo(get_filesize_kb($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$item->_file))?>)</var></a>
		    </li>
            <? } ?>
          <? } ?>
		  </ul>        
        </div>
      <? } ?>
      
      <? if(!empty($links)) { ?>
      <hr>
      <div>
        <h2 class="entry-title"><? echo(dictionary('Другие обзоры')); ?></h2>
        <ul class="links">
        <? foreach($links as $item) { ?>
          <li><var><? echo($item->date); ?></var><a href="<? echo($item->url); ?>"><? echo($item->name); ?></a></li>
        <? } ?>  
        </ul>
      </div>
      <hr>
      <? } ?>
      
 	</li>
  </ul>
</div>