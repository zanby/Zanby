{form from=$form id = "list_add_form"}
	<div id="list_errors">
		{include file="groups/lists/errors.tpl"}
	</div>
	<div class="prIndentBottom"></div>	
	<div class="prDropBox">
		<div class="prDropBoxInner"> 
			<div class="prDropHeader">
				<h3>{t}Name and Type{/t}</h3>
			</div>
			<table class="prForm">
				<col width="36%" />
				<col width="54%" />
				<col width="10%" />	
					<tr>
						<td class="prTRight"><label for="listType">{t}Select the type of list you wish to create:{/t}</label></td>
						<td>{form_select id="listType" name="type" selected=$type options=$types onchange="xajax_list_add_change_type(this.options[this.selectedIndex].value);"}</td>
						<td>&#160;</td>
					</tr>
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired"> * </span>
							<label for="listTitle">{t}Enter the name of your list:{/t}</label>
						</td>
						<td>{form_text id="listTitle" class="" name="title" value=$title|escape:"html"}</td>
						<td>&#160;</td>
					</tr> 
					<tr>
						<td class="prTRight"><label for="listDescr">{t}Short Description <span>(optional)</span>:{/t}</label></td>
						<td>{form_textarea id="listDescr" name="description" value=$description|escape:"html"}</td>
						<td>&#160;</td>
					</tr> 
					<tr>
						<td class="prTRight"><label for="lsitTags">{t}Tags:{/t}</label></td>
						<td>{form_text id="lsitTags" name="tags" value=$tags|escape:"html" size="30"}</td>
						<td>&#160;</td>
					</tr>
                    {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
					<tr>
						<td class="prTRight">&nbsp;</td>
						<td colspan=2><div class="prTip">{t}Tags are a way to group your lists and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
					</tr>
                    {/if}
			</table>
		</div>
	</div>
	<div class="prDropBox">
		<div class="prDropBoxInner">
			<div class="prDropHeader">
				<h3>{t}Visibility{/t}</h3>
			</div>
			<table class="prForm">
				<col width="36%" />
				<col width="54%" />
				<col width="10%" />         
				<tr>
					<td class="prTRight"><label>{t}Set the visibility of your list:{/t}</label></td>
					</td>
					<td>
						{form_radio name="private" checked=$private|default:"0" value="0" id="radio_public"}<label for="radio_public"> {t}{tparam value=$SITE_NAME_AS_STRING}<strong>Public</strong> &#8211; Everybody on %s can see the list{/t}</label>
						<div class="prIndentTopSmall">
						{form_radio name="private" checked=$private value="1" id="radio_private"}<label for="radio_private"> {t}<strong>Private</strong> &#8211; Only me and those with whom<br /> I share
						can see the list{/t}</label>
						</div>
					</td>
					<td>&#160;</td>
			  </tr>
			</table>
		</div>
	</div>
	<div class="prDropBox">
		<div class="prDropBoxInner">
			<div class="prDropHeader">
				<h3>{t}Ranking{/t}</h3>
			</div>
			<table class="prForm">
				<col width="36%" />
				<col width="54%" />
				<col width="10%" />       
				<tr>
					<td class="prTRight"><label>{t}Would you like viewers to be able to rank the items on your list?{/t}</label></td>
					<td>{form_checkbox name="ranking" id="cb_ranking" value="1" checked=false} <label for="cb_ranking">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}Enable Ranking <a href="%s/%s/info/listsranking/" target="_blank">What's this?</a>{/t}</label></td>
					<td>&#160;</td>
				</tr>
				<tr>
					<td class="prTRight"><label>{t}Would you like viewers to be able to add items to  your list?{/t}</label></td>
					<td>{form_checkbox name="adding" id="cb_adding" value="1" checked=false} <label for="cb_adding">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}Allow viewers to add items <a href="%s/%s/info/listsviewadd/" target="_blank">What's this?</a>{/t}</label></td>
					<td>&#160;</td>
				</tr>
			</table>
		</div>
	</div>
{/form}
<!-- Form Slot end -->
<div id="list_items">
	<h3>{t}List items:{/t}</h3>
	{foreach from = $list_records item=record key=id}
	  <div id="item_{$id}">
		{if $record.status == 'expanded'}
			{include file="groups/lists/lists.record.form.tpl" action="add"}
		{else}
			{include file="groups/lists/lists.record.tpl" action="add"}
		{/if}
	  </div>
	{/foreach}
</div>
<div class="prInner prTCenter">		
		<a class="prInnerTop" href="#" onclick='lock_content(); xajax_list_add_save(); return false;'>
			   {t} + Add Another List Item{/t}
		</a>
			  <br />  
		<div class="prInnerTop">
		{t var="in_button"}Save List{/t}
		{linkbutton onclick="lock_content(); xajax_list_add_publish(xajax.getFormValues('list_add_form'));  return false;" name=$in_button}  <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="javascript:history.back(); return false;">{t}Cancel{/t}</a></span>
		</div>            
</div>
{include file="groups/lists/block_layer.tpl"}