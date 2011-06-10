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
 * @package    Warecorp_Feed
 * @copyright  Copyright (c) 2010
 * @author Shutin Pavel
 */
class BaseWarecorp_Feed_Reader extends Zend_Feed_Reader
{
	
    public static function import($uri, $etag = null, $lastModified = null)
    {
        $cache       = Warecorp_Cache::getFileCache();
        $feed        = null;
        $responseXml = '';
        $client      = self::getHttpClient();
        $client->resetParameters();
        $client->setHeaders('If-None-Match', null);
        $client->setHeaders('If-Modified-Since', null);
        $client->setUri($uri);
        $cacheId = 'Zend_Feed_Reader_' . md5($uri);
        $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};
        if (self::$_httpConditionalGet && $cache) {
            $data = $cache->load($cacheId);
            if ($data) {
                if (is_null($etag)) {
                    $etag = $cache->load($cacheId.'_etag');
                }
                if (is_null($lastModified)) {
                    $lastModified = $cache->load($cacheId.'_lastmodified');;
                }
                if ($etag) {
                    $client->setHeaders('If-None-Match', $etag);
                }
                if ($lastModified) {
                    $client->setHeaders('If-Modified-Since', $lastModified);
                }
            }
            $response = $client->request('GET');
            if ($response->getStatus() !== 200 && $response->getStatus() !== 304) {
                require_once 'Zend/Feed/Exception.php';
                throw new Zend_Feed_Exception('Feed failed to load, got response code ' . $response->getStatus());
            }
            if ($response->getStatus() == 304) {
                $responseXml = $data;
            } else {
                $responseXml = $response->getBody();
                $cache->save($responseXml, $cacheId,array(), $cfgLifetime->feeds);
                if ($response->getHeader('ETag')) {
                    $cache->save($response->getHeader('ETag'), $cacheId.'_etag',array(), $cfgLifetime->feeds);
                }
                if ($response->getHeader('Last-Modified')) {
                    $cache->save($response->getHeader('Last-Modified'), $cacheId.'_lastmodified',array(), $cfgLifetime->feeds);
                }
            }
            return self::importString($responseXml);
        } elseif ($cache) {
            $data = $cache->load($cacheId);
            if ($data !== false) {
                return self::importString($data);
            }
            $response = $client->request('GET');
            if ($response->getStatus() !== 200) {
                require_once 'Zend/Feed/Exception.php';
                throw new Zend_Feed_Exception('Feed failed to load, got response code ' . $response->getStatus());
            }
            $responseXml = $response->getBody();
            $cache->save($responseXml, $cacheId, array(),$cfgLifetime->feeds);
            return self::importString($responseXml);
        } else {
            $response = $client->request('GET');
            if ($response->getStatus() !== 200) {
                require_once 'Zend/Feed/Exception.php';
                throw new Zend_Feed_Exception('Feed failed to load, got response code ' . $response->getStatus());
            }
            return self::importString($response->getBody());
        }
    }
}
