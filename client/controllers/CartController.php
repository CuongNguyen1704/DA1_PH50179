<?php
class CartController {
    public function showCart() {
        if(!isset($_SESSION['email'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem giỏ hàng';
            header('Location: ?act=login');
            exit;
        }
        include "./views/cart/cart.php";
    }

    public function addToCart() {
        if(!isset($_SESSION['email'])) {
            $_SESSION['error'] = '<i class="fas fa-exclamation-circle"></i> Vui lòng đăng nhập để thêm vào giỏ hàng';
            header('Location: ?act=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tai_khoan_id = $_SESSION['email']['tai_khoan_id'];
                $san_pham_id = $_POST['san_pham_id'];
                $ram_id = $_POST['ram_id'];
                $so_luong = $_POST['so_luong'];
                $gia = $_POST['gia'];

                // Kiểm tra sản phẩm trong giỏ hàng
                $check_sql = "SELECT * FROM gio_hang 
                            WHERE tai_khoan_id = ? AND san_pham_id = ? AND ram_id = ?";
                $existing_item = pdo_query_one($check_sql, $tai_khoan_id, $san_pham_id, $ram_id);

                if ($existing_item) {
                    $update_sql = "UPDATE gio_hang 
                                SET so_luong = so_luong + ? 
                                WHERE tai_khoan_id = ? AND san_pham_id = ? AND ram_id = ?";
                    pdo_execute($update_sql, $so_luong, $tai_khoan_id, $san_pham_id, $ram_id);
                } else {
                    $insert_sql = "INSERT INTO gio_hang (tai_khoan_id, san_pham_id, ram_id, so_luong) 
                                VALUES (?, ?, ?, ?)";
                    pdo_execute($insert_sql, $tai_khoan_id, $san_pham_id, $ram_id, $so_luong);
                }

                $_SESSION['success'] = '<i class="fas fa-check-circle"></i> Đã thêm sản phẩm vào giỏ hàng';
                header('Location: ?act=cart');
                exit;

            } catch(PDOException $e) {
                $_SESSION['error'] = '<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra: ' . $e->getMessage();
                header('Location: ?act=cart');
                exit;
            }
        }
    }

    public function removeCartItem() {
        if (!isset($_SESSION['email']) || !isset($_POST['san_pham_id']) || !isset($_POST['ram_id'])) {
            header('Location: ?act=cart');
            exit;
        }

        try {
            $sql = "DELETE FROM gio_hang 
                    WHERE tai_khoan_id = ? AND san_pham_id = ? AND ram_id = ?";
            pdo_execute($sql, 
                $_SESSION['email']['tai_khoan_id'],
                $_POST['san_pham_id'],
                $_POST['ram_id']
            );

            $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        } catch(PDOException $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm';
        }

        header('Location: ?act=cart');
        exit;
    }
} 