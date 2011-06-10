<div class="prText2">{t}Upload and store the images you wish to make available as group profile photos.{/t}</div>
<div class="prInnerTop">{t}{tparam value=$avatarsLeft}You may upload up to %s additional photos{/t}</div>
{if $avatarsLeft > 0}
    <div class="prInnerTop">
        {t var="in_button"}Upload Photos{/t}
        {linkbutton name=$in_button link=$group->getGroupPath('avatarupload')}
    </div>
{/if}
 <div class="prAvatarContent">
    <div class="prAvatarLeft">
        {foreach item=a name='avatars' from=$avatarsList}
            <div class="prFloatLeft prInnerSmall"><a href="#null" onclick="xajax_loadavatar({$a->getId()});"> <img src="{$a->setWidth(50)->setHeight(50)->setBorder(1)->getImage()}" /> </a></div>
        {foreachelse}
            <p>{t}No Profile Photos{/t}</p>
        {/foreach}
        <input type="hidden" id = "xa_avatar_id" name="avatar_id" value="{$currentAvatar->getId()}" />
        <input type="hidden" id = "xa_default" name="avatar_defaule" value="{$currentAvatar->getByDefault()}" />
        <div class="prClearer prInnerTop">
            {if $currentAvatar->getId() === 0}
                <div id="xa_deletelink" style="display:none;" class="prFloatLeft prIndentLeftSmall">
            {else}
                <div id="xa_deletelink" class="prFloatLeft">
            {/if}
                {t var="in_button_2"}Delete{/t}
                {linkbutton name=$in_button_2 link=$group->getGroupPath('avatardelete/avatar')|cat:$currentAvatar->getId() id="xa_deleteurl"}
                </div>
                <div id="xa_setprimary" class="prFloatLeft prIndentLeftSmall">
                    {if $group->getGroupType() == 'simple'}
                        {t var="in_button_3"}Set as Primary Group Photo{/t}
                        {linkbutton name=$in_button_3 link=$group->getGroupPath('avatarmakeprimary/avatar')|cat:$currentAvatar->getId() id="xa_makeprimary"}
                    {else if $group->getGroupType() == 'family'}
                        {t var="in_button_4"}Set as Primary Group Family Photo{/t}
                        {linkbutton name=$in_button_4 link=$group->getGroupPath('avatarmakeprimary/avatar')|cat:$currentAvatar->getId() id="xa_makeprimary"}
                    {/if}
                </div>
        </div>
    </div>
    <div class="prAvatarRight">
    {if $avatarsList}
        <img id="xa_avatar_path" src="{$currentAvatar->setWidth(175)->setHeight(215)->setBorder(1)->getImage()}" width="175" />
        <div id="xa_delete" style="visibility:hidden">{t}Group Primary Photo{/t}</div>
        <script>
            if (document.getElementById("xa_default").value == 1) document.getElementById("xa_delete").style.display="";
            if (document.getElementById("xa_avatar_id").value == 0)  document.getElementById("xa_deletelink").style.display="none";
         </script>
    {/if}
    </div>
</div>
