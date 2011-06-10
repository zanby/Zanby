<!-- group event list -->
{if $arrEvents}
    <div class="prInner">
        {foreach from=$arrEvents item='objEvent' name='events_i'}
        <!-- group event list slot -->
            <div class="prClr3 prInnerBottom{if !($smarty.foreach.events_i.last)} prIndentBottom{/if}">
                {if $objEvent->getRrule() !== null}<img src='{$AppTheme->images}/decorators/event/repeat.gif' />{/if}
                <div class="prFloatLeft">
                    {$objEvent->displayDate('month.view.day.details', $user, $currentTimezone)}
                    <h4><a href="{$objEvent->entityURL()}">{$objEvent->getTitle()|escape:html}</a></h4>
                    <div class="prClr3">
                        {if $objEvent->getPictureId()}
                            <img class="prEventImg" src="{$objEvent->getEventPicture()->setWidth(37)->setHeight(38)->getImage($user)}" alt="" />
                        {/if}
                        <div class="prFloatLeft prEventViewNotes">
                            {$objEvent->getDescription()|strip_script}
                        </div>
                    </div>

                    <div class="prInnerSmallTop">
                        {if null !== $user->getId() && $user->getId() == $objEvent->getCreatorId()}
                            {t}<strong>Organizer :</strong> You are organizer{/t}
                        {else}
                            <strong>{t}Organizer :{/t}</strong> <a href="{$objEvent->getCreator()->getUserPath('profile')}">{$objEvent->getCreator()->getLogin()|escape:"html"}</a>
                        {/if}
                        <br />
                        {if $objEvent->getOwnerType() == 'group'}
                                <strong>{t}Group event :{/t} </strong>
                                <a href="{$objEvent->getOwner()->getGroupPath('summary')}">{$objEvent->getOwner()->getName()|escape:html}</a><br />
                        {/if}
                    </div>
                    <div class="prInnerSmallTop">
                        <strong>{t}Event Category:{/t} </strong>
                        {foreach from=$objEvent->getCategories()->setFetchMode('object')->getList() item='category' name='event_cats'}
                            {$category->getCategory()->getName()|escape:html}{if !$smarty.foreach.event_cats.last}, {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        <!-- /group event list slot -->
        {/foreach}
    </div>
{else}
    <div>{t}No Events{/t}</div>
{/if}
<!-- /group event list -->
