<dl class="list-formirovania">
  <?if(isset($data->start_building)):?><dt><? echo(dictionary('Начало формировния')); ?></dt>
  <dd><? echo($data->start_building); ?></dd><?endif;?>
  <?if(isset($data->initial_amount)):?><dt><? echo(dictionary('Начальная сумма')); ?></dt>
  <dd class="large">$ <? echo($data->initial_amount); ?></dd>
  <?endif;?>
  <?if(isset($data->current_value)):?><dt><? echo(dictionary('Текущая стоимость')); ?></dt>
  <dd class="large">$ <? echo($data->current_value); ?></dd><?endif;?>
  <?if(isset($data->yield)):?><dt><? echo(dictionary('Доходность')); ?></dt>
  <dd class="geen"><? echo($data->yield); ?><? echo(dictionary('% годовых')); ?></dd><?endif;?>
  <?if(isset($data->duration_of_portfolio)):?>
  <?if($data->duration_of_portfolio>0):?>
	  <dt><? echo(dictionary('Дюрация портфеля')); ?></dt>
	  <dd><? echo($data->duration_of_portfolio); ?></dd>
  <?endif;?>
  <?endif;?>
</dl>

<? if(!empty($indexes_box)){ ?>
<div class="sidebar s_right">
    <? echo(settings($indexes_box)); ?>
</div>
<? } ?>

<address class="glavni-office">
  <? echo($this->load->view('body_contacts','',true)) ?>
</address>