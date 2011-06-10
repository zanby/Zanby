<?php

class BaseCronController extends Warecorp_Controller_Action
{
    public function updateTimezonesAction()
    {
	    /*
	    $tzRes = array();
	//    $ch = curl_init();
	//    curl_setopt($ch, CURLOPT_URL, "http://unicode.org/cldr/data/common/supplemental/supplementalData.xml");
	//    curl_setopt($ch, CURLOPT_HEADER, 0);
	//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//    $xml = curl_exec($ch);
	//    curl_close($ch);
	    
	    $dom = new DOMDocument();
	    //$dom->loadXML($xml);
	    $dom->load(DOC_ROOT."/discussions_log/xml.xml");
	    
	    $xp = new domXPath($dom);
	    $mapTimezones = $xp->query('/supplementalData/timezoneData/mapTimezones');
	    if ( $mapTimezones->length != 0 ) {
	        for ( $i = 0; $i < $mapTimezones->length; $i++ ) {
	           if ( 'windows' == $mapTimezones->item($i)->getAttribute('type') ) {
	               $str = $dom->saveXML($mapTimezones->item($i));
	               preg_match_all('/<mapZone\s{1,}other="([^"]*)"\s{1,}type="([^"]*)"\s{0,}\/>\s{0,}<!--\s{0,}[sd]\s{0,}(.*?)-->/im', $str, $matches);
	               if ( sizeof($matches[0] != 0) ) {
	                    for ( $i = 0; $i < sizeof($matches[0]); $i++ ) {
	                        $tzRes[] = array(
	                            'timezone_id' => trim($matches[2][$i]),
	                            'timezone_label' => trim($matches[1][$i]),
	                            'timezone_win_name' => trim($matches[3][$i])
	                        );
	                    }
	               }
	           }
	        }
	    }
	    if ( sizeof($tzRes) != 0 ) {
	        $db = Zend_Registry::get('DB');
	        foreach ( $tzRes as $zone ) {
	            $data = array();
	            $data['name'] = $zone['timezone_win_name'];
	            $data['tz_name'] = $zone['timezone_id'];
	            $data['tz_label'] = $zone['timezone_label'];
	            $db->insert('zanby_location__timezones', $data);
	        }
	    }
	    print_r($tzRes);
	    exit;
	    */
    }
    
	public function noRouteAction()
	{
        echo 'Cron not found!';
        exit;
	}
}
