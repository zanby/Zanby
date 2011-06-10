<?php
Warecorp::addTranslation('/modules/groups/action.tags.php.xml');

    //  @todo в шаблоне groups/tags.tpl нет линка для тегов группы
    //  сейчас нет функции в поиске по группам поиска по тегу
    //  @todo надо сделать Таргонскому Виталику
    
    if($this->currentGroup->getGroupType() == "family") {
	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array(Warecorp::t("Group families") => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
    } else {
        //breadcrumb
        $this->_page->breadcrumb = array_merge(
            $this->_page->breadcrumb,
            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
                $this->currentGroup->getName() => "")
            );
    } 

    $this->_page->setTitle('Tags');
    
    $tags = array();
    $tags['group'] = $this->currentGroup->getTags()->getGroupTagsList();
    $tags['members'] = $this->currentGroup->getTags()->getMembersTagsList();
    $tags['events'] = $this->currentGroup->getTags()->getEventsTagsList();
    $tags['photos'] = $this->currentGroup->getTags()->getPhotosTagsList();
    $tags['lists'] = $this->currentGroup->getTags()->getListsTagsList();
    $tags['documents'] = $this->currentGroup->getTags()->getdocumentsTagsList();
    
    $this->view->tags = $tags;
    $this->view->bodyContent = 'groups/tags.tpl';
/**/
