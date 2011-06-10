{* -- OLD CODE. DON'T REMOVE -- strip}
<table>
  <tr>
    <td>
      <img class="image_thumb" src="{$gmapItem->getAvatar()->setWidth(37)->setHeight(38)->getImage($objUser)}" />
    </td>
    <td rowspan="2" style="padding-right:10px;">
      <strong>{$gmapItem->getName()|escape:html}</strong><br />
      <span style="font-size:14px;">{$gmapItem->getDescription()|escape:html}</span><br />
      <a{if $gmapMarker->getUrlTarget()} target="{$gmapMarker->getUrlTarget()}"{/if} href="{$gmapMarker->getUrl()}">{t}go to group{/t}</a>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
{/strip*}

{strip}
<table>
  <tr>
    <td style="padding-right:10px;">
      <strong>[*T*]</strong><br />
      <span style="font-size:14px;">[*D*]</span>
    </td>
  </tr>
  [*OS*][*DS*]
  <tr>
      <td><span style="font-size:14px;"><a href="javascript:void(0)" onclick="setClusteringZoomLevel(getwmObj());">{t}Zoom in to see markers.{/t}</a></span></td>
  </tr>


  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
{/strip}