<?php
class comment
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getComment()
    {
        try {
            $sql = 'SELECT b.*, t.ho_va_ten, s.ten_san_pham 
                    FROM binh_luan b 
                    JOIN tai_khoan t ON b.tai_khoan_id = t.tai_khoan_id 
                    JOIN san_pham s ON b.san_pham_id = s.san_pham_id 
                    ORDER BY b.binh_luan_id ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deleteComments($binh_luan_id)
    {
        try {
            $sql = 'DELETE FROM binh_luan WHERE binh_luan_id =:binh_luan_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':binh_luan_id' => $binh_luan_id]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
}
