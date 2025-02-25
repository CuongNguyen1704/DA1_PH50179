<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="container" style="max-width:100%">
                <h1>Danh Sách Dung Lượng</h1>
                <a href="./?act=formAddRam" class="btn btn-primary" style="width: 170px;margin: 10px 0px;">Thêm RAM</a>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Dung lượng</th>
                                    <th>Giá tăng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rams)): ?>
                                    <?php foreach ($rams as $key => $ram): ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td><?= $ram['dung_luong'] ?></td>
                                            <td><?= $ram['gia_tang'] ?></td>
                                            <td><?= $ram['trang_thai'] == 1 ? 'Hoạt động' : 'Ngừng hoạt động' ?></td>
                                            <td>
                                                <a href="./?act=formEditRam&id=<?= $ram['ram_id'] ?>" 
                                                   class="btn btn-warning btn-sm">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- <a href="./?act=deleteRam&id=<?= $ram['ram_id'] ?>"
                                                   onclick="return confirm('Bạn có muốn xóa RAM này không?')"
                                                   class="btn btn-danger btn-sm">
                                                   <i class="fas fa-trash"></i>
                                                </a> -->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>