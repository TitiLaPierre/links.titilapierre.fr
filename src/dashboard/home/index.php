<?php

return [ "title" => "Tableau de board" ];

function render_dashboard_home() { ?>    
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
                <a class="presentation" href="create/campaign/">
                    <i class="presentation--icon fa-solid fa-layer-group"></i>
                    <h3 class="presentation--title">Lancer une campagne</h3>
                    <p class="presentation--description">Touchez plusieurs cibles, centralisez la récolte de statistiques.</p>
                    <span class="presentation--action">Commencer</span>
                    <span class="presentation--circle"></span>
                </a>
            </div>
        </section>
        <section class="page--section">
            <h2 class="page--subtitle">Activité récente</h2>
            <p class="page--description">
                Aucune activité récente enregistrée pour le moment.
            </p>
        </section>
    </main>
<?php } ?>