<?php
	Warecorp::addTranslation('/modules/info/action.version.php.xml');

if ( file_exists( "version.txt")) {
    $data = file("version.txt");
    $data = implode("<br>", $data);
} else {
    $data = Warecorp::t("trunk")."<br>";
}

if ( file_exists( ENGINE_DIR."/version.txt" )) {
    $core_version = file( ENGINE_DIR."/version.txt" );
    $core_version = implode("<br>", $core_version);
} else {
    $core_version = Warecorp::t("trunk")."<br>";
}

$db_version = Warecorp_System::getDbVersion();

$this->view->version = $data;
$this->view->core_version = $core_version;
$this->view->db_version = $db_version;
$this->view->bodyContent = "info/version.tpl";
