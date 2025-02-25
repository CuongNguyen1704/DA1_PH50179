<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <h4 class="fs-16 mb-1">Thêm Slide</h4>
                    <p class="text-muted mb-0">Thêm slide mới.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="?act=post-add-slide" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="slideImage" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="slideImage" name="img"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="slideStatus" class="form-label">Trạng Thái</label>
                            <select class="form-select" id="slideStatus" name="trang_thai"
                                required>
                                <option value="1">Hoạt động</option>
                                <option value="0">Ngừng hoạt động</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm Slide</button>
                        <a href="?act=list-slide" class="btn btn-secondary">Quay lại Danh Sách</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>