<?php

function API_responde($data) {
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

header("Content-Type: application/json; charset=utf-8");

$endpoints = [
    "login" => [
        [ "method" => "POST", "params" => [], "file" => "src/api/endpoints/login.php", "action" => "endpoint_login" ]
    ],
    "link" => [
        [ "method" => "POST", "params" => [], "file" => "src/api/endpoints/link.php", "action" => "endpoint_create_link" ],
        [ "method" => "GET", "params" => ["uid", "year", "month"], "file" => "src/api/endpoints/link.php", "action" => "endpoint_get_link_data" ],
        [ "method" => "PATCH", "params" => ["uid"], "file" => "src/api/endpoints/link.php", "action" => "endpoint_update_link" ],
        [ "method" => "DELETE", "params" => ["uid"], "file" => "src/api/endpoints/link.php", "action" => "endpoint_delete_link" ]
    ],
    "logout" => [
        [ "method" => "POST", "params" => [], "file" => "src/api/endpoints/logout.php", "action" => "endpoint_logout" ]
    ],
    "stats" => [
        [ "method" => "POST", "params" => ["uid"], "file" => "src/api/endpoints/stats.php", "action" => "endpoint_get_link_stats" ]
    ],
    "retreive_icon" => [
        [ "method" => "POST", "params" => ["uid"], "file" => "src/api/endpoints/icon.php", "action" => "endpoint_retreive_icon" ]
    ]
];

$endpoint = ARGUMENTS[0] ?? "";
$method = $_SERVER["REQUEST_METHOD"];

if (isset($endpoints[$endpoint])) {
    $endpoint = $endpoints[$endpoint];
    foreach ($endpoint as $entry) {
        if ($entry["method"] == $method && count($entry["params"]) == count(ARGUMENTS)-1) {
            $params = [];
            foreach ($entry["params"] as $i => $param) $params[$param] = ARGUMENTS[$i+1];
            $data = json_decode(file_get_contents("php://input"), true);
            try {
                require_once($entry["file"]);
                $data = $entry["action"]($params, $data);
            } catch (Exception $e) {
                http_response_code(500);
                $data = [ "success" => false, "code" => "INTERNAL_ERROR"];
            }
            API_responde($data);
        }
    }
}
API_responde([ "success" => false, "code" => "UNKNOW_ENDPOINT" ]);

return null;

?>