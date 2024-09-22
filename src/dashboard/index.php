<?php

if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) == 0) {
    return combine_settings([
        "title" => "Tableau de bord",
        "css" => ["css/dashboard.css", "css/page.css", "css/dashboard/index.css"],
        "js" => ["js/dashboard.js"],
        "action" => "render_dashboard_home",
        "navbar" => "home"
    ], require_once("src/dashboard/home/index.php"));
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "links") {
    return combine_settings([
        "title" => "Liens",
        "css" => ["css/dashboard.css", "css/page.css", "css/dashboard/index.css"],
        "js" => ["js/dashboard.js"],
        "action" => "render_dashboard_links",
        "navbar" => "links"
    ], require_once("src/dashboard/links/index.php"));
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "campaigns") {
    return combine_settings([
        "title" => "Campagnes",
        "css" => ["css/dashboard.css", "css/page.css", "css/dashboard/index.css"],
        "js" => ["js/dashboard.js"],
        "action" => "render_dashboard_campaigns",
        "navbar" => "campaigns"
    ], require_once("src/dashboard/campaigns/index.php"));
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "account") {
    return combine_settings([
        "title" => "Compte",
        "css" => ["css/dashboard.css", "css/page.css", "css/dashboard/index.css", "css/popup.css"],
        "js" => ["js/dashboard.js", "js/dashboard/account/index.js", "js/password.js", "js/popup.js"],
        "action" => "render_dashboard_account",
        "navbar" => "account"
    ], require_once("src/dashboard/account/index.php"));
} else {
    redirect("/dashboard/");
}

function render($settings) {
    [ "action" => $action, "navbar" => $navbar ] = $settings;

    require_once("layouts/navbar.php");
    layout_navbar($navbar);
    
    $action();
}

?>