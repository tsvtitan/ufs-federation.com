<?
/* if($this->last_page_name=='model') {
 // banner Valentine
$banner_name      = 'stval';
$banner_extension = 'gif';
$banner_width     = '230';
$banner_height    = '150';

switch ($this->site_lang) {
  case 'ru': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
  case 'en': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
  case 'de': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
  case 'fr': $banner_link = 'http://ru.ufs-federation.com/pages/o-kompanii/st-valentines-day.html'; break;
}

if ($this->site_lang == 'ru') {
  echo '<a style="display: block; float: right; margin-top: 20px;" href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
}
} */







/*
if($this->last_page_name=='torgovue-idei-new') {

  // banner Become a client
  $banner_name      = 'become-a-client';
  $banner_extension = 'gif';
  $banner_width     = '230';
  $banner_height    = '150';

  switch ($this->site_lang) {
    case 'ru': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
    case 'en': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
    case 'de': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
    case 'fr': $banner_link = 'http://ru.ufs-federation.com/application-form.html'; break;
  }

  if ($this->site_lang == 'ru') {
    echo '<a style="display: block; float: right; margin-top: 20px;" href="' .$banner_link. '"><img src="/banners/' .$banner_name. '_' . $this->site_lang . '.' .$banner_extension. '" width="'.$banner_width.'" height="'.$banner_height.'" alt=""/></a><br/>';
  }
}
*/
?>
  <!--<div class="section online_consultant">
    <? /*echo($this->load->view('body_online_consultant','',true))*/ ?>
  </div>-->

<div class="h_block" style="overflow: visible; <?=(
$this->last_page_name == 'runki-aktsionnogo-kapitala' ||
$this->last_page_name == 'debt-capital-market' ||
$this->last_page_name == 'lokalnue-obligatsii' ||
$this->last_page_name == '573-evroobligatsii' ||
$this->last_page_name == 'vekselnue-zayimu' ||
$this->last_page_name == 'islamskoe-finansirovanie' ||
$this->last_page_name == 'pereypakovka-dolga' ||
$this->last_page_name == 'gss' ||
$this->last_page_name == 'zao-avions-civiles-de-sukhoi' ||
$this->last_page_name == '578-stryktyra-dlya-vupyska-evroobligatsiyi' ||
$this->last_page_name == 'ufs') ? 'margin-bottom: 8px' : '' /* winter theme - 8px; normal - 122px */?>">
  <div class="postion">
    <? if($data){ ?>
    <ul>
      <li><a href="<? echo($this->phpself.$this->page_url.'/'.$data->parent_url.$this->urlsufix); ?>"><? echo($data->parent_name); ?></a></li>
      <? if($this->uri->segment(5)!='' and !isset($data->no_head_suburl)){ ?>
      <li><a href="<? echo($this->phpself.$this->page_url.'/'.$data->parent_url.'/'.$data->url.$this->urlsufix); ?>"><? echo($data->name); ?></a></li>
      <? } ?>
    </ul>
    <h1<? echo((strlen($data->header)>65)?' class="small-text"':''); ?>><? echo($data->header); ?></h1>
    <? } ?>
  </div>
  <? if($this->site_lang == 'ru') { ?>
  <div style="position: absolute; width: 100%; display: block; margin-top: 110px; margin-left: 0px; z-index: 10; padding: 0; <? /* =(
$this->last_page_name == 'runki-aktsionnogo-kapitala' ||
$this->last_page_name == 'debt-capital-market' ||
$this->last_page_name == 'lokalnue-obligatsii' ||
$this->last_page_name == '573-evroobligatsii' ||
$this->last_page_name == 'vekselnue-zayimu' ||
$this->last_page_name == 'islamskoe-finansirovanie' ||
$this->last_page_name == 'pereypakovka-dolga' ||
$this->last_page_name == '578-stryktyra-dlya-vupyska-evroobligatsiyi' ||
$this->last_page_name == 'ufs') ? 'margin-top: 210px' : '' */ ?>">
    <a class="callback allnight allnight-mini" onclick="ga('send', 'event', 'Действия', 'Обратный звонок Внутренняя');">Круглосуточный колл-центр. Мы работаем, чтобы вам было удобно.<br/><span><? echo(dictionary('8-800 234-0202')); ?></span></a>
  </div>
  <? } ?>
</div>
  


<? if (isset($data->content)) { ?>
<div class="page-content<? echo(isset($data->content_editor_class)?' editor-content':''); ?>">
<? echo(isset($data->content)?$data->content:''); ?>
</div>

<? } ?>
<? echo(isset($data->sub_content)?$data->sub_content:''); ?>