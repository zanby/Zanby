{form from=$form id = "list_edit_form"}
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
						<td class="prTRight"></td>
						<td>{t}Type:{/t} {$types[$type]}</td>
						<td></td>
					</tr>
					<tr>
						<td class="prTRight">
							<span class="prMarkRequired"> * </span>
							<label for="listTitle">{t}Enter the name of your list:{/t}</label>
						</td>
						<td>{form_text id="listTitle" class="" name="title" value=$title|escape:"html" }</td>
						<td></td>
					</tr>
					<tr>
						<td class="prTRight"><label for="listDescr">{t}Short Description <span>(optional)</span>:{/t}</label></td>
						<td>{form_textarea id="listDescr" name="description" value=$description|escape:"html"}</td>
						<td></td>
					</tr>
					<tr>
						<td class="prTRight"><label for="lsitTags">{t}Tags:{/t}</label></td>
						<td>{form_text id="lsitTags" name="tags" value=$tags|escape:"html" size="30"}</td>
						<td></td>
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
<!-- Form Slot end -->
<!-- Form Slot begin -->
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
					<td>{form_radio name="private" checked=$private|default:"0" value="0" id="radio_public"}<label for="radio_public"> {t}{tparam value=$SITE_NAME_AS_STRING}<strong>Public</strong> &#8211; Everybody on %s can see the list{/t}</label>
					<div class="prIndentTopSmall">
					{form_radio name="private" checked=$private value="1" id="radio_private"} <label for="radio_private"> {t}<strong>Private</strong> &#8211; Only me and those with whom I share<br />
					can see the list{/t}</label>						
					</td>
					<td></td>
			  </tr>
			</table>
		</div>
	</div>
<!-- Form Slot end -->
<!-- Form Slot begin -->
		<div class="prDropBox">
			<div class="prDropBoxInner">
				<div class="prDropHeader">
					<h3>{t}Sharing{/t}</h3>
				</div>
				<table class="prForm">
					<col width="36%" />
					<col width="54%" />
					<col width="10%" /> 
					<tr>
						<td class="prTRight"><label>{t}With whom would you like to share the list?{/t}</label></td>
						<td>						
							<div class="prIndentTopSmall">
								<select name="share" id="shareId" style="width: 260px;" class="prFloatLeft prIndentRightSmall">
									<option value="">{t}Select{/t}</option>
									{if $friendsList}
									<optgroup label="Friends">
										{foreach item=u key=id from=$friendsList}
										<option value="u_{$id}">{$u|escape:"html"}</option>
										{/foreach}
									</optgroup>
									{/if}
									{if $groupsList}
									<optgroup label="Groups">
										{foreach item=g key=id from=$groupsList}
										<option value="g_{$id}">{$g|escape:"html"}</option>
										{/foreach}
									</optgroup>
									{/if}
								</select>
								{t var="in_button"}Share list{/t}
								{linkbutton name=$in_button onclick="lock_content(); xajax_list_edit_share(document.getElementById('shareId').value); return false;"}
							</div>
							<div class="prTip prIndentTopSmall">
								{t}Share the list with your friends,
								groups and group families. This
								will place the list in the group
								archives and alert your friends.{/t}
							</div>
							<div id="shared_with" {if $sharedWith|@count==0} style="display: none;"{/if}>
								<h4>{t}This list is shared with:{/t}</h4>
								{foreach from=$sharedWith item=name key=share_id}
									{include file="groups/lists/lists.share.div.tpl" name=$name share_id=$share_id action="edit"}
								{/foreach}
							</div> 
							
						</td>
						<td></td>
				  </tr>
				</table>
			</div>
		</div>
<!-- Form Slot end -->
<!-- Form Slot begin -->
    {if !$isSystemWhoWill}
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
					<td class="prTRight">
						<label>{t}Would you like viewers to be able 
						to rank the items on your list?{/t}</label>
					</td>
					<td>
						{form_checkbox name="ranking" id="cb_ranking" value="1" checked=$ranking}<label for="cb_ranking">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE} Enable Ranking <a href="%s/%s/info/listsranking/" target="_blank">What's this?</a>{/t}</label>						
					</td>
					<td></td>
				</tr>
				<tr>
					<td class="prTRight">
						<label>{t}Would you like viewers to be able
						to add items to  your list?{/t}</label>
					</td>
					<td>
					{form_checkbox name="adding" id="cb_adding" value="1" checked=$adding}<label for="cb_adding"> {t}{tparam value=$BASE_URL}{tparam value=$LOCALE}Allow viewers to add items <a href="%s/%s/info/listsviewadd/" target="_blank">What's this?</a>{/t}</label>
					</td>
					<td></td>
				</tr>                
			</table>
		</div>
	</div>
    {/if}
{/form}
<!-- Form Slot end -->
{if !$isSystemWhoWill}
<div id="list_items" >
	<h3>{t}List items:{/t} <span>[<a href="#" onclick="lock_content(); xajax_list_edit_expand(); return false;">{t}Expand All{/t}</a>]</span></h3>
	
	{foreach from = $records item=record key=id}
	  <div id="item_{$id}">
		{if $record.status == 'expanded'}
			{include file="groups/lists/lists.record.form.tpl" action="edit"}
		{elseif $record.status == 'collapsed'}
			{include file="groups/lists/lists.record.tpl" action="edit"}
		{/if}
	  </div>
	{/foreach}
</div>
{/if}
<div class="prInner prTCenter">
    {if !$isSystemWhoWill}
	<a class="prInnerTop" href="#" onclick='lock_content(); xajax_list_edit_save(); return false;'>{t}+ Add Another List Item{/t}</a>
    {/if}
    <div class="prInnerTop">
    {t var="in_button_2"}Save List{/t}
    {linkbutton onclick="lock_content(); xajax_list_edit_publish(xajax.getFormValues('list_edit_form'));  return false;" name=$in_button_2}	 <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="javascript:history.back(); return false;">{t}Cancel{/t}</a></span>
    </div>
</div>
{include file="groups/lists/block_layer.tpl"}