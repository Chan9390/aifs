<?php

/**
 * AIFS OSINT Helper File
 * Copyright (c) 2016, Digital Oversight
 */
 
/**
 * Very basic html parser.
 * @version 1.0
 */
 
function cleanhtml( $string )  {
    $ignore = false;
    $buffer = '';
    $cleaned = '';
    
    for ($i = 0; $i<strlen($string); $i++) {

        if ($ignore == false)
            $buffer .= $string[$i];

        if ($string[$i] == '<') {
            $cleaned .= ' '.ereg_replace('<', '', $buffer);
            $buffer = '';
            $ignore = true;
            
        } else if ($string[$i] == '>') {
            $ignore = false;
            $buffer = '';
        }
    }

    return $cleaned;
}

/**
 * Email call on content modification, used in osint_fetch_version.php
 * @version 1.01
 */

function mailChangeAlert( $email = "aifs-noreply@" , $userurl = 'http://' , 
                                    $internal="", $path = '/var/www/aifs', $domain )  {

    if ($email == 'aifs-noreply@') {
        exit;
    }
    if ($userurl == 'http://') {
        exit;
    }
    
    require_once $path.'/common/tool/class.phpmailer.php';
    require_once $path.'/common/tool/class.smtp.php';
    
    $old_url = $url = $userurl;
    
    if (strpos($url, "http://") === false){

        $url = "http://".$url;
    }

    $mail = new PHPMailer();

    $mail->From     = "aifs-noreply@".$domain;
    $mail->FromName = "aifs-noreply@".$domain; 
    $mail->Mailer   = "mail";
    $mail->Subject = "Notification of changed URL (".date("d")."/".date("m")."/".date("Y").")";

    $mail->isHTML( true );

    $mail->Body    = "
        <html><body>
        <table width=\"600\" border=\"0\">
        <tr>
        <td><img src=\"" . $domain . "/aifs/images/aifs-email-small.gif\" alt=\"\" title=\"\" />
        </td><td>
        <h1><a href=\"" . $domain . "/aifs\">AIFS</a>.
        </h1>
        </td>
        <tr>
        </table>
        <br />Fellow AIFS user,
        <br />
        <br />
        You subscribed to the url: <b>".$old_url."</b>.<br />
        This url has changed recently, you can click <a href=\"".$internal."\">here to see changes on your AIFS.</a><br /> 
        or on this <a href=\"".$url."\">link</a> to visit the site .<br />
        <br />
        <div style=\"font-size:10px; font-color:gray\">AIFS.<br />
        </div></body></html>";

    $mail->AltBody = $body;
    $mail->AddAddress($email, $email);
    $mail->Send();
} 