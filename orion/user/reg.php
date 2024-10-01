<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php
    
    ?>
    <form action="../assets/includes/signup.inc.php" method="post">
        <input name="name" placeholder="nome" autocomplete="off" required>
        <br>
        <label for="tag">@</label>
        <input name="tag" id="tag" placeholder="tag_de_usuario" autocomplete="off" required>
        <br>
        <input name="email" placeholder="e-mail" autocomplete="off" required>
        <br>
        <input name="password" type="password" placeholder="senha" autocomplete="off" required>
        <br>
        <label for="birthday">Data de Aniversario</label>
        <input name="birthday" id="birthday" type="date" required>
        <a id="age"></a>
        <br>
        <button>submit</button>
    </form>
    
    <?php
    include_once("../assets/includes/session.inc.php");
    if(isset($_SESSION["signup_error"])){
        echo "<br>";
        echo $_SESSION["signup_error"];
        $_SESSION["signup_error"] = null;
    }
    ?>

    <script src="../assets/js/tag.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>