<?php
Warecorp::addTranslation('/modules/groups/action.gmapsinglegroup.php.xml');

$cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig("cfg.instance.xml");
define('GOOGLE_MAP_KEY', $cfgInstance->google_map_key);

$group_id   = isset($this->params['group'])     ? floor($this->params['group']) : 0;
$size_x     = isset($this->params['sizex'])     ? floor($this->params['sizex']) : 300;
$size_y     = isset($this->params['sizey'])     ? floor($this->params['sizey']) : 300;
$zoom       = isset($this->params['zoom'])      ? floor($this->params['zoom'])  : 5;
$show_tools = isset($this->params['showtools']) ? 1                             : 0;
$dragable   = isset($this->params['dragable'])  ? 1                             : 0;

$group = new Warecorp_Group_Simple("id", $group_id);

$this->view->size_x = $size_x;
$this->view->size_y = $size_y;
$this->view->zoom = $zoom;
$this->view->show_tools = $show_tools;
$this->view->dragable = $dragable;
$this->view->group_lat = $group->getLatitude();
$this->view->group_lng = $group->getLongitude();
$this->view->key = GOOGLE_MAP_KEY;

print $this->view->getContents('gmap/singlegroup.tpl');
exit;
/**/
