<div class="container mt-5">
    <h2 class="text-center mb-4">Sửa Dung Lượng</h2>
    <form action="index.php?act=editRam" method="POST">
        <input type="hidden" name="ram_id" value="<?= $ram['ram_id'] ?>">
        
        <div class="mb-3">
            <label for="dung_luong" class="form-label">Dung lượng</label>
            <input type="text" class="form-control" id="dung_luong" 
                name="dung_luong" value="<?= $ram['dung_luong'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="mo_ta" class="form-label">Mô tả</label>
            <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"><?= $ram['mo_ta'] ?></textarea>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select class="form-select" id="trang_thai" name="trang_thai">
                <option value="1" <?= $ram['trang_thai'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                <option value="0" <?= $ram['trang_thai'] == 0 ? 'selected' : '' ?>>Ngừng hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="?act=listRams" class="btn btn-secondary">Quay lại</a>
    </form>
</div> 