<table class="prForm">
    <col width="20%" />
    <col width="80%" />
    {foreach from=$processesList name=processes item=process}
    <tr>
        <td class="prTRight"><label for"gallery_title">{$process.title}</label></td>
        <td>
             <h3>{$process.status}</h3>
        </td>
    </tr>
    {/foreach}                
</table>