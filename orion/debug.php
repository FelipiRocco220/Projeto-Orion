<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto orion</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .pfp{
            height: 30px;
            display: flex;
            gap: 10px;
        }
        .pog{
            width = 50px;
            aspect-ratio: 1 / 1;
        }
    </style>
</head>
<body>
    <?php
    include_once('assets/includes/dbc.inc.php');
    include_once('assets/includes/session.inc.php');
    include_once('assets/includes/control/user_model.inc.php');
    include_once('assets/includes/control/age_control.inc.php');

    try {
        if(isset($user_id)){
            $user = get_user($db, $user_id);

            $admin = false;
            if($user->role_id == 2 || $user->role_id == 3) {
                $admin = true;
            }
    
            echo "<div class='pfp'>";
            echo "<img src='assets/images/pfp/$user->pfp' alt='user pfp'>";
            echo $user->username . " (@" . $user->tag . ")";
            echo "</div>";

            echo "<a href='user/config'>editar perfil</a>";
            echo " - ";
            echo "<a href='assets/includes/logout'>sair</a>";
            echo "<br>";
            echo "<br>";
            if($admin == true) echo "<a href='system/debug'>Debug</a><br>";
            echo "<a href='novel/catalog'>Ver Catalogo</a>";
            echo "<br>";
            echo "<a href='novel/@me'>Ver minhas Novels</a>";
            echo "<br>";
            if($admin == true) {
                echo "<br>";
                echo "<a href='novel/add'>Adicionar Novel</a>";
                echo "<br>";
                echo "<a href='novel/edit'>Editar Novel</a>";
                echo "<br>";
                echo "<a href='novel/remove'>Remover Novel</a>";
                echo "<br><br>";
            }
            echo "<a href='novel/chapter/add'>Adicionar Capítulo</a>";
            echo "<br>";
            echo "<a class='unfinished'>Editar Capítulo</a>";
            echo "<br>";
            echo "<a class='unfinished'>Remover Capítulo</a>";
        } else {
            echo "<a href='user/reg'>Register</a>";
            echo " - ";
            echo "<a href='user/log'>Login</a>";
        }
        echo "<br>";
    } catch (\Throwable $th) {
        //throw $th;
        echo "error " . $th;
    }

    try {
        $query = "select novels.id as n_id, title, description, thumbnail, novels.creation_date as cd, visibility, users.id as a_id, users.tag as author from novels join users on users.id = novels.author_id;";
        $cmd = $db->prepare($query);
        $cmd->execute();

        if($cmd->rowCount() > 0){
            echo"<br>";
            echo"<table>";
            echo"<tr>";
            echo "<td>id</td>";
            echo "<td>thumbnail</td>";
            echo "<td>titulo</td>";
            echo "<td>descrição</td>";
            echo "<td>data de criação</td>";
            echo "<td>autor</td>";
            echo "<td>nota</td>";
            echo "<td>visibilidade</td>";
            echo"</tr>";

            for($i = 0; $curNovel = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                $date = date_create($curNovel['cd']);
                $date = date_format($date,"H:i d/m/Y");
                echo"<tr>";
                echo "<td>" . $curNovel['n_id'] . "</td>";
                echo "<td>" . "<img src='assets/images/novel/thumbnail/" . $curNovel['thumbnail'] . "'><br>" . $curNovel['thumbnail'] . "</td>";
                echo "<td><a href='novel/read?s=" . $curNovel["n_id"] . "'>" . $curNovel['title'] . "</a></td>";
                echo "<td>" . $curNovel['description'] . "</td>";
                echo "<td>" . $date . "</td>";
                echo "<td><a href='user/page?u=" . $curNovel["a_id"] . "'>@" . $curNovel["author"] . "</a></td>";

                $rat_query = "select rating from novels_ratings where novel_id = " . $curNovel["n_id"] . ";";
                $rat_cmd = $db->prepare($rat_query);
                $rat_cmd->execute();
                $rat_length = $rat_cmd->rowCount();
                echo "<td>";
                if($rat_length == 0){
                    echo"<div class='stars'>";
                    for($i = 0; $i < 5; $i++){
                        echo "<i class='bi bi-star'></i>";
                    }
                    echo"</div>";
                } else {
                    $rat_num = 0;
                    for($i = 0; $curRat = $rat_cmd->fetch(PDO::FETCH_ASSOC); $i++){
                        $rat_num += $curRat["rating"];
                    }
                    $rat_num /= $rat_length;
                    $rat_num = round($rat_num * 10) / 10;
                    $rating = $rat_num * 2 . " (" . $rat_length . ")";
                    
                    echo"<div class='stars'>";
                    $round_rat = round($rat_num * 2) / 2; // fat rat??!!
                    for($i = 0; $i < 5; $i++){
                        if($i + 1 <= ceil($round_rat)) {
                            if($i + 1 == ceil($round_rat) && $i + 1 != $round_rat) echo "<i class='bi bi-star-half'></i>";
                            else echo "<i class='bi bi-star-fill'></i>";
                        }
                        else echo "<i class='bi bi-star'></i>";
                    }
                    echo $rating;
                    echo"</div>";
                }
                echo "</td>";

                $vis_names = ['Privado', 'Público', 'Não Listado'];
                echo "<td>" . $vis_names[$curNovel["visibility"]] . "</td>";

                echo"</tr>";
            }
            echo"</table>";
        } else {
            echo "<br>";
            echo"Nenhuma visual novel encontrada...";
        }
    } catch (\Throwable $th) {
        
    }

    try {
        $tag_query = "select id, pfp, username, tag, email, birth_date as bd, creation_date as cd, roles.name as role from users join roles on roles.role_id = users.role_id;";
        $tag_cmd = $db->prepare($tag_query);
        $tag_cmd->execute();

        if($tag_cmd->rowCount() > 0){
            echo "<br><br>";
            echo"<table>";

            echo "<td>foto</td>";
            echo "<td>nome</td>";
            echo "<td>tag</td>";
            echo "<td>email</td>";
            echo "<td>data de nascimento</td>";
            echo "<td>data de criação</td>";
            echo"</tr>";

            for($i = 0; $curUser = $tag_cmd->fetch(PDO::FETCH_ASSOC); $i++){
                $date = date_create($curUser['cd']);
                $date = date_format($date,"H:i d/m/Y");

                $birth = date_create($curUser['bd']);
                $birth = date_format($birth,"d/m/Y");

                $age = ' (' . calculate_age($curUser['bd']) . ' Anos)';

                $userPfp = pfp_return($curUser['pfp']);
                echo"<tr>";
                echo "<td><img class='pog' src='assets/images/pfp/" . $userPfp . "'></td>";
                echo "<td>" . $curUser['username'] . "</td>";
                echo "<td><a href='user/page?u=" . $curUser["id"] . "'>@" . $curUser['tag'] . "</a></td>";
                echo "<td>" . $curUser['email'] . "</td>";
                echo "<td>" . $birth . $age . "</td>";
                echo "<td>" . $date . "</td>";
                echo"</tr>";
            }
            echo"</table>";
        } else {
            echo "<br><br>";
            echo"Nenhum usuario encontrado...";
        }
    } catch (\Throwable $th) {
        echo $th;
    }
    ?>
</body>
</html>