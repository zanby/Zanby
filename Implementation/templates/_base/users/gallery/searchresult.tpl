{if $AccessManager->canCreateGallery($currentUser, $user)}
	{assign var="addLink" value=$currentUser->getUserPath('gallerycreate/step/1')}
{/if}

<div>

	<div class="prInner">
	  	<div class="prGrayBorder prInner">
            {include file="users/gallery/searchform.tpl"}
         </div>
	</div>

    <div class="prInnerBottom">
		<h3>{if $keywords !=""}{t}Photo Galleries about{/t}{else}{t}Photo Galleries{/t}{/if} {$keywords|escape:"html"} {t}{tparam value=$whoUploadedByString}Uploaded By %s{/t}</h3>
        <div class="prInnerTop prIndentBottom">
        {t}Show:{/t}
        <select onchange="document.location.href='{$user->getUserPath('photossearch')}preset/old/sort/' + this.options[this.selectedIndex].value; return false;">
    	{foreach from=$sortList item=sortItem key=key}
    		<option {if $sort == $key}selected {/if}value="{$key}">{$sortItem}</option>
    	{/foreach}
    	</select>
        </div>

		{if $photosList}
        <!-- search result begin -->
        <div class="prGrayBorder prClr2">
        <!-- search result begin -->
            <div>
				<div class="prIndentBottom">
		  		{$paging}
				</div>

          		<div class="prGrayBorder prInnerTop2 prClr2">
	      		{foreach from=$photosList name=photos item=photo key=id}
	      			<!--{if $smarty.foreach.photos.first}<ul>{/if}
            		<li onmouseover="this.className= 'Current'" onmouseout="this.className= ''">-->
                	<div class="prFloatLeft">
                    	<div>
							<div>
							</div>
							<div>
								<div>
									<div class="prGrayBorder">
                    					<a href="{$photo->getPhotoPath()}"><img src="{$photo->setWidth(100)->setHeight(100)->getImage($currentUser)}" /></a>
									</div>
									<div class="prInnerSmallTop">
                    					<a href="{$photo->getPhotoPath()}">{$photo->getTitle()|escape:"html"}</a>
									</div>
									<div class="prInnerSmallTop">
                    					{$photo->getLinksForTags(12,$user)}
                					</div>
                    				<div class="prInnerSmallTop">
										<div>
                        					{t}Posted{/t} {$photo->getCreateDate()|date_locale:'DATE_MEDIUM'}<br />
											{t}{tparam value=$photo->getCreator()->getUserPath('profile')}{tparam value=$photo->getCreator()->getLogin()|escape:"html"}by <a href="%s">%s</a>{/t}
                    					</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			<!--{if $smarty.foreach.photos.last}</ul>{/if}  -->
	      {foreachelse}
				<!--No photos in search result-->
	      {/foreach}
			</div>
		  	<div class="prInnerSmallTop">
	      		{$paging}
	      	</div>
    	</div>
	</div>
	{else}
		<div class="prInnerTop">{t}No photos in search result{/t}</div>
	{/if}
    </div>
</div>