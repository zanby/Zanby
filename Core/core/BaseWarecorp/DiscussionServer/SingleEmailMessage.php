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
 * Represents single message notification.
 * @author Yury Nelipovich
 */
class BaseWarecorp_DiscussionServer_SingleEmailMessage extends Warecorp_DiscussionServer_NotificationBase
{

    /**
     * Puts message to digest
     *
     * @param Warecorp_DiscussionServer_SubscriptionMessage $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    protected function putMessagesToMail()
    {
        $this->objSender = $this->message->getAuthor();

        $this->mailHeaders['Sender'] = $this->message->getDiscussion()->getFullEmail();
        $this->mailHeaders['Reply-To'] = $this->message->getDiscussion()->getFullEmail();
    }

    /**
     * Sends notification to recipient
     *
     * @param Warecorp_User $recipient
     */
    public function send($recipient)
    {
        try { $client = Warecorp::getMailServerClient(); }
        catch ( Exception $e ) { throw $e; $client = NULL; }

        if ( $client ) {
            try {
                $this->putMessagesToMail();
                $sender = $this->objSender;

                //if ( $sender->getEmail() == $recipient->getEmail() ) return true;

                /**
                 * don't need to send message to pmb system
                 * @var Artem Sukharev
                 */
                $campaignUID = $client->createCampaign();
                $client->setSender($campaignUID, $sender->getEmail(), "{$sender->getFirstname()} {$sender->getLastname()}");
                $client->setTemplate($campaignUID, 'DISCUSSION_SUBSCRIPTION_EMAIL', HTTP_CONTEXT);

                /**
                 * @see issue #12102
                 * for ZCCR to should be from group email
                 * @author Artem Sukharev
                 */
                if ( Warecorp::checkHttpContext('zccr')) {
                    $this->mailHeaders['To'] = $this->message->getDiscussion()->getFullEmail();
                    $this->mailHeaders['Reply-To'] = $sender->getFirstname().' '.$sender->getLastname().' <'.$sender->getEmail().'>';
                }

                try {
                    if ( sizeof($this->mailHeaders) != 0 ) {
                        foreach ( $this->mailHeaders as $name => $value ) {
                            $client->addHeader( $campaignUID, $name, $value );
                        }
                    }
                } catch ( Exception $e ) {}


                $last_delivery = $this->lastDelivery;
                $current_delivery = $this->currentDelivery;

                $subscription_subject = '';
                if ( !empty($this->subjectPrefix) ) {
                    $subscription_subject = sprintf($this->subjectPrefix, $this->message->getPost()->getPosition())." ";
                }
                $subscription_subject .= $this->message->getTopic()->getSubjectForEmail();

                /*
                 * TODO FIX URL for correct locale
                 */
                if ( !defined('LOCALE') ) {
                    $currLocale = ( trim($recipient->getLocale()) === '' ) ? 'en' : trim($recipient->getLocale());
                    $currLocale = explode('_', $currLocale);
                    $currLocale = $currLocale[0];
                    define('LOCALE', $currLocale);
                }


                $discussion_url = $this->message->getDiscussion()->getGroup()->getGroupPath('discussion');
                $discussion_settings_url = $this->message->getDiscussion()->getGroup()->getGroupPath('discussionsettings');
				$topic_url = $this->message->getDiscussion()->getGroup()->getGroupPath('topic/topicid/'.$this->message->getTopic()->getId());

                $discussion_url = Warecorp::getTinyUrl($discussion_url, HTTP_CONTEXT);
                $discussion_settings_url = Warecorp::getTinyUrl($discussion_settings_url, HTTP_CONTEXT);
                $topic_url = Warecorp::getTinyUrl($topic_url, HTTP_CONTEXT);

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
                $replace['{*group_name*}'] = $this->message->getDiscussion()->getGroup()->getName();
                $replace['{*topic_url*}'] = $topic_url;
                $replace['{*discussion_settings_url*}'] = $discussion_settings_url;
                //$replace['{*discussion_url*}'] = $discussion_url;
                $replace['{*discussion_url*}'] = $topic_url;

				$footer_plain_default = str_replace( array_keys($replace), $replace, $footer_plain_default );
				$footer_html_default = str_replace( array_keys($replace), $replace, $footer_html_default );
								
                $footer_plain = !empty($this->messageFooterPlain) ? "\n\n".$this->messageFooterPlain."\n\n" : $footer_plain_default."\n";
                /**
                 * @see issue #12193
                 * @author Artem Sukharev
                 */
                if ( !Warecorp::checkHttpContext('zccr') ) {
                    $footer_plain .= Warecorp::t('You received this because you are subscribed to a discussion on %s. To manage your subscription click %s.', array(SITE_NAME_AS_STRING, $discussion_settings_url));
                }

                $footer_html = !empty($this->messageFooterHtml) ? '<br/><br/>'.$this->messageFooterHtml.'<br/><br/>' : $footer_html_default.'<br />';
                /**
                 * @see issue #12193
                 * @author Artem Sukharev
                 */
                if ( !Warecorp::checkHttpContext('zccr') ) {
                    $footer_html .= Warecorp::t('You received this because you are subscribed to a discussion on %s. To manage your subscription click <a href="%s">here</a>.', array(SITE_NAME_AS_STRING, $discussion_settings_url));
                }

                /**
                 * @see issue #12193
                 * @author Artem Sukharev
                 */
                $message_post_mailbbcontentplain = '';
                if ( !Warecorp::checkHttpContext('zccr') ) {
                    $message_post_mailbbcontentplain .= "Hello ".$recipient->getFirstName()." ".$recipient->getLastName()."\n ".$sender->getLogin()." has sent the following message:\n\n".$topic_url."\n\n";
                }
				if ( $this->message->getPost()->getFormat() == 'html' ) {
				    $message_post_mailbbcontentplain .= $this->message->getPost()->getTextContent();
				} else {
				    $message_post_mailbbcontentplain .= $this->message->getPost()->getMailBBContentPlain();
				}

                /**
                 * @see issue #12193
                 * @author Artem Sukharev
                 */
				$message_post_mailbbcontenthtml = '';
                if ( !Warecorp::checkHttpContext('zccr') ) {
                    $message_post_mailbbcontenthtml .= "Hello ".$recipient->getFirstName()." ".$recipient->getLastName()."<br/>".$sender->getLogin()." has sent the following message:<br/><br/><a href='".$topic_url."'>".$topic_url."</a><br/><br/>";
                }
				if ( $this->message->getPost()->getFormat() == 'html' ) {
				    $message_post_mailbbcontenthtml .= "<div>".$this->message->getPost()->getContent()."</div>";
				} else {
				    $message_post_mailbbcontenthtml .= "<div>".$this->message->getPost()->getBBContent()."</div>";
                }
				
                $params = new Warecorp_SOAP_Type_Params();
                $params->loadDefaultCampaignParams();
                $params->addParam('discussion_subscription_subject', $subscription_subject);
                $params->addParam('message_post_mailbbcontentplain', $message_post_mailbbcontentplain);
                $params->addParam('message_post_mailbbcontenthtml', $message_post_mailbbcontenthtml);
                $params->addParam('message_footer_plain', $footer_plain);
                $params->addParam('message_footer_html', $footer_html);
                $params->addParam('messages_plain', '');
                $params->addParam('messages_html', '');
                $params->addParam('topic_url', $topic_url);
                $params->addParam('settings_url', $settings_url);
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
}

?>
