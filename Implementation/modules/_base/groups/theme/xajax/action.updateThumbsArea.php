<?php
Warecorp::addTranslation('/modules/groups/theme/xajax/action.updateThumbsArea.php.xml');

$objResponse = new xajaxResponse();

$avatars = array();
$galleriesList = $this->_page->_user->getGalleries()->setSharingMode('both')->setPrivacy(array(0,1))->getList();

$groups = array();

if($this->currentGroup->getGroupType()=='family')
{
   $_gm = $this->currentGroup->getGroups()->setTypes(array('family', 'simple'))->getList();
   foreach ( $_gm as &$_gr) {
        $groups[] = $_gr;
    } 
}


$groups[] = $this->currentGroup;

//$groups = $this->_page->_user->getGroups()->setTypes(array('family', 'simple'))->getList();

foreach ($groups as &$_group) {
    $_gl = $_group->getGalleries()->setSharingMode('both')->setPrivacy(array(0,1))->getList();
    foreach ( $_gl as &$_gall) {
        $galleriesList[] = $_gall;
    }
}
//$galleriesList = $this->currentGroup->getGalleries()->getList();
foreach ($galleriesList as &$v)
{
    $tmp = $v->getPhotos()->getList();
    foreach ($tmp as &$_v)
    {
        $avatars[$_v->getId()] = $_v;
    }
}
$avatars_tmp = array();
$counter=0;
foreach ($avatars as &$_v)
{
    $counter++;
    if ($counter > $pageNum*$perPage && $counter <= $pageNum*$perPage+$perPage)
    {
        $avatars_tmp[$_v->getId()] = $avatars[$_v->getId()];
    }
}

$this->view->total = count($avatars);
$this->view->currentCount = count($avatars_tmp);
$this->view->perPage = $perPage;
$this->view->pagesCount = ceil(count($avatars)/$perPage);
$this->view->currentPage = $pageNum;
$this->view->a_thumbs_hash = $avatars_tmp;

$objResponse->addAssign('thumbs_area', 'innerHTML', $this->view->getContents('groups/theme/avatar_thumbs_block.tpl'));
