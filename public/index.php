<?php

define('ROOT', dirname(__DIR__));
define('APP',  ROOT . '/app');

require ROOT . '/config/env.php';

session_name(getenv('SESSION_NAME') ?: 'taskmanager_session');
session_set_cookie_params([
    'lifetime' => (int)(getenv('SESSION_LIFETIME') ?: 3600),
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

require ROOT . '/config/database.php';
require APP  . '/helpers.php';

require APP . '/models/User.php';
require APP . '/models/Task.php';
require APP . '/models/TaskLog.php';
require APP . '/models/Tag.php';
require APP . '/models/Category.php';
require APP . '/models/UserSettings.php';

require APP . '/controllers/AuthController.php';
require APP . '/controllers/TaskController.php';
require APP . '/controllers/SettingsController.php';

$page   = $_GET['page']   ?? 'tasks';
$action = $_GET['action'] ?? null;
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($page) {

    case 'login':
        $ctrl = new AuthController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $ctrl->login()
            : $ctrl->loginForm();
        break;

    case 'register':
        $ctrl = new AuthController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $ctrl->register()
            : $ctrl->registerForm();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'tasks':
        $ctrl = new TaskController();
        match ($action) {
            'create' => $ctrl->create(),
            'store'  => $ctrl->store(),
            'edit'   => $ctrl->edit($id),
            'update' => $ctrl->update($id),
            'delete' => $ctrl->delete($id),
            default  => $ctrl->index(),
        };
        break;

    case 'settings':
        $ctrl = new SettingsController();
        ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update')
            ? $ctrl->update()
            : $ctrl->index();
        break;

    default:
        http_response_code(404);
        require APP . '/views/layouts/404.php';
        break;
}
