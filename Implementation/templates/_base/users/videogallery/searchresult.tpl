<div>

	<div class="prInner">
	  <div class="prGrayBorder prInner">
		{include file="users/videogallery/searchform.tpl"}
	  </div>
	</div>

    <div class="prInnerBottom">
		<h3> {if $keywords != ""}{t}Video Collections about{/t}{else}{t}Video Collections{/t}{/if} {$keywords|escape:"html"} {t}{tparam value=$whoUploadedByString}Uploaded By %s{/t}</h3>
		<div class="prInnerTop prIndentBottom_15">
		{t}Show:{/t}
		<select onchange="document.location.href='{$user->getUserPath('videossearch')}preset/old/sort/' + this.options[this.selectedIndex].value; return false;">
		{foreach from=$sortList item=sortItem key=key}
		<option {if $sort == $key}selected {/if}value="{$key}">{$sortItem}</option>
		{/foreach}
		</select>
		</div>

{if $videosList}
        <div class="prGrayBorder prClr2">
        <!-- search result begin -->
            <div>
				<div class="prIndentBottom">
		  		{$paging}
				</div>

          		<div class="prGrayBorder prInnerLeft prClr2">
	      		{foreach from=$videosList name=videos item=video key=id}
	      		<!--{if $smarty.foreach.videos.first}<ul>{/if}-->

				<!--<li onmouseover="this.className= 'Current'" onmouseout="this.className= ''">-->
                	<div class="prFloatLeft">
                    	<div>
							<div>
							</div>
							<div>
								<div>
									<div class="prGrayBorder">
									<a href="{$video->getVideoPath()}"><img  height="100" width="100" src="{$video->getCover()->setWidth(100)->setHeight(100)->getImage($currentUser)}" /></a>
									</div>
									<div class="prInnerSmallTop">
									<a href="{$video->getVideoPath()}">{$video->getTitle()|escape:"html"}</a>
									</div>
									<div class="prInnerSmallTop">
										{$video->getLinksForTags(12,$user)}
									</div>
									<div class="prInnerSmallTop">
										<div class="prIndentBottom">
										{t}Posted{/t} {$video->getCreateDate()|date_locale:'DATE_MEDIUM'}<br />
										{t}by{/t}	<a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:"html"}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
            <!--</li>-->
			<!--{if $smarty.foreach.videos.last}</ul>{/if} -->
	      {foreachelse}
			<!--<div class="prInnerTop">No videos in search result</div>-->
	      {/foreach}
		  </div>
		  		<div class="prInnerSmallTop">
	      		{$paging}
				</div>
	      </div>
		</div>
	{else}
		<div class="prInnerTop">{t}No videos in search result{/t}</div>
	{/if}
    </div>
</div>