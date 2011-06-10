<?php

$objResponse = new xajaxResponse();

$avatars = array();

$galleriesList = $this->currentUser->getVideoGalleries()->getList();
foreach ($galleriesList as &$v)
{
    $tmp = $v->getVideos()->getList();
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
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_hash = $avatars_tmp;

$objResponse->addAssign('thumbs_area', 'innerHTML', $this->view->getContents('content_objects/ddMyVideoContentBlock/avatar_thumbs_block.tpl'));
