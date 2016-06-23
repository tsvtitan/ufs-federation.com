<table class="nash-aparat analitika">
<tbody>
  <? foreach($data as $item){ ?>
  <tr>
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
      <? if (/*md5($item->name) === '799f0ca3796212cc5edcdb8b6c411398' || */md5($item->name) === 'cc7dc9e887fca1183e55e5021e9ec2eb' || (md5($item->name) === 'cfbd53020927a52fd5b409f53511bfbb' && $this->site_lang == 'en') || (md5($item->name) === 'c2258348840b6418e3ed078a837cace2' && $this->site_lang == 'en')) { ?>
        <div class="approved<?=($this->site_lang == 'en' ? ' approved-en' : '')?>"<?=(md5($item->name) === 'cc7dc9e887fca1183e55e5021e9ec2eb' || md5($item->name) === 'c2258348840b6418e3ed078a837cace2')?' id="cbonds"':''?>></div>
      <? } ?>
    </td>
  </tr>
  <? } ?>
</tbody>
</table>