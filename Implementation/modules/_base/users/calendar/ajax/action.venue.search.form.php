<?php
Warecorp::addTranslation("/modules/users/calendar/xajax/action.venue.search.form.php.xml");
$objResponse = new xajaxResponse ( ) ;
$objResponse->addClear ( 'find_simple_venue_body', 'innerHTML' ) ;

$objResponse->addScript ( "changevenueto('find_simple')" ) ;

$form = new Warecorp_Form('find_venues', 'POST');

$aCreatedBy = array(
    'anyone'            => Warecorp::t('Anyone'),
    'friends'           => Warecorp::t('Friends'),
    'groups'            => Warecorp::t('Groups'),
    'groups_families'   => Warecorp::t('Group Families'));

$aoVenuesCategoriesList = new Warecorp_Venue_CategoryList();
$aoVenuesCategoriesList->setType(Warecorp_Venue_Enum_VenueType::SIMPLE);
$aoVenuesCategoriesList->returnAsAssoc();
$aVenueCategories = $aoVenuesCategoriesList->getList();
$aoVenuesCategoriesList->setType(Warecorp_Venue_Enum_VenueType::WORLDWIDE);
$aVenueCategoriesWide = $aoVenuesCategoriesList->getList();
$aVenueCategories = array_merge($aVenueCategories, $aVenueCategoriesWide);
$aVenueCategories[0] = 'All';
ksort($aVenueCategories);

$this->view->form = $form;
$this->view->aCreatedBy = $aCreatedBy;
$this->view->aVenueCategories = $aVenueCategories;

if (!empty($aParams)){
	$searchObj = new Warecorp_Venue_Search($this->currentUser);

	$searchObj->parseParams($aParams);

	$aResult = $searchObj->getSearchResult($aParams);
	$form = new Warecorp_Form('findAVenue',"POST");

	if (is_array($aParams['find_keywords']))
	   $aParams['find_keywords'] = implode(',', $aParams['find_keywords']);

    $this->view->aoVenuesList = $aResult;
    $this->view->countVenues  = $searchObj->count;
    $this->view->form         = $form;
}

$this->view->aSearches = $aParams;
$output = $this->view->getContents ( 'users/calendar/venues.search.index.tpl' ) ;
$objResponse->addAssign ( 'find_simple_venue_body', 'innerHTML', $output ) ;
?>
