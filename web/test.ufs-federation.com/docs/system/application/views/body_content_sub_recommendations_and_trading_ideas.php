
        
    <? if (isset($data)) { ?>
		<div class="section-recommendations">
		  <div class="list-names">
		    <ul>
		      <li class="<? if (isset($data->recommendations)) { echo('selected'); } else { echo('normal'); } ?>"><a href="?type=0"><? echo(dictionary('Рекомендации')); ?></a></li>
		      <li class="<? if (isset($data->trading_ideas)) { echo('selected'); } else { echo('normal'); } ?>"><a href="?type=1"><? echo(dictionary('Торговые идеи')); ?></a></li>
		    </ul>
		  </div>
		  <div class="last_update_right"><b><? echo(dictionary('Обновление данных')); ?>: <? echo($data->last_update); ?></b></div>
		    <? if (isset($data->recommendations)) { ?>
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
              <? foreach($data->groups as $s){ ?>
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
                    <td class="left"><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->ticker))); ?>"><? echo($i->ticker); ?></a></td>
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
        <? } ?>  
        <? if (isset($data->trading_ideas)) { ?>
          <table class="table-calc">
            <thead>
            <tr>
                <th><? echo(dictionary('Выпуск')); ?></th>
                <th><? echo(dictionary('ISIN')); ?></th>
                <th><? echo(dictionary('Отрасль')); ?></th>
                <th><? echo(dictionary('Доходность, %')); ?></th>
                <th width=10%><? echo(dictionary('Цена, (% от номинала)')); ?></th>
                <th width=35%><? echo(dictionary('Рекомендация')); ?></th>
              </tr>
            </thead>
            <tbody>
              <? foreach($data->groups as $s){ ?>
                <tr height=7px></tr>
                <tr class="sector">
                  <td colspan="2"><? echo($s->name); ?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr> 
                <? foreach($s->items as $i){ ?>
                  <tr>
                    <td class="left"><a href="<? echo(sprintf('%ssearch%s?text=%s',$this->phpself,$this->urlsufix,urlencode($i->ticker))); ?>"><? echo($i->ticker); ?></a></td>
				    				<td><? echo($i->isin); ?></td>
                    <td><? echo($i->sector); ?></td>
				    				<td><? echo($i->price_currency); ?></td>
                    <td><? echo($i->price_fair); ?> </td>
                    <td style="text-align: left"><? echo($i->potential); ?> </td>
                  </tr>
                <? } ?>
              <? } ?>
            </tbody>
          </table>
          <? if($this->site_lang=='ru') { ?>
            <span><i><? echo(dictionary('﻿Представленные оценки основываются на данных, полученных из публичных источников информации, предположениях и прогнозах UFS IC и не содержат инсайдерской, и иной информации, полученной незаконным путем. Компания UFS IC и ее сотрудники не несут ответственности за инвестиционные решения, принятые на основе представленной информации.')); ?></i></span>
          <? } else { ?>
            <span><i><? echo(dictionary('The present assessments are based on the data from public sources, assumptions and forecasts of UFS IC and do not include insider or other information derived illegally. UFS IC and its employees are not responsible for the decisions based on the provided information.')); ?></i></span>
          <? } ?>
        <? } ?>
		  </div>
      <? } ?>