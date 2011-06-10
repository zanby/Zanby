<?php
$cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig("cfg.instance.xml");
define('GOOGLE_MAP_KEY', $cfgInstance->google_map_key);

$user_id    = isset($this->params['user']) ? floor($this->params['user']) : 0;
$size_x     = isset($this->params['sizex']) ? floor($this->params['sizex']) : 300;
$size_y     = isset($this->params['sizey']) ? floor($this->params['sizey']) : 300;
$zoom       = isset($this->params['zoom']) ? floor($this->params['zoom']) : 5;
$show_tools = isset($this->params['showtools']) ? 1 : 0;
$dragable   = isset($this->params['dragable']) ? 1 : 0;
$user = new Warecorp_User("id", $user_id);

$this->view->size_x = $size_x;
$this->view->size_y = $size_y;
$this->view->zoom = $zoom;
$this->view->show_tools = $show_tools;
$this->view->dragable = $dragable;
$this->view->user_lat = $user->getLatitude();
$this->view->user_lng = $user->getLongitude();
$this->view->key = GOOGLE_MAP_KEY;

print $this->view->getContents('gmap/singleuser.tpl');
exit;

