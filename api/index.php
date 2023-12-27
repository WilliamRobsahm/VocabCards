<?php
require __DIR__ . "/inc/bootstrap.php";
require __DIR__ . "/controller/api/LanguageController.php";
require __DIR__ . "/controller/api/CardController.php";
require __DIR__ . "/controller/api/DefinitionController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$uri = array_slice($uri, array_search("api", $uri) + 1);
// 0 -> "index.php", 1 -> controller name, 2 -> controller method

try {
    $controller = null;

    switch($uri[1]) {
        case "language": $controller = new LanguageController(); break;
        case "card": $controller = new CardController(); break;
        case "definition": $controller = new DefinitionController(); break;
    }

    if($controller === null) {
        throw new Exception("Invalid module");
    }

    $str_method_name = $uri[2] . '_action';
    if(method_exists($controller, $str_method_name) === false) {
        throw new Exception("Invalid method: '" . $uri[1] . "/" . $uri[2] . "'");
    }
    $controller->{$str_method_name}();
}
catch(Exception $e) {
    echo json_encode(Result::from_error($e));
    exit();
}
?>
