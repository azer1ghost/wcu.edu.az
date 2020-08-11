<?php
/**
 * Created by MF.
 * User: MF
 * Date: 6/12/2017
 * Time: 10:17 AM
 */
require 'helper/mail/class.phpmailer.php';
require 'helper/mail/class.smtp.php';
class Sendmail extends PHPMailer {

    public function sendMailTo($to, $from, $from_name, $subject, $body) {

        //$this->SMTPDebug = 3;                                 // Enable verbose debug output
        $this->isSMTP();                                        // Set mailer to use SMTP
        $this->XMailer = 'PHP X-Mailer';
        $this->Host = '';                       // Specify main and backup SMTP servers
        $this->SMTPAuth = true;                                 // Enable SMTP authentication
        $this->Username = '';                // SMTP username
        $this->Password = "";              // SMTP password
        $this->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $this->Encoding = 'base64';                             // Set encoding
        $this->CharSet = 'UTF-8';                               // Set charset
        $this->Port = 26;                                      // Port
		$this->AuthType = 'CRAM-MD5';

        $this->setFrom($from, $from_name);                      // Set who the message is to be sent from
        $this->addAddress($to);                                 // Set who the message is to be sent to
        $this->Subject = $subject;                              // Set the subject line

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $this->isHTML(true);
        $this->Body = $body;
        $this->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$this->send()) {
            return 0;
            //echo 'Mailer Error: ' . $this->ErrorInfo;
        } else {
            $this->ClearAllRecipients();
            return 1;
            //echo  'Message has been sent';
        }

    }
}