<?php  
	$objResponse = new xajaxResponse();
    $this->view->searchId = $searchId;
    $Content = $this->view->getContents ( 'users/videogallery/xajax.delete.search.tpl' ) ;
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title('');
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
