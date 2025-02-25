<?php
require_once 'pdo.php';

function createOrder($data)
{
    try {
        $pdo = pdo_get_connection();

        $sql = "INSERT INTO don_hang (tai_khoan_id, ho_va_ten, email, so_dien_thoai, dia_chi, 
                tong_tien, phuong_thuc_thanh_toan, ngay_dat) 
                VALUES (:tai_khoan_id, :ho_va_ten, :email, :so_dien_thoai, :dia_chi, 
                :tong_tien, :phuong_thuc_thanh_toan, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tai_khoan_id' => $data['tai_khoan_id'],
            ':ho_va_ten' => $data['ho_va_ten'],
            ':email' => $data['email'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':dia_chi' => $data['dia_chi'],
            ':tong_tien' => $data['tong_tien'],
            ':phuong_thuc_thanh_toan' => $data['phuong_thuc_thanh_toan']
        ]);

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function createOrderDetail($data)
{
    try {
        $pdo = pdo_get_connection();

        $sql = "INSERT INTO chi_tiet_don_hang (don_hang_id, san_pham_id, so_luong, gia, ram_id) 
                VALUES (:don_hang_id, :san_pham_id, :so_luong, :gia, :ram_id)";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':don_hang_id' => $data['don_hang_id'],
            ':san_pham_id' => $data['san_pham_id'],
            ':so_luong' => $data['so_luong'],
            ':gia' => $data['gia'],
            ':ram_id' => $data['ram_id']
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function getRamById($ram_id)
{
    try {
        $pdo = pdo_get_connection();
        $sql = "SELECT * FROM ram WHERE ram_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ram_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function getOrderById($order_id)
{
    try {
        $pdo = pdo_get_connection();
        $sql = "SELECT * FROM don_hang WHERE don_hang_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function deleteOrder($order_id)
{
    try {
        $pdo = pdo_get_connection();

        // Bắt đầu transaction
        $pdo->beginTransaction();

        // Xóa chi tiết đơn hàng trước
        $sql = "DELETE FROM chi_tiet_don_hang WHERE don_hang_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id]);

        // Sau đó xóa đơn hàng
        $sql = "DELETE FROM don_hang WHERE don_hang_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id]);

        // Commit transaction
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        // Rollback nếu có lỗi
        $pdo->rollBack();
        return false;
    }
}

function getMyOrders($tai_khoan_id)
{
    try {
        $sql = "SELECT dh.*, ctdh.*, sp.ten_san_pham, sp.hinh_sp, sp.gia 
                FROM don_hang dh 
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.don_hang_id = ctdh.don_hang_id 
                LEFT JOIN san_pham sp ON ctdh.san_pham_id = sp.san_pham_id 
                WHERE dh.tai_khoan_id = ? 
                ORDER BY dh.ngay_dat DESC, dh.don_hang_id DESC";

        $orders = [];
        $result = pdo_query($sql, $tai_khoan_id);

        foreach ($result as $row) {
            if (!isset($orders[$row['don_hang_id']])) {
                $orders[$row['don_hang_id']] = [
                    'ma_don_hang' => $row['don_hang_id'],
                    'ngay_dat' => $row['ngay_dat'],
                    'trang_thai' => (int)$row['trang_thai'],
                    'tong_tien' => $row['tong_tien'],
                    'dia_chi' => $row['dia_chi'],
                    'so_dien_thoai' => $row['so_dien_thoai'],
                    'ly_do_huy' => $row['ly_do_huy'],
                    'products' => []
                ];
            }

            if ($row['san_pham_id']) {
                $orders[$row['don_hang_id']]['products'][] = [
                    'ten_san_pham' => $row['ten_san_pham'],
                    'hinh_sp' => $row['hinh_sp'],
                    'so_luong' => $row['so_luong'],
                    'gia' => $row['gia']
                ];
            }
        }

        return array_values($orders);
    } catch (PDOException $e) {
        return [];
    }
}

function cancelOrder($order_id)
{
    try {
        $pdo = pdo_get_connection();

        // Update the order status to "canceled"
        $sql = "UPDATE don_hang SET trang_thai = 5 WHERE don_hang_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id]);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function increaseProductStock($san_pham_id, $quantity) {
    $sql = "UPDATE san_pham SET stock = stock + ? WHERE san_pham_id = ?";
    return pdo_execute($sql, $quantity, $san_pham_id);
}

function getOrderDetails($order_id)
{
    try {
        $pdo = pdo_get_connection();
        $sql = "SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function checkProductStock($san_pham_id, $quantity) {
    try {
        $pdo = pdo_get_connection();
        $sql = "SELECT stock FROM san_pham WHERE san_pham_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$san_pham_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product && $product['stock'] >= $quantity; // Kiểm tra tồn kho
    } catch (PDOException $e) {
        return false;
    }
}

