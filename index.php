<?php

require_once("internal.php");

try {
    $main = json_decode(file_get_contents("config.json"), true);
} catch (Exception $ignore) {
    http_response_code(500);
    exit();
}

define("VERSION_TYPE", $main["version"]["type"]);
define("VERSION_ID", $main["version"]["id"]);
define("WEBSITE_URL", $main["links"][VERSION_TYPE]);
define("DATABASE_INFOS", $main["database"][VERSION_TYPE]);

$path = !empty($_GET["to"]) ? $_GET["to"] : null;
while (str_ends_with($path, "/")) $path = substr($path, 0, -1);

$arguments = explode("/", $path);
$page = array_shift($arguments);

define("PAGE", $page);
define("ARGUMENTS", $arguments);

sql_connect();

if (file_exists("src/".$page."/index.php"))
    $file = "src/".$page."/index.php";
else
    $file = "src/redirection.php";

require_once("layouts/header_footer.php");
$settings = [ "title" => "Links", "css" => [], "js" => [] ];
$pageSettings = require_once($file);
if (is_array($pageSettings)) {
    $settings = combine_settings($settings, $pageSettings);
    layout_header($settings["title"], $settings["css"]);
    render($settings);
    layout_footer($settings["js"]);
}

sql_disconnect();

?>