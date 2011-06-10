<div class="prTCenter"><a href="{$CurrentGroup->getGroupPath('publish')}">{t}Publishing settings{/t}</a> | <a href="{$CurrentGroup->getGroupPath('editpublishstatus')}">{t}Publishing Status{/t}</a> </div>
{literal}
	<script>
		function publishnow(){
			{/literal}
				document.getElementById('ifr1').src='{$Path}/publishnow/';
			{literal}
		}
	</script>
{/literal}
<div class="prTCenter prInnerTop">
		{if $Data->lastPublish}
			{t}Last publish date is:{/t} {$Data->getlastPublish()}
		{else}
			<h3>{t}No publish results to display.{/t}</h3>
			<p>{t}Your summary has not been published this session{/t}</p>
		{/if} 
		<div>
			<iframe id="ifr1" width="210" height="70" frameborder="0"></iframe>
		</div>
		{t var="in_button"}Publish{/t}
		{linkbutton name=$in_button onclick="publishnow(); return false;"}
		{if $Data->lastPublish}<div class="prInnerTop"> <a href="{$Data->getDesturl()}" target="_blank">{t}View Summary at external website{/t}</a> </div>{/if}
		{if $publishnow == true}
			<script>
				publishnow();
			</script>
		{/if}
</div>