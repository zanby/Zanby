<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyMap/action.ddFamilyMapValidate.php.xml');

$objResponse = new xajaxResponse();

if ($params["Data"]["area"]["type"] == 'custom') {
    
    if (!$params["Data"]["area"]["around"]) {
        $errors[] = Warecorp::t("please specify square miles around center of the area");
    } else {    
        if ($params["Data"]["area"]["around"]<1) {
            $errors[] = Warecorp::t("please specify more then 1 square mile around center of the area");
        }    
    }
    
    if ($params["Data"]["area"]["radio_code"] == 'zip') {
    
        $zip = $params["Data"]["area"]["zip"];  
        $zipCode = Warecorp_Location_Zipcode::create($zip);
        
        if (!$zipCode->id) {
            $errors[] = Warecorp::t("we cannot find Zip code %s, please correct it",$zip);
        }
    }    
    
    if ($params["Data"]["area"]["radio_code"] == 'lat') {
    
        if (!$params["Data"]["area"]["latitude"]) {
            $errors[] = Warecorp::t("please specify latitude");
        }
        
        if (!$params["Data"]["area"]["longitude"]) {
            $errors[] = Warecorp::t("please specify longitude");
        }        
        
        if ($params["Data"]["area"]["latitude"] > 90 || $params["Data"]["area"]["latitude"] < -90) {
            $errors[] = Warecorp::t("latitude must be between -180 and 180");
        }    

        if ($params["Data"]["area"]["longitude"] > 180 || $params["Data"]["area"]["longitude"] < -180) {
            $errors[] = Warecorp::t("longitude must be between -180 and 180");
        }          
    }        
}



if (isset($errors)) {   
    $objResponse->addScript("document.getElementById('error_block_$cloneId').innerHTML = 'errors:<ul>';");
    foreach ($errors as $item) {         
        $objResponse->addScript("document.getElementById('error_block_$cloneId').innerHTML += '<li>$item</li>';");
    }
    $objResponse->addScript("document.getElementById('error_block_$cloneId').innerHTML += '</ul><br>';");
} else {
    $objResponse->addScript("document.getElementById('error_block_$cloneId').innerHTML = '';");
    if ($isRedrawElementLight)
        $objResponse->addScript('WarecorpDDblockApp.redrawElementLight("'.$cloneId.'");');
    else
        $objResponse->addScript('applyEditMode2("'.$cloneId.'");');   
}