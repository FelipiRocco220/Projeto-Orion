<?php
include_once('control/post_check.inc.php');

$novel_id = $_POST["id"];
try {
    include_once('dbc.inc.php');
    include_once('session.inc.php');

    $query = "select * from novels_favorites where user_id = :user_id and novel_id = :novel_id;";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":user_id", $user_id);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    if($cmd->rowCount() != 0){
        if(!isset($user_id)) return;
        $query = "delete from novels_favorites where user_id = :user_id and novel_id = :novel_id;";
        $cmd = $db->prepare($query);
        $cmd->bindParam(":user_id", $user_id);
        $cmd->bindParam(":novel_id", $novel_id);
        $cmd->execute();
        echo 0;
    } else {
        if(!isset($user_id)) return;
        $query = "insert into novels_favorites(user_id, novel_id) values (:user_id, :novel_id);";
        $cmd = $db->prepare($query);
        $cmd->bindParam(":user_id", $user_id);
        $cmd->bindParam(":novel_id", $novel_id);
        $cmd->execute();
        echo 1;
    }
} catch (\Throwable $th) {
    echo $th;
}