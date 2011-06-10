<?php  
	$objResponse = new xajaxResponse();
    $this->view->searchId = $searchId;
    $Content = $this->view->getContents ( 'users/gallery/xajax.delete.search.tpl' ) ;
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title('');
    $popup_window->content($Content);
    $popup_window->width(450)->height(150)->open($objResponse);
