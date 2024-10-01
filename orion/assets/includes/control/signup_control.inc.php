<?php
declare(strict_types=1);

function is_empty(string $name, string $tag, string $pwd, string $email, string $birth):Bool {
    $data = [$name, $tag, $pwd, $email, $birth];
    foreach ($data as $d) if(empty(trim($d))) return true;
    return false;
}

function valid_username(string $name):Bool {
    $size = strlen(trim($name));
    if($size == 0){
        $_SESSION["signup_error"] = "Nome de usuario inválido.";
        return false;
    }
    if($size > 60){
        $_SESSION["signup_error"] = "Nome de usuario muito grande (" . $size . "/60)";
        return false;
    }
    if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $name)){
        $_SESSION["signup_error"] = "Nome de usuario não deve ter caracteres especiais.";
        return false;
    }
    return true;
}

function valid_tag(string $tag):Bool {
    $size = strlen(trim($tag));
    if($size == 0) {
        $_SESSION["signup_error"] = "Tag inválida.";
        return false;
    }
    if($size > 50) {
        $_SESSION["signup_error"] = "Tag muito grande (" . $size . "/50)";
        return false;
    }
    if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)=\{\}\[\]\|;:"\<\>,\?\\\]/', $tag)) {
        $_SESSION["signup_error"] = "Tag apenas pode conter alguns caracteres especiais. (_-+.)";
        return false;
    }
    return true;
}

function valid_email(object $database, string $email, array $strict_emails, array $strict_emails_ends):Bool {
    $email = trim($email);
    $size = strlen($email);
    if($size == 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["signup_error"] = "Email inválido.";
        return false;
    }
    if($size > 100) {
        $_SESSION["signup_error"] = "Email muito grande (" . $size . "/100)";
        return false;
    }

    $fend = explode("@", $email)[1];
    $end = explode(".", $fend)[0];
    if(!in_array($end, $strict_emails)){
        $_SESSION["signup_error"] = "Email com endereço não permitido ('$fend')";
        return false;
    }

    $dot_array = explode(".", $fend);
    array_shift($dot_array);
    $dot = join(".", $dot_array);
    if(!in_array($dot, $strict_emails_ends)){
        $_SESSION["signup_error"] = "Email com final de endereço não permitido ('$dot')";
        return false;
    }

    if(email_exists($database, $email)){
        $_SESSION["signup_error"] = "Email já está em uso";
        return false;
    }

    return true;
}

function tag_taken(object $database, string $tag) {
    $tag = trim($tag);
    $tag_query = "select tag from users where tag = :user_tag;";
    $tag_cmd = $database->prepare($tag_query);
    $tag_cmd->bindParam(":user_tag", $tag);
    $tag_cmd->execute();
    $tag_fetch = $tag_cmd->fetch(PDO::FETCH_ASSOC);
    if($tag_fetch) return true;
    else return false;
}

function email_exists(object $database, string $email){
    $email_query = "select id from users where email = :user_email;";
    $email_cmd = $database->prepare($email_query);
    $email_cmd->bindParam(":user_email", $email);
    $email_cmd->execute();
    $email_fetch = $email_cmd->fetch(PDO::FETCH_ASSOC);
    if($email_fetch) return true;
    else return false;
}

function valid_pwd(string $pwd, bool $perm = false):Bool {
    $pwd = trim($pwd);

    $size = strlen($pwd);
    if($perm){
        if($size <= 8) {
            $_SESSION["signup_error"] = "Senha deve ter mais que 8 caracteres.";
            return false;
        }
        if(is_numeric($pwd)) {
            $_SESSION["signup_error"] = "Senha deve ter letras.";
            return false;
        }
        if(!preg_match('#[0-9]#', $pwd)) {
            $_SESSION["signup_error"] = "Senha deve ter pelo menos um número.";
            return false;
        }
        if(!preg_match('/[A-Z]/', $pwd)) {
            $_SESSION["signup_error"] = "Senha deve ter pelo menos uma letra maiúscula.";
            return false;
        }
        if(!preg_match('/[a-z]/', $pwd)) {
            $_SESSION["signup_error"] = "Senha deve ter pelo menos uma letra minúscula.";
            return false;
        }
        if(!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $pwd)) {
            $_SESSION["signup_error"] = "Senha deve ter pelo menos um caractere especial.";
            return false;
        }
    } else {
        if($size < 3) {
            $_SESSION["signup_error"] = "Senha deve ter mais que 3 caracteres.";
            return false;
        }
    }
    return true;
}

function valid_date(string $date, string $format = 'Y-m-d'):Bool {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
function valid_age(string $date, int $age_limit):Bool {
    $age = calculate_age($date);
    
    if($age <= $age_limit) return false;
    return true;
}

function create_user(object $database, string $username, string $tag, string $email, string $birth, string $pwd):Bool {
    try {
        $query = "insert into users(username, tag, email, birth_date, password, role_id) values (:username, :tag, :email, :birth_date, :password, 0);";
        $cmd = $database->prepare($query);
        $cmd->bindParam(":username", $username);
        $cmd->bindParam(":tag", $tag);
        $cmd->bindParam(":email", $email);
        $cmd->bindParam(":birth_date", $birth);
        $pwd_hashed = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
        $cmd->bindParam(":password", $pwd_hashed);
        $cmd->execute();
        return true;
    } catch (\Throwable $th) {
        return false;
    }
}