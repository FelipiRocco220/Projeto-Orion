<?php
    include_once('../assets/includes/dbc.inc.php');
    include_once('../assets/includes/session.inc.php');

    try {
        if(isset($user_id)){
            include_once('../assets/includes/control/user_model.inc.php');
            $user = get_user($db, $user_id);
            if($user->role_id != 2 && $user->role_id != 3){
                header("Location: ../index.php");
                die();
            }
        } else {
            header("Location: ../index.php");
            die();
        }
    } catch (\Throwable $th) {
        header("Location: ../index.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Novel</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/pages/novel_add.css">
</head>
<body>
    <form class="flex" action="../assets/includes/functions/add/add_novel.inc.php" method="post" enctype="multipart/form-data">
        <div class="thumbnail">
            <div id="novel_thumb"></div>
            <input id="thumb" name="thumbnail" type="file" autocomplete="off" accept="image/png, image/jpeg">
        </div>
        <div class="actions column">
            <input name="title" placeholder="Nome da Obra" accept=".png,.jpg,.jpeg" required autocomplete="off">

            <textarea name="description" placeholder="Uma breve descrição da obra." required autocomplete="off"></textarea>

            <span>
                <label>Autor: </label>
                <?php
                try {
                    $query = "select id, tag from users;";
                    $cmd = $db->prepare($query);
                    $cmd->execute();
        
                    echo"<select name='author'>";
                    for($i = 0; $curUser = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                        echo "<option value='" . $curUser['id'] . "'>@" . $curUser['tag'] . "</option>";
                    }
                    echo"</select>";
                } catch (\Throwable $th) {
                    die("error...");
                }
                ?>
            </span>

            <span>
                <label>Tags: </label>
                <br>
                <?php
                try {
                    $query = "select * from tags;";
                    $cmd = $db->prepare($query);
                    $cmd->execute();
        
                    for($i = 0; $curUser = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                        echo "<input type='checkbox' name='tag#" . $curUser['id'] . "'>";
                        echo "<label for='tag#" . $curUser['id'] . "'>" . $curUser['name'] . "</label>";
                        echo "<br>";
                    }
                } catch (\Throwable $th) {
                    die("error...");
                }
                ?>
            </span>
            <div class="tags"></div>

            <button>submit</button>
        </div>
    </form>

    <?php
    if(isset($_SESSION["add_novel_error"])){
        echo "<br>";
        echo $_SESSION["add_novel_error"];
        $_SESSION["add_novel_error"] = null;
    }
    ?>

    <script src="../assets/js/thumbnail.js"></script>
    <script src="../assets/js/tags.js"></script>
</body>
</html>