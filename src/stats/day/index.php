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
                    <i class="fa-solid fa-arrow-left"></i>
                    Voir la liste des liens
                </a>
                <a class="page--action" href="link/<?= $link["uid"] ?>/">
                    <i class="fa-solid fa-qrcode"></i>
                    Gérer le lien
                </a>
            </div>
            <div class="page--icon--wrapper">
            <?php if (isset($link["icon_uid"])) { ?>                
                <img class="page--icon" alt="Icone du lien" src="media/<?= $link["icon_uid"] ?>">
            <?php } else { ?>
                <span class="page--icon fa-solid fa-earth-europe"></span>
            <?php } ?>
            </div>
            <h1 class="page--title"><?= $link["name"] ?></h1>
            <p class="page--description">
                Statistiques du lien par adresse IP pour un jour donné.
            </p>
        </div>
        <div class="splitView">
            <div class="grid">
                <div class="statsHeader">
                    <div class="statsHeader--warp">
                        <button class="statsHeader--button" id="calendar--back"><i class="fa-solid fa-arrow-left"></i></button>
                        <div class="statsHeader--date">
                            <input class="statsHeader--date--part" id="calendar--day" value="<?= $day ?>" maxlength="2" />
                            <input class="statsHeader--date--part" id="calendar--month" value="<?= $months[$month - 1] ?>" maxlength="10" />
                            <input class="statsHeader--date--part" id="calendar--year" value="<?= $year ?>" maxlength="4" />
                        </div>
                        <button class="statsHeader--button" id="calendar--next"><i class="fa-solid fa-arrow-right"></i></button>
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
            <div class="sidebar" style="display: none;">
                <button class="sidebar--close">
                    <i class="fa-solid fa-xmark"></i>
                    Fermer
                </button>
                <hr class="sidebar--separator">
                <div class="sidebar--part">
                    <div>
                        <h3 class="sidebar--title">127.0.0.1</h3>
                        <h4 class="sidebar--description">Saint-Herblain, France</h4>
                    </div>
                    <a class="sidebar--tag" href="https://google.com/maps" target="_blank">
                        <i class="fa-solid fa-location-dot"></i>
                        Voir
                    </a>
                </div>
                <hr class="sidebar--separator">
                <div class="sidebar--part">
                    <h5 class="sidebar--subtitle">Activité</h5>
                    <div class="sidebar--activities"></div>
                </div>
                <hr class="sidebar--separator">
                <div class="sidebar--part">
                    <h5 class="sidebar--subtitle">Autre activité</h5>
                    <div class="sidebar--presentation">
                        <h6 class="sidebar--presentation--title">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Ce mois-ci
                        </h6>
                        <p class="sidebar--presentation--description">
                            L'utilisateur a utilisé le lien <b>N/A</b> ce mois-ci.
                        </p>
                    </div>
                    <div class="sidebar--presentation">
                        <h6 class="sidebar--presentation--title">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Depuis toujours
                        </h6>
                        <p class="sidebar--presentation--description">
                            L'utilisateur a utilisé le lien <b>N/A</b> depuis la création du lien.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php } ?>