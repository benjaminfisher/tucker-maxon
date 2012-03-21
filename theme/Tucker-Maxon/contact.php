<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      template.php
* @Package:   GetSimple
* @Action:    Tucker-Maxon theme for the GetSimple CMS
*
*****************************************************/

include 'header.inc.php';
include 'sidebar.contact.php';
?>
  <div class="content">
    <hgroup>
      <h2><?php get_page_title(); ?></h2>
    </hgroup>
  
    <?php get_page_content(); ?>
	
<?php

if (isset ($_POST['name'])) {
/* These are the variable that tell the subject of the email and where the email will be sent.*/

$emailSubject = 'Message from TuckerMaxon.com';
$mailto = 'contact@tmos.org';

/* These will gather what the user has typed into the fieled. */

$nameField = $_POST['name'];
$emailField = $_POST['email'];
$questionField = $_POST['question'];

/* This takes the information and lines it up the way you want it to be sent in the email. */

$body = "Name: {$nameField} <br> Email: {$emailField} <br> Question: {$questionField} <br>";


$headers = "From: $email\r\n"; // This takes the email and displays it as who this email is from.
$headers .= "Content-type: text/html\r\n"; // This tells the server to turn the coding into the text.
$success = mail($mailto, $emailSubject, $body, $headers); // This tells the server what to send.

/*
if ($success) {
  echo $body;
}
*/

}


?>

<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Form Mailer</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="form_mailer.php">
    
    <div id="name_block">
      <label for"name">Name:</label></td>
      <input name="name" type="text" id="name" size="30" />
    </div>

    <div id="email_block">
      <label for="email">Email:</label></td>
      <input name="email" type="text" id="email" size="30" />
    </div>

    <div id="question_block">
      <label id="question_label" for="question">Question:</label>
      <textarea name="question" cols="30" rows="5" id="question"></textarea>
    </div>

    <div id="submit_block">
      <label>
        <input type="submit" name="Submit" id="Submit" value="Submit" />
      </label>
    </div>

</form>
</body>
</html>
  </div>
    
<?php include 'footer.inc.php'; ?>
