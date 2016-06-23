<?php /* Smarty version 2.6.18, created on 2014-09-09 07:45:57
         compiled from Popup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'Popup.tpl', 87, false),array('modifier', 'vtlib_purify', 'Popup.tpl', 112, false),array('function', 'html_options', 'Popup.tpl', 98, false),)), $this); ?>
<script>
var image_pth = '<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
';

function showAllRecords()
{
        modname = document.getElementById("relmod").name;
        idname= document.getElementById("relrecord_id").name;
        var locate = location.href;
        url_arr = locate.split("?");
        emp_url = url_arr[1].split("&");
        for(i=0;i< emp_url.length;i++)
        {
                if(emp_url[i] != '')
                {
                        split_value = emp_url[i].split("=");
                        if(split_value[0] == modname || split_value[0] == idname )
                                emp_url[i]='';
                        else if(split_value[0] == "fromPotential" || split_value[0] == "acc_id")
                                emp_url[i]='';

                }
        }
        correctUrl =emp_url.join("&");
        Url = "index.php?"+correctUrl;
        return Url;
}

//function added to get all the records when parent record doesn't relate with the selection module records while opening/loading popup.
function redirectWhenNoRelatedRecordsFound()
{
        var loadUrl = showAllRecords();
        window.location.href = loadUrl;
}
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['THEME_PATH']; ?>
style.css">
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/Inventory.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/json.js"></script>
<!-- vtlib customization: Javascript hook -->
<script language="JavaScript" type="text/javascript" src="include/js/vtlib.js"></script>
<!-- END -->
<script language="JavaScript" type="text/javascript" src="include/js/<?php  echo $_SESSION['authenticated_user_language']; ?>.lang.js?<?php  echo $_SESSION['vtiger_version']; ?>"></script>
<script language="JavaScript" type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/<?php echo $this->_tpl_vars['MODULE']; ?>
.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script type="text/javascript">
function add_data_to_relatedlist(entity_id,recordid,mod) {
        opener.document.location.href="index.php?module=<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
&action=updateRelations&destination_module="+mod+"&entityid="+entity_id+"&parentid="+recordid+"&return_module=<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
&return_action=<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
";
}

function set_focus() {
	$('search_txt').focus();
}
</script>

<body  onload=set_focus() class="small" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bottommargin=0 rightmargin=0>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mailClient mailClientBg">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<?php if ($this->_tpl_vars['recid_var_value'] != ''): ?>
                            <td class="moduleName" width="80%" style="padding-left:10px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>
&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_RELATED_TO']; ?>
&nbsp;<?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['PARENT_MODULE']]; ?>
</td>
                    <?php else: ?>
                            <?php if ($this->_tpl_vars['RECORD_ID']): ?>
	                            <td class="moduleName" width="80%" style="padding-left:10px;"><a href="javascript:;" onclick="window.history.back();"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>
</a> > <?php echo $this->_tpl_vars['PRODUCT_NAME']; ?>
</td>
							<?php else: ?>
	                            <td class="moduleName" width="80%" style="padding-left:10px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>
</td>
							<?php endif; ?>
                    <?php endif; ?>
					<td  width=30% nowrap class="componentName" align=right><?php echo $this->_tpl_vars['APP']['VTIGER']; ?>
</td>
				</tr>
			</table>
			<div id="status" style="position:absolute;display:none;right:135px;top:15px;height:27px;white-space:nowrap;"><img src="<?php echo vtiger_imageurl('status.gif', $this->_tpl_vars['THEME']); ?>
"></div>
			<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
				<tr>
					<td style="padding:10px;" >
						<form name="basicSearch" action="index.php" onsubmit="callSearch('Basic');return false;">
						<table width="100%" cellpadding="5" cellspacing="0">
						<tr>
							<td width="20%" class="dvtCellLabel"><img src="<?php echo vtiger_imageurl('basicSearchLens.gif', $this->_tpl_vars['THEME']); ?>
"></td>
							<td width="30%" class="dvtCellLabel"><input type="text" name="search_text" id="search_txt" class="txtBox"> </td>
							<td width="30%" class="dvtCellLabel"><b><?php echo $this->_tpl_vars['APP']['LBL_IN']; ?>
</b>&nbsp;
								<select name ="search_field" class="txtBox">
											 <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['SEARCHLISTHEADER']), $this);?>

								</select>
								<input type="hidden" name="searchtype" value="BasicSearch">
								<input type="hidden" name="module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
								<input type="hidden" name="action" value="Popup">
								<input type="hidden" name="query" value="true">
								<input type="hidden" name="select_enable" id="select_enable" value="<?php echo $this->_tpl_vars['SELECT']; ?>
">
								<input type="hidden" name="curr_row" id="curr_row" value="<?php echo $this->_tpl_vars['CURR_ROW']; ?>
">
								<input type="hidden" name="fldname_pb" value="<?php echo $this->_tpl_vars['FIELDNAME']; ?>
">
								<input type="hidden" name="productid_pb" value="<?php echo $this->_tpl_vars['PRODUCTID']; ?>
">
								<input name="popuptype" id="popup_type" type="hidden" value="<?php echo $this->_tpl_vars['POPUPTYPE']; ?>
">
								<input name="recordid" id="recordid" type="hidden" value="<?php echo $this->_tpl_vars['RECORDID']; ?>
">
								<input name="record_id" id="record_id" type="hidden" value="<?php echo $this->_tpl_vars['RECORD_ID']; ?>
">
								<input name="return_module" id="return_module" type="hidden" value="<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
">
								<input name="from_link" id="from_link" type="hidden" value="<?php echo vtlib_purify($_REQUEST['fromlink']['value']); ?>
">
								<input name="maintab" id="maintab" type="hidden" value="<?php echo $this->_tpl_vars['MAINTAB']; ?>
">
								<input type="hidden" id="relmod" name="<?php echo $this->_tpl_vars['mod_var_name']; ?>
" value="<?php echo $this->_tpl_vars['mod_var_value']; ?>
">
                                <input type="hidden" id="relrecord_id" name="<?php echo $this->_tpl_vars['recid_var_name']; ?>
" value="<?php echo $this->_tpl_vars['recid_var_value']; ?>
">
																<?php if ($_REQUEST['form'] == 'vtlibPopupView'): ?>
									<input name="form"  id="popupform" type="hidden" value="<?php echo vtlib_purify($_REQUEST['form']); ?>
">
									<input name="forfield"  id="forfield" type="hidden" value="<?php echo vtlib_purify($_REQUEST['forfield']); ?>
">
									<input name="srcmodule"  id="srcmodule" type="hidden" value="<?php echo vtlib_purify($_REQUEST['srcmodule']); ?>
">
									<input name="forrecord"  id="forrecord" type="hidden" value="<?php echo vtlib_purify($_REQUEST['forrecord']); ?>
">
								<?php endif; ?>
								<?php if ($_REQUEST['currencyid'] != ''): ?>
									<input type="hidden" name="curr_row" id="currencyid" value="<?php echo vtlib_purify($_REQUEST['currencyid']); ?>
">
								<?php endif; ?>
															</td>
							<td width="20%" class="dvtCellLabel">
								<input type="button" name="search" value=" &nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SEARCH_NOW_BUTTON']; ?>
&nbsp; " onClick="callSearch('Basic');" class="crmbutton small create">
							</td>
						</tr>
						 <tr>
							<td colspan="4" align="center">
								<table width="100%" class="small">
								<tr>	
									<?php echo $this->_tpl_vars['ALPHABETICAL']; ?>

								</tr>
								</table>
							</td>
						</tr>
						</table>
						</form>
					</td>
				</tr>
				<?php if ($this->_tpl_vars['recid_var_value'] != ''): ?>
                                <tr>
                                        <td align="right"><input id="all_contacts" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>
" accessKey="" class="crmbutton small edit" value="<?php echo $this->_tpl_vars['APP']['SHOW_ALL']; ?>
&nbsp;<?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE']]; ?>
" LANGUAGE=javascript onclick="window.location.href=showAllRecords();" type="button"  name="button"></td>
                                </tr>
                                <?php endif; ?>
			</table>

			<div id="ListViewContents">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "PopupContents.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>
		</td>
	</tr>
	
</table>
</body>
<script>
var gPopupAlphaSearchUrl = '';
var gsorder ='';
var gstart ='';
function callSearch(searchtype)
{
    gstart='';
    for(i=1;i<=26;i++)
    {
        var data_td_id = 'alpha_'+ eval(i);
        getObj(data_td_id).className = 'searchAlph';
    }
    gPopupAlphaSearchUrl = '';
    search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
    search_txt_val= encodeURIComponent(document.basicSearch.search_text.value.replace(/\'/,"\\'"));
    var urlstring = '';
    if(searchtype == 'Basic')
    {
	urlstring = 'search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val;
    }
	popuptype = $('popup_type').value;
	act_tab = $('maintab').value;
	urlstring += '&popuptype='+popuptype;
	urlstring += '&maintab='+act_tab;
	urlstring = urlstring +'&query=true&file=Popup&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=<?php echo $this->_tpl_vars['MODULE']; ?>
Ajax&ajax=true&search=true';
	urlstring +=gethiddenelements();
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
				method: 'post',
				postBody: urlstring,
				onComplete: function(response) {
					$("status").style.display="none";
					$("ListViewContents").innerHTML= response.responseText;
				}
			}
		);
}	
function alphabetic(module,url,dataid)
{
    gstart='';
    document.basicSearch.search_text.value = '';	
    for(i=1;i<=26;i++)
    {
	var data_td_id = 'alpha_'+ eval(i);
	getObj(data_td_id).className = 'searchAlph';
    }
    getObj(dataid).className = 'searchAlphselected';
    gPopupAlphaSearchUrl = '&'+url;	
    var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true&search=true&"+url;
    urlstring +=gethiddenelements();
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;
    $("status").style.display="inline";
    new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {
                                	$("status").style.display="none";
									$("ListViewContents").innerHTML= response.responseText;
				}
			}
		);
}
function gethiddenelements()
{
	gstart='';
	var urlstring=''	
	if(getObj('select_enable').value != '')
		urlstring +='&select=enable';	
	if(document.getElementById('curr_row').value != '')
		urlstring +='&curr_row='+document.getElementById('curr_row').value;	
	if(getObj('fldname_pb').value != '')
		urlstring +='&fldname='+getObj('fldname_pb').value;	
	if(getObj('productid_pb').value != '')
		urlstring +='&productid='+getObj('productid_pb').value;	
	if(getObj('recordid').value != '')
		urlstring +='&recordid='+getObj('recordid').value;
	if(getObj('relmod').value != '')
                urlstring +='&'+getObj('relmod').name+'='+getObj('relmod').value;
    if(getObj('relrecord_id').value != '')
            urlstring +='&'+getObj('relrecord_id').name+'='+getObj('relrecord_id').value;
	
	// vtlib customization: For uitype 10 popup during paging
	if(document.getElementById('popupform'))
		urlstring +='&form='+encodeURIComponent(getObj('popupform').value);
	if(document.getElementById('forfield'))
		urlstring +='&forfield='+encodeURIComponent(getObj('forfield').value);
	if(document.getElementById('srcmodule'))
		urlstring +='&srcmodule='+encodeURIComponent(getObj('srcmodule').value);
	if(document.getElementById('forrecord'))
		urlstring +='&forrecord='+encodeURIComponent(getObj('forrecord').value);
	// END
		
	if(document.getElementById('currencyid') != null && document.getElementById('currencyid').value != '')
		urlstring +='&currencyid='+document.getElementById('currencyid').value;

	var return_module = document.getElementById('return_module').value;
	if(return_module != '')
		urlstring += '&return_module='+return_module;
	return urlstring;
}
																									
function getListViewEntries_js(module,url)
{
	gstart="&"+url;

	popuptype = document.getElementById('popup_type').value;
        var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true&"+url;
    	urlstring +=gethiddenelements();
	
	<?php if (! $this->_tpl_vars['RECORD_ID']): ?>
		search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
		search_txt_val=document.basicSearch.search_text.value;
		if(search_txt_val != '')
			urlstring += '&query=true&search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val;
	<?php endif; ?>
	if(gPopupAlphaSearchUrl != '')
		urlstring += gPopupAlphaSearchUrl;	
	else
		urlstring += '&popuptype='+popuptype;	
	
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;

	urlstring += (gsorder !='') ? gsorder : '';
	$("status").style.display = "";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {
                                        $("ListViewContents").innerHTML= response.responseText;
									$("status").style.display = "none";
				}
			}
		);
}

function getListViewSorted_js(module,url)
{
	gsorder=url;
        var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true"+url;
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;
	urlstring += (gstart !='') ? gstart : '';
	$("status").style.display = "";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {
                                        $("ListViewContents").innerHTML= response.responseText;
									$("status").style.display = "none";
				}
			}
		);
}

var product_labelarr = {
	CLEAR_COMMENT:'<?php echo $this->_tpl_vars['APP']['LBL_CLEAR_COMMENT']; ?>
',
	DISCOUNT:'<?php echo $this->_tpl_vars['APP']['LBL_DISCOUNT']; ?>
',
	TOTAL_AFTER_DISCOUNT:'<?php echo $this->_tpl_vars['APP']['LBL_TOTAL_AFTER_DISCOUNT']; ?>
',
	TAX:'<?php echo $this->_tpl_vars['APP']['LBL_TAX']; ?>
',
	ZERO_DISCOUNT:'<?php echo $this->_tpl_vars['APP']['LBL_ZERO_DISCOUNT']; ?>
',
	PERCENT_OF_PRICE:'<?php echo $this->_tpl_vars['APP']['LBL_OF_PRICE']; ?>
',
	DIRECT_PRICE_REDUCTION:'<?php echo $this->_tpl_vars['APP']['LBL_DIRECT_PRICE_REDUCTION']; ?>
'
};

</script>