<? if(sizeOf($data)>0) { ?>
<script>

String.prototype.toMMSS = function () {
    sec_numb    = parseInt(this);
    var hours = Math.floor(sec_numb / 3600);
    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
    var seconds = sec_numb - (hours * 3600) - (minutes * 60);

    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = minutes+':'+seconds;
    return time;
} 

function decrease_counter(obj,timerInterval) {

	if (obj.childNodes) {
	  for (var i=0;i<=obj.childNodes.length-1;i++) {
		  var o=obj.childNodes[i];
		  if (o.attributes) {
        var counter=o.attributes["counter"];
		    if (counter) {
			    counter=o.attributes["counter"].value;
			    counter=counter-1;
			    if (counter<0) {
            clearInterval(timerInterval);
            setTimeout(function(){
              document.location.href="<? echo($this->phpself.$this->page_name); ?>";  
            },3000);
			    } else {
  				 o.attributes["counter"].value=counter;
  				 o.innerHTML=counter.toString().toMMSS();
			    }		
		    }	else {
			    decrease_counter(o,timerInterval);
		    }
		  } else {
			  decrease_counter(o,timerInterval);
		  }	  	
	  }
	}  	
}
</script>
<? } ?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
        <td align="center" colspan="2"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center" class="left_sidebar">
        <table border="0" cellspacing="0" cellpadding="0" align="center" class="left_content">
          <? if(isset($is_update)){ ?>
          <tr>
                <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_status')); ?></h5></td>
          </tr>
          <tr>
                <td class="item"><? echo($is_update); ?></td>
          </tr>
          <? } ?>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_search')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <form action="<? echo($this->phpself.$this->page_name); ?>" method="post">
                    <input type="text" class="text" name="search" value="<? echo(isset($_SESSION['search'])?stripslashes($_SESSION['search']):''); ?>" />
                    <br />
                    <input type="image" src="<? echo($this->base_url); ?>images/btn_ok_admin.png" />
                    <input type="hidden" name="form_search" value="1">
                </form>
            </td>
          </tr>
          <tr>
              <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_menu')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <ul class="sub_menu">
                    <li><a href="<? echo($this->phpself.$this->page_name); ?>/add"><? echo($this->lang->line('admin_tpl_add')); ?></a></li>
                </ul>
            </td>
          </tr>
        </table>
	</td>
	<td valign="top" align="center">
		<table id="review_data" border="0" cellspacing="0" cellpadding="0" align="center" class="form_content">
      <? if($data) { ?>
		  <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
        <td class="header_title" width="50%"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
        <td class="header_title" width="25%" style="text-align:center"><h5><? echo($this->lang->line('admin_tpl_company')); ?></h5></td>
        <td class="header_title" width="25%" style="text-align:center"><h5><? echo($this->lang->line('admin_tpl_mailing_status')); ?></h5></td>
        <td class="header_title"></td>
        <td class="header_title"></td>
      </tr>
        <? $timer = false; $refresh = false;
          foreach($data as $item) { ?>
      
          <tr <? echo($item->css_class); ?>>
            <td class="item"><? echo($item->timestamp); ?></td>
            <td class="item"><? echo($item->name); ?></td>
            <td class="item" style="text-align:center">
              <? 
                echo(($item->company[0]=='UFS')?'UFS IC':'Premier'); 
              ?>
            </td>
            <td class="item" style="text-align:center; padding: 0 0 0 0">
              <?
                if ($item->subscriber_count>0 || $item->keyword_count>0) {
                  
                  $flag = ($item->all_count==0) || (($item->all_count>0) && ($item->all_count==($item->sent_count+$item->error_count)));
                  if ($flag) {
                    
                    $name = $this->lang->line('admin_mailing_make');
                    echo('<span><a href="'.$this->phpself.$this->page_name.'/make/'.$item->id.'">'.$name.'</a></span>');
                    
                  } else {
                    
                    $d = gmdate('i:s',abs($item->time_diff));
                    if ($item->time_diff>0) {
                      $timer = true;
                      echo($this->lang->line('admin_mailing_waiting').'&nbsp<span counter="'.$item->time_diff.'" style="color:red">'.$d.
              	   	 		  '</span><br><a href="'.$this->phpself.$this->page_name.'/cancel/'.$item->id.'">'.$this->lang->line('admin_mailing_cancel').'</a> | <a href="'.$this->phpself.$this->page_name.'/send/'.$item->id.'">'.$this->lang->line('admin_mailing_send').'</a>');
                    } else {
                      $refresh = true;
                      echo('<div><span style="display: inline-block; width:16px; height:16px; background-image: url(/images/spinner.gif)">&nbsp</span>&nbsp');
                      echo('<span>'.$this->lang->line('admin_mailing_sending').'</span><br/>');
                      echo('<a href="'.$this->phpself.$this->page_name.'/interrupt/'.$item->id.'">'.$this->lang->line('admin_mailing_interrupt').'</a></div>');
                    }
                  }
                } else {
                  if ($item->section_count==0) {
                    echo('<span>'.$this->lang->line('admin_mailing_no_sections').'</span>');
                  } else {
                    echo('<span>'.$this->lang->line('admin_mailing_no_subscribers_or_keywords').'</span>');
                  }
                }
                ?>
            </td>
            <td class="item center">
              <?
                if ($item->all_count > 0) { 
                  
                  echo(sprintf('<span style="color:%s; white-space: nowrap;">%d / %d</span>',
                               (($item->all_count-$item->error_count)>$item->sent_count)?'red':'green',$item->sent_count,$item->all_count-$item->error_count));
                  
                }
              ?>
            </td>
			      <td class="item">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><a href="<? echo($this->phpself.$this->page_name.'/test/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_test')); ?>"><img src="<? echo($this->base_url); ?>images/email.png" alt="<? echo($this->lang->line('admin_tpl_test')); ?>" width="16" height="16" /></a></td> 
                  <td width="10px" nowrap></td> 
                  <td><a href="<? echo($this->phpself.$this->page_name.'/edit/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_edit')); ?>"><img src="<? echo($this->base_url); ?>images/pencil.png" alt="<? echo($this->lang->line('admin_tpl_edit')); ?>" width="16" height="16" /></a></td> 
                  <td><a href="<? echo($this->phpself.$this->page_name.'/files/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_files')); ?>"><img src="<? echo($this->base_url); ?>images/files.png" alt="<? echo($this->lang->line('admin_tpl_files')); ?>" width="16" height="16" /></a></td>                        
                  <td><a href="<? echo($this->phpself.$this->page_name.'/del/'.$item->id); ?>" title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;"><img src="/images/delete.png" alt="<? echo($this->lang->line('admin_tpl_del')); ?>" width="16" height="16" /></a></td>
                </tr>
              </table>
			      </td>
          </tr>
          <? } ?>
          
          <? if ($timer) { ?>
            <script>
            $(document).ready(function() {

              var timerInterval = setInterval(function() {
                obj=document.getElementById('review_data');
                if (obj) {
                  decrease_counter(obj,timerInterval);
                }  
              },1000);
            });
            </script>
          <? } ?>
            
          <? if ($refresh) { ?>
            <script>
            $(document).ready(function() {

              setInterval(function() {
                document.location.href="<? echo($this->phpself.$this->page_name); ?>";  
              },60*1000);
            });
            </script>
          <? } ?>
      <? } else { ?>      
      <tr>
        <td class="item"><h4><? echo($this->lang->line('admin_tpl_not_found')); ?></h4></td>
      </tr>
      <? } ?>
    </table>
    <? echo(isset($pages)?$pages:''); ?>
	</td>
  </tr>	
</table>