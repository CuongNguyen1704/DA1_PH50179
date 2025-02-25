<?php

class Products
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllProducts()
    {
        try {
            $sql = "SELECT 
                        sp.*,
                        dm.ten_danh_muc,
                        GROUP_CONCAT(r.dung_luong SEPARATOR ', ') as ram_info,
                        COALESCE(
                            (SELECT hinh_sp 
                             FROM hinh_anh_san_pham 
                             WHERE san_pham_id = sp.san_pham_id 
                             LIMIT 1),
                            'default.jpg'
                        ) as hinh_sp
                    FROM san_pham sp
                    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.danh_muc_id
                    LEFT JOIN san_pham_ram spr ON sp.san_pham_id = spr.san_pham_id
                    LEFT JOIN ram r ON spr.ram_id = r.ram_id
                    GROUP BY sp.san_pham_id
                    ORDER BY sp.san_pham_id DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi lấy danh sách sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function addProduct($ten_san_pham, $gia, $ngay_nhap, $mo_ta, $trang_thai, $danh_muc_id, $stock)
    {
        try {
            // Debug SQL và parameters
            $sql = "INSERT INTO san_pham (ten_san_pham, gia, ngay_nhap, mo_ta, trang_thai, danh_muc_id, stock) 
                    VALUES (:ten_san_pham, :gia, :ngay_nhap, :mo_ta, :trang_thai, :danh_muc_id, :stock)";
            
            error_log("SQL Query: " . $sql);
            error_log("Parameters: " . print_r([
                ':ten_san_pham' => $ten_san_pham,
                ':gia' => $gia,
                ':ngay_nhap' => $ngay_nhap,
                ':mo_ta' => $mo_ta,
                ':trang_thai' => $trang_thai,
                ':danh_muc_id' => $danh_muc_id,
                ':stock' => $stock
            ], true));
            
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':ten_san_pham' => $ten_san_pham,
                ':gia' => $gia,
                ':ngay_nhap' => $ngay_nhap,
                ':mo_ta' => $mo_ta,
                ':trang_thai' => $trang_thai,
                ':danh_muc_id' => $danh_muc_id,
                ':stock' => $stock
            ]);
            
            error_log("Execute result: " . ($result ? "Success" : "Failed"));
            
            if ($result) {
                $san_pham_id = $this->conn->lastInsertId();
                error_log("New product ID: " . $san_pham_id);
                $this->conn->commit();
                return ['san_pham_id' => $san_pham_id];
            }
            
            $this->conn->rollBack();
            return false;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi thêm sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function addProductImage($url, $san_pham_id)
    {
        try {
            $sql = 'INSERT INTO hinh_anh_san_pham (hinh_sp, san_pham_id) VALUES (:hinh_sp, :san_pham_id)';
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':hinh_sp' => $url,
                ':san_pham_id' => $san_pham_id,
            ]);
        } catch(PDOException $e) {
            error_log("Lỗi thêm hình ảnh sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function thongTinProduct($idProduct)
    {
        try {
            // Kiểm tra kết nối
            if (!$this->conn) {
                error_log("Database connection failed");
                return false;
            }

            // Sửa câu SQL để lấy đầy đủ thông tin sản phẩm
            $sql = "SELECT sp.*, dm.ten_danh_muc, hasp.hinh_anh_id, hasp.hinh_sp 
                    FROM san_pham sp
                    LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.danh_muc_id
                    LEFT JOIN hinh_anh_san_pham hasp ON hasp.san_pham_id = sp.san_pham_id
                    WHERE sp.san_pham_id = :idProduct
                    LIMIT 1";

            // Debug
            error_log("SQL Query: " . $sql);
            error_log("Product ID: " . $idProduct);

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':idProduct' => $idProduct]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug kết quả
            error_log("Product data: " . print_r($result, true));

            return $result;
        } catch (\PDOException $e) {
            error_log("Database error in thongTinProduct: " . $e->getMessage());
            return false;
        }
    }

    public function updateProductWithRelations($san_pham_id, $ten_san_pham, $gia, $hinh_id, $hinh_path, $ngay_nhap, $mo_ta, $trang_thai, $danh_muc_id, $stock) {
        try {
            // Check stock and update status
            $trang_thai = $stock > 0 ? 1 : 0; // Set status to 1 if in stock, 0 if out of stock

            $sql = "UPDATE san_pham SET 
                    ten_san_pham = :ten_san_pham,
                    gia = :gia,
                    ngay_nhap = :ngay_nhap,
                    mo_ta = :mo_ta,
                    trang_thai = :trang_thai,
                    danh_muc_id = :danh_muc_id,
                    stock = :stock
                    WHERE san_pham_id = :san_pham_id";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':ten_san_pham' => $ten_san_pham,
                ':gia' => $gia,
                ':ngay_nhap' => $ngay_nhap,
                ':mo_ta' => $mo_ta,
                ':trang_thai' => $trang_thai, // Use updated status
                ':danh_muc_id' => $danh_muc_id,
                ':stock' => $stock
            ]);

            // Cập nhật hình ảnh nếu có
            if ($hinh_path !== null) {
                $sqlImage = "UPDATE hinh_anh_san_pham 
                            SET hinh_sp = :hinh_sp 
                            WHERE hinh_anh_id = :hinh_id";
                $stmtImage = $this->conn->prepare($sqlImage);
                $stmtImage->execute([
                    ':hinh_sp' => $hinh_path,
                    ':hinh_id' => $hinh_id
                ]);
            }

            return true;
        } catch(PDOException $e) {
            error_log("Lỗi cập nhật sản phẩm: " . $e->getMessage());
            return false;
        }
    }
    

    public function deleteProductAndImages($id)
    {
        try {
            // bắt đầu giao dịch trong cơ sở dữ liệu , đảm bảo thao tác xóa ảnh và sản phẩm nếu thất bại sẽ rollback về để đảm bảo dữ li
            $this->conn->beginTransaction();

            // Xóa ảnh từ bảng hinh_anh
            $sqlDeleteImages = 'DELETE FROM hinh_anh_san_pham WHERE san_pham_id = :id';
            $stmtImages = $this->conn->prepare($sqlDeleteImages);
            $stmtImages->execute([':id' => $id]);

            // Xóa sản phẩm từ bảng san_pham
            $sqlDeleteProduct = 'DELETE FROM san_pham WHERE san_pham_id = :id';
            $stmtProduct = $this->conn->prepare($sqlDeleteProduct);
            $stmtProduct->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in deleteProductAndImages: " . $e->getMessage());
            return false;
        }
    }

    public function getCategories()
    {
        $sql = 'SELECT * FROM danh_muc';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function checkProductInOrders($productId) {
        $sql = "SELECT COUNT(*) as count FROM chi_tiet_don_hang WHERE san_pham_id = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['productId' => $productId]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function addProductRam($san_pham_id, $ram_id) {
        try {
            $sql = "INSERT INTO san_pham_ram (san_pham_id, ram_id) 
                    VALUES (:san_pham_id, :ram_id)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':ram_id' => $ram_id
            ]);
        } catch(PDOException $e) {
            error_log("Lỗi thêm RAM cho sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function getAllRam()
    {
        try {
            $sql = "SELECT * FROM ram WHERE trang_thai = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi lấy danh sách RAM: " . $e->getMessage());
            return false;
        }
    }

    public function getProductRams($san_pham_id) 
    {
        try {
            $sql = "SELECT r.* 
                    FROM ram r
                    INNER JOIN san_pham_ram spr ON r.ram_id = spr.ram_id
                    WHERE spr.san_pham_id = :san_pham_id";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':san_pham_id', $san_pham_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi lấy RAM của sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    // Thêm phương thức để cập nhật RAM của sản phẩm
    public function updateProductRams($san_pham_id, $ram_ids) 
    {
        try {
            $this->conn->beginTransaction();
            
            // Xóa tất cả RAM cũ của sản phẩm
            $sqlDelete = "DELETE FROM san_pham_ram WHERE san_pham_id = :san_pham_id";
            $stmtDelete = $this->conn->prepare($sqlDelete);
            $stmtDelete->execute([':san_pham_id' => $san_pham_id]);
            
            // Thêm RAM mới
            if (!empty($ram_ids)) {
                $sqlInsert = "INSERT INTO san_pham_ram (san_pham_id, ram_id) VALUES (:san_pham_id, :ram_id)";
                $stmtInsert = $this->conn->prepare($sqlInsert);
                foreach ($ram_ids as $ram_id) {
                    $stmtInsert->execute([':san_pham_id' => $san_pham_id, ':ram_id' => $ram_id]);
                }
            }
            
            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi trong updateProductRams: " . $e->getMessage());
            return false;
        }
    }
}

