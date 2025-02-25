<style>
    .container{
        margin-top: 50px;
        width: 700px;
    }
</style>
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">Cập Nhật Thông Tin </h4>
        </div>
        <div class="card-body">
            <form action="index.php?act=updateUser&id=<?= $user['tai_khoan_id'] ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden"  name="hinh_cu" value="<?= $user['hinh'] ?>">
                <div class="row g-4">
                    <!-- Cột trái cho thông tin cá nhân -->
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <label for="ho_va_ten" class="col-md-4 col-form-label">Họ và Tên</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="ho_va_ten" name="ho_va_ten" value="<?= $user['ho_va_ten'] ?>" required disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label">Email</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required disabled >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="mat_khau" class="col-md-4 col-form-label">Mật Khẩu</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="mat_khau" name="mat_khau" value="<?= $user['mat_khau'] ?>" required disabled >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="dia_chi" class="col-md-4 col-form-label">Địa Chỉ</label>
                            <div class="col-md-8">
                                <textarea disabled class="form-control" id="dia_chi" name="dia_chi" rows="2" ><?= $user['dia_chi'] ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="so_dien_thoai" class="col-md-4 col-form-label">Số Điện Thoại</label>
                            <div class="col-md-8">
                                <input disabled type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" value="<?= $user['so_dien_thoai'] ?>" >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="vai_tro" class="col-md-4 col-form-label">Vai Trò</label>
                            <div class="col-md-8">
                                <select disabled class="form-select" id="vai_tro" name="vai_tro" required>
                                    <option value="1" <?= $user['vai_tro'] == 1 ? 'selected' : '' ?>>Quản trị viên</option>
                                    <option value="0" <?= $user['vai_tro'] == 0 ? 'selected' : '' ?>>Khách hàng</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cột phải cho hình ảnh -->
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <div class="avatar-preview mb-3">
                                <?php if (!empty($user['hinh']) && file_exists($user['hinh'])): ?>
                                    <img src="<?= $user['hinh'] ?>" alt="Avatar" class="rounded-circle img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="../Upload/User/default.jpg" alt="Default Avatar" class="rounded-circle img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <label for="hinh" class="form-label">Cập nhật ảnh đại diện</label>
                            <input type="file" class="form-control" id="hinh" name="hinh" >
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save me-2"></i>Cập Nhật
                    </button>
                    <a href="index.php?act=listUser" class="btn btn-secondary px-5 ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
