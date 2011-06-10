{literal}
    <script type="text/javascript">//<![CDATA[ 
        $(function(){ $('#btnCancel').unbind().bind('click', function(){$('#mode').val(0); $('#formConfirm').trigger('submit'); return false;}) })
    //]]></script>
{/literal}
{form from=$form id="formConfirm"}
    <input type="hidden" name="mode" id="mode" value=1>
    <h2>{t}{tparam value=$SITE_NAME}Please confirm your %s account{/t}</h2> 
    <span>
        {t}{tparam value=$SITE_NAME}
        Thank you. We have processed your request and found %s account that belongs to your Facebook account.        
        {/t}
    </span>    
    <div class="prInnerTop">{t}Please confirm your account or cancel it.{/t}</div>    
    <div class="prInnerTop">
        <h3>{t}Account Details{/t}</h3>
        <div class="prInnerTop">{t}Username :{/t} {$user->getLogin()|escape:html}</div>
        <div class="prInnerTop">{t}Email address :{/t} {$user->getEmail()|escape:html}</div>
    </div>
	<div class="prInnerTop">
	{t var='button_01'}Yes, it's my account.{/t}
	{form_submit name="confirm" id="btnConfirm" value=$button_01} 
	{t var='button_02'}No, it isn't my account{/t}
	{form_submit id="btnCancel" name="cancel" value=$button_02}
	</div>
{/form}