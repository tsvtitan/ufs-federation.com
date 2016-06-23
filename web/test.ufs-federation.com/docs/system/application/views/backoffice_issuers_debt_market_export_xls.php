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
<td colspan="15">Дата экспорта: <? echo(date('d.m.Y')); ?></td>
</tr>
<tr>
<td></td>
<td align="left"><? echo($this->lang->line('admin_issuers_debt_market_name')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_maturity_date')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_isin')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_currency')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_closing_price')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_income')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_duration')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_rate')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_next_coupon')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_volume')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_payments_per_year')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_rating_sp')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_rating_moodys')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_rating_fitch')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_industry')); ?></td>
<td align="center"><? echo($this->lang->line('admin_issuers_debt_market_country')); ?></td>
</tr>
<? foreach($data as $item){ ?>
<tr>
<td></td>
<td align="left"><? echo($item->name); ?></td>
<td align="center"><? echo($item->maturity_date); ?></td>
<td align="center"><? echo($item->isin); ?></td>
<td align="center"><? echo($item->currency); ?></td>
<td align="center"><? echo($item->closing_price); ?></td>
<td align="center"><? echo($item->income); ?></td>
<td align="center"><? echo($item->duration); ?></td>
<td align="center"><? echo($item->rate); ?></td>
<td align="center"><? echo($item->next_coupon); ?></td>
<td align="center"><? echo($item->volume); ?></td>
<td align="center"><? echo($item->payments_per_year); ?></td>
<td align="center"><? echo($item->rating_sp); ?></td>
<td align="center"><? echo($item->rating_moodys); ?></td>
<td align="center"><? echo($item->rating_fitch); ?></td>
<td align="center"><? echo($item->industry); ?></td>
<td align="center"><? echo($item->country); ?></td>
</tr>
<? } ?>
</table>
<? } ?>
</body>
</html>