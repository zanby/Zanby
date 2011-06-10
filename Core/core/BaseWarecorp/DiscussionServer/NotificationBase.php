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
 * Represents common functionality of notification messages in discussions.
 * Derived classe is composes its messages to be placed to mail template.
 * @author Yury Nelipovich
 */
abstract class BaseWarecorp_DiscussionServer_NotificationBase
{
    /**
     * @var string Notification type
     */
    protected $type;

    protected $lastDelivery;
    protected $currentDelivery;
    protected $subjectPrefix;
    protected $messageFooterPlain;
    protected $messageFooterHtml;
    protected $subscriptionTarget;
    protected $objSender;
    protected $mailHeaders;
    
    protected $message;
    protected $messages = array();
    
    /**
     * set notification type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * get notification type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Constructs new instance of object
     *
     * @param string $templateKey key of template to use for notification
     * @param string $lastDelivery date of last delivery - Zend_Date
     * @param string $subscriptionName one of: 'group', 'discussion', 'topic'. defines type of subscription
     * @param int $subscriptionId id of subscription
     * @param int $groupId id of group subscription belongs to
     * @author Yury Nelipovich
     */
    public function __construct($templateKey, $lastDelivery, $subscriptionName, $subscriptionId, $groupId)
    {
        $this->mailHeaders = array();
        $this->objSender = new Warecorp_User();

        $this->lastDelivery = $lastDelivery;
        $this->currentDelivery = new Zend_Date();

        /**
         * get subject prefix
         */
        $groupSettings = Warecorp_DiscussionServer_Settings::findByGroupId($groupId);
        $EmailSubjectPrefix = trim($groupSettings->getEmailSubjectPrefix());
        if ( $EmailSubjectPrefix != "" && !preg_match('/^(\[.+(?=\])\])|({.+(?=})})$/i', $EmailSubjectPrefix) ) {
            $EmailSubjectPrefix = '['.$EmailSubjectPrefix.']';
        }
        $this->subjectPrefix = $EmailSubjectPrefix;

        /**
         * get message footer
         */
        if ( $groupSettings->getMessageFooterMode() == 1 ) {
            $this->messageFooterPlain = $groupSettings->getMessageFooterContent();
            $this->messageFooterHtml = $groupSettings->getMessageFooterContent();
        }

        //load subscription object depending on its type
        /**
         * subscription for all discussions from current group
         */
        if ($subscriptionName == 'group') {
            $subscriptionTarget = Warecorp_Group_Factory::loadById($groupId);
        }
        /**
         * subscription for current discussion
         */
        elseif ($subscriptionName == 'discussion') {
            $subscription = new Warecorp_DiscussionServer_DiscussionSubscription($subscriptionId);
            $subscriptionTarget = new Warecorp_DiscussionServer_Discussion($subscription->getDiscussionId());
        }
        /**
         * sudscription for current topic
         */
        elseif ($subscriptionName == 'topic') {
            $subscription = new Warecorp_DiscussionServer_TopicSubscription($subscriptionId);
            $subscriptionTarget = $subscription->getTopic();
        }
        $this->subscriptionTarget = $subscriptionTarget;
    }

    /**
     * Sends notification to recipient
     *
     * @param Warecorp_User $recipient
     */
    public function send($recipient)
    {
        try { $client = Warecorp::getMailServerClient(); }
        catch ( Exception $e ) { $client = NULL; }

        if ( $client ) {
            try {
                $this->putMessagesToMail();
                $sender = $this->objSender;
                
                /**
                 * don't need to send message to pmb system
                 * @var Artem Sukharev
                 */
                $campaignUID = $client->createCampaign();                
                if ( $this->getType() === 'SINGLE' ) {
                    $client->setSender(
                        $campaignUID,
                        $sender->getEmail(),
                        $sender->getFirstname().' '.$sender->getLastname()
                    );
                } elseif ( $this instanceof Warecorp_DiscussionServer_SingleEmailMessage ) {
                    $client->setSender(
                        $campaignUID,
                        $this->message->getDiscussion()->getFullEmail(),
                        $this->message->getDiscussion()->getGroup()->getName().' - '.$this->message->getDiscussion()->getTitle()
                    );
                } else {
                    $client->setSender(
                        $campaignUID,
                        "discussions@".DOMAIN_FOR_GROUP_EMAIL,
                        SITE_NAME_AS_STRING." Discussions"
                    );
                }
                $client->setTemplate($campaignUID, 'DISCUSSION_SUBSCRIPTION_EMAIL', HTTP_CONTEXT);
               
                try {
                    if ( sizeof($this->mailHeaders) != 0 ) {
                        foreach ( $this->mailHeaders as $name => $value ) {
                            $client->addHeader( $campaignUID, $name, $value );
                        }
                    }
                } catch ( Exception $e ) {}

                $subject_prefix = ( !empty($this->subjectPrefix) ) ? $this->subjectPrefix : null;
                $last_delivery = $this->lastDelivery;
                $current_delivery = $this->currentDelivery;
                

                function_exists("smarty_modifier_user_date_format") || require_once COMMON_SMARTY_PLUGINS_DIR.'/modifier.user_date_format.php';
                function_exists('smarty_modifier_escape') || require_once COMMON_SMARTY_PLUGINS_DIR.'/modifier.escape.php';
                
                $subscription_subject = '';
                if ( $this instanceof Warecorp_DiscussionServer_SingleEmailMessage ) {
                    if ( $subject_prefix ) $subscription_subject = sprintf($subject_prefix, $this->message->getPost()->getPosition())." ";
                    $subscription_subject .= $this->message->getTopic()->getSubjectForEmail();
                } elseif ( $this->getType() === 'DIGEST_25' ) {
                    $subscription_subject = "Discussion Digest, 25 new messages";
                } elseif ( $this->getType() === 'DIGEST_50' ) {
                    $subscription_subject = "Discussion Digest, 25 new messages";
                } elseif ( $this->getType() === 'DIGEST_DAILY' ) {
                    $subscription_subject  = "Daily Discussion Digest, ";
                    $subscription_subject .= smarty_modifier_user_date_format($last_delivery, $recipient->getTimezone(), 'MAIL_SHORT');
                    $subscription_subject .= " - ";
                    $subscription_subject .= smarty_modifier_user_date_format($current_delivery, $recipient->getTimezone(), 'MAIL_SHORT');
                } elseif ( $this->getType() === 'DIGEST_WEEKLY' ) {
                    $subscription_subject  = "Weekly Discussion Digest, ";
                    $subscription_subject .= smarty_modifier_user_date_format($last_delivery, $recipient->getTimezone(), 'MAIL_SHORT');
                    $subscription_subject .= " - ";
                    $subscription_subject .= smarty_modifier_user_date_format($current_delivery, $recipient->getTimezone(), 'MAIL_SHORT');
                }

                $group_name = null;
                $discussion_url = null;
                $discussion_settings_url = null;

                $messages_plain = "";
                $messages_html = "";
                if ( sizeof($this->messages) != 0 ) {
                    $countMessages = sizeof($this->messages);
                    foreach ( $this->messages as $message ) {
                        $group_name = $group_name ? $group_name : $message->getDiscussion()->getGroup()->getName();
                        $discussion_url = $discussion_url ? $discussion_url : $message->getDiscussion()->getGroup()->getGroupPath('discussion');
                        $discussion_settings_url = $discussion_settings_url ? $discussion_settings_url : $message->getDiscussion()->getGroup()->getGroupPath('discussionsettings');

                        $discussion_url = Warecorp::getTinyUrl($discussion_url, HTTP_CONTEXT);
                        $discussion_settings_url = Warecorp::getTinyUrl($discussion_settings_url, HTTP_CONTEXT);

                        $tmp_topic_url = $message->getDiscussion()->getGroup()->getGroupPath('topic/topicid').$message->getTopic()->getId();
                        $tmp_topic_url = Warecorp::getTinyUrl($tmp_topic_url, HTTP_CONTEXT);

                        if (!Warecorp::checkHttpContext('zccr')) {
                            /**
                             * PLAIN PART
                             * @author Artem Sukharev
                             */
                            $messages_plain .= "Discussion: ". smarty_modifier_escape($message->getDiscussion()->getTitle(), 'html')."\n";
                            $messages_plain .= "Topic: ". smarty_modifier_escape($message->getTopic()->getSubject(), 'html')."\n";
                            $messages_plain .= "Author: ". smarty_modifier_escape($message->getAuthor()->getLogin(), 'html')."\n";
                            $messages_plain .= "Posted: ". smarty_modifier_escape($message->getPosted($recipient->getTimezone()), 'html')."\n";
                            $messages_plain .= "\n";
                            if ( $message->getPost()->getFormat() == 'html' ) {
                                $messages_plain .= $message->getPost()->getTextContent()."\n";
                            } else {
                                $messages_plain .= $message->getPost()->getMailBBContentPlain()."\n";
                            }
                            $messages_plain .= "\n\n";
                            $messages_plain .= "View Topic: ".$tmp_topic_url."\n";
                            $messages_plain .= "Topic Reply: ". $message->getDiscussion()->getFullEmail().'?subject='.rawurlencode((($subject_prefix)? sprintf($subject_prefix, $message->getPost()->getPosition()).' ' : '').'RE: '.$message->getTopic()->getSubjectForEmail()).'&body='.rawurlencode(" ")."\n";
                            if ( $countMessages > 1 ) {
                                $messages_plain .= "\n============================================================\n\n";
                            }
                            /**
                             * HTML PART
                             */
                            $messages_html .= "<div><b>Discussion: </b>". smarty_modifier_escape($message->getDiscussion()->getTitle(), 'html')."</div>";
                            $messages_html .= "<div><b>Topic: </b>". smarty_modifier_escape($message->getTopic()->getSubject(), 'html')."</div>";
                            $messages_html .= "<div><b>Author: </b>". smarty_modifier_escape($message->getAuthor()->getLogin(), 'html')."</div>";
                            $messages_html .= "<div><b>Posted: </b>". smarty_modifier_escape($message->getPosted($recipient->getTimezone()), 'html')."</div>";
                            $messages_html .= "<div style=\"height:10px;\" />";
                            if ( $message->getPost()->getFormat() == 'html' ) {
                                $messages_html .= "<div>".$message->getPost()->getContent()."</div>";
                            } else {
                                $messages_html .= "<div>".$message->getPost()->getBBContent()."</div>";
                            }
                            $messages_html .= "<div style=\"height:10px;\" />";
                            $messages_html .= "<div>";
                            $messages_html .= "<a href=\"{$tmp_topic_url}\">View Topic</a> | ";
                            $messages_html .= "<a href=\"mailto:{$message->getDiscussion()->getFullEmail()}?subject=".rawurlencode((($subject_prefix)? sprintf($subject_prefix, $message->getPost()->getPosition()).' ' : '').'RE: '.$message->getTopic()->getSubjectForEmail())."&body=".rawurlencode(' ')."\">Topic Reply</a>";
                            $messages_html .= "</div>";
                            if ( $countMessages > 1 ) {
                                $messages_html .= "<div style=\"height:10px;\" />";
                                $messages_html .= "<div><hr></div>";
                                $messages_html .= "<div style=\"height:10px;\" />";
                            }
                        }
                        /**
                         * FOR ZCCR ONLY
                         * ----------------------------------------------------------------------------------------
                         */
                        else {
                            /**
                             * PLAIN PART
                             * @author Artem Sukharev
                             */
                            $messages_plain .= "On ".(smarty_modifier_escape($message->getPosted($recipient->getTimezone()), 'html'))." ".(smarty_modifier_escape($message->getAuthor()->getLogin(), 'html'))." wrote: "."\n";
                            $messages_plain .= "Subject: ".(smarty_modifier_escape($message->getTopic()->getSubject(), 'html'))."\n";
                            $messages_plain .= "\n";
                            if ( $message->getPost()->getFormat() == 'html' ) {
                                $messages_plain .= $message->getPost()->getTextContent()."\n";
                            } else {
                                $messages_plain .= $message->getPost()->getMailBBContentPlain()."\n";
                            }
                            $messages_plain .= "\n";
                            $messages_plain .= "To see the full post, visit: ".$tmp_topic_url."\n";
                            if ( $countMessages > 1 ) {
                                $messages_plain .= "\n============================================================\n\n";
                            }
                            /**
                             * HTML PART
                             */
                            $messages_html .= "<div>On ".(smarty_modifier_escape($message->getPosted($recipient->getTimezone()), 'html'))." ".(smarty_modifier_escape($message->getAuthor()->getLogin(), 'html'))." wrote: "."</div>";
                            $messages_html .= "<div>Subject: ".(smarty_modifier_escape($message->getTopic()->getSubject(), 'html'))."</div>";
                            $messages_html .= "<div style=\"height:10px;\" />";
                            if ( $message->getPost()->getFormat() == 'html' ) {
                                $messages_html .= "<div>".$message->getPost()->getContent()."</div>";
                            } else {
                                $messages_html .= "<div>".$message->getPost()->getBBContent()."</div>";
                            }
                            $messages_html .= "<div style=\"height:10px;\" />";
                            $messages_html .= "<br />";
                            $messages_html .= "<div>To see the full post, visit: <a href='".$tmp_topic_url."'>".$tmp_topic_url."</a></div>";
                            if ( $countMessages > 1 ) {
                                $messages_html .= "<div style=\"height:10px;\" />";
                                $messages_html .= "<div><hr></div>";
                                $messages_html .= "<div style=\"height:10px;\" />";
                            }
                        }
                        $countMessages = $countMessages - 1;
                    }
                }

                if ( !empty($this->message) && $this->message instanceof Warecorp_DiscussionServer_SubscriptionMessage ) {
                    if ( $message->getPost()->getFormat() == 'html' ) {
                        $message_post_mailbbcontentplain = $message->getPost()->getTextContent();
                        $message_post_mailbbcontenthtml  = $message->getPost()->getContent();
                    } else {
                        $message_post_mailbbcontentplain = $message->getPost()->getMailBBContentPlain();
                        $message_post_mailbbcontenthtml  = $message->getPost()->getBBContent();                        
                    }
                } else {
                    $message_post_mailbbcontentplain = NULL;
                    $message_post_mailbbcontenthtml  = NULL;
                }
				
                /*
                 * TODO FIX URL for correct locale
                 */
                if ( !defined('LOCALE') ) {
                    $currLocale = ( trim($recipient->getLocale()) === '' ) ? 'en' : trim($recipient->getLocale());
                    $currLocale = explode('_', $currLocale);
                    $currLocale = $currLocale[0];
                    define('LOCALE', $currLocale);
                }

				/**
				 * Load Default Footer
				 * @author Artem Sukharev
				 * @see #10487
				 */
				$cfgLoader = Warecorp_Config_Loader::getInstance();

                /**
                 * Try load Discussion Cfg from implementation
                 * if false - load from core
                 * @author Artem Sukharev
                 * @see issue #12193
                 */
                if ( file_exists(CONFIG_DIR.DIRECTORY_SEPARATOR.'cfg.discussion.xml') ) {
                    $cfgDefaultFooter = $cfgLoader->getAppConfig('cfg.discussion.xml')->{'default_footer'};
                } else {
                    $cfgDefaultFooter = $cfgLoader->getCoreConfig('cfg.discussion.xml')->{'default_footer'};
                }

				$footer_plain_default = isset($cfgDefaultFooter->plain) ? $cfgDefaultFooter->plain : '';
				$footer_html_default = isset($cfgDefaultFooter->html) ? $cfgDefaultFooter->html : '';
				$replace['{*SITE_NAME*}'] = SITE_NAME_AS_STRING;
				$replace['{*SITE_URL*}'] = SITE_NAME_AS_FULL_DOMAIN;
				$replace['{*SITE_NAME_AS_DOMAIN*}'] = SITE_NAME_AS_DOMAIN;
				$replace['{*BASE_HTTP_HOST*}'] = BASE_HTTP_HOST;
				$replace['{*SITE_EMAIL_FEEDBACK*}'] = 'feedback@'.DOMAIN_FOR_EMAIL;
				$replace['{*SITE_LINK_PRIVACY*}'] = BASE_URL.'/'.LOCALE.'/info/privacy/';
				$replace['{*SITE_LINK_TERMS*}'] = BASE_URL.'/'.LOCALE.'/info/terms/';
				$replace['{*SITE_LINK_REGISTRATION*}'] = BASE_URL.'/'.LOCALE.'/registration/index/';
				$replace['{*SITE_LINK_UNSUBSCRIBE*}'] = $recipient->getUserPath('settings');

                /**
                 * @see issue #12193
                 * @author Artem Sukharev
                 */
                $replace['{*group_name*}'] = $group_name;
                $replace['{*discussion_settings_url*}'] = $discussion_settings_url;
                $replace['{*discussion_url*}'] = $discussion_url;

				$footer_plain_default = str_replace( array_keys($replace), $replace, $footer_plain_default );
				$footer_html_default = str_replace( array_keys($replace), $replace, $footer_html_default );
				
                $footer_plain = !empty($this->messageFooterPlain) ? "\n\n".$this->messageFooterPlain : $footer_plain_default;
                $footer_html = !empty($this->messageFooterHtml) ? '<br/><br/>'.$this->messageFooterHtml : $footer_html_default;
				
                $params = new Warecorp_SOAP_Type_Params();
                $params->loadDefaultCampaignParams();
                $params->addParam('discussion_subscription_subject', $subscription_subject);
                $params->addParam('message_post_mailbbcontentplain', $message_post_mailbbcontentplain);
                $params->addParam('message_post_mailbbcontenthtml', $message_post_mailbbcontenthtml);
                $params->addParam('message_footer_plain', $footer_plain);
                $params->addParam('message_footer_html', $footer_html);
                $params->addParam('messages_plain', $messages_plain);
                $params->addParam('messages_html', $messages_html);
                $client->addParams($campaignUID, $params);

                $r = new Warecorp_SOAP_Type_Recipient();
                $r->setEmail( $recipient->getEmail() );
                $r->setName( $recipient->getFirstname().' '.$recipient->getLastname() );
                $r->setLocale( $recipient->getLocale() );
                $r->addParam('CCFID', Warecorp::getCCFID($recipient));
                $client->addRecipient($campaignUID, $r);

                $client->startCampaign($campaignUID);
            } catch ( Exception $e ) { throw $e; }
        }
    }

    /**
     * Puts all messages or one message to email template
     */
    protected abstract function putMessagesToMail();
}

?>
