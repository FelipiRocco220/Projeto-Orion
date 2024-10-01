<?php

$txt = $_POST['novelText'];
$id = $_POST['chapterID'];
$view = $_POST['view'];

if(!is_numeric($id) || !is_numeric($view)) {
    die("screw you >:(");
}
$id = (float) $id;
$view = (float) $view;
if(floor($id) != $id || floor($view) != $view) {
    die("screw you >:(");
}

try {
    include_once('../dbc.inc.php');
    include_once('../session.inc.php');

    $query = "select file, author_id from novels_chapters join novels on novels.id = novels_chapters.novel_id where novels_chapters.id = :chapter_id;";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":chapter_id", $id);
    $cmd->execute();
    $curChapter = $cmd->fetch(PDO::FETCH_ASSOC);

    if($curChapter["author_id"] != $user_id) {
        die();
        return;
    }
    
    $myfile = fopen("../../source/" . $curChapter["file"], "w") or die("Unable to open file!");
    fwrite($myfile, trim($txt));
    fclose($myfile);

    $query = "update novels_chapters set visibility = :view where id = :chapter_id;";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":chapter_id", $id);
    $cmd->bindParam(":view", $view);
    $cmd->execute();
} catch (\Throwable $th) {
    die();
    return;
}