<?php

function format_view($view) {
	$format = [
		"creation_date" => intval($view["creation_date"]),
		"ip" => $view["ip"]
	];
	return $format;
}

function endpoint_get_link_stats($params, $data) {
    if (!is_logged()) return [ "success" => false, "code" => "CONNECTION_REQUIRED" ];

    $link = sql_query("SELECT * FROM links WHERE uid = ?", [$params["uid"]], format: "first");
    $user = get_user();

    if (!$link) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];
    if ($link["user_id"] != $user["id"]) return [ "success" => false, "code" => "LINK_NOT_FOUND" ];
    if (empty($data) || empty($data["start"]) && empty($data["end"])) return [ "success" => false, "code" => "INVALID_RANGE" ];
    if (!is_numeric($data["start"]) || !is_numeric($data["end"])) return [ "success" => false, "code" => "INVALID_RANGE" ];

    $values = array_map("format_view",
        sql_query("SELECT * FROM views WHERE link_uid = ? AND creation_date >= ? AND creation_date <= ?", [$link["uid"], $data["start"], $data["end"]], format: "array")
    );

    return [ "success" => true, "code" => "LINK_DATA", "data" => $values ];
} ?>