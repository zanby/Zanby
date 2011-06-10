{if $FACEBOOK_USED && !$_RSVP_ && !$user->getId()}
    {literal}
    <script type="text/javascript">
        {/literal}{assign_adv var="url_oncheck_rsvp_status_ready" value="array('controller' => 'facebook', 'action' => 'checkrsvpstatus')"}{literal}
        FBCfg.url_oncheck_rsvp_status_ready = '{/literal}{$Warecorp->getCrossDomainUrl($url_oncheck_rsvp_status_ready)}{literal}';
        $(function(){ 
            FBApplication.check_rsvp_status(0, 0); 
        })
    </script>
    {/literal}
{/if}


<div class="prInner prClr2">
    <!-- MapBlock -->  
    <div class="prMapBlock">
        <div class="prMap">
            <iframe 
                id="mapIframe" 
                frameborder="0" 
                style="width:100%; height:400px;" 
                src="/en/map/?listenZoomLevelChanges=1&wdtype=iniframe&switchGEScenario=search&wtype=map&height=400&defaultDisplayType=1&r=1243179692&mapCache={$mapCache}&clat={$clat}&clng={$clng}&mapType=G_MAP_OVERLAY"
            ></iframe>
        </div>
        <!-- /MapBlock  --> </div>                   
        <!-- left -->         
        <div class="prEGLandSearchPaging">
            <div class="prIndentBottomSmall prFloatRifgt">
                {$paging}     
            </div>
        </div>
        
        <div class="prEventList-left">
            {if $arrEvents}
                {foreach from=$arrEvents item='objEvent' name='events_i'}      
                {view_factory 
                    entity='event' 
                    view='mapView' 
                    object=$objEvent 
                    Warecorp_ICal_AccessManager=$Warecorp_ICal_AccessManager 
                    currentOwner=$CurrentGroup 
                    user=$user 
                    arrEventsLinks=$arrEventsLinks 
                    viewMode=$viewMode 
                    currentTimezone=$currentTimezone 
                    last=$smarty.foreach.events_i.last 
                    AppTheme=$AppTheme}
                {/foreach}
            {else}
            <div class="prNoEvents">{t}No Events{/t}</div>
            {/if}
                                  
            <div class="prEGLandSearchPaging">
                <div class="prIndentBottomSmall prIndentTop ">
                    {$paging}  
                </div>
            </div>
            <!-- /group event list -->          
        </div>

        <!-- left -->
        <!-- right -->
        <div class="prEventList-right">
            <h3>{t}All events tags:{/t}</h3>
            {foreach from=$lstTags->getAllList() item=t}
                <a href="{$BASE_URL}/{$LOCALE}/search/events/preset/new/keywords/{$t->name}/">({$t->currentCnt}) {$t->name|escape:html}</a><br />
            {foreachelse}
                {t}No Tags{/t}
            {/foreach}
        </div>
        <!-- right -->
</div>