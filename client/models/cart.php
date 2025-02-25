<?php
class Cart {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
        if (!$this->conn) {
            die("Không thể kết nối database");
        }
    }

    public function addToCart($tai_khoan_id, $san_pham_id, $ram_id, $so_luong) {
        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $sql = "SELECT * FROM gio_hang WHERE tai_khoan_id = :tai_khoan_id AND san_pham_id = :san_pham_id AND ram_id = :ram_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tai_khoan_id' => $tai_khoan_id,
                ':san_pham_id' => $san_pham_id,
                ':ram_id' => $ram_id
            ]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                $sql = "UPDATE gio_hang 
                        SET so_luong = so_luong + :so_luong 
                        WHERE tai_khoan_id = :tai_khoan_id 
                        AND san_pham_id = :san_pham_id 
                        AND ram_id = :ram_id";
            } else {
                $sql = "INSERT INTO gio_hang (tai_khoan_id, san_pham_id, ram_id, so_luong, ngay_them_vao) 
                        VALUES (:tai_khoan_id, :san_pham_id, :ram_id, :so_luong, CURRENT_TIMESTAMP)";
            }

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':tai_khoan_id' => $tai_khoan_id,
                ':san_pham_id' => $san_pham_id,
                ':ram_id' => $ram_id,
                ':so_luong' => $so_luong
            ]);

            // Nếu thành công thì commit
            if ($result) {
                $this->conn->commit();
                return true;
            }
            
            // Nếu thất bại thì rollback
            $this->conn->rollBack();
            return false;
            
        } catch(PDOException $e) {
            // Rollback nếu có lỗi
            $this->conn->rollBack();
            error_log("Lỗi thêm vào giỏ hàng: " . $e->getMessage());
            throw $e;
        }
    }

    public function getCartItems($tai_khoan_id) {
        try {
            $sql = "SELECT g.*, sp.ten_san_pham, sp.gia, hasp.hinh_sp as hinh_sp, 
                    r.dung_luong, r.gia_tang,
                    ((sp.gia + r.gia_tang) * g.so_luong) as thanh_tien,
                    r.gia_tang as ram_gia_tang
                    FROM gio_hang g
                    JOIN san_pham sp ON g.san_pham_id = sp.san_pham_id
                    JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
                    JOIN ram r ON g.ram_id = r.ram_id
                    WHERE g.tai_khoan_id = ?
                    GROUP BY g.gio_hang_id
                    ORDER BY g.gio_hang_id DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':tai_khoan_id' => $tai_khoan_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Lỗi lấy giỏ hàng: " . $e->getMessage());
            return false;
        }
    }

    public function updateCartQuantity($cartId, $quantity) {
        try {
            $sql = "UPDATE gio_hang SET so_luong = :so_luong WHERE gio_hang_id = :gio_hang_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':so_luong' => $quantity,
                ':gio_hang_id' => $cartId
            ]);
        } catch(PDOException $e) {
            error_log("Lỗi cập nhật số lượng: " . $e->getMessage());
            return false;
        }
    }

    public function removeCartItem($cartId) {
        try {
            $sql = "DELETE FROM gio_hang WHERE gio_hang_id = :gio_hang_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':gio_hang_id' => $cartId]);
        } catch(PDOException $e) {
            error_log("Lỗi xóa sản phẩm: " . $e->getMessage());
            return false;
        }
    }
}
?> 