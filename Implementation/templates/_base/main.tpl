<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"{if $FACEBOOK_USED} xmlns:fb="http://www.facebook.com/2008/fbml"{/if}>
<head>
    <title>{$TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="{$KEYWORDS|escape:html}" />
	<meta name="description" content="{$DESCRIPTION|escape:html}" />
	{if $GEOPOSITION}<meta name="geo.position" content="{$GEOPOSITION}" />{/if}
	{if $GEOPLACENAME}<meta name="geo.placename" content="{$GEOPLACENAME}" />{/if}
	{if $GEOREGION}<meta name="geo.region" content="{$GEOREGION}" />{/if}
	<link rel="shortcut icon" type="image/x-icon" href="{$AppTheme->images}/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/main.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/layout.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/prmaster.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/yui_container.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/yui-menu.css" media="screen" />
	<!-- local css -->
	<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/photos.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/flashupload.css" media="screen" />
	{* AppTheme *}
	<script type="text/javascript">
		AppTheme = {$AppThemeJson};
	</script>
	{* AppTheme *}
	<script type="text/javascript" src="{$AppTheme->js}/iepngfix_tilebg.js" ></script>
	<link rel="stylesheet" type="text/css" href="{$AppTheme->common->js}/jquery/menu/fg.menu.css" media="screen" />
    {include file="bugzilla.tpl"}
	<script type="text/javascript" src="{$JS_URL}/common.js"></script>
	<script type="text/javascript" src="{$JS_URL}/yui/yahoo/yahoo-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/event/event-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/dom/dom-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/animation/animation.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/connection/connection-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/container/container_core-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/container/container-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/yui/menu/menu-min.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/json/json.js" ></script>
	{*<script type="text/javascript" src="{$JS_URL}/geometry.js" ></script>*}
	<script type="text/javascript" src="{$JS_URL}/content.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/BrowserDetect.js" ></script>
	<script type="text/javascript" src="{$JS_URL}/MainApplication.js" ></script>
    <!-- thickbox -->
    <link rel="stylesheet" type="text/css" href="{$AppTheme->css}/thickbox.css" media="screen" />
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/jquery-1.3.2.js" ></script>
	<script type="text/javascript" src="{$AppTheme->common->js}/jquery/jquery.ellipsis-1.0.js" ></script>
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/thickbox/thickbox.js" ></script>
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/thickbox/thickbox-app-custom.js" ></script>

    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/menu/fg.menu.js"></script>
    <script type="text/javascript" src="{$AppTheme->common->js}/menu/groups-menu.js" ></script>

    <!-- START XAJAX -->
	{$XajaxJavascript}
    <!-- STOP XAJAX-->
</head>
<body {$onload_attributes} {$body_attributes}>   
{if $FACEBOOK_USED}
<script type="text/javascript">var FACEBOOK_APP_ID = "{$FACEBOOK_APP_ID}"; var FACEBOOK_SESSION_STATE = {$FACEBOOK_SESSION_STATE};</script>
<script src="http://connect.facebook.net/en_US/all.js#appId={$FACEBOOK_APP_ID}&amp;xfbml=1"></script>
<div id="fb-root"></div>
<script type="text/javascript" src="{$AppTheme->common->js}/fb/connect.js" ></script>
{literal}
<script type="text/javascript">
	{/literal}{assign_adv var="url_change_connection_state" value="array('controller' => 'facebook', 'action' => 'changeconnectionstate')"}{literal}
	FBCfg.url_change_connection_state = '{/literal}{$Warecorp->getCrossDomainUrl($url_change_connection_state)}{literal}';
</script>
{/literal}
{/if}

<div class="prMain">
{popup_init src="$JS_URL/overlib/overlib.js"}

{include file="header.tpl"}

<div class="prLayout-headerNav"><div class="prLayout-inner">
    <div class="prClr2">
	<a href="{$BASE_URL}/{$LOCALE}/index/" class="prLogoBottom"><img class="pngFixIE" src="{$AppTheme->images}/logo_bottom.png" alt="Logo" /></a>
		<div class="prFloatRight">
			<div class="prGlobalNav prClr">
                {menu_global}
			</div>
		</div>
    </div>
</div></div>


{menu_bread_crumb}


<div class="prLayout-content prClr3"><div class="prLayout-inner">
    <div class="prContent"><div class="prContent-inner">
			{menu_submenu output='menu_submenu_out'}
			{if $menu_submenu_out !== ''}
				<div class="prContentHeader">
					<div class="prContentHeaderInner prClr2">
						<div class="prSubNav additional">
							{$menu_submenu_out}
						</div>
							{ButtonPanel}
								{*ButtonPanel_Render}{/ButtonPanel_Render*}
								{ButtonPanel_Render groupId='Default'}{/ButtonPanel_Render}
								{ButtonPanel_Render groupId='GroupLinks'}{/ButtonPanel_Render}
								{ButtonPanel_Render groupId='GroupInfo'}{/ButtonPanel_Render}
								{*ButtonPanel_Render groupId='Icons'}{/ButtonPanel_Render*}
							{/ButtonPanel}
					</div>
				</div>
					<div class="prContentBlock"><div class="prContentBlockInner">						
						{include file=$bodyContent}
					</div></div>
			{else}
				<div class="prContentHeaderWhite">
					<div class="prContentHeaderInnerWhite prClr2">
						{$menu_submenu_out}
					</div>
				</div>
					<div class="prContentBlock"><div class="prContentBlockInner">
						{ButtonPanel}
							{*ButtonPanel_Render}{/ButtonPanel_Render*}
							{ButtonPanel_Render groupId='Default'}{/ButtonPanel_Render}
							{ButtonPanel_Render groupId='GroupLinks'}{/ButtonPanel_Render}
							{ButtonPanel_Render groupId='GroupInfo'}{/ButtonPanel_Render}
							{*ButtonPanel_Render groupId='Icons'}{/ButtonPanel_Render*}
						{/ButtonPanel}
						{include file=$bodyContent}
					</div></div>
			{/if}
		<div class="prContentFooter">
			<div class="prContentFooterInner">
				&#160;
			</div>
		</div>
	</div></div>

    {menu_left}

</div></div>

<div class="prLayout-footer"><div class="prLayout-inner">
    <div class="prFooterContent">
		<div class="prFooterNav">
            {menu_bottom}&#160;
		</div>
		<div class="prFooterNav_rightLogo"><a href="http://www.zanby.com"><img class="pngFixIE" height="40" border="0" width="50" alt="Zanby Powered" src="{$AppTheme->images}/zanby_powered.png" /></a></div>
    </div>
</div></div>
</div>

{* jQuery for DropDown Nav *}
{* jQuery for Ellipsis for FF *}
{literal}
<script type="text/javascript">
	$(document).ready(function(){
		
		$("ul.prTopSubNav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled - Adds empty span tag after ul.prTopSubNav
		$("ul.prTopNav li span").parent().hover(function() {
						}, function(){
					$(this).parent().find("ul.prTopSubNav").hide();
				});
		$(".prDropDown span").click(function () {
			$(this).parent().find("ul.prTopSubNav").toggle();
		}).hover(function() { 
				$(this).addClass("subhover"); //On hover over, add class "subhover"
			}, function(){	//On Hover Out
				$(this).removeClass("subhover"); //On hover out, remove class "subhover"
		});
			$(".prDropDown").hover(
			function() {
				$(this).addClass("prSubHover");
			},
			function() {
			$(this).removeClass("prSubHover");
			}
		);
		if ($.browser.mozilla) {
			$('.ellipsis_init').ellipsis();
		} 
	});
</script>
{/literal}


{*  START : OLD CONTENT *}
<div id="ajaxMessagePanel" style="visibility:hidden; display:none;">
    <div class="hd"><div class='tl'></div><span id="ajaxMessagePanelTitle">{t}Message{/t}</span><div class='tr'></div></div>
    <div class="bd" id="ajaxMessagePanelContent"></div>{*<div class="ft"> </div>*}
</div>

<div class="prPopUp2" style="visibility: hidden; position: absolute; width: 250px; height: 50px; top: -55px; left: 100px; display: none;" id="ajaxMessageAlert">
    <div><h5 id="ajaxMessageAlertContent"></h5></div>
</div>

<script type="text/javascript">
    YAHOO.util.Event.onDOMReady(MainApplication.init);
    {$AjaxAlertJsCode}
	{literal}
		YAHOO.util.Event.onDOMReady(function(){if (document.getElementById('DOMReadyIE')) { document.getElementById('DOMReadyIE').className='DOMReadyIE';}});
	{/literal}
</script>

{* Warecorp_Facebook_Feed::onPageInit placeholder; @author Artem Sukharev *}
{if $FACEBOOK_USED}
<script type="text/javascript">
{$fbJsInit}
</script>
{/if}

{$strWP_ZSSO_IFrame}

{if $GOOGLE_ANALYTICS=='on' && $GOOGLE_ANALYTICS_ID}
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
{*  END : OLD CONTENT *}
{if $TRACER_CODE}
<script type="text/javascript">
{$TRACER_CODE}
</script>
{/if}
{if $Warecorp->isTranslateOnlineDebugMode()}
<script type="text/javascript" src="{$AppTheme->common->js}/translate/tools.js" ></script>
{/if}


</body>
</html>
