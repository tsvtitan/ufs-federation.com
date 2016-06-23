  <div class="promo-section"> 
    
  <div class="section online_consultant">
    <? echo($this->load->view('body_online_consultant','',true)) ?>
  </div>
    
    <div class="section second_s">
      <h2><? echo(dictionary('Мы в СМИ')); ?></h2>
      <? if(count($press_about_us)>0){ ?>
          <? foreach($press_about_us as $item){ ?>
          <div class="article">
            <var class="news-logo clearfix">
              <? if(!empty($item->img)){ ?>
              <span class="block-img"><img src="<? echo($this->base_url.'/upload/press_about_us/small/'.$item->img); ?>" /></span>
              <? } ?>
              <var>
				<? echo($item->timestamp); ?><span><? echo($item->name); ?></span>
			  </var> 
            </var>
            <a href="<? echo($this->phpself.'pages/'.$item->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot">
                <? echo($item->short_content); ?>
            </a>
          </div>
          <? } ?>
         <a href="<? echo($this->phpself.'pages/'.$press_about_us[0]->page_url.$this->urlsufix); ?>" class="all-news"><? echo(dictionary('Все публикации')); ?></a>
      <? } ?>
    </div>
          
    <div class="section first_s">
      <h2><? echo(dictionary('Новости')); ?></h2>
      <? if(count($news)>0){ ?>
          <? foreach($news as $item){ ?>
          <div class="article">
            <var><? echo($item->timestamp); ?></var>
            <a href="<? echo($this->phpself.'pages/'.$item->page_url.'/'.$item->url.$this->urlsufix); ?>" class="shot">
                <? echo($item->short_content); ?>
            </a>
          </div>
          <? } ?>
         <a href="<? echo($this->phpself.'pages/'.$news[0]->page_url.$this->urlsufix); ?>" class="all-news"><? echo(dictionary('Все новости')); ?></a>
      <? } ?>
    </div>
   
    <div class="section contacts">
      
      <? echo($this->load->view('body_contacts','',true)) ?>
      
      <? echo($this->load->view('body_banners','',true)) ?>
      
      <? echo($this->load->view('body_adv','',true)) ?>
       
      <div class="newsletter">
        <h5><? echo(dictionary('Подпишитесь на нашу рассылку')); ?> <a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('analytics').$this->urlsufix); ?>"><? echo(dictionary('аналитических материалов')); ?></a></h5>		
        <!-- Begin MailChimp Signup Form -->
        <link href="http://cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
	  	<style type="text/css">
			#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
			/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
			   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
		</style>
		<div id="mc_embed_signup">
			<form action="http://ufs-federation.us4.list-manage1.com/subscribe/post?u=82f163d05f081f13c8bd7bfe5&amp;id=93d44d8b15" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
				<!-- <label for="mce-EMAIL">Subscribe to our mailing list</label> -->
				<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<? echo(dictionary('Ваша электронная почта')); ?>" required>
				<div class="clear"><input type="submit" value="<? echo(dictionary('Подписаться')); ?>" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
			</form>
		</div>
		
      </div>
      
      <? echo($this->load->view('body_subscriber','',true)) ?>
      
   </div>
    
   </div>  		
  
    <div class="bottom-block clearfix">
        <? if($conferencies){ ?>
        <div class="conference-block">
            <div class="conference-inner">
            <b><? echo(dictionary('Ближайшие мероприятия')); ?>:</b>
            <p>
            <a href="<? echo($this->phpself.'pages/'.$conferencies->page_url.'/'.$conferencies->url.$this->urlsufix); ?>">
                <? echo($conferencies->name); ?>
            </a>
            </p>
            <a href="<? echo($this->phpself.'pages/'.$conferencies->page_url.'/all'.$this->urlsufix); ?>" class="icon-conference"><? echo(dictionary('Все мероприятия')); ?></a>
            </div>
        </div>
        <? } ?>

        <?if(!empty($lase_debt_emmiter) or !empty($lase_dmarket_daily)){?>
        <div class="comments">
        <h2><? echo(dictionary('Специальная аналитика')); ?></h2>
            <ul>
                <?if(!empty($lase_debt_emmiter)):?>
                <li><span><a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('debt_market').$this->urlsufix); ?>"><? echo(dictionary('Долговой рынок')); ?></a>:</span> <a class="icon-comment" href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url_by_id($lase_debt_emmiter->page_id).'/'.$lase_debt_emmiter->url.$this->urlsufix); ?>"><? echo(dictionary('Комментарий за')); ?> <?echo($lase_debt_emmiter->date)?></a></li>
                <?endif;?>

             
                <?if(!empty($lase_dmarket_daily)):?>
                <li><span><a href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url('share_market').$this->urlsufix); ?>"><? echo(dictionary('Рынок акций')); ?></a>:</span> <a class="icon-comment" href="<? echo($this->phpself.'pages/'.$this->global_model->pages_url_by_id($lase_dmarket_daily->page_id).'/'.$lase_dmarket_daily->url.$this->urlsufix); ?>"><? echo(dictionary('Комментарий за')); ?> <?echo($lase_dmarket_daily->date)?></a></li>
                <?endif;?>
            </ul>
        </div>
        <?}?>
    </div>
