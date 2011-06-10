<?php

if (isset($this->params['month'])) {
    $paramDate = $this->params['month'];
    if (!preg_match('/^\d{4}\-(0[1-9])|(1[0-2])$/', $paramDate)) $this->_redirect($this->admin->getAdminPath('userActivityLogs/'));
} else {
    $this->_redirect($this->admin->getAdminPath('userActivityLogs/'));
}

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

header('Content-type: text/x-csv');
header('Content-Disposition: attachment; filename='. $backupFilePrefix. '_'. $paramDate. '.csv;');
if (file_exists($backupFilePath. '/'. $backupFilePrefix. '_'. $paramDate. '.csv')) {
    readfile($backupFilePath. '/'. $backupFilePrefix. '_'. $paramDate. '.csv');
    exit;
}

$defaultTimeZone = date_default_timezone_get();
date_default_timezone_set('UTC');

$paramDate .= '-01';
$date = new Zend_Date($paramDate, Zend_Date::ISO_8601);

$logsExport = Warecorp_Util_UserActivity_LogExport::createFromConfig();
$logsExport->writeLogsToCsvFile($date, 'php://output');

date_default_timezone_set($defaultTimeZone);
exit;	            
