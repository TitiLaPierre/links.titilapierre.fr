<?php

function endpoint_change_password($params, $data) {
    // Checks whether the user is connected to an account
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];
    // Checks whether all fields are present
    if (empty($data["username"]) || empty($data["oldPassword"]) || empty($data["newPassword"])) return [ "success" => false, "code" => "INVALID_FIELDS" ];
    // Checks whether the user is trying to change the credentials of the account to which they are connected
    $account = get_user();
    if ($data["username"] != $account["username"] || !password_verify($data["oldPassword"], $account["password"])) return [ "success" => false, "code" => "INVALID_CREDENTIALS" ];

    $new_password = password_hash($data["newPassword"], PASSWORD_DEFAULT);
    // TODO: Checks on the new password
    sql_query("UPDATE users SET password = ? WHERE id = ?", [$new_password, $account["id"]]);
    return [ "success" => true, "code" => "PASSWORD_UPDATED" ];
}

?>