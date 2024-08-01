<?php
if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) == 1 && ARGUMENTS[0] == "link") {
    layout_header("Nouveau lien");
    view_create_link();
    layout_footer();
} else {
    redirect("/dashboard/");
}
?>