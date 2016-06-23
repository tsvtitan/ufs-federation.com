<div class="section-kalendar-statistiki">
<ul class="kalendar-statistiki" id="nav_na_grafikah">
  <li><a href="#" rev="content_s" rel="content_tab_1"><span><? echo(dictionary('Предыдущая неделя')); ?></span> <var><? echo(isset($data[1]->week)?($data[1]->week->monday.' — '.$data[1]->week->sunday):'') ?></var></a></li>
  <li><a href="#" rev="content_s" rel="content_tab_2"><span><? echo(dictionary('Текущая неделя')); ?></span> <var><? echo(isset($data[2]->week)?($data[2]->week->monday.' — '.$data[2]->week->sunday):'') ?></var></a></li>
  <li><a href="#" rev="content_s" rel="content_tab_3"><span><? echo(dictionary('Следующая неделя')); ?></span> <var><? echo(isset($data[3]->week)?($data[3]->week->monday.' — '.$data[3]->week->sunday):'') ?></var></a></li>
</ul>
<div id="content_s">
<? for($i=1;$i<=3;$i++){ ?>
  <div id="content_tab_<? echo($i); ?>" class="content-tab">
    <div class="section">
    <? if(isset($data[$i]->data)){ ?>
      <span class="fict">
          <form action="" method="post">
            <select name="calendar_statistics_country[<? echo($i); ?>]" id="strana-statistiki" onchange="javascript:this.form.submit();">
                <option value="">&nbsp;-&nbsp;все страны&nbsp;-&nbsp;</option>
             <? foreach($data[$i]->country as $item){ ?>
              <option<? if($item==@$_REQUEST['calendar_statistics_country'][$i]){ ?> selected="selected"<? } ?> value="<? echo($item); ?>"><? echo($item); ?></option>
             <? } ?>
            </select>
          </form>
      </span>
      <table class="table-calc">
        <thead>
          <tr>
            <th><? echo(dictionary('Время')); ?></th>
            <th><? echo(dictionary('Страна')); ?></th>
            <th><? echo(dictionary('Месяц')); ?></th>
            <th><? echo(dictionary('Показатель')); ?></th>
            <th><? echo(dictionary('Прогноз')); ?></th>
            <th><? echo(dictionary('Предыдущее значение')); ?></th>
            <th><? echo(dictionary('Влияние фактора')); ?></th>
          </tr>
        </thead>
        <tbody>
          <? foreach($data[$i]->data as $item){ ?>
          <tr class="date-incriment">
            <td colspan="7"><? echo($item->date); ?></td>
          </tr>
            <? foreach($item->arr as $val){ ?>
              <tr<? echo($val->css_class); ?>>
                <td><? echo($val->time); ?></td>
                <td><? echo($val->country); ?></td>
                <td><? echo(dictionary($val->month)); ?></td>
                <td><? echo($val->rate); ?></td>
                <td><? echo($val->forecast); ?></td>
                <td><? echo($val->previous_value); ?></td>
                <td><? echo($val->influence_factor); ?></td>
              </tr>
            <? } ?>
          <? } ?>
        </tbody>
      </table>
	  <div class="show_more">Смотреть еще</div>
    <? }else{ ?>
      <span class="fict"><? echo(dictionary('Нет данных на этот период')); ?></span>
    <? } ?>
    </div>
  </div>
<? } ?>
</div>
</div>
