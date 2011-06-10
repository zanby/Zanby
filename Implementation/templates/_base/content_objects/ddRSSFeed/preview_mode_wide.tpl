<div class="themeA">
    {include file="content_objects/headline_block_view.tpl"}
    
    {if !$rss_hash && !$title}
        <div class="prInner">
        {if $isInternalFeed}
                {assign var="_slocale" value=/$LOCALE/}
                <p>{t}This is the internal link. Please follow it to view local content.{/t}</p>
                <p><a href="{$rss_url|replace:'/rss/':$_slocale|escape:'html'}"><span class="text">{$rss_url|replace:'/rss/':$_slocale|escape:'html'}</span></a></p>
        {else}
            <p>{include file="content_objects/ddRSSFeed/error_message.tpl"}</p>
        {/if}
        </div>
    {else}
        
        
        <div class="prInner">
            <h4>{$title|escape:"html"}</h4>
            {section name=record loop=$rss_hash}
                <p>
                    <a href="{$rss_hash[record].href}" target="_blank" style="{if $rss_header_font!=''}font-family:{$rss_header_font};{/if}
                    {if $rss_header_color!=''}color:{$rss_header_color};{/if}{if $rss_header_font_size!=''}font-size:{$rss_header_font_size}px;{/if}">{$rss_hash[record].title|escape:"html"}</a>
                    {if $rss_hash[record].content}<br/><span style="{if $rss_description_font!=''}font-family:{$rss_description_font};{/if}
                    {if $rss_description_color!=''}color:{$rss_description_color};{/if}{if $rss_description_font_size!=''}font-size:{$rss_description_font_size}px;{/if}">{$rss_hash[record].content}</span><br/><br/>{/if}
                </p>
            {/section}
        </div>
     {/if}
</div>