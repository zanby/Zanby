<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}
    <div class="prInnerTop prInnerLeft prClr3">
        <table cellspacing="0" cellpadding="0" width="100%">
            <col width="100%" />
            <tbody>
            
            {foreach name=documents from=$documents_ids item=id key=key}
            {assign var="myIteration" value=$smarty.foreach.documents.iteration-1}
            <tr>
                <td>
                    <!-- Content object doc item -->
                    <div class="prTCenter" id="document_{$cloneId}_{$key}">
                        {if $id}
                        <!-- -->
                        <div>
                            <a href="{if $documents_objects[$key]->getIsLink()}{$documents_objects[$key]->getOriginalName()}" target="_blank{else}{$currentUser->getUserPath("docget/docid")|cat:$documents_objects[$key]->getId()}{/if}">
                               <img src="{$documents_objects[$key]->getImageFileNameByExtension($documents_objects[$key]->getFileExt(), 'big')}" align="top" />
                            </a>
                            <img src="{$AppTheme->images}/decorators/spacer.gif" alt="" title="" width="15" />
                        </div>
                        <a href="{if $documents_objects[$key]->getIsLink()}{$documents_objects[$key]->getOriginalName()}" target="_blank{else}{$currentUser->getUserPath("docget/docid")|cat:$documents_objects[$key]->getId()}{/if}">
                            {$documents_objects[$key]->getOriginalName()|truncate:16|escape:'html'}
                        </a>
                        <br />
                        <span>{$documents_objects[$key]->getFileSize()|replace:" ":"&nbsp;"|escape:'html'} | {$documents_objects[$key]->getFileExt()|escape:'html'}</span>
                        <script type="text/javascript">
        				        YAHOO.example.container.ttdocs_{$cloneId}_{$key} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$key}", {$smarty.ldelim} hidedelay:100, context:"document_{$cloneId}_{$key}", width:"350px" , text:"{$documents_objects[$key]->getOriginalName()|escape:'html'}<br>{$documents_objects[$key]->getFileSize()|escape:'html'} | {$documents_objects[$key]->getFileExt()|escape:'html'}<br />{$documents_objects[$key]->getDescription()|escape:'html'}<br /><br />Created by <a href=\"{$documents_objects[$key]->getCreator()->getUserPath('profile')}/\">{$documents_objects[$key]->getCreator()->getLogin()|escape:'html'}</a> on {$documents_objects[$key]->createDate}"{$smarty.rdelim});
        			        </script>
                        <!-- / -->
                        {else}
                        <!-- -->
                        <div>&nbsp;</div>
                        <!-- / -->
                        {/if} </div>
                    <!-- /Content object doc item -->
                    <input type="hidden" name="documents_{$cloneId}_{$smarty.foreach.documents.iteration}" id="documents_{$cloneId}_{$smarty.foreach.documents.iteration}" value="{if $thumbs[$smarty.foreach.documents.iteration]} {$documents_ids[$smarty.foreach.documents.iteration]}{/if}">
                </td>
            </tr>
            {/foreach}
            </tbody>
            
        </table>
    </div>
   <div class="prInnerTop">
    <a class="prLink2" href="{$CurrentGroup->getGroupPath('documents')}">{t}More Documents{/t} &raquo;</a>
</div>
</div>
