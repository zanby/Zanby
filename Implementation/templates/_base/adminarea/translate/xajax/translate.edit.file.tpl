<form name="editPhraseForm" id="editPhraseForm" onsubmit="TranslateApplication.submitEditPhraseForm(); return false;" method='post'>
<input type='hidden' name='messageKey' id='messageKey' value={$messageKey}>
<input type='hidden' name='messageFile' id='messageFile' value={$file}>
    <table cellspacing="0" cellpadding="0" class="znResult">
        <col width="100%" />
        <thead>
            <tr>
                <th class="znTLeft">Edit translations</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="znTRight">
                    <a href="javascript:void(0);" onclick="xajax_showTranslateFile('{$file}')">RETURN TO LIST OF PHRASES</a> or <input type='submit' name='btnSubmit' value='Save Changes'>
                </td>
            </tr>
            <tr>
                <td>
                    {assign var=cvet value="znBG1"}
                    <table cellspacing="0" cellpadding="0" style="width:100%">
                        <tr>
                            <td class="{$cvet} znTLeft"><b>{$LocalesNamesList.$defaultLocale} (default) : </b></td>                        
                        </tr>
                        <tr>
                            <td class="{$cvet} znTLeft">
                                <textarea style="width:100%" rows=5 name="translateMessage_{$defaultLocale}">{$translate->_($messageKey, $defaultLocale)}</textarea>
                            </td>                        
                        </tr> 
                        {foreach from=$LocalesList item='locale'}
                            {if $locale !== 'rss' && $locale != $defaultLocale}
                                {if $cvet=="znBG1"}{assign var=cvet value=""}
                                {else}{assign var=cvet value="znBG1"}{/if}

                                <tr>
                                    <td class="{$cvet} znTLeft"><b>{$LocalesNamesList.$locale} : </b></td>                        
                                </tr>
                                <tr>
                                    <td class="{$cvet} znTLeft">
                                        {if $translate->isTranslated($messageKey, $locale)}
                                            <textarea style="width:100%" rows=5 name="translateMessage_{$locale}">{$translate->_($messageKey, $locale)}</textarea>
                                        {else}
                                            <textarea style="width:100%" rows=5 name="translateMessage_{$locale}"></textarea>
                                        {/if}                                    
                                    </td>                        
                                </tr> 
                            {/if}
                        {/foreach}
                    </table>
                </td>
            </tr>
            <tr>
                <td class="znTRight">
                    <a href="javascript:void(0);" onclick="xajax_showTranslateFile('{$file}')">RETURN TO LIST OF PHRASES</a> or <input type='submit' name='btnSubmit' value='Save Changes'>
                </td>
            </tr>
        </tbody>
    </table>
</form>