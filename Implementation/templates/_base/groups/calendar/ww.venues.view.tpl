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
           <td>
               <a href="#" onclick="xajax_editWWVenue('{$venue->getId()}', 'wa'); return false;">
                   {$venue->getName()|escape:"html"}
               </a>
               <div>{$venue->getCategory()->getName()|escape:html}</div>
           </td>
           <td>
		   		{t var="in_button"}Change Venue{/t}
               {linkbutton color="blue" name=$in_button onclick="xajax_setWWVenue("|cat:$venue->getId()|cat:"); return false;"}
           </td>
        </tr>
        {if $venue->getDescription()}
        <tr>
           <td class="prTRight">
               <label>{t}Description:{/t}</label>
           </td>
           <td>
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
           <td>&nbsp;</td>
        </tr>
        {/if}
    </tbody>
</table>
</div>
<!-- /form container -->