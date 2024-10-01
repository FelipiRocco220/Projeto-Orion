<link rel="stylesheet" href="../../assets/css/main.css">
<link rel="stylesheet" href="../../assets/css/pages/chapter_read.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php
    include_once('../../assets/includes/dbc.inc.php');
    include_once('../../assets/includes/session.inc.php');

    if(empty($_GET["c"])){
        die("screw you >:(");
    }
    $chapter_id = $_GET["c"];
    if(!is_numeric($chapter_id)) {
        die("screw you >:(");
    }
    $chapter_id = (float) $chapter_id;
    if(floor($chapter_id) != $chapter_id) {
        die("screw you >:(");
    }
    
    $query = "select file, author_id from novels_chapters join novels on novels.id = novels_chapters.novel_id where novels_chapters.id = :chapter_id;";
    $cmd = $db->prepare($query);
    $cmd->bindParam(":chapter_id", $chapter_id);
    $cmd->execute();

    if($cmd->rowCount() > 0){
        $curChapter = $cmd->fetch(PDO::FETCH_ASSOC);
        if($curChapter["author_id"] == $user_id) {
            echo "<a href='edit?c=$chapter_id'>Editar capitulo</a>";
            echo "</br></br>";
        }

        $myfile = fopen("../../assets/source/" . $curChapter["file"], "r");
        echo "<div class='read_container'>";
        while ($line = fgets($myfile)) {
            echo($line);
        }
        echo "</div>";
        fclose($myfile);
    } else {
        die();
    }
?>
<script src="../../assets/js/read.js"></script>