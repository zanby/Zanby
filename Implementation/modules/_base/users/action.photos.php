<?php

    $items_per_page = 9;
	$this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;

    $galleriesList = $this->currentUser->getArtifacts()->getGalleriesList($this->params['page'], $items_per_page);
    $galleriesCount = $this->currentUser->getArtifacts()->getGalleriesCount();

    $this->view->galleriesList = $galleriesList;
    $this->view->galleriesCount = $galleriesCount;

	// paging
	$paging_url = $this->currentUser->getUserPath('photos', false);
	$P = new Warecorp_Common_PagingProduct($galleriesCount, $items_per_page, $paging_url);
	$this->view->paging = $P->makePaging($this->params['page']);

    $this->view->bodyContent = 'users/gallery/list.tpl';
