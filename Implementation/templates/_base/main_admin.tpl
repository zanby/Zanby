<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$TITLE}</title>
    <link rel="stylesheet" type="text/css" href="{$AppTheme->css}/main.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{$AppTheme->css}/prmaster.css" media="screen" />

    {* AppTheme *}
    <script type="text/javascript">
        AppTheme = {$AppThemeJson};
    </script>
    {* AppTheme *}
    <script type="text/javascript" src="{$JS_URL}/common.js" ></script>
    <script type="text/javascript" src="{$JS_URL}/content.js" ></script>

    <link rel="stylesheet" type="text/css" href="{$AppTheme->css}/thickbox.css" media="screen" />
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/jquery-1.3.2.js" ></script>
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/jquery.ellipsis-1.0.js" ></script>
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/thickbox/thickbox.js" ></script>
    <script type="text/javascript" src="{$AppTheme->common->js}/jquery/thickbox/thickbox-app-custom.js" ></script>

    {$XajaxJavascript}
</head>
    <body {$onload_attributes} {$body_attributes}><div class="prMainAdmin">
    <!-- content area begin -->
        {if !$logined}
            {if isset($bodyContent)}{include file=$bodyContent}{/if}
        {else}
            <div class="prTRight">Welcome, <span>{$admin->getLogin()} ({$admin->getRole()})</span> <a href="{$admin->getAdminPath('logout')}">logout</a></div>
            {tab template="tabs2" active=$active}
                {if $actionsList.settings==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/settings/" name="settings"}{t}Settings{/t}{/tabitem}
                {/if}
                {if $actionsList.members==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/members/" name="members"}{t}Members{/t}{/tabitem}
                {/if}
                {if $actionsList.groups==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groups/" name="groups"}{t}Groups{/t}{/tabitem}
                {/if}

                {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/families/" name="families"}{t}Group Families{/t}{/tabitem}

                {if $actionsList.templates==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/templates/" name="templates"}{t}Templates{/t}{/tabitem}
                {/if}
                {if $actionsList.log==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/log/" name="log"}{t}Show log{/t}{/tabitem}
                {/if}
                {if $actionsList.translate==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/translate/" name="translate"}{t key='main_admin_10'}Translate{/t}{/tabitem}
                {/if}
                {if $actionsList.rss==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/rss/" name="translate"}{t}RSS{/t}{/tabitem}
                {/if}
                {if $actionsList.about==1}
                    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/about/" name="about"}{t}About{/t}{/tabitem}
                {/if}
            {/tab}

            {if isset($bodyContent)}
                {include file=$bodyContent}
            {/if}
        {/if}
    </div></body>
</html>
