<?php

namespace php_font_changer;

require_once "init.php";

const SUBJECT = "Thank you for the test";

function get_test_msg(){
    $msg = "This is the test sending from a rental server.\n";
    $msg .= "Sent the mail successfully if you read this.\n";
    $msg .= "This mail was sent at " . date('Y/m/d H:i:s') . ".";
    return $msg;
}

function send_mail($subject, $msg){
    $headers = "From: " . MMM_FROM . "\n";
    $headers .= "Return-Path: " . MMM_TO . "\n";
    $headers .= "Content-Type: text/plain;charset=ISO-2022-JP";

    $is_success = mb_send_mail(MMM_TO, $subject, $msg, $headers);
    if(!$is_success) {
        die('Sending failed.');
    }
}

//send_mail(SUBJECT, get_test_msg()); // For test

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>My Micro Mailer</title>
</head>
<body>
    <p>Sent a test mail.</p>
</body>
</html>