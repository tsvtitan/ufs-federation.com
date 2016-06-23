<? if(isset($groups)&&isset($lang)) { ?>
<? foreach($groups as $g) { ?>
<? if($lang=='ru') { ?>
<br>
<p class="MsoNormal" style="margin-left:7.1pt"><b><span lang="EN-US" style="font-family:&quot;Bookman Old Style&quot;,&quot;serif&quot;;
mso-ansi-language:EN-US;mso-fareast-language:RU;mso-no-proof:yes">Торговые идеи на рынке <? echo((strtolower($g->name)=='еврооблигации')?'еврооблигаций':'рублевых облигаций') ?><o:p></o:p></span></b></p>
<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="1000" style="width:684.45pt;margin-left:4.65pt;border-collapse:collapse;mso-yfti-tbllook:
    1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">
  <tbody>
    <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:33.0pt">
      <td width="152" style="width: 114pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 33pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Выпуск<o:p></o:p></span></b></p>
      </td>
      <td width="127" style="width: 95pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 33pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">ISIN<o:p></o:p></span></b></p>
      </td>
      <td width="175" style="width: 131pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 33pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Рейтинг</span></b><b><span lang="EN-US" style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">   S&amp;P/Moody's/Fitch<o:p></o:p></span></b></p>
      </td>
      <td width="127" style="width: 95pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 33pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Доходность, %<o:p></o:p></span></b></p>
      </td>
      <td width="99" style="width: 74pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 33pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Цена закрытия, %<o:p></o:p></span></b></p>
      </td>
      <td width="291" style="width: 218pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 33pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Комментарий<o:p></o:p></span></b></p>
      </td>
    </tr>
    <? foreach($g->items as $i) { ?>
    <tr style="mso-yfti-irow:1;height:126.75pt">
      <td width="152" style="width: 114pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 126.75pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" style="margin-bottom: 0.0001pt;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><a href="<?=$i->url?>"><?=$i->name?><o:p></o:p></span></p>
      </td>
      <td width="127" style="width: 95pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 126.75pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->isin?><o:p></o:p></span></p>
      </td>
      <td width="175" style="width: 131pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 126.75pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">&nbsp;<?=$i->rating?><o:p></o:p></span></p>
      </td>
      <td width="127" style="width: 95pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 126.75pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->yield?><o:p></o:p></span></p>
      </td>
      <td width="99" style="width: 74pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 126.75pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->price?><o:p></o:p></span></p>
      </td>
      <td width="291" style="width: 218pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 126.75pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" style="margin-bottom: 0.0001pt; text-align: justify;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->description?><o:p></o:p></span></p>
      </td>
    </tr>
    <? } ?>
  </tbody>
</table> 
<? } elseif(($lang=='en')||($lang=='de')) { ?>
<br>
<p class="MsoNormal" style="margin-left:7.1pt"><b><span lang="EN-US" style="font-family:&quot;Bookman Old Style&quot;,&quot;serif&quot;;
mso-ansi-language:EN-US;mso-fareast-language:RU;mso-no-proof:yes">Trading Ideas in the <? echo((strtolower($g->name)=='eurobonds')?'Eurobond Market':'Ruble Bond Market') ?><o:p></o:p></span></b></p>
<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="1000" style="width:684.45pt;margin-left:4.65pt;border-collapse:collapse;mso-yfti-tbllook:
    1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">
  <tbody>
    <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:41.25pt">
      <td width="181" style="width: 135.45pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 41.25pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Issue<o:p></o:p></span></b></p>
      </td>
      <td width="121" style="width: 91pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 41.25pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">ISIN<o:p></o:p></span></b></p>
      </td>
      <td width="163" style="width: 122pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 41.25pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span lang="EN-US" style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Rating   S&amp;P/Moody's/Fitch<o:p></o:p></span></b></p>
      </td>
      <td width="88" style="width: 66pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 41.25pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Yield, %<o:p></o:p></span></b></p>
      </td>
      <td width="85" style="width: 64pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 41.25pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Closing Price, %<o:p></o:p></span></b></p>
      </td>
      <td width="275" style="width: 206pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 41.25pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><b><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;">Comment<o:p></o:p></span></b></p>
      </td>
    </tr>
    <? foreach($g->items as $i) { ?>
    <tr style="mso-yfti-irow:1;height:105.0pt">
      <td width="181" style="width: 135.45pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 105pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" style="margin-bottom: 0.0001pt;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><a href="<?=$i->url?>"><?=$i->name?><o:p></o:p></span></p>
      </td>
      <td width="121" style="width: 91pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 105pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->isin?><o:p></o:p></span></p>
      </td>
      <td width="163" style="width: 122pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 105pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->rating?><o:p></o:p></span></p>
      </td>
      <td width="88" style="width: 66pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 105pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->yield?><o:p></o:p></span></p>
      </td>
      <td width="85" style="width: 64pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 105pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" align="center" style="margin-bottom: 0.0001pt; text-align: center;"><span style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->price?><o:p></o:p></span></p>
      </td>
      <td width="275" style="width: 206pt; border-style: none none solid; border-bottom-color: windowtext; border-bottom-width: 1pt; background-color: white; padding: 0cm 5.4pt; height: 105pt; background-position: initial initial; background-repeat: initial initial;">
      <p class="MsoNormal" style="margin-bottom: 0.0001pt; text-align: justify;"><span lang="EN-US" style="font-size: 10pt; font-family: 'Bookman Old Style', serif;"><?=$i->description?><o:p></o:p></span></p>
      </td>
    </tr>
    <? } ?>
  </tbody>
</table>
<? }}} ?>