<script type="text/javascript">YAHOO.namespace("example.container");</script>
{assign var="addLink" value=$currentUser->getUserPath('videogallerycreate/step/1')}

{if $AccessManager->canCreateGallery($currentUser, $user)}
    {assign var="addLink" value=$currentUser->getUserPath('videogallerycreate/step/1')}
    {assign var="addLinkName" value="Upload Video"}    
{/if}

<!---BUUU--->

<div>
	<div class="prInner">
    	<div class="prGrayBorder prInner">
        {include file="users/videogallery/searchform.tpl"}
    	</div>
	</div>
    <div>
		<div>
        <div class="prIndentBottom13">
            <h3>{t}Videos By Country{/t}</h3>
            <p class="prInnerTop">
                {foreach from=$countryList item=country name=countries}
					<a href="{$user->getUserPath('videossearch')}preset/country/id/{$country.id}/">{$country.name}</a>{if !$smarty.foreach.countries.last}, {/if}
                {/foreach}
            </p>
            <p class="prInnerTop">
                <a href="{$user->getUserPath('videossearch')}view/countries/">{t}All Countries{/t}</a>
            </p>
        </div>
        <div class="prIndentBottom13">
            <h3>{t}Videos In Top World Cities{/t}</h3>
            <p class="prInnerTop">
                {foreach from=$cityList item=city name=cities}
                    <a href="{$user->getUserPath('videossearch')}preset/city/id/{$city.id}/">{$city.name}</a>{if !$smarty.foreach.cities.last}, {/if}
                {/foreach}
            </p>
            <p class="prInnerTop">
                <a href="{$user->getUserPath('videossearch')}view/cities/">{t}All World Cities{/t}</a>
            </p>
        </div>
        <div class="prIndentBottom13">
            <h3>{t}Browse Videos By My Tags{/t}</h3>
            <div class="prInnerTop">
				{foreach item=m key=k from=$tags name=myTags}
                    {if $m.rating>80}
                        <a href="{$user->getUserPath('videossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                    {elseif $m.rating>60}
                        <a style="font-size:1.2em;" href="{$user->getUserPath('videossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                    {elseif $m.rating>40}
                        <a style="font-size:1em;" href="{$user->getUserPath('videossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                    {elseif $m.rating>20}
                        <a style="font-size:0.9em;" href="{$user->getUserPath('videossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                    {else}
                        <a style="font-size:0.8em;" href="{$user->getUserPath('videossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                    {/if}
                    {if !$smarty.foreach.myTags.last} {/if}</li>
                {/foreach}
			</div>
        </div>
		</div>
    </div>
    <div>
        <div class="prIndentBottom prInnerTop">
            <h3>{t}{tparam value=$SITE_NAME_AS_STRING}New Videos On %s{/t}</h3>
            {if $videosList.friends}
                <div class="prInnerSmallTop">
                    <div>{t}From{/t} <a href="{$currentUser->getUserPath('videossearch')}preset/new/whouploaded/2/sort/1">{t}My Friends{/t}</a></div>
					<div class="prClr2">
                    {foreach item=p name='videos' from=$videosList.friends}
                        <div class="prFloatLeft">
                            <a href="{$p->getVideoPath()}" id="img_{$p->getId()}friends"><img height="23" width="23" src="{$p->getCover()->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" /></a>
                            <script>
                                YAHOO.example.container.img_{$p->getId()}friendsX = new YAHOO.widget.Tooltip("img_{$p->getId()}friendsX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}friends", text:"{$p->getTitle()|nl2br|escape:javascript} <br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
                            </script>
                        </div>
                    {foreachelse}
                        {t}No Friend's videos at all{/t}
                    {/foreach}
					</div>
                </div>
            {/if}

            {if $videosList.groups}
               <div class="prInnerSmallTop">
                    <div>{t}From{/t} <a href="{$currentUser->getUserPath('videossearch')}preset/new/whouploaded/3/sort/1">{t}My Groups{/t}</a></div>
					<div class="prClr2">
                    {foreach item=p name='videos' from=$videosList.groups}
                        <div class="prFloatLeft">
                            <a href="{$p->getVideoPath()}" id="img_{$p->getId()}groups"><img height="23" width="23" src="{$p->getCover()->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" />
                            <script>
                                YAHOO.example.container.img_{$p->getId()}groupsX = new YAHOO.widget.Tooltip("img_{$p->getId()}groupsX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}groups", text:"{$p->getTitle()|nl2br|escape:javascript} <br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
                            </script>
                        </div>
                    {foreachelse}
                        {t}No videos in my groups at all{/t}
                    {/foreach}
					</div>
                </div>
            {/if}

			{if $videosList.families}
                <div class="prInnerSmallTop">
                    <div>{t}From{/t} <a href="{$currentUser->getUserPath('videossearch')}preset/new/whouploaded/4/sort/1">{t}My Group Families{/t}</a></div>
					<div class="prClr2">
                    {foreach item=p name='videos' from=$videosList.families}
                        <div class="prFloatLeft">
                            <a href="{$p->getVideoPath()}" id="img_{$p->getId()}families"><img height="23" width="23" src="{$p->getCover()->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" /></a>
                            <script>
                                YAHOO.example.container.img_{$p->getId()}familiesX = new YAHOO.widget.Tooltip("img_{$p->getId()}familiesX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}families", text:"{$p->getTitle()|nl2br|escape:javascript} <br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
                            </script>
                        </div>
                    {foreachelse}
                        {t}No videos in my group families at all{/t}
                    {/foreach}
					</div>
                </div>
            {/if}
            {if $videosList.anyone}
                <div class="prInnerSmallTop">
                    <div>{t}From{/t} <a href="{$currentUser->getUserPath('videossearch')}preset/new/whouploaded/1/sort/1">{t}Anyone{/t}</a></div>
					<div class="prClr2">
                    {foreach item=p name='videos' from=$videosList.anyone}
                        <div class="prFloatLeft">
                            <a href="{$p->getVideoPath()}" id="img_{$p->getId()}any"><img height="23" width="23" src="{$p->getCover()->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" /></a>
                            <script>
                                YAHOO.example.container.img_{$p->getId()}anyX = new YAHOO.widget.Tooltip("img_{$p->getId()}anyX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}any", text:"{$p->getTitle()|nl2br|escape:javascript} <br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
                            </script>
                        </div>


                    {/foreach}
					</div>
                </div>
            {/if}
		</div>
    </div>
</div>
        <!-- inner end -->

