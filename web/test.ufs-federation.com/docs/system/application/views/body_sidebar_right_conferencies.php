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

    <? if(is_array($data->held)){ ?>
<div class="konver-list">
  <? if($this->last_page_name=='letnyaya-veranda') { ?>
    <h2><? echo(dictionary('Состоявшиеся ранее')); ?></h2>
  <? foreach($data->held as $item){ ?>
  <div class="article">
    <var><? echo($item->timestamp); ?></var>
    <? echo((isset($item->link) && trim($item->link)!='')?'<a href="'.$item->link.'" class="shot">'.$item->name.'</a>':$item->name) ?>
  </div>
  <? } ?>
  <a class="all-news" href="<? echo($this->phpself.$data->page_url.'/all'.$this->urlsufix); ?>"><? echo(dictionary('Все мероприятия')); ?></a>
</div><? } ?>
<? } ?>
<? if(is_array($data->upcoming)){ ?>
<div class="konver-list">
  <h2><? echo(dictionary('Ближайшие мероприятия')); ?></h2>
  <? foreach($data->upcoming as $item){ ?>
  <div class="article">
    <var><? echo($item->timestamp); ?></var>
    <a href="<? echo($this->phpself.$data->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot"><? echo($item->name); ?></a>
  </div>
  <? } ?>
  <a class="all-news" href="<? echo($this->phpself.$data->page_url.'/all'.$this->urlsufix); ?>"><? echo(dictionary('Все мероприятия!!')); ?></a>
</div>
<? } ?>
    
<address class="glavni-office">
  <? echo($this->load->view('body_contacts','',true)) ?>
</address>
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>