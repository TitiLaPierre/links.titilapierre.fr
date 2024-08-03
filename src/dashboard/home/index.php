<?php

return [ "title" => "Tableau de board" ];

function render_dashboard_home() {
    require_once("layouts/link_preview.php");
    $user = get_user(); ?>    
    <main class="page">
        <div class="page--infos">
            <h1 class="page--title">Votre Tableau de bord</h1>
            <p class="page--description">
                Bienvenue sur votre tableau de bord. À partir d'ici vous pouvez gérer vos liens, vos campagnes et en suivre les statistiques.
            </p>
        </div>
        <section class="page--section">
            <h2 class="page--subtitle">Commencer à créer</h2>
            <div class="presentation--list">
                <a class="presentation" href="create/link/">
                    <i class="presentation--icon fa-solid fa-link"></i>
                    <h3 class="presentation--title">Créer un lien</h3>
                    <p class="presentation--description">Redirigez les utilistateurs et récolter des données analytiques sur ceux-ci.</p>
                    <span class="presentation--action">Commencer</span>
                    <span class="presentation--circle"></span>
                </a>
                <div class="presentation presentation__disabled">
                    <i class="presentation--icon fa-solid fa-layer-group"></i>
                    <h3 class="presentation--title">Lancer une campagne</h3>
                    <p class="presentation--description">Touchez plusieurs cibles, centralisez la récolte de statistiques.</p>
                    <span class="presentation--action">Commencer</span>
                    <span class="presentation--circle"></span>
                </div>
            </div>
        </section>
        <section class="page--section">
            <h2 class="page--subtitle">Activité récente</h2>
            <?php
                $links = sql_query("SELECT * FROM links WHERE user_id = ? AND UNIX_TIMESTAMP()-last_activity < 604800 ORDER BY last_activity DESC LIMIT 3", [$user["id"]], format: "array");
                if (count($links) > 0) { ?>
                    <div class="linkPreview--list">
                        <?php foreach ($links as $link) layout_link_preview($link) ?>
                    </div>
                    <div class="page--actions">
                        <a class="page--action" href="dashboard/links/"><i class="fa-solid fa-arrow-right"></i>Tout voir</a>
                    </div>
                <?php } else { ?>
                    <div class="page--warning">
                        <i class="page--warning--icon fa-solid fa-envelope-open"></i>
                        <div class="page--warning--content">
                            <div>
                                <h2 class="page--warning--title">Aucune activité récente</h2>
                                <p class="page--warning--description">
                                    Il n'y a rien à afficher comme information récente de cette semaine.
                                </p>
                            </div>
                            <span class="page--warning--action">Tout voir</span>
                        </div>
                    </div>
                <?php } ?>
        </section>
    </main>
<?php } ?>