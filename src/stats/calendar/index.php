<?php

return [
    "js" => ["js/stats/index.js", "js/stats/calendar/index.js"],
    "css" => ["css/stats/calendar/index.css"]
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
            <div class="page--icon--wrapper">
            <?php if (isset($link["icon_uid"])) { ?>                
                <img class="page--icon" alt="Icone du lien" src="media/<?= $link["icon_uid"] ?>">
            <?php } else { ?>
                <span class="page--icon fas fa-earth-europe"></span>
            <?php } ?>
            </div>
            <h1 class="page--title"><?= $link["name"] ?></h1>
            <p class="page--description">
                Statistiques du lien par jour pour un mois donné.
            </p>
        </div>
        <div class="calendar">
            <div class="statsHeader">
                <div class="statsHeader--warp">
                    <button class="statsHeader--button" id="calendar--back"><i class="fas fa-arrow-left"></i></button>
                    <div class="statsHeader--date">
                        <input class="statsHeader--date--part" id="calendar--month" value="<?= $months[$month - 1] ?>" maxlength="10" />
                        <input class="statsHeader--date--part" id="calendar--year" value="<?= $year ?>" maxlength="4" />
                    </div>
                    <button class="statsHeader--button" id="calendar--next"><i class="fas fa-arrow-right"></i></button>
                    <button class="statsHeader--button" id="calendar--now"><i class="fa-solid fa-clock-rotate-left"></i></button>
                </div>
                <div class="statsHeader--modes">
                    <label class="statsHeader--mode" for="mode--total" style="--color: var(--main);">
                        <input type="radio" name="calendar--mode" value="total" id="mode--total" checked>
                        Vues
                    </label>
                    <label class="statsHeader--mode" for="mode--unique" style="--color: var(--mainDark);">
                        <input type="radio" name="calendar--mode" value="unique" id="mode--unique">
                        Vues uniques
                    </label>
                </div>
            </div>
            <div class="calendar--content"></div>
        </div>
    </main>
<?php } ?>