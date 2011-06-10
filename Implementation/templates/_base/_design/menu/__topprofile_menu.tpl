
      {tab template="tabs2" active=$active}
      
        {if $Warecorp_User_AccessManager->canViewProfile($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('profile') name="profile"}{t}Profile{/t}{/tabitem}
        {/if}
             
        {if $Warecorp_User_AccessManager->canViewPhotos($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('photos') name="photos"}{t}Photo{/t}{/tabitem}
        {/if}
        
        {if $Warecorp_User_AccessManager->canViewVideos($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('videos') name="videos"}{t}Videos{/t}{/tabitem}
        {/if}        
        
        {if $Warecorp_User_AccessManager->canViewLists($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('lists') name="lists"}{t}Lists{/t}{/tabitem}
        {/if}
        
        {if $Warecorp_User_AccessManager->canViewDocuments($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('documents') name="documents"}{t}Documents{/t}{/tabitem}
        {/if}
        
        {if $Warecorp_User_AccessManager->canViewEvents($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('calendar.list.view') name="calendar"}{t}Events{/t}{/tabitem}
        {/if}
        
        {if $Warecorp_User_AccessManager->canViewFriends($currentUser, $user)}
            {tabitem link=$currentUser->getUserPath('friends') name="friends"}{t}Friends{/t}{/tabitem}
        {/if}
        
     {/tab}
