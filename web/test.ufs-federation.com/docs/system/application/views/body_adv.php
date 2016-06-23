<script>
$(document).ready(function() {
	$('#right_adv_click').click(function() {
		$('#right_adv_ul').toggle('fast');
	});
});
/* function right_adv_h6_click(obj) {
	  div=document.getElementById('right_adv_ul');	  
	  div2=document.getElementById('right_adv');
	  
	  if (div.style.visibility=='visible'){
	    div.style.visibility='hidden';
	    div2.style.marginBottom='0px';
	  } else {
		div2.style.marginBottom='40px';   
	    div.style.visibility='visible';
	  }
	}
*/
</script>
<!-- onclick="right_adv_h6_click(this);" -->
<div id="right_adv">
  <h6 id="right_adv_click"><? echo($right_avd_caption) ?></h6>
</div>
  <div id="right_adv_ul">
    <ul>
      <? if($right_adv_menu){ ?>
        <? foreach($right_adv_menu as $item){ ?>
		    <li><a href="<? echo($this->phpself.'pages/'.$item->url); ?>"><? echo($item->name); ?></a></li>
        <? } ?>
      <? } ?>
    <br class="clear"/>
    </ul>
  </div>  
