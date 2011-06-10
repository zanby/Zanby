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
 * WARECORP framework
 * @package Warecorp_DiscussionServer
 * @author Artem Sukharev
 */

require_once PEAR_DIR.'Mail_Mime'.DIRECTORY_SEPARATOR.'mimeDecode.php';

class BaseWarecorp_DiscussionServer_MailParser
{
    const EMAIL_REGEX           = '/[\w&\'\*\+-\.\/=\?\^\{\}\~]+@[\w\.\-]+/i';
    const SUBJECT_TOPIC_INDEX   = '\s*\((\d+)\)';

    private $db;
    private $mailContent;
    private $mailParsedContent;
    private $textPart = null;
    private $htmlPart = null;
    private $parts = array();
    private $headers = null;
       
    /**
     * Constructor
     * @param string $mailContent - mime mail message
     * @author Artem Sukharev
     */
	function __construct($mailContent)
	{
	    $this->db = Zend_Registry::get('DB');
	    $this->mailContent = $mailContent;

	    $mail = new Mail_mimeDecode($mailContent);
        $params['include_bodies']   = true;
        $params['decode_bodies']    = true;
        $params['decode_headers']   = false;
        $this->mailParsedContent    = $mail->decode($params);

        $this->parsePart($this->mailParsedContent);
        $this->parseHeaders();        
	}

    /**
     * isGroupEmail
     * 
     * @param string $email 
     * @access public
     * @return bool
     * @author Roman Gabrusenok
     */
    public function isGroupEmail( $email )
    {
        if ( preg_match(Warecorp_DiscussionServer_MailParser::EMAIL_REGEX, $email, $matched) ) {
            $email = $matched[0];

            if ( !is_string($email) )
                return false;
            if ( FALSE === ($domain = substr($email, strpos($email, '@')+1)) )
                return false;
            if ( strcmp(strtolower(DOMAIN_FOR_GROUP_EMAIL), strtolower($domain)) !== 0 )
                return false;
            return true;
        }
        return false;
    }
    
    /**
     * return text/plain part of message
     * @return string
     * @author Artem Sukharev
     */
	public function getTextPart()
    {
        return $this->textPart;
    }
    
    /**
     * return text/html part of message
     * @return string
     * @author Artem Sukharev
     */
    public function getHtmlPart()
    {
        return $this->htmlPart;
    }
    
    /**
     * return parts of message
     * @return array
     * @author Artem Sukharev
     */
    public function getOtherParts()
    {
        return $this->parts;
    }
    
    /**
     * return object of headers of message
     *     - to
     *     - from
     *     - subject
     *     - date
     * @return object
     * @author Artem Sukharev
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    /**
     * @param object $headers
     * @return void
     * @author Roman Gabrusenok
     */
    public function setHeaders( $headers )
    {
        $this->headers = $headers;
    }
    
    /**
     * return message content, if exists plain part - return it, else return html part
     * convert message content to UTF-8 encoding
     * @return string
     * @author Artem Sukharev
     */
    public function getContent()
    {
        $content = "";
        /*
        if ( $this->getTextPart() !== null && trim($this->getTextPart()->body) != "" ) {
            $content = $this->getTextPart()->body;
            $content = iconv($this->getTextPart()->charset, "UTF-8", $content);
        } elseif ( $this->getHtmlPart() !== null && trim($this->getHtmlPart()->body) != "" ) {
            $content = $this->prepareHtml($this->getHtmlPart()->body);
            $content = iconv($this->getHtmlPart()->charset, "UTF-8", $content);
        }
        */
        if ( $this->getHtmlPart() !== null && trim($this->getHtmlPart()->body) != "" ) {
            $content = $this->prepareHtml($this->getHtmlPart()->body);
            $content = iconv($this->getHtmlPart()->charset, "UTF-8", $content);
        } elseif ( $this->getTextPart() !== null && trim($this->getTextPart()->body) != "" ) {
            $content = nl2br($this->getTextPart()->body);
            $content = iconv($this->getTextPart()->charset, "UTF-8", $content);
        }        
        return $content;
    }
    
    /**
     * prepare html message part for output, convert html tags to bbcode tags
     * @param string $html - html message
     * @return string
     * @author Artem Sukharev
     */
    static public function prepareHtml($html)
    {        
        if ( !defined("DISCUSSION_MODE") || DISCUSSION_MODE == 'bbcode' ) {
            return self::prepareHtmlToBBcode($html);
        } else {
            return self::prepareHtmlToHtml($html);
        }
    }
    
    
    /**
     * prepare html message part for output, convert html tags to bbcode tags
     * @param string $html - html message
     * @return string
     * @author Artem Sukharev
     */
    static public function prepareHtmlToBBcode($html)
    {        
        $new_line = "\n";

        $html = preg_replace("/\r/mi",                                      "", $html);
        $html = preg_replace("/\n/mi",                                      "", $html);
        $html = preg_replace('/(\s){2,}/mi',                                " ", $html);
        $html = preg_replace('/<![^>]*>/smi',                               "", $html );
        $html = preg_replace('/<!--.*?-->/smi',                             "", $html );
        $html = preg_replace('/<head>.*<\/head>/smi',                       "", $html );
        $html = preg_replace('/<style([^>]*)>.*<\/style>/smi',              "", $html );
        $html = preg_replace('/<script([^>]*)>.*<\/script>/smi',            "", $html );
        $html = preg_replace('/<html[^>]*>/mi',                             "", $html );
        $html = preg_replace('/<\/html[^>]*>/mi',                           "", $html );
        $html = preg_replace('/<body[^>]*>/mi',                             "", $html );
        $html = preg_replace('/<\/body[^>]*>/mi',                           "", $html );

        /**
         * @todo расширить функционал
         */
        $html = preg_replace('/\s*<table([^>]*)>/mi',                          "[table]", $html);
        $html = preg_replace('/\s*<\/table>/mi',                               "[/table]", $html);
        $html = preg_replace('/\s*<tr([^>]*)>/mi',                             "[tr]", $html);
        $html = preg_replace('/\s*<\/tr>/mi',                                  "[/tr]", $html);
        $html = preg_replace('/\s*<td([^>]*)>/mi',                             "[td]", $html);
        $html = preg_replace('/\s*<\/td>/mi',                                  "[/td]", $html);


        preg_match_all('/(<a[^>]*>)(.*?)<\/a>/mi', $html, $amatches);
        if ( isset($amatches[1]) && sizeof($amatches[1]) != 0 ) {
            foreach ( $amatches[1] as $_ind => $match ) {
                $string = "[url";
                if ( preg_match_all('/href="([^"]*)"/mi', $match, $href) ) {
                    $string .= "=".$href[1][0];
                } elseif ( preg_match_all("/href='([^']*)'/mi", $match, $href) ) {
                    $string .= "=".$href[1][0];
                } elseif ( preg_match_all("/href=([^\s>]*)/mi", $match, $href) ) {
                    $string .= "=".$href[1][0];
                }
                $string .= " target=_blank]".$amatches[2][$_ind]."[/url]";
                $html = str_replace($amatches[0][$_ind], $string, $html);
            }
        }

        preg_match_all('/<img[^>]*>/mi', $html, $imatches);
        if ( isset($imatches[0]) && sizeof($imatches[0]) != 0 ) {
            foreach ( $imatches[0] as $_ind => $match ) {
                $imgSrc    = null;
                $imgWidth  = null;
                $imgHeigth = null;
                if ( preg_match_all('/src="([^"]*)"/mi', $match, $src) ) {
                    $imgSrc = $src[1][0];
                } elseif ( preg_match_all("/src='([^']*)'/mi", $match, $src) ) {
                    $imgSrc = $src[1][0];
                } elseif ( preg_match_all("/src=([^\s>]*)/mi", $match, $src) ) {
                    $imgSrc = $src[1][0];
                }
                if ( $imgSrc !== null ) {
                    if ( preg_match('/^cid:(.*)$/i', $imgSrc, $srcMatch) ) {
                        /**
                         * @todo в будущем надо будет сохранять это и ставить на него линк
                         */
                        $imgSrc = "";
                    } elseif (preg_match('/^http:\/\/(.*)$/i', $imgSrc, $srcMatch)) {
                        /**
                         * @todo думаю надо сохранять на сервер картинки и их использовать
                         * уменьшение картинок надо делать при выводе на самом штмл
                         */
                        /*
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $imgSrc);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $out = curl_exec($ch); 
                        curl_close($ch);
                        */
                        /*
                        $imgWidth = 100;
                        $imgHeigth = 100;
                        */
                    } else {
                        $imgSrc = "";
                    }

                    $string = '[img';
                    if ( $imgWidth ) {
                        $string .= ' width='.$imgWidth;
                    } else {
                        if ( preg_match_all('/width="([^"]*)"/mi', $match, $width) ) {
                            $string .= ' width='.$width[1][0];
                        } elseif ( preg_match_all("/width='([^']*)'/mi", $match, $width) ) {
                            $string .= ' width='.$width[1][0];
                        } elseif ( preg_match_all("/width=([^\s>]*)/mi", $match, $width) ) {
                            $string .= ' width='.$width[1][0];
                        }
                    }
                    if ( $imgHeigth ) {
                        $string .= ' height='.$imgHeigth;
                    } else {
                        if ( preg_match_all('/height="([^"]*)"/mi', $match, $height) ) {
                            $string .= ' height='.$height[1][0];
                        } elseif ( preg_match_all("/height='([^']*)'/mi", $match, $height) ) {
                            $string .= ' height='.$height[1][0];
                        } elseif ( preg_match_all("/height=([^\s>]*)/mi", $match, $height) ) {
                            $string .= ' height='.$height[1][0];
                        }
                    }
                    $string .= ']';
                    $string .= $imgSrc."[/img]";

                    $html = str_replace($imatches[0][$_ind], $string, $html);
                    
                }                   
            }
        }
        
        /**
         * @todo FONT - не реализован
         */
        
        /**
         * @todo QUOTE
         */
        
        $html = preg_replace('/<center[^>]*>/mi',                           "[align=center]", $html);
        $html = preg_replace('/<\/center[^>]*>/mi',                         "[/align]", $html);

        $html = preg_replace('/<br[^>]*>/mi',                               $new_line, $html);
        $html = preg_replace('/<hr[^>]*>/mi',                               "[hr]", $html);

        $html = preg_replace('/<b[^>]*>/mi',                                "[b]", $html);
        $html = preg_replace('/<\/b[^>]*>/mi',                              "[/b]", $html);
        $html = preg_replace('/<strong[^>]*>/mi',                           "[b]", $html);
        $html = preg_replace('/<\/strong[^>]*>/mi',                         "[/b]", $html);

        $html = preg_replace('/<i[^>]*>/mi',                                "[i]", $html);
        $html = preg_replace('/<\/i[^>]*>/mi',                              "[/i]", $html);
        $html = preg_replace('/<em[^>]*>/mi',                               "[i]", $html);
        $html = preg_replace('/<\/em[^>]*>/mi',                             "[/i]", $html);
//
        /**
         * @todo надо смотреть выравнивание и подставлять их 
         */
        $html = preg_replace('/<p[^>]*>/mi',                                "[align]", $html);
        $html = preg_replace('/<\/p[^>]*>/mi',                              "[/align]", $html);
        $html = preg_replace('/<div[^>]*>/mi',                              "[align]", $html);
        $html = preg_replace('/<\/div[^>]*>/mi',                            "[/align]", $html);
        $html = preg_replace('/<span[^>]*>/mi',                             "", $html);
        $html = preg_replace('/<\/span[^>]*>/mi',                           "", $html);


        $html = preg_replace('/<ul[^>]*>/mi',                               "[list]", $html);
        $html = preg_replace('/<\/ul[^>]*>/mi',                             "[/list]", $html);
        $html = preg_replace('/<li[^>]*>/mi',                               "[*]", $html);
        $html = preg_replace('/<\/li[^>]*>/mi',                             "", $html);


        $html = preg_replace('/<h1[^>]*>/mi',                               "[h1]", $html);
        $html = preg_replace('/<\/h1[^>]*>/mi',                             "[/h1]", $html);
        $html = preg_replace('/<h2[^>]*>/mi',                               "[h2]", $html);
        $html = preg_replace('/<\/h2[^>]*>/mi',                             "[/h2]", $html);
        $html = preg_replace('/<h3[^>]*>/mi',                               "[h3]", $html);
        $html = preg_replace('/<\/h3[^>]*>/mi',                             "[/h3]", $html);
        $html = preg_replace('/<h4[^>]*>/mi',                               "[h3]", $html);
        $html = preg_replace('/<\/h4[^>]*>/mi',                             "[/h3]", $html);
        $html = preg_replace('/<h5[^>]*>/mi',                               "[h3]", $html);
        $html = preg_replace('/<\/h5[^>]*>/mi',                             "[/h3]", $html);
        $html = preg_replace('/<h6[^>]*>/mi',                               "[h3]", $html);
        $html = preg_replace('/<\/h6[^>]*>/mi',                             "[/h3]", $html);

        $html = preg_replace('/<\?php(.*?)\?>/mi',                          "[php]\\1[/php]", $html);
        $html = preg_replace('/<code[^>]*>/mi',                             "[code]", $html);
        $html = preg_replace('/<\/code[^>]*>/mi',                           "[/code]", $html);
        $html = str_replace('&nbsp;',                                       " ", $html);

        $html = preg_replace('/<[^>]*>/mi', "", $html);

        $html = trim($html);

        //need to correct displaing special simbols html equivalents 
        //added by Saharchuk Timofei              
        $html = htmlspecialchars_decode($html, ENT_QUOTES);       

        return $html;
    }

    /**
     * prepare html message part for output, convert html tags to bbcode tags
     * @param string $html - html message
     * @return string
     * @author Artem Sukharev
     */
    static public function prepareHtmlToHtml($html)
    {        
        $new_line = "\n";

        $html = preg_replace("/\r/mi",                                      "", $html);
        $html = preg_replace("/\n/mi",                                      "", $html);
        $html = preg_replace('/<![^>]*>/smi',                               "", $html );
        $html = preg_replace('/<!--.*?-->/smi',                             "", $html );
        $html = preg_replace('/<head>.*<\/head>/smi',                       "", $html );
        $html = preg_replace('/<style([^>]*)>.*<\/style>/smi',              "", $html );
        $html = preg_replace('/<script([^>]*)>.*<\/script>/smi',            "", $html );
        $html = preg_replace('/<html[^>]*>/mi',                             "", $html );
        $html = preg_replace('/<\/html[^>]*>/mi',                           "", $html );
        $html = preg_replace('/<body[^>]*>/mi',                             "", $html );
        $html = preg_replace('/<\/body[^>]*>/mi',                           "", $html );      
        $html = preg_replace('/<blockquote[^>]*>/mi',                       "", $html );
        $html = preg_replace('/<\/blockquote[^>]*>/mi',                     "", $html );      
        
        return $html;
    }
    
    /**
     * extract headers values from mime message
     *     - to
     *     - from
     *     - subject
     *     - date 
     * @return object
     * @author Artem Sukharev
     */
    private function parseHeaders()
    {    	
        $this->headers->subject = $this->mailParsedContent->headers['subject'];
        if ( $this->getTextPart() !== null && $this->getTextPart()->charset ) {
            $this->headers->subject = iconv_mime_decode($this->headers->subject, 0, $this->getTextPart()->charset);
            $this->headers->subject = iconv($this->getTextPart()->charset, "UTF-8", $this->headers->subject);
        } elseif ( $this->getHtmlPart() !== null && $this->getHtmlPart()->charset ) {
            $this->headers->subject = iconv_mime_decode($this->headers->subject, 0, $this->getHtmlPart()->charset);
            $this->headers->subject = iconv($this->getHtmlPart()->charset, "UTF-8", $this->headers->subject);
        }
        $this->headers->to = $this->mailParsedContent->headers['to'];
        if ( ($cc = trim($this->mailParsedContent->headers['cc'])) !== '' ) {
            $this->headers->to .= ', '.$cc;
        }
        $this->headers->from = $this->mailParsedContent->headers['from'];
        $this->headers->date = $this->mailParsedContent->headers['date'];
        /*
        $this->headers->date = new Zend_Date($this->mailParsedContent->headers['date'], Zend_Date::RFC_2822);
        $date = new Zend_Date($this->mailParsedContent->headers['date'], Zend_Date::RFC_2822);
        $date->setTimezone('UTC');
        $this->headers->date_utc = $date;
        */
    }
    
    private function parsePart($part)
    {
        if ( isset($part->parts) ) {
            foreach ( $part->parts as $_part ) {
                $this->parsePart($_part);
            }
        } else {
            if ( $part->ctype_primary == 'text' ) {
                if ( $part->ctype_secondary == 'plain' ) {
                    $this->textPart = new stdClass();
                    $this->textPart->body = $part->body;
                    $this->textPart->charset = ( isset($part->ctype_parameters) && isset($part->ctype_parameters['charset']) ) ? $part->ctype_parameters['charset'] : 'utf-8';
                } elseif ( $part->ctype_secondary == 'html' ) {
                    $this->htmlPart = new stdClass();
                    $this->htmlPart->body = $part->body;
                    $this->htmlPart->charset = ( isset($part->ctype_parameters) && isset($part->ctype_parameters['charset']) ) ? $part->ctype_parameters['charset'] : 'utf-8';
                }
            } else {
                $this->parts[] = $part;
            }
        }
    }
    
    /**
     * Searches user id who sent message.
     *
     * @return int id of user or null if user not found.
     */
    public function getAuthorId()
    {
        $senderEmail = $this->getAuthorEmail();
        
        //start search
        $userSearch = new Warecorp_User_Search();
        return $userSearch->searchByEmail($senderEmail);
    }
    
    /**
     * Extracts email address from headers->from
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        //get senders's email (see http://www.remote.org/jochen/mail/info/chars.html)
        if ( !preg_match(Warecorp_DiscussionServer_MailParser::EMAIL_REGEX, $this->headers->from, $matched) )
            throw new Exception('Error extracting sender\'s email addres from '.$this->headers->from);
            
        return $matched[0];
    }
    
    /**
     * Searches for topic in given discussion
     *
     * @param Warecorp_DiscussionServer_Discussion $discussion
     * @return int or null if not found
     */
    public function getTopicId($discussion)
    {
        $topics = $discussion->getTopics();
        $topicName = $this->getTopicName($index, $isReply);
        
        //make index zero-based
        //if ( isset($index) ) $index -= 1;
        
        $topic = $topics->findByTopicNameInDiscussion($topicName, $discussion->getId(), $index);
        
        return isset($topic) ? $topic->getId() : null;
    }
    
    /**
     * Parses subject of email and returns its parts.
     * See cfg.discussion.xml for expressions that parse subjects.
     *
     * @param int $index out parameter: index of topic (beginning from 1) or null if not specified
     * @param book $isReply out parameter: true if subject contains reply sign
     * @return string extracted topic name
     */
    public function getTopicName(&$index, &$isReply)
    {
        $subjectPart = trim($this->headers->subject);
        
        /**
         * load reply-detecting expressions from configuration file
         */
        $replyRegex = array();          //  array of regular expressions that check reply
        $replyRegexIndex = array();     //  array of regular expressions that check reply with topic index
        $matchGroupIndex = array();     //  array of group indexes in $replyRegex where topic name is placed

        /**
         * Try load Discussion Cfg from implementation
         * if false - load from core
         * @author Artem Sukharev
         * @see issue #12193
         */
        if ( file_exists(CONFIG_DIR.DIRECTORY_SEPARATOR.'cfg.discussion.xml') ) {
            $cfgDiscussion = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.discussion.xml');
        } else {
            $cfgDiscussion = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.discussion.xml');
        }

        foreach ($cfgDiscussion->reply_expressions as $replyExpression) {
            //prepare expressions
            $replyRegex[] = '/^'.$replyExpression->regex_part.'$/i';
            $replyRegexIndex[] = '/^'.$replyExpression->regex_part.Warecorp_DiscussionServer_MailParser::SUBJECT_TOPIC_INDEX.'$/i';
            $matchGroupIndex[] = intval($replyExpression->topic_name_group_index);
        }
        
        $topicName = null;
        
        /**
         * step1: check for reply with topic index
         */
        for ($i = 0; $i < count($replyRegexIndex); $i++) {
            if (preg_match($replyRegexIndex[$i], $subjectPart, $matched)) {
                $topicName = trim($matched[$matchGroupIndex[$i]]);      //use predefined index of group
                $index = $matched[count($matched) - 1];                 //use last group
                $isReply = true;
                break;
            }
        }
        
        /**
         * step2: if not mathed any pattern then check for reply without topic index
         */
        if ( !isset($topicName) ) {
            for ($i = 0; $i < count($replyRegex); $i++) {
                if (preg_match($replyRegex[$i], $subjectPart, $matched)) {
                    $topicName = trim($matched[$matchGroupIndex[$i]]);//use predefined index of group
                    $index = null;
                    $isReply = true;
                    break;
                }
            }
        }
        
        /**
         * step3: if not mathed any pattern then check for non-reply
         */
        if ( !isset($topicName) ) {
            //prepare expressions
            $replyExpression = $cfgDiscussion->new_topic_expression;
            $replyRegex = '/^'.$replyExpression->regex_part.'$/i';
            $replyRegexIndex = '/^'.$replyExpression->regex_part.Warecorp_DiscussionServer_MailParser::SUBJECT_TOPIC_INDEX.'$/i';
            $matchGroupIndex = intval($replyExpression->topic_name_group_index);
            
            if (preg_match($replyRegexIndex, $subjectPart, $matched)) {
                $topicName = trim($matched[$matchGroupIndex]);
                $index = $matched[count($matched) - 1];
                $isReply = false;
            } elseif (preg_match($replyRegex, $subjectPart, $matched)) {
                $topicName = trim($matched[$matchGroupIndex]);
                $index = null;
                $isReply = false;
            }
        }
        
        if (!isset($topicName))
            throw new Exception('Error extracting topic from '.$this->headers->subject);

        return $topicName;
    }
    
    public function getDiscussion(&$emailIsObsolete)
    {
        /**
         * get recipient's email (see http://www.remote.org/jochen/mail/info/chars.html)
         */
        if ( !preg_match(Warecorp_DiscussionServer_MailParser::EMAIL_REGEX, $this->headers->to, $matched) )
            throw new Exception('Error extracting recipient\'s email addres from '.$this->headers->to);
            
        $recipientEmail = $matched[0];
        /**
         * FIXME заменяется временно для локальных мыл, потом надо это убрать
         */
        $recipientEmail = preg_replace('/@discussions\.zanby\.buick$/i', '@'.DOMAIN_FOR_GROUP_EMAIL, $recipientEmail);
        
        /**
         * search discussion by its eamil
         */
        $discussionList = new Warecorp_DiscussionServer_DiscussionList();
        $discussion = $discussionList->findByFullEmail($recipientEmail);
        
        if ( !isset($discussion) ) {
            /**
             * search discussion in obsolete emails
             */
            $discussion = $discussionList->findByObsoleteFullEmail($recipientEmail);
            $emailIsObsolete = true;
        } else
            $emailIsObsolete = false;
            
        return $discussion;
    }
    
    public function getOriginalMessageAsPlainText()
    {
        return 'From: '.$this->headers->from."\n".
               'To: '.$this->headers->to."\n".
               'Subject: '.$this->headers->subject."\n".
               "\n".$this->getContent();
    }
}
?>
