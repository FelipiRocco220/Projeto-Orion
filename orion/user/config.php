<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
<?php
    // Possibilitar editar mais informações alem da imagem de perfil

    include_once('../assets/includes/dbc.inc.php');
    include_once('../assets/includes/session.inc.php');
    try {
        if(isset($user_id)){
            include_once('../assets/includes/control/user_model.inc.php');
    
            $user = get_user($db, $user_id);
            ?>

                <div class="pfp-big" id="pfp" style="background-image: url('<?php echo "../assets/images/pfp/" . $user->pfp; ?>');"></div>
                <br>
                <div class="form">
                    <input id="name" value='<?php echo $user->username; ?>'>
                    <br>
                    <label>@</label> <input id="tag" value='<?php echo $user->tag; ?>'>
                    <br>
                    <input id="email" value='<?php echo $user->email; ?>'>
                    <br>
                    <button id="submit">Editar</button>
                </div>

            <?php
        }
    } catch (\Throwable $th) {
        die("Erro de Servidor :(");
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/edit.js"></script>
</body>
</html>