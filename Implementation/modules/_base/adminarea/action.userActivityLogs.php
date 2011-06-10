<?php

$config = Warecorp_Util_UserActivity_ConfigTracker::getXmlConfig();
if ($config->backupFilesPrefix) {
    $backupFilePrefix = $config->backupFilesPrefix;
} else {
    $backupFilePrefix = 'activityLog';
}

if ($config->backupFilesPath) {
    $backupFilePath = $config->backupFilesPath;
} else {
    $backupFilePath = APP_VAR_DIR. '/logs';
}

$backupedLogs = array();
$matches = array();
if ($dh = opendir($backupFilePath)) {
    while ($file = readdir($dh)) {
		if ($file == '.' || $file == '..' || is_dir($backupFilePath. '/'. $file)) continue;
		if (preg_match('/^'. preg_quote($backupFilePrefix). '_(\d{4}\-(0[1-9])|(1[0-2]))\.csv/', $file, $matches)) {
		    $backupedLogs[] = $matches[1];
		}
    }
}

$this->view->backupedLogs = $backupedLogs;

$defaultTimeZone = date_default_timezone_get();
date_default_timezone_set('UTC');

$date = new Zend_Date();
$this->view->currMonth = $date->toString('YYYY-MM');
$date->subMonth(1);
$this->view->previousMonth = $date->toString('YYYY-MM');

date_default_timezone_set($defaultTimeZone);

$this->view->bodyContent = 'adminarea/userActivityLogs.tpl';
