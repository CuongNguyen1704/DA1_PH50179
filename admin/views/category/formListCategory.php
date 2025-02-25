<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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
    </style>
</head>
<body>
<div class="row">
    <div class="col">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <h1 class="fs-16 mb-1">Danh Sách Danh Mục</h1>
                    <p class="text-muted mb-0">Danh sách các danh mục sản phẩm hiện có.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="?act=form-add-category" class="btn btn-primary mb-3">Thêm Danh Mục</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Danh Mục</th>
                                <!-- <th>Hình ảnh</th>
                                <th>Mô Tả</th> -->
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listCategory as $key => $Category) : ?>
                                <tr>
                                    <td><?= $Category['danh_muc_id'] ?></td>
                                    <td><?= $Category['ten_danh_muc'] ?></td>
                                    <!-- <td><img src="<?= $Category['hinh'] ?>" style="width: 100px;"></td>
                                    <td><?= $Category['mo_ta'] ?></td> -->
                                    <td>
                                        <span class="status-badge <?= $Category['trang_thai'] == 1 ? 'status-active' : 'status-locked' ?>">
                                            <?= $Category['trang_thai'] == 1 ? 'Hoạt động' : 'Ngừng hoạt động' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?act=form-edit-category&danh_muc_id=<?= $Category['danh_muc_id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="?act=delete-category&danh_muc_id=<?= $Category['danh_muc_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
