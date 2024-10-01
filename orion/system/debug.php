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
    <title>Projeto orion</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php
    // Trabalhar em sistema de debug mais tarde.
    include_once('../assets/includes/control/debug.inc.php');
    $names = ["Acesso Global", "Acesso de Usuarios", "Senha Forte", "Logs Txt", "Editar Posts", "Novas Contas", "Novos Posts", "Novos Comentarios"];
    $vars = [$global_access, $user_access, $strong_password, $txt_logs, $edit_posts, $new_accounts, $new_posts, $new_comments];
    for($i = 0; $i < count($vars); $i++){
        $add = '';
        if($names[$i] == true) $add = 'checked';
        echo "<input type='checkbox'" . $add . ">" . $names[$i] . "<br>";
    }
    echo "<br>Idade minima para criar conta: <input disabled type='number' value='" . $age_restriction . "'><br>";
    echo "Emails validos: " . implode(", ", $valid_emails_types);
    ?>
</body>