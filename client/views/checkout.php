<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/client/Header.css">
    <link rel="stylesheet" href="./assets/css/client/Footer.css">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .product-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .product-image img {
            max-width: 100px;
            height: auto;
        }

        .checkout-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <?php include "views/header.php"; ?>

    <div class="checkout-container">
        <div class="checkout-form">
            <h3>Thông tin đặt hàng</h3>
            <form action="?act=place-order" method="POST">
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" class="form-control" value="<?= $_SESSION['email']['ho_va_ten'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?= $_SESSION['email']['email'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" class="form-control" value="<?= $_SESSION['checkout_data']['so_dien_thoai'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Địa chỉ giao hàng</label>
                    <input type="text" class="form-control" value="<?= $_SESSION['checkout_data']['dia_chi'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Phương thức thanh toán</label>
                    <select name="phuong_thuc_thanh_toan" class="form-control" required>
                        <option value="1">Thanh toán khi nhận hàng (COD)</option>
                        <option value="2">Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Đặt hàng</button>
            </form>
        </div>

        <div class="product-summary">
            <h3>Thông tin đơn hàng</h3>
            <?php foreach ($_SESSION['checkout_data']['cart_items'] as $item): ?>
                <div class="product-info d-flex gap-3 mb-3">
                    <div class="product-image">
                        <img src="<?= $item['hinh_sp'] ?>" alt="<?= $item['ten_san_pham'] ?>">
                    </div>
                    <div class="product-details">
                        <h5><?= $item['ten_san_pham'] ?></h5>
                        <p class="mb-1">Dung lượng: <?= $item['dung_luong'] ?></p>
                        <p class="mb-1">Số lượng: <?= $item['so_luong'] ?></p>
                        <p class="text-primary mb-0">
                            Giá: <?= number_format($item['gia'] + $item['gia_tang'], 0, ',', '.') ?>₫
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="total-section">
                <?php 
                $tong_tam_tinh = 0;
                foreach ($_SESSION['checkout_data']['cart_items'] as $item) {
                    $tong_tam_tinh += ($item['gia'] + $item['gia_tang']) * $item['so_luong'];
                }
                ?>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <span><?= number_format($tong_tam_tinh, 0, ',', '.') ?>₫</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Phí vận chuyển:</span>
                    <span>30.000₫</span>
                </div>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Tổng cộng:</span>
                    <span><?= number_format($tong_tam_tinh + 30000, 0, ',', '.') ?>₫</span>
                </div>
            </div>
        </div>
    </div>

    <?php include "views/footer.php"; ?>
</body>

</html>