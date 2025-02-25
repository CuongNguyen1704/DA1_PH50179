<div class="container mt-5">
    <h2 class="text-center mb-4">Thêm Dung lượng</h2>
    <form action="index.php?act=addRam" method="POST" onsubmit="return validateInput()">
        <div class="mb-3">
            <label for="dung_luong" class="form-label">Dung lượng</label>
            <input type="text" class="form-control" id="dung_luong" 
                name="dung_luong" placeholder="Ví dụ: 8GB" required oninput="validateInput()">
            <div id="error-message" class="text-danger" style="display:none;"></div>
        </div>

        <div class="mb-3">
            <label for="mo_ta" class="form-label">Giá tăng</label>
            <input class="form-control" id="gia_tang" name="gia_tang" required></input>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select class="form-select" id="trang_thai" name="trang_thai">
                <option value="1">Hoạt động</option>
                <option value="0">Ngừng hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Dung lượng</button>
        <a href="index.php?act=listRams" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<script>
function validateInput() {
    const input = document.getElementById('dung_luong').value;
    const regex = /^\d+(GB|TB)$/; // Kiểm tra định dạng
    const errorMessage = document.getElementById('error-message');
    if (!regex.test(input)) {
        errorMessage.style.display = 'block';
        errorMessage.innerText = 'Dung lượng RAM phải theo định dạng: Number + "GB/TB"';
        return false; // Ngăn không cho form được gửi
    } else {
        errorMessage.style.display = 'none';
        return true; // Cho phép form được gửi
    }
}
</script>