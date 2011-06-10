<?php
Warecorp::addTranslation('/modules/groups/promotion/action.deletesearch.php.xml');

	$objResponse = new xajaxResponse();
	if (isset($url)) {
	    $this->view->url = $url;
		$Content = $this->view->getContents ( 'groups/promotion/deletesearch.tpl' ) ;

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Remove search'));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);
	}
