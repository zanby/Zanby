<table cellspacing="0" cellpadding="0" class="znResult">
    <col width="100%" />
    <thead>
        <tr>
            <th class="znTLeft" colspan=2 id="ListOfPhrasesTitleBox">List of phrases - File : <b>{$fileName}</b></th>
        </tr>
    </thead>
    <tbody>
        {assign var=cvet value=""}
        {foreach from=$Messages  key='key' item='message'}
            {if $cvet=="znBG1"}{assign var=cvet value=""}
            {else}{assign var=cvet value="znBG1"}{/if}
            
            {assign var='isTranslated' value='true'}
            {foreach from=$LocalesList item='locale'}
                {if $locale != 'rss'}
                    {if !$translate->isTranslated($key, true, $locale) || isTranslated == 'false'}
                        {assign var='isTranslated' value='false'}
                    {/if}
                {/if}
            {/foreach}
            <tr>
                <td class="{$cvet} znTLeft">                    
                    {if $isTranslated == 'true'}
                        {$message|escape}
                    {else}
                        <font style="color:#FB9204">{$message|escape}</font>
                    {/if}
                </td>
                <td class="{$cvet} znTLeft" style="width:50px;"><a href="javascript:void(0);" onClick="xajax_editTranslateFile('{$file}', '{$key}'); return false;">EDIT</a></td>
            </tr>
        {foreachelse}
            <tr>
                <td class="{$cvet} znTLeft" colspan=2>
                &nbsp;
                </td>
            </tr>        
        {/foreach}
    </tbody>
</table>