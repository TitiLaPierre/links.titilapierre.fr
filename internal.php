<?php

function get_session() {
	if (!empty($_GLOBALS["session"])) return $GLOBALS["session"];
	if (empty($_COOKIE["token"])) return null;
	$session = sql_query("SELECT * FROM sessions WHERE token = ?", [$_COOKIE["token"]], format: "first");
	if (empty($session)) return null;
	if ($session["creation_date"]+60*60*24*7 < time()) {
		sql_query("DELETE FROM sessions WHERE token = ?", [$_COOKIE["token"]]);
		$session = null;
	}
	$GLOBALS["session"] = $session;
	return $session;
}

function get_user() {
	$session = get_session();
	$user = sql_query("SELECT * FROM users WHERE id = ?", [$session["user_id"]], format: "first");
	return $user;
}

function is_logged() {
	return !empty(get_session());
}

function redirect($location) {
	header("Location: $location");
	exit();
}

function sql_connect() {
    $GLOBALS["DATABASE"] = mysqli_init();
    $GLOBALS["DATABASE"]->real_connect(DATABASE_INFOS["hostname"], DATABASE_INFOS["username"], DATABASE_INFOS["password"], DATABASE_INFOS["database"]);
    $GLOBALS["DATABASE"]->set_charset("utf8");
}

function sql_disconnect() {
    $GLOBALS["DATABASE"]->close();
}

function sql_query($sql, $params=[], $format=null) {
    $request = $GLOBALS["DATABASE"]->prepare($sql);
    if (!$request) return null;

    $types = str_repeat("s", count($params));
    if (!empty($params)) $request->bind_param($types, ...$params);
    $request->execute();

    $result = $request->get_result();
    if (!$result) return null;

    if ($format == "array") return $result->fetch_all(MYSQLI_ASSOC);
    else if ($format == "first") return $result->fetch_all(MYSQLI_ASSOC)[0] ?? null;
}

function generate_token() {
	return substr(base64_encode(sha1(mt_rand())), 0, 32);
}

function generate_random_string($length=8) {
	$characters = 'abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, $charactersLength - 1)];
	return $randomString;
}

function generate_uid() {
	return generate_random_string(4)."-".generate_random_string(4)."-".generate_random_string(4)."-".generate_random_string(4);
}

function combine_settings($parentSettings, $childSettings) {
	$settings = $parentSettings;
	foreach ($childSettings as $key => $value) {
		if (is_array($value) && isset($settings[$key])) $settings[$key] = array_merge($settings[$key], $value);
		else $settings[$key] = $value;
	}
	return $settings;
}

function create_media($category, $url, $owner) {
    if (empty($category) || empty($url) || empty($owner)) return null;
    $ok = true;

    try {
        $responses = [];
        $regex = "/^HTTP\/\d+(?>\.\d+)? (\d{3}) [A-Za-z ]+$/m";
        foreach (get_headers($url) as $header) {
            if (preg_match($regex, $header, $matches)) $responses[] = $matches[1];
        }
        if (end($responses) != "200") {
            $ok = false;
        }
    } catch (Exception $e) {}

    if ($ok) {
        $media_uid = generate_uid();
        $media_path = "medias/".$category."/".$media_uid.".png";

        if (!file_exists("medias/".$category."/")) mkdir("medias/".$category."/", 0777, true);
        file_put_contents($media_path, file_get_contents($url));

        sql_query("INSERT INTO medias (uid, category, path, type, owner_id, creation_date) VALUES (?, ?, ?, ?, ?, ?)", [$media_uid, $category, $media_path, "image/png", $owner["id"], time()]);
    }
    return $ok ? $media_uid : null;
}

function delete_media($media_uid) {
    $user = get_user();
    if (empty($media_uid) || empty($user)) return null;

    $media = sql_query("SELECT * FROM medias WHERE uid = ?", [$media_uid], format: "first");
    if (isset($media) && $media["owner_id"] == $user["id"]) {
        unlink($media["path"]);
        sql_query("DELETE FROM medias WHERE uid = ?", [$media_uid]);
    }
}

?>