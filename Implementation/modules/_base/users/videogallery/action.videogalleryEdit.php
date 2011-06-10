<?php
    Warecorp::addTranslation( "/modules/users/videogallery/action.videogalleryEdit.php.xml" );
    
    $this->_page->Xajax->registerUriFunction( "edit_photo", "/users/videogalleryEditGallVideo/" );
    $this->_page->Xajax->registerUriFunction( "edit_photo_do", "/users/videogalleryEditGallVideoDo/" );
    $this->_page->Xajax->registerUriFunction( "cancel_edit_photo", "/users/videogalleryCancelEditGallVideo/" );
    $this->_page->Xajax->registerUriFunction( "delete_photo", "/users/videogalleryDeleteGallVideoDo/" );
    $this->_page->Xajax->registerUriFunction( "upload_photo", "/users/videogalleryUploadVideo/" );
    $this->_page->Xajax->registerUriFunction( "upload_photo_do", "/users/videogalleryUploadVideoDo/" );
    $this->_page->Xajax->registerUriFunction( "share_group", "/users/videogalleryShareGroup/" );
    $this->_page->Xajax->registerUriFunction( "share_group_do", "/users/videogalleryShareGroupDo/" );
    $this->_page->Xajax->registerUriFunction( "share_friend", "/users/videogalleryShareFriend/" );
    $this->_page->Xajax->registerUriFunction( "share_friend_do", "/users/videogalleryShareFriendDo/" );
    $this->_page->Xajax->registerUriFunction( "show_share_history", "/users/videogalleryShowShareHistory/" );
    $this->_page->Xajax->registerUriFunction( "unshare_group_do", "/users/videogalleryUnShareGroupDo/" );
    $this->_page->Xajax->registerUriFunction( "unshare_friend_do", "/users/videogalleryUnShareFriendDo/" );
    $this->_page->Xajax->registerUriFunction( "image_rotate", "/users/imageRotate/" );
    $this->_page->Xajax->registerUriFunction( "editshowpage", "/users/videoeditshowpage/" );
    
    if ( SINGLEVIDEOMODE ) {
        $form = new Warecorp_Form( 'editPhotoForm', 'post', $this->currentUser->getUserPath( 'videogalleryEditGallVideoDo' ) );
        $this->view->form = $form ;
    }
    $gallery_id = isset( $this->params['gallery'] ) ? floor( $this->params['gallery'] ) : 0;
    $action = isset( $this->params['faction'] ) ? $this->params['faction'] : "";
    $items_per_page = 10;
    $page = empty( $this->params['page'] ) ? 1 : floor( $this->params['page'] );
    $paging_url = '#null';
    
    if ( $gallery_id == 0 || !Warecorp_Video_Gallery_Abstract::isGalleryExists( $gallery_id ) ) {
        $this->_page->showAjaxAlert( Warecorp::t('Incorrect Gallery') );
        $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
    }
    
    $gallery = Warecorp_Video_Gallery_Factory::loadById( $gallery_id );
    
    if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery( $gallery, $this->currentUser, $this->_page->_user ) ) {
        $this->_page->showAjaxAlert( Warecorp::t('Access Denied' ) );
        $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
    }
    
    $gEditForm = new Warecorp_Form( 'galleryEditForm', 'post', $this->currentUser->getUserPath( 'videogalleryedit/gallery' ) . $gallery->getId() . '/' );
    $gEditForm->addRule( 'title', 'required', Warecorp::t('Enter please gallery title' ) );
    
    $videosListObj = $gallery->getVideos()->setCurrentPage( $page )->setListSize( $items_per_page );
    $videosList = $videosListObj->getList();
    
    if ( SINGLEVIDEOMODE ) {
        if ( !isset( $videosList[0] ) )
            $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
        if ( $videosList[0]->getSource() == 'nonvideo' ) {
            if ( !defined( 'ALLOW_EDIT_NONVIDEO_VIDEO' ) ) {
                $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
            } elseif ( ALLOW_EDIT_NONVIDEO_VIDEO !== 1 ) {
                $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
            }
        }
    } else {
        if ( count( $videosList ) == 1 && $videosList[0]->getSource() == 'nonvideo' ) {
            $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
        }
    }
    $P = new Warecorp_Common_PagingProduct( $videosListObj->getCount(), $items_per_page, $paging_url );
    $this->view->infoPaging = $P->makeInfoPaging( $page ) ;
    $this->view->paging = $P->makeAjaxLinkPaging( $page, "xajax_editshowpage('", "', '" . $gallery_id . "'); return false;" ) ;
    
    if ( $action != "save" ) {
        $this->view->gEditForm = $gEditForm ;
        $this->view->SWFUploadID = session_id() ;
        $this->view->gallery_id = $gallery_id ;
        $this->view->gallery = $gallery ;
        $this->view->videoslist = $videosList ;
        $this->view->page = $page ;
        $this->view->expand = isset( $this->params['expand'] ) ? $this->params['expand'] : null ;
        $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance() ;
        $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create() ;
        $this->view->bodyContent = 'users/videogallery/' . VIDEOMODEFOLDER . 'edit.tpl' ;
    } else {
        /**
         * remove gallery and all photos + sharing
         */
        $remove = (isset( $this->params["remove"] ) && $this->params["remove"] == "1") ? 1 : 0;
        if ( $remove == 1 ) {
            $gallery->delete();
            $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
        } 
        /**
         * update gallery and photos
         */
        else {
            if ( $gEditForm->validate( $this->params ) ) {
                $gallery->setPrivate( (isset( $this->params["isPrivate"] ) && $this->params["isPrivate"] == 0) ? 0 : 1 );
                $gallery->setTitle( $this->params["title"] );
                $gallery->save();
                $this->_redirect( $this->currentUser->getUserPath( 'videos' ) );
            } else {
                //$photosList = $gallery->getPhotos()->getList();
                $this->view->gEditForm = $gEditForm ;
                $this->view->SWFUploadID = session_id() ;
                $this->view->gallery_id = $gallery_id ;
                $this->view->gallery = $gallery ;
                $this->view->page = $page ;
                $this->view->expand = isset( $this->params['expand'] ) ? $this->params['expand'] : null ;
                $this->view->videosList = $videosList ;
                $this->view->bodyContent = 'users/videogallery/' . VIDEOMODEFOLDER . 'edit.tpl' ;
            }
        }
    }
