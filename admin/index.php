<?php
$act = isset($_GET['act']) ? $_GET['act'] : 'static';

include '../admin/commons/env.php';
include '../admin/commons/function.php';
require_once '../admin/models/Category.php';
require_once '../admin/models/Slide.php';
require_once '../admin/models/products.php';
require_once '../admin/models/users.php';
require_once '../admin/models/OrderModel.php';
require_once '../admin/models/comment.php';
require_once '../admin/models/ram.php';
require_once '../admin/models/DashboardModel.php';

require_once '../admin/controllers/categoryController.php';
require_once '../admin/controllers/slideController.php';
require_once '../admin/controllers/productsController.php';
require_once '../admin/controllers/userController.php';
require_once '../admin/controllers/OrderController.php';
require_once '../admin/controllers/commentController.php';
require_once '../admin/controllers/DashboardController.php';
require_once '../admin/controllers/ramController.php';


include './views/layout/header.php';

$userController = new UserController();
$orderController = new OrderController();
$productsController = new ProductsController();
$categoryController = new CategoryController();
$slideController = new SlideController();
$commentController = new commentController();
$dashboardController = new DashboardController();
$ramController = new RamController();

switch ($act) {
    case 'static':
        $dashboardController->index();
        break;
    case 'list-category':
        $categoryController->listCategory();
        break;
    case 'form-add-category':
        $categoryController->formAddCategory();
        break;
    case 'post-add-category':
        $categoryController->postAddCategory();
        break;
    case 'form-edit-category':
        $categoryController->formEditCategory();
        break;
    case 'post-edit-category':
        $categoryController->postEditCategory();
        break;
    case 'delete-category':
        $categoryController->postDeleteCategory();
        break;

    case 'list-slide':
        $slideController->listSlide();
        break;
    case 'form-add-slide':
        $slideController->formAddSlide();
        break;
    case 'post-add-slide':
        $slideController->postAddSlide();
        break;
    case 'form-edit-slide':
        $slideController->formEditSlide();
        break;
    case 'post-edit-slide':
        $slideController->postEditSlide();
        break;
    case 'delete-slide':
        $slideController->postDeleteSlide();
        break;
    case 'listProducts':
        $productsController->listProducts();
        break;
    case 'formAddProducts':
        $productsController->formAddProducts();
        break;
    case 'postFormAdd':
        $productsController->postFormAdd();
        break;
    case 'deleteProduct':
        $productsController->deleteProduct($_GET['id']);
        break;
    case 'formEditProducts':
        $productsController->formEditProducts($_GET['id']);
        break;
    case 'updateProduct':
        $productsController->updateProduct();
        break;

    case 'listUser':
        $userController->listUser();
        break;
    case 'create':
        $userController->create();
        break;
    case 'updateUser':
        $userController->edit($_GET['id']);
        break;
    case 'delete':
        $userController->delete($_GET['id']);
        break;
    case 'list-orders':
        $orderController->index();
        break;
    case 'view-order':
        if (isset($_GET['id'])) {
            $orderController->view($_GET['id']);
        }
        break;
    case 'update-order-status':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderController->updateStatus();
        }
        break;

    case 'listComments':
        $commentController->listComment();
        break;
    case 'deleteComments':
        $commentController->deleteComments();
        break;

    case 'viewDetail':
        if (isset($_GET['id'])) {
            $userController->viewDetail($_GET['id']);
        }
        break;
    case 'listRams':
        $ramController->listRam();
        break;
    case 'formAddRam':
        $ramController->formAddRam();
        break;
    case 'addRam':
        $ramController->addRam();
        break;
    case 'formEditRam':
        $ramController->formEditRam();
        break;
    case 'editRam':
        $ramController->editRam();
        break;
    case 'deleteRam':
        $ramController->deleteRam();
        break;
    default:
        break;
}

include './views/layout/footer.php';
