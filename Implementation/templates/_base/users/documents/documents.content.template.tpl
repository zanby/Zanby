{strip}
    {if !$foldersList && !$documentsList}
        <div class="prTCenter">{t}No Documents{/t}
    {else}    
        <table cellspacing="0" cellpadding="0" class="prTableDocsInner">
            <col width="5%" />
            <col width="45%" />
            <col width="28%" />
            <col width="22%" />
            {assign var="canManageOwnerDocuments" value=$AccessManager->canManageOwnerDocuments($currentUser, $currUser, $user->getId())}
            {foreach item=f name='folders' from=$foldersList}    
            <tr>
                <td class="">
                    {* ! class="folder-checkbox" - is required to correct work of js - don't change it ! *}
                    <input type="checkbox" {if !$canManageOwnerDocuments } disabled{/if} class="folder-checkbox prNoBorder" name="folders_ch[]" id="" value="{$f->getId()}" />
                </td>
                <td>
                    {* ! class="drag-source" - is required to correct work of js - don't change it ! *}
                    <div class='drag-source' folder="{$f->getId()}" owner="{$f->getOwnerId()}">
                        <img src="{$AppTheme->images}/documents/bg-mydocs-folder.gif" border="0" alt="" title="" class="prIndentRightSmall prVMiddle" />
                        <a href="javascript:void(0);" onclick="DocumentApplication.changeActiveFolderDirect({$f->getId()}); return false;" title="{$f->getName()|escape}">{$f->getName()|escape}</a>
                    </div>
                </td>
                <td></td>
                <td class="prText5">
                    {$f->getUpdateDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME_SHORT'}
                </td>
            </tr>
            {/foreach}
            {foreach item=d name='docs' from=$documentsList}
            <tr{if $smarty.foreach.docs.iteration % 2} class="prZebraBg2"{else} class="prZebraBg1"{/if}>
                <td class="">
                    {* ! class="item-checkbox" - is required to correct work of js - don't change it ! *}
                    <input type="checkbox" class="item-checkbox prNoBorder" name="files_ch[]" value="{$d->getId()}" 
                        {if $d->canBeCheckOut()} cancheckout='1'{else} cancheckout='0'{/if}
                        {if $d->canBeCheckIn()} cancheckin='1'{else} cancheckin='0'{/if}
                        {if $d->canBeCancelCheckOut()} cancancelcheckout='1'{else} cancancelcheckout='0'{/if}
                        {if $d->canBeRevision()} canrevision='1'{else} canrevision='0'{/if}
                        {if $d->canBeShared($currentUser, $currUser, $user->getId())} canshare='1'{else} canshare='0'{/if}
                        {if $d->canBeUnShared($currentUser, $user->getId())} canunshare='1'{else} canunshare='0'{/if}
                        {if $d->getIsLink()} weblink='1'{else} weblink='0'{/if}
                    />
                </td>
                <td>
                    {* ! class="drag-source" - is required to correct work of js - don't change it ! *}
                    <div {if !$d->getShare()}class='drag-source'{/if} document="{$d->getId()}">
					<div class="prEllipsis prDocTitle">
                        <img src="{$d->getIconImg()}" border="0" alt="" title="" class="prIndentRightSmall prVMiddle" />
                        {if $d->getIsLink()}
                            {if $d->getShare()}{t}(Shared){/t}&nbsp;{/if}
							<a href="{$d->getOriginalName()|escape}" target="_blank" title="{$d->getOriginalName()|escape} {$d->getDescription()} | {$d->getFileSize()}"><span>{if $d->getShare()}{$d->getOriginalName()|escape}{else}{$d->getOriginalName()|escape}{/if}</span></a>
                             | <span>{$d->getFileSize()}</span>
                        {else}
                            {if $d->getShare()}{t}(Shared){/t}&nbsp;{/if}
                            <a href="{$currentUser->getUserPath('docget')}docid/{$d->getId()}/"  id="docid{$d->getId()}" title="{$d->getOriginalName()|escape} {$d->getDescription()} | {$d->getFileSize()}">
                            <span>{if $d->getShare()}{$d->getOriginalName()|escape}{else}{$d->getOriginalName()|escape}{/if}</span> 
                            </a> | <span>{$d->getFileSize()}</span>
                        {/if}
						</div>
                    </div>
                </td>
                <td class="prText5">
					<div class="prEllipsis prDocChecked">
						{if $d->getIsCheckOut()}<img src="{$AppTheme->images}/documents/lock_icon.gif" class="prInnerSmallRight" width="9" height="10"/>{t}Checked out by{/t} <a href="{$d->getCheckOutUser()->getUserPath('profile')}" title="{$d->getCheckOutUser()->getLogin()|escape}">{$d->getCheckOutUser()->getLogin()|escape}</a>
						{else}{assign var='revision' value=$d->getLastRevision()}{if $revision }{t}Last modified by{/t} <a href="{$revision->getRevisionCreator()->getUserPath('profile')}" title="{$revision->getRevisionCreator()->getLogin()|escape}">{$revision->getRevisionCreator()->getLogin()|escape}</a>{else}{/if}{/if}
					</div>
				</td>
                <td class="prText5">
                    {$d->getUpdateDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME_SHORT'}
                </td>
            </tr>
            {/foreach}
        </table>
    {/if}
{/strip}