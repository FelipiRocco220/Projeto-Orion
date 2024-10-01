<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
session_set_cookie_params([
    'lifetime' => 2419200, // tempo de cookies, 2419200 é a quantidade de segundos em quatro semanas
    'path' => '/',
    'domain' => 'localhost',
    'secure' => true,
    'httponly' => true
]);

session_start();

$user_id = null;
if(isset($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];

if(isset($db) && isset($user_id)){
    try {
        $real_query = "select id from users where id = :user_id;";
        $real_cmd = $db->prepare($real_query);
        $real_cmd->bindParam(":user_id", $user_id);
        $real_cmd->execute();
        $real_fetch = $real_cmd->fetch(PDO::FETCH_ASSOC);
        if(!$real_fetch){
            $user_id = null;
            $_SESSION['user_id'] = null;
            die();
        }
    } catch (\Throwable $th) {
        $user_id = null;
        $_SESSION['user_id'] = null;
        die();
    }
}