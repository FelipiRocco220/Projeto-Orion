<?php
include_once('control/post_check.inc.php');

$name = $_POST["name"];
$tag = $_POST["tag"];
$email = $_POST["email"];
$password = $_POST["password"];
$birthdate = $_POST["birthday"];

try {
    include_once('dbc.inc.php');
    include_once('session.inc.php');
    include_once('control/age_control.inc.php');
    include_once('control/signup_control.inc.php');
    include_once("control/debug.inc.php");

    if(!valid_date($birthdate)){
        $_SESSION["signup_error"] = "Data de aníversario inválida.";
        header("Location: ../../user/reg.php");
        die();
    }
    if(!valid_age($birthdate, $age_restriction)){
        $_SESSION["signup_error"] = "Apenas usuários com mais de $age_restriction são permitidos.";
        header("Location: ../../user/reg.php");
        die();
    }

    if(is_empty($name, $tag, $password, $email, $birthdate)){
        $_SESSION["signup_error"] = "Informações faltam no formulário.";
        header("Location: ../../user/reg.php");
        die();
    }
    if(!valid_pwd($password, $strong_password)){
        header("Location: ../../user/reg.php");
        die();
    }
    if(!valid_username($name)){
        header("Location: ../../user/reg.php");
        die();
    }
    $tag = str_replace(" ", "_", trim($tag));
    if(!valid_tag($tag)){
        header("Location: ../../user/reg.php");
        die();
    }
    if(!valid_email($db, $email, $valid_emails_types, $valid_emails_ends)){
        header("Location: ../../user/reg.php");
        die();
    }
    if(tag_taken($db, $tag)){
        $_SESSION["signup_error"] = "Tag ocupada.";
        header("Location: ../../user/reg.php");
        die();
    }

    if(create_user($db, $name, $tag, $email, $birthdate, $password)){
        $query = "select LAST_INSERT_ID();";
        $cmd = $db->prepare($query);
        $cmd->execute();
        $fetch = $cmd->fetch();
        
        $_SESSION['user_id'] = $fetch[0];

        try {
            if($txt_logs){
                $file = "../log/account/new.txt";
                $myfile = fopen($file, "a");

                date_default_timezone_set("America/Sao_Paulo");
                $date = date("H:i d/m/Y");
                $txt = "@" . $tag . " created with username " . $name . " in " . $date . "\n";

                fwrite($myfile, $txt);
                fclose($myfile);
            }
        } catch (\Throwable $th) {
            // i would die
        }

        header("Location: ../../index.php");
        die();
    }

    $db = null;
    $fetch = null;
    $cmd = null;

    header("Location: ../../user/reg.php");
    die();
} catch (PDOException $e) {
    header("Location: ../../error.php");
}