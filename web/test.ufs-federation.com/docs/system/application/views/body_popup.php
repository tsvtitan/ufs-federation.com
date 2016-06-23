<div id="wrapper-popup">
  <div class="popup">
    <? if (!$error) { ?>
    <strong class="tit"><? echo(($error)?$error:dictionary('Спасибо, ваш вопрос был отправлен специалистам компании. Мы ответим на него в ближайшее время.')); ?></strong>
    <h3><? echo(dictionary('Ваш вопрос')); ?></h3>
    <p><? echo($query); ?></p>
    <p><span onclick="hide_id('wrapper-popup')" class="question-more"><? echo(dictionary('Задать ещё вопрос')); ?></span></p>
    <? } else { ?>
    <strong class="tit"><? echo($error); ?></strong>
    <? } ?>
    <span onclick="hide_id('wrapper-popup')" class="close-popup"><? echo(dictionary('закрыть')); ?></span>
  </div>
</div>
<script type="text/javascript">
    show_id('wrapper-popup');
</script>