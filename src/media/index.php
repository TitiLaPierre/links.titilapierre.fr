<?php

function error_fallback($code) {
    $titles = [
        403 => "403 — Accès refusé",
        404 => "404 — Ressource introuvable"
    ];
    $title = $titles[$code] ?? $code." — Une erreur est survenue";
    echo("<h1>$title</h1>");
    exit();
}

if (!is_logged()) {
    error_fallback(403);
} else if (count(ARGUMENTS) == 1) {
    $media = sql_query("SELECT * FROM medias WHERE `uid` = ?", [ARGUMENTS[0]], format: "first");
    if (empty($media))
        error_fallback(404);
    else if ($media["owner_id"] != get_user()["id"])
        error_fallback(404);
    else {
        ["path" => $path, "type" => $type] = $media;
        if (!file_exists($path)) {
            sql_query("DELETE FROM medias WHERE `uid` = ?", [ARGUMENTS[0]]);
            error_fallback(404);
        } else {
            header("Content-Type: $type");
            header("Content-Length: " . filesize($path));
            readfile($path);
        }
    }
} else
    error_fallback(404);

return null;

?>