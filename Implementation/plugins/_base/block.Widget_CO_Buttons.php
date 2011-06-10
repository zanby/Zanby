<?php
function smarty_block_Widget_CO_Buttons($params, $content, &$smarty)
{
    /**
     * End tag
     */
	$theme = Zend_Registry::get('AppTheme');
	$Warecorp = new Warecorp();
	
    if ( $content !== null ) {//print_r($smarty);
        $output = '';
        
        switch ($smarty->_tpl_vars['MOD_ACTION_NAME']) {
        	
        	/*USER*/
        	
        	case 'users_compose':
        		
        		$objUser = Zend_Registry::get('User');
        		$output = <<<EOD
        		
        		<div class="prIndentTop">
					<p><em><span class="prMarkRequired">*</span> {$Warecorp->t('Drag and drop objects to arrange layout')}</em></p>
					<p><em><span class="prMarkRequired">*</span> {$Warecorp->t('Click')}<img class="prIndentRightSmall prIndentLeftSmall" src="{$theme->images}/buttons/edit.gif" title="" align="absmiddle" />{$Warecorp->t('icon to edit')}</em></p>
					
					<h3>{$Warecorp->t('Customizable Content')}</h3>
					<a class="prBlock" id="ddContentBlock" href="#null" title="{$Warecorp->t('Content Block')}" alt="{$Warecorp->t('Content Block')}"><img src="{$theme->images}/buttons/btnContentBlock.gif" title="" /></a>
					<a class="prBlock" id="ddImage" href="#null" title="{$Warecorp->t('Content Block')}" alt="{$Warecorp->t('Content Block')}"><img src="{$theme->images}/buttons/btnPicture.gif" title="" /></a>
		                                             
					<h3>{$Warecorp->t('Profile Information')}</h3>
					<a class="prBlock" id="ddPicture" href="#null" title="{$Warecorp->t('Profile Photo')}" alt="{$Warecorp->t('Content Block')}"><img src="{$theme->images}/buttons/btnAvatar.gif" title="" /></a>
					<a class="prBlock" id="ddProfileDetails" href="#null" title="{$Warecorp->t('Profile Details')}" alt="{$Warecorp->t('Content Block')}"><img src="{$theme->images}/buttons/btnProfileDetails.gif" title="" /></a> 
					
				    <h3>{$Warecorp->t('Other Content')}</h3> 
					<a class="prBlock" id="ddMyPhotos" href="#null" title="{$Warecorp->t('My Photos')}" alt="{$Warecorp->t('My Photos')}"><img src="{$theme->images}/buttons/btnMyPhotos.gif" title="" /></a>
					<a class="prBlock" id="ddMyLists" href="#null" title="{$Warecorp->t('My Lists')}" alt="{$Warecorp->t('My Lists')}"><img src="{$theme->images}/buttons/btnMyLists.gif" title="" /></a>
					<a class="prBlock" id="ddMyDocuments" href="#null" title="{$Warecorp->t('My Documents')}" alt="{$Warecorp->t('My Documents')}"><img src="{$theme->images}/buttons/btnMyDocuments.gif" title="" /></a>
					<a class="prBlock" id="ddMyGroups" href="#null" title="{$Warecorp->t('My Groups')}" alt="{$Warecorp->t('My Groups')}"><img src="{$theme->images}/buttons/btnMyGroups.gif" title="" /></a>
					<a class="prBlock" id="ddMyFriends" href="#null" title="{$Warecorp->t('Friends')}" alt="{$Warecorp->t('Friends')}"><img src="{$theme->images}/buttons/btnFriends.gif" title="" /></a>
					<a class="prBlock" id="ddMyDiscussions" href="#null" title="{$Warecorp->t('My Discussions')}" alt="{$Warecorp->t('My Discussions')}"><img src="{$theme->images}/buttons/btnMyDiscussions.gif" title="" /></a>
					<a class="prBlock" id="ddMyEvents" href="#null" title="{$Warecorp->t('Events')}" alt="{$Warecorp->t('Events')}"><img src="{$theme->images}/buttons/btnEvents.gif" title="" /></a>
					<a class="prBlock" id="ddRSSFeed" href="#null" title="{$Warecorp->t('RSS Feed')}" alt="{$Warecorp->t('RSS Feed')}"><img src="{$theme->images}/buttons/btnRssFeed.gif" title="" /></a>
		            <a class="prBlock" id="ddScript" href="#null" title="{$Warecorp->t('Script')}" alt="{$Warecorp->t('Script')}"><img src="{$theme->images}/buttons/btnScript.gif" title="" /></a> 
				</div>
EOD;
				//<a class="prBlock" id="ddMogulus" href="#null" title="Mogulus" alt="Mogulus"><img src="{$theme->images}/buttons/btnMyPhotos.gif" title="" /></a>
		        //<a class="prBlock" id="ddIframe" href="#null" title="Iframe" alt="Iframe"><img src="{$theme->images}/buttons/btnMyPhotos.gif" title="" /></a>
		            
				break;

        	case 'groups_edit':
        		
        		/*FAMILY*/
        		$objGroup = $smarty->_tpl_vars['CurrentGroup'];
        		if ($objGroup->getGroupType() == 'family') {
        			
        			$mapAddon = ( in_array(HTTP_CONTEXT, array('z1sky', 'cpp', 'zftn', 'zea')) )?('<a class="prBlock" id="ddFamilyWidgetMap" href="#null" title="Family Map" alt="Family Map"><img src="'.$theme->images.'/buttons/btnFamilyMap.gif" title="" /></a>'):'';
        			
        			$output = <<<EOD
        		
	        		<div class="prIndentTop">
						<p><em><span class="prMarkRequired">*</span> {$Warecorp->t('Drag and drop objects to arrange layout')}</em></p>
						<p><em><span class="prMarkRequired">*</span> {$Warecorp->t('Click')}<img class="prIndentRightSmall prIndentLeftSmall" src="{$theme->images}/buttons/edit.gif" title="" />{$Warecorp->t('icon to edit')}</em></p>
						
						<h3>{$Warecorp->t('Customizable Content')}</h3>
						<a class="prBlock" id="ddContentBlock" href="#null" title="{$Warecorp->t('Content Block')}" alt="{$Warecorp->t('Content Block')}"><img src="{$theme->images}/buttons/btnContentBlock.gif" title="" /></a>
			            <a class="prBlock" id="ddGroupImage" href="#null" title="{$Warecorp->t('Picture')}" alt="{$Warecorp->t('Picture')}"><img src="{$theme->images}/buttons/btnPicture.gif" title="" /></a>
			            <a class="prBlock" id="ddFamilyIcons" href="#null" title="{$Warecorp->t('Family Icons')}" alt="{$Warecorp->t('Family Icons')}"><img src="{$theme->images}/buttons/btnFamilyIcons.gif" title="" /></a>
						
						<h3>{$Warecorp->t('Group Information')}</h3>
						<a class="prBlock" id="ddFamilyAvatar" href="#null" title="{$Warecorp->t('Family Profile Photo')}" alt="{$Warecorp->t('Family Profile Photo')}"><img src="{$theme->images}/buttons/btnFamilyAvatar.gif" title="" /></a>
			
						<h3>{$Warecorp->t('Other Content')}</h3>
						<a class="prBlock" id="ddGroupPhotos" href="#null" title="{$Warecorp->t('Family Photo Galleries')}" alt="{$Warecorp->t('Family Photo Galleries')}"><img src="{$theme->images}/buttons/btnPhotoGalleries.gif" title="" /></a>
			                
						<a class="prBlock" id="ddFamilyVideoContentBlock" href="#null" title="{$Warecorp->t('Single Video')}" alt="{$Warecorp->t('Single Video')}"><img src="{$theme->images}/buttons/Single-Story.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyTopVideos" href="#null" title="{$Warecorp->t('Top Videos')}" alt="{$Warecorp->t('Top Videos')}"><img src="{$theme->images}/buttons/Top-Stories.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyPeople" href="#null" title="{$Warecorp->t('Family People')}" alt="{$Warecorp->t('Family People')}"><img src="{$theme->images}/buttons/btnFamilyPeople.gif" title="" /></a>
			            
			            <a class="prBlock" id="ddMogulus" href="#null" title="{$Warecorp->t('LiveStream Video')}" alt="{$Warecorp->t('LiveStream Video')}"><img src="{$theme->images}/buttons/btnEmbeddedVideo.gif" title="" /></a>
			            
			            <a class="prBlock" id="ddScript" href="#null" title="{$Warecorp->t('Script')}" alt="{$Warecorp->t('Script')}"><img src="{$theme->images}/buttons/btnScript.gif" title="" /></a>
						
						
						<a class="prBlock" id="ddFamilyLists" href="#null" title="{$Warecorp->t('Family Lists')}" alt="{$Warecorp->t('Family Lists')}"><img src="{$theme->images}/buttons/btnFamilyLists.gif" title="" /></a>
						<a class="prBlock" id="ddGroupDocuments" href="#null" title="{$Warecorp->t('Family Documents')}" alt="{$Warecorp->t('Family Documents')}"><img src="{$theme->images}/buttons/btnFamilyDocuments.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyMemberIndex" href="#null" title="{$Warecorp->t('Member Index')}" alt="{$Warecorp->t('Member Index')}"><img src="{$theme->images}/buttons/btnMemberIndex.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyEvents" href="#null" title="{$Warecorp->t('Events')}" alt="{$Warecorp->t('Events')}"><img src="{$theme->images}/buttons/btnFamilyEvents.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyDiscussions" href="#null" title="{$Warecorp->t('Group Discussions')}" alt="{$Warecorp->t('Group Discussions')}"><img src="{$theme->images}/buttons/btnFamilyDiscussions.gif" title="" /></a>
						
						<a class="prBlock" id="ddRSSFeed" href="#null" title="{$Warecorp->t('RSS Feed')}" alt="{$Warecorp->t('RSS Feed')}"><img src="{$theme->images}/buttons/btnRssFeed.gif" title="" /></a>
						
						{$mapAddon}
						
					</div>
EOD;
						//<a class="prBlock" id="ddIframe" href="#null" title="Iframe" alt="Iframe"><img src="{$theme->images}/buttons/btnIFrame.gif" title="" /></a>

        		} else {
        			
				/*PREMIUM GROUP*/	
        		
        		$mapAddon = ( in_array(HTTP_CONTEXT, array('z1sky', 'cpp', 'zftn', 'zea')) )?('<a class="prBlock" id="ddGroupWidgetMap" href="#null" title="Group Map" alt="Group Map"><img src="'.$theme->images.'/buttons/btnGroupMap.gif" title="" /></a>'):'';
        		
        		$output = <<<EOD
        			<div class="prIndentTop">
						<p><em><span class="prMarkRequired">*</span> {$Warecorp->t('Drag and drop objects to arrange layout')}</em></p>
						<p><em><span class="prMarkRequired">*</span> {$Warecorp->t('Click')}<img class="prIndentRightSmall prIndentLeftSmall" src="{$theme->images}/buttons/edit.gif" title="" />{$Warecorp->t('icon to edit')}</em></p>
						
						<h3>{$Warecorp->t('Customizable Content')}</h3>
						<a class="prBlock" id="ddContentBlock" href="#null" title="{$Warecorp->t('Content Block')}" alt="{$Warecorp->t('Content Block')}"><img src="{$theme->images}/buttons/btnContentBlock.gif" title="" /></a>
						<a class="prBlock" id="ddGroupImage" href="#null" title="{$Warecorp->t('Picture')}" alt="{$Warecorp->t('Picture')}"><img src="{$theme->images}/buttons/btnPicture.gif" title="" /></a>
						                                             
						<h3>{$Warecorp->t('Group Information')}</h3>
						<a class="prBlock" id="ddGroupAvatar" href="#null" title="{$Warecorp->t('Group Profile Photo')}" alt="{$Warecorp->t('Group Profile Photo')}"><img src="{$theme->images}/buttons/btnGroupAvatar.gif" title="" /></a>
				
EOD;
				if ($objGroup->getFamilyGroups()->getCount()) {

				$output.= <<<EOD
					<a class="prBlock" id="ddGroupFamilyIcons" href="#null" title="{$Warecorp->t('Family Icons')}" alt="{$Warecorp->t('Family Icons')}"><img src="{$theme->images}/buttons/btnFamilyIcons.gif" title="" /></a>
EOD;
				}
							
				
				$output.= <<<EOD
						<h3>{$Warecorp->t('Other Content')}</h3>
						<a class="prBlock" id="ddGroupPhotos" href="#null" title="{$Warecorp->t('Group Photo Galleries')}" alt="{$Warecorp->t('Group Photo Galleries')}"><img src="{$theme->images}/buttons/btnPhotoGalleries.gif" title="" /></a>
			            
			            
			            <a class="prBlock" id="ddFamilyVideoContentBlock" href="#null" title="{$Warecorp->t('Single Video')}" alt="{$Warecorp->t('Single Video')}"><img src="{$theme->images}/buttons/Single-Story.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyTopVideos" href="#null" title="{$Warecorp->t('Top Videos')}" alt="{$Warecorp->t('Top Videos')}"><img src="{$theme->images}/buttons/Top-Stories.gif" title="" /></a>
						 
			            <a class="prBlock" id="ddMogulus" href="#null" title="{$Warecorp->t('LiveStream Video')}" alt="{$Warecorp->t('LiveStream Video')}"><img src="{$theme->images}/buttons/btnEmbeddedVideo.gif" title="" /></a>
			            
			            <a class="prBlock" id="ddScript" href="#null" title="{$Warecorp->t('Script')}" alt="{$Warecorp->t('Script')}"><img src="{$theme->images}/buttons/btnScript.gif" title="" /></a>
			            
			                
						<a class="prBlock" id="ddGroupLists" href="#null" title="{$Warecorp->t('Group Lists')}" alt="{$Warecorp->t('Group Lists')}"><img src="{$theme->images}/buttons/btnGroupLists.gif" title="" /></a>
						
						<a class="prBlock" id="ddGroupDocuments" href="#null" title="{$Warecorp->t('Group Documents')}" alt="{$Warecorp->t('Group Documents')}"><img src="{$theme->images}/buttons/btnGroupDocuments.gif" title="" /></a>
						
						<a class="prBlock" id="ddGroupMembers" href="#null" title="{$Warecorp->t('Group Members')}" alt="{$Warecorp->t('Group Members')}"><img src="{$theme->images}/buttons/btnGroupMembers.gif" title="" /></a>
						<a class="prBlock" id="ddGroupEvents" href="#null" title="{$Warecorp->t('Events')}" alt="{$Warecorp->t('Events')}"><img src="{$theme->images}/buttons/btnGroupEvents.gif" title="" /></a>
						<a class="prBlock" id="ddFamilyDiscussions" href="#null" title="{$Warecorp->t('Group Discussions')}" alt="{$Warecorp->t('Group Discussions')}"><img src="{$theme->images}/buttons/btnGroupDiscussions.gif" title="" /></a>         
						<a class="prBlock" id="ddRSSFeed" href="#null" title="{$Warecorp->t('RSS Feed')}" alt="{$Warecorp->t('RSS Feed')}"><img src="{$theme->images}/buttons/btnRssFeed.gif" title="" /></a>
						
						{$mapAddon}
						
					</div>	
EOD;
					//<a class="prBlock" id="ddIframe" href="#null" title="Iframe" alt="Iframe"><img src="{$theme->images}/buttons/btnIFrame.gif" title="" /></a>

        		}
        		
        		break;
        	
        }
        
        return $output.$content;
    }    
}