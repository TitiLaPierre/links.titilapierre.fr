<?php

function validate_year($year) {
    return preg_match("/^[0-9]{1,4}$/", $year);
}

function validate_month($month) {
    return preg_match("/^[0-9]{1,2}$/", $month) && $month >= 1 && $month <= 12;
}

function validate_day($day, $month, $year) {
    return preg_match("/^[0-9]{1,2}$/", $day) && $day >= 1 && $day <= 31 && checkdate($month, $day, $year);
}

if (!is_logged()) {
    redirect("/login/");
} else if (count(ARGUMENTS) > 4) {
    redirect("/dashboard/links/");
} else {
    $linkId = ARGUMENTS[0] ?? null;
    $year = ARGUMENTS[1] ?? null;
    $month = ARGUMENTS[2] ?? null;
    $day = ARGUMENTS[3] ?? null;
    
    $link = sql_query("SELECT * FROM links WHERE `uid` = ?", [$linkId], format: "first");
    if (!$link)
        redirect("/dashboard/links/");
    else if ($link["user_id"] != get_user()["id"])
        redirect("/dashboard/links/");

    if (empty($year) || !validate_year($year))
        redirect("/stats/".$link["uid"]."/".date("Y")."/".date("m")."/");
    else if (empty($month) || !validate_month($month))
        redirect("/stats/".$link["uid"]."/".$year."/".date("m")."/");
    else if (empty($day)) {
        return combine_settings([
            "title" => "Statistiques — ".$link["name"],
            "css" => ["css/dashboard.css", "css/page.css", "css/stats/index.css"],
            "js" => ["js/dashboard.js"],
            "year" => $year,
            "month" => $month,
            "link" => $link,
            "day" => null
        ], require_once("src/stats/calendar/index.php"));
    } else if (!validate_day($day, $month, $year))
        redirect("/stats/".$link["uid"]."/".$year."/".$month."/");
    else {
        return combine_settings([
            "title" => "Statistiques — ".$link["name"],
            "css" => ["css/dashboard.css", "css/page.css", "css/stats/index.css"],
            "year" => $year,
            "month" => $month,
            "day" => $day,
            "link" => $link
        ], require_once("src/stats/day/index.php"));
    }

}

function render($settings) {
    [ "year" => $year, "month" => $month, "day" => $day, "link" => $link ] = $settings;

    require_once("layouts/navbar.php");
    layout_navbar();

    if (empty($day)) {
        render_stats_calendar($link, $year, $month);
    } else {
        render_stats_day($link, $year, $month, $day);
    }
}

?>