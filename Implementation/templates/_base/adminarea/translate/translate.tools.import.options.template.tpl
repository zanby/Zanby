<td colspan="3" class="znTRight znNoPadding">
    <div style="width:450px; float:right;padding:2px;">
    <form name="importForm" id="importForm" method="post" action="" enctype="multipart/form-data">
    <input type='hidden' name='handle' value='1'>
    <input type='hidden' name='file' value='{$file}'>
    <table cellspacing="0" cellpadding="0" style="text-align:left; width:100%">
        <tr>
            <td colspan="2" class="znNoPadding znWidgetInner14">
                <div>Please choose langueage(s) you want to import</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="znNoPadding">
                <table cellspacing="0" cellpadding="0" class="znMyDocsTable2" style="text-align:left;">
                    {section name=sec1 loop=$lstLocalesSize start=0 step=1}
                    <tr>
                        <td class="znNoPadding znWidgetInner14">
                            <div>
                            {assign var='locale' value=$lstLocales[0][$smarty.section.sec1.index]}
                            {if $locale}                                
                                <input type='checkbox' id="languages_{$locale}" name='languages[]' value={$locale} {if in_array($locale, $lstSelectedLocales) && $locale != $defLocale && !$allLocales}checked{/if}> <label for="languages_{$locale}">{$lstLocalesNames[$locale]}</label>
                            {else}
                            {/if}
                            </div>
                        </td>
                        <td class="znNoPadding znWidgetInner14">
                            <div>
                            {assign var='locale' value=$lstLocales[1][$smarty.section.sec1.index]}
                            {if $locale}                                
                                <input type='checkbox' id="languages_{$locale}" name='languages[]' value={$locale} {if in_array($locale, $lstSelectedLocales) && $locale != $defLocale && !$allLocales}checked{/if}> <label for="languages_{$locale}">{$lstLocalesNames[$locale]}</label>
                            {else}
                            {/if}
                            </div>
                        </td>
                        <td class="znNoPadding znWidgetInner14">
                            <div>
                            {assign var='locale' value=$lstLocales[2][$smarty.section.sec1.index]}
                            {if $locale}                                
                                <input type='checkbox' id="languages_{$locale}" name='languages[]' value={$locale} {if in_array($locale, $lstSelectedLocales) && $locale != $defLocale && !$allLocales}checked{/if}> <label for="languages_{$locale}">{$lstLocalesNames[$locale]}</label>
                            {else}
                            {/if}
                            </div>
                        </td>
                    </tr>
                    {/section}
                    {if  $allLocales}
                    <tr>
                        <td colspan=3 class="znNoPadding znWidgetInner14">
                            <input type='checkbox' id="check_all" name='check_all' onclick="TranslateApplication.handleCheckAllLocales();"> <label for="check_all">Check All</label>
                            <p class="znTip">Note: this will replace data for all languages. Make sure your file is complete: empty entries for the language will clear translated text.</p>
                        </td>
                    </tr>           
                    {/if}
                </table
            </td>
        </tr>
        <tr>
            <td colspan="2" class="znNoPadding znWidgetInner14">
                <input type='file' name='fileField' id='fileField'>&nbsp;<input type='button' name='btnImportFile' value='Import file' onclick='TranslateApplication.handleImportFile(); return false;'> or <a href="javascript:void(0)" onclick="xajax_showTranslateFile(TranslateApplication.workTextNode.data.filename)">Cancel</a>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="znNoPadding znWidgetInner14" id="ExportImportToolsErrors" style="display:none"></td>
        </tr>

    </table>
    </form>
    </div>
</td>