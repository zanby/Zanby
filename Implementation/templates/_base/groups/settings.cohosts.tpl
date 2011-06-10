{literal}
	<script language="javascript">
	function addCoHost() {
		var cohost = document.getElementById("new_cohost").value;
		if ( cohost == "" ) {
			alert("Enter name of new co-host.");
			return false;
		}
		xajax_privileges_add_new_cohost(cohost);
	}
	function deleteCoHost(cohost_uid ) {
		xajax_privileges_delete_cohost(cohost_uid);
	}	
	</script>
{/literal}

{if $visibility_details == "cohosts"}<script>xajax_privileges_cohosts_show('{$groupId}');</script>
{else}
    {if $visibility == true}
        {form from=$form name="chForm" id="chForm" onsubmit="xajax_privileges_add_cohost(getElementById('cohost').value); return false;"}
		{form_errors_summary}
            <table class="prForm">
                <col width="35%" />
                <col width="65%" />                             
                <tbody>
                    <tr>
                        <td class="prInnerSmallLeft">
                            <!-- -->
                                <ol class="prIndentLeftLarge">
                                    <li>{t}{tparam value=$group->getHost()->getLogin()|escape:"html"}<span class="prText2 prIndentRightSmall">%s</span>(Owner){/t}</li>
                                    {foreach item=c key=cohost_id item=cohost_name name=cohost_name from=$cohosts}
                                        <li class="prIndentTopSmall"><span class="prText2">{$cohost_name|escape:"html"}</span> <a href="#" onclick="xajax_privileges_delete_cohost({$cohost_id}); return false;">{t}Delete{/t}</a></li>
                                    {/foreach}
                                    	<li class="prIndentTopSmall"><div class=" yui-skin-sam"><div class="yui-ac">
                                        {form_text id="cohost" name="cohost" value=$Login|escape:"html" autocomplete="off"}
                                        <div id="acCohost"></div>
                                    	</div></div>
										</li>
                                </ol>
                                <div class="prIndentLeftLarge"><a href="#" onclick="xajax_privileges_add_cohost(getElementById('cohost').value); return false;">+ {t}Add Co-host{/t}</a></div>
                            <!-- / -->
                        </td>
						<td>&#160;</td>
                    </tr>
                </tbody>
            </table>
        {/form}
    {/if}
{/if}
