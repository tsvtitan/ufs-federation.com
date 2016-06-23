<? if (($this->site_lang=='ru') || ($this->site_lang=='en')) { ?>

<script type="text/javascript" language="javascript" src="/js/json2.js"></script>

<style>

  
/* clearfix */
  .clearfix:after {
  	clear: both;
  	content:' ';
  	display: block;
  	font-size: 0;
  	line-height: 0;
  	visibility: hidden;
  	width: 0;
  	height: 0;
  }
  .clearfix {
  	display: inline-block;
  }
  * html .clearfix {
  	height: 1%;
  }
  .clearfix {
  	display: block;
  }


/* IM styles */


  #chat {
    right: 40px;
    width: 260px;
    /* min-height: 324px; */
    position: fixed;
    bottom: 0;
    /* background: #c8d3db url('/img/im/chat-bg-sprite.gif') repeat-y 0 0; */
    font-size: 14px;
    z-index: 999;
  }
  #chat .tab-header {
    background: #09395d url('/img/im/chat-bg-sprite.gif') repeat-y 100% 0;
    padding: 0.6em 14px;
  }
  #chat, #chat .tab-header, .person {           /* new */
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
  }
  #chat .person {                               /* new */
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    display: block;
    width: 100%;
    background: #09395d url('/img/im/chat-bg-sprite.gif') repeat-y 100% 0;
    padding: 6px 0 6px 6px;
    margin: 0 auto 10px;
    overflow: hidden;
    text-decoration: none !important;
  }
  #chat .person:hover {                         /* new */
    background: #ff9016 url('/img/bg-resume.png') repeat-y 50% 0;
  }
  #chat .person:hover p span.status, #chat .person:hover p, #chat .person:hover span {
  /* .person:hover p, .person:hover p span, .person:hover p .status { */
    color: #555;
  }
  #chat .person:hover p {                       /* new */
    color: #363636;
  }
  #chat p.center {                              /* new */
    text-align: center;
  }
  
  
  #chat #tab-minimized {
    margin: 0;
    display: block;
    text-decoration: none;
    color: #fff;
    /* margin-bottom: 4px; */
  }
  #chat i.icon {
    display: inline-block;
    width: 24px;
    height: 18px;
    margin-right: 0.6em;
    vertical-align: middle;
    background: transparent url('/img/im/chat-sprite.png') no-repeat 0 0;
  }
  #chat .ava {
    width: 60px;
    height: 60px;
    -webkit-border-radius: 32px;
    -moz-border-radius: 32px;
    border-radius: 32px;
    /* border: 2px solid #fff; */
    float: left;
    margin-right: 14px;
    background: transparent url('/img/im/consultant-icon2.gif') no-repeat 0 0;
  }
  #chat .consultant p {
    display: block;
    margin: 0;
    padding: 0;
    color: #fff;
    margin-left: 74px;
  }
  #chat .consultant p span {
    color: #98a6b5;
    font-size: 11px;
    display: block;
  }
  #chat .consultant p span.status {
    margin-top: 1em;
  }
  #chat .consultant p span.online strong {
    color: #0da96c;
  }
  #chat .consultant p span strong {
    color: #d5c303;
  }
  #chat .grip {
    display: block;
    height: 10px;
    margin-top: -8px;
    background: transparent url('/img/im/chat-sprite.png') no-repeat 50% -20px;
    margin-bottom: 6px;
    cursor: n-resize;
  }
  #chat .close-tab {
    float: right;
    width: 20px;
    margin-right: -14px;
    margin-top: -12px;
    display: block;
    overflow: hidden;
  }
  #chat .close-tab .icon {
    background: transparent url('/img/im/chat-sprite.png') no-repeat 0 -46px;
    width: 20px;
    margin: 0;
    height: 24px;
  }
  #chat .conversation {
    background: #c6d2da url('/img/im/chat-bg-sprite.gif') repeat-y 0 0;
    padding: 0.6em 12px;
  }
  #chat .conversation .date {
    float: right;
    font-size: 10px;
    color: #787f84;
    margin-top: 12px;
  }
  #chat .baloon {
    color: #343434;
    background-color: #fff;
    float: left;
    max-width: 140px;
    padding: 0.6em 14px;
    font-size: 13px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
  }
  #chat .pip, #chat .msg-in .pip {
    height: 7px;
    background: transparent url('/img/im/chat-sprite.png') no-repeat 0 -72px;
    margin-top: -14px;
    clear: both;
  }
  #chat .msg-out .pip {
    background-position: 100% -96px;
  }
  #chat .msg-out, #chat .msg-in {
    margin-bottom: 14px;
  }
  




  
  #chat .msg-out .baloon {
    color: #242526;
    background-color: #a3aaae;
    float: right;
  }
  #chat .msg-out .date {
    float: left;
  }
  #chat hr {
    margin: 6px 0;
  }
  
  #chat .input input, #chat .offline-mode input, #chat .offline-mode textarea { /* new */
    padding: 0.5em 14px;
    border: 0; /* 1px solid #aeb8bf; */
    width: 100%;
    box-sizing: border-box;
    outline: none;
  }
  #chat .conversation,
  #chat .input {
    border: 1px solid #a5afb5;
  }
  #chat .conversation {
    border-top: 0;
    border-bottom: 0;
    height: 260px;
    overflow-y: scroll;
  }
  
  /* States */
  #chat.mode-minimized #tab-minimized {
    display: block;
  }
  #chat.mode-minimized .tab-header,
  #chat.mode-minimized .conversation,
  #chat.mode-minimized .input {
    display: none;
  }
  
  #chat.mode-full #tab-minimized {
    display: none;
  }
  #chat.mode-full .tab-header,
  #chat.mode-full .conversation,
  #chat.mode-full .input {
    display: block;
  }
  
  
  #chat .set-name button, #chat .set-name input, #chat .set-name input#send {
    display: block;
    padding: 0.6em 14px;
    border: 0;
    color: #fff;
    background: #09395d;
    border-radius: 4px;
    outline: none;
    margin: auto;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
  }
  #chat .set-name input, #chat .set-name textarea {               /* new */
    color: #000;
    background: #fff;
    border-radius: 0;
    display: none;
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    margin-bottom: 6px;
  }
  #chat .offline-mode input, #chat .offline-mode textarea {       /* new */
    display: block;
  }
  #chat .offline-mode textarea {                                  /* new */
    height: 100px;
  }

</style>

<div id="chat" class="mode-minimized">
  <a href="#" id="tab-minimized" class="tab-header"><i class="icon"></i>Напишите нам. Мы онлайн!</a>
  
  <div id="chat-block-conversation" style="display: none">
    <div class="tab-header">
      <a href="#" class="grip" title="Растянуть"></a>
      <a href="#" class="close-tab" title="Закрыть"><i class="icon"></i></a>

      <div class="consultant clearfix">
        <img id="consultant-image" src="/img/im/consultant-icon2.gif" class="ava">
        <p><font id="consultant-name"></font><span id="consultant-occupation">Консультант</span><span class="status online"><strong>•</strong>&nbsp;Онлайн</span></p>
      </div>
    </div>
    <div id="chat-conversation" class="conversation">
      <div id="chat-set-name" class="set-name">
        <button>Представиться</button>
        <input id="chat-user" type="text" name="set-name" placeholder="Укажите имя...">
        <input id="send" type="submit" value="Сохранить" style="display: none">
      </div>
    </div>
    <div class="input">
      <input id="chat-text" type="text" value="" name="" placeholder="Введите сообщение...">
    </div>
  </div>
  
  <div id="chat-block-consultants" style="display: none">
    <div class="tab-header">
      <a href="#" class="grip" title="Растянуть"></a>
      <a id="close-tab" href="#" class="close-tab" title="Закрыть"><i class="icon"></i></a>
      <div class="consultant clearfix"></div>
    </div>
    <div class="conversation">
      <p class="center">Выберите консультанта<br>чтобы начать диалог</p>
      <div id="list">
      </div>
    </div>    
  </div>
  
  <div id="chat-block-message" style="display: none">
    <div class="tab-header">
      <a href="#" class="grip" title="Растянуть"></a>
      <a id="close-tab" href="#" class="close-tab" title="Закрыть"><i class="icon"></i></a>
      <div class="consultant clearfix"></div>
    </div>
    <div class="conversation">
      <p id="info" class="center">Наши консультанты сейчас не&nbsp;могут ответить.<br/>Пожалуйста, напишите письмо.</p>
      <div class="set-name offline-mode">
        <form id="leave-message" method="post">
          <input id="email" name="email" type="text" placeholder="Ваш email…">
          <textarea id="message" name="message" placeholder="Ваше сообщение…"></textarea>
          <input id="send" type="submit" value="Оставить сообщение">
        </form>  
      </div>
    </div>    
  </div>
  
  <span id="chat-adviser" style="display:none"></span>
  <span id="chat-adviser-id" style="display:none"></span>
  <span id="chat-last-id" style="display:none"></span>
  
</div>

<script type="text/javascript">
  
  var talkStarted = false;
  var talkClosed = <? echo((isset($_SESSION['closed']) && $_SESSION['closed'])?'true':'false') ?>;
  var hInterval;
  var inProcess = false;
  
  var format = function (str, col) {
    col = typeof col === 'object' ? col : Array.prototype.slice.call(arguments, 1);

    return str.replace(/\{\{|\}\}|\{(\w+)\}/g, function (m, n) {
      if (m === "{{") { return "{"; }
      if (m === "}}") { return "}"; }
      return col[n];
    });
  };  

  function send(obj,done) {
    
    var url = '<? echo($this->phpself) ?>adviser/ufs.php';
    $.ajax(url,{data: JSON.stringify(obj), contentType: 'application/json', type: 'POST'}).done(function(json) {  
      //alert(json);
      data = JSON.parse(json);
      if (data) {
        if (done!==null) { done(data); }
      }
    });
  }
  
  function getUser() {
    return $('#chat-user').attr('value');
  }
  
  function setUser(user) {
    $('#chat-user').attr('value',user);
  }
  
  function getAdviser() {
    return $('#chat-adviser').text();  
  }
  
  function getAdviserId() {
    return $('#chat-adviser-id').text();  
  }

  function setAdviserId(id) {
    $('#chat-adviser-id').text(id);  
  }

  function getLastId() {
    return $('#chat-last-id').text();  
  }

  function setLastId(lastId) {
    $('#chat-last-id').text(lastId);
  }

  function getText() {
    return $('#chat-text').attr('value');
  }
  
  function getLeaveEmail() {
    return $('#leave-message #email').attr('value');
  }
  
  function getLeaveMessage() {
    return $('#leave-message #message').val();
  }
  
  function wrapLinks(text) {
    var re = /(https?:\/\/(([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?))/g;
    return text.replace(re,"<a href=\"$1\" title=\"\">$1</a>");
  }
  
  function messageIn(time,text) {
    
    text = wrapLinks(text);
    $(format('<div class="msg-in clearfix"><div class="date">{0}</div><div class="baloon">{1}</div><div class="pip"></div>',time,text)).insertBefore('#chat-set-name');
  }

  function messageOut(time,text) {
    
    text = wrapLinks(text);
    $(format('<div class="msg-out clearfix"><div class="date">{0}</div><div class="baloon">{1}</div><div class="pip"></div>',time,text)).insertBefore('#chat-set-name');
  }
  
  function scrollToBottom() {
    
    var divs = $('#chat-conversation div');
    $('#chat-conversation').scrollTop(divs[divs.size()-1].offsetTop);
  }
  
  function clearAdvisers() {
    $('#chat-block-consultants #list').empty();
  }
  
  function addAdviser(adviser) {
    
    var s = format('<a href="#" class="consultant clearfix person"><img id="consultant-image" src="{0}" class="ava">'+
                   '<p><font id="consultant-name">{1}</font><span>{2}</span>'+
                   '<span class="status online"><strong>•</strong>&nbsp;Онлайн</span></p>'+
                   '<span id="adviser-id" style="display:none">{3}</span></a>',
                    adviser.image,adviser.name,adviser.occupation,adviser.id);
            
    $(s).appendTo('#chat-block-consultants #list').click(function(e){
      e.preventDefault();
      setAdviserId($(this).children('#adviser-id').text());
      startChatRandomly(e);
    });
  }
  
  function getDefaultObj(method) {
   
    var obj = new Object();
    obj.method = method;
    obj.lang = '<?=$this->site_lang?>'; 
    obj.user = getUser();
    obj.adviser = getAdviser();
    obj.adviser_id = getAdviserId();
    obj.last_id = getLastId();
    obj.referrer = document.referrer;
    return obj;
  }
  
  function setAdviserExists(adviser) {
    $('#consultant-image').attr('src',adviser.image);
    $('#consultant-name').text(adviser.name);
    $('#consultant-occupation').text(adviser.occupation);
    setAdviserId(adviser.id);
  }
  
  function setAdviserEmpty() {
    $('#consultant-image').attr('src','/img/im/consultant-icon2.gif');
    $('#consultant-name').text('');
    $('#consultant-occupation').text('');
  }

  function talkExists(done) {
    
    var obj = getDefaultObj('exists');
    send(obj,function(data){
      if (data.success) {
        if (done!==null) { done(data.found); }
      }
    });
  }

  function setAdviser(done) {
    
    var obj = getDefaultObj('adviser');
    send(obj,function(data){
      if (data.success) {
        if (data.found) {
          setAdviserExists(data);
        } else {
          setAdviserEmpty();
        }
        if (done!==null) { done(data.found); }
      }
    });
  }
  
  function setAdvisers(done) {
    
    var obj = getDefaultObj('advisers');
    send(obj,function(data){
      if (data.success) {
        clearAdvisers();
        if (data.found) {
          var count = data.advisers.length;
          if (count>0) {
            for (var i=0; i<count; i++) {
              addAdviser(data.advisers[i]);
            }
          }
        }
        if (done!==null) { done(data.found); }
      }
    });
  }
  
  function processMessages(messages,needChat) {
    
    var count = messages.length;
    if (count>0) {
      for (var i=0; i<count; i++) {
        if (messages[i].out) {
          messageIn(messages[i].time,messages[i].text);
        } else {
          messageOut(messages[i].time,messages[i].text);
        }
        setLastId(messages[i].id);
      }
      if (needChat) {
        showChatOrConsultants(true);
      }
      scrollToBottom();
    }
  }
  
  function startTalk(done,failed) {
    
    var obj = getDefaultObj('start');
    send(obj,function(data){
      if (data.success) {
        setUser(data.user);
        processMessages(data.messages,!data.closed);
      }
      if (!data.closed) {
        if (done!==null) { done(); }
      } else { 
        if (failed!==null) { failed(); }
      }
    });
  }

  function stopTalk(done) {
    
    var obj = getDefaultObj('stop');
    send(obj,function(data){
      if (data.success) {
        if (done!==null) { done(); }
      }
    });
  }

  function sendIncoming(done,failed) {

    var obj = getDefaultObj('incoming');
    obj.text = getText();
    var d = new Date().toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/,"$1");
    messageOut(d,obj.text);
    $('#chat-text').attr('value','');
    scrollToBottom();
    send(obj,function(data){
      if (data.success) {
        setLastId(data.id);
        scrollToBottom();
        if (done!==null) { done(); }
      } else {
        if (failed!==null) { failed(); }
      }
    });
  }

  function sendName(done) {

    var obj = getDefaultObj('name');
    send(obj,function(data){
      if (data.success) {
        scrollToBottom();
        if (done!==null) { done(); }
      }
    });
  }
  
  function sendMessage(done) {

    var obj = getDefaultObj('message');
    obj.email = getLeaveEmail();
    obj.message = getLeaveMessage();
    send(obj,function(data){
      if (data.success) {
        $('#leave-message').hide();
        $('#chat-block-message #info').text(data.message);
        if (done!==null) { done(); }
      }
    });
  }
  
  function receiveOutgoing() {
    
    clearInterval(hInterval);
    var obj = getDefaultObj('outgoing');
    send(obj,function(data){
      if (data.success) {
        talkClosed = data.closed;
        if (data.adviser) {
          setAdviserExists(data.adviser);
        }
        if (data.messages) {
          processMessages(data.messages,!talkClosed);
        }
        if (!talkClosed) {
          setReceiveLoop();
        }
      }
    });
  }

  function setReceiveLoop() {
    
    clearInterval(hInterval);
    hInterval = setInterval(receiveOutgoing,2000);
  }
  
  function showChatOrConsultants(conversations) {

    var has = $('#chat').hasClass('mode-minimized');
    if (has) {
      $('#tab-minimized').hide();
      $('#chat').toggleClass('mode-minimized',false);
      $('#chat').toggleClass('mode-full',true);
    }
    $('#chat-block-message').hide();
    if (conversations) {
      $('#chat-block-consultants').hide();
      $('#chat-block-conversation').show();
      if (getUser()!=='') {
        hideInputName();
      }
      scrollToBottom();
    } else {
      $('#chat-block-conversation').hide();
      $('#chat-block-consultants').show();
    }
  }
  
  function showMessageBoard(e) {
    
    e.preventDefault();
    var has = $('#chat').hasClass('mode-minimized');
    if (has) {
      $('#tab-minimized').hide();
      $('#chat').toggleClass('mode-minimized',false);
      $('#chat').toggleClass('mode-full',true);
    }
    $('#chat-block-conversation').hide();
    $('#chat-block-consultants').hide();
    $('#chat-block-message').show();
  }
  
  function startChatRandomly(e) {

    if (!talkStarted && !talkClosed) {
      
      if (!inProcess) {
        
        inProcess = true;
        
        setAdviser(function(found){
          
          if (found) {
            startTalk(function(){
              talkStarted = true;
              showChatOrConsultants(true);
              setReceiveLoop();
              inProcess = false;
            },function(){
              talkClosed = true;
              if (e!==null) {
                showChatOrConsultants(true);
              }
              inProcess = false;
            });
          } else {
            if (e!==null) {
              showMessageBoard(e);
            }
            inProcess = false;
          }
        });
      }
    } else {
      showChatOrConsultants(true);
    }
  }
  
  function startChatSelectively(e) {
    
    if (!talkStarted && !talkClosed) {
      
      if (!inProcess) {
        
        inProcess = true;
        
        talkExists(function(found){
          if (found) {
            inProcess = false;
            startChatRandomly(e);
          } else {
            setAdvisers(function(found){
              if (found) {
                showChatOrConsultants(false);  
                inProcess = false;
              } else {
                inProcess = false;  
                startChatRandomly(e);
              }
            });
          }
        });
      }
      
    } else {
      showChatOrConsultants(true);
    }
  }
  
  function startChatWithoutEvent() {
    startChatRandomly(null);
  }
  
  function hideChat() {
    
    $('#chat').toggleClass('mode-full',false);
    $('#tab-minimized').show();
    $('#chat').toggleClass('mode-minimized',true);
  }
  
  function hideInputText() {
    
    $('#chat-text').hide();
  }
  
  function hideInputName() {
    
    $('#chat-set-name button').hide();
    $('#chat-set-name input').hide();
  }
  

  function stopChat() {
    
    hideChat();
    if (!talkClosed) {
      talkClosed = true;
      clearInterval(hInterval);
      stopTalk();
    }
  }

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
  
  function isBlank(str) {
    return (!str || /^\s*$/.test(str));
  }  
  
  function checkLeaveFields() {
    
    ret = false;
    if (!isEmail(getLeaveEmail())) {
      alert('Заполните электронный адрес.');
      return false;
    } else if (isBlank(getLeaveMessage())) {
      alert('Заполните текст сообщения.');
      return false;
    }
    return true;
  }

  $(document).ready(function(){
    
    var chatTimeout;
    
    if (!talkClosed) {
      chatTimeout = setTimeout(startChatWithoutEvent,10000);
    }
    
    $('#tab-minimized').click(function(e){
      e.preventDefault();
      clearTimeout(chatTimeout);
      startChatSelectively(e);
    });
    
    $('#chat-block-conversation .close-tab').click(function(e){
      e.preventDefault();
      clearTimeout(chatTimeout);
      stopChat();
    });

    $('#chat-block-consultants .close-tab').click(function(e){
      e.preventDefault();
      clearTimeout(chatTimeout);
      hideChat();
    });

    $('#chat-block-message .close-tab').click(function(e){
      e.preventDefault();
      clearTimeout(chatTimeout);
      hideChat();
    });

    $('#chat-text').keydown(function(e){
      if ((e.which===13) && (talkStarted || (!talkStarted && talkClosed))) {
        e.preventDefault();
        $('#chat-text').prop('disabled',true);
        if (getText()!=='') { 
          clearInterval(hInterval);
          sendIncoming(function(){
            $('#chat-text').prop('disabled',false);
            talkStarted = true;
            talkClosed = false;
            setReceiveLoop();
          },function(){
            $('#chat-text').prop('disabled',false);
          }); 
         }
      }
    });
    
    $('#chat-set-name button').click(function(e){
      e.preventDefault();
      $('#chat-set-name button').hide();
      $('#chat-set-name input').show();
      $('#chat-set-name #send').show();
      $('#chat-set-name #chat-user').focus();
    });

    $('#chat-set-name input').keydown(function(e){
      if (e.which===13) {
        e.preventDefault();
        if (getUser()!=='') {
          sendName(function(){
            hideInputName();
          }); 
        }
      }
    });
    
    $('#chat-set-name #send').click(function(){
      if (getUser()!=='') {
        sendName(function(){
          hideInputName();
        }); 
      }
    });
    
    $('#leave-message #send').click(function(e){
      e.preventDefault();
      if (!inProcess && checkLeaveFields()) {
        inProcess = true;
        sendMessage(function(){
          inProcess = false;
        });
      }
    });
  });
</script>


<? } ?>