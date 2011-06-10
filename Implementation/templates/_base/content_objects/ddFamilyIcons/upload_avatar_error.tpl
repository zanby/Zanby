{literal}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>
<body>
<h4 style="color:red; text-align:center">
{/literal}
{if $error}{$error|escape:'html'}{else}
{t}Error occured.{/t}
{/if}
</h4>
{if $close}
<script>
window.top.xajax_load_bgis({if $refresh}true{else}false{/if}{if $cloneId}, '{$cloneId}'{/if});
</script>
{/if}
</body>
</html>
