<?php

function endpoint_create_link($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];
    if (empty($data["name"]) || empty($data["redirect_url"])) return [ "success" => false, "code" => "INVALID_FIELDS" ];
    
    $name = htmlspecialchars($data["name"]); 
    $name = preg_replace("/\s+/", " ", $name);
    $source_url = empty($data["source_url"]) ? generate_random_string(8) : htmlspecialchars($data["source_url"]);
    $redirect_url = htmlspecialchars($data["redirect_url"]);
    
    if (!preg_match("/^[\p{L}\p{N} ]{1,64}$/u", $name)) return [ "success" => false, "code" => "INVALID_NAME" ];
    if (!preg_match("/^[a-zA-Z0-9\.\-_]{3,64}$/", $source_url)) return [ "success" => false, "code" => "INVALID_SOURCE" ];
    if (!preg_match("/^(?!mailto:)(?:(?:http|https|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?:(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[0-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff0}-9]+-?)*[a-z\x{00a1}-\x{ffff0}-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff0}-9]+-?)*[a-z\x{00a1}-\x{ffff0}-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))|localhost)(?::\d{2,5})?(?:(\/|\?|#)[^\s]*)?$/u", $redirect_url)) return [ "success" => false, "code" => "INVALID_REDIRECT" ];
    if (strlen($redirect_url) >= 2083) return [ "success" => false, "code" => "INVALID_REDIRECT" ];
    if (sql_query("SELECT * FROM links WHERE source_url = ?", [$source_url], format: "first")) return [ "success" => false, "code" => "LINK_ALREADY_EXISTS" ];

    $user = get_user();
    $uid = generate_uid();

    $creation_date = time();
    sql_query("INSERT INTO links (name, uid, source_url, redirect_url, user_id, creation_date, last_activity) VALUES (?, ?, ?, ?, ?, ?, ?)", [$name, $uid, $source_url, $redirect_url, $user["id"], $creation_date, $creation_date], format: null);

    $media_uid = create_media("icons", "https://www.google.com/s2/favicons?sz=64&domain=".$redirect_url, get_user());
    $qrcode_uid = create_media("qrcodes", "https://api.qrserver.com/v1/create-qr-code/?size=150x150&qzone=4&data=".WEBSITE_URL.$source_url, get_user());

    sql_query("UPDATE links SET media_uid = ?, qrcode_uid = ? WHERE uid = ?", [$media_uid, $qrcode_uid, $uid], format: null);

    return [ "success" => true, "code" => "LINK_CREATED", "data" => [ "uid" => $uid ] ];
}

function endpoint_update_link($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];

    $link = sql_query("SELECT * FROM links WHERE uid = ?", [$params["uid"]], format: "first");
    $user = get_user();
    if (!$link) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];
    if ($link["user_id"] != $user["id"]) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];

    if (!empty($data["name"])) {
        $link["name"] = htmlspecialchars($data["name"]);
        $link["name"] = preg_replace("/\s+/", " ", $link["name"]);
    }
    if (!empty($data["source_url"])) $link["source_url"] = htmlspecialchars($data["source_url"]);
    if (!empty($data["redirect_url"])) $link["redirect_url"] = htmlspecialchars($data["redirect_url"]);
    
    if (!preg_match("/^[\p{L}\p{N} ]{1,64}$/u", $link["name"])) return [ "success" => false, "code" => "INVALID_NAME" ];
    if (!preg_match("/^[a-zA-Z0-9\.\-_]{3,64}$/", $link["source_url"])) return [ "success" => false, "code" => "INVALID_SOURCE" ];
    if (!preg_match("/^(?!mailto:)(?:(?:http|https|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?:(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[0-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff0}-9]+-?)*[a-z\x{00a1}-\x{ffff0}-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff0}-9]+-?)*[a-z\x{00a1}-\x{ffff0}-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))|localhost)(?::\d{2,5})?(?:(\/|\?|#)[^\s]*)?$/u", $link["redirect_url"])) return [ "success" => false, "code" => "INVALID_REDIRECT" ];
    if (strlen($link["redirect_url"]) >= 2083) return [ "success" => false, "code" => "INVALID_REDIRECT" ];
    if (sql_query("SELECT * FROM links WHERE source_url = ? AND uid != ?", [$link["source_url"], $link["uid"]], format: "first")) return [ "success" => false, "code" => "LINK_ALREADY_EXISTS" ];

    sql_query("UPDATE links SET name = ?, source_url = ?, redirect_url = ? WHERE uid = ?", [$link["name"], $link["source_url"], $link["redirect_url"], $link["uid"]], format: null);

    return [ "success" => true, "code" => "LINK_EDITED" ];
}

function endpoint_delete_link($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];

    $link = sql_query("SELECT * FROM links WHERE uid = ?", [$params["uid"]], format: "first");
    $user = get_user();
    if (!$link) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];
    if ($link["user_id"] != $user["id"]) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];

    sql_query("DELETE FROM links WHERE uid = ?", [$link["uid"]], format: null);

    return [ "success" => true, "code" => "LINK_DELETED" ];
}

?>