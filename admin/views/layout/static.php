<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            min-width: 80%;
            margin: 0 auto;
        }

        .card {
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .stat-card {
            border-radius: 15px;
        }

        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
        }

        .bg-primary:hover {
            background-color: #0056b3 !important;
        }

        .bg-success:hover {
            background-color: #218838 !important;
        }

        .bg-warning:hover {
            background-color: #e0a800 !important;
        }
    </style>
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card text-white bg-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-users stat-icon"></i>
                        <h5 class="card-title">Tổng số người dùng</h5>
                        <h2 class="card-text" id="userCount"><?= number_format($statistics['total_users']) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-success">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart stat-icon"></i>
                        <h5 class="card-title">Tổng số đơn hàng</h5>
                        <h2 class="card-text" id="orderCount"><?= number_format($statistics['total_orders']) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-dollar-sign stat-icon"></i>
                        <h5 class="card-title">Tổng doanh thu</h5>
                        <h2 class="card-text" id="revenue"><?= number_format($statistics['total_revenue'], 0, ',', '.') ?>đ</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-info">
                    <div class="card-body text-center">
                        <i class="fas fa-eye stat-icon"></i>
                        <h5 class="card-title">Lượt truy cập</h5>
                        <h2 class="card-text" id="visitCount"><?= number_format($statistics['total_views']) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu và lượt truy cập -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Biểu đồ doanh thu
                    </div>
                    <div class="card-body">
                        <!-- Placeholder cho biểu đồ doanh thu -->
                        <canvas id="revenueChart" width="100%" height="60"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Biểu đồ lượt truy cập
                    </div>
                    <div class="card-body">
                        <!-- Placeholder cho biểu đồ lượt truy cập -->
                        <canvas id="trafficChart" width="100%" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thêm bảng thống kê chi tiết -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Đơn hàng gần đây</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Sản phẩm</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo $order['product_image']; ?>" 
                                                     alt="Product" 
                                                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                <span><?php echo $order['product']; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $order['customer_name']; ?></td>
                                        <td><?php echo $order['total_amount']; ?></td>
                                        <td><span class="badge bg-<?php echo $order['status_class']; ?>"><?php echo $order['status']; ?></span></td>
                                        <td><?php echo $order['created_at']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dữ liệu mẫu cho biểu đồ doanh thu
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
                datasets: [{
                    label: 'Doanh thu',
                    data: [1200, 1900, 3000, 5000, 2300, 3400],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2
                }]
            }
        });

        // Dữ liệu mẫu cho biểu đồ lượt truy cập
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        const trafficChart = new Chart(trafficCtx, {
            type: 'bar',
            data: {
                labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'],
                datasets: [{
                    label: 'Lượt truy cập',
                    data: [200, 300, 400, 500, 600, 700, 800],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });



        // Khởi tạo animation khi trang tải xong
        document.addEventListener('DOMContentLoaded', () => {
            animateValue("userCount", 0, 1234, 2000);
            animateValue("orderCount", 0, 567, 2000);
            animateValue("visitCount", 0, 8901, 2000);

            // Định dạng số tiền
            document.getElementById("revenue").innerHTML = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(12345000);

           

            const tbody = document.getElementById('recentOrders');
            orders.forEach(order => {
                tbody.innerHTML += `
                <tr>
                    <td>${order.id}</td>
                    <td>${order.customer}</td>
                    <td>${order.product}</td>
                    <td>${order.total}</td>
                    <td><span class="badge bg-${order.status === 'Đã giao' ? 'success' : order.status === 'Đang giao' ? 'primary' : 'warning'}">${order.status}</span></td>
                    <td>${order.date}</td>
                </tr>
            `;
            });
        });
    </script>

</body>

</html>