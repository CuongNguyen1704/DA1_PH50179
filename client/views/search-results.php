<?php include "views/header.php"; ?>

<div class="container mt-4">
    <h3>Kết quả tìm kiếm cho: "<?= htmlspecialchars($_GET['keyword']) ?>"</h3>
    
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

<?php include "views/footer.php"; ?> 