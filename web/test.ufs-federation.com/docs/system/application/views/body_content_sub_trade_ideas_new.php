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
    
<? if ($data) { ?>
<? /*
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.widgets.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/css/theme.blue.css" type="text/css" media="all">
 
<style>
	.sector td {
		background: #cdcdcd;
		font-weight: bold;
	}
</style>
<script>
  $(document).ready(function() {
  	  
  	  $('#close-disclamer').click(function(){
			$('.trade-ideas-disclamer').toggle('fast');
			return false;
		});

    $('.addtocartlink').click(function(){
      var linkid = 'x' + $(this).attr("id");
      if ($(this).is(':checked')){
        $(this).next('span').text('<? echo(dictionary("Удалить")) ?>');
        $('#'+linkid).show();
      } else {
        $(this).next('span').text('<? echo(dictionary("Оформить заявку")) ?>');
        $('#'+linkid).hide();
      };
    });
    
    $('.cartorder').click(function(){
      $('#form_trade_ideas').submit();
      return false;
    });

    jQuery.tablesorter.addParser({
      id: "fancyNumber",
      is: function(s) {
        return /^[0-9]?[0-9,\.]*$/.test(s);
      },
      format: function(s) {
          return jQuery.tablesorter.formatFloat( s.replace(/,/g,'') );
      },
      type: "numeric"
    });

    var names = '<?
      $names = "";
      if (isset($groups) && is_array($groups)) {
        $n = array();
        foreach($groups as $g) {
          $n[] = "#table".$g->counter;
        }
        $names = implode(",",$n);
      }
      echo($names); 
    ?>';
    
    $('#table1').tablesorter({
		debug:true,
		theme : 'blue',
		cssInfoBlock : "tablesorter-no-sort",
		widgets: [ 'zebra', 'stickyHeaders' ],
		widgetZebra: { css: ['d0', 'd1']},
		stickyHeaders : 'tablesorter-stickyHeader',
		headers: {
        0: {sorter: "text"},
        1: {sorter: "text"},
        2: {sorter: "text"},
        3: {sorter: "percent"},
        4: {sorter: "percent"},
        5: {sorter: "text"}
      }
    });
  });

</script>
*/ ?>


<style>

  table.tablesorter-blue tbody tr td.bold  {
    font-weight: bold;
    color: #000;
  }
  table.tablesorter-blue tbody tr td.close  {
    color: #ef181e;
  }
  table.tablesorter-blue tbody tr td.acenter  {
    text-align: center;
  }
  table.tablesorter-blue tbody tr td.ajustify  {
    text-align: justify;
  }
  table.tablesorter-blue tbody tr td.aright  {
    text-align: right;
  }
  div.chkbx {
    display: block;
    padding-top: 4px;
    padding-left: 6px;
    text-align: bottom;
  }
  div.chkbx a {
    display: none;
    vertical-align: middle;
    text-decoration: underline;
  }
  div.chkbx input {
    margin-right: 4px;
  }


.remove_item {
		display: inline-block;
		background: url(/img/close-sprite.gif) no-repeat 50% 0%;
		width: 20px;
		height: 20px;
		margin: auto;
  }
  .remove_item:hover, .remove_item:active {
  	background-position: 50% -48px;
  }
  .trade-ideas-disclamer {
  	background: -moz-linear-gradient(top, #fff, #e0e0e0); /* Firefox 3.6+ */
	/* Chrome 1-9, Safari 4-5 */
	background: -webkit-gradient(linear, left top, left bottom, 
	            color-stop(0%,#fff), color-stop(100%,#e0e0e0));
	/* Chrome 10+, Safari 5.1+ */
	background: -webkit-linear-gradient(top, #fff, #e0e0e0);
	background: -o-linear-gradient(top, #fff, #e0e0e0); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #fff, #e0e0e0); /* IE10 */
	background: linear-gradient(top, #fff, #e0e0e0); /* CSS3 */
	text-shadow: #fff 0 1px 0;
  }
</style>
 <a id="gotop" class="scrollTop" href="#" onclick="top.goTop(); return false;"></a>  
<?
$lang_prefix = 'en';
if($this->site_lang=='ru' || $this->site_lang=='en' || $this->site_lang=='de') {
	$lang_prefix = $this->site_lang;
}
?>
<!-- div class="section-recommendations" -->
<? /*
<div class="trade-ideas-disclamer" style="border-radius: 4px; border: 1px solid #f7f7f7; box-shadow: #999 0 0 6px; padding: 10px; margin-bottom: 2em;">
	<a href="#" class="remove_item" id="close-disclamer" style="position: relative; float: right"></a>
	<table style="border: 0; margin: 0; width: 97%;">
		<tr>
			<td style="width: 35%; height: 1px; padding: 0;"></td>
			<td style="width: 35%; height: 1px; padding: 0;"></td>
			<td style="width: 30%; height: 1px; padding: 0;"></td>
		</tr>
		<tr>
			<td colspan="2"><h3><? echo(dictionary('Уважаемый инвестор')); ?>!</h3><? echo(dictionary('Если вы уже являетесь клиентом брокера UFS Finance, то выбрав одну из торговых идей, вы можете отправить заявку брокеру')); ?>.</td>
			<td rowspan="2"><? if($this->site_lang=='ru') { ?><h3>&nbsp;</h3><p style="margin: 0; padding: 0; border-left: 2px solid #777; text-align: left; padding-left: 12px;">Если вы&nbsp;не&nbsp;являетесь клиентом брокера UFS&nbsp;Finance, вы&nbsp;можете <a href="/application-form.html"><nobr>открыть счет онлайн</nobr></a>.<? } ?></td>
		</tr>
		<tr>
			<td><div class="pic" style="padding-left: 22px; background: transparent url(/img/trade-ideas-disclamer2_<?=$lang_prefix?>.gif) no-repeat 22px 0; width: 166px; height: 61px;"><span style="display: block; position: relative; margin-left: -22px; font-size: 22px; font-weight: bold; font-style: italic">1.</span></div></td>
			<td><div class="pic" style="padding-left: 22px; background: transparent url(/img/trade-ideas-disclamer2_<?=$lang_prefix?>.gif) no-repeat 22px -61px; width: 166px; height: 61px;"><span style="display: block; position: relative; margin-left: -22px; font-size: 22px; font-weight: bold; font-style: italic">2.</span></div></td>
		</tr>
	</table>
</div>
 */ ?>

<!-- TODO: удалить после внесения в JS и CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/js/jquery.stickytableheaders.js"></script>

<script>

$(document).ready(function() {
  $('.trade-ideas').stickyTableHeaders();
  
  $('.expand').click(function(){
    
    var hiddenText = $(this).prev('.hidden-text');
    var shortText = $(this).parent().children('.short-text');
    var pusherCell = $(this).closest('tr').nextAll('.pusher').first().children('td');
    if (hiddenText.is(':visible')) {
      shortText.css('display','inline');
      hiddenText.css('display','none');
      pusherCell.animate({height:0},300);
      $(this).text('<? echo(dictionary('Показать полностью')); ?>');
    } else {
      var commentCollapsedHeight = $(this).closest('.comment').outerHeight();
      shortText.css('display','none');
      hiddenText.css('display','inline');
      var commentFullHeight = $(this).closest('.comment').outerHeight();
      pusherCell.css('height',commentFullHeight-commentCollapsedHeight+1);
      $(this).text('<? echo(dictionary('Скрыть комментарий')); ?>');
    }
    return false;
  });
  $('.tab-nav li').click(function(){
    var tabs = $(this).siblings();
    var panes = $(this).closest('.tabs-container').find('.tab-pane');
    var tabId = $(this).children('a').attr('href').substring(1);
    
    tabs.removeClass('active');
    $(this).addClass('active');
    
    //alert(tabId);
    
    panes.removeClass('active').fadeOut(30);
    $('#'+tabId).addClass('active').fadeIn(30);
    
    return false;
  });
});





</script>

<style>

table.trade-ideas,
table.trade-ideas thead.tableFloatingHeaderSticky {
  border: 1px solid #fff !important;
  border-collapse: separate;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -webkit-border-radius: 4px;
 -moz-border-radius: 4px;
  border-radius: 4px;
}
table.trade-ideas,
table.trade-ideas th,
table.trade-ideas td,
.buttonGrad {
  /* font-family: open sans; */
}
table.trade-ideas,
table.trade-ideas th,
table.trade-ideas td {
  background-color: #fffefa;
  border: 0;
  font-size: 11px !important; /* 95%; */
  font-weight: normal;
}
table.trade-ideas th,
table.trade-ideas td {
  text-align: left;
  vertical-align: middle;
  border-left: 0;
  border-right: 0;
  padding: 0.5em 0.75em;
  /* padding-left: 0.75em;
  padding-right: 0.75em; */
}
table.trade-ideas td.sub-header {
  border-left: 2px solid #fff;
  /* font-weight: bold; */
  /* font-style: italic; */
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  /* color: rgba(0,0,0,1); */
}
table.trade-ideas th {
  background-color: #e0e6e7;
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  text-align: center;
  font-weight: bold;
}
table.trade-ideas th.header {
  background-color: #004577; /* 26C196; #009abf; #004577; */
  /* background: #009abf;
  background: -moz-linear-gradient(top,  #009abf 0%, #0085a3 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#009abf), color-stop(100%,#0085a3));
  background: -webkit-linear-gradient(top,  #009abf 0%,#0085a3 100%);
  background: -o-linear-gradient(top,  #009abf 0%,#0085a3 100%);
  background: -ms-linear-gradient(top,  #009abf 0%,#0085a3 100%);
  background: linear-gradient(to bottom,  #009abf 0%,#0085a3 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#009abf', endColorstr='#0085a3',GradientType=0 ); */
  color: #fff;
  text-shadow: none; /* 0px 0px 6px #006680; */
  text-align: left;
  font-weight: normal;
  white-space: nowrap;
}
table.trade-ideas th.header .date,
table.trade-ideas th.header .target {
  float: right;
  overflow: hidden;
  margin-left: 3em;
}
span.date i,
span.target i {
  width: 16px;
  height: 16px;
  display: inline-block;
  background: transparent url(/img/sprite-ideas.png) no-repeat 0 0;
  margin-right: 0.5em;
  vertical-align: text-bottom; /* top; */
  margin-top: 0.05em;
}
span.date i {
  background-position: -16px 0;
}
table.trade-ideas th.header .vs {
  font-weight: bold;
  /* font-style: italic; */
  
  background-color: #fff;
  letter-spacing: -0.05em;
  display: inline-block;
  width: 14px;
  height: 14px;

 -webkit-border-radius: 10px;
 -moz-border-radius: 10px;
  border-radius: 10px;
  
  padding: 2px;
  margin: 0 0.5em;
  color: #004577; /* 009abf; */
  font-size: 10px;
  line-height: 14px;
  text-shadow: none;
  font-weight: bold;
  text-align: center;
}
table.trade-ideas thead tr:first-child th:first-child {
 -webkit-border-radius: 4px 0 0 0;
 -moz-border-radius: 4px 0 0 0;
  border-radius: 4px 0 0 0;
}
table.trade-ideas.ti-corner,
table.trade-ideas.ti-corner thead.tableFloatingHeaderSticky,
table.trade-ideas.ti-corner thead tr:first-child th:first-child {
  -webkit-border-top-left-radius: 0;
  -moz-border-radius-topleft: 0;
  border-top-left-radius: 0;
}
table.trade-ideas thead tr:first-child th:last-child {
 -webkit-border-radius: 0 4px 0 0;
 -moz-border-radius: 0 4px 0 0 ;
  border-radius: 0 4px 0 0;
}
table.trade-ideas tbody tr.odd:last-child td:first-child {
 -webkit-border-radius: 0 0 0 4px;
 -moz-border-radius: 0 0 0 4px;
  border-radius: 0 0 0 4px;
  /* background-color: red !important; */
}
table.trade-ideas tbody td.comment:last-child {
 -webkit-border-radius: 0 0 4px 0;
 -moz-border-radius: 0 0 4px 0;
  border-radius: 0 0 4px 0;
}
table.trade-ideas thead.tableFloatingHeaderSticky,
table.trade-ideas thead.tableFloatingHeaderSticky tr th {
 -webkit-border-radius: 0 0 0 0 !important;
 -moz-border-radius: 0 0 0 0 !important;
  border-radius: 0 0 0 0 !important;
  background-color: rgba(224, 230, 231,0.8) !important;
}
table.trade-ideas thead.tableFloatingHeaderSticky {
  border: 1px solid #fff !important;
}
table.trade-ideas thead.tableFloatingHeaderSticky {
 -moz-box-sizing: content-box;
 -webkit-box-sizing: content-box;
  box-sizing: content-box;
  
  border-top-width: 0 !important;
  border-bottom-width: 0 !important;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.21);
 -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.21);
 -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.21);
}
table.trade-ideas td.sell,
table.trade-ideas td.buy,
table.trade-ideas td.hold {
  color: #fff;
  text-transform: uppercase;
  text-align: center;
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  font-weight: bold;
}
table.trade-ideas td.buy {
  background-color: #ebfae8; /* #3daf2c; */
  color: #318c23;
  /* border-bottom: 1px solid #c4d7c1;
  border-top: 1px solid #f3faf2; */
}
table.trade-ideas td.sell {
  background-color: #fff3f2; /* #e1261c; */
  color: #e1261c;
  /* border-bottom: 1px solid #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas td.hold {
  color: #44474d;
  /* border-bottom: 1px solid #c4d7c1;
  border-top: 1px solid #f3faf2; */
}
table.trade-ideas tr.buy td a,
table.trade-ideas tr.sell td a {
  font-weight: bold;
}
table.trade-ideas tr.buy.odd td {
  background-color: #dbf0d8; /* #3daf2c; */
}
table.trade-ideas tr.sell.odd td {
  background-color: #fae5e3; /* #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas tr.buy.even td {
  background-color: #ebfae8; /* #3daf2c; */
}
table.trade-ideas tr.sell.even td {
  background-color: #fff3f2; /* #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas tr.even td {
  background-color: #f2f7f7; /* #3daf2c; */
}
table.trade-ideas tr.odd td {
  background-color: #e3e8e8; /* #e1261c; */
  /* border-bottom: 2px solid #e1261c; */
}
table.trade-ideas tr.bold td {
  font-weight: bold;
}
table.trade-ideas td.comment {
  text-align: justify;
  /* font-style: italic; */
  vertical-align: top;
  color: #000; /* rgba(68,71,77,0.7); */
  padding: 0 !important;
}
table.trade-ideas td.comment /*,
table.trade-ideas tr.pusher td */ {
  background: #fffaf0 !important;
}
table.trade-ideas td.comment div {
  padding: 0 0.75em; /* 0 18px 1.5em; */
}
table.trade-ideas tr.pusher td {
  padding: 0;
}
.buttonGrad {
  font-size: 100%;
  min-width: 125px;
  background-color: transparent;
  background-repeat: repeat-x;
  background-position: center left;
  cursor: pointer !important;
  white-space: nowrap;
  width: 100%;
  outline: none;
  display: inline-block;
  margin: 1.3em auto;
  color: black;
  text-shadow: 0px 1px rgba(255,255,255,0.8);
 -webkit-border-radius: 4px;
 -moz-border-radius: 4px;
  border-radius: 4px;
  border: 1px solid silver;
  box-shadow: 1px 2px 2px 0px rgba(0,0,0,0.08);
  -webkit-box-shadow: 1px 2px 2px 0px rgba(0,0,0,0.08);
  min-height: 25px;
  height: auto;
  padding: 0.4em 8px;

  background: -moz-linear-gradient(top, #FDFDFD, #F0F0F0); /* Firefox 3.6+ */
  /* Chrome 1-9, Safari 4-5 */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FDFDFD), color-stop(100%,#F0F0F0));
  /* Chrome 10+, Safari 5.1+ */
  background: -webkit-linear-gradient(top, #FDFDFD, #F0F0F0);
  background: -o-linear-gradient(top, #FDFDFD, #F0F0F0); /* Opera 11.10+ */
  background: -ms-linear-gradient(top, #FDFDFD, #F0F0F0); /* IE10 */
  background: linear-gradient(top, #FDFDFD, #F0F0F0); /* CSS3 */
  background: -webkit-gradient(linear, left bottom, left top, from(#F0F0F0), to(#FDFDFD), color-stop(14%,#E2E2E2)) !important;
}
.buttonGradPush,
.buttonGrad:active {
  background-color: #b5c6d4 !important;
  box-shadow: inset 1px 2px 5px 0.5px #79858e;
 -webkit-box-shadow: inset 1px 2px 5px 0.5px #79858e;
 -moz-box-shadow: inset 1px 2px 5px 0.5px #79858e;
  border-color: #909090;
  /* color: white; */
  text-shadow: 0px 1px 3px gray;
}
.buttonGradGold,
.buttonGrad:hover {
  background: -moz-linear-gradient(top, #ebcf3e, #dfb829); /* Firefox 3.6+ */
  /* Chrome 1-9, Safari 4-5 */
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ebcf3e), color-stop(100%,#d5a81a));
  /* Chrome 10+, Safari 5.1+ */
  background: -webkit-linear-gradient(top, #ebcf3e, #dfb829);
  background: -o-linear-gradient(top, #ebcf3e, #dfb829); /* Opera 11.10+ */
  background: -ms-linear-gradient(top, #ebcf3e, #dfb829); /* IE10 */
  background: linear-gradient(top, #ebcf3e, #dfb829); /* CSS3 */
  background: -webkit-gradient(linear, left bottom, left top, from(#F0F0F0), to(#FDFDFD), color-stop(14%,#E2E2E2)) !important;

  background: -webkit-gradient(linear, left bottom, left top, from(#dfb829), to(#ebcf3e), color-stop(14%,#d5a81a)) !important;
  border-color: #bba351;
  text-shadow: 0px 1px rgba(248,233,0,0.8);
  padding-left: 1em;
  padding-right: 1em;
}
a.expand {
  white-space: nowrap;
  display: block;
  padding: 6px 0;
  font-weight: normal !important;
  
  /* display: none; */
}
/* .hidden-text {
  display: inline !important;
}
table.trade-ideas td.comment form {
  display: block;
  width: 180px;
  float: right;
  padding-left: 30px;
} */









/* tabs */
.tabs-container {
  width: 100%;
}
.tab-nav {
  list-style: none;
  border-bottom: 1px solid #fff; /* #004577; */
  margin-top: 0;
  margin-bottom: -12px;
  display: block;
}
.tab-nav:before, .tab-nav:after {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  display: table;
  content: " ";
}
.tab-nav:after {
  clear: both;
}

.tab-nav>li {
  float: left;
  list-style: none;
  margin-bottom: -1px;
  position: relative;
  display: block;
  background: none;
  padding: 10px 10px 0;
  margin-left: -10px;
  overflow: hidden;
  border-bottom: 0;
}
.tab-nav>li.active>a, .tab-nav>li.active>a:hover, .tab-nav>li.active>a:focus {
  color: #44474d;
  cursor: default;
  background-color: #e0e6e7;
  border: 1px solid #fff;
  border-bottom-color: transparent;
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 1);
  text-align: center;
  font-weight: bold;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
 -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.21);
  
}
.tab-nav>li>a {
  margin-right: 2px;
  line-height: 1.428571429;
  
  border-radius: 4px 4px 0 0;
  position: relative;
  display: block;
  padding: 10px 15px;
  color: #44474d;
  /* border: 1px solid #fff; */
  border: 1px solid transparent;
  border-bottom-color: transparent;
  font-weight: bold;
}
.tab-nav>li>a:hover {
  border-bottom-color: transparent;
}
.tab-nav>li>a:hover, .tab-nav>li>a:focus {
  text-decoration: none;
  background-color: rgba(224, 230, 231, 0.25);
  /* color: #2a6496; */
}
.tab-pane {
  display: none;
}
.tabs-container .active {
  display: block;
}
.tab-content .tab-pane:fisrt-child  {}

/* temp */
table.trade-ideas th.header .target2 {
  float: none;
  margin-left: 0;
}
table.trade-ideas2 .buttonGrad {
  width: 225px;
  margin: 6px auto;
}
table.trade-ideas2 td.sub-header {
  border-left: 0;
}
table.trade-ideas2 td.comment div {
  padding-top: 16px; 
} 


</style>

<div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
<?  $counter = 0;
    if ($data) { ?>

<div class="tabs-container">
  <ul class="tab-nav">
    <? $first = true; foreach ($data as $idea_item) {
      $tab = '';
      $group_tab = $trade_idea_group_name[$idea_item->language][$idea_item->group_name];
      if ($group_tab == 'евро') $tab='euro';
      if ($group_tab == 'рубл') $tab='local';
    ?>
    <li<? echo($first?' class="active"':''); $first = false; ?>>
      <a href="#<? echo($tab); ?>"><? echo(dictionary($idea_item->group_name)); ?></a>
    </li>
    <? } ?>
    <!-- li><a href="#euro"><? echo(dictionary('Еврооблигации')); ?></a></li -->
    <!--li class="active"><a href="#local"><? echo(dictionary('Локальный рынок')); ?></a></li -->
  </ul>
  <div class="tab-content">
    <? foreach ($data as $idea_item) {
           $idea_data = new trade_idea_item();
           $idea_data->where(array('language' => $idea_item->language,
                                  'group_name' => $idea_item->group_name, 
                                  'finished is null' => null))->order_by(
                                      'language, group_name, priority'
                                  )->get(); 
    ?>
    <div class="tab-pane<? if ($counter == 0) echo(' active'); ?>" id="<? 
        $tab = '';
        $group_tab = $trade_idea_group_name[$idea_item->language][$idea_item->group_name];
        if ($group_tab == 'евро') $tab='euro';
        if ($group_tab == 'рубл') $tab='local';
        echo($tab);
    ?>">
        <table style="padding: 0px;" class="trade-ideas trade-ideas2 <? if($counter == 0) echo('ti-corner'); ?>">
            <colgroup>
              <col style="width: 24%;">
              <col style="width: 46%;">
              <col style="width: 12%;">
              <col style="width: 9%;">
              <col style="width: 9%;">
            </colgroup>
            <thead style="position: static; margin-top: 0px; left: 682px; z-index: 1; top: 0px; width: 808px;" class="tableFloatingHeaderOriginal">
              <tr>
                <th rowspan="2" style="min-width: 0px; max-width: none;"><? echo(dictionary('Инструмент')); ?></th>
                <th rowspan="2" style="min-width: 0px; max-width: none;"><? echo(dictionary('Комментарий')); ?></th>
                <th rowspan="2" style="min-width: 0px; max-width: none;">&nbsp;</th>
                <th colspan="2" style="min-width: 0px; max-width: none;"><? echo(dictionary('Уровень')); ?></th>
              </tr>
              <tr>
                <th style="min-width: 0px; max-width: none;"><? echo(dictionary('Открытия')); ?></th>
                <th style="min-width: 0px; max-width: none;"><? echo(dictionary('Текущий')); ?></th>
              </tr>
            </thead>
                  <tbody>

              <? foreach ($idea_data as $item) {
                      $item_level_values = $item->level_value->get();
                      $item_level_value = null;
                      foreach ($item_level_values as $item_level_value){}
                  ?>
                <tr>
                  <th class="header acenter"><? 
                        $pos = strpos($item->caption, "VS");
                        if($pos) {
                          $caption = explode ("VS", $item->caption);
                          echo($caption[0]); ?><span class="vs">VS</span><? echo($caption[1]);
                        }
                        else
                          echo($item->caption); 
                    ?></th>
                  <th class="header acenter">
                      <span class="target target2" title="<? echo($item_level_value->data_Type->get()->data_type_name); ?>"><i></i>
                          <? echo($item_level_value->data_Type->get()->data_type_name); ?>:&nbsp;<? echo($item_level_value->level_value); ?>
                      </span>
                  </th>
                  <th class="header" style="text-align: right" colspan="3">
                      <span class="date" title="<? echo(dictionary('Дата рекомендации')); ?>"><i></i><? 
                          echo(dictionary('Дата рекомендации')); ?>: <? 
                          $recommend_date = strtotime($item->recommend_date);
                          $recommend_date = date('d.m.Y', $recommend_date);/*date('j', $recommend_date).' '.global_model::months_ru_short($recommend_date).', '.date('Y',$recommend_date);*/
                          echo($recommend_date); ?>
                      </span>
                  </th>
                </tr>
                <? switch($item->type_id ) {
                      case 1:
                        $is_first = true;
                        foreach ($item->tool->get() as $tool) {
                          $j = 1;
                          if($tool->operation == 'ПОКУПАТЬ' || $tool->operation == 'BUY') $idea_oper='buy';
                          if($tool->operation == 'ПРОДАВАТЬ' || $tool->operation == 'SELL') $idea_oper='sell';
                          if($tool->operation == 'ДЕРЖАТЬ' || $tool->operation == 'HOLD') $idea_oper='hold';
                          $tool = $item->tool->get(); ?>
                <tr class="<?  echo($idea_oper.' odd');  ?>" >
                  <td class="<? echo($idea_oper); ?>"><? echo($tool->operation); ?></td>
                      <?  if ($is_first) {
                            $is_first = false;  ?>
                  <td class="comment" rowspan="5">
                    <div>
                      <span class="short-text" style="display: <? if(strlen($item->comment)<=400) echo('none'); else echo 'inline'; ?>"><? echo(preg_replace('/\s+?(\S+)?$/', '', substr($item->comment, 0, 400))); ?></span> 
                      <span class="hidden-text" style="display: none"><? echo($item->comment);  ?></span>
                      <span class="hidden-text" style="display: <? if(strlen($item->comment)>400) echo('none'); else echo 'inline'; ?>"><? echo($item->comment);  ?></span>
                      <? if(strlen($item->comment)>400) { ?>
                      <a class="expand" href="#"><? echo(dictionary('Показать полностью')); ?></a>
                      <? } ?>
                    </div>
                  </td>
                  <td colspan="3">
                      <form method="post" action="<? echo($this->base_url.$this->full_page_url); ?>">
                        <input name="trade_idea_id" value="<? echo($item->id); ?>" type="hidden">
                        <input class="placeOrder buttonGrad" value="<? echo(dictionary('Оформить заявку')); ?>" type="submit">
                        <input type="hidden" name="order"/>
                      </form>
                  </td>
                      <?  } ?>
                </tr>      
                          <?  $j = 1;
                              $level_value_types = $tool->level_value->select('data_type_id')->distinct()->get();
                              foreach ($level_value_types as $level_value_type ) {
                                $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                                if($j % 2 <> 0) $idea_row = 'even';
                                else $idea_row = 'odd';
                                $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                          ?>
                <tr class="<? echo($idea_oper.' '.$idea_row);  ?>">
                  <td class="acenter"><? switch($j) {
                                  case 1: ?><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($tool->name))); ?>"><? echo($tool->name); ?></a><?      break; 
                                  case 2: ?><? echo($tool->isin); ?><?      break; 
                                  case 3: ?><? echo($tool->rating); ?><?      break;
                          } ?></td>
                  <td class="sub-header"><? echo($level_values->data_type->get()->data_type_name); ?></td>
                            <?  foreach ($level_values as $level_value ) {  ?>
                  <td class="acenter"><? echo($level_value->level_value); ?></td>
                            <?  } ?>
                </tr>
                            <?  $j++; 
                              }
                        } ?>
                <tr class="<? echo('pusher '.$idea_oper.' odd'); ?>">
                    <td></td>
                    <td colspan="3"></td>
                </tr>
                     <? 
                        break;?>
                  <?  case 2:
                        $is_first = true;
                        foreach ($item->tool->get() as $tool) {  
                          $j = 1;
                          if($tool->operation == 'ПОКУПКА' || $tool->operation == 'BUY') $idea_oper='buy';
                          if($tool->operation == 'ПРОДАЖА' || $tool->operation == 'SELL') $idea_oper='sell';

                ?>
                <tr class="<?  echo($idea_oper.' odd');  ?>" >
                  <td class="<?  echo($idea_oper); ?>"><? echo($tool->operation); ?></td>
                      <?  if ($is_first) { 
                            $is_first = false;  ?>
                  <td class="comment" rowspan="10">
                    <div>
                      <span class="short-text" style="display: <? if(strlen($item->comment)<=1400) echo('none'); else echo 'inline'; ?>"><? echo(preg_replace('/\s+?(\S+)?$/', '', substr($item->comment, 0, 1400))); ?></span> 
                      <span class="hidden-text" style="display: <? if(strlen($item->comment)>1400) echo('none'); else echo 'inline'; ?>"><? echo($item->comment);  ?></span>
                      <? if(strlen($item->comment)>1400) { ?>
                      <a class="expand" href="#"><? echo(dictionary('Показать полностью')); ?></a>
                      <? } ?>
                    </div>
                  </td>
                  <td colspan="3">
                      <form method="post" action="<? echo($this->base_url.$this->full_page_url); ?>">
                          <input name="trade_idea_id" value=<? echo($item->id); ?>" type="hidden">
                          <input class="placeOrder buttonGrad" value="<? echo(dictionary('Оформить заявку')); ?>" type="submit">
                          <input type="hidden" name="order"/>
                      </form>
                  </td>
                      <?  } else { ?>
                  <td colspan="3"></td>
                      <? } ?>
                </tr>
                          <?  $level_value_types = $tool->level_value->select('data_type_id')->distinct()->get();
                              foreach ($level_value_types as $level_value_type ) {
                                if($j % 2 <> 0) $idea_row = 'even';
                                else $idea_row = 'odd';
                                $level_values = $tool->level_value->get_where(array('data_type_id' => $level_value_type->data_type_id));
                          ?>
                <tr class="<?  echo($idea_oper.' '.$idea_row);  ?>">
                  <td class="header acenter"><?  switch($j) {
                                  case 1: ?><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($tool->name))); ?>"><? echo($tool->name); ?></a><?      break; 
                                  case 2: ?><? echo($tool->isin); ?><?      break; 
                                  case 3: ?><? echo($tool->rating); ?><?      break;
                                } ?>
                  </td>
                  <td class="sub-header"><? echo($level_values->data_type->get()->data_type_name); ?></td>
                            <?  foreach ($level_values as $level_value ) {  ?>
                  <td class="acenter"><? echo($level_value->level_value); ?></td>
                            <?  } ?>
                </tr>
                            <?  $j++;
                              }
                        } ?>
                <tr class="odd bold">
                  <td></td>
                  <?  reset($item_level_values); ?>
                  <td class="sub-header"><? echo($item_level_values->data_type->get()->data_type_name); ?></td>
                            <?  foreach ($item_level_values as $item_level_value ) {  
                                  if ($item_level_value->level_type_id <> 3) {
                              ?>
                  <td class="acenter bold"><? echo($item_level_value->level_value); ?></td>        
                                  <?  }
                                } ?>
                </tr>  
                <tr class="pusher even"><td></td><td colspan="3"></td></tr>
                    <?  break;
                   }
                ?>
                            <? 
                            
            } ?>

            </tbody>
        </table>
        <?=($tab=='local' ? '<p class="table-notice"><span class="info-sign">i</span>&nbsp;<i>Данные предоставляются Московской Биржей</i></p><br style="clear:both"/>' : '')?>
    </div>
         <? $counter++; 
            } ?>
  </div>
</div>
      <? } ?>

<? if($this->site_lang=='ru') { ?>
<span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span>

<? } else { ?>
<span><i><? echo(dictionary('The present assessments are based on the data from public sources, assumptions and forecasts of UFS IC and do not include insider or other information derived illegally. UFS IC and its employees are not responsible for the decisions based on the provided information.')); ?></i></span>
<? } ?>

<? } ?>