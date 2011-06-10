{strip}
<style type="text/css">

	{*
	*
	*  PLEASE DON'T EDIT THIS TEMPLATE WITHOUT APPROVE FROM KOMAROVSKI
	*
	*}
	
	
	{* area background *}
	.prCOarea {$smarty.ldelim}
		{* background-color *}
		{if $theme->backgroundColor}
			background-color: {$theme->backgroundColor};
		{else}
			background-color: #edf3f8;
		{/if}
		{* background-image *}
		{if $theme->backgroundImage}
			background-image: url({$theme->backgroundUrl});
		{/if}
		{* background-repeat *}
		{if $theme->backgroundTile}
			background-repeat: repeat;
		{else}
			background-repeat: no-repeat;
		{/if}
	{$smarty.rdelim}
	
	
	
	{*.prWide .prCOarea {$smarty.ldelim}
		
	{$smarty.rdelim}
	*}
	
	
	
    .prContent-outer {$smarty.ldelim}
        {* background-color *}
        {if $theme->backgroundColor}
            background-color: {$theme->backgroundColor};
        {else}
            background-color: #edf3f8;
        {/if}
        {* background-image *}
        {if $theme->backgroundImage}
            background-image: url({$theme->backgroundUrl});
        {/if}
        {* background-repeat *}
        {if $theme->backgroundTile}
            background-repeat: repeat;
        {else}
            background-repeat: no-repeat;
        {/if}
    {$smarty.rdelim}	
	
	{* CO background and outline *}

    {*!!!!! AHTUNG !!!!! Don't touch znbContentObjectBordel !!!!!! *}

	.prContentObject,  td.znbContentObjectBordel, .prContentObjectComposeMode {$smarty.ldelim}
		border-color:{$theme->outlineColor};
		border-style:{$theme->outlineStyle};
		background-color:{if $theme->fillColorTransparent} transparent;{else}{$theme->fillColor};{/if}
		margin-bottom: 10px;
		overflow: hidden;
	    border-width:1px;
	{$smarty.rdelim}
	
	{*.prContent .prContentObject {$smarty.ldelim}
		width: 100% !important;
	{$smarty.rdelim}
	
	.prWidget0-conarrow {$smarty.ldelim}
		overflow: hidden;
		width: 100%;
	{$smarty.rdelim}
	*}
	
	
	{* CO body text *}
	.prContentObject p, .prContentObject div, .themeA, .themeA p, .themeA div {$smarty.ldelim}
		color:{$theme->bodyTextColor};
		font-family:{$theme->bodyTextFontFamily};
	{$smarty.rdelim}
	
	
	
	
	
	{*fix for displaying list elements in ddContentObject*}
	div.prContentObject div.li-cru ul li, div.themeA div.li-cru ul li{$smarty.ldelim}
        list-style-type:disc;
        margin-left: 40px;
    {$smarty.rdelim}
	
    div.prContentObject div.li-cru ol li, div.themeA div.li-cru ol li{$smarty.ldelim}
        list-style-type:decimal;
        margin-left: 40px;
    {$smarty.rdelim}
    
    {*fox for displaying content of CO ddContentObject (p height) *}
    .copfix p {$smarty.ldelim}margin-bottom:10px;{$smarty.rdelim}
    
    
    
    
    
	{* CO headline *}
	.prContentObject h3 , .themeA h3  {$smarty.ldelim}
		color:{$theme->headlineTextColor};
		font-family:{$theme->headlineTextFontFamily};
		font-size: 1.15em;
	{$smarty.rdelim}
	
	{* CO Link Color*}
    .prContentObject a, .themeA a {$smarty.ldelim}
        color:{$theme->linkColor};
    {$smarty.rdelim}
	
	
	
	
	
	
	
	
	
	{* CO Accent Text *}
	.prContentObject .prTColor9, .prContentObject .prSmallText {$smarty.ldelim}
		color:{$theme->commentColor};
		font-family:{$theme->commentFontFamily};
	{$smarty.rdelim}
	
	{* CO section header *}
	.prContentObject .prCOSectionHeader h3, .themeA .prCOSectionHeader h3, .prContentObject .prWidgetToogl h3, .prContentObject .prWidgetToogl a {$smarty.ldelim}
		color:{$theme->headerColor};
		font-family:{$theme->headerFontFamily};
	{$smarty.rdelim}
	
	{*.prContentObject .prButton {$smarty.ldelim}
		font-family: Arial, Helvetica, sans-serif;
	{$smarty.rdelim}
	
	.prContentObject .prCO-headline,  .prContentObject .prCO-headline .prHeaderTools{$smarty.ldelim}
		font-family: Arial, Helvetica, sans-serif;
	{$smarty.rdelim}
	*}
	
	
	
</style>
{/strip}