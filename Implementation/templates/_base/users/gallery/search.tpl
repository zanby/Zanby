<script type="text/javascript">YAHOO.namespace("example.container");</script>
{if $AccessManager->canCreateGallery($currentUser, $user)}
	{assign var="addLink" value=$currentUser->getUserPath('gallerycreate/step/1')}
{/if}


<div>
	<div class="prInner">
          <div class="prGrayBorder prInner">
            {include file="users/gallery/searchform.tpl"}
          </div>
	</div>
    <div>
		<div>
			<div class="prInnerTop3">
				<h3>{t}Photos By Country{/t}</h3>
				<p class="prInnerTop">
				{foreach from=$countryList item=country name=countries}
					<a href="{$user->getUserPath('photossearch')}preset/country/id/{$country.id}/">{$country.name}</a>{if !$smarty.foreach.countries.last}, {/if}
				{/foreach}
				</p>
				<p class="prInnerTop">
					<a href="{$user->getUserPath('photossearch')}view/countries/">{t}All Countries{/t}</a>
				</p>
			</div>
			<div class="prInnerTop3">
				  <h3>{t}Photos In Top World Cities{/t}</h3>
				  <p class="prInnerTop">
					{foreach from=$cityList item=city name=cities}
					<a href="{$user->getUserPath('photossearch')}preset/city/id/{$city.id}/">{$city.name}</a>{if !$smarty.foreach.cities.last}, {/if}
					{/foreach}
				  </p>
				  <p class="prInnerTop">
				  <a href="{$user->getUserPath('photossearch')}view/cities/">{t}All World Cities{/t}</a>
				  </p>
			  </div>

              <div class="prInnerTop3">
                  <h3>{t}Browse Photos By My Tags{/t}</h3>
                  <div class="prInnerTop">
                    {foreach item=m key=k from=$tags name=myTags}
                        {if $m.rating>80}
                             <a href="{$user->getUserPath('photossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                        {elseif $m.rating>60}
                             <a style="font-size:1.2em;" href="{$user->getUserPath('photossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                        {elseif $m.rating>40}
                             <a style="font-size:1em;" href="{$user->getUserPath('photossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                        {elseif $m.rating>20}
                             <a style="font-size:0.9em;" href="{$user->getUserPath('photossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                        {else}
                             <a style="font-size:0.8em;" href="{$user->getUserPath('photossearch')}preset/tag/id/{$k}/">{$m.obj->name|escape:html}</a>
                        {/if}
                        {if !$smarty.foreach.myTags.last} {/if}
                    {/foreach}
                   </div>
        		</div>
		</div>
    </div>
    <div>
    	<div class="prInnerRight prInnerTop">
             <h3>{t}{tparam value=$SITE_NAME_AS_STRING}New Photos On %s{/t}</h3>
				{if $photosList.friends}
                <div class="prInnerSmallTop">
						{t}{tparam value=$currentUser->getUserPath('photossearch')}From <a href="%spreset/new/whouploaded/2/sort/1">My Friends</a>{/t}
						<div class="prClr2">
						{foreach item=p name='photos' from=$photosList.friends}
							 <div class="prFloatLeft">
								<a href="{$p->getPhotoPath()}" id="img_{$p->getId()}friends"><img src="{$p->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" /></a>
								<script>
									YAHOO.example.container.img_{$p->getId()}friendsX = new YAHOO.widget.Tooltip("img_{$p->getId()}friendsX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}friends", text:"{$p->getGallery()->getTitle()|nl2br|escape:javascript} Gallery<br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
								</script>
							</div>
						{foreachelse}
							{t}No Friend's Photos at all{/t}
						{/foreach}
						</div>
                	</div>
                {/if}
                {if $photosList.groups}
				<div class="prInnerSmallTop">
					{t}{tparam value=$currentUser->getUserPath('photossearch')}From <a href="%spreset/new/whouploaded/3/sort/1">My Groups</a>{/t}
					<div class="prClr2">
					{foreach item=p name='photos' from=$photosList.groups}
						 <div class="prFloatLeft">
							<a href="{$p->getPhotoPath()}" id="img_{$p->getId()}groups"><img src="{$p->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" />
							<script>
								YAHOO.example.container.img_{$p->getId()}groupsX = new YAHOO.widget.Tooltip("img_{$p->getId()}groupsX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}groups", text:"{$p->getGallery()->getTitle()|nl2br|escape:javascript} Gallery<br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
							</script>
						</div>
					{foreachelse}
						{t}No Photos in my groups at all{/t}
					{/foreach}
					</div>
				</div>
                {/if}
                {if $photosList.families}
                 <div class="prInnerSmallTop">
				 	{t}{tparam value=$currentUser->getUserPath('photossearch')}From <a href="%spreset/new/whouploaded/4/sort/1">My Group Families</a>{/t}
					<div class="prClr2">
                    {foreach item=p name='photos' from=$photosList.families}
						<div class="prFloatLeft">
							<a href="{$p->getPhotoPath()}" id="img_{$p->getId()}families"><img src="{$p->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" /></a>
                            <script>
                                YAHOO.example.container.img_{$p->getId()}familiesX = new YAHOO.widget.Tooltip("img_{$p->getId()}familiesX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}families", text:"{$p->getGallery()->getTitle()|nl2br|escape:javascript} Gallery<br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
                            </script>
                        </div>
                     {foreachelse}
                        {t}No Photos in my group families at all{/t}
                    {/foreach}
					</div>
                </div>
                {/if}
                {if $photosList.anyone}
                <div class="prInnerSmallTop">
					{t}From {tparam value=$currentUser->getUserPath('photossearch')}<a href="%spreset/new/whouploaded/1/sort/1">Anyone</a>{/t}
					<div class="prClr2">
                    {foreach item=p name='photos' from=$photosList.anyone}
                        <div class="prFloatLeft">
                            <a href="{$p->getPhotoPath()}" id="img_{$p->getId()}any"><img src="{$p->setWidth(23)->setHeight(23)->getImage()}" class="prGrayBorder" /></a>
                            <script>
                                YAHOO.example.container.img_{$p->getId()}anyX = new YAHOO.widget.Tooltip("img_{$p->getId()}anyX", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$p->getId()}any", text:"{$p->getGallery()->getTitle()|nl2br|escape:javascript} Gallery<br />{$p->getDescription()|nl2br|escape:javascript}"{$smarty.rdelim});
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
