<h2>{t}My Profile Template Editor{/t}</h2>
<!-- secondary tabs begin -->
<div>
{tab template="tabs1" active="theme"}
	{tabitem link=$currentUser->getUserPath('compose') name="compose"}{t}Layout &amp; Content{/t}{/tabitem}
	{tabitem link=$currentUser->getUserPath('theme') name="theme"}{t}Theme{/t}{/tabitem}
	{tabitem link=$currentUser->getUserPath('publishing') name="publishing"}{t}Publishing{/t}{/tabitem}
{/tab}
</div>                
<!-- secondary tabs end -->

{include file='content_objects/theme/ddpages_theme_form.tpl'}
