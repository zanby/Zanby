{if $bu_view == 'expanded'}
    {form from=$form onsubmit="xajax_privacy_bu_block(xajax.getFormValues('buForm')); return false;" id="buForm"}
    {form_errors_summary}
        <table class="prForm">
            <col width="40%" />
            <col width="60%" />				
            <tbody>
                <tr>
                    <td>
                        <p class="prDefaultText">{t}Users that are blocked will not be able to see your profile, view your content or find you in search results.{/t}</p>
                    </td>
                    <td>
                        <!-- -->
                        <ol class="prIndentLeftLarge">
                            {if $privacy}
                                {foreach item=u key=id name=users from=$privacy->getBlockList()->setOrder('login')->returnAsAssoc()->getList()}
                                    <li{if !$smarty.foreach.users.first} class="prIndentTop"{/if}>{$u|escape} <a href="#null" onclick="xajax_privacy_bu_unblock({$id});">{t}Unblock{/t}</a></li>
                                {/foreach}
                            {/if}
                            <li{if $smarty.foreach.users.iteration > 0} class="prIndentTop"{/if}>
                                <div class="yui-skin-sam"><div class="yui-ac">
                                    {form_text name="bu_login" value=$bu_login|escape autocomplete="off" id="buLogin"}
                                     <div id="acLogins"></div>
                                </div></div>
                            </li>
                        </ol>
                        <!-- / -->
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
					{t var='button_01'}Block{/t}
					{form_submit name="form_submit" value=$button_01}</td>
                </tr>
            </tbody>
        </table>
    {/form}
{/if}