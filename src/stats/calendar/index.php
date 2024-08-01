<?php

return [
    "js" => ["js/stats/calendar/index.js", "js/popup.js"],
    "css" => ["css/popup.css"]
];

function render_stats_calendar($link, $year, $month) {
    $months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]; ?>
    <main class="page">
        <span linkData='<?= json_encode($link) ?>' hidden></span>
        <span viewData='<?= json_encode(["year" => intval($year), "month" => intval($month)]) ?>' hidden></span>
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
            <h1 class="page--title"><?= $link["name"] ?></h1>
            <p class="page--description">
                Statistiques du mois de <?= $months[$month-1] ?> <?= $year ?>.
            </p>
        </div>
        <div class="calendar">
            <div class="page--actions">
                <button class="page--action calendar--back">
                    <i class="fas fa-arrow-left"></i>
                    Précédent
                </button>
                <button class="page--action calendar--warp">
                    <i class="fas fa-spinner fa-spin" id="calendar--date--icon"></i>
                    <span class="calendar--date" style="display: none;"></span>
                </button>
                <button class="page--action calendar--next">
                    Suivant
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            <div class="calendar--options">
                <label class="calendar--option" style="--color: var(--main);" for="calendar--option--total">
                    <input type="radio" name="calendar--option" value="total" id="calendar--option--total" checked>
                    <i class="fas fa-eye"></i>Visites
                </label>
                <label class="calendar--option" style="--color: var(--mainDark);" for="calendar--option--unique">
                    <input type="radio" name="calendar--option" value="unique" id="calendar--option--unique">
                    <i class="fas fa-users"></i>Visiteurs uniques
                </label>
            </div>
            <div class="calendar--content"></div>
        </div>
    </main>
<?php } ?>