<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head profile="http://gmpg.org/xfn/11">

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="<? echo($this->base_url); ?>favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<? echo($this->base_url); ?>css/main_admin.css" type="text/css" />
<title>popup</title>
</head>
<body class="popup">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top" align="center" class="content_top"><a class="button_update" href="javascript:void(0);" onclick="javascript:window.opener.location.href='<? echo($_SESSION['back_cat']); ?>'; window.close();"><? echo($this->lang->line('admin_tpl_close_and_update')); ?></a></td>
			</tr>
			<tr>
				<td valign="top" align="center" class="content">
				<? echo($content); ?>
				</td>
			</tr>
		</table>	
	</td>
  </tr>
</table>
</body>
</html>