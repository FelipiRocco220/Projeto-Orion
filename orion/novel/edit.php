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
    <title>Editar Novel</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <form action="../assets/includes/edit_novel.inc.php" method="post">
        <span>
            <label>Obra: </label>
            <?php
            try {
                $query = "select id, title from novels where author_id = :author_id";
                $cmd = $db->prepare($query);
                $cmd->bindParam(":author_id", $user_id);
                $cmd->execute();
    
                echo"<select name='novel'>";
                for($i = 0; $curUser = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                    echo "<option value='" . $curUser['id'] . "'>" . $curUser['title'] . "</option>";
                }
                echo"</select>";
            } catch (\Throwable $th) {
                die("error...");
            }
            ?>
        </span>
        <br>
        <button>submit</button>
    </form>
</body>
</html>