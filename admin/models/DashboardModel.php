<?php
class DashboardModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getStatistics()
    {
        try {
            // tổng số người dùng
            $userQuery = "SELECT COUNT(*) as total_users FROM tai_khoan WHERE vai_tro = 0";
            $userStmt = $this->conn->query($userQuery);
            $totalUsers = $userStmt->fetch(PDO::FETCH_ASSOC)['total_users'];

            // tổng số đơn hàng
            $orderQuery = "SELECT COUNT(*) as total_orders FROM don_hang";
            $orderStmt = $this->conn->query($orderQuery);
            $totalOrders = $orderStmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

            // tổng doanh thu
            $revenueQuery = "SELECT SUM(tong_tien) as total_revenue FROM don_hang WHERE trang_thai = 4";
            $revenueStmt = $this->conn->query($revenueQuery);
            $totalRevenue = $revenueStmt->fetch(PDO::FETCH_ASSOC)['total_revenue'];

            // tổng lượt xem sản phẩm
            $viewQuery = "SELECT SUM(so_luot_xem) as total_views FROM san_pham";
            $viewStmt = $this->conn->query($viewQuery);
            $totalViews = $viewStmt->fetch(PDO::FETCH_ASSOC)['total_views'];

            return [
                'total_users' => $totalUsers,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'total_views' => $totalViews
            ];
        } catch (PDOException $e) {
            error_log("Lỗi thống kê: " . $e->getMessage());
            return false;
        }
    }

    public function getRecentOrders()
    {
        try {
            $sql = "SELECT 
                    dh.don_hang_id as id,
                    dh.ngay_dat as created_at,
                    dh.tong_tien as total_amount,
                    dh.trang_thai,
                    tk.ho_va_ten as customer_name,
                    MIN(sp.ten_san_pham) as product,
                    MIN(hasp.hinh_sp) as product_image
                    FROM don_hang dh
                    JOIN tai_khoan tk ON dh.tai_khoan_id = tk.tai_khoan_id
                    JOIN chi_tiet_don_hang ctdh ON dh.don_hang_id = ctdh.don_hang_id
                    JOIN san_pham sp ON ctdh.san_pham_id = sp.san_pham_id
                    LEFT JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
                    GROUP BY dh.don_hang_id
                    ORDER BY dh.don_hang_id DESC
                    LIMIT 5";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // xử lý trạng thái và class tương ứng
            foreach ($orders as &$order) {
                switch ($order['trang_thai']) {
                    case 1:
                        $order['status'] = 'Chờ xử lý';
                        $order['status_class'] = 'warning';
                        break;
                    case 2:
                        $order['status'] = 'Đã xác nhận';
                        $order['status_class'] = 'primary';
                        break;
                    case 3:
                        $order['status'] = 'Đang giao';
                        $order['status_class'] = 'info';
                        break;
                    case 4:
                        $order['status'] = 'Hoàn thành';
                        $order['status_class'] = 'success';
                        break;
                    case 5:
                        $order['status'] = 'Đã hủy';
                        $order['status_class'] = 'danger';
                        break;
                }
            }

            return $orders;
        } catch (PDOException $e) {
            error_log("Lỗi lấy đơn hàng gần đây: " . $e->getMessage());
            return [];
        }
    }
}
