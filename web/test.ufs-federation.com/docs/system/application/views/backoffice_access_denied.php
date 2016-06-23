<script>

function hide_access_denied() 
{
  document.getElementById('access_denied_shade').style.display='none';
  document.getElementById('access_denied').style.display='none';	 
}
 
</script>

<div id="access_denied_shade"></div>
<div id="access_denied">
  <div id="access_denied_title"><? echo($this->lang->line('admin_tpl_error')) ?></div>
  <div id="access_denied_text"><? echo($this->lang->line('admin_tpl_access_denied')) ?></div>
  <div id="access_denied_button">
    <button onclick="hide_access_denied();"><? echo($this->lang->line('admin_tpl_close')) ?></button>
  </div>  
</div>