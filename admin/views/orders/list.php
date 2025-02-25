<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Quản lý đơn hàng</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="index.php" method="GET" class="form-inline justify-content-end">
                <input type="hidden" name="act" value="list-orders">
                <div class="input-group">
                    <input type="text" class="form-control" name="keyword" 
                           placeholder="Tìm theo mã đơn, tên, email, SĐT..." 
                           value="<?= isset($_GET['keyword']) ? ($_GET['keyword']) : '' ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php if(isset($_GET['keyword']) && !empty($_GET['keyword'])): ?>
                <div class="mb-3">
                    <p class="mb-0">Kết quả tìm kiếm cho: <strong><?= ($_GET['keyword']) ?></strong></p>
                    <a href="index.php?act=list-orders" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </a>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                          
                            
                            <tr>
                                <td>#<?= $order['don_hang_id'] ?></td>
                                <td><?= $order['ho_va_ten'] ?></td>
                                <td><?= date('d/m/Y', strtotime($order['ngay_dat'])) ?></td>
                                <td><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</td>
                                <td>
                                    <?php
                                    $trang_thai = [
                                        1 => '<span class="badge bg-warning">Chờ xử lý</span>',
                                        2 => '<span class="badge bg-primary">Đã xác nhận</span>',
                                        3 => '<span class="badge bg-info">Đang giao</span>',
                                        4 => '<span class="badge bg-success">Đã hoàn thành</span>',
                                        5 => '<span class="badge bg-danger">Đã hủy</span>'
                                    ];
                                    echo $trang_thai[$order['trang_thai']] ?? $trang_thai[1];
                                    ?>
                                </td>
                                <td>
                                    <a href="index.php?act=view-order&id=<?= $order['don_hang_id'] ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>