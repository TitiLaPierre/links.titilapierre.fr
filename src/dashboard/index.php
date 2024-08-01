<?php

if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) == 0) {
    return combine_settings([
        "title" => "Tableau de bord",
        "css" => ["css/dashboard.css", "css/dashboard/index.css"],
        "js" => ["js/dashboard.js"],
        "action" => "render_dashboard_home",
        "navbar" => "home"
    ], require_once("src/dashboard/home/index.php"));
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "links") {
    return combine_settings([
        "title" => "Liens",
        "css" => ["css/dashboard.css", "css/dashboard/index.css"],
        "js" => ["js/dashboard.js"],
        "action" => "render_dashboard_links",
        "navbar" => "links"
    ], require_once("src/dashboard/links/index.php"));
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "campaigns") {
    return combine_settings([
        "title" => "Campagnes",
        "css" => ["css/dashboard.css"],
        "js" => ["js/dashboard.js"],
        "action" => "render_dashboard_campaigns",
        "navbar" => "campaigns"
    ], require_once("src/dashboard/campaigns/index.php"));
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