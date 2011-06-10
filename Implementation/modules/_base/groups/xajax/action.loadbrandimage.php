<?php
Warecorp::addTranslation('/modules/groups/xajax/action.loadbrandimage.php.xml');

$avatarId = floor($imageId);
$objResponse = new xajaxResponse();

$brandItem = new Warecorp_Group_BrandPhoto_Item($avatarId);

if ($brandItem->getGroupId() == $this->currentGroup->getId()){

    if ($brandItem->isExists()) {
        $objResponse->addAssign("xa_branditem_path", "src", $brandItem->setWidth(300)->setHeight(300)->setBorder(1)->getImage());
        $objResponse->addAssign("deletelink", "innerHTML", "<a href='#' onclick='xajax_branditemDelete({$brandItem->getId()}); return false;'>".Warecorp::t("delete")."</a>");
    }
}