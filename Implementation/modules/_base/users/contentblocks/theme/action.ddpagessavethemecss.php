<?php
Warecorp::addTranslation("/modules/users/contentblocks/theme/action.ddpagessavethemecss.php.xml");
$objResponse = new xajaxResponse();

Warecorp_DDPages::saveCSSToDB($entity_id, $this->currentUser->EntityTypeId, $css_text);
$Script = "alert('". Warecorp::t('Saved')."')";

$objResponse->addScript($Script);