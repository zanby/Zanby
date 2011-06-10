<?php
/**
 * 
 * @author Serge Rybakov
 */
class BaseCmsController extends Warecorp_Controller_Action
{
    public $params;

    public function init()
    {
        Warecorp::addTranslation("/modules/cms/cms.controller.php.xml");
    	parent::init();
        $this->params = $this->_getAllParams();
    }

    // Block-specific actions **
    public function blockEditSaveAction($frmData) { include_once(PRODUCT_MODULES_DIR . "/cms/xajax/action.blockEditSave.php"); return $objResponse; }

    public function blockEditPopupJSAction($divid, $id, $pid, $ord) { include_once(PRODUCT_MODULES_DIR . "/cms/xajax/action.blockEditPopupJS.php"); return $objResponse; }
    //** Block-specific actions
}
