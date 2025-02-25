<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPHONE STORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/client/Header.css">
    <link rel="stylesheet" href="../assets/css/client/Footer.css">
    <link rel="stylesheet" href="./assets/css/client/Home.css">
</head>
<body>
    <?php include "views/header.php"; ?>
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <div class="category-list">
        <div class="banner">
            <?php include "views/slideShow.php"; ?>
        </div>
        
        <?php if (isset($searchTitle)): ?>
            <!-- Hiển thị kết quả tìm kiếm -->
            <div class="category-item">
                <h3><?= $searchTitle ?></h3>
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">
                        Không tìm thấy sản phẩm nào phù hợp với từ khóa tìm kiếm.
                    </div>
                <?php else: ?>
                    <div class="product-list">
                        <?php foreach ($products as $product): ?>
                            <a href="?act=product-detail&id=<?= $product['san_pham_id'] ?>" class="product-box">
                                <img src="<?= $product['hinh_sp'] ?>" alt="<?= $product['ten_san_pham'] ?>">
                                <div class="product-infor">
                                    <div class="product-name"><?= $product['ten_san_pham'] ?></div>
                                    <div class="product-price"><?= number_format($product['gia'], 0, ',', '.') ?><span>₫</span></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Hiển thị trang chủ bình thường -->
            <div class="category-item">
                <h3>SẢN PHẨM MỚI</h3>
                <div class="product-list">
                    <?php foreach ($products as $product): ?>
                        <a href="?act=product-detail&id=<?= $product['san_pham_id'] ?>" class="product-box">
                            <img src="<?= $product['hinh_sp'] ?>" alt="<?= $product['ten_san_pham'] ?>"><div class="product__sale-tag">New</div>
                            <div class="product-infor">
                                <div class="product-name"><?= $product['ten_san_pham'] ?></div>
                                <div class="product-price"><?= number_format($product['gia'], 0, ',', '.') ?><span>₫</span></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category-item">
                <h4>TOP 10 SẢN PHẨM ĐƯỢC QUAN TÂM NHẤT</h4>
                <div class="product-list">
                    <?php foreach ($dstop10 as $product): ?>
                        <a href="?act=product-detail&id=<?= $product['san_pham_id'] ?>" class="product-box">
                            <img src="<?= $product['hinh_sp'] ?>" alt="<?= $product['ten_san_pham'] ?>">
                            <div class="product-infor">
                                <div class="product-name"><?= $product['ten_san_pham'] ?></div>
                                <div class="product-price"><?= number_format($product['gia'], 0, ',', '.') ?><span>₫</span></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php include "views/footer.php"; ?>
    <script src="assets/js/slideShow.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn alert sau 3 giây
        setTimeout(function() {
            var alert = document.querySelector('.alert');
            if(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000);
    </script>
</body>
</html>