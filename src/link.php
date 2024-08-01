<?php

if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) == 1) {
    $link = sql_query("SELECT * FROM links WHERE `uid` = ?", [ARGUMENTS[0]], format: "first");
    if (!$link)
        redirect("/dashboard/links/");
    else if ($link["user_id"] != get_user()["id"])
        redirect("/dashboard/links/");


    layout_header("Lien — ".$link["name"]);
    view_link($link);
    layout_footer();
} else {
    redirect("/dashboard/links/");
}
?>