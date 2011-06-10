User {$invitor->getLogin()} invites you to be {if $invitor->getGender() == 'female'}his{else}him{/if} friend!

To accept invitation, please follow this link - {$recipient->getUserPath('friend')}cmd/add/user/{$invitor->getId()}

To decline invitation, please do nothing, ok? :)

You may view your friends following this link - {$recipient->getUserPath('friends')}