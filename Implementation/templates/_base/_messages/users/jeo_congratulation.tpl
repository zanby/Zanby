User {$invitor->getLogin()} joins you as friend and now {if $invitor->getGender() == 'female'}his{else}him{/if} is your mutual friend!

You may view your friends following this link - {$recipient->getUserPath('friends')}

