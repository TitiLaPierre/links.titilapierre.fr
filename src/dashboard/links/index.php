<?php

return [ "title" => "Tableau de bord — Liens" ];

function render_dashboard_links() {
    $user = get_user(); ?>
    <main class="page">
        <div class="page--infos">
            <div class="page--actions">
                <a class="page--action" href="create/link/">
                    <i class="fas fa-plus"></i>
                    Créer un lien
                </a>
                <button class="page--action" onclick="this.querySelector('.page--search').focus()">
                    <i class="fas fa-search"></i>
                    <input class="page--search" type="text" placeholder="Rechercher">
                </button>
            </div>
            <h1 class="page--title">Vos Liens</h1>
            <p class="page--description">
                Retrouvez ici tous les liens que vous avez créés.
            </p>
        </div>
        <section class="page--section">
            <h2 class="page--subtitle">Parcourir les liens</h2>
            <div class="linkPreview--list">
                <?php
                    $links = sql_query("SELECT * FROM links WHERE user_id = ? ORDER BY last_activity DESC", [$user["id"]], format: "array");
                    foreach ($links as $link) { ?>
                        <a class="linkPreview" href="link/<?= $link["uid"] ?>/">
                            <div class="linkPreview--content">
                                <?php if (empty($link["media_uid"])) { ?>
                                    <i class="linkPreview--icon fa-solid fa-earth-europe"></i>
                                <?php } else { ?>
                                    <img class="linkPreview--icon" src="media/<?= $link["media_uid"] ?>">
                                <?php } ?>
                                <div>
                                    <h2 class="linkPreview--title"><?= $link["name"] ?></h2>
                                    <span class="linkPreview--description">
                                        <?php
                                        if ($link["views"] > 0) {
                                            $vues = $link["views"]." vue".($link["views"] > 1 ? "s" : "");
                                            $lastActivity = "Dernière activité le ".date("d/m/Y", $link["last_activity"]);
                                            $activity = $vues." — ".$lastActivity;
                                        } else {
                                            $activity = "Aucune activité — Créé le ".date("d/m/Y", $link["last_activity"]);
                                        }
                                        ?>
                                        <?= $activity ?>
                                    </span>
                                </div>
                            </div>
                            <div class="linkPreview--url--list">
                                <div class="linkPreview--url">
                                    <h6 class="linkPreview--url--label">Source</h6>
                                    <span class="linkPreview--url--value"><?= WEBSITE_URL ?><u><?= $link["source_url"] ?></u>/</span>
                                </div>
                                <div class="linkPreview--url">
                                    <h6 class="linkPreview--url--label">Destination</h6>
                                    <span class="linkPreview--url--value"><u><?= $link["redirect_url"] ?></u></span>
                                </div>
                            </div>
                        </a>
                <?php }  ?>
        </section>
    </main>
<?php } ?>