<? if(isset($data_arr)){ ?> 

<? echo(isset($content)?$content:''); ?>

<div class="subblock-anltc">
	<ul class="news hfeed">
 <? foreach($data_arr as $item){ ?>
	<li id="debt_emitter_<? echo($item->id); ?>" class="hentry subblock">
		<dl>
			<dt><h2 class="entry-title"><a href="<? echo($this->phpself.$page_url.'/'.$item->url.$this->urlsufix); ?>"><? echo($item->name); ?></a></h2></dt>
			<dd><var title="<? echo($item->date); ?>"><? echo($item->date); ?><? if (isset($item->subject) && (trim($item->subject)!='')) { echo(' / '.$item->subject); } ?></var></dd>
		</dl>	
			<div class="entry-summary">
				<p><? echo($item->short_content); ?></p>
			</div>	 
			<?if(!empty($item->files)):?>
			<ul class="docs">
            <?foreach($item->files as $item):?>
              <?if(!empty($item->_file)):?>
              	<li class="pdf">
					<a href="http://<? echo($this->site_lang.'.'.$this->host); ?>/download-files/<? echo($item->url.$this->mail_urlsufix); ?>" target="_blank"><? echo($item->name); ?> <var>(<?echo(get_filesize_kb($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$item->_file))?>)</var></a>
				</li>
              <?endif;?>
            <?endforeach;?>
			</ul>
			<?endif;?>
	</li>
 <? } ?>
	</ul>
	<?echo(isset($pages)?$pages:'')?>
</div>
<? }else{ ?>
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
        
      <?if(!empty($files)):?>
      <ul class="docs">
         <?foreach($files as $item):?>
           <?if(!empty($item->_file)):?>
              <li class="pdf">
                <a href="http://<? echo($this->site_lang.'.'.$this->host); ?>/download-files/<? echo($item->url.$this->mail_urlsufix); ?>" target="_blank"><? echo($item->name); ?> <var>(<?echo(get_filesize_kb($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$item->_file))?>)</var></a>
              </li>
           <?endif;?>
         <?endforeach;?>
      </ul>        
      <?endif;?>
      
      <? if(!empty($links)) { ?>
      <hr>
      <div>
        <h2 class="entry-title"><? echo(dictionary('Ещё обзоры по теме')); ?></h2>
        <ul class="links" id="surveys">
        <?
        $cnt = 0;
        foreach($links as $item) { ?>
          <li<?=($cnt>=5)?' style="display:none"':''?>><var><? echo($item->date); ?></var><a href="<? echo($item->url); ?>"><? echo($item->name); ?></a></li>
        <?
        $cnt++;
        } ?>
        </ul>
        <a href="#" class="expand"><? echo(dictionary('Ещё обзоры по теме')); ?></a>
        <script>
        $('.expand').click(function(){
            if ($('#surveys li').is(':hidden')) {
              $('#surveys li').css('display','block');
            }
            return false;
          });
        </script>
      </div>
      <hr>
      <? } ?>
      
 		</li>
	</ul>
</div>	
<? } ?>

