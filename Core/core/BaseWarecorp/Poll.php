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
 *
 * @package    Warecorp_Poll
 * @copyright  Copyright (c) 2007
 * @author     Alexander Komarovski
 */

class BaseWarecorp_Poll extends Warecorp_Data_Entity
{
    public $id;
    public $entityId;
    public $entityTypeId;
    public $creationDate;
    public $title;
    public $pollType;
    
    public $poll_view_result = 0;
    public $poll_view_nomember = 0;
    public $poll_vote_nomember = 0;
    
    private $Questions = null;



    public function __construct($id = null)
    {
        parent::__construct('zanby_poll__items');

        $this->addField('id');
        $this->addField('entity_id', 'entityId');
        $this->addField('entity_type_id', 'entityTypeId');
        $this->addField('creation_date', 'creationDate');
        $this->addField('title');
        $this->addField('poll_type', 'pollType');


        if ($id !== null)
        {
            $this->pkColName = 'id';
            $this->loadByPk($id);
        }
    }





    public function setQuestions()
    {
        $select = $this->_db->select();
        $select->from('zanby_poll__questions', 'id')
        ->where('poll_id = ?', $this->id);
        $questions = $this->_db->fetchCol($select);

        foreach ($questions as &$question)
        {
            $question = new Warecorp_Poll_Question($question);
        }
        $this->Questions = $questions;
    }





    public function getQuestions()
    {
        if ( $this->Questions === null )
        {
            $this->setQuestions();
        }
        return $this->Questions;
    }





    function deleteQuestions()
    {
        $questions = $this->getQuestions();
        foreach($questions as &$_v)
        {
            $_v->deleteAnswers();
            $_v->delete;
        }
        return true;
    }




    
    function viewPollResult()
    {
        $ret = array();

        $questions = $this->getQuestions();

        foreach($questions as $k => &$v)
        {
            $answers = $v->getAnswers();
            if ($answers)
            {
                $ret[$k] = $questions[$k];
                $ret[$k]['answers'] = $answers;
            }
        }
        $smart_vars = array(
        'poll'		=> $poll,
        'questions'	=> $ret,
        'group_id'	=> $entity_id,
        'access_vote' => $this->checkPollAccessVoting(),
        );

        $_page = new Warecorp_Common_Page;
        $_page->Template->Assign($smart_vars);
        return $_page->Template->getContents("/ddpages/polls/view_poll_results.tpl");
    }





    public function viewPoll($xajax = false)//$entity_id)//, $poll_id, $xajax = false)
    {return '';
        if (!$this->checkPollAccessVoting())
        {
            return array('content'	=> $this->viewPollResult());
        }
        $java_script = '';
        $ret =  array();
        if (!$xajax)
        {
            $java_script .= "var poll_vote_".$poll_id." = new Array();\n";
        }
        $questions = $this->getQuestions();
        
        if (!empty($questions))
        foreach($questions as $k => &$v)
        {
            $answers = $v->getAnswers();
            if ($answers)
            {
                $java_script .= "poll_vote_".$this->id."[".$v->id."] = ".$answers[0]->id.";\n";
                $ret[$k] = $v;
                $ret[$k]['answers'] = $answers;
            }
        }
        $smart_vars = array(
        'poll'		=> $this,
        'questions'	=> $ret,
        'group_id'	=> $this->entityId,
        'java_script' => $java_script,
        'access_vote' => $this->checkPollAccessVoting(),
        );

        $_page = new Warecorp_Common_Page;
        $_page->Template->Assign($smart_vars);
        return $_page->Template->getContents("/ddpages/polls/view_poll.tpl");
        //return array('content'	=> theme("content","templates/ddpages/polls/view_poll", $smart_vars), 'java_script' => $java_script);
    }






    public function checkPollAccessVoting()
    {
        //check cookies blocking
        if (isset($_COOKIE['poll_voting'][$this->id]) && $_COOKIE['poll_voting'][$this->id])
        {
            $this->blockPollCookiesVoting();
            return false;
        }

        /*//check ip blocking
        $poll_voting_timeout = variable_get('poll_vote_timeout_in_day', 30)*86400;
        $time = time() - $poll_voting_timeout;
        //$sql = 'SELECT * FROM {og_poll_ban} WHERE poll_id = '.$poll_id.' AND ban_ip = "'.$_SERVER['REMOTE_ADDR'].'" AND ban_timestamp > '.$time;
        $sql = 'SELECT * FROM {og_poll_ban} WHERE poll_id = '.$poll_id.' AND ban_ip = "'.$_SERVER['REMOTE_ADDR'].'"';
        $query = db_query($sql);
        $ban_data = db_fetch_array($query);
        if ($ban_data)
        {
        return false;
        }*/
        return true;
    }





    function blockPollIpVoting()
    {
        //$sql = 'INSERT INTO {og_poll_ban} SET poll_id = '.$this->id.', ban_ip = "'.$_SERVER['REMOTE_ADDR'].'", ban_timestamp = '.time();
        //$query = db_query($sql);
        return true;
    }





    function blockPollCookiesVoting()
    {
        $poll_voting_timeout = POLL_VOTE_TIMEOUT_IN_DAY * 86400;
        setcookie ("poll_voting[".$this->id."]", 1,time()+$poll_voting_timeout, '/', '.'.SITE_NAME_AS_DOMAIN);
        return true;
    }




}
