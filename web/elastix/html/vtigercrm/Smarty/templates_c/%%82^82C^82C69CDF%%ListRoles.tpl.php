<?php /* Smarty version 2.6.18, created on 2014-09-02 13:44:39
         compiled from ListRoles.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'ListRoles.tpl', 91, false),)), $this); ?>

<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<style type="text/css">
a.x {
		color:black;
		text-align:center;
		text-decoration:none;
		padding:5px;
		font-weight:bold;
}
	
a.x:hover {
		color:#333333;
		text-decoration:underline;
		font-weight:bold;
}

ul {color:black;}	 
	
.drag_Element{
	position:relative;
	left:0px;
	top:0px;
	padding-left:5px;
	padding-right:5px;
	border:0px dashed #CCCCCC;
	visibility:hidden;
}

#Drag_content{
	position:absolute;
	left:0px;
	top:0px;
	padding-left:5px;
	padding-right:5px;
	background-color:#000066;
	color:#FFFFFF;
	border:1px solid #CCCCCC;
	font-weight:bold;
	display:none;
}
</style>
<script>
 if(!e)
  window.captureEvents(Event.MOUSEMOVE);

//  window.onmousemove= displayCoords;
//  window.onclick = fnRevert;
  
   function displayCoords(event) 
	 {
				var move_Element = document.getElementById('Drag_content').style;
				if(!event){
						move_Element.left = e.pageX +'px' ;
						move_Element.top = e.pageY+10 + 'px';	
				}
				else{
						move_Element.left = event.clientX +'px' ;
					    move_Element.top = event.clientY+10 + 'px';	
				}
	}
  
	  function fnRevert(e)
	  {
		  	if(e.button == 2){
				document.getElementById('Drag_content').style.display = 'none';
				hideAll = false;
				parentId = "Head";
	    		parentName = "DEPARTMENTS";
			    childId ="NULL";
	    		childName = "NULL";
			}
	}

</script>

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<div align=center>
<br>
	
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "SetMenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="<?php echo vtiger_imageurl('ico-roles.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['MOD']['LBL_ROLES']; ?>
" width="48" height="48" border=0 title="<?php echo $this->_tpl_vars['MOD']['LBL_ROLES']; ?>
"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a> > <?php echo $this->_tpl_vars['MOD']['LBL_ROLES']; ?>
</b></td>
				</tr>
				<tr>
					<td valign=top class="small"><?php echo $this->_tpl_vars['MOD']['LBL_ROLE_DESCRIPTION']; ?>
</td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong><?php echo $this->_tpl_vars['MOD']['LBL_ROLE_HIERARCHY_TREE']; ?>
</strong></td>
						<td class="small" align=right>&nbsp;</td>
					</tr>
					</table>

					<div id='RoleTreeFull'  onMouseMove="displayCoords(event)"> 
                			        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RoleTree.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		                	</div>

					
					
					
					
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr><td class="small" nowrap align=right><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></td></tr>
					</table>
				</td>
				</tr>
				</table>
			
			
			
			</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
		
	</div>

</td>
        <td valign="top"><img src="<?php echo vtiger_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
   </tr>
</tbody>
</table>
	<div id="Drag_content">&nbsp;</div>

<script language="javascript" type="text/javascript">
	var hideAll = false;
	var parentId = "";
	var parentName = "";
	var childId ="NULL";
	var childName = "NULL";

		
	
	 function get_parent_ID(obj,currObj)
	 {
			var leftSide = findPosX(obj);
    			var topSide = findPosY(obj);
			var move_Element = document.getElementById('Drag_content');
		 	childName  = document.getElementById(currObj).innerHTML;
			childId = currObj;
			move_Element.innerHTML = childName;
			move_Element.style.left = leftSide + 15 + 'px';
			move_Element.style.top = topSide + 15+ 'px';
			move_Element.style.display = 'block';
			hideAll = true;	
	}
	
	function put_child_ID(currObj)
	{
			var move_Element = $('Drag_content');
	 		parentName  = $(currObj).innerHTML;
			parentId = currObj;
			move_Element.style.display = 'none';
			hideAll = false;	
			if(childId == "NULL")
			{
//				alert("Please Select the Node");
				parentId = parentId.replace(/user_/gi,'');
				window.location.href="index.php?module=Settings&action=RoleDetailView&parenttab=Settings&roleid="+parentId;
			}
			else
			{
				childId = childId.replace(/user_/gi,'');
				parentId = parentId.replace(/user_/gi,'');
				new Ajax.Request(
  					'index.php',
				        {queue: {position: 'end', scope: 'command'},
					        method: 'post',
					        postBody: 'module=Users&action=UsersAjax&file=RoleDragDrop&ajax=true&parentId='+parentId+'&childId='+childId,
						onComplete: function(response) {
							if(response.responseText != alert_arr.ROLE_DRAG_ERR_MSG)
							{
						                $('RoleTreeFull').innerHTML=response.responseText;
						                hideAll = false;
							        parentId = "";
						                parentName = "";
						                childId ="NULL";
								childName = "NULL";
						        }
						        else
						                alert(response.responseText);
			                        }
				        }
				);
			}
	}

	function fnVisible(Obj)
	{
			if(!hideAll)
				document.getElementById(Obj).style.visibility = 'visible';
	}

	function fnInVisible(Obj)
	{
		document.getElementById(Obj).style.visibility = 'hidden';
	}

	


function showhide(argg,imgId)
{
	var harray=argg.split(",");
	var harrlen = harray.length;	
	var i;
	for(i=0; i<harrlen; i++)
	{
		var x=document.getElementById(harray[i]).style;
        	if (x.display=="none")
        	{
           		x.display="block";
			document.getElementById(imgId).src="<?php echo vtiger_imageurl('minus.gif', $this->_tpl_vars['THEME']); ?>
";
         	}
        	else
		{
			x.display="none";
			document.getElementById(imgId).src="<?php echo vtiger_imageurl('plus.gif', $this->_tpl_vars['THEME']); ?>
";
		}
	}
}



</script>