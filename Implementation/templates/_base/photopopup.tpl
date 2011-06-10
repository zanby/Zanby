<html>
<head>
<title></title>
{literal}
<script language="javascript">
	function resWin() {
		s = document.images[0];
		w = window;
		window.height = s.height+90;
		window.width = s.width+30;
		w.resizeTo(window.width,window.height);
		w.toolbar = 0;
		w.menubar = 0;
	}
</script>
{/literal}
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="resWin();">
<table align="center" valign="middle" height="100%">
<tr><td valign="center" align="center">
<b>{$photo->getTitle()}</b>
<a href="#" onClick="window.close(); return false;">
	<img src="{$photo->getSrc()}_orig.jpg" border=0 title="Click to close" style="border:1px solid #000000"></a>
</td></tr>
</table>

{if $GOOGLE_ANALYTICS=='on' && $GOOGLE_ANALYTICS_ID}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." :
"http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost +
"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("{$GOOGLE_ANALYTICS_ID}");
pageTracker._initData();
pageTracker._trackPageview();
</script>
{/if}
</body>
</html>
