<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="{$KEYWORDS|escape:html}" />
<meta name="description" content="{$DESCRIPTION|escape:html}" />
{if $GEOPOSITION}<meta name="geo.position" content="{$GEOPOSITION}" />{/if}
{if $GEOPLACENAME}<meta name="geo.placename" content="{$GEOPLACENAME}" />{/if}
{if $GEOREGION}<meta name="geo.region" content="{$GEOREGION}" />{/if}
<title>{$TITLE}</title>

<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/main.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/prmaster.css" media="screen" />
<link rel="shortcut icon" type="image/x-icon" href="{$AppTheme->images}/favicon.ico">
<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/prmaster.css" media="print" />

{literal}
<style type="text/css">
.prPrintBody {margin: 20px; text-align: left;}
.prPrintBody h1 {color: #000000!important;}
.prPrintBody h2 {color: #000000!important; margin: 0; padding: 0; font-size: 18px;}
.prPrintBody td {padding: 10px; vertical-align: top;}
.prPrintBody thead th {padding: 20px 10px;}
.prResultPrint {width: 100%;border-collapse: collapse; border-top: 2px dashed #000000; margin: 10px 0 20px;}
</style>
<style type="text/css" media="print">
.prButton {display: none;}
h1 {font-size: 22px; font-weight: bold;}
h2 {font-size: 15px!important; font-weight: bold;}
</style>

<script type="text/javascript">
    function printEventDetails() {
        window.print();
    }
</script>
{/literal}

</head>
<body {$body_attributes}>
    <div class="prPrintBody">
        {include file=$bodyContent}
    </div>
</div>
<!-- cover end -->


{if $GOOGLE_ANALYTICS=='on' && $GOOGLE_ANALYTICS_ID}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ?"https://ssl." :
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
{if $TRACER_CODE}
<script type="text/javascript">
{$TRACER_CODE}
</script>
{/if}
</body>
</html>
