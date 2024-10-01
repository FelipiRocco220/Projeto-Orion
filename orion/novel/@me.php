<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .pfp{
            height: 30px;
            display: flex;
            gap: 10px;
        }

        .star{
            background: gray;
            width: 10px;
            height: 10px;
            float: left;
            margin-right: 10px;
        }
        .star.active{
            background: yellow;
        }
        .star.middleActive{
            position: relative;
        }
        .star.middleActive::before{
            content: "";
            display: block;
            background: yellow;
            width: 5px;
            height: 10px;
        }
    </style>
</head>
<body>
    <?php
        include_once('../assets/includes/dbc.inc.php');
        include_once('../assets/includes/session.inc.php');
        if(!isset($user_id)) {
            header("Location: ../index.php");
            die();
        }

        try {
            $query = "select * from novels where author_id = :author_id";
            $cmd = $db->prepare($query);
            $cmd->bindParam(":author_id", $user_id);
            $cmd->execute();

            echo"<h3>Minhas Novels:</h3>";
            if($cmd->rowCount() > 0){
                echo"<table>";
                echo"<tr>";
                echo "<td>thumbnail</td>";
                echo "<td>titulo</td>";
                echo "<td>descrição</td>";
                echo "<td>data de criação</td>";
                echo "<td>nota</td>";
                echo"</tr>";

                for($i = 0; $curNovel = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                    $date = date_create($curNovel['creation_date']);
                    $date = date_format($date,"H:i d/m/Y");
                    echo"<tr>";
                    echo "<td>" . "<img src='../assets/images/novel/thumbnail/" . $curNovel['thumbnail'] . "'><br>" . $curNovel['thumbnail'] . "</td>";
                    echo "<td>" . $curNovel['title'] . "</td>";
                    echo "<td>" . $curNovel['description'] . "</td>";
                    echo "<td>" . $date . "</td>";

                    $rat_query = "select rating from novels_ratings where novel_id = :novel_id;";
                    $rat_cmd = $db->prepare($rat_query);
                    $rat_cmd->bindParam(":novel_id", $curNovel["id"]);
                    $rat_cmd->execute();
                    $rat_length = $rat_cmd->rowCount();
                    if($rat_length == 0){
                        $rating = "nenhuma";
                    } else {
                        $rat_num = 0;
                        for($i = 0; $curRat = $rat_cmd->fetch(PDO::FETCH_ASSOC); $i++){
                            $rat_num += $curRat["rating"];
                        }
                        $rat_num /= $rat_length;
                        $rat_num = round($rat_num * 10) / 10;
                        $rating = $rat_num . " (" . $rat_length . ")";
                    }
                    echo "<td>";
                    echo $rating;
                    if($rat_length > 0){
                        $round_rat = round($rat_num * 2) / 2; // fat rat??!!
                        for($i = 0; $i < 5; $i++){
                            if($i + 1 <= ceil($round_rat)) {
                                if($i + 1 == ceil($round_rat) && $i + 1 != $round_rat) echo"<div class='star middleActive'></div>";
                                else echo"<div class='star active'></div>";
                            }
                            else echo"<div class='star'></div>";
                        }
                    }
                    echo "</td>";

                    echo"</tr>";
                }

                echo"</table>";
            } else {
                echo "Você não possui novels.";
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo "killmyself " . $th;
        }
    ?>
</body>
</html>