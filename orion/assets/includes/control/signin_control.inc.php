<?php
declare(strict_types=1);

function is_empty(string $email, string $pwd):Bool {
    $data = [$email, $pwd];
    foreach ($data as $d) if(empty(trim($d))) return true;
    return false;
}

function valid_email(string $email, array $strict_emails):Bool {
    $size = strlen(trim($email));
    if($size == 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["signin_error"] = "Email inválido.";
        return false;
    }
    if($size > 100) {
        $_SESSION["signin_error"] = "Email muito grande (" . $size . "/100)";
        return false;
    }

    $fend = explode("@", $email)[1];
    $end = explode(".", $fend)[0];
    if(!in_array($end, $strict_emails)){
        $_SESSION["signin_error"] = "Email com endereço não permitido ('$fend')";
        return false;
    }

    $dot_array = explode(".", $fend);
    array_shift($dot_array);
    $dot = join(".", $dot_array);
    if(!in_array($dot, $valid_emails_ends)){
        $_SESSION["signin_error"] = "Email com final de endereço não permitido";
        return false;
    }

    return true;
}

function valid_pwd(string $pwd):Bool {
    $pwd = trim($pwd);

    $size = strlen($pwd);
    if($size <= 3) return false;
    return true;
}

function log_user(object $database, string $email, string $pwd):Bool {
    try {
        $log_query = "select id, password as pwd from users where email = :user_email;";
        $log_cmd = $database->prepare($log_query);
        $log_cmd->bindParam(":user_email", $email);
        $log_cmd->execute();
        $log_fetch = $log_cmd->fetch(PDO::FETCH_ASSOC);
        if($log_fetch){
            $hashed_pwd = $log_fetch["pwd"];
            if(password_verify($pwd, $hashed_pwd)){
                $_SESSION['user_id'] = $log_fetch["id"];
                return true;
            } else {
                $_SESSION["signin_error"] = "Algo deu errado no login.";
                return false;
            }
        }
        $_SESSION["signin_error"] = "Algo deu errado no login.";
        return false;
    } catch (\Throwable $th) {
        $_SESSION["signin_error"] = "Erro de servidor :(";
        return false;
    }
}