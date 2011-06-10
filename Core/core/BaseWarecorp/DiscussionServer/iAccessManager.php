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
 * @created 27-Jun-2007 11:45:48
 */
interface BaseWarecorp_DiscussionServer_iAccessManager
{
    //*********************************************************************
    /**
     * Group Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь просматривать (видеть)
     * дискуссии группы (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewGroupDiscussions($group, $user_id);
    /**
     * проверяет, может ли пользователь осуществлять поиск
     * по группе, при проверке использует
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     */
    static public function canSearcGroupDiscussions($group, $user_id);
    /**
     * проверяет, может ли пользователь создавать сообщения (post)
     * для всех дискуссий/топиков группы (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canPostMessages($group, $user_id);
    /**
     * проверяет, может ли пользователь отвечать на сообщения (post)
     * для всех дискуссий/топиков группы (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canReplyMessages($group, $user_id);
    /**
     * проверяет, может ли пользователь редактировать свои сообщения
     * для всех дискуссий группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canEditOwnMessages($group, $user_id);
    /**
     * проверяет, может ли пользователь удалять свои сообщения
     * для всех дискуссий группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canDeleteOwnMessages($group, $user_id);
    /**
     * проверяет, может ли пользователь управлять настройками
     * пользователя для группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canConfigureSettings($group, $user_id);
    /**
     * проверяет, может ли пользователь управлять настройками хоста
     * для группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canConfigureHostSettings($group, $user_id);
    /**
     * проверяет, может ли пользователь помечать прочитанными все сообщения
     * для всех дискуссий/топиков группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canMarkGroupTopicsRead($group, $user_id);
    /**
     * проверяет, может ли пользователь видеть страницу
     * Recent Posts (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewRecentMessages($group, $user_id);
    /**
     * проверяет, может ли пользователь пользоваться RSS Feed
     * (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canGroupRSSFeed($group, $user_id);

    //*********************************************************************
    /**
     * Discussion Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь просматривать конкретную дискуссию
     * @param int|Discussion $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewDiscussion($discussion, $user_id);
    /**
     * проверяет, может ли пользователь создавать новые топики для
     * определенной дискуссии, при проверке учитывается
     * @param int $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canCreateDiscussionTopic($discussion, $user_id);
    /**
     * проверяет, может ли пользователь пометить топики дискуссии как прочтенные
     * @param int $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canMarkDiscussionTopicsRead($discussion, $user_id);
    /**
     * проверяет, может ли пользователь использовать rss дискуссии
     * @param int $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canDiscussionRSSFeed($discussion, $user_id);

    //*********************************************************************
    /**
     * Topic Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь подписываться на рассылку по топику
     * при проверке использует:
     * @param int $topic
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canNotifyTopic($topic, $user_id);
    /**
     * проверяет, может ли пользователь добавлять поста в
     * топик, при проверке использует
     * @param int $topic
     * @param int $user_id
     * @return boolean
     */
	static public function canReplyTopic($topic, $user_id);

    //*********************************************************************
    /**
     * Post Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь видеть пост
     * @param int $post
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
	static public function canViewPost($post, $user_id);
	/**
	 * проверяет, может ли пользователь добавлять новый ответ на поста
	 * при проверке использует
	 * @param int $post
	 * @param int $user_id
	 * @return boolean
	 */
	static public function canReplyPost($post, $user_id);
    /**
     * проверяет, может ли пользователь редактировать
     * конкретный пост, при проверке использует
     * @param int $post
     * @param int $user_id
     * @return boolean
     */
	static public function canEditPost($post, $user_id);
    /**
     * проверяет, может ли пользователь удалить
     * конкретный пост, при проверке использует
     * @param int $post
     * @param int $user_id
     * @return boolean
     */
	static public function canDeletePost($post, $user_id);
	/**
	 * проверяет, может ли пользователь отправлять отчет о посте
	 * @param int $post
	 * @param int $user_id
	 * @return boolean
	 */
	static public function canReportPost($post, $user_id);
	/**
	 * проверяет, может ли пользователь отправлять письмо
	 * непосредственно автору конкретного поста
	 * @param int $post
	 * @param int $user_id
	 * @return boolean
	 */
	static public function canEmailAuthorPost($post, $user_id);
}
?>
