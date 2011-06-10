<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

/**
 * Warecorp FRAMEWORK
 * @package Warecorp_Photo
 * @author Artem Sukharev
 * @version 1.0
 */
class BaseWarecorp_Photo_AccessManager
{
	static protected $instance;
    static protected $publishAllowedGroupsFrom;     // ids of groups from
    
	protected function __construct()
	{
	}

    public function  __clone() {
        trigger_error("Class is Singleton, use static method getInctance()", E_USER_ERROR);
    }

    static public function getPublishAllowedGroupsFrom()
    {
        if ( null === self::$publishAllowedGroupsFrom ) {
            self::$publishAllowedGroupsFrom = array();
            try {
                $objGroup = Warecorp_Group_Factory::loadByGroupUID('theuptake-news-team',Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                if ( null !== $objGroup->getId() ) self::$publishAllowedGroupsFrom[] = $objGroup->getId();
                unset($objGroup);
            } catch (Exception $e) { /* do nothing */ }
        }
        return self::$publishAllowedGroupsFrom;
    }

    /**
     * @param string|null $context
     */
	static public function getInctance( $context = null )
	{
		if ( self::$instance == null ) 
            self::$instance = Warecorp_Photo_AccessManager_Factory::create($context);
		return self::$instance;
	}

    /*
     +-----------------------------------
     |
     | Users or Groups Galleries Methods
     |
     +-----------------------------------
    */

    /**
     * check if user can view galleries
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
	static public function canViewGalleries($context, $user)
    {
    	self::_checkContext($context);
    	self::_checkUser($user);

        if ( $context instanceof Warecorp_User ) {
        	if ( $context->getId() == $user->getId() ) {
        	   return true;
        	}else {
                return Warecorp_User_AccessManager::getInstance()->canViewPhotos($context, $user);
        	}
        }elseif ( $context instanceof Warecorp_Group_Base ) {
        	if ($context->getGroupType() == 'family') return true;
    		if ( $context->getIsPrivate()) {
    			if (!$context->getMembers()->isMemberExistsAndApproved($user->getId())) return false;
                else return true;
    		} else return true;
        }
    	return false;
    }

    /**
     * check if user can view public galleries
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewPublicGalleries($context, $user)
    {
        self::_checkContext($context);
        self::_checkUser($user);
        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * user views own galleries
             */
            if ( $context->getId() == $user->getId() ) {
                return true;
            }
            /**
             * user views galleries of other user
             */
            else {
                return true;
            }
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * FIXME
             */
        	return true;
        }
        return false;
    }

    /**
     * check if user can view private galleries
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewPrivateGalleries($context, $user)
    {
        self::_checkContext($context);
        self::_checkUser($user);
        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * user views own galleries
             */
            if ( $context->getId() == $user->getId() ) {
                return true;
            }
            /**
             * user views galleries of other user
             */
            else {
                return false;
            }
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {

        	if ($context->getMembers()->isMemberExistsAndApproved($user->getId())) return true;

        }
        return false;     }

    /**
     * check if user can create new gallery or upload new photos
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canCreateGallery($context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * user views own galleries
             */
            if ( $context->getId() == $user->getId() ) {
                return true;
            }
            /**
             * user views galleries of other user
             */
            else {
                return false;
            }
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * user is host of this group
             */
            if ($context->getMembers()->isHost($user->getId())) {
                return true;
            }
            /**
             * user is cohost of this group
             */
            elseif ($context->getMembers()->isCohost($user->getId())) {
                return true;
            }
            /**
             * user is member of this group
             */
            elseif ($context->getMembers()->isMemberExistsAndApproved($user->getId())) {
            	if (Warecorp_Group_AccessManager::canUsePhotos($context, $user)) return true;
            		else return false;
            }
            /**
             * user isn't member of this group
             */
            else {

            }
        }
        return false;
    }

    /**
     * check if user can upload new photos
     * @see Warecorp_Photo_AccessManager::canCreateGallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canUploadPhotos($context, $user)
    {
        return self::canCreateGallery($context, $user);
    }

    /*
     +-----------------------------------
     |
     |  Certain Gallery Methods
     |
     +-----------------------------------
    */

    /**
     * check if user can view gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewGallery($gallery, $context, $user)
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        if ( $context instanceof Warecorp_User ) {
            if ( $context->getId() == $user->getId() ) {
                return true;
            }else{
            	if ( $gallery->getPrivate() || $gallery->isShared($context)) {
                    return false;
            	}else{
                    if ($gallery->getOwnerId() != $context->getId() && $gallery->getOwnerId() != $user->getId() && !$gallery->isShared($context))
                    	return false;
                    return Warecorp_User_AccessManager::getInstance()->canViewPhotos($context, $user);
            	}
            }
        }elseif ( $context instanceof Warecorp_Group_Base ) {
			if ($context->getMembers()->isMemberExistsAndApproved($user->getId())) {
				return true;
            } elseif( $gallery->getPrivate() ) return false;
            if ($gallery->getOwnerId() != $context->getId() && $gallery->getOwnerId() != $user->getId() && !$gallery->isShared($context))
                    	return false;
            return true;
        }
        return false;
    }


    /**
     * check if user can edit gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canEditGallery($gallery, $context, $user)
    {

        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        if ( $context instanceof Warecorp_User ) {
			if ($gallery->getOwnerType() == 'group') {
				return false;
			}else{
				if ( $context->getId() == $user->getId() ) {
	            	if ($gallery->getOwnerId() == $user->getId()) {
	            	    return true;
	            	}else {
						return false;
	            	}
	            }else{
					return false;
				}
			}
        }elseif ( $context instanceof Warecorp_Group_Base ) {
			if ($gallery->getOwnerType() == 'group') {
                if ($gallery->getOwnerId() == $context->getId()){
                    if ($gallery->getCreatorId() == $user->getId()) return true;

	        		if ( $context->getMembers()->isHost($user->getId()) ) {
	        			return true;
	        		}

					if ( $context->getMembers()->isCohost($user->getId()) ) {
        				return true;
        			}

					return false;
				}else{
					return false;
				}

			}else{
				return false;
			}
        }
        return false;
    }

    /**
     * check if user can edit photo
     * @author Yury Zolotarsky
     */
    static public function canEditPhoto($photo, $context, $user)
    {

        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkPhoto($photo);

		return self::canEditGallery($photo->getGallery(), $context, $user);
    }


    /**
     * check if user can share gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canDeleteGallery($gallery, $context, $user)
    {

		return self::canEditGallery($gallery, $context, $user);

// 		if ( $user->getId() === null ) return false;
//
//         self::_checkContext($context);
//         self::_checkUser($user);
//         self::_checkGallery($gallery);
//
//         if ($gallery->getCreatorId() == $user->getId()) return true;
//         if ( $context instanceof Warecorp_User ) {
//             if ( $context->getId() == $user->getId() ) {
//
//                 if ( $gallery->getOwnerType() == 'user' && $gallery->getOwnerId() == $user->getId() ) {
//                    return true;
//                 }
//             }else {
//             }
//         }elseif ( $context instanceof Warecorp_Group_Base ) {
//
//             if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
//
//                 if ( $context->getMembers()->isHost($user->getId()) ) {
//                     return true;
//                 }elseif ( $context->getMembers()->isCohost($user->getId()) ) {
//                     return true;
//                 }else {
//                 	return false;
//                 }
//             }else {
//                 return false;
//             }
//         }
//         return false;
    }


    /**
     * check if user can delete photo
     * @author Yury Zolotarsky
     */
    static public function canDeletePhoto($photo, $context, $user)
    {

        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkPhoto($photo);

        return self::canDeleteGallery($photo->getGallery(), $context, $user);
    }



    /**
     * check if user can copy gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canCopyGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;
        if ( $gallery === null )       return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * account owner (user view own profile)
             */
            if ( $context->getId() == $user->getId() ) {
                if ($gallery->isShared($context)) return self::canCopyGallery($gallery, $gallery->getOwner(), $user);
                return false;
            }
            /**
             * other user (user view profile of other user)
             */
            elseif($gallery->getOwnerType() == 'user' && $user->getId() == $gallery->getOwnerId()) {
                return false;
            }else{
				return true;
			}
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            return true;
        }
        return false;
    }

    /*
     +-----------------------------------
     |
     |  Gallery Sharing Methods
     |
     +-----------------------------------
    */

    /**
     * check if user can view history of sharing
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewShareHistoryGallery($gallery, $context, $user)
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);
        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * account owner (user view own profile)
             */
            if ( $context->getId() == $user->getId() ) {
                /**
                 * user is owner of this gallery
                 */
                if ( $gallery->getOwnerType() == 'user' && $gallery->getOwnerId() == $user->getId() ) {
                   return true;
                }
                /**
                 *  user is not owner of this gallery
                 */
                else {
                }
            }
            /**
             * other user (user view profile of other user)
             */
            else {
            }
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {

                }
                /**
                 * user isn't member of this group
                 */
                else {

                }
            }
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
            else {

            }
        }
        return false;
    }


    /**
     * check if user can share gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canShareGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * account owner (user view own profile)
             */
            if ( $context->getId() == $user->getId() ) {
                /**
                 * user is owner of this gallery
                 */
                if ( $gallery->getOwnerType() == 'user' && $gallery->getOwnerId() == $user->getId() ) {
                   return true;
                }
            }
            /**
             * other user (user view profile of other user)
             */
            else {
            }
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
//            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
					return false;
                }
                /**
                 * user isn't member of this group
                 */
//                else {
//                	return false;
//                }
//            }
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
//            else {
//
//            }
        }
        return false;
    }

    static public function canPublishGallery($gallery, $context, $user)
    {
        if (HTTP_CONTEXT != 'theuptake') return false;
        
        if ( $user->getId() === null ) return false; //if anonymous user - return false

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        if ( $context instanceof Warecorp_User ) {  //if context - user profile
            return false;
        } elseif ( $context instanceof Warecorp_Group_Base) { //if context - group
            try {
                $theUptakeNewsTeam = Warecorp_Group_Factory::loadByGroupUID('theuptake-news-team',Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
            } catch (Zend_Exception $e) {
                return false;
            }
            if ( ($gallery->isShared($context) || ($gallery->getOwnerType()=='group' && $theUptakeNewsTeam && $gallery->getOwnerId() == $theUptakeNewsTeam->getId())) && in_array($context->getId(), self::getPublishAllowedGroupsFrom()) ) { //gallery belong to publish group
                try {
                    $theUptakeFamily = Warecorp_Group_Factory::loadByGroupUID('theuptake',Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
                    if ($theUptakeFamily->getMembers()->isHost($user->getId()) || $theUptakeFamily->getMembers()->isCoHost($user->getId()) || $theUptakeNewsTeam->getMembers()->isMemberExistsAndApproved($user->getId())) { //user has host privilegies
                        return true;
                    }
                } catch (Zend_Exception $e) {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * check if user can unshare gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canUnShareGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            /**
             * account owner (user view own profile)
             */
            if ( $context->getId() == $user->getId() ) {
                /**
                 * user is owner of this gallery
                 */
                 if ($gallery->isShared($context)) return true;
            }
            /**
             * other user (user view profile of other user)
             */
            else {
            	if ($gallery->isShared($context)) return true;
            }
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
/*            if ( !($gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() )) {
				return false;
            }*/
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
            /*else {*/
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    if ($gallery->isShared($context)) return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                    if ($gallery->isShared($context)) return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $gallery->getCreatorId() == $user->getId() ) {
					if ($gallery->isShared($context)) return true;
                }
                /**
                 * user hasn't HostPrivileges and not creator
                 */
                else {
                	return false;
                }
            //}
        }
        return false;
    }

    static public function canShareGalleryToAllFamilyGroups(Warecorp_Photo_Gallery_Abstract $gallery, $family, Warecorp_User $user)
    {
        if ( !($family instanceof Warecorp_Group_Family) ) {
            if ( strpos($family, 'family_') !== false ) {
                $family = substr($family, 7);
            }
            if ( is_numeric($family) ) {
                $family = Warecorp_Group_Factory::loadById($family, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
            }
        }

        if ( !($family instanceof Warecorp_Group_Family) || !$family->getId() )
            return false;

        if (
            Warecorp_Group_AccessManager::canShareToFamiliesGroups($family, $user)                                  &&
           !Warecorp_Share_Entity::isShareExists( $family->getId(), $gallery->getId(), $gallery->EntityTypeId )     &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || 
                $gallery->getCreatorId() === $user->getId() || Warecorp_Group_AccessManager::isGroupHost())
        ) {
            return true;
        }
        return false;
    }

    static public function canUnshareGalleryToAllFamilyGroups(Warecorp_Photo_Gallery_Abstract $gallery, $family, Warecorp_User $user)
    {
        if ( !($family instanceof Warecorp_Group_Family) ) {
            if ( strpos($family, 'family_') !== false ) {
                $family = substr($family, 7);
            }
            if ( is_numeric($family) ) {
                $family = Warecorp_Group_Factory::loadById($family,Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
            }
        }

        if ( !($family instanceof Warecorp_Group_Family) || !$family->getId() )
            return false;

        if (
            Warecorp_Group_AccessManager::canUnshareToFamiliesGroups($family, $user)                                &&
            Warecorp_Share_Entity::isShareExists( $family->getId(), $gallery->getId(), $gallery->EntityTypeId )     &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $gallery->getCreatorId() === $user->getId()) // || Warecorp_Group_AccessManager::isGroupHost())
        ) {
            return true;
        }
        return false;
    }

    /**
     * check if user can unshare gallery from sharing history
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canUnShareFromHistoryGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            return true;
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
					return Warecorp_Group_AccessManager::canUsePhotos($contex, $user);
                }
                /**
                 * user isn't member of this group
                 */
                else {

                }
            }
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
            else {

            }
        }
        return false;
    }

    /*
     +-----------------------------------
     |
     |  Gallery Watching Methods
     |
     +-----------------------------------
    */

    /**
     * check if user can stop watching gallery
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canStopWatchingGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            return true;
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            return false;
        }
        return false;
    }

    /*
     +-----------------------------------
     |
     |  Gallery Comments Access Methods
     |
     +-----------------------------------
    */

    /**
     * check if user can post comments
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewCommentsGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            return true;
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                	return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
                	return true;
                }
                /**
                 * user isn't member of this group
                 */
                else {
                	return false;
                }
            }
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
            else {
          		return true;
            }

        }
        return false;
    }


    /**
     * check if user can post comments
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canPostCommentsGallery($gallery, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            return true;
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                	return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
                	return true;
                }
                /**
                 * user isn't member of this group
                 */
                else {

                }
            }
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
            else {
          		if ($gallery->getCreator()->getId() == $user->getId()) return true;
            	  else return false;
            }
        }
        return false;
    }


    /**
     * check if user can edit current comment
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canEditCommentGallery($gallery, $comment, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ($comment->userId == $user->getId()) return true; else return false;

        if ( $context instanceof Warecorp_User ) {
            return true;
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
            /**
             * gallery belong to this group
             */
            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
                /**
                 * user is host of this group
                 */
                if ( $context->getMembers()->isHost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is cohost of this group
                 */
                elseif ( $context->getMembers()->isCohost($user->getId()) ) {
                    return true;
                }
                /**
                 * user is member of this group
                 */
                elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
                    /**
                     * user is createor of comment
                     */
                    if ( $comment->userId == $user->getId() ) {
                        return true;
                    }
                }
                /**
                 * user isn't member of this group
                 */
                else {

                }
            }
            /**
             * gallery don't belong to this group (gallery is shared gallery)
             */
            else {
          		if ($gallery->getCreator()->getId() == $user->getId()) return true;
            	  else return false;
            }
        }
        return false;
    }


    /**
     * check if user can delete current comment
     * @param int|Warecorp_Photo_Gallery_Abstract $gallery
     * @param Warecorp_User|Warecorp_Group_Base $context - action context (viewed profile of group)
     * @param int|Warecorp_User - current user
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canDeleteCommentGallery($gallery, $comment, $context, $user)
    {
        /**
         * if anonymous user - return false
         */
        if ( $user->getId() === null ) return false;

        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkGallery($gallery);

        /**
         * if context - user profile
         */
        if ( $context instanceof Warecorp_User ) {
            if ($comment->userId == $user->getId() || $gallery->getOwnerId() == $user->getId()) return true;
            	else return false;
        }
        /**
         * if context - group
         */
        elseif ( $context instanceof Warecorp_Group_Base ) {
        	if ($comment->userId == $user->getId() || $gallery->getOwnerId() == $user->getId()) {
                return true;
            }

            if ( $gallery->getOwnerType() == 'group' && $gallery->getOwnerId() == $context->getId() ) {
	            /**
	             * user is host of this group
	             */
	            if ( $context->getMembers()->isHost($user->getId()) ) {

	                return true;
	            }
	            /**
	             * user is cohost of this group
	             */
	            elseif ( $context->getMembers()->isCohost($user->getId()) ) {

	                return true;
	            }
	            /**
	             * user is member of this group
	             */
	            elseif ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
	                /**
	                 * user is createor of comment
	                 */
	          		if ($gallery->getCreator()->getId() == $user->getId()) return true;
	            	  else return false;
	            }
	            /**
	             * user isn't member of this group
	             */
	            else {
	          		if ($gallery->getCreator()->getId() == $user->getId()) return true;
	            	  else return false;
	            }
            }else {
            	return false;
            }
        }
        return false;
    }

    /*
     +-----------------------------------
     |
     |  Private Methods
     |
     +-----------------------------------
    */

    /**
     * check context
     * @param mixed $context
     * @return void
     * @throws Zend_Exception
     * @author Artem Sukharev
     */
    protected function _checkContext($context)
    {
    	if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
    	   throw new Zend_Exception('Incorrect context object');
    	}
    }

    /**
     * check user object, convert it to Warecorp_User if need
     * @param int|Warecorp_User - current user
     * @return void
     * @throws Zend_Exception
     * @author Artem Sukharev
     */
    protected function _checkUser(&$user)
    {
    	if ( !($user instanceof Warecorp_User) ) {
    	   $user = new Warecorp_User('id', $user);
    	}
    }

    /**
     * check gallery object, convert it to Warecorp_Photo_Gallery_Abstract if need
     * @param int|Warecorp_Photo_Gallery_Abstract - gallery
     * @return void
     * @throws Zend_Exception
     * @author Artem Sukharev
     */
    protected function _checkGallery(&$gallery)
    {
        if ( !($gallery instanceof Warecorp_Photo_Gallery_Abstract) ) {
           $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery);
        }
    }

    /**
     * check photo object, convert it to Warecorp_Photo_Gallery_Abstract if need
     * @param int|Warecorp_Photo_Abstract - photo
     * @return void
     * @throws Zend_Exception
     * @author Yury Zolotarsky
     */
    protected function _checkPhoto(&$photo)
    {
        if ( !($photo instanceof Warecorp_Photo_Abstract) ) {
           $photo = Warecorp_Photo_Factory::loadById($photo);
        }
    }
}
