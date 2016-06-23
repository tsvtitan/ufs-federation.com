<? if (!isset($subscription_data) || (isset($subscription_data) && sizeOf($subscription_data)>0) ) { ?>
<div class="subscription-box">
  <h2><? echo(dictionary('Подписка на аналитику')); ?></h2>
  <h5><? echo(dictionary('Подпишитесь на нашу рассылку')) . ' ' . dictionary('аналитических материалов'); ?></h5>
  <div id="mc_embed_signup">
    <form action="/subscribe.html" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
      <? 
        if (isset($subscription_data)) {
          foreach($subscription_data as $sd) {
            echo('<input type=hidden name="'.$sd['name'].'" value="'.$sd['value'].'">');
          }
        }
      ?>
      <input type=text name=email class="email" id="mce-EMAIL" placeholder="<? echo(dictionary('Ваша электронная почта')); ?>" required>
      <div class="clear"><input type="submit" value="<? echo(dictionary('Подписаться')); ?>" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
    </form>
  </div>
</div>
<? } ?>

<?
if($this->last_page_name=='') {

    // banner Helicopter's Gold

    $banner_name      = 'hg2014';
    $banner_extension = 'gif';
    $banner_width     = '230';
    $banner_height    = '150';

    switch ($this->site_lang) {
      case 'ru': $banner_link = '/pages/o-kompanii/helicopters-gold.html'; break;
      case 'en': $banner_link = '/pages/about-us/helicopters-gold.html'; break;
      case 'de': $banner_link = '/pages/ber-die-firma/helicopters-gold.html'; break;
      case 'fr': $banner_link = '/pages/nous-connatre/helicopters-gold.html'; break;
      case 'cn': $banner_link = '/pages/nous-connatre/helicopters-gold.html'; break;
    }

    //if ($this->site_lang == 'ru') {
      echo '<br/><a href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_ru' . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a>';
    //}
}
?>