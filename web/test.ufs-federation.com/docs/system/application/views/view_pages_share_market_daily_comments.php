<script>
    (function($){  
      $(function(){  
        var e = $(".scrollTop"),  
        speed = 500;  
  
        e.click(function(){  
            $("html:not(:animated)" +( !$.browser.opera ? ",body:not(:animated)" : "")).animate({ scrollTop: 0}, 500 );  
            return false; //важно!  
        });  
      
    function show_scrollTop(){  
            ( $(window).scrollTop()>300 ) ? e.fadeIn(600) : e.hide();  
        }  
        $(window).scroll( function(){show_scrollTop()} ); show_scrollTop();  
    });  
  
})(jQuery)  
</script>
    
    <? if(isset($data_arr)){ ?> 
 <? foreach($data_arr as $item){ ?>
  <div id="dmarket_daily_<? echo($item->id); ?>" class="article">
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
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>