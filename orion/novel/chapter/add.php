<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Novel</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/pages/novel_add.css">
</head>
<body>
    <?php
        include_once('../../assets/includes/dbc.inc.php');
        include_once('../../assets/includes/session.inc.php');
        if(!isset($user_id)) {
            header("Location: ../../index.php");
            die();
        }
    ?>

    <form action="../../assets/includes/functions/add/add_chapter.inc.php" method="post">
        <input name="pos" placeholder="Posição" type="number">
        <br>
        <input name="title" placeholder="Título do Capítulo">
        <br>
        <select name="novel">
            <?php
            $query = "select id, title from novels where author_id = :author_id";
            $cmd = $db->prepare($query);
            $cmd->bindParam(":author_id", $user_id);
            $cmd->execute();

            if($cmd->rowCount() > 0){
                for($i = 0; $curNovel = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                    echo "<option value='" . $curNovel['id'] . "'>";
                    echo $curNovel['title'];
                    echo "</option>";
                }
            } else {
                echo "<option>Nenhuma novel encontrada...</option>";
            }
            ?>
        </select>
        <br>
        <select name='view'>
            <option value='0'>Privado</option>
            <option value='1'>Não listado</option>
        </select>
        <br>
        <button>submit</button>
    </form>

    <?php
    if(isset($_SESSION["add_chapter_error"])){
        echo "<br>";
        echo $_SESSION["add_chapter_error"];
        $_SESSION["add_chapter_error"] = null;
    }
    ?>

</body>
</html>