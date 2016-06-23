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
<td align="left"><? echo($this->lang->line('admin_issuers_debt_market_issue')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_isin')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_price_currency')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_price_fair')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_potential')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_recommendation')); ?></td>
</tr>
<? foreach($data as $item){ ?>
<tr>
<td align="left"><? echo($item->ticker); ?></td>
<td align="center"><? echo($item->isin); ?></td>
<td align="center"><? echo($item->price_currency); ?></td>
<td align="center"><? echo($item->price_fair); ?></td>
<td align="center"><? echo($item->potential); ?> %</td>
<td align="center"><? echo($item->recommendation); ?></td>
</tr>
<? } ?>
</table>
<? } ?>
</body>
</html>