<form method="post" action="/mobile/<?=$func?>">
<? if ($func=='auth') { ?>
  <input name="madeBy" placeholder="madeBy" value="Apple"/><br>
  <input name="deviceModel" placeholder="deviceModel" value="iPhohe 5"/><br>
  <input name="os" placeholder="os" value="iOS"/><br>
  <!--<input name="version" placeholder="version" value="5.1"/><br>-->
  <input name="screenSize" placeholder="960x480" value="960x480"/><br>
  <input name="id" placeholder="unique ID" value="UKNOWN"/><br>
<? } elseif ($func=='getCategories') { ?>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getNews') { ?>
  <input name="categoryID" placeholder="categoryID" value=""/><br>
  <input name="subcategoryID" placeholder="subcategoryID" value=""/><br>
  <input name="newsID" placeholder="newsID" value=""/><br>
  <input name="timestamp" placeholder="timestamp" value="<? echo(time()) ?>"/><br>
  <input name="limitDateTime" placeholder="limitDateTime" value=""/><br>
  <input name="offset" placeholder="offset" value=""/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getDatesOfNews') { ?>
  <input name="categoryID" placeholder="categoryID" value=""/><br>
  <input name="subcategoryID" placeholder="subcategoryID" value=""/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getTableView') { ?>
  <input name="subcategoryID" placeholder="subcategoryID" value="17"/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getBranches') { ?>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getGroups') { ?>
  <input name="subcategoryID" placeholder="subcategoryID" value="13"/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getActivities') { ?>
  <input name="categoryID" placeholder="categoryID" value="18"/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='getHtml') { ?>
  <input name="categoryID" placeholder="categoryID" value="27"/><br>
  <input name="subcategoryID" placeholder="subcategoryID" value=""/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='qrcode') { ?>
  <input name="text" placeholder="text" value=""/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } elseif ($func=='promotion') { ?>
  <input name="promotionID" placeholder="promotionID" value=""/><br>
  <input name="accepted" placeholder="accepted" value="false"/><br>
  <input name="token" placeholder="DA8A14A03E1ACEB543184B7F0E777BD8" value="DA8A14A03E1ACEB543184B7F0E777BD8"></br>
<? } ?>
  <input type="submit"/>
</form>