<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 700px;
            margin: 20px auto;
            margin-top: 100px;
            padding: 10px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }
        .form-control, .form-select {
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
        .btn-primary {
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 500;
        }
        .title {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .status-group {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        .status-group .form-check {
            margin: 5px 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="form-container">
        <h3 class="text-center title">Thêm Người Dùng</h3>
        <form action="index.php?act=create" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" name="ho_va_ten" placeholder="Nhập họ và tên" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Nhập email" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Mật Khẩu</label>
                    <input type="password" class="form-control" name="mat_khau" placeholder="Nhập mật khẩu" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Số Điện Thoại</label>
                    <input type="text" class="form-control" name="so_dien_thoai" placeholder="Nhập số điện thoại" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Địa Chỉ</label>
                    <input type="text" class="form-control" name="dia_chi" placeholder="Nhập địa chỉ" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Vai Trò</label>
                    <select class="form-select" name="vai_tro" required>
                        <option value="" disabled selected>Chọn vai trò</option>
                        <option value="1">Quản trị viên</option>
                        <option value="0">Khách hàng</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Trạng Thái</label>
                    <div class="status-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="trang_thai" id="active" value="1" checked>
                            <label class="form-check-label" for="active">
                                <i class="fas fa-check-circle text-success"></i> Hoạt động
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="trang_thai" id="inactive" value="0">
                            <label class="form-check-label" for="inactive">
                                <i class="fas fa-times-circle text-danger"></i> Khóa
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Hình Ảnh</label>
                    <input type="file" class="form-control" name="hinh" accept="image/*">
                </div>
                
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus me-2"></i>Thêm Người Dùng
                    </button>
                </div>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
