<?php
include_once('../../dbc.inc.php');
include_once('../../session.inc.php');
include_once('../../control/signup_control.inc.php');
include_once('../../control/image_control.inc.php');
include_once('../../control/user_model.inc.php');
include_once("../../control/debug.inc.php");

$name = $_POST["name"];
$tag = $_POST["tag"];
$email = $_POST["email"];

$user_data = get_user($db, $user_id);

if($user_data->username != $name){
    if(!valid_username($name)) {
        echo $_SESSION["signup_error"];
        die();
    }
}
if($user_data->tag != $tag){
    if(!valid_tag($tag)) {
        echo $_SESSION["signup_error"];
        die();
    }
    if(tag_taken($db, $tag)) {
        echo "Tag '@$tag' ocupada.";
        die();
    }
}
if($user_data->email != $email){
    if(!valid_email($db, $email, $valid_emails_types, $valid_emails_ends)) {
        echo $_SESSION["signup_error"];
        die();
    }        
}

$query = "update users set username = :name, tag = :tag, email = :email where id = :id;";
$cmd = $db->prepare($query);
$cmd->bindParam(":name", $name);
$cmd->bindParam(":tag", $tag);
$cmd->bindParam(":email", $email);
$cmd->bindParam(":id", $user_id);
$cmd->execute();

if(!valid_image("pfp")) die();

$imgType = explode('.', $_FILES["pfp"]["name"]);
$imgType = $imgType[count($imgType) - 1];

$target_dir = "../../../images/pfp/";
$file_name = uniqid() . "." . $imgType;
$target_file = $target_dir . $file_name;
if (move_uploaded_file($_FILES["pfp"]["tmp_name"], $target_file)) {
    $has = has_pfp($db, $user_id);
    if(!empty($has)){
        unlink("../../../images/pfp/" . $has);
    }
    
    $query = "update users set pfp = :img where id = :id;";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":img", $file_name);
    $cmd->bindParam(":id", $user_id);
    $cmd->execute();
}