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
<td align="left"><? echo($this->lang->line('admin_commodity_name')); ?></td>
<td align="center"><? echo($this->lang->line('admin_commodity_ticker')); ?></td>
<td align="center"><? echo($this->lang->line('admin_commodity_sector')); ?></td>
<td align="center"><? echo($this->lang->line('admin_commodity_price_current')); ?></td>
<td align="center"><? echo($this->lang->line('admin_commodity_price_fair')); ?></td>
<td align="center"><? echo($this->lang->line('admin_commodity_potential')); ?></td>
<td align="center"><? echo($this->lang->line('admin_commodity_recommendation')); ?></td>
</tr>
<? foreach($data as $item){ ?>
<tr>
<td align="left"><? echo($item->name); ?></td>
<td align="left"><? echo($item->ticker); ?></td>
<td align="center"><? echo($item->sector); ?></td>
<td align="center"><? echo($item->price_current); ?></td>
<td align="center"><? echo($item->price_fair); ?></td>
<td align="center"><? echo($item->potential); ?> %</td>
<td align="center"><? echo($item->recommendation); ?></td>
</tr>
<? } ?>
</table>
<? } ?>
</body>
</html>