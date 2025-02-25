<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <h3 class="fs-16 mb-1">Sửa Sản Phẩm</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="./?act=updateProduct" method="post" enctype="multipart/form-data">
                        <?php if (!isset($product) || !is_array($product)): ?>
                            <div class="alert alert-danger">Không có dữ liệu sản phẩm</div>
                        <?php else: ?>
                            <div class="mb-3">
                                <input type="hidden" name="san_pham_id" value="<?= htmlspecialchars($product['san_pham_id'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Tên Sản Phẩm</label>
                                <input type="text" class="form-control" id="categoryName"
                                    name="ten_san_pham" required value="<?= htmlspecialchars($product['ten_san_pham'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="gia" class="form-label">Giá</label>
                                <input type="text" class="form-control" id="gia"
                                    name="gia" required value="<?= $product['gia'] ?>">
                            </div>

                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Ngày Nhập</label>
                                <input type="date" class="form-control" id="categoryName" name="ngay_nhap" style="width: 150px;" required value="<?= $product['ngay_nhap'] ?>">
                            </div>

                            
                            
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">Mô Tả</label>
                                <textarea 
                                    class="form-control" 
                                    id="categoryDescription" 
                                    name="mo_ta" 
                                    rows="3" 
                                    required
                                    spellcheck="false"
                                ><?= isset($product['mo_ta']) ? htmlspecialchars($product['mo_ta']) : '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="categoryStatus" class="form-label">Trạng Thái</label>
                                <select class="form-select" id="categoryStatus" name="trang_thai" required>
                                    <option value="1" <?= $product['trang_thai'] == 1 ? 'selected' : '' ?>>Còn</option>
                                    <option value="0" <?= $product['trang_thai'] == 0 ? 'selected' : '' ?>>Hết</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="categoryImage" class="form-label">Hình Ảnh</label>
                                <input type="hidden" name="hinh_anh_id" value="<?= $product['hinh_anh_id'] ?>">
                                <?php if (!empty($product['hinh_sp'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= $product['hinh_sp'] ?>" alt="Ảnh sản phẩm" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                                <input type="text" class="form-control" id="categoryImage" name="old_img"
                                    value="<?= $product['hinh_sp'] ?>" hidden>
                                <input type="file" name="hinh_sp">
                            </div>
                            <div class="mb-3">
                                <label for="categorySelect" class="form-label">Danh Mục</label>
                                <select name="danh_muc_id" id="categorySelect" class="form-select">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['danh_muc_id'] ?>" 
                                                <?= ($category['danh_muc_id'] == $product['danh_muc_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['ten_danh_muc']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ram_ids" class="form-label">RAM</label>
                                <select name="ram_ids[]" id="ram_ids" class="form-control" multiple>
                                    <?php 
                                    if ($rams): 
                                        foreach ($rams as $ram): 
                                            $selected = '';
                                            if ($productRams) {
                                                foreach ($productRams as $productRam) {
                                                    if ($productRam['ram_id'] == $ram['ram_id']) {
                                                        $selected = 'selected';
                                                        break;
                                                    }
                                                }
                                            }
                                    ?>
                                        <option value="<?php echo $ram['ram_id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $ram['dung_luong']; ?>
                                        </option>
                                    <?php 
                                        endforeach; 
                                    endif; 
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Tồn Kho</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="<?= $product['stock'] ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            <a href="?act=listProducts" class="btn btn-secondary">Quay lại Danh
                                Sách</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>