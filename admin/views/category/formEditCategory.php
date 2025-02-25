<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <h4 class="fs-16 mb-1">Sửa Danh Mục</h4>
                    <p class="text-muted mb-0" style="font-size: 20px;">Chỉnh sửa thông tin danh mục sản phẩm
                        <span style="font-weight: 700;"><?php echo $Category['ten_danh_muc']; ?></span>.
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="./?act=post-edit-category" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="editCategoryID" class="form-label">ID Danh Mục</label>
                            <input type="text" class="form-control" name="danh_muc_id" id="editCategoryID" value="<?php echo $Category['danh_muc_id']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Tên Danh Mục</label>
                            <input type="text" class="form-control" id="editCategoryName" name="ten_danh_muc" value="<?php echo $Category['ten_danh_muc']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="old_img" value="<?php echo $Category['hinh']; ?>" hidden>
                            <label for="editCategoryImage" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="editCategoryImage" name="hinh">
                            <?php if (!empty($Category['hinh'])): ?>
                                <img src="<?php echo $Category['hinh']; ?>" alt="Hình ảnh danh mục" style="width: 100px; height: auto;">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryDescription" class="form-label">Mô Tả</label>
                            <textarea class="form-control" id="editCategoryDescription" rows="3" name="mo_ta" required><?php echo $Category['mo_ta']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryStatus" class="form-label">Trạng Thái</label>
                            <select class="form-select" id="editCategoryStatus" name="trang_thai" required>
                                <option value="1" <?php echo $Category['trang_thai'] == '1' ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="0" <?php echo $Category['trang_thai'] == '0' ? 'selected' : ''; ?>>Ngừng hoạt động</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập Nhật Danh Mục</button>
                        <a href="?act=list-category" class="btn btn-secondary">Quay lại Danh Sách</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>