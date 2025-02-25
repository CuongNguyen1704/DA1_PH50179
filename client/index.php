<?php
session_start();
include '../admin/commons/env.php';
include '../admin/commons/function.php';
require_once "./models/pdo.php";
require_once "./models/user.php";
require_once "./models/sanpham.php";
require_once "./models/slide.php";
require_once "./controllers/authController.php";
require_once "./models/binhluan.php";
require_once "./controllers/OrderController.php";
require_once "./models/OrderModel.php";
require_once "./controllers/CartController.php";

$authController = new AuthController();
$products = loadAll_sanpham_home();
$dstop10 = loadAll_sanpham_top10();
$listSlides = getSlides();
$cartController = new CartController();

if (isset($_GET['act'])) {
    $act = $_GET['act'];
    switch ($act) {
        case 'register':
            $authController->register();
            break;

        case 'login':
            $authController->login();
            break;

        case 'profile':
            include "./views/profile.php";
            break;

        case 'update-profile':
            $authController->updateProfile();
            break;

        case 'logout':
            $authController->logout();
            break;

        case 'forgot-password':
            $authController->forgotPassword();
            break;

        case 'product-detail':
            if (isset($_GET['id'])) {
                $product = getProductById($_GET['id']);
                include "./views/product-detail.php";
            }
            break;

        case 'comment':
            if (isset($_SESSION['email']) && isset($_POST['san_pham_id']) && isset($_POST['noi_dung'])) {
                $san_pham_id = $_POST['san_pham_id'];
                $tai_khoan_id = $_SESSION['email']['tai_khoan_id'];
                $noi_dung = $_POST['noi_dung'];

                themBinhLuan($san_pham_id, $tai_khoan_id, $noi_dung);
                header("Location: ?act=product-detail&id=" . $san_pham_id);
            }
            break;
        case 'delete-comment': // Thêm trường hợp xóa bình luận
            if (isset($_POST['binh_luan_id'])) {
                $binh_luan_id = $_POST['binh_luan_id'];
                xoaBinhLuan($binh_luan_id);
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
            break;


        case 'checkout':
            $orderController = new OrderController();
            $orderController->checkout();
            break;

        case 'place-order':
            $orderController = new OrderController();
            $orderController->placeOrder();
            break;

        case 'my-orders':
            $orderController = new OrderController();
            $orderController->getMyOrders();
            break;
        case 'delete-order':
            $orderController = new OrderController();
            $orderController->cancelOrder();
            break;

        case 'search':
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $products = search_products($keyword);
                $searchTitle = "Kết quả tìm kiếm cho: \"" . htmlspecialchars($keyword) . "\"";
                include "./views/home.php";
            } else if (isset($_GET['category'])) {
                $category = $_GET['category'];
                error_log("Đang tìm kiếm danh mục: " . $category);

                $products = search_products_by_category($category);
                $searchTitle = htmlspecialchars($category);

                error_log("Số sản phẩm tìm thấy: " . count($products));

                include "./views/home.php";
            } else {
                $products = loadall_sanpham_home();
                include "./views/home.php";
            }

            break;
        case 'cart':
            $cartController->showCart();
            break;

        case 'add-to-cart':
            $cartController->addToCart();
            break;

        case 'remove-cart-item':
            $cartController->removeCartItem();
            break;

        case 'checkout':
            $orderController = new OrderController();
            $orderController->checkout();
            break;

        case 'place-order':
            $orderController = new OrderController();
            $orderController->placeOrder();
            break;

        case 'cancel-order':
            $orderController = new OrderController();
            $orderController->cancelOrder();
            break;

        default:
            include "./views/home.php";
            break;
    }
} else {
    include "./views/home.php";
}
