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
<td>№</td>
<td align="left"><? echo($this->lang->line('admin_tpl_name')); ?></td>
<td align="center"><? echo($this->lang->line('admin_share_market_model_portfolio_maturity_date')); ?></td>
<td align="center"><? echo($this->lang->line('admin_share_market_model_portfolio_price_starting')); ?></td>
<td align="center"><? echo($this->lang->line('admin_share_market_model_portfolio_price_current')); ?></td>
<td align="center"><? echo($this->lang->line('admin_share_market_model_portfolio_nominal_volume')); ?></td>
<td align="center"><? echo($this->lang->line('admin_share_market_model_portfolio_cost')); ?></td>
</tr>
<? foreach($data as $item){ ?>
<tr>
<td align="left"><? echo($item->set_number); ?></td>
<td align="left"><? echo($item->name); ?></td>
<td align="center"><? echo($item->maturity_date); ?></td>
<td align="center"><? echo($item->price_starting); ?></td>
<td align="center"><? echo($item->price_current); ?></td>
<td align="center"><? echo($item->nominal_volume); ?></td>
<td align="center"><? echo($item->cost); ?></td>
</tr>
<? } ?>
</table>
<? } ?>
</body>
</html>