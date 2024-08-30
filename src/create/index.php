<?php
if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) != 1 || ARGUMENTS[0] != "link") {
    redirect("/dashboard/");
}

return [
    "title" => "Nouveau lien",
    "css" => ["css/dashboard.css", "css/page.css", "css/create/index.css"],
    "js" => ["js/dashboard.js", "js/create/index.js"]
];

function render($settings) {
    
    require_once("layouts/navbar.php");
    layout_navbar(); ?>
    <main class="page">
        <div class="page--infos">
            <div class="page--actions">
                <a class="page--action" href="dashboard/links/">
                    <i class="fa-solid fa-arrow-left"></i>
                    Voir la liste des liens
                </a>
                <div class="page--action">
                    <div class="page--steps">
                        <button class="page--step" state="current" step="0"></button>
                        <button class="page--step" state="locked" step="1"></button>
                        <button class="page--step" state="locked" step="2"></button>
                    </div>
                    <span class="page--step--separator">—</span>
                    <span class="page--step--name">Étape 1</span>
                </div>
            </div>
            <h1 class="page--title">Créer un nouveau lien</h1>
            <p class="page--description">
                Créez un lien personnalisé pour rediriger les utilisateurs vers une autre page. Vous pourrez suivre les statistiques affiliées à l'utilisation de ce lien.
            </p>
        </div>
        <section class="page--section">
            <input type="text" hidden id="form--step" value="0">
            <div class="form--step" title="Étape 1" style="display: none;">
                <div class="form--field" id="form--name">
                    <div class="form--field--header">
                        <div class="form--field--line">
                            <h3 class="form--field--title">Nom du lien</h3>
                            <span class="form--field--error" style="display: none;"></span>
                        </div>
                        <p class="form--field--description">Vous êtes le seul ayant la possibilité de voir ce nom.</p>
                    </div>
                    <input class="form--field--input" type="text" maxlength="64" field="name" placeholder="Nom du lien">
                </div>
            </div>
            <div class="form--step" title="Étape 2" style="display: none;">
                <div class="form--field" id="form--target">
                    <div class="form--field--header">
                        <div class="form--field--line">
                            <h3 class="form--field--title">URL de destination</h3>
                            <span class="form--field--error" style="display: none;"></span>
                        </div>
                        <p class="form--field--description">L'URL vers laquelle les utilisateurs seront redirigés.</p>
                    </div>
                    <input class="form--field--input" type="url" field="target" placeholder="URL de destination">
                </div>
            </div>
            <div class="form--step" title="Étape 3" style="display: none;">
                <div class="form--field" id="form--source">
                    <span class="form--tip">Champ facultatif</span>
                    <div class="form--field--header">
                        <div class="form--field--line">
                            <h3 class="form--field--title">URL source</h3>
                            <span class="form--field--error" style="display: none;"></span>
                        </div>
                        <p class="form--field--description">La fin du lien depuis lequel les utilisateurs arriveront.</p>
                        <span class="form--field--output" syncPattern="<?= VERSION["base_url"] ?>%input%" syncInput="form--source--input"><?= VERSION["base_url"] ?></span>
                    </div>
                    <input class="form--field--input" type="url" maxlength="64" field="source" placeholder="URL source" id="form--source--input">
                </div>
            </div>
        </section>
        <div class="page--actions">
            <button class="page--action form--back" style="display: none;">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Précédent</span>
            </button>
            <button class="page--action form--next" style="display: none;">
                <span>Suivant</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button> 
        </div>
    </main>
<?php } ?>