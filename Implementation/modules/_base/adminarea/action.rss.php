<?php
    Warecorp::addTranslation("/modules/adminarea/action.rss.php.xml");

   $form = new Warecorp_Form('udForm', 'POST', $this->admin->getAdminPath('rss'));
    if ($form->isPostback()) {    
        $form->addRule('descMaxLength',     'required',  Warecorp::t('Enter please length of description'));
        $form->addRule('limit',                 'required',  Warecorp::t('Enter please count of items for RSS feed'));
        if ($form->validate($this->params)){ 
              Z1SKY_Feed::setFeedItemCount($this->params['limit']);
              Z1SKY_Feed::setFeedDescritionLength($this->params['descMaxLength']);   
        }
        $this->view->limit = $this->params['limit'];
        $this->view->descMaxLength = $this->params['descMaxLength'];
   }
   else {
       $this->view->limit = Z1SKY_Feed::getFeedItemCount();
       $this->view->descMaxLength = Z1SKY_Feed::getFeedDescritionLength();
   }
   $this->view->form = $form; 
   
   $this->view->bodyContent = 'adminarea/rss.tpl';
   
