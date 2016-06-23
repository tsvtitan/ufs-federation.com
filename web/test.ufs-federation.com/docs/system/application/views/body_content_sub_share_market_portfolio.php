<div class="posledinie-sdelki">
<? if(isset($transaction)){ ?>
<?if(!empty($transaction)):?>
<h2><? echo(dictionary('Последние сделки')); ?></h2>
<table>
 <? foreach($transaction as $item){ ?>
  <tr>
    <td class="name">
      <h4><? echo($item->name); ?></h4>
      <? if($item->type=='продажа'){ ?>
        <var class="prodazha"><? echo(dictionary('продажа')); ?></var>
      <? }else if($item->type=='покупка'){ ?>
        <var class="pokupka"><? echo(dictionary('покупка')); ?></var>     
      <? }else if($item->type=='погашение'){ ?>
        <var class="pogashenie"><? echo(dictionary('погашение')); ?></var>   
       <? }else if($item->type=='выплата купона'){ ?>
        <var class="vuplata"><? echo(dictionary('выплата купона')); ?></var>
      <? } ?>
    </td>
    <td class="date">
      <?if($item->type=='продажа' or $item->type=='покупка'):?>
      <h5><? echo(dictionary('Дата сделки')); ?>:</h5>
      <strong class="b"><? echo($item->date); ?></strong>
      <h5><? echo(dictionary('Цена')); ?></h5>
      <strong class="b"><? echo($item->price); ?>%</strong>
      <?endif;?>
      <?if($item->type=='погашение'):?>
      <h5><? echo(dictionary('Дата погашения')); ?>:</h5>
      <strong class="b"><? echo($item->date); ?></strong>      
      <?endif;?>      
      <?if($item->type=='выплата купона'):?>
      <h5><? echo(dictionary('Дата выплаты купона')); ?>:</h5>
      <strong class="b"><? echo($item->date); ?></strong>      
      <?endif;?>
    </td>
    <td class="suma">
      <?if($item->type=='продажа' or $item->type=='покупка'):?>
      <h5><? echo(dictionary('Количество')); ?>:</h5>
      <strong class="b"><? echo($item->quantity); ?></strong>
      <h5><? echo(dictionary('Сумма сделки')); ?>:</h5>
      <strong class="b">$<? echo($item->transaction_amount); ?></strong>
      <?endif;?>
    </td>
    <td class="def">
      <p><? echo($item->content); ?></p>
    </td>
  </tr>
 <? } ?>
</table>
<?endif;?>
<? } ?>
</div>
<div class="struktura-portfelya">
<? if(isset($structure)){ ?>
<?if(!empty($structure)):?>
<h2><? echo(dictionary('Структура портфеля')); ?></h2>

<div id="piegrafic">
    <table>
        <tbody>
          <? foreach($structure as $item){ ?>
          <tr>
            <th scope="row"><? echo($item->procent_print); ?>% <? echo($item->name); ?></th>
            <td><? echo($item->procent); ?>%</td>
          </tr>
          <? } ?>
        </tbody>
      </table>
</div>
<?endif;?>
<? } ?>

<? if(isset($composition) and isset($composition['data']) and !empty($composition['data'])){ ?>
<h2><? echo(dictionary('Состав портфеля на')); ?> <a><? echo(isset($composition['last_date']->date)?$composition['last_date']->date:''); ?></a></h2>
<table class="grand-statistika">
  <thead>
    <tr>
      <th colspan="3">&nbsp;</th>
      <th colspan="2" class="twice_usd"><? echo(dictionary('Цена')); ?> (USD)</th>
      <th colspan="2">&nbsp;</th>
    </tr>
    <tr>
      <th>№</th>
      <th><? echo(dictionary('Название')); ?></th>
      <th><? echo(dictionary('Дата погашения')); ?></th>
      <th><? echo(dictionary('Цена покупки, % от номинала')); ?></th>
      <th><? echo(dictionary('Текущая цена, % от номинала')); ?></th>
      <th><? echo(dictionary('Номинальный объем')); ?>, USD</th>
      <th><? echo(dictionary('Стоимость')); ?>, USD</th>
    </tr>
  </thead>
  <tbody>
  <? foreach($composition['data'] as $item){ ?>
    <tr<? echo($item->css_class); ?>>
      <td><? echo($item->set_number); ?></td>
      <td><? echo($item->name); ?></td>
      <td><? echo($item->maturity_date); ?></td>
      <td><? echo(($item->price_starting>0)?$item->price_starting:''); ?></td>
      <td><? echo(($item->price_current>0)?$item->price_current:''); ?> 
         <? if($item->price_stats=='up'){ ?>
          <var class="green">↑</var>
         <? }elseif($item->price_stats=='down'){ ?>
          <var class="red">↓</var>
         <? }else{ ?>
          <var class="grey"></var>
         <? } ?>
      </td>
      <td><? echo(($item->nominal_volume>0)?$item->nominal_volume:''); ?></td>
      <td><strong><? echo(($item->cost>0)?$item->cost:''); ?></strong></td>
    </tr>
  <? } ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="7">
        <var><? echo(dictionary('Итого')); ?>: <strong>$ <? echo($composition['sum']); ?></strong></var>
      </td>
    </tr>
  </tfoot>
</table>

<div class="show_more">Смотреть еще</div>
<? } ?>

</div>