<?php

class BaseInfoController extends Warecorp_Controller_Action
{
	public function init()
    {
        Warecorp::addTranslation('/modules/info/info.controller.php.xml');

    	parent::init();
    	$this->_page->setTitle(Warecorp::t('Information'));
    	//@todo author Akexander Komarovski $this->view->menuContent = '_design/menu_content/menu_content.tpl';
        
        /* set wide template for all info pages and remove right block form loged in user */
        $this->view->setLayout('main_wide.tpl');
        if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
            $this->view->isRightBlockHidden = true;
        }
    }

    public function indexAction()		{ $this->_redirect('/'); }

    public function feedbackAction()			{include_once(PRODUCT_MODULES_DIR.'/info/action.feedback.php');}
    public function aboutAction()				{include_once(PRODUCT_MODULES_DIR.'/info/action.about.php');}
    public function contactusAction()			{include_once(PRODUCT_MODULES_DIR.'/info/action.contactus.php');}
    public function privacyAction()     		{include_once(PRODUCT_MODULES_DIR.'/info/action.privacy.php');}
    public function termsAction()       		{include_once(PRODUCT_MODULES_DIR.'/info/action.terms.php');}
    public function hostfaqAction()     		{include_once(PRODUCT_MODULES_DIR.'/info/action.hostfaq.php');}
    public function siteguideAction()   		{include_once(PRODUCT_MODULES_DIR.'/info/action.siteguide.php');}
    public function versionAction()     		{include_once(PRODUCT_MODULES_DIR.'/info/action.version.php');}
	public function copyrightAction()			{include_once(PRODUCT_MODULES_DIR.'/info/action.copyright.php');}
	public function tourAction()			    {include_once(PRODUCT_MODULES_DIR.'/info/action.tour.php');}
	public function captchaAction()				{include_once(PRODUCT_MODULES_DIR.'/info/action.captcha.php');}
	public function strengthAction()			{include_once(PRODUCT_MODULES_DIR.'/info/action.strength.php');}
	public function learnmoreuserAction()		{include_once(PRODUCT_MODULES_DIR.'/info/action.learnmoreuser.php');}
	public function learnmoregroupAction()		{include_once(PRODUCT_MODULES_DIR.'/info/action.learnmoregroup.php');}
	public function learnmorefamilygroupAction(){include_once(PRODUCT_MODULES_DIR.'/info/action.learnmorefamilygroup.php');}
	public function cidAction()                 {include_once(PRODUCT_MODULES_DIR.'/info/action.cid.php');}
	public function supportAction()             {include_once(PRODUCT_MODULES_DIR.'/info/action.support.php');}
    public function listsrankingAction()        {include_once(PRODUCT_MODULES_DIR.'/info/action.listsrank.php');}
    public function listsviewaddAction()        {include_once(PRODUCT_MODULES_DIR.'/info/action.listsviewadd.php');}
    
	public function getTmxKeyAction()
	{
		$mess = 'Click edit icon';
		$key = Warecorp_Translate::create_key( $mess );
		print $key; exit;
	}
	
    public function kmlAction()
    {
        //==========================================
//        $handle = fopen(UPLOAD_BASE_PATH.'/kml/sochi.wpt', "r");
//        $contents = fread($handle, filesize(UPLOAD_BASE_PATH.'/kml/sochi.wpt'));
//        fclose($handle);
//        
//        $wpt = file( UPLOAD_BASE_PATH.'/kml/sochi.wpt' );
//        foreach ( $wpt as $_ind => &$line ) {
//            $lineCnt = explode(",", $line);
//            if ( is_array($lineCnt) && isset($lineCnt[10]) ) {
//                $lineCnt[1] = $lineCnt[1].'_'.$lineCnt[0];
//            }
//            $line = join(',', $lineCnt);
//        }
//        $content = join("", $wpt);
//        print_r($content); exit();
        
        //==========================================
        
//        $dom = new DOMDocument();
//        $dom->encoding = 'UTF-8';
//        $dom->preserveWhiteSpace = false;
//        $dom->formatOutput = true;
//        $dom->load(UPLOAD_BASE_PATH.'/kml/sochi.kml');        
//
//        $xpath = new DOMXPath($dom);
//                
//        $wpt = file( UPLOAD_BASE_PATH.'/kml/sochi.wpt' );
//        foreach ( $wpt as $line ) {
//            $lineCnt = explode(",", $line);
//            if ( is_array($lineCnt) && isset($lineCnt[10]) ) {
//                $query = '//kml/Document/Folder/Placemark[name="'.$lineCnt[1].'"]';
//                $entries = $xpath->query($query);
//                
//                if ( $entries->length != 0 ) { 
//                    $description = $dom->createElement('description');
//                    $entries->item(0)->appendChild($description);
//
//                    $data = $dom->createTextNode($lineCnt[10]);
//                    $description->appendChild($data);
//                } else print $lineCnt[1]."<br>";                
//            }
//        }
//        
//        /**
//         * Write to file
//         */            
//        $dom->formatOutput = true;
//        $fp = fopen(UPLOAD_BASE_PATH.'/kml/sochi1.kml', 'w');
//        //fwrite($fp, "\xEF\xBB\xBF".$dom->saveXML());
//        fwrite($fp, $dom->saveXML());
//        fclose($fp);
//        exit();
		
		//==========================================

		$kmlFile = UPLOAD_BASE_PATH.'/kml/kk1.kml';
		$kmlFile = UPLOAD_BASE_PATH.'/kml/sochi1.kml';
		
        $dom = new DOMDocument();
        $dom->encoding = 'UTF-8';
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->load($kmlFile);        

        $xpath = new DOMXPath($dom);
        $query = '//kml/Document/Folder/Placemark';
        $Placemarks = $xpath->query($query);
        
        if ( $Placemarks->length != 0 ) {
            for ( $i = 0; $i < $Placemarks->length; $i++ ) {
                $Placemark = $Placemarks->item($i);
                $Placemark->getElementsByTagName('name')->item(0)->nodeValue = $Placemark->getElementsByTagName('description')->item(0)->nodeValue;
            }
        }

		
        /**
         * Write to file
         */            
        $dom->formatOutput = true;
        $fp = fopen($kmlFile.'.kml', 'w');
        //fwrite($fp, "\xEF\xBB\xBF".$dom->saveXML());
        fwrite($fp, $dom->saveXML());
        fclose($fp);
        exit();

    }
    
	public function noRouteAction()		{ $this->_redirect('/'); }
}
