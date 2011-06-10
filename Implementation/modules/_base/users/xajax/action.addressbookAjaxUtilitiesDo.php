<?php
    $objResponse = new xajaxResponse();
    $contactIds = explode(',', $params);
    $Content = '';
	$AppTheme = Zend_Registry::get('AppTheme');
    foreach ($contactIds as $index=>&$contactId)
    {
        if ($contactId=='') unset($contactIds[$index]);
        else {
            $contact = Warecorp_User_Addressbook_Factory::loadById($contactId);
            if ($contact instanceof Warecorp_User_Addressbook_User) {
                $contact->displayName = $contact->getDisplayName() . ' (' . $contact->getUser()->getLogin() . ')';
            }
            if ($contact instanceof Warecorp_User_Addressbook_CustomUser) {
                $contact->displayName = $contact->getDisplayName();
                $contact->url         = $contact->getContactOwner()->getUserPath('addressbook/detail') . $contact->getContactId() . '/';
            }
            $Content .= "<li style='font-size: 14px;' id='li_" . $contact->getContactId() . "'>
                            <input type=\"hidden\" value=\"" . $contact->getContactId() . "\" name=\"contacts[]\"/>
                            " . $contact->displayName.
                            "<a href=\"#null\" class=\"znbClose\"  onclick=\"remove_child(".$contact->getContactId().");\"><img src=\"".$AppTheme->images."/decorators/profile-marker.gif\" /></a>
                        </li>";
        }
    }
    $objResponse->addAssign( "divId", "innerHTML", $Content ) ;
    $objResponse->addScript('popup_window.close();');