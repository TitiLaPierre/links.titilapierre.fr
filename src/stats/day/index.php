<?php

return [
    "js" => ["js/stats/index.js", "js/stats/day/index.js"],
    "css" => ["css/stats/day/index.css"]
];

function render_stats_day($link, $year, $month, $day) {
    $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
    $months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]; ?>
    <main class="page">
        <span linkData='<?= json_encode($link) ?>' hidden></span>
        <span viewData='<?= json_encode(["year" => intval($year), "month" => intval($month), "day" => intval($day)]) ?>' hidden></span>
        <div class="page--infos">
            <div class="page--actions">
                <a class="page--action" href="dashboard/links/">
                    <i class="fas fa-arrow-left"></i>
                    Voir la liste des liens
                </a>
                <a class="page--action" href="link/<?= $link["uid"] ?>/">
                    <i class="fas fa-qrcode"></i>
                    Gérer le lien
                </a>
            </div>
            <div class="page--icon--wrapper">
            <?php if (isset($link["icon_uid"])) { ?>                
                <img class="page--icon" alt="Icone du lien" src="media/<?= $link["icon_uid"] ?>">
            <?php } else { ?>
                <span class="page--icon fas fa-earth-europe"></span>
            <?php } ?>
            </div>
            <h1 class="page--title"><?= $link["name"] ?></h1>
            <p class="page--description">
                Statistiques du lien par adresse IP pour un jour donné.
            </p>
        </div>
        <div class="grid">
            <div class="statsHeader">
                <div class="statsHeader--warp">
                    <button class="statsHeader--button" id="calendar--back"><i class="fas fa-arrow-left"></i></button>
                    <div class="statsHeader--date">
                        <input class="statsHeader--date--part" id="calendar--day" value="<?= $day ?>" maxlength="2" />
                        <input class="statsHeader--date--part" id="calendar--month" value="<?= $months[$month - 1] ?>" maxlength="10" />
                        <input class="statsHeader--date--part" id="calendar--year" value="<?= $year ?>" maxlength="4" />
                    </div>
                    <button class="statsHeader--button" id="calendar--next"><i class="fas fa-arrow-right"></i></button>
                    <button class="statsHeader--button" id="calendar--now"><i class="fa-solid fa-clock-rotate-left"></i></button>
                </div>
                <a class="statsHeader--back" id="calendar--backToMonth" href="stats/<?= $link["uid"] ?>/<?= $year ?>/<?= $month ?>/">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span><?= $months[$month - 1] ?> <?= $year ?></span>
                </a>
            </div>
            <div class="grid--header">
                <span class="grid--column">Adresse IP</span>
                <span class="grid--column">Nombre de vues</span>
                <span class="grid--column">Dernier accès</span>
            </div>
            <div class="grid--content" style="display: none;"></div>
            <div class="page--warning" style="display: none;" id="grid--warning">
                <i class="page--warning--icon fa-solid fa-magnifying-glass"></i>
                <div>
                    <h2 class="page--warning--title">Aucune donnée</h2>
                    <p class="page--warning--description">
                        Ce lien n'a pas été utilisé ce jour là.
                    </p>
                </div>
            </div>
        </div>
    </main>
<?php } ?>