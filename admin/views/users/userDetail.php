<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .user-detail-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .user-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="user-detail-card text-center">
                    <img src="<?= $user['hinh'] ?>" class="user-avatar" alt="User Avatar">
                    <h4><?= $user['ho_va_ten'] ?></h4>
                    <span class="badge <?= $user['vai_tro'] == 1 ? 'bg-primary' : 'bg-secondary' ?>">
                        <?= $user['vai_tro'] == 1 ? 'Quản trị viên' : 'Khách hàng' ?>
                    </span>
                    <span class="badge <?= $user['trang_thai'] == 1 ? 'bg-success' : 'bg-danger' ?> ms-2">
                        <?= $user['trang_thai'] == 1 ? 'Hoạt động' : 'Bị khóa' ?>
                    </span>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="user-detail-card">
                    <h5 class="mb-4">Thông tin chi tiết</h5>
                    <div class="row mb-3">
                        <div class="col-md-4 info-label">Email:</div>
                        <div class="col-md-8"><?= $user['email'] ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 info-label">Số điện thoại:</div>
                        <div class="col-md-8"><?= $user['so_dien_thoai'] ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 info-label">Địa chỉ:</div>
                        <div class="col-md-8"><?= $user['dia_chi'] ?></div>
                    </div>
                </div>

                <?php if (!empty($userOrders)): ?>
                <div class="user-detail-card">
                    <h5 class="mb-4">Lịch sử đơn hàng</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userOrders as $order): ?>
                                <tr>
                                    <td>#<?= $order['don_hang_id'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($order['ngay_dat'])) ?></td>
                                    <td><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</td>
                                    <td>
                                        <?php
                                        $status = '';
                                        switch($order['trang_thai']) {
                                            case 1: $status = '<span class="badge bg-warning">Chờ xác nhận</span>'; break;
                                            case 2: $status = '<span class="badge bg-info">Đang xử lý</span>'; break;
                                            case 3: $status = '<span class="badge bg-success">Hoàn thành</span>'; break;
                                            case 4: $status = '<span class="badge bg-danger">Đã hủy</span>'; break;
                                        }
                                        echo $status;
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php?act=listUser" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 