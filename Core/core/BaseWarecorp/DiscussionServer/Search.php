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
 * @author Artem Sukharev
 * @version 1.0
 * @created 04-Jul-2007 14:05:40
 */
class BaseWarecorp_DiscussionServer_Search
{
	private $db = null;
	private $keyword = null;
	private $currentPage = null;
	private $listSize = null;
	private $groupId = null;
	private $order = null;
	private $caseSensitive = false;
    private $postsCount = null;
	function __construct()
	{
	    $this->db = Zend_Registry::get('DB');
	}
    /**
     * Enter description here...
     * @return string
     * @author Artem Sukharev
     */
	public function getKeyword()
	{
		return $this->keyword;
	}
    /**
     * return keyword used for like
     * @return string
     * @author Artem Sukharev
     */
	public function getPreparedKeyword()
	{
	    $keyword = $this->getKeyword();
        $keyword = str_replace("\\", "\\\\\\\\", $keyword);
        $keyword = str_replace("'", "\'", $keyword);
        $keyword = str_replace('%', '\%', $keyword);
        $keyword = str_replace('_', '\_', $keyword);
		return "%".$keyword."%";
	}
	/**
	 *
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setKeyword($newVal)
	{
		$this->keyword = $newVal;
		return $this;
	}
    /**
     * Enter description here...
     * @return string
     * @author Artem Sukharev
     */
	public function getCurrentPage()
	{
		return $this->currentPage;
	}
	/**
	 *
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setCurrentPage($newVal)
	{
		$this->currentPage = $newVal;
		return $this;
	}
    /**
     * Enter description here...
     * @return string
     * @author Artem Sukharev
     */
	public function getListSize()
	{
		return $this->listSize;
	}
	/**
	 *
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setListSize($newVal)
	{
		$this->listSize = $newVal;
		return $this;
	}
    /**
     * Enter description here...
     * @return array of Warecorp_DiscussionServer_Post
     * @author Artem Sukharev
     */
	public function getGroupId()
	{
		return $this->groupId;
	}
	/**
	 *
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setGroupId($newVal)
	{
		$this->groupId = $newVal;
		return $this;
	}
    /**
     * Enter description here...
     * @return array of Warecorp_DiscussionServer_Post
     * @author Artem Sukharev
     */
	public function getOrder()
	{
		return $this->order;
	}
	/**
	 *
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setOrder($newVal)
	{
		$this->order = $newVal;
		return $this;
	}
    /**
     * Enter description here...
     * @return array of Warecorp_DiscussionServer_Post
     * @author Artem Sukharev
     */
	public function getCaseSensitive()
	{
		return (boolean) $this->caseSensitive;
	}
	/**
	 *
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setCaseSensitive($newVal)
	{
		$this->caseSensitive = $newVal;
		return $this;
	}
    /**
     * Enter description here...
     * @return array of Warecorp_DiscussionServer_Post
     * @author Artem Sukharev
     */
	public function findPostsByKeyword()
	{
        
        if (WITH_SPHINX){
            $cl = new Warecorp_Data_Search();
            $cl->init('discussion');     
            if ($this->getPreparedKeyword()) $query =  str_replace('%','',$this->getPreparedKeyword());
            if ( $this->getGroupId() !== null ) {
                if (!is_array($this->getGroupId())) $this->setGroupId(array($this->getGroupId()));
                $cl->setFilter('group_id', $this->getGroupId());
            }
            if (EI_FILTER_ENABLED){
                $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) )); 
            }
            if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $cl->setFilter('is_blog', array(0)); 
            $cl->Query($query);
            $posts = $cl->getResultPairs();
            
            $this->postsCount = count($posts ); 
            $posts = array_slice ( $posts, ($this->getCurrentPage()-1)* $this->getListSize() ,  $this->getListSize(), true);
            
        }
        else{
	        $query = $this->db->select();
	        $query->from(array('zdp' => 'zanby_discussion__posts'), 'zdp.post_id')
	              ->join(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
	              ->join(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id');
	        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
            if ( $this->getCaseSensitive() ) $query->where("zdp.content LIKE BINARY '".$this->getPreparedKeyword()."'");
	        else $query->where("zdp.content LIKE '".$this->getPreparedKeyword()."'");
	        if ( $this->getGroupId() !== null ) {
                if (!is_array($this->getGroupId())) $this->setGroupId(array($this->getGroupId()));
                $query->where('zdd.group_id IN (?)', $this->getGroupId());
	        }
	        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	           $query->limitPage($this->getCurrentPage(), $this->getListSize());
	        }
	        if ( $this->getOrder() !== null ) $query->order = $this->getOrder();
	        else $query->order("zdd.position ASC");

	        $posts = $this->db->fetchCol($query);
        }
        
	    if ( sizeof($posts) != 0 ) {
	       foreach ( $posts as &$post ) {
	           $post = new Warecorp_DiscussionServer_Post($post);
	           $post->setContent($this->highlightKeyword($post->getContent()));
	       }

	    }
	    return $posts;
	}
	public function countPostsByKeyword()
	{
        if (WITH_SPHINX){
            if ($this->postsCount === null) $this->findPostsByKeyword();
            return $this->postsCount;             
        }
        else{
	        $query = $this->db->select();
	        $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('count(zdp.post_id)'))
	              ->join(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
	              ->join(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id');
	        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
            if ( $this->getCaseSensitive() ) $query->where("zdp.content LIKE BINARY '".$this->getPreparedKeyword()."'");
	        else $query->where("zdp.content LIKE '".$this->getPreparedKeyword()."'");

	        if ( $this->getGroupId() !== null ) {
                if (!is_array($this->getGroupId())) $this->setGroupId(array($this->getGroupId()));
                $query->where('zdd.group_id IN (?)', $this->getGroupId());
	        }
	        $posts = $this->db->fetchOne($query);
	        return ($posts);
        }
	}
	public function highlightKeyword($content, $keywords = null)
	{
        if ( null !== $keywords )
            $k = preg_quote($keywords);
        else
            $k = preg_quote($this->getKeyword());
        $k = str_replace("/", "\/", $k);
        $format = defined("DISCUSSION_MODE") ? DISCUSSION_MODE : "bbcode";
        if ( $format == 'bbcode' ) $content = preg_replace('/('.$k .')/smi', '[font color=#F58832][b]\\1[/b][/font]', $content);
        elseif ( $format == 'html' ) $content = preg_replace('/('.$k .')/smi', '<font color=#F58832><b>\\1</b></font>', $content);
        return $content;
	}

    public function searchByCriterions( $params )
    {
        if ( WITH_SPHINX ) {
            $cl = new Warecorp_Data_Search();
            $cl->init('discussion');
            $query = "";

            if (EI_FILTER_ENABLED)
                $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));

            if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() )
                $cl->setFilter('is_blog', array(0));

            if ( isset($params['city']) ) {
                if ( !is_array($params['city']) && $params['city'] === 0 ) {
                    $cl->SetFilter ( "city_id", array(0) );
                }
                else {
                    if ( is_array($params['city']) && count($params['city']) > 1 ){
                        $primaryCity = current($params['city']);
                        $cl->SetFilter ( "city_id", $params['city'] );
                    }
                    else {
                        if (is_array($params['city'])) {
                            $primaryCity = current($params['city']);
                        } else {
                            $primaryCity = $params['city'];
                        }
                    }
                    $City = Warecorp_Location_City::create($primaryCity);
                    $City->setLatitudeLongitude();
                    $latitude  = deg2rad( $City->getLatitude()  );
                    $longitude = deg2rad( $City->getLongitude() );
                    // set geo anchor to current city coordinates
                    // it's necessary for creating geodistance order
                    $cl->SetFilterGeo('latitude', 'longitude', floatval($latitude), floatval($longitude), (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 )*1000 );
                }
            } elseif ( isset($params['state']) ) {
                $cl->SetFilter ( "state_id", array( $params['state'] ) );
            } elseif ( isset($params['country']) ) {
                $cl->SetFilter ( "country_id", array( $params['country'] ) );
            } elseif ( isset($params['where']) ) {
                $location = &$params['where'];
                if ( isset($location['city']) && is_numeric($location['city']) )
                    $cl->SetFilter('city_id', array($location['city']));
                if ( isset($location['state']) && is_numeric($location['state']) )
                    $cl->SetFilter('state_id', array($location['state']));
                if ( isset($location['country']) && is_numeric($location['country']) )
                    $cl->SetFilter('country_id', array($location['country']));
            }

            if ( !empty($params['keywords']) ) {
                if ( is_array($params['keywords']) )
                    $query = implode(" ", $params['keywords']);
                else
                    $query = $params['keywords'];
            }
            $cl->Query($query);
            $cl->SetSort('position ASC, topic_id ASC, created ASC');

            return $cl->getResultPairs();
        }
        throw new Zend_Exception('Sphinx is disabled');
    }
}
?>
