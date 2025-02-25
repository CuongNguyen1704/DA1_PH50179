<?php

class OrderModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }
    public function getAllOrders() {
        $sql = "SELECT dh.*, tk.ho_va_ten, tk.email, tk.so_dien_thoai,
                COUNT(ctdh.chi_tiet_don_hang_id) as so_san_pham
                FROM don_hang dh
                LEFT JOIN tai_khoan tk ON dh.tai_khoan_id = tk.tai_khoan_id
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.don_hang_id = ctdh.don_hang_id
                GROUP BY dh.don_hang_id
                ORDER BY dh.don_hang_id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderDetail($id) {
        try {
            // Lấy thông tin đơn hàng và khách hàng
            $sql = "SELECT dh.*, tk.ho_va_ten, tk.email, tk.so_dien_thoai, tk.dia_chi, dh.ly_do_huy 
                    FROM don_hang dh
                    JOIN tai_khoan tk ON dh.tai_khoan_id = tk.tai_khoan_id
                    WHERE dh.don_hang_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                // Lấy chi tiết đơn hàng 
                $sql = "SELECT 
                        ctdh.*,
                        sp.ten_san_pham,
                        sp.gia as gia_goc,
                        r.dung_luong,
                        r.gia_tang,
                        MIN(hasp.hinh_sp) as hinh
                        FROM chi_tiet_don_hang ctdh
                        JOIN san_pham sp ON ctdh.san_pham_id = sp.san_pham_id
                        LEFT JOIN ram r ON ctdh.ram_id = r.ram_id
                        LEFT JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
                        WHERE ctdh.don_hang_id = ?
                        GROUP BY ctdh.chi_tiet_don_hang_id";
                    
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Tính toán lại tổng tiền cho từng item
                $tongDonHang = 0;
                foreach ($items as &$item) {
                    $thanhTien = ($item['gia_goc'] + $item['gia_tang']) * $item['so_luong']; 
                    $item['thanh_tien'] = $thanhTien;
                    $tongDonHang += $thanhTien;
                }
                
                // Cộng thêm phí ship
                $tongDonHang += 30000;
                
                // Cập nhật tổng tiền đơn hàng
                $sqlUpdateTotal = "UPDATE don_hang SET tong_tien = ? WHERE don_hang_id = ?";
                $stmtUpdateTotal = $this->conn->prepare($sqlUpdateTotal);
                $stmtUpdateTotal->execute([$tongDonHang, $id]);
                
                $order['tong_tien'] = $tongDonHang;
                
                return [
                    'order' => $order,
                    'items' => $items
                ];
            }
            return null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    // Lấy phương thức thanh toán
    public function getPaymentMethod($orderId) {
        $sql = "SELECT phuong_thuc_thanh_toan FROM don_hang WHERE don_hang_id = $orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['phuong_thuc_thanh_toan'];
    }
    public function updateOrderStatus($orderId, $status) {
        $currentStatus = $this->getOrderStatus($orderId);
    
        // Điều kiện kiểm tra logic cập nhật trạng thái
        if (($currentStatus == 1 && $status != 2 && $status != 5) || 
            ($currentStatus == 2 && $status != 3 && $status != 5) || 
            ($currentStatus == 3 && $status != 4) ||
            ($currentStatus >= 4)) { 
            return false; 
        }
    
        // Thực hiện cập nhật trạng thái đơn hàng
        $sql = "UPDATE don_hang 
                SET trang_thai = :status, ngay_xu_ly = CURRENT_DATE 
                WHERE don_hang_id = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    
    public function getOrdersByUserId($userId) {
        $sql = "SELECT dh.*, COUNT(ctdh.don_hang_id) as total_items 
                FROM don_hang dh 
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.don_hang_id = ctdh.don_hang_id 
                WHERE dh.tai_khoan_id = $userId 
                GROUP BY dh.don_hang_id 
                ORDER BY dh.ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchOrders($keyword) {
        $sql = "SELECT dh.*, tk.ho_va_ten, tk.email, tk.so_dien_thoai,
                COUNT(ctdh.chi_tiet_don_hang_id) as so_san_pham
                FROM don_hang dh
                LEFT JOIN tai_khoan tk ON dh.tai_khoan_id = tk.tai_khoan_id
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.don_hang_id = ctdh.don_hang_id
      
                WHERE dh.don_hang_id LIKE :keyword 
                OR tk.ho_va_ten LIKE :keyword 
                OR tk.email LIKE :keyword
                OR tk.so_dien_thoai LIKE :keyword
                GROUP BY dh.don_hang_id
                ORDER BY dh.ngay_dat DESC";    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderDetails($orderId) {
        $sql = "SELECT ct.*, sp.ten_san_pham, sp.gia
                FROM chi_tiet_don_hang ct
                JOIN san_pham sp ON ct.san_pham_id = sp.san_pham_id
                WHERE ct.don_hang_id = :orderId";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderStatus($orderId) {
        $sql = "SELECT trang_thai FROM don_hang WHERE don_hang_id = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['trang_thai'] : null;
    }
    public function cancelOrder($orderId, $reason) {
        if (empty($reason)) {
            return false; // Không thể hủy nếu lý do để trống
        }
        $sql = "UPDATE don_hang SET trang_thai = 5, ly_do_huy = :reason WHERE don_hang_id = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':orderId', $orderId);
        return $stmt->execute();
    }
}