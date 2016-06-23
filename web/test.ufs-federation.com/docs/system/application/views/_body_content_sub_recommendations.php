
        
          <? if($data->euro){ ?>
		  <div class="section-recommendations">
		  <div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
          <table class="table-calc">
            <thead>
              <tr>
                <th><? echo(dictionary('Наименование')); ?></th>
                <th><? echo(dictionary('Тикер')); ?></th>
                <th width=15%><? echo(dictionary('Цена текущая')); ?></th>
                <th width=15%><? echo(dictionary('Цена (справедливая)')); ?></th>
                <th width=15%><? echo(dictionary('Потенциал роста/снижения')); ?></th>
                <th width=10%><? echo(dictionary('Рекомендация')); ?></th>
              </tr>
            </thead>
            <tbody>
              <? foreach($sectors as $s){ ?>
                <tr height=7px></tr>
                <tr class="sector">
                  <td><? echo($s->name); ?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <? foreach($s->items as $i){ ?>
                  <tr>
                    <td class="left"><? echo($i->ticker); ?></td>
                    <td class="left"><? echo($i->name); ?></td>
                    <td><? echo($i->price_currency); ?></td>
                    <td><? echo($i->price_fair); ?> </td>
                    <td><? echo($i->potential); ?> %</td>
                    <td><? echo($i->recommendation); ?></td>
                  </tr>
                <? } ?>
              <? } ?>
            </tbody>
          </table>
          <? if($this->site_lang=='ru') { ?>
            <span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span>
          <? } ?>
		  </div>
          <? } ?>
        
      
  
