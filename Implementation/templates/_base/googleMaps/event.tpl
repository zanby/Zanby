{* -- OLD CODE. DON'T REMOVE -- strip}
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td align="center" width="100">
      {if $gmapItem->getPictureId()}<img class="image_thumb" src="{$gmapItem->getEventPicture()->setWidth(37)->setHeight(38)->getImage($objUser)}" />{else}<img src="{$AppTheme->images}/decorators/event/fakeImage.gif" />{/if}
    </td>
    <td align="left" style="padding-right:10px;" rowspan="2">
      <strong>{$gmapItem->getTitle()|escape:html}</strong><br />
      <span style="font-size:14px;">{$gmapItem->getDescription()|escape:html}</span><br />
      <a{if $gmapMarker->getUrlTarget()} target="{$gmapMarker->getUrlTarget()}"{/if} href="{$gmapMarker->getUrl()}">{t}go to event{/t}</a>
      
      {if $Warecorp_Nda_Item->hasEvent($gmapItem)}<br>
            {assign var="nda" value= $Warecorp_Nda_Item->hasEvent($gmapItem)}
            <a{if $gmapMarker->getUrlTarget()} target="{$gmapMarker->getUrlTarget()}"{/if} href="{$BASE_URL}/en/event/{$nda->getName()|replace:" ":"-"|escape:html}/">{$nda->getName()|escape:html}</a>      
      {/if}
    </td>
  </tr>
  <tr>
    <td align="center" style="font-size:13px;">{$gmapItem->displayDate('list.view', $objUser)}</td>
  </tr>
</table>
{/strip*}

{strip}
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td align="center" width="100">
      <img class="image_thumb" src="[*A*]" />
    </td>
    <td align="left" style="padding-right:10px;" rowspan="2">
      <strong>[*T*]</strong><br />
      <span style="font-size:14px;">[*D*]</span><br />
      <a target="[*T1*]" href="[*U*]">{t}go to event{/t}</a>
      [*N*]
    </td>
  </tr>
  <tr>
    <td align="center" style="font-size:13px;">[*DT*]</td>
  </tr>
</table>
{/strip}
