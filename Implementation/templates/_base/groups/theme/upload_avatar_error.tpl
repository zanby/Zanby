{*popup_item*}{*probably AJAX*}
{literal}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body {margin: 0;}
.prFormErrors {
	border: 1px solid #f2bfbf;
	padding: 5px;
	font: 0.7em arial, sans-serif;
}
-->
</style>
</head>
<body>
{/literal}
<div>
{if $error}{$error|escape:'html'}{else}
{t}Error occured.{/t}
{/if}
</div>
{if $close}
<script>
window.top.popup_window.close();
//window.top.ThemeApplication.removeBackgroundImage();
window.top.ThemeApplication.applyBackgroundImage('{$imageName}', '{$backgroundUrl}');
</script>
{/if}
</body>
</html>
{*popup_item*}
