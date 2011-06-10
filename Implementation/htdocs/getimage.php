<?php
/* Init Core and required constants */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php'; 

$application->bootstrap(array('Defines','ApplicationTheme', 'CssJsImagesPaths'));
$AppTheme = Zend_Registry::get('AppTheme');

defined('THEME_IMAGES_PATH') || define('THEME_IMAGES_PATH', 
    rtrim($AppTheme->images_path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'gallery_photos'.DIRECTORY_SEPARATOR);
defined('UPLOADED_PHOTOS_PATH') || define('UPLOADED_PHOTOS_PATH', 
    rtrim(UPLOAD_BASE_PATH, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'gallery_photos'.DIRECTORY_SEPARATOR);

$id             = ( isset($_GET['id'])     )        ? intval($_GET['id'])           : 0;
$width          = ( isset($_GET['width'])  )        ? intval($_GET['width'])        : 0;
$height         = ( isset($_GET['height']) )        ? intval($_GET['height'])       : 0;
$border         = ( isset($_GET['border']) )        ? intval($_GET['border'])       : 0;
$proportional   = ( isset($_GET['proportional']) )  ? intval($_GET['proportional']) : 0;

if ($id == 0) {
    $filename = THEME_IMAGES_PATH.'noimage_x'.$width.'_y'.$height.'_b'.$border.'.jpg';
}

$filename = UPLOADED_PHOTOS_PATH.md5($id.'zbphoto').'_x'.$width.'_y'.$height.'_b'.$border.'_p'.$proportional.'.jpg';

if (!file_exists($filename)) {
    $filename = THEME_IMAGES_PATH.'noimage_x'.$width.'_y'.$height.'_b'.$border.'.jpg';
}

if( ! (isset($filename) && file_exists($filename) && is_readable($filename)) ) {
    $filename = THEME_IMAGES_PATH.'noimage.jpg';
}

$img = imagecreatefromjpeg($filename);
header('Content-type: image/jpeg');
imagejpeg($img);
imagedestroy($img);
