<?php

$source_url = PAGE ?? null;
$link = sql_query("SELECT * FROM links WHERE `source_url` = ?", [$source_url], format: "first");

if ($link) {
    sql_query("INSERT INTO views (link_uid, ip, creation_date) VALUES (?, ?, ?)", [$link["uid"], $_SERVER["REMOTE_ADDR"] != "::1" ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1", time()]);
    sql_query("UPDATE links SET views=views+1, last_activity=? WHERE uid = ?", [time(), $link["uid"]]);
    redirect($link["redirect_url"]);
} else {
    $files = glob("src/*/index.php");
    foreach ($files as $file) {
        $file = str_replace("src/", "", $file);
        $file = str_replace("/index.php", "", $file);
        if ($file != "link") { ?>
            <a href='<?= $file ?>/'><?= $file ?></a>
            <br>
        <?php }
    }
}

?>