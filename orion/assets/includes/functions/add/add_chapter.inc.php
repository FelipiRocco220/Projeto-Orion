<?php

$pos = $_POST["pos"];
$title = $_POST["title"];
$novel_id = $_POST["novel"];
$view = $_POST["view"];

try {
  include_once('../../dbc.inc.php');
  include_once('../../session.inc.php');
  include_once('../../control/post_check.inc.php');
  
  
  if(!is_numeric($pos)){
    $_SESSION["add_chapter_error"] = "Novel inválida.";
    header("Location: ../../../../novel/chapter/add.php");
    die();
  }
  if(!is_numeric($view)){
    $_SESSION["add_chapter_error"] = "Tipo de vizualização inválida.";
    header("Location: ../../../../novel/chapter/add.php");
    die();
  }
  if($view != 0 && $view != 1){
    $_SESSION["add_chapter_error"] = "Tipo de vizualização inválida.";
    header("Location: ../../../../novel/chapter/add.php");
    die();
  }
  
  $txt = "<h1>$title</h1> <p>Escreva seu capítulo no editor.</p>";
  
  $name = uniqid() . ".txt";
  $file = "../../../source/" . $name;
  $myfile = fopen($file, "w");
  
  fwrite($myfile, $txt);
  fclose($myfile);
  
  $query = "insert into novels_chapters(pos, file, title, visibility, novel_id) values (:pos, :file, :title, :view, :novel_id);";
  $cmd = $db->prepare($query);
  $cmd->bindParam(":pos", $pos);
  $cmd->bindParam(":file", $name);
  $cmd->bindParam(":title", $title);
  $cmd->bindParam(":novel_id", $novel_id);
  if($view == 1) $view = 2;
  $cmd->bindParam(":view", $view);
  $cmd->execute();

  $chapterIDquery = "select LAST_INSERT_ID();";
  $chapterIDcmd = $db->prepare($chapterIDquery);
  $chapterIDcmd->execute();
  $chapterIDfetch = $chapterIDcmd->fetch();  
  $chapterID = $chapterIDfetch[0];
  
  header("Location: ../../../../novel/chapter/edit?c=" . $chapterID);
} catch (\Throwable $th) {
  die("Novel Failed :( " . $th);
}