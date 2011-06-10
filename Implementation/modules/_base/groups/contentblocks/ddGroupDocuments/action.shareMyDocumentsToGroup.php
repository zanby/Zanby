<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupDocuments/action.shareMyDocumentsToGroup.php.xml');

$objResponse = new xajaxResponse();

foreach ($documents_hash as $_k => $_document)
{
    $_doc = new Warecorp_Document_Item($_document);

    if ($this->_page->_user->getId() == $_doc->getOwnerId() 
        && $this->_page->_user->EntityTypeName == $_doc->getOwnerType())
    {
        $_doc->shareDocument($this->currentGroup->EntityTypeName, $this->currentGroup->getId());
    }
}