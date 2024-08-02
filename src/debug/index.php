<?php

foreach (glob("medias/icons/*") as $file) unlink($file);
foreach (glob("medias/qrcodes/*") as $file) unlink($file);

if (file_exists("medias/icons")) rmdir("medias/icons");
if (file_exists("medias/qrcodes")) rmdir("medias/qrcodes");

$links = sql_query("SELECT * FROM links", [], $format="array");
sql_query("UPDATE links SET icon_uid = NULL, qrcode_uid = NULL");

sql_query("DELETE FROM medias WHERE 1");

foreach ($links as $link) {
    $owner = sql_query("SELECT * FROM users WHERE id = ?", [$link["user_id"]], $format="first");
    $icon_uid = create_media("icons", "https://www.google.com/s2/favicons?sz=64&domain=".$link["redirect_url"], $owner);
    $qrcode_uid = create_media("qrcodes", "https://api.qrserver.com/v1/create-qr-code/?size=150x150&qzone=4&data=".WEBSITE_URL.$link["source_url"], $owner);
    var_dump($owner, $icon_uid, $qrcode_uid);
    sql_query("UPDATE links SET icon_uid = ?, qrcode_uid = ? WHERE uid = ?", [$icon_uid, $qrcode_uid, $link["uid"]]);
}

return null;

?>