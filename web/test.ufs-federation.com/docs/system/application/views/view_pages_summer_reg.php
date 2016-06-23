 <form method="POST" id="reg_form" action="<? echo($action); ?>">
      <tr><td class="sections"><? echo($sections); ?></td></tr>      
      <tr>
	    <td>
		<table class="fields">
		  <tr>
			<td class="name"><? echo(dictionary('Логин')); ?>:&nbsp<input name="name" placeholder="<? echo(dictionary('Как нам к вам обращаться')); ?>" value="<? echo($name); ?>" /></td>
		  </tr>
		  <tr>
			<td class="email"><? echo(dictionary('E-Mail')); ?>:&nbsp<input name="email" placeholder="<? echo(dictionary('Укажите ваш адрес электронной почты')); ?>" value="<? echo($email); ?>" /></td>
		  </tr>
		  <tr><td class="captcha">
			<table>
				<tr>
					<td><? if(isset($captcha_image)) { echo($captcha_image); } ?></td>
					<td><input name="word" placeholder="<? echo(dictionary('Число с картинки')); ?>" /></td>
				</tr>
			</table>
		  </td></tr>
		</table>  
		</td>
	  </tr>	
	  <tr>
	    <td class="agreement">
            <span>
            <? if ($this->site_lang=='en') { ?>Test text. English
            <? } elseif($this->site_lang=='de') {?>De. Text             
            <? } else { ?>Ru. Text info
			<? } ?>
            </span>
		</td>
	  </tr>
      
      <tr><td class="submit"><input id="subscribe_submit" name="submit" type="submit" <? echo($submit_disabled); ?> value="<? echo(dictionary('Зарегистрироваться')); ?>" style="font-weight: <? echo($submit_fontweight); ?>;" /></td></tr> 
    </form>