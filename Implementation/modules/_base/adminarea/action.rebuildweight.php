<?php    

    if (empty($this->params['mode'])) {
        $this->view->bodyContent = 'adminarea/rebuildweight.tpl';        
    } elseif ($this->params['mode'] == 'start') {
        if (Warecorp_Search::rebuildWeight($this->params) === true) {
            echo "<script>document.location.href='".BASE_URL.'/'.$this->_page->Locale.'/adminarea/rebuildweight/mode/complete/'."'</script>";exit;
            exit;
        }
    } elseif ($this->params['mode'] == 'complete') {
        $this->view->bodyContent = 'adminarea/rebuildweight.tpl';
    }
    $this->view->assign($this->params);
    
