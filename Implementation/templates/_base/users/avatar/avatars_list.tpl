{if $currentUser->getId() == $user->getId()}
	<p class="prText2">{t}Upload and store the images you wish to make available as your profile photos.{/t}</p>
	<p class="prInnerTop">{t}{tparam value=$avatarsLeft}You may upload up to %s additional photos{/t}</p>
		{if $avatarsLeft > 0}
			<div class="prInnerTop prButtonPanel">
				{t var='button_01'}Choose Photo From Galleries{/t}
				{linkbutton name=$button_01 link="#" onclick="xajax_avatarLoadFromGalleries('location.reload();')"}          		{t var='button_02'}Upload Photos{/t}
				{linkbutton name=$button_02 link=$user->getUserPath('avatarupload')}
			</div>	
		{/if}
	<div class="prAvatarContent">
		<div class="prAvatarLeft">
			{foreach item=a name='avatars' from=$avatarsList}
				<div class="prFloatLeft prInnerSmall"><a href="#null" onclick="xajax_loadavatar({$a->getId()});"> <img src="{$a->setWidth(50)->setHeight(50)->setBorder(1)->getImage()}" /> </a></div>
				{foreachelse}
				 <div class="prInnerTop">
					{t}No Profile Photos{/t}
				</div> 
			{/foreach}
			<input type="hidden" id ="xa_avatar_id" name="avatar_id" value="{$currentAvatar->getId()}" />
			<input type="hidden" id ="xa_default" name="avatar_defaule" value="{$currentAvatar->getByDefault()}" />
			<div class="prClearer prInnerTop">
				{if $currentAvatar->getId() === 0}
					<div id="xa_deletelink" style="display:none;" class="prFloatLeft prIndentLeftSmall"> 
				{else}
					<div id="xa_deletelink" class="prFloatLeft prIndentLeftSmall"> 
				{/if}
					{t var='button_03'}Delete{/t}         
					{linkbutton name=$button_03 link=$user->getUserPath('avatardelete/avatar')|cat:$currentAvatar->getId() id="xa_deleteurl"}
					</div>
					<div id="xa_setprimary" style="visibility:hidden" class="prFloatLeft prIndentLeftSmall">
						{t var='button_04'}Set as My Primary Photo{/t}
						{linkbutton name=$button_04 link=$user->getUserPath('avatarmakeprimary/avatar')|cat:$currentAvatar->getId() id="xa_makeprimary"}
					</div>
			</div>            
		</div>
		 <div class="prAvatarRight">
		  {if $avatarsList}                
				<img id="xa_avatar_path" src="{$currentAvatar->setWidth(175)->setHeight(215)->setBorder(1)->getImage()}" width="175" />
				  <div id="xa_delete" style="visibility:hidden">{t}My Primary Photo{/t}</div>
				  <script>
					if (document.getElementById("xa_default").value == 1) document.getElementById("xa_delete").style.display="";
					if (document.getElementById("xa_avatar_id").value == 0)  document.getElementById("xa_deletelink").style.display="none";
				  </script>    
		   
		   {/if} 
		</div> 
	</div>	
{/if}