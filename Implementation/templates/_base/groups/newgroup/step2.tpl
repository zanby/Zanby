{literal}
<script>
	flag = false;
	function setGemail(elem) {
		if (!flag) {
			temp = elem.value;  
			document.getElementById('gemail').value = temp.trim().replace(/\s/g,"-");
			flag = true;	
		}  
    }	
</script>
{/literal}

<div>
	<h2>{t}Group Members{/t}</h2>

  	<div class="prClr2 prInner">

		<!-- Toggled area start -->
	<div class="prDropBoxInner prIndentBottom">
		<div class="prDropHeader">
				<!-- -->
						<h2 class="prFloatLeft">{t}Step 1: Group Category and Location{/t}</h2>
						<div class="prHeaderTools">
						<a href="#" onclick="xajax_saveTempData(xajax.getFormValues('formStep2'), 2, 1);"> {t}Edit{/t}</a></div>
		</div>
	</div>
			{if $groupInThisCity && false}
	<div>
		<!--<h3>Page not found</h3>-->
		<p>
			{t}{tparam value=$groupInThisCity}{tparam value=$Category->name|escape:"html"}{tparam value=$city|escape:"html"}{tparam value=$state|escape:"html"}{tparam value=$country|escape:"html"}Did you know there are %s %s Category groups in %s, %s, %s?{/t}<br />
			{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}{tparam value=$Category->name|escape:"html"}{tparam value=$Category->name|escape:"html"}Perhaps you would like to <a href="%s/%s/search/groups/preset/new/keywords/category:'%s'/">join an existing %s category group{/t}</a>?
		</p>
	</div>         		 
            {/if}  
		<!-- -->	

			<div class="prDropBoxInner">
				<div class="prClr2">
					<h2>{t}Step 2: Group Settings{/t}</h2>
				</div>	

			<div>
				{form from=$form id="formStep2"}
				<table cellpadding="0" cellspacing="0" border="0" class="prForm">
					<col width="27%" />
					<col width="55%" /> 
					<thead>
						<tr><th colspan="2" class="prText5">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</th></tr>
						<tr><th colspan="2" class="prNoPadding">
							{form_errors_summary}
						</th></tr>
					</thead>                  
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired">*</span>
							<label>{t}Group Name:{/t}</label>
						</td>
						<td colspan="2">
							{form_text name="group_name" id="group_name" value=$group.group_name|escape:"html" onBlur="setGemail(this);"}
						</td>
					</tr>
					<tr>
						<td class="prTRight">
							<label>{t}What are members called?{/t}</label>
						</td>
						<td colspan="2">
							{form_text name="mcalled" id="mcalled" value=$group.mcalled|escape:"html" maxlength="100" }
						</td>
					</tr>                                            
					<tr>
						<td>&nbsp;</td>
						<td colspan="2"class="prTip">{t}100 characters available{/t}</td>
					</tr>                                        
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired">*</span>
							<label>{t}Group Address:{/t}</label>
						</td>
						<td colspan="2">
							<div class="prFloatLeft prIndentRightSmall">{form_text name="gemail" id="gemail" value=$group.gemail|escape:"html" dir="rtl"}</div><span> @{$DOMAIN_FOR_GROUP_EMAIL}</span>
						</td>
					</tr>                      
					<tr>
						<td>&nbsp;</td>
						<td colspan="2">
							<div class="prTip">
								<p>{t}60 characters available<br /> Name may contain letters, numbers, hyphen{/t}</p>
								<p>{t}The group address is used for the group web address, <br /> host email account and group discussions.{/t}</p>
								<div class="prInnerTop">{t}{tparam value=$BASE_HTTP_HOST}<span class="prText2">Web Address:</span>  http://%s/en/group/groupsaddressname{/t}</div>
								<div class="prInnerTop">{t}<span class="prText2">Group Discussions Email:</span>{/t} groupsaddressname@{$DOMAIN_FOR_GROUP_EMAIL}</div>                    
							</div>
						</td>
					</tr>  
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired">*</span>
							<label>{t}Description:{/t}</label>
						</td>
						<td colspan="2">
							{form_textarea name="description" id="description" value=$group.description|escape:"html"}
						</td>
					</tr>                                                                        
					<tr>
						<td>&nbsp;</td>
						<td colspan="2" class="prTip">
							 {t}2000 characters available<br />The first few words of your description will appear in search result.<br />The full description will appear in your group profile.{/t}
						</td>
					</tr>                                                         
					<tr>
						<td class="prTRight">
							<label>{t}Tags:{/t}</label>
						</td>
						<td colspan="2">
							{form_text name="tags" id="tags" value=$group.tags|escape:"html"}
						</td>
					</tr>          
					<tr>
						<td>&nbsp;</td>
						<td colspan="2" class="prTip">
							{t}Enter the top five keywords that describe your group{/t}
                            {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
                            <br/>{t}Tags are a way to group your groups and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}
                            {/if}
						</td>
					</tr>
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired">*</span>
							<label>{t}Who can join?{/t}</label>
						</td>
						<td colspan="2">
							{form_radio id="whcj1" name="hjoin"  value="0" checked=$group.hjoin|default:"0"}<label for="whcj1">{t}Anyone{/t}</label><br />
							{form_radio id="whcj2" name="hjoin"  value="1" checked=$group.hjoin}<label for="whcj2">{t}Only those I approve{/t}</label><br />
							{form_radio id="whcj3" name="hjoin"  value="2" checked=$group.hjoin}<label for="whcj3">{t}Only those with the following code:{/t}</label><br />
							<div class="prTip">
								{form_text name="jcode" id="jcode" value=$group.jcode|escape:"html"}
								<br />
								{t}You can choose a word or number as your code. It will be included in the invitation you send to the people you invite to join your group.{/t}
							</div>
						</td>
					</tr>
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired">*</span>
							<label>{t}Set Content Visibility:{/t}</label>
						</td>
						<td colspan="2">
							{form_radio id="cntvis1" name="gtype"  value="0" checked=$group.gtype|default:"0"}
							<label for="cntvis1">{t}Public{/t}</label>&nbsp;&nbsp;&nbsp;&nbsp;
							{form_radio id="cntvis2" name="gtype"  value="1" checked=$group.gtype}
							<label for="cntvis2">{t}Private{/t} &ndash; {t}Members Only{/t}*</label>
						</td>
					</tr>          
					<tr>
						<td>&nbsp;</td>
						<td colspan="2" class="prTip">
							* {t}Standard, or free, members have limited privacy controls. Even if your group is private, its profile will always be visible, and you will be identified as the host. However, if you choose "Private" your event calendar, documents, photos, lists, and messages will be visible to members only.{/t}
						</td>
					</tr>                                
				</table>
				
						  <div class="prInnerRight prInnerLeft">
							<div><div><div><div>
								<p class="prTCenter prIndentTop">
									{t var="in_button_2"}Create Group{/t}
									{linkbutton name=$in_button_2 onclick="document.getElementById('formStep2').submit(); return false;"}
								</p>
							</div></div></div></div>
						</div>

				{/form}
			</div>
		</div>
		<!-- Toggled area end -->
</div>
</div>