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
 * @package    Warecorp_Poll_Question
 * @copyright  Copyright (c) 2007
 * @author     Alexander Komarovski
 */

class BaseWarecorp_Poll_Question extends Warecorp_Data_Entity
{
    public $id;
    public $pollId;
    public $title;

    private $Answers = null;



    public function __construct($id = null)
    {
        parent::__construct('zanby_poll__questions');

        $this->addField('id');
        $this->addField('poll_id', 'pollId');
        $this->addField('title');

        if ($id !== null)
        {
            $this->pkColName = 'id';
            $this->loadByPk($id);
        }
    }





    public function setAnswers()
    {
        $select = $this->_db->select();
        $select->from('zanby_poll__answers', 'id')
        ->where('question_id = ?', $this->id);
        $answers = $this->_db->fetchCol($select);

        foreach ($answers as &$answer)
        {
            $answer = new Warecorp_Poll_Answer($answer);
        }
        $this->Answers = $answers;
    }





    public function getAnswers()
    {
        if ( $this->Answers === null )
        {
            $this->setAnswers();
        }
        return $this->Answers;
    }





    public function deleteAnswers()
    {
        $answers = $this->getAnswers();
        foreach ($answers as &$_v)
        {
            $_v->delete();
        }

        return true;
    }


}
