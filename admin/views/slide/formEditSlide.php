<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <h4 class="fs-16 mb-1">Sửa Slide</h4>
                    <p class="text-muted mb-0">Chỉnh sửa thông tin slide
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="./?act=post-edit-slide" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="editSlideID" class="form-label">ID Slide</label>
                            <input type="text" class="form-control" name="slide_id" id="editSlideID" value="<?php echo $Slide['slide_id']; ?>" required hidden>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="old_img" value="<?php echo $Slide['img']; ?>" hidden>
                            <label for="editSlideImage" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="editSlideImage" name="img">
                            <?php if (!empty($Slide['img'])): ?>
                                <img src="<?php echo $Slide['img']; ?>" alt="Slide" style="width: 100px; height: auto;">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="editSlideStatus" class="form-label">Trạng Thái</label>
                            <select class="form-select" id="editSlideStatus" name="trang_thai" required>
                                <option value="1" <?php echo $Slide['trang_thai'] == '1' ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="0" <?php echo $Slide['trang_thai'] == '0' ? 'selected' : ''; ?>>Ngừng hoạt động</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập Nhật Slide</button>
                        <a href="?act=list-slide" class="btn btn-secondary">Quay lại Danh Sách</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>