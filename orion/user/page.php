<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php
    include_once('../assets/includes/dbc.inc.php');
    include_once('../assets/includes/session.inc.php');

    $userSearched = $_GET["u"];
    try {
        if(isset($userSearched)){
            include_once('../assets/includes/control/user_model.inc.php');
    
            $user = get_user($db, $userSearched);

            if($user->exists == 0) {
                die("Erro ao encontrar usuario :(");
                return;
            }

            $userPfp = pfp_return($user->pfp);
            echo "<img id='miniPfp' src='" . "../assets/images/pfp/" . $userPfp . "'>";
            echo "<br>";
            echo $user->username . " (@" . $user->tag . ")";
            echo "<br>";
            echo "Criado em " . $user->cd;
        }
    } catch (\Throwable $th) {
        echo $th;
        //die("Erro ao encontrar usuario :(");
    }

    echo "<h1>Novels:</h1>";
    try {
        $query = "select novels.id as n_id, title, description, thumbnail, novels.creation_date as cd, visibility, users.id as a_id, users.tag as author from novels join users on users.id = novels.author_id where author_id = :author_id and visibility = 1;";
        $cmd = $db->prepare($query);
        $cmd->bindParam(":author_id", $userSearched);
        $cmd->execute();

        if($cmd->rowCount() > 0){
            echo"<table>";
            echo"<tr>";
            echo "<td>id</td>";
            echo "<td>thumbnail</td>";
            echo "<td>titulo</td>";
            echo "<td>descrição</td>";
            echo "<td>data de criação</td>";
            echo "<td>autor</td>";
            echo "<td>nota</td>";
            echo"</tr>";

            for($i = 0; $curNovel = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                $date = date_create($curNovel['cd']);
                $date = date_format($date,"H:i d/m/Y");
                echo"<tr>";
                echo "<td>" . $curNovel['n_id'] . "</td>";
                echo "<td>" . "<img src='../assets/images/novel/thumbnail/" . $curNovel['thumbnail'] . "'><br>" . $curNovel['thumbnail'] . "</td>";
                echo "<td><a href='../novel/read?s=" . $curNovel["n_id"] . "'>" . $curNovel['title'] . "</a></td>";
                echo "<td>" . $curNovel['description'] . "</td>";
                echo "<td>" . $date . "</td>";
                echo "<td><a href='../user/page?u=" . $curNovel["a_id"] . "'>@" . $curNovel["author"] . "</a></td>";

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

                echo"</tr>";
            }
            echo"</table>";
        } else {
            echo"Nenhuma visual novel encontrada...";
        }
    } catch (\Throwable $th) {
        
    }
    ?>
</body>
</html>