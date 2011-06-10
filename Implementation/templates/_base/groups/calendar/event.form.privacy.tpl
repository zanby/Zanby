<div>
	<div class="prToggleArea">
		<!-- form container -->
		<div id="sharing-options-full">
			<h4>{t}This event shows on your calendar as:{/t}</h4>
			{form_radio name="event_privacy" id="event_privacy_0" value="0" checked=$formParams.event_privacy}<label for="event_privacy_0" class=""> {t}This event is public. It is visible to everyone{/t}</label>
			<div class="prIndentTopSmall">
			{form_radio name="event_privacy" id="event_privacy_1" value="1" checked=$formParams.event_privacy}<label for="event_privacy_1" class=""> {if $currentGroup->getGroupType() == "family"}{t}This event is private. This event is visible only to group family members.{/t}{else}{t}This event is private. This event is visible only to group members.{/t}{/if}</label>
			</div>
		</div>
		{form_hidden name="show_privacy_block" id="show_privacy_block" value=$formParams.show_privacy_block}
		<!-- /form container -->
	</div>
</div>

