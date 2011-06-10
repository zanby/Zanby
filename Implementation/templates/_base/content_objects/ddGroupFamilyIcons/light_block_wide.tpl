<div class="themeA" id="light_{$cloneId}">
   <div class="prCOCentrino"> {if $currentAvatar->getGroup()}
        <a href="{$currentAvatar->getGroup()->getGroupPath('summary')}"><img src="{$currentAvatar->setWidth(395)->getImage()}" id="image_{$cloneId}" alt="" /></a>
        {else} <img src="{$currentAvatar->setWidth(395)->getImage()}" id="image_{$cloneId}" alt="" /> {/if} </div>
</div>
