<div class="h_block">
  <div class="postion">
    <? if($error){ ?>
    <h1 class="small-text"><? echo($error); ?></h1>
    <? } ?>
  </div>
</div>
<div class="block error-404">
    <? echo(isset($data->content)?$data->content:''); ?>
</div>