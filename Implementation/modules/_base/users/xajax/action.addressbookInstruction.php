<?php

    $objResponse = new xajaxResponse();
    
    $output = $this->view->file_type = $file_type;
    $output = $this->view->getContents('users/addressbook/instructions.tpl');
    
//    $this->view->paging = $P->makePaging($this->params['page']);
    $objResponse->addClear("list_items", "div", "file_import_instruction");
    $objResponse->addAssign("file_import_instruction",'innerHTML', $output);
