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
<td align="left"><? echo($this->lang->line('admin_dividend_name')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividend_ticker')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividend_sector')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividend_type')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividends')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividend_yield')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividend_close_date')); ?></td>
<td align="center"><? echo($this->lang->line('admin_dividend_price')); ?></td>
</tr>
<? foreach($data as $item){ ?>
<tr>
<td align="left"><? echo($item->name); ?></td>
<td align="left"><? echo($item->ticker); ?></td>
<td align="left"><? echo($item->sector); ?></td>
<td align="center"><? echo($item->dividend_type); ?></td>
<td align="center"><? echo($item->dividends); ?></td>
<td align="center"><? echo($item->dividend_yield); ?></td>
<td align="center"><? echo($item->close_date); ?></td>
<td align="center"><? echo($item->price); ?></td>
</tr>
<? } ?>
</table>
<? } ?>
</body>
</html>