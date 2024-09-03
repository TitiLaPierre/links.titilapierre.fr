<?php

function endpoint_login($params, $data) {
    if (empty($data["username"]) || empty($data["password"])) return [ "success" => false, "code" => "INVALID_CREDENTIALS" ];
    
    $account = sql_query("SELECT * FROM users WHERE LOWER(username) = LOWER(?)", [$data["username"]], format: "first");
    if (!$account) return [ "success" => false, "code" => "INVALID_CREDENTIALS" ];
    if (!password_verify($data["password"], $account["password"])) return [ "success" => false, "code" => "INVALID_CREDENTIALS" ];

    $token = generate_token();
    sql_query("INSERT INTO sessions (user_id, token, creation_date) VALUES (?, ?, ?)", [$account["id"], $token, time()]);

    return [ "success" => true, "code" => "LOGIN_SUCCESS", "token" => $token, "uid" => $account["id"] ];
}

?>