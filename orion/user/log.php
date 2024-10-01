<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <form action="../assets/includes/signin.inc.php" method="post">
        <input name="email" placeholder="e-mail" required>
        <br>
        <input name="password" type="password" placeholder="senha" required autocomplete="off">
        <br>
        <button>submit</button>
    </form>

    <?php
    include_once("../assets/includes/session.inc.php");
    if(isset($_SESSION["signin_error"])){
        echo "<br>";
        echo $_SESSION["signin_error"];
        $_SESSION["signin_error"] = null;
    }
    ?>
</body>
</html>