
<? if(!empty($groups)) { ?>

<script>

function font_technical_research_click(obj,group_id) {

	  s=document.getElementById('div_technical_research_short_group_'+group_id);
	  l=document.getElementById('div_technical_research_long_group_'+group_id);

	  if ((s.style.visibility=='visible')||(s.style.visibility=='')) {
		  s.style.visibility='hidden';
		  s.style.display='none';
		  l.style.visibility='visible';
		  l.style.display='block';
		  obj.innerText='<? echo(dictionary('свежие обзоры')); ?>';
	  } else {
		  s.style.visibility='visible';
		  s.style.display='block';
		  l.style.visibility='hidden';
		  l.style.display='none';
		  obj.innerText='<? echo(dictionary('весь список')); ?>';
	  }	  		
	  
}

</script>

<div class="section-technical-research">
  <ul class>
    <? foreach($groups as $group) { ?>
      <? if(sizeOf($group->shorts)>0) { ?>
        <li><? echo($group->name); ?></li>
        <div id="div_technical_research_short_group_<? echo($group->group_id); ?>" class="div_technical_research_shorts">
          <table>
            <? foreach($group->shorts as $item) { ?>
            <tr>
              <td class='td-date'><? echo($item->date); ?></td>
              <td class='td-link'>
                <table>
                  <tr>
                    <td class='td-name'><a href="<? echo($item->url); ?>"><? echo($item->name); ?></a></td>
                    <? if ($this->site_lang=="ru") { ?>
                    <td class='td-new'><? if(isset($item->new)) { ?><img src='/images/new_ru.gif'><? } ?></td>
                    <? } else { ?>
                    <td class='td-new'><? if(isset($item->new)) { ?><img src='/images/new_en.gif'><? } ?></td>
                    <? } ?> 
                  </tr>
                </table>    
              </td>  
            </tr> 
            <? } ?>
          </table>
        </div>
        <div id="div_technical_research_long_group_<? echo($group->group_id); ?>" class="div_technical_research_longs">
          <table>
            <? foreach($group->longs as $item) { ?>
            <tr>
              <td class='td-date'><? echo($item->date); ?></td>
              <td class='td-link'>
                <table>
                  <tr>
                    <td class='td-name'><a href="<? echo($item->url); ?>"><? echo($item->name); ?></a></td>
                    <? if ($this->site_lang=="ru") { ?>
                    <td class='td-new'><? if(isset($item->new)) { ?><img src='/images/new_ru.gif'><? } ?></td>
                    <? } else { ?>
                    <td class='td-new'><? if(isset($item->new)) { ?><img src='/images/new_en.gif'><? } ?></td>
                    <? } ?>
                  </tr>
                </table>    
              </td>  
            </tr> 
            <? } ?>
            </table>
        </div>
        <? if(sizeOf($group->longs)>sizeOf($group->shorts)) { ?>
        <div class='div-list'><font class='font-list' onclick="font_technical_research_click(this,'<? echo($group->group_id); ?>');"><? echo(dictionary('весь список')); ?></font></div>
        <? } ?>
      <? } ?>
    <? } ?>
  </ul>
</div>
<? } ?>