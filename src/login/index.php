<?php

if (is_logged()) {
    redirect("/dashboard/");
} else if (count(ARGUMENTS) > 0) {
    redirect("/login/");
}

return [
    "title" => "Connexion au Tableau de bord",
    "css" => ["css/login/index.css"],
    "js" => ["js/login/index.js"]
];

function render() { ?>
    <div class="pageBox--wrapper">
        <main class="login">
            <div class="login--container">
                <img class="login--logo" src="assets/favicon.png" alt="TitiLaPierre">
                <div class="login--part">
                    <h2 class="login--title">TitiLaPierre</h2>
                    <p class="login--description">Gestion de liens inter-sites.</p>
                    <span class="login--information information__success" style="opacity: 0;" id="information">Une erreur est survenue.</span>
                </div>
                <div class="login--part">
                    <label class="login--field" for="username">Identifiant</label>
                    <input class="login--input" autocomplete="off" type="text" id="username" name="username" placeholder="Entrez votre identifiant">
                </div>
                <div class="login--part">
                    <label class="login--field" for="password">Mot de passe</label>
                    <input class="login--input" type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
                </div>
                <div class="login--part">
                    <button class="login--button" id="login">Se connecter</button>
                    <span class="login--note">Vous resterez connecté pendant <b>1 semaine</b>.</span>
                </div>
            </div>
            <span class="login--banner" style="background-image: url(<?= VERSION["base_url"] ?>assets/login.svg">
        </main>
    </div>
<?php }

?>