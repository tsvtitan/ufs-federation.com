<script language="javascript" type="text/javascript">
	function Valid_Form()
	{
        if(document.getElementById('input_title').value=='')
		{
			alert('<? echo($this->lang->line('admin_tpl_error')); ?>: <? echo($this->lang->line('admin_pages_popup_error_name')); ?>');
			return false;
		}else{
			return true;
		}
	}
</script>
<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_url').$this->lang->line('admin_tpl_url_text')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="url" value="<? echo(isset($data->url)?$data->url:''); ?>" class="text" maxlength="250" /></td>
		  </tr>
          <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date')); ?></h5></td>
          </tr>
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                        <td>
                            <select name="date[day]" class="date">
                                <? foreach($date->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="date[month]" class="date">
                                <? foreach($date->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="date[year]" class="date">
                                <? foreach($date->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_category')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
              <? if(@$data->is_delete=='yes' or !isset($data->is_delete)){
                  $disabled_cat='';
                 }else{ ;
                  $disabled_cat=' disabled="disabled"'; ?>
                <input type="hidden" name="select[cat_id]" value="<? echo($this->cat_id.'|'.$data->parent_id); ?>" class="hidden" />
                <span style="color:red;"><? echo($this->lang->line('admin_pages_disabled_cat_text')); ?></span><br>
              <? } ?>
                <select name="select[cat_id]" class="select"<? echo($disabled_cat); ?>>
					<? foreach($menu as $item){ ?>
                        <option<? echo($item->sel); ?> value="<? echo($item->id.'|0'); ?>"><? echo($item->name); ?></option>
                        <? if(isset($item->sub)){ ?>
                            <? foreach($item->sub as $val){ ?>
                            <option<? echo($val->sel); ?> value="<? echo($item->id.'|'.$val->id); ?>">&nbsp;&nbsp;&bull;&nbsp;<? echo($val->name); ?></option>
                            <? } ?>
                        <? } ?>
					<? } ?>
				</select>
			</td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_contact')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <select name="select[contact_id]" class="select">
                <option><? echo(""); ?></option>
					<? foreach($contacts as $item){ ?>
                      <option<? echo($item->sel); ?> value="<? echo($item->id); ?>"><? echo($item->name); ?></option>
					<? } ?>
				</select>
			</td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_slider_link_type')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
                <select name="select[slider_link_type]" class="select">
                <option><? echo(""); ?></option>
					<? foreach($slider_link_types as $item){ ?>
                      <option<? echo($item->sel); ?> value="<? echo($item->id); ?>"><? echo($item->name); ?></option>
					<? } ?>
				</select>
			</td>
		  </tr>
          <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_redirect')); ?></h5></td>
		  </tr>
		  <tr>
		    <td> 
		      <table>
		        <tr> 
		  	      <td class="item"><input type="text" id="redirect" name="text[redirect]" value="<? echo(isset($data->redirect)?$data->redirect:''); ?>" class="text" /></td>
		  	      <td class="item"><input style="width:50px;" type="text" id="redirect" name="text[redirect_code]" value="<? echo(isset($data->redirect_code)?$data->redirect_code:''); ?>" class="text" /></td>
                </tr>		  	      
		  	  </table>
		  	</td>  
		  </tr>
		  <tr>
			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[name]" value="<? echo(isset($data->name)?$data->name:''); ?>" class="text" /></td>
		  </tr>
          <? if(@$data->is_home=='yes'){ ?>
           <? for($i=1;$i<=6;$i++){ 
               $str='img_'.$i; 
               $promo_title='promo_title_'.$i; 
               $promo_url='promo_url_'.$i;
               $promo_text='promo_text_'.$i; 
               $promo_hide='promo_hide_'.$i; ?>
              <tr>
                <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_promo_box')); ?> <? echo($i); ?></h5></td>
              </tr>
              <tr>
                <td class="item">
                    <h5><? echo($this->lang->line('admin_tpl_name')); ?></h5>
                    <input type="text" name="text[<? echo($promo_title); ?>]" value="<? echo(isset($data->$promo_title)?$data->$promo_title:''); ?>" class="text" maxlength="250" />
                </td>
              </tr>
              <tr>
                <td class="item">
                    <h5><? echo($this->lang->line('admin_tpl_url')); ?></h5>
                    <input type="text" name="text[<? echo($promo_url); ?>]" value="<? echo(isset($data->$promo_url)?$data->$promo_url:''); ?>" class="text" maxlength="250" />
                </td>
              </tr>
              <tr>
                <td class="item">
                    <h5><? echo($this->lang->line('admin_tpl_content')); ?></h5>
                    <textarea class="textarea" style="height: 100px;" name="text[<? echo($promo_text); ?>]"><? echo(isset($data->$promo_text)?$data->$promo_text:''); ?></textarea>
                </td>
              </tr>
              <tr>
                <td class="item">
                    <h5><? echo($this->lang->line('admin_tpl_img')); ?></h5>
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td class="item"><input type="file" name="<? echo($str); ?>" value="" class="file" /></td>
                        <td class="item">	
                            <? if(isset($data->$str)){ ?>
                                <? if(!empty($data->$str)){ ?>
                                  <img width="200" src="/upload/home/small/<? echo($data->$str); ?>">
                                <? } ?>
                                <input type="hidden" name="img_old[<? echo($i); ?>]" value="<? echo($data->$str); ?>" class="hidden" />
                            <? } ?>
                        </td>
                      </tr>	
                    </table>
                </td>
              </tr>
              <tr>
                <td class="item">
                    <input type="checkbox" class="checkbox" name="select[<? echo($promo_hide); ?>]" value="1"<? echo(($data->$promo_hide=='yes')?' checked="checked"':''); ?> /> - <? echo($this->lang->line('admin_tpl_hide_box')); ?>
                </td>
              </tr>
           <? } ?>
              <tr>
                <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_promo_slider_speed')); ?></h5></td>
              </tr>
              <tr>
                <td class="item"><input type="text" name="int[promo_slider_speed]" value="<? echo(isset($data->promo_slider_speed)?$data->promo_slider_speed:1); ?>" class="text" maxlength="2" style="width: 20px;" /></td>
              </tr>
          <? }else{ ?>
           <? if(@$data->sub_page_type!='news' and 
                 @$data->sub_page_type!='press_about_us' and 
                 @$data->sub_page_type!='reporting' and 
                 @$data->sub_page_type!='forms_of_documents'){ ?>
		   <tr>
			 <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_content')); ?></h5></td>
		   </tr>
		   <tr>
		  	 <td class="item">
			 <?php
			 include_once($_SERVER['DOCUMENT_ROOT'].'/'.$this->subdir.'44b8baa73b59b3cc090/fckeditor.php') ;
			
				$oFCKeditor = new FCKeditor('clear[content]') ;
				$oFCKeditor->BasePath = '/'.$this->subdir.'44b8baa73b59b3cc090/';
				$oFCKeditor->Config['EnterMode'] = 'br';
				$oFCKeditor->Config['EditorAreaCSS'] = '/'.$this->subdir.'css/fckeditor.css';
				$oFCKeditor->ToolbarSet = 'Default';
				$oFCKeditor->Width = '100%';
				$oFCKeditor->Height = '500';
				$oFCKeditor->Value = isset($data->content) ? $data->content : ''; 
				$oFCKeditor->Create();
			 ?>
			 </td>
		   </tr>
           <? } ?>
          <? } ?>
		  <tr>
			<td class="header_title"><h5>Meta Title</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[meta_title]"><? echo(isset($data->meta_title)?$data->meta_title:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Keywords</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[meta_keywords]"><? echo(isset($data->meta_keywords)?$data->meta_keywords:''); ?></textarea></td>
		  </tr>
		  <tr>
			<td class="header_title"><h5>Meta Description</h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><textarea class="textarea" style="height: 50px;" name="text[meta_description]"><? echo(isset($data->meta_description)?$data->meta_description:''); ?></textarea></td>
		  </tr>
          <? if(@$data->is_home=='yes'){ ?>
            <input type="hidden" name="select[is_hide]" value="1" class="hidden" />
          <? }else{ ?>
		  <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_link_to_page')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" name="text[link_to_page]" value="<? echo(isset($data->link_to_page)?$data->link_to_page:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
        <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_duration')); ?></h5></td>
		  </tr>
		  <tr>
        <td class="item">
          <table>
            <tr>
              <td class="item"><input style="width:100px;" type="text" name="int[bubble_duration]" value="<? echo(isset($data->bubble_duration)?$data->bubble_duration:''); ?>" class="text" /></td>
              <td class="item">/</td>
              <td class="item"><input style="width:50px;" type="text" name="int[bubble_count]" value="<? echo(isset($data->bubble_count)?$data->bubble_count:''); ?>" class="text" /></td>
            </tr>
          </table>
        </td>
		  </tr>
      <tr>
  			<td class="header_title"><h5><? echo($this->lang->line('admin_tpl_company')); ?></h5></td>
		  </tr>
		  <tr>
		    <td class="item">
          <select style="width: 150px;" name="select[company]" >
            <option value="UFS"<? echo(($data->company=='UFS')?' selected':''); ?>>UFS IC</option>
            <option value="PREMIER"<? echo(($data->company=='PREMIER')?' selected':''); ?>>Premier</option>
          </select>
        </td>  
		  </tr>
      <tr>
        <td class="header_title"></td>
		  </tr>
          <tr>
		  	<td class="item">
                <input type="checkbox" class="checkbox" name="select[is_hide]" value="1"<? echo(isset($data->is_hide)?$data->is_hide:''); ?> /> - <? echo($this->lang->line('admin_tpl_hide_in_menu')); ?>
                 </td>
          </tr>
          <tr>
		  	<td class="item">
                <input type="checkbox" class="checkbox" name="select[main]" value="1"<? echo((isset($data->main) && ($data->main=='1'))?' checked':''); ?> /> - <? echo($this->lang->line('admin_tpl_show_in_submenu')); ?>
                        </td>
          </tr>
          <? } ?>
		  <tr>
            <td class="header_title"><h5>Тип страницы <small>(предназначено для разработчиков)</small></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[sub_page_type]', $data->sub_page_type_array, $data->sub_page_type, 'class="select"', true)); ?>
            </td>
		  </tr>
		  <tr>
            <td class="header_title"><h5>Тип блока Индексы <small>(предназначено для разработчиков)</small></h5></td>
		  </tr>
          <tr>
		  	<td class="item">
            <? echo(form_dropdown('select[indexes_box]', $data->indexes_box_array, $data->indexes_box, 'class="select"', true)); ?>
            </td>
		  </tr>
                      <tr>
			<td class="header_title"></td>
		  </tr>
		  <tr>
			<td class="submit">
                <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                    <tr>
                        <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
                        <td><a href="<? echo($this->phpself.$this->page_name.'/cat/'.(isset($data->cat_id)?$data->cat_id:$this->cat_id)); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
                        <td width="100%">&nbsp;</td>
                       <? if(isset($data->id)){ ?>
                        <? if($data->is_delete=='yes'){ ?>
                        <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$data->cat_id.'/'.$data->id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
                        <? } ?>
                       <? } ?>
                    </tr>
                </table>
            </td>
		  </tr>
     
                  
        </table>
        <? if(isset($data->id)){ ?>
		<input type="hidden" name="id" value="<? echo($data->id); ?>" class="hidden" />
        <? } ?>
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>