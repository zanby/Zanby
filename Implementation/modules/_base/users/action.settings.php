<?php

    $this->_page->Xajax->registerUriFunction("settings_basicInformation_show",  "/users/showBasicInformation/");
    $this->_page->Xajax->registerUriFunction("settings_basicInformation_hide",  "/users/hideBasicInformation/");
    $this->_page->Xajax->registerUriFunction("settings_loginInformation_show",  "/users/showLoginInformation/");
    $this->_page->Xajax->registerUriFunction("settings_loginInformation_hide",  "/users/hideLoginInformation/");
    $this->_page->Xajax->registerUriFunction("settings_accountCancel_show",     "/users/showAccountCancel/");
    $this->_page->Xajax->registerUriFunction("settings_accountCancel_hide",     "/users/hideAccountCancel/");
    $this->_page->Xajax->registerUriFunction("settings_basicInformation_save",  "/users/saveBasicInformation/");
    $this->_page->Xajax->registerUriFunction("settings_loginInformation_save",  "/users/saveLoginInformation/");
    $this->_page->Xajax->registerUriFunction("settings_accountCancel_save",     "/users/saveAccountCancel/");    
    $this->_page->Xajax->registerUriFunction("detectCountry",                   "/ajax/detectCountry/");
    //$this->_page->Xajax->registerUriFunction("autoCompleteCity",                "/ajax/autoCompleteCity/");
    //$this->_page->Xajax->registerUriFunction("autoCompleteZip",                 "/ajax/autoCompleteZip/");
    $this->_page->Xajax->registerUriFunction("zipcodeavailable",                "/ajax/zipCodeAvailable/");
    $this->_page->Xajax->registerUriFunction("cityavailable",                   "/ajax/cityAvailable/");
    $this->_page->Xajax->registerUriFunction("citychoosealias",                 "/ajax/cityChooseAlias/");
    $this->_page->Xajax->registerUriFunction("citychoosecustom",                "/ajax/cityChooseCustom/");
    

    $visible = isset($this->params["visible"]) ? $this->params["visible"] : "";
    $this->view->visibility_details = $visible;
    $this->view->bodyContent = 'users/settings.tpl';
