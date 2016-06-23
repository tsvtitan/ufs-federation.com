<? if($this->site_lang=='ru') { ?>

<script>

old_window_resize=window.onresize;
old_window_load=window.onload;

//window.onresize=new_window_resize;
//window.onload=new_window_load;

function set_left_slider_visible()
{
  c=document.getElementById('left_slider_caption');
  div=document.getElementById('left_slider_div');
  
  if ((c!=null)&&(div!=null)) {

	if (document.body.clientWidth>=1800) {
      c.style.visibility='visible';
	} else {
	  c.style.visibility='hidden';  
      div.style.visibility='hidden';  
    }
  }
}

function new_window_resize()
{
  set_left_slider_visible();	
  if (!old_window_resize) {
	old_window_resize();   
  }	  	
}

function new_window_load()
{
  set_left_slider_visible();	
  if (!old_window_load) {
	  old_window_load();   
  }	  	
}

function set_cookie(name, value, expires, path, domain, secure) {

	expires instanceof Date ? expires = expires.toGMTString() : typeof(expires) == 'number' && (expires = (new Date(+(new Date) + expires * 1e3)).toGMTString());
	var r = [name + "=" + escape(value)], s, i;
	for(i in s = {expires: expires, path: path, domain: domain}){
		s[i] && r.push(i + "=" + s[i]);
	}
	return secure && r.push("secure"), document.cookie = r.join(";"), true;
}

function left_slider_span_click(obj) {

  div=document.getElementById('left_slider_div');	  
  
  if (div.style.visibility=='visible'){
    div.style.visibility='hidden';	  
  } else {
    div.style.visibility='visible';	  
  }	  		
  
}


function left_slider_button_click(obj) {

   div=document.getElementById('left_slider_caption');	  
   div2=document.getElementById('left_slider_button');	  
   
   if ((div.style.left=='0px')||(div.style.left=='')) {
	 div.style.left='-350px';
	 div2.className='left_slider_button_open';	  
   } else {
	 div.style.left='0px';	  
	 div2.className='left_slider_button_close';	  
   }	  

   set_cookie(div.id,div.style.left);		

}

</script>

<div id="left_slider_caption" <? if(isset($_COOKIE['left_slider_caption'])) { echo('style="left:'.$_COOKIE['left_slider_caption'].'"');} else { setcookie('left_slider_caption','0px'); } ?>>
    <span id="left_slider_span" onclick="left_slider_span_click(this);"><? echo($left_slider_caption) ?></span>
    <div id="left_slider_button" class="left_slider_button_close" onclick="left_slider_button_click(this);"></div>
    <div id="left_slider_div">
      <table id="left_slider_table">
      <tr><td>
      <ul id="left_slider_ul">
      <? if($left_slider_menu){ ?>
        <? foreach($left_slider_menu as $item){ ?>
           <li><a id="left_slider_a" href="<? echo($this->phpself.'pages/'.$item->url); ?>"><? echo($item->name); ?></a></li>
        <? } ?>
      <? } ?>
      </ul>
      </td></tr>
      </table>
    </div>
</div>

<? } ?>