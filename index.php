<?php
// Include configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Include controllers
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/EcoFacilitiesController.php';
require_once __DIR__ . '/controllers/ApiController.php';

// Parse URL
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Set default controller and method
$controller = !empty($url[0]) ? $url[0] : 'home';
$method = isset($url[1]) ? $url[1] : 'index';
$param = isset($url[2]) ? $url[2] : null;

// Initialize appropriate controller based on URL
switch ($controller) {
    case 'user':
        $userController = new UserController();

        switch ($method) {
            case 'login':
                $userController->login();
                break;
            case 'logout':
                $userController->logout();
                break;
            case 'profile':
                $userController->profile();
                break;
            case 'manage':
                $userController->manageUsers();
                break;
            case 'create':
                $userController->createUser();
                break;
            case 'edit':
                if ($param) {
                    $userController->editUser($param);
                } else {
                    redirect('/user/manage');
                }
                break;
            case 'delete':
                if ($param) {
                    $userController->deleteUser($param);
                } else {
                    redirect('/user/manage');
                }
                break;
            default:
                redirect('/');
                break;
        }
        break;

    case 'ecofacilities':
    case 'facility':
        $facilitiesController = new EcoFacilitiesController();

        switch ($method) {
            case 'index':
                $facilitiesController->index();
                break;
            case 'view':
                if ($param) {
                    $facilitiesController->view($param);
                } else {
                    redirect('/ecofacilities');
                }
                break;
            case 'search':
                $facilitiesController->search();
                break;
            case 'add-status':
                if ($param) {
                    $facilitiesController->addStatus($param);
                } else {
                    redirect('/ecofacilities');
                }
                break;
            case 'delete-status':
                if ($param) {
                    $facilitiesController->deleteStatus($param);
                } else {
                    redirect('/ecofacilities');
                }
                break;
            case 'manage':
                $facilitiesController->manage();
                break;
            case 'create':
                $facilitiesController->create();
                break;
            case 'edit':
                if ($param) {
                    $facilitiesController->edit($param);
                } else {
                    redirect('/ecofacilities/manage');
                }
                break;
            case 'delete':
                if ($param) {
                    $facilitiesController->delete($param);
                } else {
                    redirect('/ecofacilities/manage');
                }
                break;
            case 'categories':
                $facilitiesController->manageCategories();
                break;
            case 'create-category':
                $facilitiesController->createCategory();
                break;
            case 'edit-category':
                if ($param) {
                    $facilitiesController->editCategory($param);
                } else {
                    redirect('/ecofacilities/categories');
                }
                break;
            case 'delete-category':
                if ($param) {
                    $facilitiesController->deleteCategory($param);
                } else {
                    redirect('/ecofacilities/categories');
                }
                break;
            case 'map':
                $facilitiesController->showMap();
                break;
            default:
                redirect('/ecofacilities');
                break;
        }
        break;
    case 'api':
        $apiController = new ApiController();
        switch($method) {
            case 'facilities':
                if ($param === 'paginate') {
                    $apiController->paginateFacilities();
                } else {
                    $apiController->getFacilities();
                }
                break;
            case 'facility':
                if ($param) {
                    $apiController->getFacility($param);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Facility ID required']);
                }
                break;
            case 'status':
                if ($param === 'add') {
                    $apiController->addStatus();
                } else if ($param === 'update') {
                    $apiController->updateStatus();
                } else if ($param === 'get' && isset($url[3])) {
                    $apiController->getStatuses($url[3]);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid status request']);
                }
                break;
            case 'search':
                $apiController->searchFacilities();
                break;
            case 'categories':
                $apiController->getCategories();
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Endpoint not found']);
                break;
        }
        exit;
        break;
    case 'home':
    default:
        // Home page
        include __DIR__ . '/views/home.php';
        break;
}