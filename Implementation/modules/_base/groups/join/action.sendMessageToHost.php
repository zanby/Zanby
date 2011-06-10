<?php
if (!isset($params['_wf__messageSendForm'])) {
    //Do not translate next messages as they send to host of the group by default and we cannot determinate locale of the host, so en
    $params['subject'] = 'Invitation request from ' . $this->_page->_user->getLogin();
    $params['message'] = 'I am interested in joining the group ' . $this->currentGroup->getName() . ' which you host on ' . SITE_NAME_AS_STRING;
}

$objResponse = $this->messageSendToHostAction($params);
