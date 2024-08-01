<?php
if (is_logged()) {
    layout_header("Tableau de bord");
    view_logout();
    layout_footer();
} else redirect("/login/");
?>