{if $mode_pending}{include file="groups/familymembersPending.tpl"}
{elseif $mode_request}{include file="groups/familymembersRequest.tpl"}
{else}{include file="groups/familymembersApproved.tpl"}{/if}	
