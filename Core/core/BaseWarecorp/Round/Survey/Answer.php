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

class BaseWarecorp_Round_Survey_Answer extends Warecorp_Data_Entity
{
    private $id;
    private $roundId;
    private $surveyCompleteDate;
    private $surveyReturnedStatus;
    private $surveyReturnedAnswerId;
    private $user;

    public function setId ( $value ) {
        $this->id = $value;
        return $this;
    }
    public function getId () {
        return $this->id;
    }
    public function setRoundId ( $value ) {
        $this->roundId = $value;
        return $this;
    }
    public function getRoundId () {
        return $this->roundId;
    }
    public function setSurveyCompleteDate ( $value ) {
        $this->surveyCompleteDate = $value;
        return $this;
    }
    public function getSurveyCompleteDate () {
        return $this->surveyCompleteDate;
    }
    public function setSurveyReturnedStatus ( $value ) {
        $this->surveyReturnedStatus = $value;
        return $this;
    }
    public function getSurveyReturnedStatus () {
        return $this->surveyReturnedStatus;
    }
    public function setSurveyReturnedAnswerId ( $value ) {
        $this->surveyReturnedAnswerId = $value;
        return $this;
    }
    public function getSurveyReturnedAnswerId () {
        return $this->surveyReturnedAnswerId;
    }
    public function setUserId ( $value ) {
        $this->user = null;
        $this->userId = $value;
        return $this;
    }
    public function getUserId () {
        return $this->userId;
    }
    public function getUser() {
        if ( null == $this->user ) {
            $this->user = new Warecorp_User($this->getUserId());
        }
        return $this->user;
    }

    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct($id = null)
    {
        parent::__construct('zanby_rounds__survey_answers');

        $this->addField('id', 'id');
        $this->addField('round_id', 'roundId');
        $this->addField('survey_complete_date', 'surveyCompleteDate');
        $this->addField('survey_returned_status', 'surveyReturnedStatus');
        $this->addField('survey_returned_answer_id', 'surveyReturnedAnswerId');
        $this->addField('user_id', 'userId');

        if ($id !== null){
            $this->pkColName = 'id';
            $this->load($id);
        }
    }

    static public function getAnswerByRoundAndUser($roundID, $userID)
    {
        if ( !$roundID ) throw new Exception('Incorrect round ID');
        if ( !$userID ) return null;

        $dbConn = Zend_Registry::get("DB");
        $query = $dbConn->select();
		$query->from('zanby_rounds__survey_answers', array('id'));
        $query->where('round_id = ?', $roundID);
        $query->where('user_id = ?', $userID);
		$id = $dbConn->fetchOne($query);
        if ( $id ) return new Warecorp_Round_Survey_Answer($id);
        return null;
    }
}
