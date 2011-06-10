<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}
   <div class="prCOCentrino"> {if $currentAvatar->getGroup()}
        <a href="{$currentAvatar->getGroup()->getGroupPath('summary')}"><img src="{$currentAvatar->setWidth(147)->getImage()}" id="image_{$cloneId}" alt="" /></a>
        {else} <img src="{$currentAvatar->setWidth(147)->getImage()}" id="image_{$cloneId}" alt="" /> {/if} </div>
</div>
