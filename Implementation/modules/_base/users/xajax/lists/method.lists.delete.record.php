<?php

    if (isset($list['records'][$record_id])) {
        $objResponse->addRemove("item_".($record_id));
        unset($list['records'][$record_id]);
    }

    if (is_array($list['records'])) {
        $i = 1;
        foreach ($list['records'] as $id => &$record) {
            $record['display_index'] = $i;
            $objResponse->addClear("list_items", "div", "display_index_".($id));
            $objResponse->addAssign("display_index_".($id),'innerHTML', $i);
            $i++;
        }
    }