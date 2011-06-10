<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.saveConstraints.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    /**
     * hierarchy type was changed
     */
    if ( $h->getHierarchyType() != $data['hierarchy_type'] ) {
        /**
         * live to custom
         */
    	if ( $data['hierarchy_type'] == Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM ) {
            /**
             * build custom categories
             */
            $h->convertLiveToCustom($data);
        } 
        /**
         * custom to live - build default live categories
         */
        else {    
            /**
             * remove custom categories
             */
            $h->convertCustomToLive($data);
        }
    } 
    /**
     * category filter was changed
     */
    elseif ( $h->getCategoryType() != $data['category_type'] ) {
        if ( $data['hierarchy_type'] == Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM ) {
            /**
             * rebuild custom categories
             */
            $h->convertCustomToCustom($data);
        } else {
            /**
             * remove custom categories
             */
            $h->convertCustomToLive($data);
        }
    }
    /**
     * category focus was changed
     */ 
    elseif ( $h->getCategoryFocus() != $data['category_focus'] ) {
        if ( $data['hierarchy_type'] == Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM ) {
            /**
             * rebuild custom categories
             */
            $h->convertCustomToCustom($data);
        } else {
            /**
             * remove custom categories
             */
            $h->convertCustomToLive($data);
        }
    }
    $h->updateHierarchyConstraints($data);
        
    /**
     * need reload hierarchy categories section 
     */

    /**
     * need reload hierarchy options section 
     */

    $objResponse = new xajaxResponse();
    $objResponse->addScript('document.location.reload();');
    

