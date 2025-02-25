<?php
class Slide
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getSlide()
    {
        try {
            $sql = 'SELECT * FROM slides ORDER BY slide_id ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function addSlide($img, $trang_thai)
    {
        try {
            $sql = 'INSERT INTO slides(img, trang_thai) 
                    VALUES (:img, :trang_thai)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':img' => $img,
                ':trang_thai' => $trang_thai
            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    public function inforSlide($slide_id)
    {
        try {
            $sql = 'SELECT * FROM slides WHERE slide_id = :slide_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':slide_id' => $slide_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function updateSlide($slide_id, $img, $trang_thai)
    {
        try {
            $sql = 'UPDATE slides SET img = :img, trang_thai = :trang_thai WHERE slide_id = :slide_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':slide_id' => $slide_id,
                ':img' => $img,
                ':trang_thai' => $trang_thai
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    public function deleteSlide($slide_id)
    {
        try {
            $category = $this->inforSlide($slide_id);
            $imagePath = $category['hinh'] ?? null;

            $sql = 'DELETE FROM slides WHERE slide_id = :slide_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':slide_id' => $slide_id]);

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
}
