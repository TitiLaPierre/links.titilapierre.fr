<?php

$source_url = PAGE ?? null;
$link = sql_query("SELECT * FROM links WHERE `source_url` = ?", [$source_url], format: "first");

if ($link) {
    sql_query("INSERT INTO views (link_uid, ip, creation_date) VALUES (?, ?, ?)", [$link["uid"], $_SERVER["REMOTE_ADDR"] != "::1" ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1", time()]);
    sql_query("UPDATE links SET views = views+1 WHERE uid = ?", [$link["uid"]]);
    redirect($link["redirect_url"]);
} else {
    layout_header("TitiLaPierre");
    $files = glob("src/*.php");
    foreach ($files as $file) {
        $file = str_replace("src/", "", $file);
        $file = str_replace(".php", "", $file);
        if ($file != "link") { ?>
            <a href='<?= $file ?>/'><?= $file ?></a>
            <br>
        <?php }
    }
    layout_footer();
}

?>