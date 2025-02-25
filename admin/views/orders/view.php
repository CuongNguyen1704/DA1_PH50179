<?php
// Kiểm tra và lấy dữ liệu từ biến $data
$order = $data['order'] ?? null;
$items = $data['items'] ?? [];
$currentStatus = $data['currentStatus'] ?? null;
$cancelReason = $data['cancelReason'] ?? null;

// Hiển thị thông báo lỗi nếu có
if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}

if (!$order || empty($items)) {
    echo "Không có dữ liệu đơn hàng";
    return;
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #<?= $order['don_hang_id'] ?></h1>
        <a href="index.php?act=list-orders" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">Khách hàng:</th>
                            <td><?= $order['ho_va_ten'] ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= $order['email'] ?></td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td><?= $order['so_dien_thoai'] ?></td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td><?= $order['dia_chi'] ?></td>
                        </tr>
                        <tr>
                            <th>Ngày đặt:</th>
                            <td><?= date('d/m/Y', strtotime($order['ngay_dat'])) ?></td>
                        </tr>
                        <tr>
                            <th>Phương thức thanh toán:</th>
                            <td>
                                <?php if ($order['phuong_thuc_thanh_toan'] == 'online'): ?>
                                    <span class="btn btn-success">Thanh toán Online</span>
                                <?php else: ?>
                                    <span class="btn btn-info">Thanh toán khi nhận hàng (COD)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <form action="index.php?act=update-order-status" method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?= $order['don_hang_id'] ?>">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="1" <?= (!$order['trang_thai'] || $order['trang_thai'] == 1) ? 'selected' : '' ?>>Chờ xử lý</option>
                                        <option value="2" <?= $order['trang_thai'] == 2 ? 'selected' : '' ?>>Đã xác nhận</option>
                                        <option value="3" <?= $order['trang_thai'] == 3 ? 'selected' : '' ?>>Đang giao</option>
                                        <option value="4" <?= $order['trang_thai'] == 4 ? 'selected' : '' ?>>Đã hoàn thành</option>
                                        <option value="5" <?= $order['trang_thai'] == 5 ? 'selected' : '' ?> <?= !in_array($order['trang_thai'], [1, 2]) ? 'disabled' : '' ?>>Đã hủy</option>
                                    </select>
                                    <?php if (in_array($order['trang_thai'], [1, 2])): ?>
                                        <input type="text" name="reason" placeholder="Nhập lý do hủy" class="form-control mt-2" />
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <?php if ($currentStatus == 5 && !empty($cancelReason)): ?>
                        <tr>
                            <th>Lý do hủy:</th>
                            <td><?= htmlspecialchars($cancelReason) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết sản phẩm</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['hinh'])): ?>
                                                    <img src="<?= $item['hinh'] ?>" class="product-img mr-2" alt="<?= $item['ten_san_pham'] ?>">
                                                <?php endif; ?>
                                                <div>
                                                    <div class="font-weight-bold"><?= $item['ten_san_pham'] ?></div>
                                                    <small class="text-muted">Dung lượng: <?= $item['dung_luong'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $item['so_luong'] ?></td>
                                        <td class="text-right">
                                            <?php
                                            $don_gia = $item['gia_goc'] + $item['gia_tang'];
                                            echo number_format($don_gia, 0, ',', '.');
                                            ?>đ
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $thanh_tien = ($item['gia_goc'] + $item['gia_tang']) * $item['so_luong'];
                                            echo number_format($thanh_tien, 0, ',', '.');
                                            ?>đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tạm tính:</strong></td>
                                    <td class="text-right">
                                        <strong><?= number_format($order['tong_tien'] - 30000, 0, ',', '.') ?>đ</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Phí vận chuyển:</strong></td>
                                    <td class="text-right">
                                        <strong>30.000đ</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                    <td class="text-right">
                                        <strong class="text-primary"><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</strong>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .btn {
        padding: 8px 12px;
        font-weight: 500;
    }

    .table-borderless td,
    .table-borderless th {
        border: 0;
        padding: 12px 8px;
    }

    .form-control {
        border-radius: 4px;
    }

    tfoot tr td {
        border-top: 2px solid #dee2e6;
    }

    .text-primary {
        color: #4e73df !important;
    }

    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
    }
</style>