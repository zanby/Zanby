<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.confirm.popup.close.php.xml');    
$AccessManager = Warecorp_List_AccessManager_Factory::create();
$objResponse = new xajaxResponse();


$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;

$list =(isset($data['list_id'])) ? new Warecorp_List_Item($data['list_id']) : new Warecorp_List_Item();
$action = isset($data['action']) ? $data['action'] : "";
switch ($action) {
    case 'offwatch':
        if (!$AccessManager->canManageLists($this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }
        break;
    case 'unshare':

        if (!$AccessManager->canUnshareList($list, $this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }
        break;
    case 'delete':
        if (!$AccessManager->canManageList($list, $this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }
        break;
    default:
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
        break;
}

if (!$AccessManager->canManageList($list, $this->currentGroup, $this->_page->_user->getId()) && $action !== 'unshare') {
    $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
    return;
}

if (isset($data['action']) && $list->getId()) {
    switch ($data['action']) {
        case 'offwatch':
            $list->offWatch();
            break;
        case 'delete':
            $list->delete();
            /** Send notification to host **/
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $list, "LISTS", "DELETE", false );
            
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "DELETE");
//                $mail->addParam('section', "LISTS");
//                $mail->addParam('chObject', $list);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->sendToPMB(true);
//                $mail->send();
//            }
            /** --- **/
            break;
        case 'unshare':
            $contextId = !empty($context)?$context->getId():$this->currentGroup->getId();
            if ( $this->currentGroup->getGroupType() !== 'family' ) {
                $list->unshareList('group', $contextId);
                /**Set Exception to Share if gallery shared from family**/
                $families = $this->currentGroup->getFamilyGroups()->returnAsAssoc(true)->getList();
                if ( !empty($families) ) {
                    $listSharedToFamilies = Warecorp_Share_Entity::whichFamiliesSharedFrom($list->getId(), $list->EntityTypeId);
                    if ( !empty($listSharedToFamilies) ) {
                        $familiesToException = array_intersect(array_keys($families), $listSharedToFamilies);
                        if ( !empty($familiesToException) ) {
                            foreach ( $familiesToException as $familyId ) {
                                if ( !Warecorp_Share_Entity::hasShareException($familyId, $list->getId(), $list->EntityTypeId, $this->currentGroup->getId()) ) {
                                    Warecorp_Share_Entity::addShareException($familyId, $list->getId(), $list->EntityTypeId, $this->currentGroup->getId());
                                }
                            }
                        }
                    }
                }
                /**Set Exception to Share if gallery shared from family**/
            } elseif ( $this->currentGroup->getGroupType() === 'family' ) {
                if (Warecorp_Share_Entity::isShareExists($contextId, $list->getId(), $list->EntityTypeId)) {
                    if ( Warecorp_List_AccessManager_Factory::create()->canUnshareListToAllFamilyGroups($list, $this->currentGroup, $this->_page->_user) ) {
                        $list->unshareList('group', $contextId, true);
                        Warecorp_Share_Entity::removeShareException($contextId, $list->getId(), $list->EntityTypeId);
                    }
                } else {
                    $list->unshareList('group', $contextId);
                }
            }
            break;
        default:
            $objResponse->addScript('popup_window.close();');
            break;
    }
    $objResponse->addScript("document.location.reload();");
} else {
    $objResponse->addScript('popup_window.close();');
}
