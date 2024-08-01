<?php
if (is_logged()) {
    redirect("/dashboard/");
} else if (count(ARGUMENTS) > 0) {
    redirect("/login/");
} else {
    layout_header("Connexion au Tableau de bord");
    view_login();
    layout_footer();
}
?>