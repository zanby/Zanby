{literal}
<script>
  flag = false;
  function setGemail(elem)
  {
  	if (!flag) {
  	  temp = elem.value;  
  	  document.getElementById('gemail').value = temp.trim().replace(/\s/g,"-").replace(/[^A-Za-z0-9\-]/g, "");
  	  flag = true;	
  	}  	
  }
</script>
{/literal}
<div class="prDropBoxInner prIndentBottom">
                <div class="prDropBoxInnerInner">
	<div class="prDropHeader">
		<!-- -->
				<h2>{t}Step 1: Group Category and Location{/t}</h2>
				<div class="prHeaderTools">
				<a onclick="xajax_saveTempData(xajax.getFormValues('formStep2'), 2, 1);" href="#">{t}Edit{/t}</a></div>
				
	</div>
	{if $groupInThisCity && !$hideGroupSearch}
	<div class="prHeaderHelper">
					{t}{tparam value=$groupInThisCity}{tparam value=$Category->name|escape:"html"}{tparam value=$city|escape:"html"}{tparam value=$state|escape:"html"}{tparam value=$country|escape:"html"}Did you know there are %s %s Category groups in %s, %s, %s?<br />
					Perhaps you would like to {/t} <a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/category:'{$Category->name|escape:"html"}'/">{t}{tparam value=$Category->name|escape:"html"}join an existing %s category group{/t}</a>?
	</div>           		 
	{/if} 
</div>
</div>	
		<!-- -->
			<!-- toggle section begin -->
			<div class="prDropBoxInner">
<div class="prDropHeader">
	<!-- -->
		<h2>{t}Step 2: Group Settings{/t}</h2>	</div>
<div class="prHeaderHelper prText5">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</div>		
	<div class="prDropMain">
	{form from=$form id="formStep2"} 
		{form_errors_summary}
		<table class="prForm">
			<col width="30%" />
			<col width="40%" />
			<col width="30%" />
			<tbody>
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="name">{t}Group Name:{/t}</label></td>
					<td>{form_text name="name" id="name" value=$group.name|escape:"html" onBlur="setGemail(this);"}</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"><label for="mcalled">{t}What are members called?{/t}</label></td>
					<td>{form_text name="mcalled" id="mcalled" value=$group.mcalled|escape:"html" maxlength="100" }
					</td>
					<td class="prTip">{t}100 characters available{/t}</td>
				</tr>								
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="gemail">{t}Group Address:{/t}</label></td>
					<td>{form_text name="gemail" id="gemail" value=$group.gemail|escape:"html" dir="rtl"}</td>
					<td>@{$DOMAIN_FOR_GROUP_EMAIL}</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2">
<div class="prTip">					
					<p>{t}60 characters available<br />
					Name may contain letters, numbers, hyphen{/t}</p>
					<p class="prIndentTopSmall">{t}The group address is used for the group web address, <br />
					host email account and group discussions.{/t}</p>
					<p class="prIndentTopSmall">{t}{tparam value=$BASE_HTTP_HOST}<span class="prText2">Web Address:</span>  http://%s/en/group/groupsaddressname{/t}</p>
					<p class="prIndentTopSmall">{t}{tparam value=$DOMAIN_FOR_GROUP_EMAIL}<span class="prText2">Group Discussions Email:</span> groupsaddressname@%s{/t}</p>	
</div>					
					</td>									
				</tr>	
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="description">{t}Description:{/t}</label></td>
					<td>{form_textarea name="description" id="description" value=$group.description|escape:"html" }</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2"><div class="prTip">{t}2000 characters available<br />The first few words of your description will appear in search result.<br />The full description will appear in your group profile.{/t}</div></td>									
				</tr>
				<tr>
					<td class="prTRight"> <label for="tags">{t}Tags:{/t}</label></td>
					<td> {form_text name="tags" id="tags" value=$group.tags|escape:"html" }</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2">
                        <div class="prTip">
                            {t}Enter the top five keywords that describe your group{/t}
                            {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
                            <br/>{t}Tags are a way to group your groups and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}
                            {/if}
                        </div>
                    </td>
				</tr>	
				<tr>
					<td class="prTRight"> <span class="prMarkRequired">*</span> <label for="tags">{t}Who can join?{/t}</label></td>
					<td colspan="2"> 
						{form_radio id="whcj1" name="hjoin"  value="0" checked=$group.hjoin|default:"0"}<label for="whcj1"> {t}Anyone{/t}</label>
						<div class="prIndentTopSmall">
						{form_radio id="whcj2" name="hjoin"  value="1" checked=$group.hjoin}<label for="whcj2"> {t}Only those I approve{/t}</label></div>
						<div class="prIndentTopSmall">
						{form_radio id="whcj3" name="hjoin"  value="2" checked=$group.hjoin}<label for="whcj3"> {t}Only those with the following code:{/t}</label></div>							
					</td>									
				</tr>
				<tr>
					<td></td>
					<td>{form_text name="jcode" id="jcode" value=$group.jcode|escape:"html"}</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2">
						<div class="prTip">{t}You can choose a word or number as your code. It will be included in the invitation you send to the people you invite to join your group.{/t}</div>
					</td>									
				</tr>
				<tr>
					<td class="prTRight"> <span class="prMarkRequired">*</span> <label for="tags">{t}Set Content Visibility:{/t}</label></td>
					<td colspan="2"> 
						 {form_radio id="cntvis1" name="gtype"  value="0" checked=$group.gtype|default:"0"}<label for="cntvis1"> {t}Public{/t}</label>
						{form_radio id="cntvis2" name="gtype"  value="1" checked=$group.gtype}<label for="cntvis2"> {t}Private &ndash; Members Only*{/t}</label>					
					</td>									
				</tr>
				<tr>
					<td></td>
					<td colspan="2">
						<div class="prTip">{t}* If your group is private, its profile will be visible, and you will be identified as the host. However, if you choose "Private" your event calendar, documents, photos, lists, and messages will be visible to members only.{/t}</div>
					</td>									
				</tr>								 
			</tbody>
		</table>

		<div class="prTCenter prIndentTop">
			{t var='button_02'}Create Group{/t}
			{linkbutton name=$button_02 onclick="document.getElementById('formStep2').submit(); return false;"}
		</div>
	{/form}
</div>	
</div>
