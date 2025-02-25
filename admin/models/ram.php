<?php
class Ram {
    public $conn;

    public function __construct() {
        $this->conn = connectDb();
    }

    // Lấy tất cả RAM với thông tin sản phẩm
    public function getAllRam() {
        $sql = "SELECT r.*, COUNT(spr.san_pham_id) as so_san_pham_su_dung 
                FROM ram r
                LEFT JOIN san_pham_ram spr ON r.ram_id = spr.ram_id
                GROUP BY r.ram_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm RAM mới
    public function addRam($dung_luong, $gia_tang, $trang_thai) {
        try {
            $sql = "INSERT INTO ram (dung_luong, gia_tang, trang_thai) 
                    VALUES (:dung_luong, :gia_tang, :trang_thai)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':dung_luong', $dung_luong);
            $stmt->bindParam(':gia_tang', $gia_tang);
            $stmt->bindParam(':trang_thai', $trang_thai);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Lỗi thêm RAM: " . $e->getMessage());
            return false;
        }
    }

    // Lấy thông tin RAM theo ID
    public function getRamById($ram_id) {
        try {
            $sql = "SELECT * FROM ram WHERE ram_id = :ram_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ram_id', $ram_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi lấy RAM: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật RAM
    public function updateRam($ram_id, $dung_luong, $gia_tang, $trang_thai) {
        try {
            $sql = "UPDATE ram 
                    SET dung_luong = :dung_luong,
                        gia_tang = :gia_tang,
                        trang_thai = :trang_thai
                    WHERE ram_id = :ram_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ram_id', $ram_id);
            $stmt->bindParam(':dung_luong', $dung_luong);
            $stmt->bindParam(':gia_tang', $gia_tang);
            $stmt->bindParam(':trang_thai', $trang_thai);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Lỗi cập nhật RAM: " . $e->getMessage());
            return false;
        }
    }

    // Thêm phương thức này vào class Ram
    public function getAllProduct() {
        try {
            $sql = "SELECT * FROM san_pham WHERE trang_thai = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi lấy danh sách sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRam($ram_id) {
        try {
            $sql = "DELETE FROM ram WHERE ram_id = :ram_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ram_id', $ram_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Lỗi xóa RAM: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra xem RAM đã tồn tại chưa
    public function checkRamExists($dung_luong) {
        try {
            $sql = "SELECT * FROM ram WHERE dung_luong = :dung_luong";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':dung_luong', $dung_luong);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false; // Trả về true nếu tồn tại
        } catch(PDOException $e) {
            error_log("Lỗi kiểm tra RAM: " . $e->getMessage());
            return false;
        }
    }
}
?>
