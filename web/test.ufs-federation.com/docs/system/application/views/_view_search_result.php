
<script>

function option_change(obj) {
  fm=document.getElementById('search_form');
  fm.submit();
}

</script>

<div class="h_block">
  <div class="postion">
<h1 ><a href="<? echo($this->phpself.$this->page_url.'/search'.$this->urlsufix); ?>"><? echo(dictionary('Поиск по сайту')); ?></a></h1>
  </div>
</div>
<div class="page-content editor-content':''); ?>">
  <div class="search_blok">  
      <form method="POST" id="search_form" action="<? echo($this->phpself.'search.html'); ?>">
        <select name="option" onchange="option_change(this);">
          <option <? if($option==SPH_MATCH_ANY) { echo("selected "); } ?>value="<? echo(SPH_MATCH_ANY); ?>"><? echo(dictionary('Неточное совпадение')); ?></option>
          <option <? if($option==SPH_MATCH_PHRASE) { echo ("selected "); } ?>value="<? echo(SPH_MATCH_PHRASE); ?>"><? echo(dictionary('Точное совпадение')); ?></option>
          </select>
        <input type="text" value="<?echo($search_word)?>" name="searched" id="searched" placeholder="<? echo(dictionary('Поиск по сайту')); ?>"  />
        <input type="submit" value="<? echo(dictionary('найти')); ?>" />
      </form>
  </div>  
 <p><? echo(dictionary('Вы искали')); ?> <strong>«<?echo($search_word)?>»</strong></p>  
     <p><? echo(dictionary('Результатов')); ?>: <strong><?echo($result_count)?></strong> </p> 

    <?if(count($items)>0):?>
    
        <div class="news-block">
               <ul class="news hfeed">
                 <?foreach($items as $it):?>
                 <li class="hentry subblock">                     
                     <dl>
                      <dt><!--img src="/img/userfiles/news_1.png" alt="" /--></dt>
                      <dd>
                        <var><? echo($it['date']); ?> 
                          <? if (isset($it['menu'])) { ?>
                            <i> 
                            <? foreach($it['menu'] as $mi) { ?>
                              <span> / </span><a href="<? echo($mi['link']); ?>" ><? echo($mi['name']); ?></a>
                            <? } ?>
                            </i>
                          <? } ?>  
                        </var> 
                      </dd>
                    </dl>
                     <div class="entry-summary">
                      <p>
                        <a href="<? echo($it['link']); ?>" class="url" rel="bookmark">	
                            <? echo($it['text']); ?>
                        </a>
                      </p>
                    </div>
                 </li>                 
                 <?endforeach;?>
               </ul>
            <?echo(isset($pages)?$pages:'')?>
        </div>
    <?endif;?>
</div>
    