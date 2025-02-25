<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .container {
            max-width: 100%;
            padding: 10px;
        }

        .table {
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .table th {
            border-bottom: 2px solid #dee2e6;
            padding: 15px;
            font-weight: 600;
            color: #495057;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .user-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-locked {
            background-color: #f8d7da;
            color: #721c24;
        }

        .role-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin {
            background-color: #cce5ff;
            color: #004085;
        }

        .role-user {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
        }

        .add-user-btn {
            padding: 5px 20px;
            border-radius: 5px;
            font-weight: 400;
        }

        /* .add-user-btn i {
            margin-right: 5px;
        } */
        .card-body{
            border: 1px #d2d2d2 solid;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="row">
<div class="col">
        <div class="col-12">
            <h1 class="fs-16 mb-1">Quản lý người dùng</h1>
            <p class="text-muted mb-0">Danh sách người dùng hiện có.</p>
        </div>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
        <div class="card-body">
            <a href="index.php?act=create" class="btn btn-primary add-user-btn mb-3">
                <i class="fas fa-user-plus"></i> Thêm
            </a>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thông tin</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['tai_khoan_id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= $user['hinh'] ?>" alt="User Image" class="user-image me-3">
                                    <div>
                                        <h6 class="mb-0"><?= $user['ho_va_ten'] ?></h6>
                                        <small class="text-muted mt-2">
                                            <i class="fas fa-user-shield me-1 mt-1"></i>
                                            <?= $user['vai_tro'] == 1 ? 'Quản trị viên' : 'Khách hàng' ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-envelope me-1"></i> <?= $user['email'] ?>
                                </div>
                                <div>
                                    <i class="fas fa-phone me-1"></i> <?= $user['so_dien_thoai'] ?>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge <?= $user['vai_tro'] == 1 ? 'role-admin' : 'role-user' ?>">
                                    <?= $user['vai_tro'] == 1 ? 'Quản trị viên' : 'Khách hàng' ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?= $user['trang_thai'] == 1 ? 'status-active' : 'status-locked' ?>">
                                    <?= $user['trang_thai'] == 1 ? 'Hoạt động' : 'Bị khóa' ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?act=viewDetail&id=<?= $user['tai_khoan_id'] ?>"
                                    class="btn btn-info btn-action"
                                    title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- <a href="index.php?act=updateUser&id=<?= $user['tai_khoan_id'] ?>"
                                    class="btn btn-warning btn-action"
                                    title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a> -->
                                <!-- <a href="index.php?act=delete&id=<?= $user['tai_khoan_id'] ?>"
                                    class="btn btn-danger btn-action"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"
                                    title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>