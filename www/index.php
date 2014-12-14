<?

$root_directory = __DIR__.'/../';
$app_directory = $root_directory.'/protected';
$components_directory = $app_directory.'/components';
$web_directory = $root_directory.'/www';

require_once $root_directory.'App.php';

$config = require_once $root_directory.'protected/config/main.php';
require_once $root_directory.'protected/config/routes.php';
//todo add init.php with .env
//todo add sql file
$app = new App($config);
$app->run();
