<div class="content_form_ans">

<div class="section-obr-svyaz">
<? if(isset($error['error'])){ ?>
	<div class="check_error" id="check_error">
		  <strong><? echo(dictionary('Пожалуйста заполните обязательные поля')); ?>:</strong>
		  <ul>
              <? foreach($error['error'] as $er){ ?>
			  <li><? echo($er); ?></li>
              <? } ?>
		  </ul>
	</div>
<? } ?>
  <div class="form-ans">

    <form action="" method="post">
      <label>
        <input type="text" id="vashe-imya" name="text[name]" placeholder="<? echo(dictionary('Ваше имя')); ?>" value="<? echo(isset($error['name'])?$error['name']:''); ?>" maxlength="250" />
      </label>
      <label class="email">
        <input type="text" id="vash-email" name="text[email]" placeholder="<? echo(dictionary('Ваша электронная почта')); ?>" value="<? echo(isset($error['email'])?$error['email']:''); ?>" maxlength="250" /><br />
        <span class="sp"><? echo(dictionary('Вы получите уведомление об ответе')); ?></span>
      </label>
      <label>
        <textarea id="vash-vopros" placeholder="<? echo(dictionary('Ваш вопрос')); ?>" name="text[query]"><? echo(isset($error['query'])?$error['query']:''); ?></textarea>
        <span class="sp"><? echo(dictionary('Чем конкретнее вы сформулируете вопрос, тем более точный ответ получите')); ?></span>
      </label>
     <ul class="fieldset">
<!--          <li class="capture-img"><img src="<? echo($this->base_url); ?>/code/index.php?rnd=<? echo(time()); ?>" alt="" /></li> 
          <li class="capture-text">
          <span><? echo(dictionary('Повторите текст на картинке справа')); ?></span>-->
<!--          <label>
          <input type="text" id="kapcha" name="kapcha_code" placeholder="Captcha" value="" maxlength="6" />
          </label>-->
<!--        </li> -->
        <li class="capture-submit"><input type="submit" value="<? echo(dictionary('Задать вопрос')); ?>" /></li> 
      </ul>
<!--      <input type="submit" value="<? echo(dictionary('Задать вопрос')); ?>" /> -->
      <input type="hidden" name="form_submit" value="1">
    </form>
  </div>
</div>

</div>