<?php

return [ "title" => "Tableau de bord — Campagnes" ];

function render_dashboard_campaigns() { ?>
    <main class="page">
        <div class="page--infos">
            <div class="page--actions">
                <a class="page--action" href="create/campaign/">
                    <i class="fas fa-plus"></i>
                    Créer une campagne
                </a>
                <button class="page--action" onclick="this.querySelector('.page--search').focus()">
                    <i class="fas fa-search"></i>
                    <input class="page--search" type="text" placeholder="Rechercher">
                </button>
            </div>
            <h1 class="page--title">Vos Campagnes</h1>
            <p class="page--description">
                Retrouvez ici toutes les campagnes que vous avez créées.
            </p>
        </div>
    </main>
<?php } ?>