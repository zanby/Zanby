<h2>{t}Invite Groups to join family{/t} <span>{t}(Step 2 of 2){/t}</span></h2>
	 {form id="icForm" from=$composeForm}
	 {form_hidden id="inv_name" name="inv_name" value=$name}
	<div class="prDropBox prDropBoxInner">
		<div class="prDropHeader"><h2>{t}Step 2: Compose Email{/t}</h2></div>
			<div class="prHeaderHelper">{t}Your invitation will be sent to the hosts of the following groups  -Each invitation will be stored as an individual email in the Sent Invitations folder.{/t}</div>
			
			  <ul class="prIndentBottomSmall">
				{foreach item="item" from=$recipients name="recipientsIteration"}
				<li class="prInnerSmall">
					{assign var="itemId" value=$item->getId()}
					{$smarty.foreach.recipientsIteration.iteration}.&nbsp;<a href="{$item->getId()}">{$item->getName()}</a>&nbsp;<a class="prClose" href="./remove/{$item->getId()}">&nbsp;</a>
					{form_hidden name="recipients[`$itemId`]" value=$item->getName()}
				</li>
				{/foreach}
			</ul>
			<div>
				<h3>{t}Your Message{/t}</h3>
				<table class="prForm">
					<col width="20%" />
					<col width="80%" />                                  
					<tr>
						<th colspan="2">{form_errors_summary}</th>
					</tr>						
					<tr>
						<td class="prTRight"><label for="subject">{t}Subject:{/t}</label></td>
						<td>{form_text name=subject value=$subject}</td>
					</tr>
					<tr>
						<td class="prTRight"><label for="body">{t}Text:{/t}</label></td>
						<td>{form_textarea name=body rows=7 value=$body}</td>
					</tr>
					<tr>
						<td></td>
						<td>
						{if $isNamed}    
							{t var="in_submit"}Save Draft{/t}
							{form_submit name="draft" value=$in_submit} 
						{else}
							{form_hidden name="draft" value="draft"}
							{t var="in_button"}Save Draft{/t}
							{linkbutton name=$in_button onclick="xajax_nameInvitation(); return false;"}

						{/if}			    			
						<span class="prIndentLeftSmall">{t var="in_submit_2"}Send{/t}{form_submit name="send" value=$in_submit_2 }</span>
						</td>
					</tr>
				</table>
			</div>
	{/form}                   