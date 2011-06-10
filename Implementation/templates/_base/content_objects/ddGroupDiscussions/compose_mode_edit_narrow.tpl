{include file="content_objects/edit_mode_settings_narrow.tpl"}
<!-- ============================================== -->
<div class="prInnerSmall">
    <table class="prForm">
        <tbody>
            <tr>
                <td class="prTBold">{t}Promote a discussion to the group homepage:{/t}</td>
            </tr>
        {foreach from=$discussionsThreads item=current name=threads}
        {assign var=iter value=$smarty.foreach.threads.iteration-1}
        <tr>
            <td class="prNoPadding{if !$smarty.foreach.threads.first} prInnerTop{/if}">
                <select id="family_discussions_threads_select_{$cloneId}" onchange="set_family_discussions_thread_value({$iter}, this.value, '{$cloneId}');return false;">
                    <option value="0" selected="selected">{t}Select Thread{/t}</option>
                    
                {foreach from=$discussionList item=discus}
                {assign var=iter2 value=$smarty.foreach.discus.iteration-1}
                {if $discus->getTopics()->findByDiscussionId($discus->getId())}
                    
                    <option value="{$discus->getId()}" {if $discus->getId() == $current[0]}selected="selected"{/if}>
                    {$discus->getTitle()|escape:"html"}</option>
                    
                {/if}
                {/foreach}
            
                </select>
            </td>
        </tr>
        <tr>
            <td class="prNoPadding">
                <select onchange="set_family_discussions_thread_topic_value({$iter}, this.value, '{$cloneId}');return false;">
                    <option value="0" selected="selected">{t}Select Topic{/t}</option>
                    
                {foreach from=$discussionList item=discus}
                {assign var=iter2 value=$smarty.foreach.discus.iteration-1}
                {if $discus->getTopics()->findByDiscussionId($discus->getId())}
                {if $discus->getId() == $current[0]}
                {foreach from=$discus->getTopics()->findByDiscussionId($current[0]) item=topic}
                    
                    <option value="{$topic->getId()}" {if $topic->getId() == $current[1]}selected="selected"{/if}>{$topic->getSubject()|escape:"html"} </option>
                    
                {/foreach}
                {/if}
                {/if}
                {/foreach}
            
                </select>
            </td>
        </tr>
        {/foreach}
        </tbody>
        
    </table>
    <div class="prTRight prTBold prInnerSmallTop">
        <a href="#null" onclick="family_discussions_add_thread('{$cloneId}');return false;">{t}+ Add Thread{/t}</a>
    </div>
    <div>
        <input onclick="family_discussions_show_thread_summaries_check((document.getElementById('family_discussions_show_thread_summaries_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_discussions_show_thread_summaries_check_{$cloneId}" type="checkbox" {if $discussionsShowThreadSummaries}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
        <label for="family_discussions_show_thread_summaries_check_{$cloneId}"> {t}Show Thread Summaries{/t}</label>
    </div>
    <div>
        <table class="prForm">
            <tbody>
                <tr>
                    <td class="prTBold">{t}Select Pre-set Discussion Summary tabs to display:{/t}</td>
                </tr>
                <tr>
                    <td>
                        <input onclick="family_discussions_most_active_check((document.getElementById('family_discussions_most_active_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_discussions_most_active_check_{$cloneId}" type="checkbox" {if $discussionsDisplayMostActive}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
                        <label for="family_discussions_most_active_check_{$cloneId}"> {t}Most Active{/t}</label>
                        <div class="prIndentTop">
                            <input onclick="family_discussions_most_recent_check((document.getElementById('family_discussions_most_recent_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_discussions_most_recent_check_{$cloneId}" type="checkbox" {if $discussionsDisplayMostRecent}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
                            <label for="family_discussions_most_recent_check_{$cloneId}"> {t}Most Recent{/t}</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label class="prTBold" for="family_discussions_threads_number_select_{$cloneId}">{t}Set the number of threads you wish to display in each tab:{/t}</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select id="family_discussions_threads_number_select_{$cloneId}" onchange="set_family_discussions_thread_number(this.value, '{$cloneId}');return false;">
                            <option value="0" {if $discussionsShowThreadsNumber == 0}selected="selected"{/if}>{t}Show 0 threads{/t}</option>
                            <option value="1" {if $discussionsShowThreadsNumber == 1}selected="selected"{/if}>{t}Show 1 thread{/t}</option>
                            <option value="2" {if $discussionsShowThreadsNumber == 2}selected="selected"{/if}>{t}Show 2 threads{/t}</option>
                            <option value="3" {if $discussionsShowThreadsNumber == 3}selected="selected"{/if}>{t}Show 3 threads{/t}</option>
                            <option value="4" {if $discussionsShowThreadsNumber == 4}selected="selected"{/if}>{t}Show 4 threads{/t}</option>
                            <option value="5" {if $discussionsShowThreadsNumber == 5}selected="selected"{/if}>{t}Show 5 threads{/t}</option>
                            <option value="6" {if $discussionsShowThreadsNumber == 6}selected="selected"{/if}>{t}Show 6 threads{/t}</option>
                            <option value="7" {if $discussionsShowThreadsNumber == 7}selected="selected"{/if}>{t}Show 7 threads{/t}</option>
                            <option value="8" {if $discussionsShowThreadsNumber == 8}selected="selected"{/if}>{t}Show 8 threads{/t}</option>
                            <option value="9" {if $discussionsShowThreadsNumber == 9}selected="selected"{/if}>{t}Show 9 threads{/t}</option>
                            <option value="10" {if $discussionsShowThreadsNumber == 10}selected="selected"{/if}>{t}Show 10 threads{/t}</option>
                            <option value="20" {if $discussionsShowThreadsNumber == 20}selected="selected"{/if}>{t}Show 20 threads{/t}</option>
                            <option value="30" {if $discussionsShowThreadsNumber == 30}selected="selected"{/if}>{t}Show 30 threads{/t}</option>
                            <option value="40" {if $discussionsShowThreadsNumber == 40}selected="selected"{/if}>{t}Show 40 threads{/t}</option>
                            <option value="50" {if $discussionsShowThreadsNumber == 50}selected="selected"{/if}>{t}Show 50 threads{/t}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input onclick="family_discussions_show_thread_summaries_check2((document.getElementById('family_discussions_show_thread_summaries_check2_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_discussions_show_thread_summaries_check2_{$cloneId}" type="checkbox" {if $discussionsShowThreadSummaries2}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
                        <label for="family_discussions_show_thread_summaries_check2_{$cloneId}"> {t}Show Summaries for Most Active/Recent threads{/t}</label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- ============================================== -->
{include file="content_objects/headline_block_narrow.tpl"}
<div class="themeA" id="light_{$cloneId}">
{include file="content_objects/ddGroupDiscussions/light_block_narrow.tpl"}
</div>
{include file="content_objects/edit_mode_buttons.tpl"} 