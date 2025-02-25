<?php
class Category
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getCategory()
    {
        try {
            $sql = 'SELECT * FROM danh_muc ORDER BY danh_muc_id ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function addCategory($ten_danh_muc, $hinh, $mo_ta, $trang_thai)
    {
        try {
            $sql = 'INSERT INTO danh_muc(ten_danh_muc, hinh, mo_ta, trang_thai) 
                    VALUES (:ten_danh_muc, :hinh, :mo_ta, :trang_thai)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ten_danh_muc' => $ten_danh_muc,
                ':hinh' => $hinh,
                ':mo_ta' => $mo_ta,
                ':trang_thai' => $trang_thai
            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    public function inforCategory($danh_muc_id)
    {
        try {
            $sql = 'SELECT * FROM danh_muc WHERE danh_muc_id = :danh_muc_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function updateCategory($danh_muc_id, $ten_danh_muc, $hinh, $mo_ta, $trang_thai)
    {
        try {
            $sql = 'UPDATE danh_muc SET ten_danh_muc = :ten_danh_muc, hinh = :hinh, mo_ta = :mo_ta, trang_thai = :trang_thai WHERE danh_muc_id = :danh_muc_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':danh_muc_id' => $danh_muc_id,
                ':ten_danh_muc' => $ten_danh_muc,
                ':hinh' => $hinh,
                ':mo_ta' => $mo_ta,
                ':trang_thai' => $trang_thai
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    public function deleteCategory($danh_muc_id)
    {
        try {
            // Lấy thông tin của danh mục để lấy đường dẫn ảnh
            $category = $this->inforCategory($danh_muc_id);
            $imagePath = $category['hinh'] ?? null;

            // Xóa danh mục trong database
            $sql = 'DELETE FROM danh_muc WHERE danh_muc_id = :danh_muc_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);

            // Xóa ảnh nếu có đường dẫn
            if ($imagePath) {
                deleteFile($imagePath);
            }

            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    public function getAllCategories() {
        try {
            $sql = "SELECT * FROM danh_muc WHERE trang_thai = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function checkCategoryConstraints($danh_muc_id) {
        try {
            // Kiểm tra xem danh mục có sản phẩm không
            $sql = "SELECT COUNT(*) as count FROM san_pham WHERE danh_muc_id = :danh_muc_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            // Kiểm tra xem có đơn hàng nào chứa sản phẩm của danh mục này không
            $sql = "SELECT COUNT(*) as count FROM chi_tiet_don_hang ctdh 
                    JOIN san_pham sp ON ctdh.san_pham_id = sp.san_pham_id 
                    WHERE sp.danh_muc_id = :danh_muc_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            $orderCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            return [
                'has_products' => $productCount > 0,
                'has_orders' => $orderCount > 0
            ];
        } catch (PDOException $e) {
            error_log("Error checking category constraints: " . $e->getMessage());
            return false;
        }
    }
}
