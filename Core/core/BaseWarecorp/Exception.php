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


class BaseWarecorp_Exception extends Zend_Exception
{
	protected $code;
	protected $message;

	function __construct($msg = 'Unknown Exception', $state = '00000')
	{
		/**
         *  LOGING
         */
		if ( (defined('ERRORS_LOG_MODE') && ERRORS_LOG_MODE != 0) || (defined('ERRORS_EMAIL_SEND_MODE') && ERRORS_EMAIL_SEND_MODE != 0) ) {
			$fileName = DEBUG_LOG_DIR.'errors_report.xml';
			if ( !file_exists($fileName) ) {
				$handle = fopen($fileName, "w");
				fclose($handle);
				chmod($fileName, 0777);
				$doc = new DOMDocument();
				$root = $doc->createElement('root');
				$doc->appendChild($root);
				$doc->save($fileName);
			}
			if ( file_exists($fileName) && is_writable($fileName) ) {
				$doc = DOMDocument::load($fileName);
				$doc->formatOutput = true;

				$root = $doc->getElementsByTagName('root');
				$root = $root->item(0);

				$record = $doc->createElement('record');
				$root->appendChild($record);

				$defaultTimezone = date_default_timezone_get();
				date_default_timezone_set('UTC');
				$date = new Zend_Date();
				date_default_timezone_set($defaultTimezone);

				$nodeData = $doc->createElement('data', $date->get(Zend_Date::RFC_2822));
				$nodeError = $doc->createElement('error', $msg);
				$nodeFile = $doc->createElement('file', $this->getFile());
				$nodeLine = $doc->createElement('line', $this->getLine());


				$nodeRequest = $doc->createElement('request');
				$nodeCDRequest = $doc->createCDATASection(var_export($_REQUEST, true));
				$nodeRequest->appendChild($nodeCDRequest);

				$nodeSession = $doc->createElement('session');
				$nodeCDSession = $doc->createCDATASection(var_export($_SESSION, true));
				$nodeSession->appendChild($nodeCDSession);

				$nodeCookie = $doc->createElement('cookie');
				$nodeCDCookie = $doc->createCDATASection(var_export($_COOKIE, true));
				$nodeCookie->appendChild($nodeCDCookie);

				$nodeServer = $doc->createElement('server');
				$nodeCDServer = $doc->createCDATASection(var_export($_SERVER, true));
				$nodeServer->appendChild($nodeCDServer);

				$nodeBacktrace = $doc->createElement('backtrace');
				$nodeCDBacktrace = $doc->createCDATASection(var_export(debug_backtrace(), true));
				$nodeBacktrace->appendChild($nodeCDBacktrace);

				$record->appendChild($nodeData);
				$record->appendChild($nodeError);
				$record->appendChild($nodeFile);
				$record->appendChild($nodeLine);
				$record->appendChild($nodeRequest);
				$record->appendChild($nodeSession);
				$record->appendChild($nodeCookie);
				$record->appendChild($nodeServer);
				$record->appendChild($nodeBacktrace);

				if ( defined('ERRORS_LOG_MODE') && ERRORS_LOG_MODE != 0 ) {
					$doc->save($fileName);
				}

				if ( defined('ERRORS_EMAIL_SEND_MODE') && ERRORS_EMAIL_SEND_MODE != 0 ) {
					if ( defined('ERRORS_EMAIL_SEND_TO') && ERRORS_EMAIL_SEND_TO != '' ) {
						$html = new DOMDocument();
						$html->loadXML($doc->saveXML($record));
						$emailContent = $html->saveHTML();

						require_once(ENGINE_DIR.'/htmlMimeMail5/htmlMimeMail5.php');
						$mail = new htmlMimeMail5();
						$mail->setTextCharset("UTF-8");
						$mail->setHTMLCharset("UTF-8");
						$mail->setHeadCharset("UTF-8");

                        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
                        $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
						if ( isset($cfgInstance->smtp_method) && $cfgInstance->smtp_method == 'smtp' ) {
                            $timeout = ( isset($cfgInstance->smtp_timeout) ) ? $cfgInstance->smtp_timeout : 5;
                            $socket_set_timeout = ( isset($cfgInstance->socket_set_timeout) ) ? $cfgInstance->socket_set_timeout : 5;
                            $mail->setSMTPParams($cfgInstance->smtp_host, $cfgInstance->smtp_port, null, null, null, null, $timeout, $socket_set_timeout);
							$send_method = 'smtp';
						} else $cfgInstance->smtp_method = 'mail';

						$mail->setText($emailContent);
						$mail->setHTML('');
						$mail->setFrom('ErrorReporting@zanby.com');
						$mail->setSubject('Error Reporting Message');


						$emails = explode(';',ERRORS_EMAIL_SEND_TO);
						foreach ( $emails as $email ) {
							//  @todo ������ ��������, ��� ����� �� ������������ �������� ���� � ���
							if ($cfgInstance->smtp_method == 'smtp') {
								$mail->send(array($email), $cfgInstance->smtp_method, true);
							} else {
								$email = preg_replace("/@(.*?)$/mi","@testing.zanby.buick",$email);
								$mail->send(array($email), $cfgInstance->smtp_method, true);
							}
						}

					}
				}
			}
		}

		/**
         *  DISPLAY
         */
		if ( defined('ERRORS_DISPLAY_MODE') && ERRORS_DISPLAY_MODE != 0 ) {
			
			if ( isset($_GET["xajax"]) || isset($_POST["xajax"])){ //if ajax request - show alert
				$objResponse = new xajaxResponse();
				$objResponse->addScript("alert('Exception occured. Check log for more details'); document.location='".BASE_URL."/en/';");

				$sContentHeader = "Content-type: text/xml;";
				header($sContentHeader);
				print $objResponse->getXML(); exit;
			} 
			
			$this->message = $msg;
			$this->code = $state;

			$backtrace = debug_backtrace();
			$backtrace = var_export($backtrace, true);
			$backtrace = '<?php
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
 $backtrace = '.$backtrace . ';?>';
			$backtrace = highlight_string($backtrace, true);

			$request = $_REQUEST;
			$request = var_export($request, true);
			$request = '<?php
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
 $_REQUEST = '.$request . ';?>';
			$request = highlight_string($request, true);

			$session = $_SESSION;
			$session = var_export($session, true);
			$session = '<?php
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
 $_SESSION = '.$session . ';?>';
			$session = highlight_string($session, true);

			$cookie = $_COOKIE;
			$cookie = var_export($cookie, true);
			$cookie = '<?php
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
 $_COOKIE = '.$cookie . ';?>';
			$cookie = highlight_string($cookie, true);

			$server = $_SERVER;
			$server = var_export($server, true);
			$server = '<?php
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
 $_SERVER = '.$server . ';?>';
			$server = highlight_string($server, true);


			$page = Zend_Registry::get("Page");
			$page->Template->assign('bodyContent', 'error.tpl');
			$page->Template->assign('error', $this);
			$page->Template->assign('backtrace', $backtrace);
			$page->Template->assign('request', $request);
			$page->Template->assign('session', $session);
			$page->Template->assign('cookie', $cookie);
			$page->Template->assign('server', $server);
			print $page->Template->getContents($page->Template->layout);
			exit;
		} else {// if not show info - just redirect to home
			if ( isset($_GET["xajax"]) || isset($_POST["xajax"])){ //if ajax request
				$objResponse = new xajaxResponse();
				$objResponse->addRedirect(BASE_URL.'/en/');

				$sContentHeader = "Content-type: text/xml;";
				header($sContentHeader);
				print $objResponse->getXML(); exit;
				
			} else {//if simple request

				Header('Location: '.BASE_URL.'/en/');
				exit;
			}
		}
	}
}
