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

class BaseWarecorp_Image_Captcha
{
    
	public function __construct()
    {
	
    }

    public static function generateCaptcha(&$code)
    {
        srand(time());
        $code   = ($code === null) ? rand(1000,9999) : $code;
        $i      = rand(1,2);
        $im     = DOC_ROOT."/upload/imgkey/fon$i.png";
        $im     = ImageCreateFromPNG($im);
        $code   = strval($code);

        for ($i = 1; $i <= strlen($code); $i++) {
            $color = ImageColorAllocate($im, rand(0, 150),
            rand(0, 150), rand(0, 150));
            ImageString($im, rand(4,15), 20*$i-rand(0,10),
            rand(10,30), $code{$i-1}, $color);
        }

        ImagePng($im, DOC_ROOT."/upload/imgkey/".md5($code).".png");
        ImageDestroy($im);
        return "<img src='/upload/imgkey/".md5($code).".png"."'>";
    }
}
?>
