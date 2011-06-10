<?php
    function smarty_function_menu_bread_crumb($params, &$smarty)
    {
        $objUser = Zend_Registry::get('User');
		Warecorp::addTranslation('/plugins/function.menu_bread_crumb.php.xml');
		
        $objUser = $objCurrentUser = $objGroup = null;
        $context = Warecorp::isContext($smarty, $objUser, $objCurrentUser, $objGroup);
        $objRequest = $smarty->get_template_vars('objRequest');

        $delim = ' <span class="color1">/</span> ';
        $output = array();
        switch ( $context ) {
            case 'info' :
                if ( Warecorp::$actionName == 'Feedback' )  			$output[] = Warecorp::t('Feedback');
                if ( Warecorp::$actionName == 'about' )     			$output[] = Warecorp::t('About Us');
                if ( Warecorp::$actionName == 'contactus' ) 			$output[] = Warecorp::t('Contact Us');
                if ( Warecorp::$actionName == 'privacy' )   			$output[] = Warecorp::t('Privacy Policy');
                if ( Warecorp::$actionName == 'terms' )	    			$output[] = Warecorp::t('Terms of Service');
                if ( Warecorp::$actionName == 'hostfaq' )   			$output[] = Warecorp::t('FAQ for Group Hosts');
                if ( Warecorp::$actionName == 'siteguide' ) 			$output[] = Warecorp::t('Guide');
                if ( Warecorp::$actionName == 'version' )   			$output[] = Warecorp::t('Information');
                if ( Warecorp::$actionName == 'copyright' ) 			$output[] = Warecorp::t('Copyright');
                if ( Warecorp::$actionName == 'tour' ) 	    			$output[] = Warecorp::t('Tour');
                if ( Warecorp::$actionName == 'captcha' )   			$output[] = Warecorp::t('What is a CAPTCHA?');
                if ( Warecorp::$actionName == 'strength' )  			$output[] = Warecorp::t('Password Strength');
                if ( Warecorp::$actionName == 'learnmoreuser' ) 		$output[] = Warecorp::t('Learn more about user');
                if ( Warecorp::$actionName == 'learnmoregroup' )		$output[] = Warecorp::t('Learn more about group');
                if ( Warecorp::$actionName == 'learnmorefamilygroup' ) 	$output[] = Warecorp::t('Learn more about family group');
                if ( Warecorp::$actionName == 'cid' ) 					$output[] = Warecorp::t('What is a CID?');
                if ( Warecorp::$actionName == 'support' ) 				$output[] = Warecorp::t('Support');
                if ( Warecorp::$actionName == 'listsranking' ) 			$output[] = Warecorp::t('What is Ranking?');
                if ( Warecorp::$actionName == 'listsviewadd' ) 			$output[] = Warecorp::t('What is Allow viewers to add items?');
            case 'users_index' :
                if ( Warecorp::$actionName == 'index' )     $output[] = Warecorp::t('Browse Members');
                if ( Warecorp::$actionName == 'search' )    $output[] = Warecorp::t('Browse and Search Members');
                break;
            case 'group_index' :
                if ( Warecorp::$actionName == 'index' )     $output[] = Warecorp::t('Browse Groups');
                if ( Warecorp::$actionName == 'search' )    $output[] = Warecorp::t('Browse and Search Groups');
				if ( Warecorp::$actionName == 'familylanding' )    $output[] = Warecorp::t('Browse Group Families');
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | User Account
             * |
             * +-----------------------------------------------------------------
             */
            case 'user_account' :
                if ( Warecorp::is('Settings', 'User') )     $output[] = Warecorp::t('Account Information');
                if ( Warecorp::is('Rounds', 'User') )       $output[] = Warecorp::t('Past Participation');
                if ( Warecorp::is('Avatars', 'User') )      $output[] = Warecorp::t('My Profile Photos');
                if ( Warecorp::is('Privacy', 'User') )      $output[] = Warecorp::t('My Privacy');
                if ( Warecorp::is('Bookmarks', 'User') )    $output[] = Warecorp::t('My Bookmarks');
                if ( Warecorp::is('Networks', 'User') )     $output[] = Warecorp::t('Facebook');

                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | User Profile
             * |
             * +-----------------------------------------------------------------
             */
            case 'user_profile' :
                $output[] = "<a href=\"{$objCurrentUser->getUserPath('profile')}\">".htmlspecialchars($objCurrentUser->getLogin())."</a>";
                if ( Warecorp::is('Groups', 'User') )       $output[] = Warecorp::t('My Groups');
                if ( Warecorp::is('Discussions', 'User') )  $output[] = Warecorp::t('My Discussions');
                if ( Warecorp::is('Events', 'User') )       $output[] = Warecorp::t('My Events');
                if ( Warecorp::is('Friends', 'User') )      $output[] = Warecorp::t('My Friends');
                if ( Warecorp::is('Messages', 'User') )     $output[] = Warecorp::t('My Messages');
                if ( Warecorp::is('Photos', 'User') )       $output[] = Warecorp::t('My Photos');
                if ( Warecorp::is('Videos', 'User') )       $output[] = Warecorp::t('My Videos');
                if ( Warecorp::is('Documents', 'User') )    $output[] = Warecorp::t('My Documents');
                if ( Warecorp::is('Lists', 'User') )        $output[] = Warecorp::t('My Lists');
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | People Profile
             * |
             * +-----------------------------------------------------------------
             */
            case 'people_profile' :
                $output[] = "<a href=\"{$objCurrentUser->getUserPath('profile')}\">".htmlspecialchars($objCurrentUser->getLogin())."</a>";
                if ( Warecorp::is('Events', 'User') )       $output[] = Warecorp::t('Events');
                if ( Warecorp::is('Friends', 'User') )      $output[] = Warecorp::t('Friends');
                if ( Warecorp::is('Photos', 'User') )       $output[] = Warecorp::t('Photos');
                if ( Warecorp::is('Videos', 'User') )       $output[] = Warecorp::t('Videos');
                if ( Warecorp::is('Documents', 'User') )    $output[] = Warecorp::t('Documents');
                if ( Warecorp::is('Lists', 'User') )        $output[] = Warecorp::t('Lists');
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | Group
             * |
             * +-----------------------------------------------------------------
             */
            case 'group' :
                if ( 'EIA' == IMPLEMENTATION_TYPE ) {
                    if ( Zend_Registry::isRegistered('globalGroup') ) {
                        require_once(MODULES_DIR.'/GroupsController.php');
                        $objGlobalGroup = Zend_Registry::get('globalGroup');
                    } else throw new Exception('Incorrect global group param');
                }
                if ( empty($objGlobalGroup) || $objGlobalGroup->getId() != $objGroup->getId() ) {
                    $output[] = "<a href=\"{$objGroup->getGroupPath('summary')}\">".htmlspecialchars($objGroup->getName())."</a>";
                    if ( Warecorp::is('Members', 'Group') )         $output[] = Warecorp::t('Members');
                    if ( Warecorp::is('Discussions', 'Group') )     $output[] = Warecorp::t('Discussions');
                    if ( Warecorp::is('Events', 'Group') )          $output[] = Warecorp::t('Events');
                    if ( Warecorp::is('Photos', 'Group') )          $output[] = Warecorp::t('Photos');
                    if ( Warecorp::is('Videos', 'Group') )          $output[] = Warecorp::t('Videos');
                    if ( Warecorp::is('Documents', 'Group') )       $output[] = Warecorp::t('Documents');
                    if ( Warecorp::is('Lists', 'Group') )           $output[] = Warecorp::t('Lists');
                    if ( Warecorp::is('Settings', 'Group') )        $output[] = Warecorp::t('Settings');
                    if ( Warecorp::is('Hierarchy', 'Group') )       $output[] = Warecorp::t('Hierarchy');
                    if ( Warecorp::is('Brandgallery', 'Group') )    $output[] = Warecorp::t('Web Badges');
                    if ( Warecorp::is('Invitations', 'Group') )     $output[] = Warecorp::t('Invitations');
                    if ( Warecorp::is('Avatars', 'Group') )         $output[] = Warecorp::t('Group Photo');
                }
                break;

			case 'search' :
				if ( in_array(Warecorp::$actionName, array('index', 'search')) )     $output[] = Warecorp::t('All Results');
				if ( Warecorp::$actionName == 'groups' )     	$output[] = Warecorp::t('Search Groups');
				if ( Warecorp::$actionName == 'members' )     	$output[] = Warecorp::t('Search Members');
				if ( Warecorp::$actionName == 'photos' )     	$output[] = Warecorp::t('Search Photos');
				if ( Warecorp::$actionName == 'videos' )     	$output[] = Warecorp::t('Search Videos');
				if ( Warecorp::$actionName == 'discussions' )   $output[] = Warecorp::t('Search Discussions');
				if ( Warecorp::$actionName == 'events' )     	$output[] = Warecorp::t('Search Events');
				if ( Warecorp::$actionName == 'lists' )     	$output[] = Warecorp::t('Search Lists');
				if ( Warecorp::$actionName == 'documents' )     	$output[] = Warecorp::t('Search Documents');

				break;
			case 'registration' :
			    if ( Warecorp::$actionName == 'index' ) {
    			    if ( FACEBOOK_USED ) {
    			        $facebookId = null;
    			        $facebookId = Warecorp_Facebook_Api::getFacebookId();
    			        if ( !empty($facebookId) && 'facebook' == $objRequest->getParam('mode', null) ) {
    			            $output[] = Warecorp::t('Sign Up with Your Facebook Account');
    			        } else {
    			            $output[] = Warecorp::t('Sign Up');
    			        }
    			    } else {
                        $output[] = Warecorp::t('Sign Up');
    			    }
    			    break;
			    }
        }
		if (count($output) > 0){
		        $output = join($delim, $output);
				$output = "<div class='prContentHeadline'><div class='prLayout-inner'><h1 class='prNoInner'>".$output."</h1></div></div>";
		}
		else{
			$output = "<div class='prContentNoHeadline'></div>";
		}
		
		//$output = join($delim, $output);
        return $output;
    }
