<div class="h_block">
  <div class="postion">
    <? if($data){ ?>
    <h1<? echo((strlen($data->name)>65)?' class="small-text"':''); ?>><? echo($data->name); ?></h1>
    <? } ?>
  </div>
</div>
<div class="page-content<? echo(isset($data->content_editor_class)?' editor-content':''); ?>">
<? echo(isset($data->content)?$data->content:''); ?>
</div>