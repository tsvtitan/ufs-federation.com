<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
        <td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
	<? if (isset($error)) { ?>
	<tr>
	  <td align="center" style="color: red">
	    <? echo($error); ?>
	  </td>
	</tr>
	<? } ?>
	<tr>
	  <td align="center">
      <form action="<? echo($this->uri->uri_string); ?>" method="post">
         <p><input name="subj" type="text" class="text" size="50" maxlength="100" value="<? echo($subj); ?>">
         <p><input name="lnk" type="text" class="text" size="50" value="<? echo($lnk); ?>">		
         <p><textarea name="message" class="textarea" cols="90" maxlength="19000" rows="20"><? echo($message); ?></textarea>
         <p><input name="submit" type="submit" style="height: 25px; width: 130px;" value="Отправить в QUIK">
       </form>
       </td>
    </tr>
</table>