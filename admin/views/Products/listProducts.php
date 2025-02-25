<div class="row">
    <div class="col">
        <div class="h-100">
            <!-- <div class="container" style="max-width:100%"> -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: #f8d7da; color: #721c24; border: none; border-radius: 3px;">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <h1 class="fs-16 mb-1">Danh Sách Sản Phẩm</h1>
                    <p class="text-muted mb-0">Danh sách các sản phẩm hiện có.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <a href="./?act=formAddProducts" class="btn btn-primary" style="width: 170px;">Thêm Sản Phẩm </a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Giá Sản Phẩm</th>
                                <th>RAM</th>
                                <th style="width: 120px;">Ngày Nhập</th>
                                <th style="width: 340px;">Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Hình Ảnh</th>
                                <th>Lượt Xem</th>
                                <th>Danh Mục</th>
                                <th>Tồn Kho</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($listProducts as $product): ?>

                                <tr>
                                    <td><?= $product['san_pham_id'] ?></td>
                                    <td><?= $product['ten_san_pham'] ?></td>
                                    <td><?= number_format($product['gia'], 0, ',', '.') ?>đ</td>
                                    <td>
                                        <?php
                                        if (!empty($product['ram_info'])) {
                                            echo htmlspecialchars($product['ram_info']);
                                        } else {
                                            echo "Chưa có RAM";
                                        }
                                        ?>
                                    </td>
                                    <td><?= $product['ngay_nhap'] ?></td>
                                    <td><?= $product['mo_ta'] ?></td>
                                    <td><?= $product['trang_thai'] == 1 ? 'Còn hàng' : 'Hết hàng' ?></td>
                                    <td>
                                        <img src="<?= $product['hinh_sp'] ?>" width="100px" alt="">
                                    </td>
                                    <td><?= $product['so_luot_xem'] ?></td>
                                    <td><?= $product['ten_danh_muc'] ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td>
                                        <a href="./?act=formEditProducts&id=<?= $product['san_pham_id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="./?act=deleteProduct&id=<?= $product['san_pham_id'] ?>"
                                            onclick="return confirm('Bạn có muốn xóa sản phẩm này không?')"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>