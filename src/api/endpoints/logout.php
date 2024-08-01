<?php

function endpoint_logout($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];

    $session = sql_query("SELECT * FROM sessions WHERE token = ?", [$_COOKIE["token"]], format: "first");
    if (!$session) return [ "success" => false, "code" => "LOGOUT_FAILED" ];

    sql_query("DELETE FROM sessions WHERE token = ?", [$_COOKIE["token"]]);

    return [ "success" => true, "code" => "LOGOUT_SUCCESS" ];
}

?>