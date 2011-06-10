<?php
Warecorp::addTranslation('/modules/groups/attachment/action.attachdel.php.xml');

    if ( isset($this->params['attachrel']) && floor($this->params['attachrel']) != 0 ) {
        $attachRel = new Warecorp_Data_AttachmentRelation($this->params['attachrel']);
        if (!empty($attachRel)) {
            $attachRel->delete();
        }
    }