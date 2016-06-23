<style>
*{margin:0;padding:0}
body{ position:relative}
html{height:100%;margin-bottom:1px;  }
body{color:#333;text-align:left;font:13px Tahoma, Helvetica, Geneva, sans-serif; position:relative;}
a {color:#005987;font:13px Tahoma, Helvetica, Geneva, sans-serif;outline:0}
a:hover{text-decoration:none}
p{line-height:1.5em;margin:0 10px 1em 20px;text-indent:0}
h1,h2,h3{color:#f3801f;font-weight:normal; border: none}
h1,h2{font-size:24px;line-height:1.8em;margin:0 0 0.5em 20px}
h2{margin-top:-120px}
h3{font-size:18px}
.wrap{width:960px; position: absolute; top:10px; left:50%; margin-bottom: 50px; margin-left:-480px; z-index:2010; opacity:1; }
.wrap img{border:none;vertical-align:inherit; }
.header,.main,.footer{clear:both;}
.header{background:  url(img/ie6/h.png) no-repeat 0 0; height:158px; z-index:20; position:relative}
.brows{width:100%;margin-bottom:20px;}
.brows td { padding:0; padding-bottom:130px; text-align:center}
.brows td a{font-size:17px}
tr.brows_name td {text-align:center;}
.return{position:absolute;top:-110px;left:20px; z-index:100}
.return a{color:#005987;font:26px Tahoma, Helvetica, Geneva, sans-serif;text-decoration: underline;}
.return a:hover{text-decoration:none}
.why{width:100%;margin-top:25px; }
.why tr td p {line-height:1.8}
.why p{padding:0;margin:0}
.main{background:url(img/ie6/bg_span_wol.png) repeat-y 0 top; position:relative; z-index: 20}
.footer{background:url(img/ie6/f.png) no-repeat 0 0;height:131px}
.td1,.td2,.td3{width:239px;height:119px;vertical-align:top;padding:15px 65px 0 25px;}
.td1{background:url(img/ie6/td1.jpg) top no-repeat}
.td2{background:url(img/ie6/td2.jpg) top no-repeat}
.td3{background:url(img/ie6/td3.jpg) top no-repeat}
.qtabs{float:right;color:#666;margin:57px 34px 0 0}
.qtabs ul{list-style:none}
.qtabs li{float:left;cursor:pointer;padding:0 5px}
.qtabs li span:hover{text-decoration:underline}
.qtabs li.open span{color:#f3801f}
.qtabs li.open p:hover{text-decoration:none}
.qtwrapper{height:1%}
.qtcontent{width:94%;margin:0 30px;padding:20px 0}
.qtcurrent{position:relative;overflow:hidden}
#russian { display: none;}
#english { display: block;}
.active { color:#F3801F; text-decoration: none;}
table { border: none}
ul li { background: none}
#opaco { position:absolute; top: expression(eval(document.body.scrollTop)+'px'); left:0; background:#000; opacity:0.8; height:100%; width: 100%; z-index: 2000; filter:progid:DXImageTransform.Microsoft.Alpha(opacity = 80); /* IE5+ */  }
iframe { position:absolute; top:0; left:20px; display: block; background:url(img/ie6/blank.gif) top no-repeat; height: 950px; width: 940px; z-index: 10}
</style>

	<script type="text/javascript">
function language(e) {

	if (e == 1)
	{
		document.getElementById('russian').style.display = 'none';
		document.getElementById('english').style.display = 'block';
		document.getElementById('link1').className='';
		document.getElementById('link2').className='active';

	}
	else 
	{
		document.getElementById('russian').style.display = 'block';
		document.getElementById('english').style.display = 'none';			
		document.getElementById('link1').className='active';
		document.getElementById('link2').className='';

	}
	changeBodyHeight();
}
window.onload = function ()
{
//	alert(document.getElementById('wrapdiv').clientHeight);
	changeBodyHeight();
}
function changeBodyHeight()
{
	document.getElementById('opaco').style.height = 20 + (document.body.clientHeight > document.getElementById('wrapdiv').clientHeight? document.body.clientHeight:document.getElementById('wrapdiv').clientHeight);
}
	</script>
	
<div id="opaco"></div>
<div class="wrap" id="wrapdiv">

	<div class="header">
		<div class="qtabs">
			<ul id="qtabs-ex2">
				<li><a id="link1" href="javascript:language(2);">По-русски</a></li>
				<li><a id="link2" class="active" href="javascript:language(1);">English</a></li>
			</ul>
		</div>
	</div>
	<div class="main" id="current-ex2">
		<div class="qtcontent" id="russian">
			<div class="return">
				<a href="index.html">Вернуться на сайт</a>			</div>
			<h1>Внимание! Вы используете устаревший браузер Internet Explorer 6</h1>
			<p>Данный сайт построен на передовых, современных технологиях, которые не поддерживается браузером Internet Explorer 6-й версии.</p>
			<p>Настоятельно Вам рекомендуем выбрать и установить любой из современных браузеров. Это бесплатно и займет всего несколько минут.</p><br />
			<table class="brows" cellspacing="0">
				<tr>
					<td><a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" onclick="return !window.open(this.href)"><img src="img/ie6/ie.jpg" alt="Internet Explorer" /></a></td>
					<td><a href="http://www.opera.com/download/" onclick="return !window.open(this.href)"><img src="img/ie6/op.jpg" alt="Opera Browser" /></a></td>
					<td><a href="http://www.mozilla.com/firefox/" onclick="return !window.open(this.href)"><img src="img/ie6/mf.jpg" alt="Mozilla Firefox" /></a></td>
					<td><a href="http://www.google.com/chrome" onclick="return !window.open(this.href)"><img src="img/ie6/gc.jpg" alt="Google Chrome" /></a></td>
					<td><a href="http://www.apple.com/safari/download/" onclick="return !window.open(this.href)"><img src="img/ie6/as.jpg" alt="Apple Safari" /></a></td>
				
			
					
				</tr>
				<tr class="brows_name">
					<td><a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" onclick="return !window.open(this.href)">Internet Explorer 8</a></td>
					<td><a href="http://www.opera.com/download/" onclick="return !window.open(this.href)">Opera Browser</a></td>
					<td><a href="http://www.mozilla.com/firefox/" onclick="return !window.open(this.href)">Mozilla Firefox</a></td>
					<td><a href="http://www.google.com/chrome" onclick="return !window.open(this.href)">Google Chrome</a></td>
					<td><a href="http://www.apple.com/safari/download/" onclick="return !window.open(this.href)">Apple Safari</a></td>


				</tr>
			</table>
			<h2>Почему лучше сменить мой браузер?</h2>
			<p>Internet Explorer 6 является устаревшим браузером. Он не может предоставить все возможности, которые могут предоставить современные браузеры, а скорость его работы в несколько раз ниже! Многие современные сайты не корректно отображаются в IE6</p>
			<p>Если по каким-либо причинам Вы не имеете доступа к возможности установки программ, то рекомендуем воспользоваться портативными версиями браузеров. 
			Они не требуют установки на компьютер и работают с любого диска или вашей флешки: <a href="http://portableapps.com/apps/internet/firefox_portable" onclick="return !window.open(this.href)">Mozilla Firefox</a> или <a href="http://portableapps.com/apps/internet/google_chrome_portable" onclick="return !window.open(this.href)">Google Chrome</a> или <a href="http://www.opera-usb.com/" onclick="return !window.open(this.href)">Opera</a>.</p>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="td1">
						<h3>Безопасность</h3>
						<p>Использование IE6 потенциально опасно, так как именно через него доступ в Ваш компьютер имеют вирусы и мошенники.</p></td>
					<td>&nbsp;</td>
					<td class="td2">
						<h3>Только факты</h3>
						<p>IE6 был выпущен в 2001 году!<br />
						Позже были созданы версии<br />
						7 и 8, вскоре ожидается следующая версия Internet Explorer.</p></td>
					<td>&nbsp;</td>
					<td class="td3">
						<h3>Microsoft</h3>
						<p>Microsoft, разработчик Internet<br /> Explorer 6, прекратила его поддержку<br />	и рекомендует устанавливать новые версии своего браузера.</p></td>
				</tr>
			</table>
		</div>
		<div class="qtcontent" id="english">
			<div class="return">
				<a href="/">Return to site</a>			</div>
			<h1>Caution! You are using the out-of-date Internet Explorer 6 browser</h1>
			<p>This site is built on the basis of advanced, modern technologies which are not supported by Internet Explorer browser of 6-th version.</p>
			<p>It is strongly recommended to choose and install any of modern browsers. It is free of charge and also will take only some minutes to be installed.</p>		<br />
			<table class="brows" cellspacing="0">
				<tr>
					<td><a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" onclick="return !window.open(this.href)"><img src="img/ie6/ie.jpg" alt="Internet Explorer" /></a></td>
					<td><a href="http://www.opera.com/download/" onclick="return !window.open(this.href)"><img src="img/ie6/op.jpg" alt="Opera Browser" /></a></td>
					<td><a href="http://www.mozilla.com/firefox/" onclick="return !window.open(this.href)"><img src="img/ie6/mf.jpg" alt="Mozilla Firefox" /></a></td>
					<td><a href="http://www.google.com/chrome" onclick="return !window.open(this.href)"><img src="img/ie6/gc.jpg" alt="Google Chrome" /></a></td>
					<td><a href="http://www.apple.com/safari/download/" onclick="return !window.open(this.href)"><img src="img/ie6/as.jpg" alt="Apple Safari" /></a></td>
				</tr>
				<tr class="brows_name">
					<td><a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" onclick="return !window.open(this.href)">Internet Explorer 8</a></td>
					<td><a href="http://www.opera.com/download/" onclick="return !window.open(this.href)">Opera Browser</a></td>
					<td><a href="http://www.mozilla.com/firefox/" onclick="return !window.open(this.href)">Mozilla Firefox</a></td>
					<td><a href="http://www.google.com/chrome" onclick="return !window.open(this.href)">Google Chrome</a></td>
					<td><a href="http://www.apple.com/safari/download/" onclick="return !window.open(this.href)">Apple Safari</a></td>
				</tr>
			</table>
			<h2>Why is it better to change my browser?</h2>
			<p>Internet Explorer 6 is the browser of old version! It can’t provide all possibilities which can be given by modern browsers, and speed of its work is several times lower! The majority of modern  sites is displayed incorrectly in IE6.</p>
			<p>If for any reason you have no permission to install programs, we recommend you to use the portable versions of browsers. They do not require installation on your computer and work with any drive or your flash: <a href="http://portableapps.com/apps/internet/firefox_portable" onclick="return !window.open(this.href)">Mozilla Firefox</a> or <a href="http://portableapps.com/apps/internet/google_chrome_portable" onclick="return !window.open(this.href)">Google Chrome</a> or <a href="http://www.opera-usb.com/" onclick="return !window.open(this.href)">Opera</a></p>
			<table class="why" cellspacing="0">
				<tr>
					<td class="td1">
						<h3>Safety</h3>
						<p>Using of IE6 is potentially dangerous since through it viruses and hackers can access your computer.</p>					</td>
					<td>&nbsp;</td>
					<td class="td2">
						<h3>Only facts</h3><p>IE6 was released in year 2001.<br />	IE7 and IE8 Versions were<br />	released later. A new version is expected to be released soon. </td>
					<td>&nbsp;</td>
					<td class="td3">
						<h3>Microsoft</h3>
						<p>	Being the developer of Internet Explorer 6, Microsoft has stopped supporting IE6 and recommends to install new version of their browser.  </p></td>
				</tr>
			</table>
		</div>

	</div>
</div>