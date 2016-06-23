$(document).ready(function() {
    $('.slogan_main').slogan_main();
    $('.slogan_social').slogan_social();
    $('#social_media').social_media();});
    $.fn.extend({
        social_media: function() {
            return this.each(function() {
                var counter=7;
                var $this = $(this);
                var html = '';
                if (facebook == 'hide'){
                    var counter = counter-1
                }else{
                    html += '<div class="social_box">';
                    html += '<a href="'+facebook+'"><img src="/img/facebook_in.png" alt="" class="in"></a>';
                    html += '<img src="/img/facebook_out.png" alt="" class="out">';
                    html += '</div>';
                }
                if (twitter == 'hide') {
                    var counter = counter-1}else{html    += '<div class="social_box">';html    += '<a href="'+twitter+'"><img src="/img/twitter_in.png" alt="" class="in"></a>';html    += '<img src="/img/twitter_out.png" alt="" class="out">';html    += '</div>';}if (rss == 'hide'){var counter = counter-1}else{html    += '<div class="social_box">';html    += '<a href="'+rss+'"><img src="/img/rss_in.png" alt="" class="in"></a>';html    += '<img src="/img/rss_out.png" alt="" class="out">';html    += '</div>';}if (deviantart == 'hide'){var counter = counter-1}else{html    += '<div class="social_box">';html    += '<a href="'+deviantart+'"><img src="countdown_files/img/social_media/deviantart_in.png" alt="" class="in"></a>';html    += '<img src="countdown_files/img/social_media/deviantart_out.png" alt="" class="out">';html    += '</div>';}if (myspace == 'hide'){var counter = counter-1}else{html    += '<div class="social_box">';html    += '<a href="'+myspace+'"><img src="countdown_files/img/social_media/myspace_in.png" alt="" class="in"></a>';html    += '<img src="countdown_files/img/social_media/myspace_out.png" alt="" class="out">';html    += '</div>';}if (lastfm == 'hide'){var counter = counter-1}else{html    += '<div class="social_box">';html    += '<a href="'+lastfm+'"><img src="countdown_files/img/social_media/lastfm_in.png" alt="" class="in"></a>';html    += '<img src="countdown_files/img/social_media/lastfm_out.png" alt="" class="out">';html    += '</div>';}if (flikr == 'hide'){var counter = counter-1}else{html    += '<div class="social_box">';html    += '<a href="'+rss+'"><img src="countdown_files/img/social_media/flikr_in.png" alt="" class="in"></a>';html    += '<img src="countdown_files/img/social_media/flikr_out.png" alt="" class="out">';html    += '</div>';}

                $this.html(html);
                if(counter==1){
                    $("#social_media").css('width', "40px"); }else if(counter==2){$("#social_media").css('width', "80px"); }else if(counter==3){$("#social_media").css('width', "120px"); }else if(counter==4){$("#social_media").css('width', "160px"); }else if(counter==5){$("#social_media").css('width', "200px"); }else if(counter==6){$("#social_media").css('width', "240px"); }else if(counter==7){$("#social_media").css('width', "280px"); }            });        }});
    $.fn.extend({
        slogan_main: function() {
            return this.each(function() {
                var $this = $(this);
                var html = '<div id="main_titel">' + main_titel + '<span>' + sub_titel + '</span></div>';
                $this.html(html);
            });
        }});
    $.fn.extend({slogan_social: function() {            return this.each(function() {                                var $this = $(this);                var html = '<div id="main_titel"><span>' + social_network_titel + '</span></div>';                $this.html(html);              });        }});$(document).ready(function(){$("img.in").hover(function() {$(this).stop().animate({"opacity": "0"}, "slow");},function() {$(this).stop().animate({"opacity": "1"}, "slow");});});month= --month;dateFuture = new Date(year,month,day,hour,min,sec);

function GetCount(){
    dateNow = new Date();
    amount = dateFuture.getTime() - dateNow.getTime()+5;
    delete dateNow;
    if(amount < 0){
        out="<div id='days'><span></span>0<div id='days_text'>Дней</div></div>"
+ "<div id='hours'><span></span>0<div id='hours_text'>Часов</div></div>"
+ "<div id='mins'><span></span>0<div id='mins_text'>Минут</div></div>"
+ "<div id='secs'><span></span>0<div id='secs_text'>Секунд</div></div>" ;


document.getElementById('countbox').innerHTML=out;               }                else{                days=0;hours=0;mins=0;secs=0;out="";                amount = Math.floor(amount/1000);                 days=Math.floor(amount/86400);                 amount=amount%86400;                hours=Math.floor(amount/3600);                 amount=amount%3600;                mins=Math.floor(amount/60);                 amount=amount%60;                                secs=Math.floor(amount);                 out="<div id='days'><span></span>" + days +"<div id='days_text'>Дней</div></div>" + "<div id='hours'><span></span>" + hours +"<div id='hours_text'>Часов</div></div>" + "<div id='mins'><span></span>" + mins +"<div id='mins_text'>Минут</div></div>" + "<div id='secs'><span></span>" + secs +"<div id='secs_text'>Секунд</div></div>" ;                document.getElementById('countbox').innerHTML=out;                setTimeout("GetCount()", 1000);        }}window.onload=function(){GetCount();}