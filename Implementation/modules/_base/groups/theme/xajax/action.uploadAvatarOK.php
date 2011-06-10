<?php
Warecorp::addTranslation('/modules/groups/theme/xajax/action.uploadAvatarOK.php.xml');
   
if ( isset($_FILES['edit']['name']["img_1"]) && $_FILES['edit']["error"]["img_1"] == 0){
    
   
    
    
    
    
    if (is_uploaded_file($_FILES['edit']['tmp_name']["img_1"])) {
  
       list($width, $height, $type, $attr) = getimagesize($_FILES['edit']['tmp_name']["img_1"]);
       if ($type == 1 || $type == 2 || $type==6) 
       {
           
            while(file_exists($filepath = str_replace(' ','_',UPLOAD_BASE_PATH."/upload/theme_background_images/".$this->currentGroup->getId()."_g".time().$_FILES['edit']["name"]["img_1"]) )){}
          
            $filepath = preg_replace('/(\.[a-zA-Z0-9]*)$/', '_orig$1', $filepath);
            $backgroundUrl = str_replace(UPLOAD_BASE_PATH, UPLOAD_BASE_URL, $filepath); 
            move_uploaded_file($_FILES['edit']['tmp_name']["img_1"], $filepath); 
            list($width, $height, $type, $attr) = getimagesize($filepath); 
            Warecorp_Image_Thumbnail::makeThumbnail($filepath, str_replace('_orig.','_medium.',$filepath), ceil(270*$width/590), ceil(270*$height/590), true);
            Warecorp_Image_Thumbnail::makeThumbnail($filepath, str_replace('_orig.','_small.',$filepath), 37, ceil(37*$height/$width), true);
            
            $this->view->error = Warecorp::t('Upload complete');
            $this->view->close = 1;
            $this->view->imageName = $_FILES['edit']["name"]["img_1"];
            $this->view->backgroundUrl = $backgroundUrl;

            $out = $this->view->getContents('groups/theme/upload_avatar_error.tpl');
            print $out; exit;
            
            
           
       }else{
         $this->view->error = Warecorp::t('Incorrect Image File'); 
         $out = $this->view->getContents('groups/theme/upload_avatar_error.tpl');
         print $out; exit;  
       }
       
       
   } else {
        $out = $this->view->getContents('groups/theme/upload_avatar_error.tpl');
        print $out; exit;
   }  

   
   
}
else
{
    
  $out = $this->view->getContents('groups/theme/upload_avatar_error.tpl');
        print $out; exit;  
}
