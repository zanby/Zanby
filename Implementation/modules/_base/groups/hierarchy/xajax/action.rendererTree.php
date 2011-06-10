<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.rendererTree.php.xml');

    $objResponse = new xajaxResponse();
    $state = Warecorp_Location_State::create($stateid);
    $cities = $state->getCitiesListAssoc();
    $Script = "TreeChilds = new Array();";
    $ind = 0;
    foreach ( $cities as $cin => $city ) {
        //$Script .= "var tmpTarget = new YAHOO.util.DDTarget('".$city."_div');";
        $Script .= "TreeChilds[".$ind."] = {label : '".$city."' , divid : '".$city."_div', stateid : '".$state->id."' };";
        $ind++;
    }
    $objResponse->addScript($Script);

