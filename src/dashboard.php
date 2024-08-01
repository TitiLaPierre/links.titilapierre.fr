<?php
if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) == 0) {
    layout_header("Tableau de bord");
    view_dashboard();
    layout_footer();
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "links") {
    layout_header("Tableau de bord — Liens");
    view_dashboard_links();
    layout_footer();
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "campaigns") {
    layout_header("Tableau de bord — Campagnes");
    view_dashboard_campaigns();
    layout_footer();
} else {
    redirect("/dashboard/");
}
?>