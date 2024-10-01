<?php
include_once('control/post_check.inc.php');

$email = $_POST["email"];
$password = $_POST["password"];

try {
    include_once('dbc.inc.php');
    include_once('session.inc.php');
    include_once('control/age_control.inc.php');
    include_once('control/signin_control.inc.php');
    include_once("control/debug.inc.php");
    
    if(is_empty($email, $password)){
        $_SESSION["signin_error"] = "Informações faltam no formulário.";
        header("Location: ../../user/log.php");
        die();
    }
    if(!valid_pwd($password)){
        header("Location: ../../user/log.php");
        die();
    }
    if(!valid_email($db, $email, $valid_emails_types)){
        header("Location: ../../user/log.php");
        die();
    }
    
    if(log_user($db, $email, $password)){
        header("Location: ../../index.php");
        die();
    }
    
    header("Location: ../../user/log.php");
    die();
} catch (\Throwable $th) {
    header("Location: ../../error.php");
}