<?php
	$this->view->contentBlocksHTML = Warecorp_DDPages::getAllBlocksHTML($this->_page, $this->currentUser, $this->_page->_user);

	$theme = Warecorp_Theme::loadThemeFromDB($this->_page->_user);
    $theme->prepareFonts();
    $this->view->theme = $theme; 

    $this->view->setLayout('main_print.tpl');
	$this->view->bodyContent = 'users/print.tpl';
