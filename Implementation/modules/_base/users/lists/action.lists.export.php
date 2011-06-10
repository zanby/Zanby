<?php
/*
* Export lists
* @author Sergey Vaninsky
* return CSV file
*/
Warecorp::addTranslation("/modules/users/lists/action.lists.export.php.xml");
$list = new Warecorp_List_Item($this->params['id']);
$filename = $list->getTitle();
$filename = str_replace("\\", "_", $filename);
$filename = preg_replace("/[^A-z|^0-9]{1,}/", "_", $filename);
$filename = preg_replace("/_{2,}/", "_", $filename);
if($filename[0]==='_')$filename=substr($filename,1);
$filename = 'LIST_'.substr($filename,0,40).".csv";
$db = & Zend_Registry::get("DB");
$select = $db->select()
             ->from('zanby_documents__items', 'id')
             ->where('original_name = ?', $filename);
$id = $db->fetchOne($select);

$newDoc = new Warecorp_Document_Item($id);
$newDoc->setOwnerType   ('user')
       ->setOwnerId        ($this->_page->_user->getId())
       ->setCreatorId      ($this->_page->_user->getId())
       ->setOriginalName   ($filename)
       ->setCreationDate   (new Zend_Db_Expr('NOW()'))
       ->setMimeType('text/comma-separated-values')
       ->setDescription(Warecorp::t('Exported list'))
       ->setPrivate        ('public');
//$newDoc->save();
//$id = $newDoc->getId();
$path = DOC_ROOT.'/upload/documents/'.$filename;
$select = $db->select()
             ->from('zanby_lists__records', '*')
             ->where('list_id = ?', $list->getId());
$records = $db->fetchAll($select);
$csv_file = fopen($path, "w");
/* save: Title & Entry for all records */
if('32'==$list->getListType()) {
    fwrite($csv_file,Warecorp::t("Who will...,Entry,Volunteered first name,Last name,email"));
} else {
    fwrite($csv_file,Warecorp::t("Title,Entry"));
}
fwrite($csv_file,"\n");
foreach($records as $record) {
    $title = $record['title'];
    $xmlstr = simplexml_load_string($record['xml']);
    /* debug
    *  foreach($xmlstr as $ind=>$val){Dump($ind.'='.$val['value']);}
    */
    $entity = ($xmlstr[0]);
    $entry = $entity->description['value'];
    /* entry - from record[] or from xml */
    $entry = empty($entry)?$record['entry']:$entry;
    $stringbegin = str_replace(",", " ",$record['title']).','.str_replace(",", " ",$entry);
    if('32'!==$list->getListType()) {
        fwrite($csv_file,$stringbegin."\n");
    } else {
      /* if "who will..." - write volunteered */
        $select = $db->select()
                     ->from('zanby_lists__volunteers', '*')
                     ->where('record_id = ?', $record['id']);
        $volunteers = $db->fetchAll($select);
        if(count($volunteers)===0) {
            fwrite($csv_file,$stringbegin.",,,\n");
        } else {
            foreach($volunteers as $volunteer) {
                $volUser = new Warecorp_User('id',$volunteer['user_id']);
                fwrite($csv_file,$stringbegin.",".$volUser->getFirstName().",".$volUser->getLastName().",".$volUser->getEmail()."\n");
            }
        }
    }
}
fclose($csv_file);
/* give file for downloading */
    header("Content-Type: " . $newDoc->getMimeType());
    header("Content-Length: ". filesize($path));
    header("Content-Disposition: attachment; filename=\"" . $newDoc->getOriginalName() . "\"");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: must-revalidate");
    header("Content-Location: ".$newDoc->getOriginalName());
    readfile($path);
    unlink($path);
    exit;
//$this->_redirect($this->currentUser->getUserPath('documents'));
