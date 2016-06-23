<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</head>
<body>
<? if($data){ ?>
<table>
<tr>
<td align="left"><? echo($this->lang->line('admin_tpl_name')); ?></td>
<td align="center"><? echo($this->lang->line('admin_tpl_proportion_currency')); ?></td>
</tr>
<? foreach($data as $item){ ?>
<tr>
<td align="left"><? echo($item->name); ?></td>
<td align="left"><? echo($item->proportion_currency); ?></td>

</tr>
<? } ?>
</table>
<? } ?>
</body>
</html>