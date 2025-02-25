<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/client/Cart.css">
</head>
<style>
    .cart-item {
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        background-color: #f8f9fa;
    }

    .cart-item img {
        max-width: 100%;
        height: auto;
        object-fit: cover;
    }

    .btn-outline-danger {
        transition: all 0.2s ease;
    }

    .btn-outline-danger:hover {
        transform: scale(1.05);
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 8px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .btn-primary {
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<body>
    <?php include "views/header.php"; ?>

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

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Giỏ hàng của bạn</h2>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>

                <?php
                // Kiểm tra đăng nhập
                if (!isset($_SESSION['email'])) {
                    echo '<div class="alert alert-warning">Vui lòng đăng nhập để xem giỏ hàng</div>';
                    exit;
                }

                try {
                    $tai_khoan_id = $_SESSION['email']['tai_khoan_id'];

                    // Truy vấn lấy thông tin giỏ hàng
                    $sql = "SELECT g.*, sp.ten_san_pham, sp.gia, hasp.hinh_sp as hinh_sp, 
                            r.dung_luong, r.gia_tang,
                            (sp.gia + r.gia_tang) * g.so_luong as thanh_tien
                            FROM gio_hang g
                            JOIN san_pham sp ON g.san_pham_id = sp.san_pham_id
                            JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
                            JOIN ram r ON g.ram_id = r.ram_id
                            WHERE g.tai_khoan_id = ?
                            GROUP BY g.gio_hang_id
                            ORDER BY g.gio_hang_id DESC";

                    $cartItems = pdo_query($sql, $tai_khoan_id);

                    // Tính tổng tiền
                    $totalAmount = 0;
                    foreach ($cartItems as $item) {
                        $totalAmount += ($item['gia'] + $item['gia_tang']) * $item['so_luong'];
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Có lỗi xảy ra: ' . $e->getMessage() . '</div>';
                    exit;
                }
                ?>

                <?php if (!empty($cartItems)): ?>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <?php foreach ($cartItems as $key => $item): ?>
                                        <div class="row align-items-center mb-4 cart-item">
                                            <div class="col-md-1">
                                                <input type="checkbox" class="cart-checkbox" data-id="<?= $item['gio_hang_id'] ?>" data-price="<?= ($item['gia'] + $item['gia_tang']) * $item['so_luong'] ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <img src="<?= $item['hinh_sp'] ?>" class="img-fluid rounded" alt="<?= $item['ten_san_pham'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="mb-2"><?= $item['ten_san_pham'] ?></h5>
                                                <p class="text-muted mb-1">RAM: <?= $item['dung_luong'] ?></p>
                                                <p class="text-primary fw-bold mb-1">
                                                    <?= number_format($item['gia'] + $item['gia_tang'], 0, ',', '.') ?>₫
                                                </p>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3">Số lượng: <?= $item['so_luong'] ?></span>
                                                    <form action="?act=remove-cart-item" method="POST" class="d-inline">
                                                        <input type="hidden" name="san_pham_id" value="<?= $item['san_pham_id'] ?>">
                                                        <input type="hidden" name="ram_id" value="<?= $item['ram_id'] ?>">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Xóa
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($key < count($cartItems) - 1): ?>
                                            <hr class="my-3">
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Tổng đơn hàng</h4>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Tạm tính:</span>
                                        <span class="fw-bold subtotal-amount">0₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Phí vận chuyển:</span>
                                        <span class="fw-bold">30.000₫</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="fw-bold">Tổng cộng:</span>
                                        <span class="text-primary fw-bold fs-5 total-amount">30.000₫</span>
                                    </div>

                                    <form action="?act=checkout" method="POST">
                                        <input type="hidden" name="tong_tien" value="<?= $totalAmount + 30000 ?>">
                                        <?php foreach ($cartItems as $item): ?>
                                            <input type="hidden" name="cart_items[]" value="<?= htmlspecialchars(json_encode($item)) ?>">
                                        <?php endforeach; ?>
                                        <div class="mb-3">
                                            <input type="text" name="dia_chi" class="form-control" placeholder="Địa chỉ giao hàng" required
                                                value="<?= isset($_SESSION['email']['dia_chi']) ? $_SESSION['email']['dia_chi'] : '' ?>">
                                        </div>
                                        <div class="mb-4">
                                            <input type="tel" name="so_dien_thoai" class="form-control" placeholder="Số điện thoại" required
                                                value="<?= isset($_SESSION['email']['so_dien_thoai']) ? $_SESSION['email']['so_dien_thoai'] : '' ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-2" id="confirmOrderBtn" disabled>
                                            Tiến hành thanh toán
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h4>Giỏ hàng của bạn đang trống</h4>
                        <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng của bạn</p>
                        <a href="index.php" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include "views/footer.php"; ?>

    <script>
        // Tự động ẩn alert sau 3 giây
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 3000);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.cart-checkbox');
            const confirmOrderBtn = document.getElementById('confirmOrderBtn');
            let selectedTotal = 0;
            let selectedItems = [];

            function updateForm() {
                const form = document.querySelector('form[action="?act=checkout"]');
                const cartItemsInputs = form.querySelectorAll('input[name="cart_items[]"]');

                cartItemsInputs.forEach(input => {
                    input.disabled = true;
                });

                selectedItems.forEach(cartId => {
                    const itemIndex = Array.from(checkboxes).findIndex(cb => cb.dataset.id === cartId);
                    if (itemIndex !== -1) {
                        cartItemsInputs[itemIndex].disabled = false;
                    }
                });
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const price = parseFloat(this.dataset.price);
                    const cartId = this.dataset.id;

                    if (this.checked) {
                        selectedTotal += price;
                        selectedItems.push(cartId);
                    } else {
                        selectedTotal -= price;
                        selectedItems = selectedItems.filter(id => id !== cartId);
                    }

                    confirmOrderBtn.disabled = selectedItems.length === 0;

                    const shippingFee = 30000;
                    const finalTotal = selectedTotal + (selectedItems.length > 0 ? shippingFee : 0);

                    document.querySelector('.subtotal-amount').textContent =
                        new Intl.NumberFormat('vi-VN').format(selectedTotal) + '₫';
                    document.querySelector('.total-amount').textContent =
                        new Intl.NumberFormat('vi-VN').format(finalTotal) + '₫';

                    document.querySelector('input[name="tong_tien"]').value = finalTotal;

                    updateForm();
                });
            });
        });
    </script>
</body>

</html>