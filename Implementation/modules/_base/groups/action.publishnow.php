<?php
Warecorp::addTranslation('/modules/groups/action.publishnow.php.xml');

$Data = new Warecorp_Group_Publish("group_id", $this->currentGroup->getId());

$base_server_url = $this->currentGroup->getPath();

print "
<div id='main_div'>
    <center><font style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#000000'>".Warecorp::t("Publishing in progress")."</font></center>
<center>
    <div id='publish_percent' style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; font-weight:bold; color:#FF0000;'>25%</div>
</center>
    <table width='50' bgcolor='green' id='t1'>
    <tr><td id='td1'><img src='theme/product/images/decorators/px.gif' width='0' heigth='20'></td>
</tr>
</table>
<div id='publish_text' style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; font-weight:bold; color:#FF0000;'>Connect</div>
</div>
";
flush();
restore_error_handler();
error_reporting(0);//disable all errors;

// set up basic connection

$parts = explode(":", $Data->getFtpServer());
if (!$parts[1]) $parts[1] = null;

$conn_id = @ftp_connect($parts[0], $parts[1], 30);
if (!$conn_id )
{
    sleep(1);
    print "<script>
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("FTP connection has failed!")."';
            </script>";
    flush(); exit;
}

sleep(1);
print "<script>document.getElementById('t1').width='100';
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Logging In")."';
            document.getElementById('publish_percent').innerHTML = '50%';
            </script>";
flush();

// login with username and password
$login_result = @ftp_login($conn_id, $Data->getFtpUsername(), $Data->getFtpPassword());

if (!$login_result) {
    sleep(1);
    print "<script>
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("FTP Login Failed!")."';
            </script>";
    flush(); exit;
} else {
    sleep(1);
    print "<script>document.getElementById('t1').width='120';
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Change Folder")."';
            document.getElementById('publish_percent').innerHTML = '60%';
            </script>";
    flush();
}

if ($Data->getFtpMode() == 1){
    sleep(1);
    print "<script>document.getElementById('t1').width='100';
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Enable Passive Mode")."';
            document.getElementById('publish_percent').innerHTML = '50%';
            </script>";
    flush();
    if ( ftp_pasv($conn_id, true) == FALSE) {
        print "<script>document.getElementById('t1').width='100';
                document.getElementById('publish_text').innerHTML = '".Warecorp::t("Failed to enable passive mode")."';
                document.getElementById('publish_percent').innerHTML = '50%';
                </script>";
        flush();
        exit;
    }
}


if ($Data->getFtpFolder()){
    $chdir_result = @ftp_chdir($conn_id, $Data->getFtpFolder());

    if (!$chdir_result) {
        sleep(1);
        print "<script>
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("FTP CHDIR Failed!")."';
            </script>";
        flush(); exit;
    } else {
        sleep(1);
        print "<script>document.getElementById('t1').width='150';
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Uploading")."';
            document.getElementById('publish_percent').innerHTML = '75%';
            </script>";
        flush();
    }
} else {
    print "<script>document.getElementById('t1').width='150';
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Uploading")."';
            document.getElementById('publish_percent').innerHTML = '75%';
            </script>";
    flush();

}
$filename = APP_VAR_DIR . "/tmp/".$this->currentGroup->getId().".tmp";
$fp = fopen($filename, "w");

//GENERATE STATIC HTML PAGE WITH DDcontent
$theme = Warecorp_Theme::loadThemeFromDB($this->currentGroup);
$theme->prepareFonts();
$this->view->theme = $theme;
$this->view->contentBlocksHTML = Warecorp_DDPages::getAllBlocksHTML($this->_page, $this->currentGroup, $this->_page->_user);
$this->view->data = $this->view->getContents("content_objects/publish.tpl");
$this->view->base_host = BASE_HTTP_HOST;

$file_content = $this->view->getContents("main_publish.tpl");


if ( fwrite($fp,$file_content) == FALSE) {
    print "<script>
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Failed to create temp file!")."';
            </script>";
    fclose($fp);
    flush(); exit;
}

@ftp_delete( $conn_id, $Data->getFilename());
$upload = ftp_put($conn_id, $Data->getFilename(), $filename, FTP_ASCII);
fclose($fp);

// check upload status
if (!$upload) {
    sleep(1);
    print "<script>
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("FTP upload has failed!")."';
            </script>";
    unlink($filename);
    flush(); exit;
} else {
    sleep(1);
    print "<script>document.getElementById('t1').width='200';
            document.getElementById('publish_text').innerHTML = '".Warecorp::t("Completed")."';
            document.getElementById('publish_percent').innerHTML = '100%';
            </script>";
    flush();
    sleep(2);
    print "<script>
            document.getElementById('main_div').innerHTML = '<font style=\'font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#000000\'>".Warecorp::t("PUBLISHED Successfully")."</font>';
            </script>";
    flush();
}
ftp_close($conn_id);

$Data->setlastPublish(date("Y-m-d H:i:s", time()));
$Data->save();

//unlink($filename);
print "<script>
          top.location.href='".$this->currentGroup->getGroupPath('editpublishstatus')."';
       </script>";

exit;
/**/
