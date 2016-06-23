<? if(isset($data_arr)){ ?>
  <h2><? echo(dictionary('Все вопросы')); ?></h2>
  <? foreach($data_arr as $item){ ?>
  <div class="article">
    <var><? echo($item->timestamp); ?></var>
    <a href="<? echo($this->phpself.$data->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot"><? echo($item->name); ?></a>
  </div>
  <? } ?>
<? }else{ ?>
    <var class="cur-date"><? echo($time); ?></var>

    <div class="form-ans">
     <form method="post" action="">
       <label>
         <input type="text" id="vashe-imya" placeholder="<? echo(dictionary('Ваше имя')); ?>" name="text[name]" /> 
       </label>
       <label class="email">
         <input type="text" id="vash-email" placeholder="<? echo(dictionary('Ваша электронная почта')); ?>" name="text[email]" /><br />
         <span class="sp"><? echo(dictionary('Мы не публикуем адреса')); ?></span>
       </label>
       <textarea id="vash-vopros" placeholder="<? echo(dictionary('Ваш вопрос')); ?>"  name="text[query]"></textarea>
       <span class="sp"><? echo(dictionary('Чем конкретней вы сформулируете вопрос, тем более точный ответ получите')); ?></span>
       <input type="submit" value="<? echo(dictionary('Задать вопрос')); ?>" />
       <input type="hidden" name="int[c_id]" value="<? echo($c_id); ?>">
       <input type="hidden" name="form_submit" value="1">
     </form>
    </div>
	<script type="text/javascript" src="<? echo($this->base_url); ?>/js/jquery.placeholder.min.js"></script>
    <div class="answer-quest with_a">
      <h2><? echo(dictionary('Вопросы с ответами')); ?></h2>
      <? foreach($data as $item){ ?>
        <? if(!empty($item->answer) and $item->status=='show'){ ?>
          <blockquote class="answer prf">
            <cite><? echo($item->user); ?></cite>
         
            <div class="q"><q><? echo(nl2br($item->query)); ?></q></div>
            <span class="fict"></span>
          </blockquote>
          <blockquote class="question">
            <cite><? echo(dictionary('Ответ компании UFS IC')); ?></cite>
           
            <div class="q"><q><? echo(nl2br($item->answer)); ?></q></div>
            <span class="fict"></span>
          </blockquote>
        <? } ?>
      <? } ?>
    </div>
<? } ?>