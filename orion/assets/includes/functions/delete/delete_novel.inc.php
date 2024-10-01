<?php
include_once('control/post_check.inc.php');

if(empty($_POST["novel"])){
    header("Location: ../../../../error.php");
    die();
}
$novel_id = $_POST["novel"];

try {
    include_once('../../dbc.inc.php');
    include_once('../../session.inc.php');

    if(isset($user_id)){
        include_once('../../control/user_model.inc.php');
        $user = get_user($db, $user_id);
        if($user->role_id != 2 && $user->role_id != 3){
            header("Location: ../../../../error.php");
            die();
        }
    } else {
        header("Location: ../../../../error.php");
        die();
    }
} catch (\Throwable $th) {
    echo $th;
    // header("Location: ../../../../error.php");
}

try {
    $query = "delete from novels_tags where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    $query = "delete from novels_favorites where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    $query = "delete from novels_ratings where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    $query = "delete from novels_ratings where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    $query = "delete from novels_volumes where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    $query = "select * from novels_chapters where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    for($i = 0; $curChapter = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
        $file = "../../../source/" . $curChapter['file'];
        if(file_exists($file)) unlink($file);
    }

    $query = "delete from novels_chapters where novel_id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();
    
    $query = "select * from novels where id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();
    $length = $cmd->rowCount();

    if($length > 0){
        $curNovel = $cmd->fetch(PDO::FETCH_ASSOC);
        $file = "../../../images/novel/thumbnail/" . $curNovel['thumbnail'];
        if(file_exists($file)) unlink($file);
    }

    $query = "delete from novels where id = :novel_id";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":novel_id", $novel_id);
    $cmd->execute();

    header("Location: ../../../../novel/remove");
} catch (\Throwable $th) {
    echo $th;
}