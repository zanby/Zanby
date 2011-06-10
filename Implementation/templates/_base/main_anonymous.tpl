<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{$TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="{$KEYWORDS|escape:html}" />
	<meta name="description" content="{$DESCRIPTION|escape:html}" />
	{if $GEOPOSITION}<meta name="geo.position" content="{$GEOPOSITION}" />{/if}
	{if $GEOPLACENAME}<meta name="geo.placename" content="{$GEOPLACENAME}" />{/if}
	{if $GEOREGION}<meta name="geo.region" content="{$GEOREGION}" />{/if}
	<link rel="stylesheet" type="text/css" href="{$CSS_URL}/znmain.css" />
	<link rel="stylesheet" type="text/css" href="{$CSS_URL}/zn-marina.css" />
	<link rel="stylesheet" type="text/css" href="{$CSS_URL}/zn-gena.css" />
	<link rel="stylesheet" type="text/css" href="{$CSS_URL}/znadditional.css" />
	<link rel="stylesheet" type="text/css" href="{$CSS_URL}/default.css" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/yui_container.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/yui-menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$CSS_URL}/print.css" media="print" />
	<!-- -->
	{include file="bugzilla.tpl"}
	<script type="text/javascript" src="{$JS_URL}/common.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/yahoo/yahoo-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/event/event-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/dom/dom-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/animation/animation.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/connection/connection-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/container/container_core-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/container/container-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/menu/menu-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/json/json.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/geometry.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/content.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/BrowserDetect.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/MainApplication.js" ></script>
	{$XajaxJavascript}
	<!-- / -->
</head>
<!-- page body begin -->
<body {$onload_attributes} {$body_attributes}><div>
	<!-- overlib -->
	{popup_init src="$JS_URL/overlib/overlib.js"}
	<!-- /overlib -->
	<!-- page header begin -->
	<div>
		<div><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/registration/index/">{t}Registration{/t}</a> <span>|</span> <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/login/">{t}Login{/t}</a></div>
		<a href="http://{$BASE_HTTP_HOST}"><img src="{$AppTheme->images}/decorators/logo-v2.gif" alt="" /></a>
		<!-- main menu begin -->
		{mainnav_anonymous}
		<!-- main menu end -->
	</div>
	<!-- page header end -->
	
	<!-- contentarea begin -->
	<div>
		
		<!-- content area begin -->
		<div>
			{include file=$bodyContent}
			<div class="prClr2"></div>
		</div>
		<!-- content area end -->
		
		<!-- postcontent -->
		<div>
			{footer}
		</div>
		<!-- /postcontent -->
		
	</div>
	<!-- contentarea end -->
	
	<!-- extra begin -->
	<div>
		{if $bodyContent =="index/index_anonymous.tpl"}
			<!-- register begin -->
			{include file="_design/menu/registernow.tpl"}
			<!-- register end -->
			<!-- login form begin -->
			{loginnow}
			<!-- login form end -->
		{else}
			<!-- sponsored links begin -->
			{if $bodyContent !="registration/index.tpl"} {include file="_design/menu/registernow.tpl"}{/if}
			{if $bodyContent !="users/login.tpl"}{loginnow}{/if}
			<!-- sponsored links end -->
		{/if}
	</div>
	<!--extra end -->
	
	
	<!-- footer begin -->
	<div>
		{t}Copyright{/t} &copy; 2008, <a href="http://{$BASE_HTTP_HOST}">{$SITE_NAME_AS_STRING}</a>
	</div>
	<!-- footer end -->
	
	<!--------------- !!!!!!!!!!!!!!!!!!!!!!!!! ------->
	<!-- popup -->
	<div id="ajaxMessagePanel" style="visibility:hidden; display:none;">
	    <div class="hd">
	        <div class='tl'></div>
	            <span id="ajaxMessagePanelTitle">{t}Message{/t}</span>
	        <div class='tr'></div>
	    </div>
	    <div class="bd" id="ajaxMessagePanelContent"></div>
	    {*<div class="ft"> </div>*}
	</div>

	<!-- popup -->
	<div style="visibility:hidden; display:none; position:absolute;" id="ajaxMessageAlert">
		<h5 id="ajaxMessageAlertContent"></h5>
	</div>
	<!-- /popup -->
	<!--------------- !!!!!!!!!!!!!!!!!!!!!!!!! ------->
	
	<!-- dom ready -->
	<script type="text/javascript">
	    YAHOO.util.Event.onDOMReady(MainApplication.init);
	    {$AjaxAlertJsCode}
	</script>
	<!-- /dom ready -->
	
	<!-- google begin -->
	{if $GOOGLE_ANALYTICS=='on' && $GOOGLE_ANALYTICS_ID}
	<script type="text/javascript">
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
{literal} try { {/literal}
var pageTracker = _gat._getTracker("{$GOOGLE_ANALYTICS_ID}");
pageTracker._trackPageview();
{literal} } catch(err) {} {/literal}</script>
	{/if}
	<!-- google end -->
	
</div>
{if $TRACER_CODE}
<script type="text/javascript">
{$TRACER_CODE}
</script>
{/if}
{if $Warecorp->isTranslateOnlineDebugMode()}
<script type="text/javascript" src="{$AppTheme->common->js}/translate/tools.js" ></script>
{/if}

</body>
<!-- page body end -->
</html>
