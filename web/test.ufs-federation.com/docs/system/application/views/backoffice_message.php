<? if (isset($message) && ($message instanceof stdClass) && !isset($access_denied)) { ?>

<script type="text/javascript" language="javascript" src="/js/json2.js"></script>

<script type="text/javascript">

  function hide_message() {
    
    var obj = new Object();
    var url = '<? echo($this->phpself) ?>hide_message';
    $.ajax(url,{data: JSON.stringify(obj), contentType: 'application/json', type: 'POST'}).done(function(json) {  
      //alert(json);
      data = JSON.parse(json);
      if (data && data.success) {
        document.getElementById('message_shade').style.display='none';
        document.getElementById('message_box').style.display='none';
      }
    });
  }
  
</script>

<div id="message_shade"></div>
<div id="message_box">
  <div id="message_box_title"><? echo((isset($message->title) && !is_null($message->title))?$message->title:$this->lang->line('admin_tpl_message_info')) ?></div>
  <div id="message_box_text"><? echo((isset($message->text) && !is_null($message->text))?$message->text:$this->lang->line('admin_tpl_message_no_text')) ?></div>
  <div id="message_box_button">
    <button onclick="hide_message();"><? echo($this->lang->line('admin_tpl_close')) ?></button>
  </div>  
</div>
<? } ?>