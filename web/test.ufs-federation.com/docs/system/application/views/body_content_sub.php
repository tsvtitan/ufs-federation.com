<?if(is_active_settings('our_clients')):?>
<div class="colomn_shift_right">
    <div class="nashi-klienti">
      <h2><? echo(dictionary('Наши клиенты')); ?></h2>
      <? echo(settings('our_clients')); ?>
    </div>
</div>
<?endif;?>