
        
          <? if($data) { ?>
		  <div class="section-recommendations">
		  <div class="last_update"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($last_update); ?></b></div>
          <table class="table-calc">
            <thead>
              <tr>
                <th><? echo(dictionary('Эммитент')); ?></th>
                <th><? echo(dictionary('Название бумаги')); ?></th>
                <th width=15%><? echo(dictionary('ISIN')); ?></th>
                <th width=15%><? echo(dictionary('BID')); ?></th>
                <th width=15%><? echo(dictionary('ASK')); ?></th>
                <th width=10%><? echo(dictionary('Цена закрытия')); ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <? foreach($data as $s){ ?>
                <tr height=7px></tr>
                  <td class="left"><? echo($s->short_name); ?></td>
                  <td class="left"><? echo($s->security_name); ?></td>
                  <td><? echo($s->id_isin); ?></td>
                  <td><? echo($s->bid); ?></td>
                  <td><? echo($s->ask); ?></td>
                  <td><? echo($s->px_close_1d); ?></td>
                  <td></td>
                </tr>
              <? } ?>
            </tbody>
          </table>
          <? if($this->site_lang=='ru') { ?>
            <span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span>
          <? } ?>
		  </div>
          <? } ?>
        
      
  
