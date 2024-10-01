<?php
include_once('assets/includes/dbc.inc.php');
include_once('assets/includes/session.inc.php');

$email = 'teste@gmail.com';

$email_query = "select id from users where email = :user_email;";
$email_cmd = $db->prepare($email_query);
$email_cmd->bindParam(":user_email", $email);
$email_cmd->execute();
$email_fetch = $email_cmd->fetch(PDO::FETCH_ASSOC);

echo "<br>";
if($email_fetch){
    echo "Existe";
} else {
    echo "Não existe";
}