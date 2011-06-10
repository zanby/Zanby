<?php
    function smarty_function_mainnav($params, &$smarty)
    {
        Warecorp::addTranslation("/plugins/function.mainnav.php.xml");
        /**
         * Use global navigation if implementation is ESA
         */
    	if ( IMPLEMENTATION_TYPE == 'ESA' ) {
    		/**
    		 * 
    		 */
	        $currentUser    = $smarty->_tpl_vars['currentUser'];
	        $user           = $smarty->_tpl_vars['user'];
	        $bodyContent    = $smarty->_tpl_vars['bodyContent'];
	        $ACTION_NAME    = strtolower($smarty->_tpl_vars['ACTION_NAME']);
	        $MOD_NAME       = $smarty->_tpl_vars['MOD_NAME'];
	        
	        $pageHome      = (boolean) ( $bodyContent == "index/index.tpl" || $bodyContent == "index/index_anonymous.tpl" );
	        $pageGroups    = (boolean) ( $MOD_NAME == "groups" || $bodyContent == 'users/familylanding.tpl');
	        $pageUser      = (boolean) ( $MOD_NAME == "users" && ($currentUser->getId() != $user->getId()) && $bodyContent != 'users/familylanding.tpl');
	        $pageSupport   = (boolean) ( $MOD_NAME == "info" && $ACTION_NAME == "support" );
	        
	        $out = '<ul class="znMainMenu">';
	        //  Home
	        if ( $pageHome ) {
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/"><span>'.Warecorp::t('Home').'</span></a></li>';
	        } else {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/"><span>'.Warecorp::t('Home').'</span></a></li>';        
	        }
	        //  Groups
	        if ( $pageGroups ) {
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/index/"><span>'.Warecorp::t('Groups').'</span></a></li>';        
	        } elseif ( $pageHome ) {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/index/"><span>'.Warecorp::t('Groups').'</span></a></li>';        
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/index/"><span>'.Warecorp::t('Groups').'</span></a></li>';        
	        }
	        //  Users
	        if ( $pageUser ) {
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/"><span>'.Warecorp::t('Members').'</span></a></li>';              
	        } elseif ( $pageGroups ) {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/"><span>'.Warecorp::t('Members').'</span></a></li>';        
	        } else {
                $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/"><span>'.Warecorp::t('Members').'</span></a></li>';
	        }
	        //  Support        
	        if ( $pageSupport ) {
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/support/"><span>'.Warecorp::t('Support').'</span></a></li>';                
	        } elseif ( $pageUser ) {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/support/"><span>'.Warecorp::t('Support').'</span></a></li>';        
	        } else {
	        	$out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/support/"><span>'.Warecorp::t('Support').'</span></a></li>';
	        }
	        $out .= '</ul>';
	        return $out;
    	} 
        /**
         * Use group menu if implementation is 2a-b
         */    	
    	else {
	    	/**
	    	 * 
	    	 */
	        $currentUser    = $smarty->_tpl_vars['currentUser'];
	        $user           = $smarty->_tpl_vars['user'];
	        $bodyContent    = $smarty->_tpl_vars['bodyContent'];
	        $ACTION_NAME    = strtolower($smarty->_tpl_vars['ACTION_NAME']);
	        $MOD_NAME       = $smarty->_tpl_vars['MOD_NAME'];
	        
	        $leftMenuActions = array(
	            "Events"      =>array('calendar.list.view','calendar.month.view', 'calendar.hierarchy.view', 'calendar.member.view', 'calendar.event.view','calendar.event.create','calendar.event.edit','calendar.event.copy.do','calendar.action.confirm','calendar.event.apply.request',                   'calendar','calendarview','calendarviewevent','calendarexpired','calendaradd','calendaredit','calendarical','calendarconfirm','calendarsearch','calendarsearchindex', 'calendar.member.view', 'calendar.hierarchy.view'),
	            "Videos"      =>array('videos','videogallery','videogallerycreate','videogalleryedit','videogalleryview', 'videossearch'),
	            "Photos"      =>array('photos','gallery','gallerycreate','galleryedit','galleryview', 'photossearch'),
	            "Lists"       =>array('lists','listsdelete','listsedit','listsadd','listsview','listssearch'),
	            "Discussions" =>array('discussion', 'discussionsettings', 'discussionhostsettings', 'topic', 'replytopic', 'discussionsearch', 'recenttopic'),
	            "Documents"   =>array('documents'),
	            "Blog"        =>array('blog', 'blog.details', 'blog.create', 'blog.edit'),
	            "Tools"       =>array('settings')
	        );
	        
	        $isGlobalGroup = false;
	        $globalGroup = null;
	        if ( Zend_Registry::isRegistered('globalGroup') ) {
	            $globalGroup = Zend_Registry::get('globalGroup');
	            if ( !isset($smarty->_tpl_vars['CurrentGroup']) || (isset($smarty->_tpl_vars['CurrentGroup']) && $smarty->_tpl_vars['CurrentGroup']->getId() == $globalGroup->getId() ) ) {
	                $isGlobalGroup = true;
	            }
	        }
	        
	        $pageHome           = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'index')           || ($MOD_NAME == "groups" && $ACTION_NAME == 'summary')));
	        $pageGroups         = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'groups')          || ($MOD_NAME == "groups" && $ACTION_NAME == 'members')));
	        $pageEvents         = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'events')          || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Events']))));
	        $pageVideo          = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'videos')          || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Videos']))));
	        $pagePhotos         = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'photos')          || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Photos']))));
	        $pageLists          = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'lists')           || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Lists']))));
	        $pageDiscussions    = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'discussion')      || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Discussions']))));
	        $pageBlog           = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'blog')            || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Blog']))));
	        $pageDocuments      = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'documents')       || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Documents']))));
	        $pageTools          = (boolean) ($isGlobalGroup && (($MOD_NAME == "index" && $ACTION_NAME == 'settings')        || ($MOD_NAME == "groups" &&  in_array($ACTION_NAME, $leftMenuActions['Tools']))));
	        		
	        $out = '<ul class="znMainMenu">';
	        //  Home
	        if ( $pageHome ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/"><span>Home</span></a></li>';
	        } else {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/"><span>Home</span></a></li>';
	        }
	        //  News
	        if ( $pageGroups ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/groups/"><span>Groups</span></a></li>';
	        } elseif ( $pageHome ) {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/groups/"><span>Groups</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/groups/"><span>Groups</span></a></li>';
	        }
	        //  Events
            if ( $pageEvents ) { 
                $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/events/"><span>Events</span></a></li>';
            } elseif ( $pageGroups ) { 
                $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/events/"><span>Events</span></a></li>';
            } else {
                $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/events/"><span>Events</span></a></li>';
            }
	        //  Video
	        if ( $pageVideo ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/videos/"><span>Videos</span></a></li>';
	        } elseif ( $pageEvents ) { 
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/videos/"><span>Videos</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/videos/"><span>Videos</span></a></li>';
	        }
	        //  Photos
	        if ( $pagePhotos ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/photos/"><span>Photos</span></a></li>';
	        } elseif ( $pageVideo ) { 
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/photos/"><span>Photos</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/photos/"><span>Photos</span></a></li>';
	        }
	        //  Lists
	        if ( $pageLists ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/lists/"><span>Lists</span></a></li>';
	        } elseif ( $pagePhotos ) { 
	            $out .= '<li  class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/lists/"><span>Lists</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/lists/"><span>Lists</span></a></li>';
	        }
	        //  Discussions
	        if ( $pageDiscussions ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/discussion/"><span>Discussions</span></a></li>';
	        } elseif ( $pageLists ) { 
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/discussion/"><span>Discussions</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/discussion/"><span>Discussions</span></a></li>';
	        }
	        //  Blog
	        if ( $pageBlog ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/blog/"><span>Blog</span></a></li>';
	        } elseif ( $pageDiscussions ) { 
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/blog/"><span>Blog</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/blog/"><span>Blog</span></a></li>';
	        }
	        //  Documents
	        if ( $pageDocuments ) { 
	            $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/documents/"><span>Documents</span></a></li>';
	        } elseif ( $pageBlog ) {
	            $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/documents/"><span>Documents</span></a></li>';
	        } else {
	            $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/documents/"><span>Documents</span></a></li>';
	        }
	        
	        if ( $globalGroup ) {
	            if ( $globalGroup->getMembers()->isHost($user->getId()) || $globalGroup->getMembers()->isCohost($user->getId()) ) {
	                //  Tools
	                if ( $pageTools ) { 
	                    $out .= '<li class="znCurrent"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/settings/"><span>Tools</span></a></li>';
	                } elseif ( $pageDocuments ) {
	                    $out .= '<li class="znNoBorder"><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/settings/"><span>Tools</span></a></li>';
	                } else {
	                    $out .= '<li><a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/settings/"><span>Tools</span></a></li>';
	                }
	            }
	        }
	
	        $out .= '</ul>';
	        return $out;
    	}
    }
?>
