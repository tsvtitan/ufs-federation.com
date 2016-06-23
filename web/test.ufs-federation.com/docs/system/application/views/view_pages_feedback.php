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
        <span class="sp"><? echo(dictionary('Чем четче вы сформулируете вопрос, тем более точный ответ получите')); ?></span>
      </label>
      <ul class="fieldset">
        <li class="capture-img"><img src="<? echo($this->base_url); ?>/code/index.php?rnd=<? echo(time()); ?>" alt="" /></li>
        <li class="capture-text">
          <span><? echo(dictionary('Повторите текст на картинке справа')); ?></span>
          <label>
          <input type="text" id="kapcha" name="kapcha_code" placeholder="Captcha" value="" maxlength="6" />
          </label>
        </li>
        <li class="capture-submit"><input type="submit" value="<? echo(dictionary('Задать вопрос')); ?>" /></li>
      </ul>
      <input type="hidden" name="form_submit" value="1">
    </form>
  </div>
  <?if(isset($data)):?>
  <? if(is_array($data)){ ?>
  <div class="section-a-q">
    <h2><? echo(dictionary('Ответы на вопросы')); ?></h2>
    <? foreach($data as $item){ ?>
    <blockquote>
      <cite><span class="fict"></span><? echo(nl2br($item->query)); ?></cite>
      <? if(!empty($item->answer)){ ?>
      <div class="q"><q><span class="fict"></span><? echo(nl2br($item->answer)); ?></q></div>
      <? } ?>
    </blockquote>
    <? } ?>
  </div>
  <? } ?>
  <?endif;?>
  <p class="ups"><a href="javascript:ScrollUp();"><? echo(dictionary('Наверх')); ?></a></p>
</div>

</div>