<!-- form container -->
<div class="prClr3">
<table cellspacing="0" cellpadding="0" border="0" class="prForm">
	<col width="20%" />
    <col width="55%" />
    <col width="25%" />
    <tbody>
        <tr>
           <td class="prTRight">
               <label>{t}Name:{/t}</label>
           </td>
           <td class="prInnerLeft">
               {if $currentGroup->getId() == $venue->getOwnerId() && $venue->getOwnerType() == 'group' }
                   <a href="#" onclick="xajax_editVenue( '{$venue->getId()}', 'a' ); return false;">
                       {$venue->getName()|escape:"html"}
                   </a>
               {else}
                   <span>
                    {$venue->getName()|escape:"html"}
                   </span>
               {/if}
               <div>{$venue->getCategory()->getName()|escape:html}</div>
           </td>
           <td>
           </td>
        </tr>
        <tr>
           <td class="prTRight">
               <label>{t}Address:{/t}</label>
           </td>
           <td class="prInnerLeft">
               <span>
                   {if $venue->getAddress1()}{$venue->getAddress1()|escape:html}<br />{/if}
                   {if $venue->getAddress2()}{$venue->getAddress2()|escape:html}<br />{/if}
                   {$venue->getCity()->getState()->getCountry()->name|escape:html}<br />
                   {$venue->getCity()->getState()->name|escape:html}<br />
                   {$venue->getCity()->name|escape:html}<br />
                   {if $venue->getZipcode()} {$venue->getZipcode()}<br />{/if}
               </span>
           </td>
           <td>&#160;</td>
        </tr>
        {if $venue->getDescription()}
        <tr>
           <td class="prTRight">
               <label>{t}Description:{/t}</label>
           </td>
           <td class="prInnerLeft">
               <span>{$venue->getDescription()|escape:"html"|nl2br}</span>
           </td>
           <td>&nbsp;</td>
        </tr>
        {/if}
        {if $venue->getPhone()}
        <tr>
           <td class="prTRight">
               <label>{t}Phone:{/t}</label>
           </td>
           <td class="prInnerLeft">
               <span>{$venue->getPhone()|escape:html}</span>
           </td>
           <td>&nbsp;</td>
        </tr>
        {/if}
        {if $venue->getWebsite()}
        <tr>
           <td class="prTRight">
               <label>{t}Website:{/t}</label>
           </td>
           <td class="prInnerLeft">
               <a href="{$venue->getWebsite()|escape:html}">{$venue->getWebsite()|escape:html}</a>
           </td>
           <td>&nbsp;</td>
        </tr>
        {/if}
    </tbody>
</table>
<div class="prTCenter prInnerSmallBottom">{t var="in_button"}Change Venue{/t}{linkbutton color="blue" name=$in_button onclick="xajax_setVenue("|cat:$venue->getId()|cat:"); return false;"}</div>
</div>
<!-- /form container -->