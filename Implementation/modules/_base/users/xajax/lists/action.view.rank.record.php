<?php

    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");

    $record_id = isset($record_id) ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);

    if (!Warecorp_List_AccessManager_Factory::create()->canRankRecord($record, $this->currentUser, $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';

    if ( isset($rank) ) {
        $rank = floor($rank);
        if ($rank>=1 && $rank<=5) {
            $record->addRank($rank);
            $current_rank = $record->getRank();
            $this->view->record            = $record;
            $this->view->current_rank      = $current_rank*15;
            $this->view->thanks            = 1;
            $output = $this->view->getContents('users/lists/lists.view.record.rank.tpl');
            $objResponse->addClear("list_items", "div", "record_rank_".($record->getId()));
            $objResponse->addAssign("record_rank_".($record->getId()),'innerHTML', $output);

            if (isset($_SESSION['list_view'][$record->getListId()]['order']) && in_array($_SESSION['list_view'][$record->getListId()]['order'], array('rankasc','rankdesc'))) {
                unset($_SESSION['list_view'][$record->getListId()]['order']);
                $objResponse->addScript("reset_order_select();");
            }
        }
    }
