<?php

class BaseSummaryController extends Warecorp_Controller_Action
{
    public $params;

    public function init()
    {
        parent::init();
        $this->params = $this->getRequest()->getParams();
    }
    public function noRouteAction()		{$this->_redirect('/'); }

}
