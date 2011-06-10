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
 * @package    Warecorp_Poll_Collection
 * @copyright  Copyright (c) 2007
 * @author     Alexander Komarovski
 */

class BaseWarecorp_Poll_Collection
{
    public $_db;
    
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->_db = Zend_Registry::get("DB");
    }


    
    /**
     * Returns polls list
     *
     * @param integer $entity_id
     * @param integer $entity_type_id
     * @return array of object
     */
    public function getPolls($entity_id, $entity_type_id)
    {
        $sql = $this->_db->select()->from('zanby_poll__items', 'id')
        ->where('entity_id', $entity_id)
        ->where('entity_type_id', $entity_type_id)
        ->order('id');
        $polls = $this->_db->fetchCol($sql);

        foreach ($polls as &$poll)
        {
            $poll = new Warecorp_Poll($poll);
        }
        return $polls;
    }





   /* function get_inserted_polls($entity_id)
{
	$polls = array();
	$sql = 'SELECT * FROM {og_poll_insert} WHERE nid = '.abs(intval($entity_id)).' ORDER BY poll_id ASC';
	$query = db_query($sql);
	while($data = db_fetch_array($query)) {
		$polls[] = $data;
	}
	return $polls;
}*/





    /**
     * Returns polls content
     *
     * @param object $_page
     * @param unknown_type $index
     * @param unknown_type $entity_id
     * @param unknown_type $poll_id
     * @param unknown_type $type
     * @return array
     */
    public static function PollsGetContent($_page, $index, $entity_id, $poll_id, $type)
    {
        $ret = array();
        $java_script = "var element = DDCApp.getIndexByElementName('Element_".$index."');\n".
        "element.Data['poll_id'] = ".$poll_id.";\n".
        "element.Data['group_id'] = ".$entity_id.";\n".
        "element.Data['type'] = '".$type."';\n";

        
        //$poll = array();//print($poll_id);die;
        $poll = new Warecorp_Poll($poll_id);
        //print_r($poll);die;
        if ($type == 'edit')
        {
            if ($poll)
            {
                $java_script .= "element.Data['name_id'] = 'Element_".$index."';\n".
                "element.Data['index'] = ".$index.";\n".
                "element.Data['head_title'] = '".str_replace(array("\n", "\r"), "", $poll->title)."';\n".
                "element.Data['data'] = new Array();\n".
                "element.Data['settings'] = new Array();\n".
                "element.Data['settings']['view_result'] = ".$poll->poll_view_result.";\n".
                "element.Data['settings']['view_nomember'] = ".$poll->poll_view_nomember.";\n".
                "element.Data['settings']['vote_nomember'] = ".$poll->poll_vote_nomember.";\n";
                $questions = $poll->getQuestions();
                $i = 0;
                foreach($questions as $k => &$v)
                {
                    $java_script .= "element.Data['data'][".$i."] = new Array();\n".
                    "element.Data['data'][".$i."]['question'] = '".str_replace(array("\n", "\r"), "", $v->title)."';\n".
                    "element.Data['data'][".$i."]['answers'] = new Array();\n";

                    $answers = $v->getAnswers();
                    $a = 0;
                    foreach ($answers as $a_k => $a_v)
                    {
                        $java_script .= "element.Data['data'][".$i."]['answers'][".$a."] = '".str_replace(array("\n", "\r"), "", $a_v->title)."';\n";
                        ++$a;
                    }
                    ++$i;
                }
                $java_script .= "poll_load_content(".$index.");";
            }
        }

        $svars = array(
        'index'				=> $index,
        'rand_id'			=> Warecorp_DDPages::profilesAPI_register_code(),
        'type'				=> $type,
        'poll'				=> $poll,
        );
        
        $_page->Template->assign($svars);
        $ret['Content'] = $_page->Template->getContents('ddpages/polls/new_poll.tpl');
        
        $ret['java_script'] = $java_script;
        $ret['popup_head'] = '<span style="color:#fff;font-weight:bold;">Polls</span>';
        return $ret;
    }



    
    
    
    
    
    
    
    /*
    
    function add_poll($entity_id, $poll_id, $data)
{
	$ret = array();
	$poll = get_poll($entity_id, $poll_id);
	if ($poll)
	{
		$questions = get_questions($poll_id);
		foreach($questions as $k => $v)
		{
			$answers = get_answers($v['question_id']);
			if ($answers)
			{
				$ret[$k] = $questions[$k];
				$ret[$k]['answers'] = $answers;
			}
		}
	}
	delete_poll($entity_id, $poll_id);
	$sql = 'INSERT INTO {og_poll} 
			SET poll_id = '.$poll_id.', 
				nid = '.$entity_id.', 
				poll_title = "'.$data['head_title'].'", 
				poll_created = "'.time().'", 
				poll_view_result = '.($data['settings']['view_result']?1:0).', 
				poll_view_nomember = '.($data['settings']['view_nomember']?1:0).', 
				poll_vote_nomember = '.($data['settings']['vote_nomember']?1:0);

	$query = db_query($sql);
	foreach($data['data'] as $k=>$v)
	{
		$sql = 'INSERT INTO {og_poll_question} 
				SET poll_id = '.$poll_id.', 
					question_title = "'.$v['question'].'"'; 
		$query = db_query($sql);
		$question_id = mysql_insert_id();
		foreach ($v['answers'] as $k2 => $v2)
		{
			$sql = 'INSERT INTO {og_poll_answer} 
					SET question_id = '.$question_id.', 
						answer_title = "'.$v2.'", 
						answer_counter = '.($ret[$k]['answers'][$k2]['answer_counter']?$ret[$k]['answers'][$k2]['answer_counter']:0);
			$query = db_query($sql);
		}
	}
	return $poll_id;
}

function add_poll_vote($poll_id, $question_id, $answer_id)
{
	$sql = 'UPDATE {og_poll_answer} SET answer_counter =  answer_counter + 1 WHERE answer_id = '.abs(intval($answer_id)).' AND question_id = '.abs(intval($question_id));
	$query = db_query($sql);
	block_poll_cookies_voting($poll_id);
	block_poll_ip_voting($poll_id);
	return true;
}












function add_poll_insert2group($entity_id, $poll_id)
{
	$sql = 'INSERT INTO {og_poll_insert} SET nid = '.abs(intval($entity_id)).', poll_id = '.abs(intval($poll_id));
	$query = db_query($sql);
	return true;
}

function delete_poll_insert2group($entity_id)
{
	$sql = 'DELETE FROM {og_poll_insert} WHERE nid = '.abs(intval($entity_id));
	$query = db_query($sql);
	return true;
}*/

    
    
    
    
    
    
    
    


}
