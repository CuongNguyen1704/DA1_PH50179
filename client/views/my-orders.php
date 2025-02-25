<?php include 'header.php'; ?>

<div class="messages position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 1000;">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?php unset($_SESSION['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?php unset($_SESSION['error']) ?>
        </div>
    <?php endif; ?>
</div>

<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn hàng của tôi</li>
        </ol> -->
    </nav>

    <div class="orders-container">
        <h2 class="section-title mb-4">Đơn hàng của tôi</h2>

        <div class="orders-list">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold">Mã đơn hàng: #<?php echo $order['ma_don_hang']; ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="order-status me-3">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    $trang_thai = (int)$order['trang_thai'];
                                    switch ($trang_thai) {
                                        case 1:
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Chờ xử lý';
                                            break;
                                        case 2:
                                            $statusClass = 'bg-primary';
                                            $statusText = 'Đã xác nhận';
                                            break;
                                        case 3:
                                            $statusClass = 'bg-info';
                                            $statusText = 'Đang giao';
                                            break;
                                        case 4:
                                            $statusClass = 'bg-success';
                                            $statusText = 'Đã hoàn thành';
                                            break;
                                        case 5:
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Đã hủy';
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary';
                                            $statusText = 'Không xác định';
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </div>
                                <?php if ($order['trang_thai'] == 1 || $order['trang_thai'] == 2):  ?>
                                    <button class="btn btn-danger btn-sm" onclick="confirmCancel(<?php echo $order['ma_don_hang']; ?>)">
                                        Hủy đơn hàng
                                    </button>
                                <?php elseif ($order['trang_thai'] == 5):  ?>
                                    <!-- <span class="text-muted">Đơn hàng đã hủy</span> -->
                                    <?php if (isset($order['ly_do_huy']) && !empty($order['ly_do_huy'])): ?>
                                        <p class="text-muted" style="margin-top: 15px;">Lý do hủy: <?php echo $order['ly_do_huy']; ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php foreach ($order['products'] as $product): ?>
                                <div class="product-item d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="product-image">
                                        <img src="<?php echo $product['hinh_sp']; ?>" alt="<?php echo $product['ten_sp']; ?>" class="rounded">
                                    </div>
                                    <div class="product-details ms-3 flex-grow-1">
                                        <h6 class="product-name mb-1"><?php echo $product['ten_san_pham']; ?></h6>
                                        <p class="product-ram mb-1 text-muted">Dung lượng: <?php echo $product['dung_luong']; ?></p>
                                        <p class="product-quantity mb-1 text-muted">Số lượng: <?php echo $product['so_luong']; ?></p>
                                        <p class="product-price mb-0 text-danger fw-bold">
                                            <?php
                                            $total_price = $product['gia'];
                                            echo number_format($total_price, 0, ',', '.');
                                            ?>đ
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="order-footer mt-3 d-flex justify-content-between align-items-center">
                                <div class="order-date">
                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                    <span class="text-muted">Ngày đặt: <?php echo date('d/m/Y', strtotime($order['ngay_dat'])); ?></span>
                                </div>
                                <div class="order-total">
                                    <span class="me-2">Tổng tiền:</span>
                                    <span class="text-danger fw-bold fs-5">
                                        <?php
                                        $total_price = $order['tong_tien'];
                                        echo number_format($total_price, 0, ',', '.');
                                        ?>đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="empty">
                        <i class="fas fa-shopping-bag fa-3x mb-3 text-muted"></i>
                        <h5>Bạn chưa có đơn hàng nào</h5>
                        <a href="index.php" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .order-item {
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .order-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .order-product-image {
        border-radius: 8px;
    }

    .order-status {
        font-weight: 600;
    }

    .product-image {
        width: 80px;
        height: 80px;
        min-width: 80px;
        margin-right: 15px;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #eee;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .product-item {
        padding: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
    }

    .product-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .product-details {
        flex: 1;
    }

    .product-name {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .product-quantity {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .product-price {
        font-size: 16px;
        font-weight: 600;
        color: #dc3545;
    }

    @media (max-width: 576px) {
        .product-image {
            width: 60px;
            height: 60px;
            min-width: 60px;
        }
    }
</style>

<script>
    function confirmCancel(orderId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
            window.location.href = `?act=cancel-order&id=${orderId}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 3000);
    });
</script>
<?php include 'footer.php'; ?>