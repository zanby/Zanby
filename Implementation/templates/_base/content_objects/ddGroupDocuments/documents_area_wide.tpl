<!-- Content Object Documents -->
<div class="prCOCentrino">
<table cellspacing="0" cellpadding="0" width="100%">
    <col width="33%" />
    <col width="33%" />
    <col width="34%" />
    <tbody>
        <tr>
            {foreach name=documents from=$documents_ids item=id key=key}
            {assign var="myIteration" value=$smarty.foreach.documents.iteration-1}
            <td>
                <!-- Content object doc item -->
                <div class="prTCenter" id="document_{$cloneId}_{$key}">
                    {if $id}
                    <!-- -->
                    <div>
                        <a href="{if $documents_objects[$key]->getIsLink()}{$documents_objects[$key]->getOriginalName()}" target="_blank{else}{$currentUser->getUserPath("docget/docid")|cat:$documents_objects[$key]->getId()}{/if}">
                            <img src="{$documents_objects[$key]->getImageFileNameByExtension($documents_objects[$key]->getFileExt(), 'big')}" align="top" />
                        </a>
                        {if !$disable_click}
                            {if !($smarty.foreach.documents.first && $smarty.foreach.documents.last)}
                                <a href="#null" onclick="removeDDDocumentSlot('{$cloneId}', {$myIteration});return false;">
                                    <img src="{$AppTheme->images}/decorators/profile-marker.gif" alt="" title="" />
                                </a>
                            {else}
                                <a href="#null" onclick="removeSingleDDDocumentSlot('{$cloneId}', {$myIteration});return false;">
                                    <img src="{$AppTheme->images}/decorators/profile-marker.gif" alt="" title="" />
                                </a>
                            {/if}
                        {else}
                            <img src="{$AppTheme->images}/decorators/spacer.gif" alt="" title="" width="15" />
                        {/if}
                    </div>
                    <a href="{if $documents_objects[$key]->getIsLink()}{$documents_objects[$key]->getOriginalName()}" target="_blank{else}{$currentUser->getUserPath("docget/docid")|cat:$documents_objects[$key]->getId()}{/if}">
                       {$documents_objects[$key]->getOriginalName()|truncate:16|escape:'html'}
                    </a>
                    <br />
                    <span>{$documents_objects[$key]->getFileSize()|replace:" ":"&nbsp;"|escape:'html'} | {$documents_objects[$key]->getFileExt()|escape:'html'}</span>
                    {else}
                    {if !$disable_click}
                    <div>
                        <a href="#null" onclick="xajax_select_document('{$id}', '{$key}', getMouseCoordinateX(event), getMouseCoordinateY(event), 'document_{$cloneId}_{$key}', '{$cloneId}');return false;">
                            <img src="{$AppTheme->images}/decorators/icoUnknown.gif" align="top" />
                        </a>
                        {if !($smarty.foreach.documents.first && $smarty.foreach.documents.last)}
                            <a href="#null" onclick="removeDDDocumentSlot('{$cloneId}', {$myIteration});return false;">
                                <img src="{$AppTheme->images}/decorators/profile-marker.gif" alt="" title="" align="top" />
                            </a>
                        {else}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {/if}
                    </div>
                    <a href="#" onclick="xajax_select_document('{$id}', '{$key}', getMouseCoordinateX(event), getMouseCoordinateY(event), 'document_{$cloneId}_{$key}', '{$cloneId}');return false;">{t}Select Document{/t}</a>
                    {else}
                        <div class="clear" style="height:1px;"><span /></div>
                    {/if}
                        <!-- / -->
                    {/if}
                </div>
                <!-- /Content object doc item -->
                <input type="hidden" name="documents_{$cloneId}_{$smarty.foreach.documents.iteration}" id="documents_{$cloneId}_{$smarty.foreach.documents.iteration}" value="{if $thumbs[$smarty.foreach.documents.iteration]} {$documents_ids[$smarty.foreach.documents.iteration]}{/if}">
            </td>
            {if $smarty.foreach.documents.last}
            {if $smarty.foreach.documents.iteration%3 == 1}
            <td>&nbsp;</td>
            {/if}
            {if $smarty.foreach.documents.iteration%3 == 2}
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            {/if}
            {/if}
            
            {if $smarty.foreach.documents.iteration%3 == 0} </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr> {/if}
            {/foreach} </tr>
    </tbody>
</table>
</div>
<div class="prInnerTop">
    <a class="prLink2" href="{$CurrentGroup->getGroupPath('documents')}">{t}More Documents{/t} &raquo;</a>
</div>