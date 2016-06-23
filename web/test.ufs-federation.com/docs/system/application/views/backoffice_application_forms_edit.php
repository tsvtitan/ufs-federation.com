<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><h2><? echo($this->tpl_header); ?></h2></td>
	</tr>
  <tr>
    <td valign="top" align="center">
	<form action="<? echo($this->uri->uri_string); ?>" method="post" onsubmit="return Valid_Form();" enctype="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" align="center" class="form_content edit">
      <tr>			
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_num').' '.(isset($data->num)?$data->num:'')); ?></h5></td>
		  </tr>
		      <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_date_create')); ?></h5></td>
          </tr> 
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                   <tr>
                        <td>
                            <select name="created[day]" class="date">
                                <? foreach($created->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="created[month]" class="date">
                                <? foreach($created->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="created[year]" class="date">
                                <? foreach($created->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <select name="created[hour]" class="date">
                                <? foreach($created->hour as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>:</td>
                        <td>
                            <select name="created[minute]" class="date">
                                <? foreach($created->minute as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        <td>:</td>
                        <td>
                            <select name="created[seconds]" class="date">
                                <? foreach($created->seconds as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_last_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[last_name]" value="<? echo(isset($data->last_name)?$data->last_name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_first_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[first_name]" value="<? echo(isset($data->first_name)?$data->first_name:''); ?>" class="text" /></td>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_middle_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[middle_name]" value="<? echo(isset($data->middle_name)?$data->middle_name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_email')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[email]" value="<? echo(isset($data->email)?$data->email:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_phone')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[phone]" value="<? echo(isset($data->phone)?$data->phone:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_fax')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[fax]" value="<? echo(isset($data->fax)?$data->fax:''); ?>" class="text" /></td>
		  </tr>
		      <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_birth_date')); ?></h5></td>
          </tr> 
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                   <tr>
                        <td>
                            <select name="birth_date[day]" class="date">
                                <? foreach($birth_date->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="birth_date[month]" class="date">
                                <? foreach($birth_date->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="birth_date[year]" class="date">
                                <? foreach($birth_date->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_birth_place')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[birth_place]" value="<? echo(isset($data->birth_place)?$data->birth_place:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_inn')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[inn]" value="<? echo(isset($data->inn)?$data->inn:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_citizen')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[citizen]" value="1"<? echo(isset($data->citizen)&&($data->citizen=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_citizen_on')); ?></label></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_passport_number')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[passport_number]" value="<? echo(isset($data->passport_number)?$data->passport_number:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_passport_authority')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[passport_authority]" value="<? echo(isset($data->passport_authority)?$data->passport_authority:''); ?>" class="text" /></td>
		  </tr>
		      <tr>
            <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_passport_date')); ?></h5></td>
          </tr> 
          <tr>
            <td class="item">
                <table border="0" cellspacing="0" cellpadding="0" align="left">
                   <tr>
                        <td>
                            <select name="passport_date[day]" class="date">
                                <? foreach($passport_date->day as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td> 
                        <td>
                            <select name="passport_date[month]" class="date">
                                <? foreach($passport_date->month as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>.</td>
                        <td>
                            <select name="passport_date[year]" class="date">
                                <? foreach($passport_date->year as $item){ ?>
                                    <option value="<? echo($item->id); ?>"<? echo($item->select); ?>><? echo($item->id); ?></option>
                                <? } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_passport_code')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[passport_code]" value="<? echo(isset($data->passport_code)?$data->passport_code:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_zipcode')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[residence_zipcode]" value="<? echo(isset($data->residence_zipcode)?$data->residence_zipcode:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_region')); ?></h5></td>
		  </tr>
		  <tr>
		    <td class="item">
		      <select id="input_title"name="text[residence_region_id]">
		        <option value=""<? echo(!isset($data->residence_region_id)?' selected':'') ?>><? echo('выберите') ?></option>
		        <? if(isset($residence_regions)) { 
		             foreach ($residence_regions as $p) {
		               echo(sprintf('<option value="%s"%s>%s</option>',$p->region_id,($data->residence_region_id==$p->region_id)?' selected':'',$p->name));	
		             }		 
		           }
		        ?>
		      </select>
		    </td>  
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_locality')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[residence_locality]" value="<? echo(isset($data->residence_locality)?$data->residence_locality:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_street')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[residence_street]" value="<? echo(isset($data->residence_street)?$data->residence_street:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_house')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[residence_house]" value="<? echo(isset($data->residence_house)?$data->residence_house:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_building')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[residence_building]" value="<? echo(isset($data->residence_building)?$data->residence_building:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_residence_flat')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[residence_flat]" value="<? echo(isset($data->residence_flat)?$data->residence_flat:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_as_residence')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[post_as_residence]" value="1"<? echo(isset($data->post_as_residence)&&($data->post_as_residence=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_post_as_residence_on')); ?></label></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_zipcode')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[post_zipcode]" value="<? echo(isset($data->post_zipcode)?$data->post_zipcode:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_region')); ?></h5></td>
		  </tr>
		  <tr>
		    <td class="item">
		      <select id="input_title"name="text[post_region_id]">
		        <option value=""<? echo(!isset($data->post_region_id)?' selected':'') ?>><? echo('выберите') ?></option>
		        <? if(isset($post_regions)) { 
		             foreach ($post_regions as $p) {
		               echo(sprintf('<option value="%s"%s>%s</option>',$p->region_id,($data->post_region_id==$p->region_id)?' selected':'',$p->name));	
		             }		 
		           }
		        ?>
		      </select>
		    </td>  
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_locality')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[post_locality]" value="<? echo(isset($data->post_locality)?$data->post_locality:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_street')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[post_street]" value="<? echo(isset($data->post_street)?$data->post_street:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_house')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[post_house]" value="<? echo(isset($data->post_house)?$data->post_house:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_building')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[post_building]" value="<? echo(isset($data->post_building)?$data->post_building:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_post_flat')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[post_flat]" value="<? echo(isset($data->post_flat)?$data->post_flat:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_recipient')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_recipient]" value="<? echo(isset($data->bank_recipient)?$data->bank_recipient:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_current_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_current_account]" value="<? echo(isset($data->bank_current_account)?$data->bank_current_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_personal_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_personal_account]" value="<? echo(isset($data->bank_personal_account)?$data->bank_personal_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_name]" value="<? echo(isset($data->bank_name)?$data->bank_name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_region')); ?></h5></td>
		  </tr>
		  <tr>
		    <td class="item">
		      <select id="input_title"name="text[bank_region_id]">
		        <option value=""<? echo(!isset($data->bank_region_id)?' selected':'') ?>><? echo('выберите') ?></option>
		        <? if(isset($bank_regions)) { 
		             foreach ($bank_regions as $p) {
		               echo(sprintf('<option value="%s"%s>%s</option>',$p->region_id,($data->bank_region_id==$p->region_id)?' selected':'',$p->name));	
		             }		 
		           }
		        ?>
		      </select>
		    </td>  
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_town')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_town]" value="<? echo(isset($data->bank_town)?$data->bank_town:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_inn')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_inn]" value="<? echo(isset($data->bank_inn)?$data->bank_inn:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_bic')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_bic]" value="<? echo(isset($data->bank_bic)?$data->bank_bic:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_bank_corr_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[bank_corr_account]" value="<? echo(isset($data->bank_corr_account)?$data->bank_corr_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[out_bank]" value="1"<? echo(isset($data->out_bank)&&($data->out_bank=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_out_bank_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_recipient')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_recipient]" value="<? echo(isset($data->out_bank_recipient)?$data->out_bank_recipient:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_current_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_current_account]" value="<? echo(isset($data->out_bank_current_account)?$data->out_bank_current_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_personal_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_personal_account]" value="<? echo(isset($data->out_bank_personal_account)?$data->out_bank_personal_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_card_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_card_account]" value="<? echo(isset($data->out_bank_card_account)?$data->out_bank_card_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_iban')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_iban]" value="<? echo(isset($data->out_bank_iban)?$data->out_bank_iban:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_name]" value="<? echo(isset($data->out_bank_name)?$data->out_bank_name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_country')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_country]" value="<? echo(isset($data->out_bank_country)?$data->out_bank_country:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_region')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_region]" value="<? echo(isset($data->out_bank_region)?$data->out_bank_region:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_town')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_town]" value="<? echo(isset($data->out_bank_town)?$data->out_bank_town:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_inn')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_inn]" value="<? echo(isset($data->out_bank_inn)?$data->out_bank_inn:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_bic')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_bic]" value="<? echo(isset($data->out_bank_bic)?$data->out_bank_bic:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_corr_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_corr_account]" value="<? echo(isset($data->out_bank_corr_account)?$data->out_bank_corr_account:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_corr_bank_name')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_corr_bank_name]" value="<? echo(isset($data->out_bank_corr_bank_name)?$data->out_bank_corr_bank_name:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_corr_bank_location')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_corr_bank_location]" value="<? echo(isset($data->out_bank_corr_bank_location)?$data->out_bank_corr_bank_location:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_corr_bank_swift')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_corr_bank_swift]" value="<? echo(isset($data->out_bank_corr_bank_swift)?$data->out_bank_corr_bank_swift:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_out_bank_swift')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[out_bank_swift]" value="<? echo(isset($data->out_bank_swift)?$data->out_bank_swift:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_public_face')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[public_face]" value="1"<? echo(isset($data->public_face)&&($data->public_face=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_public_face_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_public_country')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[public_country]" value="<? echo(isset($data->public_country)?$data->public_country:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_public_organization')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[public_organization]" value="<? echo(isset($data->public_organization)?$data->public_organization:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_public_position')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[public_position]" value="<? echo(isset($data->public_position)?$data->public_position:''); ?>" class="text" /></td>
		  </tr>
		  </tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_official')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[official]" value="1"<? echo(isset($data->official)&&($data->official=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_official_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_official_organization')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[official_organization]" value="<? echo(isset($data->official_organization)?$data->official_organization:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_official_position')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[official_position]" value="<? echo(isset($data->official_position)?$data->official_position:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_laundering')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[laundering]" value="1"<? echo(isset($data->laundering)&&($data->laundering=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_laundering_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_keyword')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[keyword]" value="<? echo(isset($data->keyword)?$data->keyword:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_forts')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[forts]" value="1"<? echo(isset($data->forts)&&($data->forts=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_forts_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_special_account')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[special_account]" value="1"<? echo(isset($data->special_account)&&($data->special_account=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_special_account_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_internet_trading')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[internet_trading]" value="1"<? echo(isset($data->internet_trading)&&($data->internet_trading=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_internet_trading_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_terminal_count')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><input type="text" id="input_title" name="text[terminal_count]" value="<? echo(isset($data->terminal_count)?$data->terminal_count:''); ?>" class="text" /></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_orders_in_office')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[orders_in_office]" value="1"<? echo(isset($data->orders_in_office)&&($data->orders_in_office=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_orders_in_office_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_orders_by_mail')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[orders_by_mail]" value="1"<? echo(isset($data->orders_by_mail)&&($data->orders_by_mail=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_orders_by_mail_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_orders_by_phone')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[orders_by_phone]" value="1"<? echo(isset($data->orders_by_phone)&&($data->orders_by_phone=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_orders_by_phone_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_orders_by_email')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[orders_by_email]" value="1"<? echo(isset($data->orders_by_email)&&($data->orders_by_email=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_orders_by_email_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_delivery')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item">
		  	  <select name="string[delivery]">
		  	    <option value=""><? echo($this->lang->line('admin_tpl_delivery_none')); ?></option>
		  	    <option value="1"<? echo(($data->delivery=='1')?' selected':'') ?>><? echo($this->lang->line('admin_tpl_delivery_courier')); ?></option>
		  	    <option value="2"<? echo(($data->delivery=='2')?' selected':'') ?>><? echo($this->lang->line('admin_tpl_delivery_agent')); ?></option>
		  	    <option value="3"<? echo(($data->delivery=='3')?' selected':'') ?>><? echo($this->lang->line('admin_tpl_delivery_letter')); ?></option>
		  	  </select>
		  	</td>
		  </tr>
		  <tr>
			  <td class="header_title"><h5><? echo($this->lang->line('admin_tpl_agree')); ?></h5></td>
		  </tr>
		  <tr>
		  	<td class="item"><label><input style="margin-right: 5px;" type="checkbox" id="input_title" name="int[agree]" value="1"<? echo(isset($data->agree)&&($data->agree=='1')?' checked':''); ?> class="text" /><? echo($this->lang->line('admin_tpl_agree_on')); ?></label></td>
		  </tr>
		  <tr>
			  <td class="header_title"></td>
		  </tr>
		  <tr>
			<td class="submit">
         <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
           <tr>
             <td><input type="image" src="<? echo($this->base_url); ?>images/save1.gif" /></td>
             <td><a href="<? echo($this->phpself.$this->page_name); ?>"><img src="<? echo($this->base_url); ?>images/cancel.gif"></a></td>
             <td width="100%">&nbsp;</td>
             <? if(isset($data->application_form_id)){ ?>
             <td><a title="<? echo($this->lang->line('admin_tpl_del')); ?>" onclick="return confirm('<? echo($this->lang->line('admin_tpl_del')); ?>?'); false;" href="<? echo($this->phpself.$this->page_name.'/del/'.$data->application_form_id); ?>"><img alt="<? echo($this->lang->line('admin_tpl_del')); ?>" src="<? echo($this->base_url); ?>images/del_btn.png"></a></td>
             <? } ?>
           </tr>
         </table>
       </td>
		  </tr> 
    </table>
    <? if(isset($data->application_form_id)){ ?>
		<input type="hidden" name="application_form_id" value="<? echo($data->application_form_id); ?>" class="hidden" />
     <? } ?>
		<input type="hidden" name="submit" value="submit" class="hidden" />
	</form>
	</td>
  </tr>	
</table>