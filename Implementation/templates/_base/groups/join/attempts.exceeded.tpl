	<h2>{t}Join a Group{/t}</h2>

	<h3 class="prInner prTCenter"><span class="prMarkRequired">{t}You exceeded the number of attempts <br /> to enter correct code to join{/t}</span></h3>
	<div class="prInner prTBold">
			<a href="{$CurrentGroup->getGroupPath('summary')}">{$CurrentGroup->getName()|escape|wordwrap:30:"\n":true}</a>
	</div>
	<div class="prInner">
		<p class="prTLeft">{t}{tparam value=$attemptLimit}{tparam value=$attemptPause}
			You entered incorrect code %s times. System locked this group for you for %s minutes. After this period of time you can try to join the group again.{/t}
		</p> 
	</div>	
	<div class="prInner prTBold">
			<a href="/">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a>
	</div>
	<!--div class="prInner">
		<p class="prTLeft">
			{t}If you need help getting around, why not to see a {/t}<a href="#null">{t}{tparam value=$SITE_NAME_AS_STRING}%s Tour{/t}</a>?
		</p>
	</div-->	
	<div class="prInner">
		<p class="prTLeft">
			{t}If you have a special question, please {/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us{/t}</a>.
		</p> 
	</div>

