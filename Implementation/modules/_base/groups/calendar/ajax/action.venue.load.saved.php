<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.venue.load.saved.php.xml');
$objResponse = new xajaxResponse ( ) ;
$objResponse->addClear ( 'saved_simple_venue_body', 'innerHTML' ) ;

$objResponse->addScript ( "changevenueto('saved_simple')" ) ;

$aoVenuesList = new Warecorp_Venue_List();
$aoVenuesList->setOwnerId( $this->currentGroup->getId() );
$aoVenuesList->setType( 'simple' );
$aoVenuesList->setCategory($aParams['c']);
$aoVenuesList->setLetter($aParams['l']);
$aoVenuesList->setCurrentPage($aParams['p']);
$aoVenuesList->setListSize(10);

$usedLetters = $aoVenuesList->getLettersList ( ) ;

for ( $i = 'A' ; $i != 'AA' ; $i ++ ) {
	$letters [ $i ] = array ( 'current' => $i == $aParams [ 'l' ] ? true : false , 
	                          'link'    => array_key_exists ( $i, $usedLetters ) ? true : false ) ;
}

$aoVenuesCategoriesList = new Warecorp_Venue_CategoryList();
$aoVenuesCategoriesList->returnAsAssoc();
$aoVenuesCategoriesList->setType( Warecorp_Venue_Enum_VenueType::SIMPLE );
$venue_categories = $aoVenuesCategoriesList->getList();
$venue_categories[0] = Warecorp::t("[ CHOOSE CATEGORY ]");
ksort($venue_categories);

$this->view->categories           = $venue_categories;
$this->view->letters              = $letters; 
$this->view->countVenues          = $aoVenuesList->getCount();
$this->view->aSearches            = $aParams;
$this->view->event                = array('venue_type' => 'simple');
$this->view->aoSimpleVenuesList   = $aoVenuesList->getList(); 

$output = $this->view->getContents ( 'groups/calendar/venues.saved.index.tpl' ) ;
$objResponse->addAssign ( 'saved_simple_venue_body', 'innerHTML', $output ) ;



?>
