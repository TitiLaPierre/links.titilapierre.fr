<?php

$source_url = PAGE ?? null;
$link = sql_query("SELECT * FROM links WHERE `source_url` = ?", [$source_url], format: "first");

if ($link) {
    sql_query("INSERT INTO views (link_uid, ip, creation_date) VALUES (?, ?, ?)", [$link["uid"], $_SERVER["REMOTE_ADDR"] != "::1" ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1", time()]);
    sql_query("UPDATE links SET views=views+1, last_activity=? WHERE uid = ?", [time(), $link["uid"]]);
    redirect($link["redirect_url"]);
}

return ["css" => ["css/redirection.css"]];

function render($settings) { ?>
    <div class="error">
        <i class="error--icon fas fa-exclamation-triangle"></i>
        <h1 class="error--title">Lien innexistant</h1>
        <p class="error--description">
            Le lien que vous avez suivi n'<b>existe pas</b> ou a été <b>supprimé</b>. Il est possible que vous l'ayez <b>mal recopié</b>. Dans le cas échéant, vous pouvez demander à l'auteur du lien de vous <b>en renvoyer un nouveau</b>.
        </p>
        <a href="javascript:history.back()" class="error--button">
            <i class="fas fa-arrow-left"></i>
            Retourner à la page précédente
        </a>
    </div>
    <?php if (is_logged()) { ?>
        <a href="dashboard/" class="error--login">Accéder au Tableau de bord</a>
    <?php } else { ?>
        <a href="login/" class="error--login">Se connecter au Tableau de bord</a>
    <?php }
} ?>