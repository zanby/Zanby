<?php

    Warecorp::addTranslation('/modules/tags/action.index.php.xml');
    
    //  @todo в шаблоне tags/index.tpl надо сделать для листов и событий, на данный момент они не работают

    if ( isset($this->params['tag']) && is_numeric($this->params['tag']) ) {
        $tag = new Warecorp_Data_Tag($this->params['tag']);
        if ( $tag->id === null ) {
            $this->_redirect('/');
        }
        $entities = $tag->getTagEntitiesAsObj();
        foreach ( $entities as $entity ) {
            if ( $entity instanceof Warecorp_User )         $all_entities['members']['items'][] = $entity;
            if ( $entity instanceof Warecorp_Group_Simple )        $all_entities['groups']['items'][] = $entity;
            if ( $entity instanceof Warecorp_Document_Item ) {
                if ( $entity->ownerType == 'user' ) $all_entities['members']['documents'][] = $entity;
                else $all_entities['groups']['documents'][] = $entity;
            }
            if ( $entity instanceof Warecorp_Photo_Item  ) {
                if ( $entity->getGallery()->ownerType == 'user' ) $all_entities['members']['photos'][] = $entity;
                else $all_entities['groups']['photos'][] = $entity;
            }
            if ( $entity instanceof Warecorp_List_Item ) {
                if ( $entity->ownerType == 'user' ) $all_entities['members']['lists'][] = $entity;
                else $all_entities['groups']['lists'][] = $entity;
            }
        }
        $this->view->tag = $tag;
    }


    $this->_page->setTitle(Warecorp::t('Tags'));
    $this->view->entities = $all_entities;
    $this->view->bodyContent = 'tags/index.tpl';
