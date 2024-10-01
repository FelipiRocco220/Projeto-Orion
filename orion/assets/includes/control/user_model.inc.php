<?php
function get_user(object $database, int $id):Object {
    $user_query = "select username, tag, email, pfp, creation_date as cd, roles.role_id as r_id, roles.name as role from users join roles on roles.role_id = users.role_id where id = :user_id;";
    $user_cmd = $database->prepare($user_query);
    $user_cmd->bindParam(":user_id", $id);
    $user_cmd->execute();
    $user_exists = $user_cmd->rowCount();
    $user_fetch = $user_cmd->fetch(PDO::FETCH_ASSOC);

    if($user_exists == 0) return (object) array("exists"=>0);

    $profile_pic = 'default/gray.png';
    if($user_fetch["pfp"]) $profile_pic = $user_fetch["pfp"];
    $cd = date_create($user_fetch['cd']);
    $cd = date_format($cd,"H:i d/m/Y");

    $user_data = array(
        "username"=>trim($user_fetch["username"]),
        "tag"=>trim($user_fetch["tag"]),
        "email"=>trim($user_fetch["email"]),
        "pfp"=>trim($profile_pic),
        "cd"=>$cd,
        "role"=>trim($user_fetch["role"]),
        "role_id"=>trim($user_fetch["r_id"]),
        "exists"=>1
    );

    return (object) $user_data;
}

function get_username(object $database, int $id):String {
    $username_query = "select username from users where id = :user_id;";
    $username_cmd = $database->prepare($username_query);
    $username_cmd->bindParam(":user_id", $id);
    $username_cmd->execute();
    $username_fetch = $username_cmd->fetch();
    return trim($username_fetch[0]);
}

function get_tag(object $database, int $id):String {
    $tag_query = "select tag from users where id = :user_id;";
    $tag_cmd = $database->prepare($tag_query);
    $tag_cmd->bindParam(":user_id", $id);
    $tag_cmd->execute();
    $tag_fetch = $tag_cmd->fetch();
    return trim($tag_fetch[0]);
}

function get_email(object $database, int $id):String {
    $username_query = "select email from users where id = :user_id;";
    $username_cmd = $database->prepare($username_query);
    $username_cmd->bindParam(":user_id", $id);
    $username_cmd->execute();
    $username_fetch = $username_cmd->fetch();
    return trim($username_fetch[0]);
}

function get_role(object $database, int $id):String {
    $role_query = "select roles.name as role from users join roles on roles.role_id = users.role_id where id = :user_id;";
    $role_cmd = $database->prepare($role_query);
    $role_cmd->bindParam(":user_id", $id);
    $role_cmd->execute();
    $role_fetch = $role_cmd->fetch();
    return trim($role_fetch[0]);
}

function pfp_return($requestPfp){
    $pfp = 'default/gray.png';
    if(isset($requestPfp)) $pfp = $requestPfp;
    return $pfp;
}
function get_pfp(object $database, int $id):String {
    $pfp_query = "select pfp from users where id = :user_id;";
    $pfp_cmd = $database->prepare($pfp_query);
    $pfp_cmd->bindParam(":user_id", $id);
    $pfp_cmd->execute();
    $pfp_fetch = $pfp_cmd->fetch();

    $profile_pic = pfp_return($pfp_fetch["pfp"]);

    return trim($profile_pic);
}
function has_pfp(object $database, int $id):String {
    $pfp_query = "select pfp from users where id = :user_id;";
    $pfp_cmd = $database->prepare($pfp_query);
    $pfp_cmd->bindParam(":user_id", $id);
    $pfp_cmd->execute();
    $pfp_fetch = $pfp_cmd->fetch();

    $profile_pic = '';
    if($pfp_fetch) $profile_pic = $pfp_fetch["pfp"];

    return trim($profile_pic);
}