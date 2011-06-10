	<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/yui-autocomplete.css" media="screen" />
{literal}
	<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
    <!-- Dependencies --> 
    <script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script> 
    <!-- OPTIONAL: Connection (required only if using XHR DataSource) --> 
    <script type="text/javascript" src="/js/yui/connection/connection-min.js"></script> 
    <!-- OPTIONAL: Animation (required only if enabling animation) --> 
	<script type="text/javascript" src="/js/yui/animation/animation-min.js"></script> 
	<!-- Source file --> 
	<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script> 
	<script type="text/javascript" src="/js/autocomplete_xajax_datasource.js"></script> 
	<!-- OPTIONAL: Reload basic autocomplete functions for correct view  --> 
	<script type="text/javascript" src="/js/autocomplete_reload_logins.js"></script>
    <script type="text/javascript">
    
        function convertSelectedOptionsToString(select_id, separator, join_separator)
        {
            if (typeof separator == "undefined") separator = ";";
            if (typeof join_separator == "undefined") join_separator = " ";
            var select = document.getElementById(select_id);
            if(!select) {
                alert("Element with id='" + select_id + "' doesn't exist");
                return false;
            }
            var array_options = new Array();
            for (var i = 0; i < select.length; i++) {
                if (select.options[i].selected == true) {
                         array_options[array_options.length] = select.options[i].value + separator;
                }
            }
            if ( array_options.length > 0 ) return array_options.join(join_separator);
            else return '';
        }
        
        var m_fCallBackToAutocomplete;
        var m_oParent;
        var m_sQuery;
        
        function doQueryXajax(fCallBack, oParent, sQuery)
        {
            m_fCallBackToAutocomplete = fCallBack;
            m_oParent = oParent;
            m_sQuery = sQuery;
            xajax_load_contact_list(sQuery, YAHOO.util.Dom.get('target_emails').value, "autocompleteCallback");
        }
                                    
        function autocompleteCallback(sResponse)
        {
            if (sResponse.constructor != Array)
            {
                sResponse = new Array();
            }
            m_fCallBackToAutocomplete(m_sQuery, sResponse, m_oParent);
        }
        var myDataSource = new YAHOO.widget.DS_XAJAX();
        function getListObjects(){
            var eventObjectsColl = YAHOO.util.Dom.getElementsByClassName('mail-list-object-hidden', 'input');
			var eventObjectsValues = new Array();
			if ( eventObjectsColl && eventObjectsColl.length ) {
				for (var i = 0; i < eventObjectsColl.length; i++ ) {
					eventObjectsValues[eventObjectsValues.length] = eventObjectsColl[i].value;
				}
			}
			return eventObjectsValues;
        }
        function getGroupObjects(){
            var eventObjectsColl = YAHOO.util.Dom.getElementsByClassName('mail-group-object-hidden', 'input');
			var eventObjectsValues = new Array();
			if ( eventObjectsColl && eventObjectsColl.length ) {
				for (var i = 0; i < eventObjectsColl.length; i++ ) {
					eventObjectsValues[eventObjectsValues.length] = eventObjectsColl[i].value;
				}
			}
			return eventObjectsValues;
        }

	</script>
{/literal}
{form id=frm from=$form enctype="multipart/form-data"}
	
	<!-- =========================================== -->
 
		
				<!-- init menu -->
				<script type="text/javascript">
					initMessagesMenu('prMessages-menu');
				</script>
				<!-- /init menu -->
				<table class="prForm">
					<col width="75" />
					<col width="570" />
					<col width="0" />
					<thead>
						<tr><th colspan="3">
							{form_errors_summary}
							{form_hidden name="id" value=$id|escape:html}
						</th></tr>
					</thead>
					<tbody>
                        <tr>
                            	<td valign="top" align="right"><label>{t}To:{/t}</label></td>
                            	<td class="yui-skin-sam">
                                    <span>{t}{tparam value=$SITE_NAME_AS_STRING}Enter an email address or a %s username, separated by a comma.{/t}</span><br/>
                                    
                                    <a href="javascript:void(0)" onclick="xajax_addFromAddressbook(); return false;">{t}Insert addresses from Address Book{/t}</a>
                                   

                                    <div style="{if !$formParams.mail_lists}display:none{/if}" id="ListsObjects">
                                        {include file="users/messages/contact.list.tpl"}
                                    </div>
                                    <div style="{if !$formParams.mail_groups}display:none{/if}" id="GroupsObjects">
                                        {include file="users/messages/contact.group.tpl"}
                                    </div>

                                     {form_textarea id="target_emails" name="target_emails" value=$target_emails|escape:html style="width: 100%;" class="prIndentTop"}
                                     <div class="yui-ac" id="div_autocomplete" style="width: 305px">
                                    </div>
                                    <div>{t}{tparam value=$SITE_NAME_AS_STRING}{tparam value=$SITE_NAME_AS_STRING}These email addresses will be stored in your %s address book<br />for future use. %s will not use them for marketing purposes.{/t}</div>
                                    <script type="text/javascript">
                                    {literal}
                                        var myAutoComp = new YAHOO.widget.AutoComplete("target_emails", "div_autocomplete", myDataSource);
                                        myAutoComp.delimChar = [",", " "]; 
                                        myAutoComp.animVert = true;
                                        myAutoComp.useIFrame = false; 
                                        myAutoComp.maxResultsDisplayed = 10;
                                        myAutoComp.animHoriz = true;
                                        myAutoComp.useShadow = true;
                                        myAutoComp.animSpeed = 0.5; 
                                    {/literal}
                                    </script>
                                </td>
                                <td class="prTip"></td>
                        </tr>

                       	<tr>
							<td valign="top" align="right"><label for="subject">{t}Subject:{/t}</label></td>
							<td>{form_text name=subject value=$subject|escape:html maxlength="100" style="width: 100%;"}</td>
							<td class="prTip"></td>
						</tr>
						<tr><td colspan="3" class="">
								{form_textarea id="tiny_textarea" name=body value=$body|escape:html}
								{literal}
	                            <!-- tinyMCE -->
	                            <script type="text/javascript">
	                                // Notice: The simple theme does not use all options some of them are limited to the advanced theme
	                                tinyMCE.init({
										mode : "exact",
										elements : "tiny_textarea",
										theme : "advanced",
										theme_advanced_toolbar_location : "top",
										theme_advanced_toolbar_align : "left",
										theme_advanced_buttons1 : "formatselect, fontsizeselect ,|, forecolor,|,   bold, italic, underline, strikethrough,|, justifyleft, justifycenter, justifyright, justifyfull,|, undo, redo,|, cleanup,|, bullist, numlist,|, image,|, code",
                                        theme_advanced_buttons2 : "",
                                        theme_advanced_buttons3 : ""
									});
	                            </script>
	                            <!-- /tinyMCE -->
								{/literal}
						</td></tr>
						<tr>
							<td colspan="3">
									{t var='button_01'}Send{/t}
									{form_submit name='btnSend' value=$button_01}&nbsp;

									{t var='button_02'}Save Draft{/t}
		                            {form_submit name='btnDraft' value=$button_02}&nbsp;

									<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="location.href='{$currentUser->getUserPath('messagelist')}';return false;">{t}Cancel{/t}</a></span>
							</td>
						</tr>
						
					</tbody>
				</table>
	
			<!--  /my messages container --> 

	<!-- =========================================== -->
	
{/form} 