<?php
$isJQueryCall = false;
$_SESSION['INVITE_PROPERTIES'] = array();

/**
* Need properties:
* $properties['entityType'] - Type of entity, example 'group', 'friends', etc.  (Required)
* $properties['entityId'] - Id of entity to invite, not need for all entities, example 'friends' have ID same as User (Required)
* $properties['entityUid'] - Only for Event entity  (Required for Event)
* $properties['year'] - Only for Event entity (Optional)
* $properties['month'] - Only for Event entity (Optional)
* $properties['day'] - Only for Event entity (Optional)
* $properties['returnUrl'] - User redirect to this page after send invitation   (Required)
*/

if ( empty($properties) ) { //  Request from jQuery
    $isJQueryCall = true;
    $this->_helper->viewRenderer->setNoRender(true);
    $properties = $this->getRequest()->getParams();
    $locale = $properties['locale'];
    foreach ( $properties as $k => $v ) {
        if ( FALSE !== strpos($k, '/'.$locale.'/') ) {
            unset($properties[$k]);
            break;
        }
    }
    unset($properties['locale'], $properties['controller'], $properties['action'], $locale);

    switch ( strtolower(trim($properties['entityType'])) ) {
    case 'event':
        $properties['newEventInvitation'] = true;
        break;
    }
}

if ( $properties['entityId'] == 'district' ) {
    $user =& $this->_page->_user;
    $group = Warecorp_Group_Factory::loadByGroupUID(IMPLEMENTATION_GROUP_UID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
    if ( $group && $group->getId() )    $properties['entityId'] = $group->getId();
    else return;
}

$_SESSION['INVITE_PROPERTIES'] = $properties;

if ( $isJQueryCall ) {
    echo '{"done":1}'; exit;
} else {
    $objResponse = new xajaxResponse();
}
//    $objResponse->addRedirect(BASE_URL.'/'.LOCALE.'/invite/');
