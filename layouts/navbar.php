<?php function layout_navbar($selection=null) {
$link_selection = ["home" => "", "links" => "", "campaigns" => "", "account" => ""];
$link_selection[$selection] = "link__selected";
$user = get_user();
$navbar_state = $_COOKIE["navbar_state"] ?? null;
$minimized = $navbar_state && $navbar_state === "minimized" ? "minimized" : "";
?>
<script type="text/javascript">
    window.addEventListener("pageshow", async () => {
        if (!getCookie("token")) window.location.href = url(`login/?required`)
    })
</script>
<button class="navbar--minimize <?= $minimized ?>" id="minimize" style="display: none;"><i class="fa-solid fa-chevron-left"></i></button>
<nav class="mobileNavbar">
    <div class="mobileNavbar--header">
        <button class="mobileNavbar--button" id="mobileNavbar--button"><i class="fa-solid fa-bars"></i></button>
        <img src="assets/favicon.png" alt="Logo" class="mobileNavbar--logo">
        <a href="dashboard/account/" class="mobileNavbar--button <?= $link_selection["account"] ?>">
            <i class="fa-solid fa-circle-user"></i>
        </a>
    </div>
    <div class="mobileNavbar--menu" style="display: none;" id="mobileNavbar--menu">
        <h2 class="mobileNavbar--title">Tableau de bord</h2>
        <hr class="mobileNavbar--separator">
        <a href="dashboard/" class="mobileNavbar--link <?= $link_selection["home"] ?>">
            <i class="fa-solid fa-house"></i>
            Accueil
        </a>
        <a href="dashboard/links/" class="mobileNavbar--link <?= $link_selection["links"] ?>">
            <i class="fa-solid fa-link"></i>
            Liens
        </a>
        <a href="dashboard/campaigns/" class="mobileNavbar--link <?= $link_selection["campaigns"] ?>">
            <i class="fa-solid fa-layer-group"></i>
            Campagnes
        </a>
        <hr class="mobileNavbar--separator">
        <a href="dashboard/account/" class="mobileNavbar--link <?= $link_selection["account"] ?>">
            <i class="fa-solid fa-circle-user"></i>
            Mon compte
        </a>
    </div>
</nav>
<nav class="navbar <?= $minimized ?>">
    <div class="navbar--header navbar--aligned">
        <img src="assets/favicon.png" alt="Logo" class="navbar--logo">
        <h2 class="navbar--title minimizable">Tableau de bord</h2>
    </div>
    <div class="navbar--groupContainer">
        <div class="navbar--group">
            <hr class="navbar--separator">
            <a href="dashboard/" class="navbar--link <?= $link_selection["home"] ?>">
                <i class="fa-solid fa-house"></i>
                <span class="minimizable">Accueil</span>
            </a>
            <a href="dashboard/links/" class="navbar--link <?= $link_selection["links"] ?>">
                <i class="fa-solid fa-link"></i>
                <span class="minimizable">Liens</span>
            </a>
            <a href="dashboard/campaigns/" class="navbar--link <?= $link_selection["campaigns"] ?>">
                <i class="fa-solid fa-layer-group"></i>
                <span class="minimizable">Campagnes</span>
            </a>
        </div>
        <div class="navbar--group">
            <a href="dashboard/account/" class="navbar--link navbar--profile <?= $link_selection["account"] ?>">
                <i class="fa-solid fa-circle-user"></i>
                <span class="minimizable"><?= $user["username"] ?></span>
            </a>
            <a href="logout/" class="navbar--link">
                <i class="fa-solid fa-sign-out-alt"></i>
                <span class="minimizable">Déconnexion</span>
            </a>
        </div>
    </div>
</nav>
<?php } ?>