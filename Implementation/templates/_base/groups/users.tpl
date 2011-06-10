{assign var = "foreach_name" value="users_"|cat:$tool}
{assign var = "text_name" value="text_"|cat:$tool}
{assign var = "id_name" value=$tool|cat:"_access"|cat:$maxValue}

<div id="{$tool}_access_div" {if $option != $maxValue}style="display:none;"{/if}>
<div id="{$tool}_access_error_div">
{form_errors_summary}
</div>
<ol class="prIndentLeft">
	<li>{$group->getHost()->getLogin()|escape:"html"}{t}(owner){/t}</li>
	{foreach item=u key=id name=$foreach_name from=$privileges->getUsersListByTool($tool)->returnAsAssoc()->getList()}
		<li id="{$id}" class="prIndentTopSmall">
            {$u|escape}
            <a href="javascript:void(0)" onclick="document.getElementById('{$id_name}').checked = true;xajax_privileges_user_delete('{$tool}', '{$id}', xajax.getFormValues('gpForm'));">
                {t}Delete{/t}
            </a>
        </li>
	{/foreach}
	<li class="prIndentTopSmall">
		<div>
			<div class="yui-skin-sam">
				<div class="yui-ac">
					{form_text name=$text_name value=$values.$tool|escape:"html" autocomplete="off" id=$text_name onfocus="document.getElementById('$id_name').checked = true;"}
					<div id="{$tool}_acLogins"></div>
				</div>
		   </div>
		</div>
	</li>
</ol>
<div class="prIndentLeft prIndentTopSmall"><a href="#" onclick="document.getElementById('{$id_name}').checked = true;xajax_privileges_user_add('{$tool}', xajax.getFormValues('gpForm'));return false;">{t}+Add User{/t}</a></div>
</div>
