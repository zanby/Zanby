{if $visibility_details == "accountcancel"}
	<script>xajax_settings_accountCancel_show();</script>
{else}
    {if $visibility == true}
        <!-- form begin -->
        {form from=$form id="acForm" name="acForm"}
		{form_errors_summary}
            <table class="prForm">
                <col width="45%" />
                <col width="55%" />               
                <tbody>
                    <tr>
                        <td>{form_checkbox name="confirm" value="1" checked=0}<label for="confirm">{t} {tparam value=$SITE_NAME_AS_STRING}Yes, I want to resign from %s.{/t}</label></td>
                        <td>
						{t var='button_01'}Permanently resign and erase all my account information{/t}
						{linkbutton name=$button_01 onclick="xajax_settings_accountCancel_save(xajax.getFormValues('acForm')); return false;"}</td>
                    </tr>
                </tbody>
            </table>
        {/form}
        <!-- form end -->
    {/if}
{/if}
