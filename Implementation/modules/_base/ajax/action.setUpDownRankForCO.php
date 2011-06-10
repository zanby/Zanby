<?php
    Warecorp::addTranslation("/modules/ajax/action.setUpDownRankForCO.php.xml");
$objResponse = new xajaxResponse();

if (Warecorp_Video_Standard::isVideoExists($video_id)) {
  $video = Warecorp_Video_Factory::loadById($video_id);
} else {
  $video = Warecorp_Video_Factory::createByOwner($this->_page->_user);
}

$video_id = $video->getId();
$user_id = $this->_page->_user->getId();

if (!empty($user_id) && !empty($video_id)) {
  switch ($direction) {
    case 'up':
      $video->setUpDownRank($this->_page->_user, 1);
      
      break;
    case 'down':
      $video->setUpDownRank($this->_page->_user, -1);
      break;
    
  }
  if (!empty($cloneId)) {     
      $objResponse->addScript('xajax_get_block_content_in_preview_mode("'.$cloneId.'", "'.htmlspecialchars($params).'");');
  }
}
