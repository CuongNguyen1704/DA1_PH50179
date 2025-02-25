<?php
class DashboardController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }

    public function index() {
        // thống kê
        $statistics = $this->dashboardModel->getStatistics();
        // đơn hàng gần đây
        $recentOrders = $this->dashboardModel->getRecentOrders();
        require_once './views/layout/static.php';
    }
}