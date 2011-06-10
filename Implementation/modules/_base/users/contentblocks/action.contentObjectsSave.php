<?php

$objResponse = new xajaxResponse();

mb_regex_encoding('UTF-8');
mb_internal_encoding('UTF-8');
foreach ( $items as &$_item) {
    if ($_item['ContentType'] == 'ddContentBlock')
    {
        $_item["Data"]["Content"] = mb_ereg_replace("'<script[^>]*?>.*?</script>'si",'',$_item["Data"]["Content"]);
        $_item["Data"]["Content"] = mb_ereg_replace("'<body[^>]*?>.*?</body>'si",'',$_item["Data"]["Content"]);
        $_item["Data"]["Content"] = mb_ereg_replace("'<html[^>]*?>.*?</html>'si",'',$_item["Data"]["Content"]);
        //print strlen($_item["Data"]["Content"]);
        //$_item["Data"]["Content"] = substr($_item["Data"]["Content"],0, 4000);
        //print strlen($_item["Data"]["Content"]);
    }
    if ($_item['ContentType'] == 'ddMyDocuments')
    {
       if(!empty($_item['Data']['items']))
       {
            foreach ($_item['Data']['items'] as $_k => &$_v) {  
                if (empty($_v) || !Warecorp_Document_Item::isDocumentExists($_v)) {
                    unset($_item['Data']['items'][$_k]);
                }
            }
       }
    }
    if ($_item['ContentType'] == 'ddScript')
    {
       if(!empty($_item['Data']['alt_src']))
       {
          $_item['Data']['alt_src'] = '';   
       } 
       if(isset($_item['Data']['jscontent']))
       {
          unset($_item['Data']['jscontent']); 
       } 
    }
    if ( $_item['ContentType'] === 'ddMyGroups' ) {
        $ids = $this->_page->_user->getGroups()->setTypes(array('family', 'simple'))->returnAsAssoc(true)->getList();
        $_item['Data']['not_new_groups'] = array_keys($ids);
    }
}
Warecorp_CO_Content::saveToDB($this->_page->_user->getId(), $this->_page->_user->EntityTypeId, $items);
