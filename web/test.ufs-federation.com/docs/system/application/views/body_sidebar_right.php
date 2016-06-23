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
<address class="glavni-office">
  <? echo($this->load->view('body_contacts','',true)) ?>
  <? echo($this->load->view('body_events','',true)) ?>
  <? echo($this->load->view('body_extra','',true)) ?>
  
</address>
<a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>  


