{*if $BUG_TRACKING_SYSTEM}
    <link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/ifb2.css" media="screen" />
    <script type="text/javascript">var ifbversion = "{$BUGZILLA_PROJECT_VERSION}"; var ifbproject = "{if $BUG_TRACKING_PROJECT_CODE}{$BUG_TRACKING_PROJECT_CODE}{else}{$BUGZILLA_PROJECT_NAME}{/if}";</script>
    {if $BUG_TRACKING_SYSTEM=="bugzilla"}
        <script type="text/javascript" src="{$JS_URL}/ifb2.js" ></script>
    {/if}
    {if $BUG_TRACKING_SYSTEM=="redmine"}
        <script type="text/javascript" src="{$JS_URL}/ifb2_redmine.js" ></script>
    {/if}
{/if*}