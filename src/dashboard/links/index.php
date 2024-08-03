<?php

return [ "title" => "Tableau de bord — Liens" ];

function render_dashboard_links() {
    require_once("layouts/link_preview.php");
    $user = get_user(); ?>
    <main class="page">
        <div class="page--infos">
            <div class="page--actions">
                <a class="page--action" href="create/link/">
                    <i class="fas fa-plus"></i>
                    Créer un lien
                </a>
                <button class="page--action" onclick="this.querySelector('.page--search').focus()" disabled>
                    <i class="fas fa-search"></i>
                    <input class="page--search" type="text" placeholder="Rechercher" disabled>
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
                    if (count($links) === 0) { ?>
                        <a class="page--warning" href="create/link/">
                            <i class="page--warning--icon fa-solid fa-magnifying-glass"></i>
                            <div class="page--warning--content">
                                <div>
                                    <h2 class="page--warning--title">Aucun lien créé</h2>
                                    <p class="page--warning--description">
                                        Vous n'avez pas créé de lien pour le moment, commencez dès maintenant !
                                    </p>
                                </div>
                                <span class="page--warning--action">Créer un lien</span>
                            </div>
                        </a>
                    <?php } foreach ($links as $link) layout_link_preview($link) ?>
        </section>
    </main>
<?php } ?>