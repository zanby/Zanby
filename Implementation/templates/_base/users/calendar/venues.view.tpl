<!-- form container -->
<div class="prClr3">
<table cellspacing="0" cellpadding="0" border="0" class="prForm">
	<col width="30%" />
    <col width="40%" />
    <col width="30%" />

    <tbody>
        <tr>
           <td class="prTRight">
               <label>{t}Name:{/t}</label>
           </td>
           <td>
               {if $user->getId() == $venue->getOwnerId() && $venue->getOwnerType() == "user"}
               <a href="#" onclick="xajax_editVenue( {$venue->getId()}, 'a' ); return false;">
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
           <td>
               <span>
                   {if $venue->getAddress1()}<div>{$venue->getAddress1()|escape:html}</div>{/if}
                   {if $venue->getAddress2()}<div class="prIndentTopSmall">{$venue->getAddress2()|escape:html}</div>{/if}
                   <div class="prIndentTopSmall">{$venue->getCity()->getState()->getCountry()->name|escape:html}</div>
                   <div class="prIndentTopSmall">{$venue->getCity()->getState()->name|escape:html}</div>
                   <div class="prIndentTopSmall">{$venue->getCity()->name|escape:html}</div>
                   {if $venue->getZipcode()} <div class="prIndentTopSmall">{$venue->getZipcode()}</div>{/if}
               </span>
           </td>
           <td></td>
        </tr>
        {if $venue->getDescription()}
        <tr>
           <td class="prTRight">
               <label>{t}Description:{/t}</label>
           </td>
           <td>
               <span">{$venue->getDescription()|escape:"html"|nl2br}</span>
           </td>
           <td></td>
        </tr>
        {/if}
        {if $venue->getPhone()}
        <tr>
           <td class="prTRight">
               <label>{t}Phone:{/t}</label>
           </td>
           <td>
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
           <td>
               <a href="{$venue->getWebsite()|escape:html}">{$venue->getWebsite()|escape:html}</a>
           </td>
           <td></td>
        </tr>
        {/if}
    </tbody>
</table>
<div class="prTCenter prInnerSmallBottom prInnerTop">
{t var='button'}Change Venue{/t}
{linkbutton color="blue" name=$button onclick="xajax_setVenue("|cat:$venue->getId()|cat:"); return false;"}</div>
</div>
<!-- /form container -->