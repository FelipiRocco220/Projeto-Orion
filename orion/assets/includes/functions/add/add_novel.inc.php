<?php
include_once('control/post_check.inc.php');

$title = $_POST["title"];
$desc = $_POST["description"];
$author_id = $_POST["author"];
$tags = [];

try {
  include_once('../../dbc.inc.php');
  include_once('../../session.inc.php');
  include_once('../../control/novel_control.inc.php');
  include_once('../../control/image_control.inc.php');
  
  if(is_empty($title, $desc)){
    $_SESSION["add_novel_error"] = "Informações faltam no formulário.";
    header("Location: ../../../../novel/add.php");
    die();
  }
  if(!is_numeric($author_id)){
    $_SESSION["add_novel_error"] = "Autor da obra inválido.";
    header("Location: ../../../../novel/add.php");
    die();
  }
  if(!valid_author($db, $author_id)){
    $_SESSION["add_novel_error"] = "Autor da obra não existe.";
    header("Location: ../../../../novel/add.php");
    die();
  }
  if(!valid_title($title)){
    header("Location: ../../../../novel/add.php");
    die();
  }
  if(!valid_desc($desc)){
    header("Location: ../../../../novel/add.php");
    die();
  }
  if(!valid_image("thumbnail")){
    header("Location: ../../../../novel/add.php");
    die();
  }

  for($i = 1; $i <= 15; $i++){
    if(isset($_POST["tag#" . $i])) {
      array_push($tags, $i);
    }
  }
  
  $imgType = explode('.', $_FILES["thumbnail"]["name"]);
  $imgType = $imgType[count($imgType) - 1];
  
  $target_dir = "../../../images/novel/thumbnail/";
  $file_name = uniqid() . "." . $imgType;
  $target_file = $target_dir . $file_name;
  if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
    
    $query = "insert into novels(thumbnail, title, description, visibility, author_id) values (:img, :title, :desc, 0, :author_id);";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":img", $file_name);
    $cmd->bindParam(":title", $title);
    $cmd->bindParam(":desc", $desc);
    $cmd->bindParam(":author_id", $author_id);
    $cmd->execute();

    $idquery = "select LAST_INSERT_ID();";
    $idcmd = $db->prepare($idquery);
    $idcmd->execute();
    $idfetch = $idcmd->fetch();  
    $novelID = $idfetch[0];

    for($i = 0; $i < count($tags); $i++){
      $query = "insert into novels_tags(novel_id, tag_id) values (:novel_id, :tag_id);";
      $cmd = $db->prepare($query);
      $cmd->bindParam(":tag_id", $tags[$i]);
      $cmd->bindParam(":novel_id", $novelID);
      $cmd->execute();
    }
    
    header("Location: ../../../../debug.php");
    die();
  } else {
    die("not possible to save image");
  }
} catch (\Throwable $th) {
  echo $th;
  // header("Location: ../../../../error.php");
}