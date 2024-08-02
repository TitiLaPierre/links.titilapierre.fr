<?php

if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) == 1) {
    $link = sql_query("SELECT * FROM links WHERE `uid` = ?", [ARGUMENTS[0]], format: "first");
    if (!$link)
        redirect("/dashboard/links/");
    else if ($link["user_id"] != get_user()["id"])
        redirect("/dashboard/links/");
    return [
        "title" => "Lien — ".$link["name"],
        "css" => ["css/dashboard.css", "css/popup.css", "css/link/index.css", "css/copy.css"],
        "js" => ["js/dashboard.js", "js/link/index.js", "js/popup.js", "js/copy.js"],
        "link" => $link
    ];
} else {
    redirect("/dashboard/links/");
}

function render($settings) {
    [ "link" => $link ] = $settings;
    
    require_once("layouts/navbar.php");
    layout_navbar(); ?>
<main class="page">
    <span linkData='<?= json_encode($link) ?>' hidden></span>
    <div class="page--infos">
        <div class="page--actions">
            <a class="page--action" href="dashboard/links/">
                <i class="fas fa-arrow-left"></i>
                Voir la liste des liens
            </a>
            <a class="page--action" href="stats/<?= $link["uid"] ?>/">
                <i class="fas fa-chart-line"></i>
                Statistiques
            </a>
        </div>
        <button class="page--icon--wrapper">
            <span class="page--icon fas fa-earth-europe" <?php if (isset($link["icon_uid"])) { ?>style="display: none;"<?php } ?>></span>
            <img class="page--icon" alt="Icone du lien"  <?php if (empty($link["icon_uid"])) { ?>style="display: none;"<?php } else { ?>src="media/<?= $link["icon_uid"] ?>"<?php } ?>>
            <span class="page--icon--action"><i class="fa-solid fa-rotate-right"></i></span>
        </button>
        <h1 class="page--title"><?= $link["name"] ?></h1>
        <p class="page--description">
            Créé le <?= date("d/m/Y", $link["creation_date"]) ?> à <?= date("H:i", $link["creation_date"]) ?>.
        </p>
    </div>
    <div class="dual">
        <div class="dual--item">
            <h2 class="dual--title">Liens</h2>
            <div class="link">
                <div class="link--header">
                    <h3 class="link--title">Source</h3>
                    <p class="link--description">
                        Le traffic est redirigé à partir de cet URL.
                    </p>
                </div>
                <button class="link--url" copy="<?= WEBSITE_URL.$link["source_url"]."/" ?>">
                    <span><?= WEBSITE_URL?><u><?= $link["source_url"]?></u>/</span>
                    <i class="fas fa-copy" copied="fas fa-check"></i>
                </button>
            </div>
            <div class="link">
                <div class="link--header">
                    <h3 class="link--title">Destination</h3>
                    <p class="link--description">
                        Le traffic est redirigé vers cet URL.
                    </p>
                </div>
                <button class="link--url" copy="<?= $link["redirect_url"] ?>">
                    <u><?= $link["redirect_url"] ?></u>
                    <i class="fas fa-copy" copied="fas fa-check"></i>
                </button>
            </div>
        </div>
        <div class="dual--item">
            <h2 class="dual--title">
                QR Code
                <a class="dual--button" href="media/<?= $link["qrcode_uid"] ?>" download="qrcode_<?= $link["source_url"] ?>.png" target="_blank"><i class="fas fa-download"></i></a>
            </h2>
            <div>
                <img class="qrcode" src="media/<?= $link["qrcode_uid"] ?>" alt="QR Code">
            </div>
        </div>
    </div>
    <div class="page--actions">
        <button class="page--action" id="edit" disabled>
            <i class="fas fa-edit"></i>
            Modifier
        </button>
        <button class="page--action action__danger" id="delete">
            <i class="fas fa-trash"></i>
            Supprimer définitivement
        </button>
    </div>
</main>
<?php } ?>