<?php
    $file = $this->getRequest()->getParam('file', null);
    $requestFile = $file;
    $file = APP_HOME_DIR.'/languages'.urldecode($file);
    $pathinfo = pathinfo($file);

    if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
        $LocalesList = $_SESSION['translation_tools']['locales'];
    } else $LocalesList = Warecorp::getLocalesList();

    /**
     * Load original file
     */
    $origDom = new DOMDocument();
    $origDom->encoding = 'UTF-8';
    $origDom->preserveWhiteSpace = false;
    $origDom->formatOutput = true;
    $origDom->load($file);
    /**
    * Create new language xml file from original
    */
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->encoding = 'UTF-8';
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($origDom->saveXML());
    unset($origDom);
    $body = $dom->getElementsByTagName('tmx')->item(0)->getElementsByTagName('body')->item(0);

    $tus = $body->getElementsByTagName('tu');
    if ( 0 != $tus->length ) {
        //$tuvsToRemove = array();
        foreach ( $tus as $tu ) {
            $defaultTuv = null;
            $secondTuvs = array();
            $tuvs = $tu->getElementsByTagName('tuv');
            if ( 0 != $tuvs->length ) {
                foreach ( $tuvs as $tuv ) {
                    $lang = $tuv->getAttribute('xml:lang');
                    if ( in_array($lang, $LocalesList) ) {
                        $secondTuvs[$lang] = $tuv;
                        if ( $lang == Warecorp::getDefaultLocale() ) $defaultTuv = $tuv;
                    } else $tu->removeChild($tuv);
                }
//                if ( !empty($tuvsToRemove) ) {
//                    foreach ( $tuvsToRemove as $tuv ) {
//                        $tu->removeChild($tuv);
//                    }
//                }
                foreach ( $LocalesList as $_locale ) {
                    if ( 'rss' != $_locale && !isset($secondTuvs[$_locale]) ) {
                        $_tuv = clone $defaultTuv;
                        $_tuv->setAttribute('xml:lang', $_locale);
                        $segment = $_tuv->getElementsByTagName('seg')->item(0);
                        $segment->nodeValue = '';
                        $tu->appendChild($_tuv);
                    }
                }
            }
        }
    }
    $xml = $dom->saveXML();
    header("Content-Type: text/xml"); // application/octet-stream
    header("Content-Length: ". mb_strlen($xml, 'UTF-8') );
    header("Content-Disposition: attachment; filename=\"".$pathinfo['basename']."\"");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: must-revalidate");
    header("Content-Location: ".$pathinfo['basename']."");
    print $xml;
    exit;
