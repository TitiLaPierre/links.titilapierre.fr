<?php

function endpoint_retreive_icon($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];

    $link = sql_query("SELECT * FROM links WHERE uid = ?", [$params["uid"]], format: "first");
    $user = get_user();

    if (!$link) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];
    if ($link["user_id"] != $user["id"]) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];

    delete_media($link["media_uid"]);

    $media_uid = create_media("icons", "https://www.google.com/s2/favicons?sz=64&domain=".$link["redirect_url"], get_user());
    sql_query("UPDATE links SET media_uid = ? WHERE uid = ?", [$media_uid, $params["uid"]]);

    return isset($media_uid) ? [ "success" => true, "code" => "ICON_UPDATED", "data" => [ "media_uid" => $media_uid ] ] : [ "success" => false, "code" => "ICON_ERROR" ];
}

?>