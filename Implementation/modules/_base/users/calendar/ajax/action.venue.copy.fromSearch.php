<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.venue.copy.fromSearch.php.xml");
    // Common data
    $objResponse = new xajaxResponse ( ) ;

    $venue = new Warecorp_Venue_Item($aParams);

    $venue_tags  = $venue->getTagsList();
    if ($venue_tags) {
        foreach ($venue_tags as &$_tag) {
            $_tag = $_tag->getPreparedTagName();
        }
        $tags = implode(' ',$venue_tags);
    }

    if ( $venue->getOwnerId() !== $this->_page->_user->getId() ) {
    $copyVenue = clone($venue);

    $copyVenue->setId(null);
    $copyVenue->setOwnerId( $this->currentUser->getId() );
    $copyVenue->setOwnerType( 'user' );

    if ($copyVenue->save()){
        if (isset($tags)) $copyVenue->addTags($tags);
        $objResponse->showAjaxAlert( Warecorp::t('Venue is Saved!' ));
    } else {
    	$objResponse->showAjaxAlert( Warecorp::t('Venue isn\'t Saved!' ));
    }
    } else {
    	$objResponse->showAjaxAlert( Warecorp::t('Venue is alredy Yours' ));
    }
    //$output = $this->view->getContents ( 'users/calendar/venues.add.edit.tpl' ) ;
    //$objResponse->addAssign ( 'saved_simple_venue_body', 'innerHTML', $output ) ;
?>