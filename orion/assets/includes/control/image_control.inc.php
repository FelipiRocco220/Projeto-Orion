<?php
function valid_image(string $thumb):Bool {
    if(!isset($_FILES[$thumb])) return false;
    try {
        if(getimagesize($_FILES[$thumb]["tmp_name"]) === true) {
            $_SESSION["add_novel_error"] = "Imagem inválida.";
            return false;
        }
    } catch (\Throwable $th) {
        $_SESSION["add_novel_error"] = "Imagem inexistente.";
        return false;
    }

    if ($_FILES[$thumb]["size"] > 5000000) { // 5mb
        $_SESSION["add_novel_error"] = "Imagem muito grande.";
        return false;
    }
    $type = explode('.', $_FILES[$thumb]["name"]);
    $type = $type[count($type) - 1];
    if($type != "jpg" && $type != "png" && $type != "jpeg") {
        $_SESSION["add_novel_error"] = "Imagem de extensão inválida.";
        return false;
    }
    return true;
}