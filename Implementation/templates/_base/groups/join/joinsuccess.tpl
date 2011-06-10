<script type="text/javascript" src="/js/tours.js" ></script>
	<!-- tabs2 begin -->
	<!-- tabs2 end -->
	<!-- tabs2 area begin -->

<h2>{t}Join a Group{/t}</h2>

{if $CurrentGroup->getJoinMode() == 0 || $CurrentGroup->getJoinMode() == 2}
<!-- Congratulations begin -->              

	<h3 class="prInnerBottom">
		<span class="prMarkRequired">{t}Congratulations!{/t}</span> {t}You have successfully joined the{/t} <a href="{$CurrentGroup->getGroupPath('summary')}">{$CurrentGroup->getName()|escape|wordwrap:30:"\n":true}</a>
	</h3>
	<ul class="prTBold prInner">
		<li class="prInnerBottom"><a href="{$CurrentGroup->getGroupPath('summary')}">{t}Go to Group Homepage{/t}</a></li>
		<li><a href="/">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></li>
	</ul>
	<div class="prInnerTop">
		<p class="prTLeft">
			{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question, please <a href="%s/%s/info/contactus/">Contact Us</a>.{/t}
		</p> 
	</div>

		<!-- Congratulations end -->
		{elseif $CurrentGroup->getJoinMode() == 1}  

	<h3 class="prInnerBottom">{t}{tparam value=$CurrentGroup->getGroupPath('summary')}{tparam value=$CurrentGroup->getName()|escape|wordwrap:30:"\n":true}
		Your request to join <a href="%s">%s</a> has been sent to the host.{/t}
	</h3>
	<div class="prInnerTop prTLeft">{t}{tparam value=$SITE_NAME_AS_STRING}{tparam value=$SITE_NAME_AS_STRING}{tparam value=$user->getUserPath('messagelist')}
			If the host approves your membership request, you will receive confirmation in the email associated with your %s account and in your %s Message Center in <a href="%s">your account</a>.{/t}
	</div>
	<div class="prInnerTop prTBold ">
			<a href="/">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a>
	</div>
	<div class="prInnerTop">
		<p class="prTLeft">
			{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question, please <a href="%s/%s/info/contactus/">Contact Us</a>.{/t}
		</p> 
	</div>
	 
		{/if}                            
	<!-- tabs2 area end -->
