<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center"><h2><? echo($this->lang->line('admin_change_password_header')); ?></h2></td>
  </tr>
  <? if($error){ ?>
  <tr>
    <td align="center" class="error"><h4><? echo($error); ?></h4></td>
  </tr>
  <? } ?>
  <tr>
    <td valign="top" align="center">
 	<form action="<? echo($this->phpself); ?>change_pass" method="post">
		<table border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td><label for="oldpassword"><? echo($this->lang->line('admin_change_password_old')); ?></label></td>
          </tr>
          <tr>
            <td><input type="text" name="oldpassword" id="oldpassword" class="text" size="15" /></td>
          </tr>
          <tr>
            <td><label for="newpassword"><? echo($this->lang->line('admin_change_password_new')); ?></label></td>
          </tr>
          <tr>
            <td><input type="text" name="newpassword" id="newpassword" class="text" size="15" /></td>
          </tr>
          <tr>
            <td class="submit"><input type="image" src="<? echo($this->base_url); ?>images/btn_ok_admin.png" /></td>
          </tr>
        </table>
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>
</table>
