<?php
/**
* This file is part of the htmlMimeMail5 package (http://www.phpguru.org/)
*
* htmlMimeMail5 is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* htmlMimeMail5 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with htmlMimeMail5; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* 
* © Copyright 2005 Richard Heyes
*/

    require_once('../htmlMimeMail5.php');
    
    $mail = new htmlMimeMail5();

    /**
    * Set the from address
    */
    $mail->setFrom('Richard <richard@example.com>');
    
    /**
    * Set the subject
    */
    $mail->setSubject('Test email');
    
    /**
    * Set high priority
    */
    $mail->setPriority('high');

    /**
    * Set the text of the Email
    */
    $mail->setText('Sample text');
    
    /**
    * Set the HTML of the email
    */
    $mail->setHTML('<b>Sample HTML</b> <img src="background.gif">');
    
    /**
    * Add an embedded image
    */
    $mail->addEmbeddedImage(new fileEmbeddedImage('background.gif'));
    
    /**
    * Add an attachment
    */
    $mail->addAttachment(new fileAttachment('example.zip'));

    /**
    * Send the email
    */
    $mail->send(array('richard@example.com'));
?>