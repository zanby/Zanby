{*popup_item*}
<div id="pricePanel">
    <table cellspacing="0" cellpadding="0" align="center">
        <col width="50%"/>
        <col width="25%" />
        <col width="25%" />
        <tbody>
            <tr>
                <td>
                    <strong>{t}Size of Group (in members){/t}</strong>
                </td>
                <td nowrap="nowrap">
                    <strong>{t}Monthly or {/t}</strong>
                </td>
                <td>
                    <strong>{t}Annually{/t}</strong>
                </td>
            </tr>
          {foreach item=p name='prices' from=$prices}
            <tr>
              {if $smarty.foreach.prices.iteration <= 2}
                <td class="groupsize"><strong>{$p.option}</strong></td>
                <td nowrap="nowrap"><strong>${$p.monthly|string_format:"%.2f"} {t}USD{/t}</strong></td>
                <td nowrap="nowrap"><strong>${$p.annualy|string_format:"%.2f"} {t}USD{/t}</strong></td>
              {else}
                <td class="groupsize">{$p.option}</td>
                <td nowrap="nowrap">${$p.monthly|string_format:"%.2f"} {t}USD{/t}</td>
                <td nowrap="nowrap">${$p.annualy|string_format:"%.2f"} {t}USD{/t}</td>
              {/if}
            </tr>
          {/foreach}
        </tbody>
    </table>
        <div class="prInnerTop prTCenter">    
		{t var="in_button"}Close{/t}    
		{linkbutton style="" name=$in_button onclick="popup_window.close(); return false;"}
	</div>
</div>
{*popup_item*}