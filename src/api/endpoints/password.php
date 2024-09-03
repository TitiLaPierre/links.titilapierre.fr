<?php

function endpoint_change_password($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];
    if (empty($data["password"])) return [ "success" => false, "code" => "INVALID_FIELDS" ];

    $password = password_hash($data["password"], PASSWORD_DEFAULT);
    sql_query("UPDATE users SET password = ? WHERE id = ?", [$password, $params["uid"]]);
    return [ "success" => true, "code" => "PASSWORD_UPDATED" ];
}

?>