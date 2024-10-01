<?php
declare(strict_types=1);

function is_empty(string $name, string $dsc):Bool {
    $data = [$name, $dsc];
    foreach ($data as $d) if(empty(trim($d))) return true;
    return false;
}

function valid_author(object $database, int $id):Bool {
    $query = "select id from users where id = :id;";
    $cmd = $database->prepare($query);
    $cmd->bindParam(":id", $id);
    $cmd->execute();
    $fetch = $cmd->fetch(PDO::FETCH_ASSOC);
    if($fetch) return true;
    else return false;
}

function valid_title(string $title):Bool {
    $title = trim($title);

    $size = strlen($title);
    if($size == 0) {
        $_SESSION["add_novel_error"] = "Titulo inválido";
        return false;
    }
    if($size > 60) {
        $_SESSION["add_novel_error"] = "Titulo muito grande (" . $size . "/60)";
        return false;
    }
    return true;
}

function valid_desc(string $description):Bool {
    $title = trim($description);

    $size = strlen($description);
    if($size == 0) {
        $_SESSION["add_novel_error"] = "Descrição inválida";
        return false;
    }
    if($size > 255) {
        $_SESSION["add_novel_error"] = "Descrição muito grande (" . $size . "/255)";
        return false;
    }
    return true;
}