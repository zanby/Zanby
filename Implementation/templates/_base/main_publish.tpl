<html>
<head>
<base href="http://{$base_host}/">
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link rel="stylesheet" type="text/css" href="/css/default.css" media="screen"/>
	 <link rel="shortcut icon" type="image/x-icon" href="{$AppTheme->images}/favicon.ico">
</head>
<body bgcolor="#FFFFFF">
<!--
HEADER
-->
<table width="600">
<tr><td>
{$data}
</td>
</tr>
</table>
<!--
FOOTER
-->

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
</script>
{/if}
{if $TRACER_CODE}
<script type="text/javascript">
{$TRACER_CODE}
</script>
{/if}
</body>
</html>
