<?php

/* MAILER REST API FOR TMAIL TO AUTO-SEND EMAILS ~ TEBEL.ORG */

// secret key to mitigate abuse of spamming using mailer api
if ($_GET['APIKEY']!="random_long_string_mailer_api_key") die("ERROR - invalid API secret key");

// address email recipient by name if recipient name provided
if ($_GET['SENDNAME']=="")
	$name = "";
else
	$name = "<p>Hi " . $_GET['SENDNAME'] . "," . "</p><p></p>";

// your catch-all email in case recipient email not provided
if ($_GET['SENDTO']=="")
	$to = "greatappleid99@gmail.com";
else
	$to = $_GET['SENDTO'];

// your default send from email if send from email not provided
if ($_GET['SENDFROM']=="")
	$from = "Evan <greatappleid99@gmail.com>";
else
	$from = $_GET['SENDFROM'];

// set default email subject if email subject not provided
if ($_GET['SUBJECT']=="")
	$subject = "Your Subject";
else
	$subject = $_GET['SUBJECT'];

// set message body to blank if email message not provided
if ($_GET['MESSAGE']=="")
	{$custom_message = "Message body is empty."; $message = " ";}
else
	{
// set your email footer below to be used when there is message body
$custom_message = $_GET['MESSAGE'];
$message = "
<html>
<head>
<title>" . $subject . "</title>
</head>
<body>
" . $name . "
<p>" . $custom_message . "<br>" .
"<span style=\"font-family: arial, helvetica, sans-serif; font-size: small;\">Kind Regards,<br><span style=\"color: #000000;\"><strong><em>Your Name</em></strong></span></span></p>
</body>
</html>";
	}

// OPTION 1 - FOR SUPPORTING ATTACHMENTS AND WINDOWS - https://github.com/PHPMailer/PHPMailer
// get from above URL PHPMailerAutoload.php, class.phpmailer.php, class.smtp.php, class.pop3.php
require_once('/full_path_on_your_server/PHPMailer/PHPMailerAutoload.php'); $mail = new PHPMailer;

// configure SMTP server settings and account credentials used for sending mails
$mail->Host = "smtp.gmail.com"; $mail->Port = 26; $mail->SMTPAuth = true;
$mail->Username = "greatappleid99@gmail.com"; $mail->Password = "Asdfgh+1234";

$mail->isHTML(true); $mail->isSMTP(); $mail->SMTPDebug = 0; // set to 2 for debugging
if ($_GET['OUTPUT']=="TEXT") $mail->Debugoutput = 'text'; else $mail->Debugoutput = 'html';

if (strpos($from, '<') !== false) {
	$from_name = trim(substr($from,0,strpos($from,'<')));
	$from_email = trim(substr($from,strpos($from,'<')+1));
	$from_email = trim(str_replace('>','',$from_email));
	$mail->setFrom($from_email,$from_name);}
else	$mail->setFrom(trim($from));

// for loop to manage multiple to emails separated by ,
$to_list = explode(',',$to); foreach ($to_list as $to_item) {
if (strpos($to_item, '<') !== false) {
	$to_name = trim(substr($to_item,0,strpos($to_item,'<')));
	$to_email = trim(substr($to_item,strpos($to_item,'<')+1));
	$to_email = trim(str_replace('>','',$to_email));
	$mail->addAddress($to_email,$to_name);}
else	$mail->addAddress(trim($to_item));}

// for debugging above block to extract email and name eg: Name <name@gmail.com>
// echo trim($from) . "\n"; echo $from_email ."\n"; echo $from_name . "\n";
// echo trim($to) . "\n"; echo $to_email . "\n"; echo $to_name . "\n"; die ("");

// only if applicable and required, explicitly setup DKIM (for identity authentication by receiver's mail server)
// $mail->DKIM_domain = "your_domain"; $mail->DKIM_private = "/full_path_on_your_server/private_key";
// $mail->DKIM_selector = "your_selector"; $mail->DKIM_identity = $mail->From; $mail->DKIM_passphrase = "";

$mail->Subject = $subject; $mail->msgHTML($message, dirname(__FILE__));
if ($_GET['ATTACHMENT']!="") $mail->addAttachment($_GET['ATTACHMENT']);

// customise result output below to show email success or failure
// first block is to show output as raw text, second block as html
if ($_GET['OUTPUT']=="TEXT")
        {
        if ($mail->send())
		echo $subject . " mail sent successfully to " . trim($to) . "\n";
        else
		echo $subject . " mail not sent through to " . trim($to) . " - " . $mail->ErrorInfo . "\n";
        }
else
        {
        if ($mail->send())
		echo "<h1><center><br><br><br><br><br><br><br><br>" . $subject . 
		" mail sent successfully to " . trim($to) . "</center></h1>";
        else
		echo "<h1><center><br><br><br><br><br><br><br><br>" . $subject . 
		" mail not sent through to " . trim($to) . " - " . $mail->ErrorInfo . "</center></h1>";
        }
// OPTION 1 - END OF BLOCK

/*
// OPTION 2 - FOR BASIC EMAIL WITHOUT ATTACHMENTS - SENDING USING PHP MAIL
$headers  = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: ' . $from . "\r\n";

// customise result output below to show email success or failure
// first block is to show output as raw text, second block as html
if ($_GET['OUTPUT']=="TEXT")
        {
        if (mail($to,$subject,$message,$headers))
		echo $subject . " mail sent successfully to " . trim($to) . "\n";
        else
		echo $subject . " mail not sent through to " . trim($to) . "\n";
	}
else
	{
        if (mail($to,$subject,$message,$headers))
		echo "<h1><center><br><br><br><br><br><br><br><br>" . $subject . 
		" mail sent successfully to " . trim($to) . "</center></h1>";
        else
		echo "<h1><center><br><br><br><br><br><br><br><br>" . $subject . 
		" mail not sent through to " . trim($to) . "</center></h1>";
	}
// OPTION 2 - END OF BLOCK
*/

?>
