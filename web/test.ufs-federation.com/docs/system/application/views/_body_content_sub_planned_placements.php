          <? if($sectors){ ?>
		  <div class="section-planned-placement">
		  <div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
          <table class="table-calc">
            <thead>
              <tr>
                <th><? echo(dictionary('Наименование')); ?></th>
                <th width=30%><? echo(dictionary('Размещение')); ?></th>
                <th width=35%><? echo(dictionary('Объем, млн')); ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <? foreach($sectors as $s){ ?>
                <tr height=7px></tr>
                <tr class="sector">
                  <td colspan="2"><? echo($s->name); ?></td>
                  <td></td>
                  <td></td>
                </tr>
                <? foreach($s->items as $i){ ?>
                  <tr>
                    <td class="left"><? echo($i->name); ?></td>
                    <td><? echo($i->placement); ?></td>
                    <td><? echo($i->volume); ?></td>
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
