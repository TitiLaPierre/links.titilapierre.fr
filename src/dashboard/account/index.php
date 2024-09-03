<?php

return [ "title" => "Tableau de bord — Compte" ];

function render_dashboard_account() { 
    $user = get_user(); ?>
    <main class="page">
        <div class="page--infos">
            <h1 class="page--title">Votre Compte</h1>
            <p class="page--description">
                Gérez vos informations de connexion et les paramètres de votre compte.
            </p>
        </div>
        <section class="page--section">
            <h2 class="page--subtitle">Informations</h2>
            <div class="accountInfos">
                <i class="fa-solid fa-circle-user accountInfos--icon"></i>
                <div class="accountInfos--description">
                    <span class="presentation--title">Nom d'utilisateur</span>
                    <span id="username"><?= $user["username"] ?></span>
                </div>
            </div>
            <span class="accountInfos--creationDate">Créé le <?= date("d/m/Y", $user["creation_date"]) ?> à <?= date("H:i", $user["creation_date"]) ?>.</span>
            <h2 class="page--subtitle">Mot de passe</h2>
            <div class="page--actions updatePassword">
                <button id="updatePassword--button" class="page--action">
                    <i class="fa-solid fa-edit"></i>
                    Modifier votre mot de passe
                </button>
            </div>
        </section>
    </main>
<?php } ?>