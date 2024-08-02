<?php

return [
    "js" => ["js/stats/day/index.js"],
];

function render_stats_day($link, $year, $month, $day) {
    $months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]; ?>
    <main class="page">
        <span linkData='<?= json_encode($link) ?>' hidden></span>
        <span viewData='<?= json_encode(["year" => intval($year), "month" => intval($month), "day" => intval($day)]) ?>' hidden></span>
        <div class="page--infos">
            <div class="page--actions">
                <a class="page--action" href="stats/<?= $link["uid"] ?>/<?= $year ?>/<?= $month ?>/">
                    <i class="fas fa-arrow-left"></i>
                    Stats du <?= $month ?>/<?= $year ?>
                </a>
                <a class="page--action" href="link/<?= $link["uid"] ?>/">
                    <i class="fas fa-qrcode"></i>
                    Gérer le lien
                </a>
            </div>
            <h1 class="page--title"><?= $link["name"] ?></h1>
            <p class="page--description">
                Statistiques du <?= $day ?> <?= $months[$month-1] ?> <?= $year ?>.
            </p>
        </div>
        <div class="grid">
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
                        Ce lien n'a pas été utilisé le <?= $day ?> <?= $months[$month-1] ?> <?= $year ?>.
                    </p>
                </div>
            </div>
        </div>
    </main>
<?php } ?>