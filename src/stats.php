<?php

if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) > 3) {
    redirect("/dashboard/links/");
} else {
    $linkId = ARGUMENTS[0] ?? null;
    $year = ARGUMENTS[1] ?? null;
    $month = ARGUMENTS[2] ?? null;
    
    $link = sql_query("SELECT * FROM links WHERE `uid` = ?", [$linkId], format: "first");
    if (!$link)
        redirect("/dashboard/links/");
    else if ($link["user_id"] != get_user()["id"])
        redirect("/dashboard/links/");

    else if (($year && !$month) || (is_numeric($year) && !is_numeric($month)))
        redirect("/stats/".$link["uid"]."/".$year."/01/");
    else if (!$year || !is_numeric($year))
        redirect("/stats/".$link["uid"]."/".date("Y")."/".date("m")."/");

    else if ($year && $month && !checkdate($month, 1, $year))
        redirect("/stats/".$link["uid"]."/".date("Y")."/".date("m")."/");

    else {
        layout_header("Statistiques — ".$link["name"]);
        view_stats($link, $year, $month);
        layout_footer();
    }

}
?>