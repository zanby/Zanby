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

class BaseWarecorp_Facebook_Feed
{
    static $allowDirectStream = true;
    static $allowDirectFeed = true;
    static private $isInit = false;
    
    static public function createMessageTemplates() {
        /**
         * FEED_ACTION_MESSAGE_DEFAULT
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just created new content on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just created new content on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_DEFAULT:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_NEW_EVENT
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just created an Event <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just created an Event <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array();    
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_NEW_EVENT:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_NEW_LIST
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just created a List <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just created a List <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_NEW_LIST:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_NEW_PHOTO
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just uploaded a Photo <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just uploaded a Photo <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_NEW_PHOTO:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_NEW_VIDEO
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just uploaded a Video <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just uploaded a Video <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        /*
        $action_links[] = array('text'=>'Watch Zanby', 'href'=>'http://zanby.com'); 
        $action_links[] = array('text'=>'Watch {*artist*}', 'href'=>"http://zanby.com");
        */
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_NEW_VIDEO:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_COMMENTED_PHOTO
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just commented on Photo <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just commented on Photo <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        /*
        $action_links[] = array('text'=>'Watch Zanby', 'href'=>'http://zanby.com'); 
        $action_links[] = array('text'=>'Watch {*artist*}', 'href'=>"http://zanby.com");
        */
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_COMMENTED_PHOTO:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_COMMENTED_LIST
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} just commented on List <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*} just commented on List <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_COMMENTED_LIST:".$bundleId."<br>";
        
        /**
         * FEED_ACTION_MESSAGE_RSVP_EVENT
         */
        $one_line_story_templates = array(); 
        $one_line_story_templates[] = '{*actor*} rsvp\'d to <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>';     
        $short_story_templates = array();
        $short_story_templates[] = array(
            'template_title' => '{*actor*}  rsvp\'d to <a href="{*url*}">{*title*}</a> on <a href="{*orgurl*}">{*orgname*}</a>',
            'template_body' => ''
        );    
        $full_story_template = null;     
        $action_links = array(); 
        $bundleId = Warecorp_Facebook_Api::getInstance()->api(array(
            'method'=>'feed.registerTemplateBundle',
            'one_line_story_templates'=>$one_line_story_templates,
            'short_story_templates'=>$short_story_templates,
            'full_story_template'=>null
        ));     
        //$bundleId = Warecorp_Facebook_Api::getInstance()->api_client->feed_registerTemplateBundle($one_line_story_templates,$short_story_templates,null,$action_links);
        print "FEED_ACTION_MESSAGE_RSVP_EVENT:".$bundleId."<br>";
        
        exit;
    }
    
    static private function initMessages() {
        $cfgFBMessages = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.fb.messages.xml');
        /**
         * TODO : assign vars automaticly 
         * @var unknown_type
         */
        
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_DEFAULT) ) 			self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_DEFAULT] = $cfgFBMessages->STREAM_ACTION_MESSAGE_DEFAULT;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_EVENT) ) 			self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_EVENT] = $cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_EVENT;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_LIST) ) 			self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_LIST] = $cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_LIST;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_PHOTO) ) 			self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO] = $cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_PHOTO;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_VIDEO) ) 			self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_VIDEO] = $cfgFBMessages->STREAM_ACTION_MESSAGE_NEW_VIDEO;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_COMMENTED_PHOTO) ) 	self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_PHOTO] = $cfgFBMessages->STREAM_ACTION_MESSAGE_COMMENTED_PHOTO;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_COMMENTED_LIST) ) 	self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_LIST] = $cfgFBMessages->STREAM_ACTION_MESSAGE_COMMENTED_LIST;
        if ( !empty($cfgFBMessages->STREAM_ACTION_MESSAGE_RSVP_EVENT) ) 		self::$stream_action_messages[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_RSVP_EVENT] = $cfgFBMessages->STREAM_ACTION_MESSAGE_RSVP_EVENT;
		
		/**
		 * DEPRECATED
		 */
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_DEFAULT) ) 				self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_DEFAULT] = $cfgFBMessages->FEED_ACTION_MESSAGE_DEFAULT;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_NEW_EVENT) ) 			self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_EVENT] = $cfgFBMessages->FEED_ACTION_MESSAGE_NEW_EVENT;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_NEW_LIST) ) 			self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_LIST] = $cfgFBMessages->FEED_ACTION_MESSAGE_NEW_LIST;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_NEW_PHOTO) ) 			self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_PHOTO] = $cfgFBMessages->FEED_ACTION_MESSAGE_NEW_PHOTO;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_NEW_VIDEO) ) 			self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_VIDEO] = $cfgFBMessages->FEED_ACTION_MESSAGE_NEW_VIDEO;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_COMMENTED_PHOTO) ) 		self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_PHOTO] = $cfgFBMessages->FEED_ACTION_MESSAGE_COMMENTED_PHOTO;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_COMMENTED_LIST) ) 		self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_LIST] = $cfgFBMessages->FEED_ACTION_MESSAGE_COMMENTED_LIST;
        if ( !empty($cfgFBMessages->FEED_ACTION_MESSAGE_RSVP_EVENT) ) 			self::$feed_action_messages[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_RSVP_EVENT] = $cfgFBMessages->FEED_ACTION_MESSAGE_RSVP_EVENT;
    }
    
    /**
     * +--------------------------------------------------------------------------------
     * |
     * |
     * |
     * +--------------------------------------------------------------------------------
     */    
    
    const STREAM_ACTION_MESSAGE_DEFAULT = 1;
    const STREAM_ACTION_MESSAGE_NEW_EVENT = 2;
    const STREAM_ACTION_MESSAGE_NEW_LIST = 3;
    const STREAM_ACTION_MESSAGE_NEW_PHOTO = 4;
    const STREAM_ACTION_MESSAGE_NEW_VIDEO = 5;
    const STREAM_ACTION_MESSAGE_COMMENTED_PHOTO = 6;
    const STREAM_ACTION_MESSAGE_COMMENTED_LIST = 7;
    const STREAM_ACTION_MESSAGE_RSVP_EVENT = 8;
    const STREAM_ACTION_MESSAGE_NEW_GROUP = 9;
    const STREAM_ACTION_MESSAGE_JOIN_GROUP = 10;
    
    const STREAM_ACTION_MESSAGE_NEW_HOST = 11;
    const STREAM_ACTION_MESSAGE_CHANGED_EVENT = 12;
    const STREAM_ACTION_MESSAGE_COMMENTED_VIDEO = 13;

    const STREAM_ACTION_MESSAGE_NEW_DOCUMENT = 14;
    const STREAM_ACTION_MESSAGE_UPDATE_PROFILE = 15;
    const STREAM_ACTION_MESSAGE_UPDATE_GROUP = 16;
    const STREAM_ACTION_MESSAGE_NEW_DISCUSSION = 17;
    const STREAM_ACTION_MESSAGE_NEW_FRIEND = 18;
    const STREAM_ACTION_MESSAGE_CHANGED_LIST = 19;

    const STREAM_ACTION_MESSAGE_CHANGED_PHOTO = 20;
    const STREAM_ACTION_MESSAGE_CHANGED_VIDEO = 21;
    const STREAM_ACTION_MESSAGE_CHANGED_DOCUMENT = 22;

    //{*actor*}
    static private $stream_action_messages = array(
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_DEFAULT => ' just created new content on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_EVENT => ' just created an Event "{*title*}" on {*orgname*}',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_LIST => ' just created a List "{*title*}" on {*orgname*}',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO => ' just created a Photo "{*title*}" on {*orgname*}',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_VIDEO => ' just created a Video "{*title*}" on {*orgname*}',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_PHOTO => ' just commented on Photo "{*title*}" on {*orgname*}',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_LIST => ' just commented on List "{*title*}" on {*orgname*}',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_RSVP_EVENT => ' rsvp\'d to Event "{*title*}" on {*orgname*}',

        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_GROUP => ' just created new group "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_JOIN_GROUP => ' just joined to group "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_HOST => ' just became as host of "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_EVENT => ' just updated Event "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_VIDEO => ' just commented on Video "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_DOCUMENT => ' just created a Document "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_UPDATE_PROFILE => ' just updated Profile on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_UPDATE_GROUP => ' just updated a Group "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_DISCUSSION => ' just created a Discussion "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_FRIEND => ' just added new friend "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_LIST => ' just updated List "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_PHOTO => ' just updated Photo "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_VIDEO => ' just updated Video "{*title*}" on {*orgname*}',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_DOCUMENT => ' just updated a Document "{*title*}" on {*orgname*}'
    );
    static private $stream_action_user_message_prompt = array(
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_DEFAULT => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_EVENT => 'Tell your friends about new Event!',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_LIST => 'Tell your friends about new List!',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO => 'Tell your friends about new Photo!',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_VIDEO => 'Tell your friends about new Video!',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_PHOTO => 'Post your news on Facebook',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_LIST => 'Post your news on Facebook',
		Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_RSVP_EVENT => 'Post your news on Facebook', 


        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_GROUP => 'Tell your friends about new Group',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_JOIN_GROUP => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_HOST => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_EVENT => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_COMMENTED_VIDEO => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_DOCUMENT => 'Tell your friends about new Document',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_UPDATE_PROFILE => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_UPDATE_GROUP => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_DISCUSSION => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_FRIEND => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_LIST => 'Post your news on Facebook',  
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_PHOTO => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_DOCUMENT => 'Tell your friends about new Document',
        Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_VIDEO => 'Post your news on Facebook'

    );
    
    static public function setStreamActionMessage($message_const, $message) {
        if ( empty(self::$stream_action_messages[$message_const]) ) throw new Exception('Incorrect action message key');
        self::$stream_action_messages[$message_const] = $message;        
    }
    
    static public function getStreamActionMessage($message_const, $params = array()) {
        if ( !self::$isInit ) self::initMessages();
        if ( empty(self::$stream_action_messages[$message_const]) ) throw new Exception('Incorrect action message key');
        $strMessage = self::$stream_action_messages[$message_const];
        if ( sizeof($params) ) {
            foreach ( $params as $key => $value ) $strMessage = str_replace("{*".$key."*}", $value, $strMessage);
        }
        return array('message' => $strMessage, 'user_message_prompt' => self::$stream_action_user_message_prompt[$message_const]);
    }
    
	/**
	 *
	 *
	 */
    static public function postStream($message, $attachment = null, $action_links = null, $target_id = null, $uid = null) {
        if ( FACEBOOK_USED ) {
            $facebookId = Warecorp_Facebook_Api::getFacebookId();
            if ( !empty($facebookId) && Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) {
                if ( !is_array($message) ) {            
                    $message = array('message' => $message, 'user_message_prompt' => self::$stream_action_user_message_prompt[Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_DEFAULT]);
                }
    			
                if ( is_array($action_links) ) $action_links = json_encode($action_links);
    			
                $isFBSend = false;
                $facebookUser = new Warecorp_Facebook_User($facebookId);
                if ( self::$allowDirectStream && $facebookUser->canPublishStream() ) {
                    try {
                        Warecorp_Facebook_Api::getInstance()->api(array(
                            'method'=>'stream.publish',
                            'message'=>$message['message'],
                            'attachment'=>$attachment,
                            'action_links'=>$action_links,
                            'target_id'=>$target_id,
                            'uid'=>$uid
                        ));
                        $isFBSend = true;   
                    } catch (Exception $ex) {}                         
                }
                if ( !$isFBSend ) {                
                    $_SESSION['_WFFeed_'] = array();
                    $_SESSION['_WFFeed_']['type'] = 'stream';
                    $_SESSION['_WFFeed_']['message'] = $message['message'];
                    $_SESSION['_WFFeed_']['attachment'] = $attachment;
                    $_SESSION['_WFFeed_']['action_links'] = $action_links;
                    $_SESSION['_WFFeed_']['target_id'] = $target_id;
                    $_SESSION['_WFFeed_']['user_message_prompt'] = $message['user_message_prompt'];
                    $_SESSION['_WFFeed_']['uid'] = $uid;
                }
                return $isFBSend;
            }
        }
        return null;
    }
    
    /**
     * +--------------------------------------------------------------------------------
     * |
     * | !!!! DEPRECATED
     * |
     * +--------------------------------------------------------------------------------
     */
    
	/**
	 * DEPRECATED
	 */
    const FEED_ACTION_MESSAGE_DEFAULT = 1;
    const FEED_ACTION_MESSAGE_NEW_EVENT = 2;
    const FEED_ACTION_MESSAGE_NEW_LIST = 3;
    const FEED_ACTION_MESSAGE_NEW_PHOTO = 4;
    const FEED_ACTION_MESSAGE_NEW_VIDEO = 5;
    const FEED_ACTION_MESSAGE_COMMENTED_PHOTO = 6;
    const FEED_ACTION_MESSAGE_COMMENTED_LIST = 7;
    const FEED_ACTION_MESSAGE_RSVP_EVENT = 8;

	/**
	 * DEPRECATED
	 */
    static private $feed_action_messages = array(
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_DEFAULT => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_EVENT => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_LIST => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_PHOTO => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_VIDEO => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_PHOTO => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_LIST => null,
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_RSVP_EVENT => null
    );
	/**
	 * DEPRECATED
	 */
    static private $feed_action_user_message_prompt = array(
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_DEFAULT => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_EVENT => 'Tell your friends about new Event!',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_LIST => 'Tell your friends about new List!',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_PHOTO => 'Tell your friends about new Photo!',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_NEW_VIDEO => 'Tell your friends about new Video!',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_PHOTO => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_COMMENTED_LIST => 'Post your news on Facebook',
        Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_RSVP_EVENT => 'Post your news on Facebook'
    );
    
	/**
	 * DEPRECATED
	 */
    static public function setFeedActionMessage($message_const, $messageId) {
        if ( empty(self::$feed_action_messages[$message_const]) ) throw new Exception('Incorrect action message key');
        self::$feed_action_messages[$message_const] = $messageId;        
    }
    
	/**
	 * DEPRECATED
	 */
    static public function getFeedActionMessage($message_const) {
        if ( !self::$isInit ) self::initMessages();
        if ( empty(self::$feed_action_messages[$message_const]) ) throw new Exception('Incorrect action message key');
        return array('messageId' => self::$feed_action_messages[$message_const], 'user_message_prompt' => self::$feed_action_user_message_prompt[$message_const]);
    }
    
    /**
     * DEPRECATED 
	 *
     * @param $message
     * @param $template_data
     * @param $target_ids
     * @param $body_general
     * @param $story_size
     * @param $user_message
     * @return unknown_type
     * 
     * const STORY_SIZE_ONE_LINE = 1;
     * const STORY_SIZE_SHORT = 2;
     * const STORY_SIZE_FULL = 4;
     *   
     */
    static public function postFeed($message, $template_data, $target_ids = '', $body_general = '', $story_size = FacebookRestClient::STORY_SIZE_SHORT, $user_message = '') {
        if ( FACEBOOK_USED ) {
            $facebookId = Warecorp_Facebook_Api::getFacebookId();            
            if ( !empty($facebookId) && Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) {
                if ( !is_array($message) ) {            
                    $message = array('messageId' => $message, 'user_message_prompt' => self::$feed_action_user_message_prompt[Warecorp_Facebook_Feed::FEED_ACTION_MESSAGE_DEFAULT]);
                }
                
                $isFBSend = false;
                $facebookUser = new Warecorp_Facebook_User($facebookId);
                if ( self::$allowDirectFeed && $facebookUser->canPublishStream() ) {
                    try {
                        Warecorp_Facebook_Api::getInstance()->api(array(
                            'method'=>'feed.publishUserAction',
                            'template_bundle_id'=>$message['messageId'],
                            'template_data'=>$template_data,
                            'target_ids'=>$target_ids,
                            'body_general'=>$body_general,
                            'story_size'=>$story_size,
                            'user_message'=>$user_message
                        ));
                        //Warecorp_Facebook_Api::getInstance()->api_client->feed_publishUserAction($message['messageId'], $template_data, $target_ids, $body_general, $story_size, $user_message);
                        $isFBSend = true;   
                    } catch (Exception $ex) {}                         
                }
                if ( !$isFBSend ) {
                    $_SESSION['_WFFeed_'] = array();
                    $_SESSION['_WFFeed_']['type'] = 'feed';
                    $_SESSION['_WFFeed_']['template_bundle_id'] = $message['messageId'];
                    $_SESSION['_WFFeed_']['template_data'] = $template_data;
                    $_SESSION['_WFFeed_']['target_id'] = $target_ids; 
                    $_SESSION['_WFFeed_']['body_general'] = $body_general;
                    $_SESSION['_WFFeed_']['story_size'] = $story_size;
                    $_SESSION['_WFFeed_']['user_message_prompt'] = $message['user_message_prompt'];
                    $_SESSION['_WFFeed_']['user_message'] = $user_message;
                    
                    if (is_array($_SESSION['_WFFeed_']['target_id'])) {
                      $_SESSION['_WFFeed_']['target_id'] = json_encode($_SESSION['_WFFeed_']['target_id']);
                      $_SESSION['_WFFeed_']['target_id'] = trim($_SESSION['_WFFeed_']['target_id'], "[]"); // we don't want square brackets
                    }
                    
                }
                return $isFBSend;
            }
        }
        return null;
    }
      
    /**
     * +--------------------------------------------------------------------------------
     * |
     * |
     * |
     * +--------------------------------------------------------------------------------
     */

    static public function postEmail($recipients, $subject, $text, $fbml) {
        if ( FACEBOOK_USED ) {
            $facebookId = Warecorp_Facebook_Api::getFacebookId();
            if ( !empty($facebookId) && Warecorp_Facebook_User::isFBAccountConnected($facebookId) ) {
                $isFBSend = false;
                //if ( self::$allowDirectFeed && Warecorp_Facebook_User::canEmail() ) {
                    try {                     
                        Warecorp_Facebook_Api::getInstance()->api(array(
                            'method'=>'notifications.sendEmail',
                            'recipients'=>$recipients,
                            'subject'=>$subject,
                            'text'=>$text,
                            'fbml'=>$fbml
                        ));
                        $isFBSend = true;  
                    } catch (Exception $ex) {}                         
                //}
                return $isFBSend;
            }
        }
        return null;
    }
        
    /**
     * +--------------------------------------------------------------------------------
     * |
     * |
     * |
     * +--------------------------------------------------------------------------------
     */
    
    static public function postNotification($recipients, $notification, $type = 'user_to_user') {
        if ( !empty($recipients) && FACEBOOK_USED ) {
            $facebookId = Warecorp_Facebook_Api::getFacebookId();
            if ( !empty($facebookId)  && Warecorp_Facebook_User::isFBAccountConnected($facebookId)) {
                if ( is_array($recipients) && sizeof($recipients) != 0 ) $recipients = join(',', $recipients);
                $isFBSend = false;            
                try {                     
                    Warecorp_Facebook_Api::getInstance()->api(array(
                        'method'=>'notifications.send',
                        'recipients'=>$recipients,
                        'notification'=>$notification,
                        'type'=>$type
                    ));
                    //Warecorp_Facebook_Api::getInstance()->api_client->notifications_send($recipients, $notification, $type);
                    $isFBSend = true;  
                } catch (Exception $ex) {}                         
                return $isFBSend;
            }
        }
        return null;
    }
    
    /**
     * +--------------------------------------------------------------------------------
     * |
     * |
     * |
     * +--------------------------------------------------------------------------------
     */
    
    static public function onPageInit() {
        $js = '';
        if ( FACEBOOK_USED && $facebookId = Warecorp_Facebook_Api::getFacebookId() ) {
            if ( !empty($_SESSION['_WFFeed_']) ) {
                if ( $_SESSION['_WFFeed_']['type'] == 'stream' ) {
                    $js = 'FBApplication.onpublish_stream('.Zend_Json_Encoder::encode($_SESSION['_WFFeed_']).');';
                } elseif ( $_SESSION['_WFFeed_']['type'] == 'feed' ) {
                    $js = 'FBApplication.onpublish_feed('.Zend_Json_Encoder::encode($_SESSION['_WFFeed_']).');';
                }
            }
            if ( !empty($js) ) $js = "$(function(){ ".$js." })";
        }
        unset($_SESSION['_WFFeed_']);
        return $js;
    }
    
    static public function getJsResponse() {
        $js = '';
        if ( FACEBOOK_USED && $facebookId = Warecorp_Facebook_Api::getFacebookId() ) {
            if ( !empty($_SESSION['_WFFeed_']) ) {
                if ( $_SESSION['_WFFeed_']['type'] == 'stream' ) {
                    $js = 'FBApplication.onpublish_stream('.Zend_Json_Encoder::encode($_SESSION['_WFFeed_']).');';
                } elseif ( $_SESSION['_WFFeed_']['type'] == 'feed' ) {
                    $js = 'FBApplication.onpublish_feed('.Zend_Json_Encoder::encode($_SESSION['_WFFeed_']).');';
                }
            }
        }
        unset($_SESSION['_WFFeed_']);
        return $js;
    }
}
