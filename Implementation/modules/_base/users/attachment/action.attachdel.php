<?php
dump($this->params);
    if ( isset($this->params['attachrel']) && floor($this->params['attachrel']) != 0 ) {
        $attachRel = new Warecorp_Data_AttachmentRelation($this->params['attachrel']);
        dump($attachRel);exit;
        if (!empty($attachRel)) {
            $attachRel->delete();
        }
    }