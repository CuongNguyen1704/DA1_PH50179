<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-locked {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <h1 class="fs-16 mb-1">List Comments</h1>
                        <p class="text-muted mb-0">List comments now !!</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Product</th>
                                    <th>Content</th>
                                    <th>Date Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listComment as $key => $Comment) : ?>
                                    <tr>
                                        <td><?= $Comment['binh_luan_id'] ?></td>
                                        <td><?= $Comment['ho_va_ten'] ?></td>
                                        <td><?= $Comment['ten_san_pham'] ?></td>
                                        <td><?= $Comment['noi_dung'] ?></td>
                                        <td><?= $Comment['ngay_binh_luan'] ?></td>
                                        <td>
                                            <a href="?act=deleteComments&binh_luan_id=<?php echo $Comment['binh_luan_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash"></i></a>
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