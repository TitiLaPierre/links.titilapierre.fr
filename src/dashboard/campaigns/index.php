<?php

return [ "title" => "Tableau de bord — Campagnes" ];

function render_dashboard_campaigns() { ?>
    <main class="page">
        <div class="page--infos">
            <div class="page--actions">
                <button class="page--action" disabled>
                    <i class="fas fa-plus"></i>
                    Créer une campagne
                </button>
                <button class="page--action" onclick="this.querySelector('.page--search').focus()" disabled>
                    <i class="fas fa-search"></i>
                    <input class="page--search" type="text" placeholder="Rechercher" disabled>
                </button>
            </div>
            <h1 class="page--title">Vos Campagnes</h1>
            <p class="page--description">
                Retrouvez ici toutes les campagnes que vous avez créées.
            </p>
        </div>
        <section class="page--section">
            <h2 class="page--subtitle">Parcourir les campagnes</h2>
            <div class="linkPreview--list">
                <div class="page--warning">
                    <i class="page--warning--icon fa-solid fa-ban"></i>
                    <div class="page--warning--content">
                        <div>
                            <h2 class="page--warning--title">Indisponible pour le moment</h2>
                            <p class="page--warning--description">
                                Cette fonctionnalité n'est pas encore disponible, revenez plus tard !
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php } ?>