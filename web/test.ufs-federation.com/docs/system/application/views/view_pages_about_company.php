<!--
<table class="nash-aparat">
    <thead>
      <tr>
        <th><? if(count($director)>0){ ?><? echo(dictionary('Руководство')); ?><? } ?></th>
        <th><span><? echo(dictionary('Команда')); ?></span></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <? if(count($director)>0){ ?>
            <? foreach($director as $item){ ?>
              <div class="info-p">
                <? if(!empty($item->img)){ ?>
                <img src="<? echo($this->base_url.'/upload/analytical_team/small/'.$item->img); ?>" alt="" />
                <? } ?>
                <div class="text">
                  <h3><? echo($item->name); ?></h3>
                  <h4><? echo($item->info); ?></h4>
                </div>
              </div>
            <? } ?>
          <? } ?>
         </td>
      <? if(count($team)>0){ ?>
        <? foreach($team as $item){ ?>
        <td>
          <div class="info-p">
            <? if(!empty($item->img)){ ?>
            <img src="<? echo($this->base_url.'/upload/analytical_team/small/'.$item->img); ?>" alt="" />
            <? } ?>
            <div class="text">
              <h3><? echo($item->name); ?></h3>
              <h4><? echo($item->info); ?></h4>
            </div>
          </div>
        </td>
        <? } ?>
      <? } ?>
      </tr>
    </tbody>
    <? /* <tfoot>
      <tr>
        <td></td>
        <td<? echo((count($team)>1)?' colspan="'.count($team).'"':''); ?>><? echo(dictionary('В нашей команде работают только настоящие профессионалы, обеспечивающие высочайший уровень услуг и результат')); ?></td>
      </tr>
    </tfoot> */ ?>
</table>
-->