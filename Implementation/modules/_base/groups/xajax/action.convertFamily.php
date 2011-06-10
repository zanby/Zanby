<?php
Warecorp::addTranslation('/modules/groups/xajax/action.convertFamily.php.xml');

    $objResponse    = new xajaxResponse();

    if ( $this->currentGroup->getGroupType() == 'family' && $this->currentGroup->getPaymentType() == 'business') {
        $this->currentGroup->setPaymentType('basic');
        $this->currentGroup->save();
        $objResponse->addRedirect($this->currentGroup->getGroupPath('settings')); 
    }