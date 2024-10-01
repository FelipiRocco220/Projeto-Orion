<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php
        include_once('../assets/includes/dbc.inc.php');
        include_once('../assets/includes/session.inc.php');
    ?>
    <form action="" method="POST" id='form'>
    <input value="<?php if(isset($_POST["name"])) echo $_POST["name"]; ?>" name="name" placeholder="name" autocomplete="off">
    <br>
        <?php
        $tags_filter = [];
        try {
            $query = "select * from tags;";
            $cmd = $db->prepare($query);
            $cmd->execute();

            for($i = 0; $curUser = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                echo "<input";
                if(isset($_POST["tag#" . $curUser['id']])) {
                    echo " checked";
                    array_push($tags_filter, $curUser['id']);
                }
                echo " type='checkbox' name='tag#" . $curUser['id'] . "'>";
                echo "<label for='tag#" . $curUser['id'] . "'>" . $curUser['name'] . "</label>";
                echo "<br>";
            }
        } catch (\Throwable $th) {
            die("error...");
        }
        ?>
    <button type="submit" id="search">Pesquisar</button>
    <button type="reset" id='reset'>Limpar</button>
    </form>

    <?php
    $name;
    if(!empty($_POST["name"])) $name = $_POST["name"];

    try {
        if(!empty($name)) $query = "select novels.id as n_id, title, description, thumbnail, novels.creation_date as cd, visibility, users.id as a_id, users.tag as author from novels join users on users.id = novels.author_id where title like :novel_title and visibility = 1;";
        else $query = "select novels.id as n_id, title, description, thumbnail, novels.creation_date as cd, visibility, users.id as a_id, users.tag as author from novels join users on users.id = novels.author_id and visibility = 1;";
        $cmd = $db->prepare($query);
        if(!empty($name)) {
            $title_filter = '%' . $name . '%';
            $cmd->bindParam(":novel_title", $title_filter);
        }
        $cmd->execute();

        $novels_found = 0;
        if($cmd->rowCount() > 0){
            for($i = 0; $curNovel = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                if(count($tags_filter) > 0){
                    $tags_query = "select tag_id from novels_tags where novel_id = :novel_id;";
                    $tags_cmd = $db->prepare($tags_query);
                    $tags_cmd->bindParam(":novel_id", $curNovel["n_id"]);
                    $tags_cmd->execute();

                    $tags_found = 0;
                    for($j = 0; $curTag = $tags_cmd->fetch(PDO::FETCH_ASSOC); $j++){
                        if(in_array($curTag['tag_id'], $tags_filter)) $tags_found++;
                    }
                    if($tags_found != count($tags_filter)) continue;
                }

                $novels_found++;
                if($novels_found == 1){
                    echo"<br>";
                    echo"<table>";
                    echo"<tr>";
                    echo "<td>thumbnail</td>";
                    echo "<td>titulo</td>";
                    echo "<td>descrição</td>";
                    echo "<td>data de criação</td>";
                    echo "<td>autor</td>";
                    echo "<td>nota</td>";
                    echo "<td>visibilidade</td>";
                    echo "<td>Tags</td>";
                    echo"</tr>";
                }

                $date = date_create($curNovel['cd']);
                $date = date_format($date,"H:i d/m/Y");
                echo"<tr>";
                echo "<td>" . "<img src='../assets/images/novel/thumbnail/" . $curNovel['thumbnail'] . "'><br>" . $curNovel['thumbnail'] . "</td>";
                echo "<td><a href='read?s=" . $curNovel["n_id"] . "'>" . $curNovel['title'] . "</a></td>";
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

                $vis_names = ['Privado', 'Público', 'Não Listado'];
                echo "<td>" . $vis_names[$curNovel["visibility"]] . "</td>";

                echo "<td>";

                $tags_query = "select name from novels_tags join novels on novels_tags.novel_id = novels.id join tags on tags.id = novels_tags.tag_id where novels.id = :novel_id;";
                $tags_cmd = $db->prepare($tags_query);
                $tags_cmd->bindParam(":novel_id", $curNovel["n_id"]);
                $tags_cmd->execute();
                for($u = 0; $curTag = $tags_cmd->fetch(PDO::FETCH_ASSOC); $u++){
                    echo "#" . $curTag["name"] . " ";
                }
                echo"</td>";

                echo"</tr>";
            }
            echo"</table>";
        }

        if($novels_found == 0){
            echo "<br>";
            echo"Nenhuma visual novel encontrada...";
        }
    } catch (\Throwable $th) {
        echo $th;
    }
    ?>

    <script>
        const resetBtn = document.getElementById("reset");
        const search = document.getElementById("search");
        const form = document.getElementById("form");

        reset.onclick = () => {
            let children = form.children;
            for(var i = 0; i < children.length; i++){
                var child = children[i];
                if(child.tagName == 'INPUT'){
                    if(child.type == 'checkbox') child.removeAttribute("checked");
                    else child.removeAttribute("value");
                }
            }
            setTimeout(function(){
                search.click();
            }, 200);
        };
    </script>
</body>
</html>