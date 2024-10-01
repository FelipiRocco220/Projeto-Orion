<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/pages/novel_read.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php
    include_once('../assets/includes/dbc.inc.php');
    include_once('../assets/includes/session.inc.php');

    if(empty($_GET["s"])){
        header("Location: ../error.php");
        die();
    }
    $novel_id = $_GET["s"];
    if(!is_numeric($novel_id)) {
        header("Location: ../error.php");
        die();
    }
    $novel_id = (float) $novel_id;
    if(floor($novel_id) != $novel_id) {
        header("Location: ../error.php");
        die();
    }


    $curNovel = null;
    try {
        $query = "select novels.id as n_id, title, description, thumbnail, novels.creation_date as cd, users.id as a_id, visibility, users.tag as author from novels join users on users.id = novels.author_id where novels.id = :novel_id;";
        $cmd = $db->prepare($query);
        $cmd->bindParam(":novel_id", $novel_id);
        $cmd->execute();
        $curNovel = $cmd->fetch(PDO::FETCH_ASSOC);

        if(!$curNovel){
            header("Location: ../error.php");
            die();
        }

        if($curNovel['visibility'] == 0){
            if($curNovel['a_id'] != $user_id){
                header("Location: ../error.php");
                die();
            }
        }

        $date = date_create($curNovel['cd']);
        $date = date_format($date,"H:i d/m/Y");

        echo "<br>";
        echo "<div class='flex novel'>";
        echo "<div>";
        echo "<img src='../assets/images/novel/thumbnail/" . $curNovel['thumbnail'] . "'>";
        echo "</div>";

        echo "<div class='column'>";
        echo "<a>" . $curNovel["title"]  . "</a>";
        echo "<a>" . $curNovel["description"]  . "</a>";

        $rat_query = "select rating from novels_ratings where novel_id = :novel_id;";
        $rat_cmd = $db->prepare($rat_query);
        $rat_cmd->bindParam(":novel_id", $novel_id);
        $rat_cmd->execute();
        $rat_length = $rat_cmd->rowCount();
        if($rat_length > 0){
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
                    if($i + 1 == ceil($round_rat) && $i + 1 != $round_rat) echo "<button><i class='bi bi-star-half'></i></button>"; // estrela ativada metade
                    else echo "<button><i class='bi bi-star-fill'></i></button>"; // estrela ativa
                }
                else echo "<button><i class='bi bi-star'></i></button>"; // estrela inativa
            }
            echo $rating;
            echo"</div>";
        } else {
            echo"<div class='stars'>";
            for($i = 0; $i < 5; $i++){
                echo "<button><i class='bi bi-star'></i></button>";
            }
            echo"</div>";
        }

        echo "<span>";
        $query = "select name from novels_tags join novels on novels_tags.novel_id = novels.id join tags on tags.id = novels_tags.tag_id where novels.id = :novel_id;";
        $cmd = $db->prepare($query);
        $cmd->bindParam(":novel_id", $novel_id);
        $cmd->execute();
        for($i = 0; $curUser = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
            echo "#" . $curUser['name'] . " ";
        }
        echo "</span>";

        echo "<br>";
        echo "<span>Feito por <a href='../user/page?u=" . $curNovel["a_id"] . "'>@" . $curNovel["author"] . "</a></span>";
        echo "<a>Postado em " . $date . "</a>";
        echo "<br>";

        $fav_query = "select novel_id from novels_favorites where novel_id = :novel_id and user_id = :user_id;";
        $fav_cmd = $db->prepare($fav_query);
        $fav_cmd->bindParam(":novel_id", $novel_id);
        $fav_cmd->bindParam(":user_id", $user_id);
        $fav_cmd->execute();
        if($fav_cmd->rowCount() != 0) echo "<button class='fav' id='favBtn'><i class='bi bi-star-fill'></i>Favorita</button>";
        else echo "<button id='favBtn'><i class='bi bi-star'></i>Favoritar</button>";
        echo "</div>";
        echo "</div>";

        //$cpt_query = "select * from novels_chapters where novel_id = :novel_id and visibility = 1 order by pos;";
        if($user_id == $curNovel['a_id']) $cpt_query = "select * from novels_chapters where novel_id = :novel_id order by pos;";
        else $cpt_query = "select * from novels_chapters where novel_id = :novel_id and visibility = 1 order by pos;";
        $cpt_cmd = $db->prepare($cpt_query);
        $cpt_cmd->bindParam(":novel_id", $novel_id);
        $cpt_cmd->execute();
        $cpt_length = $cpt_cmd->rowCount();

        echo "<div class='novel'>";
        if($cpt_length == 0){
            echo "Nenhum capítulo encontrado...";
        } else {
            echo "<h3>Capítulos</h3>";
            echo "<ul>";
            for($i = 0; $curChapter = $cpt_cmd->fetch(PDO::FETCH_ASSOC); $i++){
                echo "<a href='chapter/read?c=" . $curChapter["id"] . "'><li>Capítulo " . $curChapter["pos"] . ": " . $curChapter["title"];
                if($curChapter["visibility"] == 0) echo "<span style='color:red;'> (privado)</span>";
                else if($curChapter["visibility"] == 2) echo "<span style='color:yellow;'> (não listado)</span>";
                echo  "</li></a>";
            }
            echo "</ul>";
        }
        echo "</div>";
    } catch (\Throwable $th) {
        //throw $th;
        echo $th;
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const favBtn = document.getElementById('favBtn');
        const startContainer = document.getElementsByClassName('stars')[0];
        favBtn.onclick = function(){
            $.ajax({
                url: '../assets/includes/fav.php',
                method: 'POST',
                data: {id: <?php echo $novel_id; ?> },
                success: function(r) {
                    if(r == 1){
                        favBtn.classList.add('fav');
                        favBtn.innerHTML = "<i class='bi bi-star-fill'></i>Favorita";
                    } else if(r == 0){
                        favBtn.classList.remove('fav');
                        favBtn.innerHTML = "<i class='bi bi-star'></i>Favoritar";
                    }
                }
            })
        };

        console.log(startContainer);
        for(var i = 0; i < startContainer.children.length; i++){
            var child = startContainer.children[i];
            child.id = i + 1;
            child.onclick = (e) => {
                var target = e.srcElement;
                if(target.localName == 'i') target = target.parentElement;
                if(target.localName == 'button'){
                    console.log(target.id);
                    if(target.id == 1){
                        var child = target.children[0];
                        let parent = child.parentElement;
                        let lastHTML = parent.innerHTML;
                        child.remove();
                        if(lastHTML == '<i class="bi bi-star"></i>') parent.innerHTML = "<i class='bi bi-star-fill'>";
                        else parent.innerHTML = "<i class='bi bi-star'></i>";

                        for(var j = 1; j < 5; j++){
                            startContainer.children[j].innerHTML = "<i class='bi bi-star'>"
                        }
                    } else {
                        for(var j = 0; j < 5; j++){
                            var child = startContainer.children[j];
                            if(j >= target.id) child.innerHTML = "<i class='bi bi-star'></i>";
                            else child.innerHTML = "<i class='bi bi-star-fill'>"
                        }
                    }
                }
            };
        }
    </script>
</body>
</html>